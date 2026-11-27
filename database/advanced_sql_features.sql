USE `ai_freelance_platform`;

DELIMITER //

 Status to 'In Progress'
DROP TRIGGER IF EXISTS `trg_after_assignment_insert` //
CREATE TRIGGER `trg_after_assignment_insert`
AFTER INSERT ON `assignment`
FOR EACH ROW
BEGIN
    DECLARE v_project_id INT;

    SELECT `ProjectID`
    INTO v_project_id
    FROM `proposal`
    WHERE `ProposalID` = NEW.`ProposalID`
    LIMIT 1;

    IF v_project_id IS NOT NULL AND NEW.`Status` = 'Active' THEN
        UPDATE `project`
        SET `Status` = 'In Progress'
        WHERE `ProjectID` = v_project_id;
    END IF;
END //


CREATE TRIGGER `trg_after_assignment_complete`
AFTER UPDATE ON `assignment`
FOR EACH ROW
BEGIN
    DECLARE v_project_id INT;

    IF NEW.`Status` = 'Completed' AND (OLD.`Status` IS NULL OR OLD.`Status` <> 'Completed') THEN
        SELECT `ProjectID`
        INTO v_project_id
        FROM `proposal`
        WHERE `ProposalID` = NEW.`ProposalID`
        LIMIT 1;

        IF v_project_id IS NOT NULL THEN
            UPDATE `project`
            SET `Status` = 'Completed'
            WHERE `ProjectID` = v_project_id;
        END IF;
    END IF;
END //


DROP FUNCTION IF EXISTS `fn_calculate_platform_fee` //
CREATE FUNCTION `fn_calculate_platform_fee`(
    p_amount DECIMAL(10,2),
    p_fee_rate DECIMAL(5,4)
) RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE v_rate DECIMAL(5,4);
    IF p_amount IS NULL OR p_amount <= 0 THEN
        RETURN 0.00;
    END IF;
    SET v_rate = IFNULL(p_fee_rate, 0.1000);
    RETURN ROUND(p_amount * v_rate, 2);
END //

DROP FUNCTION IF EXISTS `fn_get_freelancer_avg_rating` //
CREATE FUNCTION `fn_get_freelancer_avg_rating`(
    p_freelancer_id INT
) RETURNS DECIMAL(3,2)
READS SQL DATA
BEGIN
    DECLARE v_avg_rating DECIMAL(3,2);

    SELECT ROUND(AVG(r.`Rating`), 2)
    INTO v_avg_rating
    FROM `review` r
    JOIN `assignment` a ON r.`AssignmentID` = a.`AssignmentID`
    JOIN `proposal` p ON a.`ProposalID` = p.`ProposalID`
    WHERE p.`FreelancerID` = p_freelancer_id
      AND r.`ReviewType` = 'Client to Freelancer';

    RETURN IFNULL(v_avg_rating, 0.00);
END //

DROP VIEW IF EXISTS `vw_freelancer_summary` //
CREATE VIEW `vw_freelancer_summary` AS
SELECT
    f.`FreelancerID`,
    f.`Name` AS `FreelancerName`,
    f.`Location`,
    COUNT(DISTINCT pr.`ProposalID`) AS `TotalProposals`,
    COUNT(DISTINCT a.`AssignmentID`) AS `TotalAssignments`,
    IFNULL(SUM(CASE WHEN pay.`PaymentStatus` = 'Completed' THEN pay.`Amount` ELSE 0 END), 0.00) AS `TotalCompletedEarnings`,
    `fn_get_freelancer_avg_rating`(f.`FreelancerID`) AS `AvgClientRating`
FROM `freelancer` f
LEFT JOIN `proposal` pr ON f.`FreelancerID` = pr.`FreelancerID`
LEFT JOIN `assignment` a ON pr.`ProposalID` = a.`ProposalID`
LEFT JOIN `payment` pay ON a.`AssignmentID` = pay.`AssignmentID`
GROUP BY f.`FreelancerID`, f.`Name`, f.`Location` //

