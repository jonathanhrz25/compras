// Limpiar botones vencidos al cargar la página
(function limpiarReenviosVencidos() {
    const hoy = new Date().toISOString().split('T')[0]; // yyyy-mm-dd
    const clave = 'reenviados_' + hoy;

    Object.keys(localStorage).forEach(key => {
        if (key.startsWith('reenviados_') && key !== clave) {
            localStorage.removeItem(key);
        }
    });

    const reenviados = JSON.parse(localStorage.getItem(clave) || '{}');
    Object.entries(reenviados).forEach(([reqId, estado]) => {
        if (estado === true) {
            const btn = document.querySelector(`.btn-reenviar-correo[data-id="${reqId}"]`);
            if (btn) {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
                btn.textContent = '✅ Enviado';
                btn.disabled = true;
            }
        }
    });
})();

// Delegación para abrir modal de reenviar correo
document.addEventListener("click", function (e) {
    const btn = e.target.closest(".btn-reenviar-correo");
    if (!btn) return;

    const reqId = btn.dataset.id;
    if (!reqId) return;

    const modalEl = document.getElementById('modalReenviarCorreo');
    const inputHidden = modalEl.querySelector('#reenviarID');
    const container = modalEl.querySelector('#correos-container-reenviar');

    inputHidden.value = reqId;

    // Limpia y agrega un campo vacío inicialmente
    container.innerHTML = `
        <div class="input-group mb-2">
            <input type="email" name="correos_reenvio[]" class="form-control" placeholder="Ingrese un correo" required />
            <button type="button" class="btn btn-outline-secondary add-email-btn">
                <i class="bi bi-plus-square-fill"></i>
            </button>
        </div>
    `;

    // Cargar correos previos para la requisición
    fetch(`../php/get_correos.php?requisicion_id=${encodeURIComponent(reqId)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && Array.isArray(data.emails) && data.emails.length > 0) {
                container.innerHTML = ''; // limpiar el input inicial

                data.emails.forEach(email => {
                    const group = document.createElement('div');
                    group.className = 'input-group mb-2';

                    const input = document.createElement('input');
                    input.type = 'email';
                    input.name = 'correos[]';
                    input.className = 'form-control';
                    input.placeholder = 'Ingrese un correo';
                    input.required = true;
                    input.value = email;

                    const btnAdd = document.createElement('button');
                    btnAdd.type = 'button';
                    btnAdd.className = 'btn btn-outline-secondary add-email-btn';
                    btnAdd.innerHTML = '<i class="bi bi-plus-square-fill"></i>';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-outline-danger remove-email-btn';
                    removeBtn.innerHTML = '<i class="bi bi-x-square-fill"></i>';

                    group.appendChild(input);
                    group.appendChild(btnAdd);
                    group.appendChild(removeBtn);

                    container.appendChild(group);
                });
            }
        })
        .catch(err => {
            console.error('No se pudieron cargar correos previos:', err);
        });

    const modal = new bootstrap.Modal(modalEl);
    modal.show();
});

// Añadir y eliminar campos de correo dinámicamente (delegación)
document.addEventListener('click', function (event) {
    if (event.target.closest('.add-email-btn')) {
        // Determinar qué contenedor de correos se está usando según botón
        // Para reenviar correo, el contenedor es siempre este:
        let container = null;

        if (event.target.closest('#correos-container-reenviar')) {
            container = document.getElementById('correos-container-reenviar');
        } else if (event.target.closest('#correos-container')) {
            container = document.getElementById('correos-container');
        }

        if (!container) return;

        const newInputGroup = document.createElement('div');
        newInputGroup.className = 'input-group mb-2';

        const newInput = document.createElement('input');
        newInput.type = 'email';
        newInput.name = 'correos[]';
        newInput.className = 'form-control';
        newInput.placeholder = 'Ingrese otro correo';
        newInput.required = true;

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn btn-outline-danger remove-email-btn';
        removeButton.innerHTML = '<i class="bi bi-x-square-fill"></i>';

        newInputGroup.appendChild(newInput);
        newInputGroup.appendChild(removeButton);

        container.appendChild(newInputGroup);
    }

    if (event.target.closest('.remove-email-btn')) {
        const grp = event.target.closest('.input-group');
        if (grp) grp.remove();
    }
});

// Envío del formulario de reenvío de correo
const formReenviarCorreo = document.getElementById('formReenviarCorreo');
if (formReenviarCorreo) {
    formReenviarCorreo.addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const submitBtn = form.querySelector("button[type='submit']");
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(form);
        const reqId = formData.get('requisicion_id') || form.querySelector('#reenviarID').value;

        // Filtrar correos válidos (no vacíos)
        const emails = Array.from(formData.getAll('correos[]'))
            .map(s => s.trim())
            .filter(s => s !== '');

        if (emails.length === 0) {
            mostrarToast('⚠️ Debes agregar al menos un correo válido', 'warning');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }

        // Eliminar correos duplicados
        const uniq = Array.from(new Set(emails));

        const fd = new FormData();
        fd.append('requisicion_id', reqId);
        uniq.forEach(email => fd.append('correos[]', email));

        fetch('../php/reenviar_correo.php', {
            method: 'POST',
            body: fd
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    mostrarToast('✅ Correo reenviado correctamente', 'success');

                    // Guardar estado en localStorage
                    const hoy = new Date().toISOString().split('T')[0];
                    const clave = 'reenviados_' + hoy;
                    const reenviados = JSON.parse(localStorage.getItem(clave) || '{}');
                    reenviados[reqId] = true;
                    localStorage.setItem(clave, JSON.stringify(reenviados));

                    // Cambiar botón para reflejar envío
                    const btn = document.querySelector(`.btn-reenviar-correo[data-id="${reqId}"]`);
                    if (btn) {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-success');
                        btn.textContent = '✅ Enviado';
                        btn.disabled = true;
                    }

                    // Cerrar modal
                    const modalEl = document.getElementById('modalReenviarCorreo');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                } else {
                    console.error('Error reenviar:', data);
                    mostrarToast('❌ Error al reenviar el correo', 'danger');
                }
            })
            .catch(err => {
                console.error(err);
                mostrarToast('❌ Error de comunicación con el servidor', 'danger');
            })
            .finally(() => {
                if (submitBtn) submitBtn.disabled = false;
            });
    });
} else {
    console.warn("No se encontró el formulario 'formReenviarCorreo'");
}
