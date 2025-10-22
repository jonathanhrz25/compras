// ==========================
// 🔹 Verificar si una requisición pasa a "Confirmadas"
// ==========================
function verificarRequisicion(card) {
    if (!card) return;

    const filas = card.querySelectorAll("tbody tr");
    let todosCerrados = true;

    // Verifica el estado de cada fila
    filas.forEach(fila => {
        const estadoEl = fila.querySelector("td:nth-child(8) span");
        const estado = estadoEl ? estadoEl.textContent.trim() : "";
        console.log("Estado de fila:", estado);
        if (estado !== "Adquirido" && estado !== "Rechazado") {
            todosCerrados = false;
        }
    });

    if (todosCerrados) {
        const confirmadas = document.getElementById("seccionConfirmadas");
        if (!confirmadas) return;

        // 🔹 Deshabilitar inputs, selects, textareas y botones dentro del formulario
        card.querySelectorAll("form select, form textarea, form button, form input").forEach(el => {
            el.disabled = true;
        });

        // 🔹 Eliminar la columna Acciones (si existe)
        const tabla = card.querySelector("table");
        if (tabla) {
            const ths = Array.from(tabla.querySelectorAll("thead th"));
            const accionesIndex = ths.findIndex(th => th.textContent.trim().toLowerCase() === "acciones");
            if (accionesIndex !== -1) {
                // Eliminar encabezado
                ths[accionesIndex].remove();
                // Eliminar celdas correspondientes
                tabla.querySelectorAll("tbody tr").forEach(fila => {
                    const celdas = fila.querySelectorAll("td");
                    if (celdas[accionesIndex]) celdas[accionesIndex].remove();
                });
            }
        }

        // 🔹 Quitar tooltips o modales activos para evitar errores visuales
        const tooltips = document.querySelectorAll(".tooltip");
        tooltips.forEach(t => t.remove());

        // 🔹 Añadir clase visual para confirmadas
        card.classList.add("confirmada", "fade-out");

        // 🔹 Pequeña animación de transición antes de mover
        setTimeout(() => {
            confirmadas.prepend(card);
            card.classList.remove("fade-out");
            card.classList.add("fade-in");

            // Mostrar toast
            mostrarToast("📦 Requisición movida a Confirmadas", "info");
        }, 400);
    }
}

// 🔹 Estilos opcionales (puedes agregarlos en tu CSS global)
const style = document.createElement("style");
style.textContent = `
.fade-out { opacity: 0; transition: opacity 0.4s ease; }
.fade-in { opacity: 1; transition: opacity 0.4s ease; }
.confirmada { border-left: 5px solid #198754; } /* verde éxito */
`;
document.head.appendChild(style);
