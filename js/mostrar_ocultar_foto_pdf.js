// ==========================
// 🔹 Mostrar/Ocultar campos de foto y PDF cuando el estado sea "Adquirido"
// ==========================
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".select-estado").forEach(select => {
        const form = select.closest("form");
        const campoFoto = form?.querySelector(".campo-foto");
        const campoPDF = form?.querySelector(".campo-pdf");
        if (!campoFoto || !campoPDF) return;

        // Detectar valores desde el backend
        const fotoExistente = form.querySelector('input[name="foto_existente"]')?.value?.trim();
        const pdfExistente = form.querySelector('input[name="pdf_existente"]')?.value?.trim();

        // Miniaturas renderizadas (por si existen en DOM)
        const fila = form.closest("tr");
        const miniFoto = fila?.querySelector(".mini-foto");
        const miniPDF = fila?.querySelector(".mini-pdf");

        // Función para mostrar/ocultar dinámicamente
        function toggleCampos() {
            const estadoActual = select.value; // Valor seleccionado en el momento
            const estadoInicial = select.getAttribute("data-estado-inicial"); // Estado original cargado desde BD

            // 🛑 Si ya estaba en Adquirido desde BD → OCULTAR SIEMPRE (sin opción a subir archivos)
            if (estadoInicial === "Adquirido") {
                campoFoto.style.display = "none";
                campoPDF.style.display = "none";
                return; // ⛔ Salir de la función (no dejar que el usuario active campos)
            }

            // 🎯 Si cambia de otro estado a "Adquirido" → mostrar solo si NO hay archivos existentes
            if (estadoActual === "Adquirido") {
                if (!miniFoto && !fotoExistente) {
                    campoFoto.style.display = "block";
                } else {
                    campoFoto.style.display = "none";
                }

                if (!miniPDF && !pdfExistente) {
                    campoPDF.style.display = "block";
                } else {
                    campoPDF.style.display = "none";
                }
            } else {
                // Otros estados → siempre ocultar
                campoFoto.style.display = "none";
                campoPDF.style.display = "none";
                campoFoto.querySelector('input[type="file"]').value = '';
                campoPDF.querySelector('input[type="file"]').value = '';
            }
        }

        // Ejecutar inmediatamente al cargar (ya sea con o sin miniaturas)
        toggleCampos();

        // Escuchar cambios de estado
        select.addEventListener("change", toggleCampos);
    });
});




// ==========================
// 🔹 Modal para ampliar imagen
// ==========================
document.addEventListener("click", function (e) {
    if (e.target.matches(".mini-foto")) {
        const imgSrc = e.target.dataset.foto;

        let modal = document.getElementById("modalFoto");
        if (!modal) {
            modal = document.createElement("div");
            modal.className = "modal fade";
            modal.id = "modalFoto";
            modal.tabIndex = "-1";
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content bg-dark border-0">
                        <div class="modal-body text-center p-0">
                            <img id="imagenAmpliada" src="" class="img-fluid rounded" alt="Evidencia">
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>`;
            document.body.appendChild(modal);
        }

        const imgEl = modal.querySelector("#imagenAmpliada");
        imgEl.src = imgSrc;

        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();
    }
});


// ==========================
// 🔹 Modal para visualizar PDF
// ==========================
document.addEventListener("click", function (e) {
    if (e.target.closest(".mini-pdf")) {
        const pdfDiv = e.target.closest(".mini-pdf");
        const pdfSrc = pdfDiv.dataset.pdf;

        let modalPDF = document.getElementById("modalPDF");
        if (!modalPDF) {
            modalPDF = document.createElement("div");
            modalPDF.className = "modal fade";
            modalPDF.id = "modalPDF";
            modalPDF.tabIndex = "-1";
            modalPDF.innerHTML = `
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title">
                                <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Factura PDF
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0" style="height: 80vh;">
                            <iframe id="iframePDF" src="" width="100%" height="100%" style="border:none;"></iframe>
                        </div>
                        <div class="modal-footer bg-light">
                            <a id="btnDescargarPDF" href="#" download class="btn btn-outline-danger">
                                <i class="bi bi-download"></i> Descargar PDF
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>`;
            document.body.appendChild(modalPDF);
        }

        const iframe = modalPDF.querySelector("#iframePDF");
        const btnDescargar = modalPDF.querySelector("#btnDescargarPDF");

        iframe.src = pdfSrc;
        btnDescargar.href = pdfSrc;

        const modalInstance = new bootstrap.Modal(modalPDF);
        modalInstance.show();
    }
});


// ==========================
// 🔹 Activar tooltips de Bootstrap
// ==========================
document.addEventListener("DOMContentLoaded", function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
});