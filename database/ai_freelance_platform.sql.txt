-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2026 at 06:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ai_freelance_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_service`
--

CREATE TABLE `ai_service` (
  `ServiceID` int(11) NOT NULL,
  `ServiceName` varchar(120) NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ai_service`
--

INSERT INTO `ai_service` (`ServiceID`, `ServiceName`, `Description`) VALUES
(1, 'Custom Predictive Model Development', 'End-to-end training and evaluation of custom machine learning models'),
(2, 'RAG & Document Q&A System', 'Retrieval-Augmented Generation chatbot over enterprise PDFs and databases'),
(3, 'Automated Invoice & Receipt OCR', 'Optical character recognition pipeline to extract structured data from documents'),
(4, 'Customer Support AI Agent', '24/7 intelligent support bot integrated with web and messaging channels'),
(5, 'Fraud & Anomaly Detection Engine', 'Real-time transaction monitoring to flag unusual patterns'),
(6, 'Product Defect Visual Inspection', 'Computer vision quality control system for manufacturing lines'),
(7, 'Sales & Inventory Forecasting', 'Time-series forecasting models for demand planning and stock optimization'),
(8, 'Voice Transcription & Analytics', 'Speech-to-text conversion with call summary and sentiment tagging'),
(9, 'Recommendation System', 'Personalized product or content recommendation engine'),
(10, 'AI Model Deployment & Monitoring', 'Productionizing trained models with APIs, Docker, and drift alerts');

-- --------------------------------------------------------

--
-- Table structure for table `ai_skill`
--

