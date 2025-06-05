// Validación básica del formulario de login (lado cliente)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const correo = document.getElementById('correo').value;
            const contrasena = document.getElementById('contrasena').value;
            if (!correo || !contrasena) {
                e.preventDefault();
                alert('Por favor, completa todos los campos.');
            }
        });
    }
});