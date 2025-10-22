// Asegúrate que 'formNuevaRequisicion' está definido y existe en el DOM
const formNuevaRequisicion = document.getElementById('formNuevaRequisicion');

if (formNuevaRequisicion) {
    formNuevaRequisicion.addEventListener("submit", async function (e) {
        e.preventDefault();

        const submitBtn = formNuevaRequisicion.querySelector("button[type='submit']");
        if (submitBtn) submitBtn.disabled = true;

        // Validar correos dentro de este formulario
        const emailInputs = formNuevaRequisicion.querySelectorAll('input[name="correos[]"]');
        let valid = true;

        emailInputs.forEach(input => {
            if (input.value.trim() === '') {
                valid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!valid) {
            mostrarToast("⚠️ Por favor, ingrese todos los correos antes de continuar.", "warning");
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        const formData = new FormData(formNuevaRequisicion);

        // Procesar cantidades con unidad
        const cantidadesOriginales = formData.getAll("cantidad[]");
        const unidades = formData.getAll("unidad[]");

        // Crear cantidades combinadas cantidad+unidad
        const cantidadesConUnidad = cantidadesOriginales.map((cantidad, index) => {
            const unidad = unidades[index] || '';
            return `${cantidad}${unidad}`;
        });

        // Remover las cantidades originales para luego añadir las combinadas
        formData.delete("cantidad[]");
        cantidadesConUnidad.forEach(cantidad => formData.append("cantidad[]", cantidad));

        try {
            const res = await fetch("../php/guardar_requisicion.php", {
                method: "POST",
                body: formData
            });

            const data = await res.json();

            if (data.success) {
                // Cerrar modal
                const modalEl = document.getElementById("modalNueva");
                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }

                // Resetear formulario
                formNuevaRequisicion.reset();

                // Actualizar lista de pendientes
                const contenedor = document.getElementById("seccionPendientes");
                if (contenedor) {
                    const productos = formData.getAll("producto[]");
                    const descripciones = formData.getAll("descripcion[]");

                    const nuevaCard = document.createElement("div");
                    nuevaCard.className = "card mb-4 shadow-sm";
                    nuevaCard.id = `requisicion-${data.requisicion_id}`;
                    nuevaCard.innerHTML = `
                        <div class="card-header card-header-azul-claro">
                            <strong>Requisición #${data.requisicion_id}</strong> |
                            Fecha: ${data.fecha} |
                            Solicitante: ${data.solicitante}
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="thead">
                                        <tr>
                                            <th>Producto</th>
                                            <th>Descripción</th>
                                            <th>Cantidad</th>
                                            <th>Estado</th>
                                            <th>Fecha solicitada</th>
                                            <th>Última actualización</th>
                                            <th>Comentarios</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${productos.map((producto, i) => {
                                            const descripcion = descripciones[i] || "-";
                                            const cantidad = cantidadesConUnidad[i] || "-";
                                            return `
                                            <tr>
                                                <td>${producto}</td>
                                                <td>${descripcion}</td>
                                                <td class="text-center">${cantidad}</td>
                                                <td class="text-center"><span class="badge bg-warning">Pendiente</span></td>
                                                <td class="text-center">${data.fecha}</td>
                                                <td class="text-center">--</td>
                                                <td><span class="text-muted">Sin comentarios</span></td>
                                            </tr>`;
                                        }).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                    contenedor.prepend(nuevaCard);

                    // Si tienes una función toggleSeccion, asegúrate que está definida antes de usarla
                    if (typeof toggleSeccion === 'function' && typeof seccionPendientes !== 'undefined' && typeof btnPendientes !== 'undefined') {
                        toggleSeccion(seccionPendientes, btnPendientes);
                    }
                }

                mostrarToast("✅ Requisición guardada y correo enviado con éxito", "success");

            } else {
                mostrarToast("⚠️ " + (data.error || "No se pudo guardar"), "danger");
            }
        } catch (err) {
            console.error(err);
            mostrarToast("❌ Error de conexión con el servidor", "danger");
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    });
} else {
    console.warn("No se encontró el formulario 'formNuevaRequisicion'");
}