CREATE TABLE `ai_skill` (
  `SkillID` int(11) NOT NULL,
  `SkillName` varchar(100) NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ai_skill`
--

INSERT INTO `ai_skill` (`SkillID`, `SkillName`, `Description`) VALUES
(1, 'Machine Learning', 'Supervised and unsupervised predictive modeling using Python and scikit-learn'),
(2, 'Natural Language Processing', 'Text classification, sentiment analysis, summarization, and language models'),
(3, 'Computer Vision', 'Image recognition, object detection, OCR, and video analytics'),
(4, 'Deep Learning', 'Neural networks, CNNs, RNNs, and transformers using PyTorch or TensorFlow'),
(5, 'Generative AI', 'LLM fine-tuning, RAG pipelines, and prompt engineering'),
(6, 'Data Science', 'Exploratory data analysis, statistical modeling, and data visualization'),
(7, 'Chatbot Development', 'Conversational AI assistants and customer support automation'),
(8, 'Speech Recognition', 'Automatic speech recognition and voice-to-text processing'),
(9, 'Time Series Forecasting', 'Demand forecasting, anomaly detection, and financial trend prediction'),
(10, 'MLOps', 'Model deployment, CI/CD pipelines, monitoring, and containerization');

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `AssignmentID` int(11) NOT NULL,
  `ProposalID` int(11) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date DEFAULT NULL,
  `Status` varchar(30) NOT NULL DEFAULT 'Active'
) ;

--
-- Dumping data for table `assignment`
--

INSERT INTO `assignment` (`AssignmentID`, `ProposalID`, `StartDate`, `EndDate`, `Status`) VALUES
(1, 2, '2026-11-03', NULL, 'Active'),
(2, 4, '2026-10-12', '2026-11-15', 'Completed'),
(3, 6, '2026-11-07', NULL, 'Active'),
(4, 8, '2026-10-14', '2026-11-18', 'Completed'),
(5, 10, '2026-11-13', NULL, 'Active'),
(6, 1, '2026-11-04', NULL, 'On Hold'),
(7, 3, '2026-11-05', NULL, 'Active'),
(8, 5, '2026-11-07', NULL, 'Active'),
(9, 7, '2026-11-09', NULL, 'Active'),
(10, 9, '2026-11-11', NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `ClientID` int(11) NOT NULL,
  `ClientName` varchar(100) NOT NULL,
  `Email` varchar(120) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `OrganizationName` varchar(120) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`ClientID`, `ClientName`, `Email`, `Phone`, `OrganizationName`, `CreatedAt`) VALUES
(1, 'Kasun Perera', 'kasun.perera@lankatech.lk', '+94771234501', 'LankaTech Solutions', '2026-11-27 05:23:18'),
(2, 'Nimali Silva', 'nimali.silva@ceylonretail.lk', '+94771234502', 'Ceylon Retail Group', '2026-11-27 05:23:18'),
(3, 'Dilshan Fernando', 'dilshan@medicareai.io', '+94771234503', 'MediCare AI', '2026-11-27 05:23:18'),
(4, 'Tharindu Jayawardena', 'tharindu@finpulse.com', '+94771234504', 'FinPulse Analytics', '2026-11-27 05:23:18'),
(5, 'Sachini Wijesinghe', 'sachini@edusmart.lk', '+94771234505', 'EduSmart Lanka', '2026-11-27 05:23:18'),
(6, 'Roshan Gunasekara', 'roshan@agrosense.lk', '+94771234506', 'AgroSense Innovations', '2026-11-27 05:23:18'),
(7, 'Ayesha Rathnayake', 'ayesha@travelmate.ai', '+94771234507', 'TravelMate AI', '2026-11-27 05:23:18'),
(8, 'Kavindu Bandara', 'kavindu@logixflow.com', '+94771234508', 'LogixFlow Systems', '2026-11-27 05:23:18'),
(9, 'Dinithi Senanayake', 'dinithi@carebot.org', '+94771234509', 'CareBot Foundation', '2026-11-27 05:23:18'),
(10, 'Shehan Madushanka', 'shehan@smartgrid.lk', '+94771234510', 'SmartGrid Energy', '2026-11-27 05:23:18');

-- --------------------------------------------------------

--
-- Table structure for table `freelancer`
--

CREATE TABLE `freelancer` (
  `FreelancerID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(120) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Location` varchar(100) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `freelancer`
--

INSERT INTO `freelancer` (`FreelancerID`, `Name`, `Email`, `Phone`, `Location`, `CreatedAt`) VALUES
(1, 'Amila Dissanayake', 'amila.ml@aistudio.lk', '+94712234501', 'Colombo', '2026-11-27 05:23:18'),
(2, 'Sanduni Herath', 'sanduni.nlp@aistudio.lk', '+94712234502', 'Kandy', '2026-11-27 05:23:18'),
(3, 'Lahiru Rajapaksha', 'lahiru.cv@aistudio.lk', '+94712234503', 'Galle', '2026-11-27 05:23:18'),
(4, 'Hashini Karunaratne', 'hashini.genai@aistudio.lk', '+94712234504', 'Colombo', '2026-11-27 05:23:18'),
(5, 'Nuwan Kumara', 'nuwan.ds@aistudio.lk', '+94712234505', 'Negombo', '2026-11-27 05:23:18'),
(6, 'Malithi Samarasinghe', 'malithi.ai@aistudio.lk', '+94712234506', 'Matara', '2026-11-27 05:23:18'),
(7, 'Chathura Ekanayake', 'chathura.dl@aistudio.lk', '+94712234507', 'Jaffna', '2026-11-27 05:23:18'),
(8, 'Ishara Gunawardena', 'ishara.bot@aistudio.lk', '+94712234508', 'Kurunegala', '2026-11-27 05:23:18'),
(9, 'Pradeep Rathnayake', 'pradeep.mlops@aistudio.lk', '+94712234509', 'Ratnapura', '2026-11-27 05:23:18'),
(10, 'Thilini Peiris', 'thilini.voice@aistudio.lk', '+94712234510', 'Moratuwa', '2026-11-27 05:23:18');

-- --------------------------------------------------------

--
-- Table structure for table `freelancer_service`
--

CREATE TABLE `freelancer_service` (
  `FreelancerID` int(11) NOT NULL,
  `ServiceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `freelancer_service`
--

INSERT INTO `freelancer_service` (`FreelancerID`, `ServiceID`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 2),
(5, 5),
(6, 7),
(7, 6),
(8, 4),
(9, 10),
(10, 8);

-- --------------------------------------------------------

--
-- Table structure for table `freelancer_skill`
--

CREATE TABLE `freelancer_skill` (
  `FreelancerID` int(11) NOT NULL,
  `SkillID` int(11) NOT NULL,
  `ProficiencyLevel` varchar(30) NOT NULL DEFAULT 'Intermediate'
) ;

--
-- Dumping data for table `freelancer_skill`
--

INSERT INTO `freelancer_skill` (`FreelancerID`, `SkillID`, `ProficiencyLevel`) VALUES
(1, 1, 'Expert'),
(1, 6, 'Advanced'),
(2, 2, 'Expert'),
(2, 7, 'Advanced'),
(3, 3, 'Expert'),
(4, 5, 'Expert'),
(5, 6, 'Expert'),
(6, 9, 'Advanced'),
(7, 4, 'Expert'),
(8, 7, 'Expert');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PaymentID` int(11) NOT NULL,
  `AssignmentID` int(11) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `PaymentDate` date NOT NULL,
  `PaymentStatus` varchar(30) NOT NULL DEFAULT 'Pending'
) ;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`PaymentID`, `AssignmentID`, `Amount`, `PaymentDate`, `PaymentStatus`) VALUES
(1, 2, 1375.00, '2026-10-12', 'Completed'),
(2, 2, 1375.00, '2026-11-15', 'Completed'),
(3, 4, 1025.00, '2026-10-14', 'Completed'),
(4, 4, 1025.00, '2026-11-18', 'Completed'),
(5, 1, 1075.00, '2026-11-03', 'Completed'),
(6, 3, 1175.00, '2026-11-07', 'Completed'),
(7, 5, 1550.00, '2026-11-13', 'Completed'),
(8, 6, 500.00, '2026-11-05', 'Pending'),
(9, 7, 1000.00, '2026-11-06', 'Pending'),
(10, 8, 750.00, '2026-11-08', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `ProjectID` int(11) NOT NULL,
  `ClientID` int(11) NOT NULL,
  `ProjectTitle` varchar(150) NOT NULL,
  `Description` text NOT NULL,
  `Budget` decimal(10,2) NOT NULL,
  `Status` varchar(30) NOT NULL DEFAULT 'Open',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`ProjectID`, `ClientID`, `ProjectTitle`, `Description`, `Budget`, `Status`, `CreatedAt`) VALUES
(1, 1, 'Customer Churn Prediction Model', 'Build a ML model to predict telecom and enterprise customer churn.', 1800.00, 'Open', '2026-11-27 05:23:19'),
(2, 2, 'Sinhala & English Support Chatbot', 'Create an AI assistant to answer customer queries on an e-commerce site.', 2200.00, 'In Progress', '2026-11-27 05:23:19'),
(3, 3, 'Medical Scan Image Classifier', 'Develop a deep learning model to assist preliminary scan screening.', 3500.00, 'Open', '2026-11-27 05:23:19'),
(4, 4, 'Credit Card Fraud Detection', 'Real-time anomaly detection pipeline for financial transactions.', 2800.00, 'Completed', '2026-11-27 05:23:19'),
(5, 5, 'Automated Quiz & Essay Grader', 'NLP tool to assist teachers with short-answer evaluation.', 1600.00, 'Open', '2026-11-27 05:23:19'),
(6, 6, 'Crop Disease Detection from Leaf Photos', 'Computer vision mobile backend to identify plant diseases.', 2400.00, 'In Progress', '2026-11-27 05:23:19'),
(7, 7, 'AI Itinerary Planner for Tourists', 'Generative AI travel assistant recommending custom Sri Lanka itineraries.', 1950.00, 'Open', '2026-11-27 05:23:19'),
(8, 8, 'Warehouse Demand Forecasting', 'Forecast weekly inventory demand across regional distribution centers.', 2100.00, 'Completed', '2026-11-27 05:23:19'),
(9, 9, 'Mental Health Helpline Triage Bot', 'Empathetic conversational triage assistant with escalation rules.', 2600.00, 'Open', '2026-11-27 05:23:19'),
(10, 10, 'Smart Grid Load Forecasting', 'Predict peak electricity demand using historical weather and usage data.', 3200.00, 'In Progress', '2026-11-27 05:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `project_requirement`
--

CREATE TABLE `project_requirement` (
  `RequirementID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `SkillID` int(11) NOT NULL,
  `RequirementDetails` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_requirement`
--

INSERT INTO `project_requirement` (`RequirementID`, `ProjectID`, `SkillID`, `RequirementDetails`) VALUES
(1, 1, 1, 'Model accuracy above 88% with ROC-AUC evaluation report'),
(2, 1, 6, 'Feature engineering and customer cohort analysis included'),
(3, 2, 2, 'Support bilingual intents and fallback to human agent'),
(4, 2, 7, 'REST API integration ready for web widget'),
(5, 3, 3, 'High recall rate with explainable Grad-CAM heatmaps'),
(6, 4, 9, 'Low-latency scoring under 100ms per transaction'),
(7, 5, 5, 'Rubric-based scoring with transparent feedback notes'),
(8, 6, 4, 'Quantized model suitable for low-bandwidth mobile inference'),
(9, 7, 5, 'Grounded responses using verified destination data'),
(10, 8, 10, 'Automated weekly retraining pipeline and dashboard');

-- --------------------------------------------------------

--
-- Table structure for table `proposal`
--

CREATE TABLE `proposal` (
  `ProposalID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `FreelancerID` int(11) NOT NULL,
  `ProposalAmount` decimal(10,2) NOT NULL,
  `ProposalDate` date NOT NULL,
  `Status` varchar(30) NOT NULL DEFAULT 'Pending'
) ;