DROP VIEW IF EXISTS `vw_project_payment_overview` //
CREATE VIEW `vw_project_payment_overview` AS
SELECT
    p.`ProjectID`,
    p.`ProjectTitle`,
    c.`ClientName`,
    c.`OrganizationName`,
    p.`Budget`,
    p.`Status` AS `ProjectStatus`,
    f.`Name` AS `AssignedFreelancer`,
    IFNULL(SUM(pay.`Amount`), 0.00) AS `TotalRecordedPayments`,
    ROUND(p.`Budget` - IFNULL(SUM(pay.`Amount`), 0.00), 2) AS `RemainingBudget`
FROM `project` p
JOIN `client` c ON p.`ClientID` = c.`ClientID`
LEFT JOIN `proposal` pr ON p.`ProjectID` = pr.`ProjectID` AND pr.`Status` = 'Accepted'
LEFT JOIN `assignment` a ON pr.`ProposalID` = a.`ProposalID`
LEFT JOIN `payment` pay ON a.`AssignmentID` = pay.`AssignmentID`
LEFT JOIN `freelancer` f ON pr.`FreelancerID` = f.`FreelancerID`
GROUP BY
    p.`ProjectID`, p.`ProjectTitle`, c.`ClientName`, c.`OrganizationName`,
    p.`Budget`, p.`Status`, f.`Name` //

DROP PROCEDURE IF EXISTS `sp_create_ai_project` //
CREATE PROCEDURE `sp_create_ai_project`(
    IN p_client_id INT,
    IN p_title VARCHAR(150),
    IN p_description TEXT,
    IN p_budget DECIMAL(10,2),
    IN p_skill_id INT,
    IN p_req_details TEXT
)
BEGIN
    DECLARE v_new_project_id INT;

    IF NOT EXISTS (SELECT 1 FROM `client` WHERE `ClientID` = p_client_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid ClientID: Client does not exist.';
    END IF;

    IF p_budget <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Budget must be greater than 0.';
    END IF;

    INSERT INTO `project` (`ClientID`, `ProjectTitle`, `Description`, `Budget`, `Status`)
    VALUES (p_client_id, p_title, p_description, p_budget, 'Open');

    SET v_new_project_id = LAST_INSERT_ID();

    IF p_skill_id IS NOT NULL THEN
        INSERT INTO `project_requirement` (`ProjectID`, `SkillID`, `RequirementDetails`)
        VALUES (
            v_new_project_id,
            p_skill_id,
            IFNULL(p_req_details, 'Required AI skill for this project')
        );
    END IF;

    SELECT
        v_new_project_id AS `CreatedProjectID`,
        p_client_id AS `ClientID`,
        p_title AS `ProjectTitle`,
        p_budget AS `Budget`,
        'Open' AS `Status`;
END //

DROP PROCEDURE IF EXISTS `sp_find_freelancers_by_skill` //
CREATE PROCEDURE `sp_find_freelancers_by_skill`(
    IN p_skill_name VARCHAR(100)
)
BEGIN
    SELECT
        f.`FreelancerID`,
        f.`Name` AS `FreelancerName`,
        f.`Email`,
        f.`Location`,
        s.`SkillName`,
        fs.`ProficiencyLevel`,
        `fn_get_freelancer_avg_rating`(f.`FreelancerID`) AS `AvgClientRating`
    FROM `freelancer` f
    JOIN `freelancer_skill` fs ON f.`FreelancerID` = fs.`FreelancerID`
    JOIN `ai_skill` s ON fs.`SkillID` = s.`SkillID`
    WHERE s.`SkillName` LIKE CONCAT('%', p_skill_name, '%')
    ORDER BY `AvgClientRating` DESC, f.`Name` ASC;
END //

DELIMITER ;