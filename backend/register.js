// Validación básica del formulario de registro (lado cliente)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value;
            const correo = document.getElementById('correo').value;
            const contrasena = document.getElementById('contrasena').value;
            if (!nombre || !correo || !contrasena) {
                e.preventDefault();
                alert('Por favor, completa todos los campos.');
            } else if (!correo.includes('@') || !correo.includes('.')) {
                e.preventDefault();
                alert('Por favor, ingresa un correo válido.');
            }
        });
    }
});