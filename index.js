function addToFavorites(title) {
    alert(`¡${title} agregada a favoritos! (Funcionalidad simulada)`);
    // Aquí irá la lógica para guardar en la base de datos (AJAX o similar)
}

// Filtro de búsqueda simple
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const movieCards = document.querySelectorAll('.movie-card');
    movieCards.forEach(card => {
        const title = card.querySelector('h5').textContent.toLowerCase();
        if (title.includes(searchTerm)) {
            card.parentElement.style.display = 'block';
        } else {
            card.parentElement.style.display = 'none';
        }
    });
});