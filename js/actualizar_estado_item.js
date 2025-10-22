// ==========================
// 🔹 Actualizar estado de item (Operador)
// ==========================
document.addEventListener("submit", async function (e) {
    if (e.target.matches(".form-actualizar-estado")) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        try {
            const res = await fetch("../php/update_estado.php", {
                method: "POST",
                body: formData,
                headers: { "Accept": "application/json" }
            });

            const data = await res.json();
            console.log("Respuesta del servidor:", data);

            if (data.success) {
                const fila = form.closest("tr");

                // 🔹 Actualizar estado + miniaturas (foto y PDF)
                const tdEstado = fila.querySelector("td:nth-child(8)");
                if (tdEstado) {
                    let contenidoEstado = `
                        <span class="badge ${data.estado === "Pendiente" ? "bg-warning" :
                            data.estado === "En proceso" ? "bg-info" :
                                data.estado === "Adquirido" ? "bg-success" :
                                    "bg-danger"
                        }">${data.estado}</span>
                    `;

                    // Agregar miniatura de FOTO si existe
                    if (data.foto) {
                        contenidoEstado += `
                            <img src="../${data.foto}" 
                                 alt="Evidencia" 
                                 class="mini-foto ms-2 rounded border"
                                 style="width:50px; height:50px; object-fit:cover; cursor:pointer;"
                                 data-foto="../${data.foto}"
                                 data-bs-toggle="tooltip" 
                                 data-bs-title="Ver evidencia">
                        `;
                    }

                    // Agregar miniatura de PDF si existe
                    if (data.pdf) {
                        contenidoEstado += `
                            <div class="mini-pdf ms-2 d-inline-flex align-items-center justify-content-center border rounded"
                                 style="width:50px; height:50px; background:#f8f9fa; cursor:pointer; transition:transform 0.2s;"
                                 data-pdf="../${data.pdf}"
                                 data-bs-toggle="tooltip" 
                                 data-bs-title="Ver factura PDF"
                                 onmouseover="this.style.transform='scale(1.1)'"
                                 onmouseout="this.style.transform='scale(1)'">
                                 <i class="bi bi-file-earmark-pdf-fill text-danger fs-4"></i>
                            </div>
                        `;
                    }

                    tdEstado.innerHTML = contenidoEstado;
                }

                // ✅ Actualizar última actualización
                const tdUltimaActualizacion = fila.querySelector("td:nth-child(11)");
                if (tdUltimaActualizacion) tdUltimaActualizacion.textContent = data.updated_at;

                // ✅ Actualizar comentarios
                const tdComentarios = fila.querySelector("td:nth-child(12)");
                if (tdComentarios) tdComentarios.textContent = data.comentarios || "Sin comentarios";

                // ✅ Ocultar campos de carga si el estado es "Adquirido"
                if (data.estado === "Adquirido") {
                    const campoFoto = form.querySelector(".campo-foto");
                    const campoPDF = form.querySelector(".campo-pdf");
                    if (campoFoto) campoFoto.style.display = "none";
                    if (campoPDF) campoPDF.style.display = "none";

                    const inputFoto = form.querySelector('input[name="foto"]');
                    const inputPDF = form.querySelector('input[name="factura_pdf"]');
                    if (inputFoto) inputFoto.value = '';
                    if (inputPDF) inputPDF.value = '';
                }

                // ✅ Reiniciar tooltips de Bootstrap
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

                // ✅ Verificar si mover a confirmadas
                verificarRequisicion(fila.closest(".card"));

                // ✅ Toast de éxito
                mostrarToast("✅ Estado actualizado correctamente", "success");
            } else {
                mostrarToast("⚠️ " + (data.error || "No se pudo actualizar"), "danger");
            }
        } catch (err) {
            console.error("Error de conexión:", err);
            mostrarToast("❌ Error de conexión con el servidor", "danger");
        }
    }
});


// ==========================
// 🔹 Actualizar estado de item (TI)
// ==========================
document.addEventListener("DOMContentLoaded", function () {
    const selects = document.querySelectorAll(".estado-entrega");

    selects.forEach(select => {
        aplicarColorSelect(select); // Aplica color inicial

        select.addEventListener("change", function () {
            const id = this.dataset.id;
            const nuevoEstado = this.value;

            fetch("entrega_estado.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${id}&entrega_estado=${nuevoEstado}`
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        mostrarToast(`Estado actualizado a "${nuevoEstado}" correctamente.`);

                        aplicarColorSelect(select);

                        if (nuevoEstado !== "Pendiente") {
                            select.disabled = true;
                        }
                    } else {
                        mostrarToast("❌ Error al actualizar el estado.");
                    }
                })
                .catch(() => mostrarToast("⚠️ Error de conexión con el servidor."));
        });
    });

    // 🟢 Colorear select según el estado
    function aplicarColorSelect(select) {
        select.classList.remove("bg-warning", "bg-success", "bg-danger");
        if (select.value === "Recibido") select.classList.add("bg-success", "text-white");
        else if (select.value === "Rechazado") select.classList.add("bg-danger", "text-white");
        else select.classList.add("bg-warning", "text-dark");
    }

    // 🔔 Toast centrado estilo Bootstrap
    function mostrarToast(mensaje, tipo = "primary") {
        const toastContainer = document.createElement("div");
        toastContainer.className = `toast align-items-center text-white bg-${tipo} border-0 position-fixed top-50 start-50 translate-middle show`;
        toastContainer.style.zIndex = "1055";
        toastContainer.innerHTML = `
            <div class="d-flex">
                <div class="toast-body fs-6">${mensaje}</div>
            </div>`;
        document.body.appendChild(toastContainer);
        setTimeout(() => toastContainer.remove(), 2000);
    }
});