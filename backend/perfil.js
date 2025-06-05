function addToFavorites(peliculaId, titulo) {
    if (!peliculaId) {
        alert('Error: ID de película no válido');
        return;
    }

    fetch('perfil.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'pelicula_id=' + encodeURIComponent(peliculaId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`¡${titulo} agregada a favoritos!`);
        } else {
            alert('Error al agregar a favoritos: ' + (data.error || 'Inténtalo de nuevo'));
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

// Subir foto de perfil
document.getElementById('foto_perfil').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/bmp', 'image/tiff'];
        if (validTypes.includes(file.type)) {
            e.target.form.submit();
        } else {
            alert('Formato de imagen no permitido.');
        }
    }
});