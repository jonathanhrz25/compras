// ==========================
// 🔹 Botones y secciones
// ==========================
const btnPendientes = document.getElementById('btnPendientes');
const btnConfirmadas = document.getElementById('btnConfirmadas');
const seccionPendientes = document.getElementById('seccionPendientes');
const seccionConfirmadas = document.getElementById('seccionConfirmadas');
const contenedorBotones = document.querySelector('.botones-requisiciones');

// 🔹 Función para resaltar botón activo
function activarBoton(boton) {
    [btnPendientes, btnConfirmadas].forEach(b => b?.classList.remove('active-btn'));
    boton?.classList.add('active-btn');
}

// ==========================
// 🔹 Reubicación dinámica de gráficos
// ==========================
const contenedorGraficos = document.getElementById('contenedorGraficos');

function moverGraficos(destino) {
    if (contenedorGraficos && destino) {
        destino.appendChild(contenedorGraficos);
    }
}

// ==========================
// 🔹 Mostrar/Ocultar secciones + mover gráficos + redibujar charts
// ==========================
function toggleSeccion(seccion, boton) {
    if (!seccion) return;

    const visible = seccion.classList.contains("show");

    // Ocultar ambas secciones
    [seccionPendientes, seccionConfirmadas].forEach(s => {
        if (s) {
            s.style.display = "none";
            s.classList.remove("show");
        }
    });

    if (!visible) {
        // Mostrar la sección seleccionada
        seccion.style.display = "block";
        setTimeout(() => seccion.classList.add("show"), 10);
        activarBoton(boton);
        contenedorBotones?.classList.add('compacto');

        // ✅ Mover gráficos debajo de la sección activa
        moverGraficos(seccion);

        // ✅ Redibujar las gráficas (espera un poco para asegurar visibilidad)
        if (typeof drawCharts === "function") {
            setTimeout(() => drawCharts(), 300);
        }

        // Scroll suave
        window.scrollTo({
            top: contenedorBotones.offsetTop - 20,
            behavior: 'smooth'
        });
    } else {
        activarBoton(null);
        contenedorBotones?.classList.remove('compacto');

        // ✅ Si se oculta la sección, los gráficos regresan debajo de los botones
        moverGraficos(contenedorBotones.parentNode);

        window.scrollTo({
            top: contenedorBotones.offsetTop - 50,
            behavior: 'smooth'
        });
    }
}

// ==========================
// 🔹 Eventos de botones
// ==========================
btnPendientes?.addEventListener('click', () => toggleSeccion(seccionPendientes, btnPendientes));
btnConfirmadas?.addEventListener('click', () => toggleSeccion(seccionConfirmadas, btnConfirmadas));

// ==========================
// 🔹 Estado inicial
// ==========================
document.addEventListener("DOMContentLoaded", () => {
    // ✅ Asegurar que las secciones inicien ocultas
    [seccionPendientes, seccionConfirmadas].forEach(s => {
        if (s) {
            s.style.display = "none";
            s.classList.remove("show");
        }
    });

    // ✅ Colocar los gráficos justo debajo de los botones al iniciar
    moverGraficos(contenedorBotones.parentNode);

    // ✅ Dibujar las gráficas después de que todo cargue
    if (typeof drawCharts === "function") {
        drawCharts();
        setTimeout(() => drawCharts(), 800); // redibujo de seguridad
    }
});
