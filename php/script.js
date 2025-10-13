document.addEventListener('DOMContentLoaded', () => {
    const botonesAplicar = document.querySelectorAll('.boton-aplicar');
    const modales = document.querySelectorAll('.modal');
    const botonesCerrar = document.querySelectorAll('.cerrar-modal');

    // 1. Abrir Modal
    botonesAplicar.forEach(boton => {
        boton.addEventListener('click', function() {
            const puestoId = this.getAttribute('data-puesto_id');
            const modal = document.getElementById(`modal-${puestoId}`);
            if (modal) {
                modal.style.display = 'block';
            }
        });
    });

    // 2. Cerrar Modal con la 'x'
    botonesCerrar.forEach(boton => {
        boton.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });

    // 3. Cerrar Modal al hacer clic fuera
    window.onclick = function(event) {
        modales.forEach(modal => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    }
});