--
-- Dumping data for table `proposal`
--

INSERT INTO `proposal` (`ProposalID`, `ProjectID`, `FreelancerID`, `ProposalAmount`, `ProposalDate`, `Status`) VALUES
(1, 1, 1, 1750.00, '2026-11-01', 'Pending'),
(2, 2, 2, 2150.00, '2026-11-02', 'Accepted'),
(3, 3, 3, 3400.00, '2026-11-03', 'Pending'),
(4, 4, 5, 2750.00, '2026-10-10', 'Accepted'),
(5, 5, 4, 1550.00, '2026-11-05', 'Pending'),
(6, 6, 7, 2350.00, '2026-11-06', 'Accepted'),
(7, 7, 4, 1900.00, '2026-11-08', 'Pending'),
(8, 8, 6, 2050.00, '2026-10-12', 'Accepted'),
(9, 9, 8, 2500.00, '2026-11-10', 'Pending'),
(10, 10, 9, 3100.00, '2026-11-12', 'Accepted');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `ReviewID` int(11) NOT NULL,
  `AssignmentID` int(11) NOT NULL,
  `Rating` int(11) NOT NULL,
  `Comment` text DEFAULT NULL,
  `ReviewType` varchar(30) NOT NULL DEFAULT 'Client to Freelancer',
  `ReviewDate` date NOT NULL
) ;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`ReviewID`, `AssignmentID`, `Rating`, `Comment`, `ReviewType`, `ReviewDate`) VALUES
(1, 2, 5, 'Great fraud detection accuracy and clean documentation.', 'Client to Freelancer', '2026-11-16'),
(2, 2, 5, 'Clear requirements and prompt milestone approvals.', 'Freelancer to Client', '2026-11-16'),
(3, 4, 5, 'Forecasting model helped reduce stockouts significantly.', 'Client to Freelancer', '2026-11-19'),
(4, 4, 4, 'Good collaboration; dataset needed minor cleaning.', 'Freelancer to Client', '2026-11-19'),
(5, 1, 4, 'Strong early prototype for the bilingual chatbot.', 'Client to Freelancer', '2026-11-14'),
(6, 3, 5, 'Leaf disease classifier works smoothly on test images.', 'Client to Freelancer', '2026-11-17'),
(7, 5, 4, 'Solid load forecasting baseline with clear API endpoints.', 'Client to Freelancer', '2026-11-20'),
(8, 6, 4, 'Well-structured project brief and timely responses.', 'Freelancer to Client', '2026-11-12'),
(9, 7, 5, 'Helpful feedback during model validation phase.', 'Freelancer to Client', '2026-11-15'),
(10, 8, 4, 'Smooth communication throughout the sprint.', 'Freelancer to Client', '2026-11-18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_service`
--
ALTER TABLE `ai_service`
  ADD PRIMARY KEY (`ServiceID`),
  ADD UNIQUE KEY `ServiceName` (`ServiceName`);

--
-- Indexes for table `ai_skill`
--
ALTER TABLE `ai_skill`
  ADD PRIMARY KEY (`SkillID`),
  ADD UNIQUE KEY `SkillName` (`SkillName`);

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`AssignmentID`),
  ADD UNIQUE KEY `ProposalID` (`ProposalID`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`ClientID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `freelancer`
--
ALTER TABLE `freelancer`
  ADD PRIMARY KEY (`FreelancerID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `freelancer_service`
--
ALTER TABLE `freelancer_service`
  ADD PRIMARY KEY (`FreelancerID`,`ServiceID`),
  ADD KEY `fk_fservice_service` (`ServiceID`);

--
-- Indexes for table `freelancer_skill`
--
ALTER TABLE `freelancer_skill`
  ADD PRIMARY KEY (`FreelancerID`,`SkillID`),
  ADD KEY `fk_fskill_skill` (`SkillID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `fk_payment_assignment` (`AssignmentID`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`ProjectID`),
  ADD KEY `fk_project_client` (`ClientID`);

--
-- Indexes for table `project_requirement`
--
ALTER TABLE `project_requirement`
  ADD PRIMARY KEY (`RequirementID`),
  ADD UNIQUE KEY `uq_project_skill_req` (`ProjectID`,`SkillID`),
  ADD KEY `fk_req_skill` (`SkillID`);

--
-- Indexes for table `proposal`
--
ALTER TABLE `proposal`
  ADD PRIMARY KEY (`ProposalID`),
  ADD KEY `fk_proposal_project` (`ProjectID`),
  ADD KEY `fk_proposal_freelancer` (`FreelancerID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `fk_review_assignment` (`AssignmentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_service`
--
ALTER TABLE `ai_service`
  MODIFY `ServiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ai_skill`
--
ALTER TABLE `ai_skill`
  MODIFY `SkillID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `ClientID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `freelancer`
--
ALTER TABLE `freelancer`
  MODIFY `FreelancerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `ProjectID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_requirement`
--
ALTER TABLE `project_requirement`
  MODIFY `RequirementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `proposal`
--
ALTER TABLE `proposal`
  MODIFY `ProposalID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `ReviewID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `fk_assignment_proposal` FOREIGN KEY (`ProposalID`) REFERENCES `proposal` (`ProposalID`) ON UPDATE CASCADE;

--
-- Constraints for table `freelancer_service`
--
ALTER TABLE `freelancer_service`
  ADD CONSTRAINT `fk_fservice_freelancer` FOREIGN KEY (`FreelancerID`) REFERENCES `freelancer` (`FreelancerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fservice_service` FOREIGN KEY (`ServiceID`) REFERENCES `ai_service` (`ServiceID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `freelancer_skill`
--
ALTER TABLE `freelancer_skill`
  ADD CONSTRAINT `fk_fskill_freelancer` FOREIGN KEY (`FreelancerID`) REFERENCES `freelancer` (`FreelancerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fskill_skill` FOREIGN KEY (`SkillID`) REFERENCES `ai_skill` (`SkillID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_assignment` FOREIGN KEY (`AssignmentID`) REFERENCES `assignment` (`AssignmentID`) ON UPDATE CASCADE;

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_project_client` FOREIGN KEY (`ClientID`) REFERENCES `client` (`ClientID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_requirement`
--
ALTER TABLE `project_requirement`
  ADD CONSTRAINT `fk_req_project` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_req_skill` FOREIGN KEY (`SkillID`) REFERENCES `ai_skill` (`SkillID`) ON UPDATE CASCADE;

--
-- Constraints for table `proposal`
--
ALTER TABLE `proposal`
  ADD CONSTRAINT `fk_proposal_freelancer` FOREIGN KEY (`FreelancerID`) REFERENCES `freelancer` (`FreelancerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_proposal_project` FOREIGN KEY (`ProjectID`) REFERENCES `project` (`ProjectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_assignment` FOREIGN KEY (`AssignmentID`) REFERENCES `assignment` (`AssignmentID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
