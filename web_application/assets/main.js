function filterProjects() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.getElementsByClassName('project-card');

    for (let i = 0; i < cards.length; i++) {
        const text = cards[i].innerText.toLowerCase();
        cards[i].style.display = text.includes(input) ? '' : 'none';
    }
}
