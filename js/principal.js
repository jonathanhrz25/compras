// ==========================
// 🔹 Reutilizable: clona campos de producto
// ==========================
function initProductoCloner(btnId, containerId) {
    const btn = document.getElementById(btnId);
    if (!btn) return;

    btn.addEventListener('click', function () {
        const contenedor = document.getElementById(containerId);
        if (!contenedor) return;

        const nuevo = contenedor.querySelector('.producto-item').cloneNode(true);
        nuevo.querySelectorAll('input, textarea').forEach(el => el.value = '');
        contenedor.appendChild(nuevo);
    });

    // Manejo de eliminar producto con delegation
    document.addEventListener('click', function (e) {
        const btnEliminar = e.target.closest('.eliminarProducto'); // 🔹 robusto
        if (btnEliminar) {
            const item = btnEliminar.closest('.producto-item');
            if (!item) return;

            const contenedor = item.parentNode;
            if (contenedor.querySelectorAll('.producto-item').length > 1) {
                item.remove();
                mostrarToast("🗑️ Producto eliminado", "danger"); // 🔹 Toast centrado
            } else {
                // 🔹 Si es el único, solo limpia los campos
                item.querySelectorAll('input, textarea').forEach(el => el.value = '');
                mostrarToast("⚠️ Último producto no se puede eliminar, solo se limpió", "warning"); // 🔹 Toast centrado
            }
        }
    });
}

// Inicializar cloner solo donde aplique
initProductoCloner('agregarProductoNueva', 'productosNueva');



// ==========================
// 🔹 Función para mostrar toast (centrado con animación)
// ==========================
function mostrarToast(mensaje, tipo = "success") {
    const toastContainer = document.getElementById("toastContainer") || (() => {
        const div = document.createElement("div");
        div.id = "toastContainer";
        div.className = "toast-container position-fixed top-50 start-50 translate-middle p-3";
        div.style.zIndex = "2000";
        document.body.appendChild(div);
        return div;
    })();

    const toastEl = document.createElement("div");
    toastEl.className = `toast align-items-center text-bg-${tipo} border-0 shadow-lg fade`; // 🔹 fade agregado
    toastEl.role = "alert";
    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body fs-6 fw-semibold text-center w-100">${mensaje}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    toastContainer.appendChild(toastEl);

    const bsToast = new bootstrap.Toast(toastEl, { delay: 2000 });
    bsToast.show();

    // Eliminar el toast cuando termine
    toastEl.addEventListener("hidden.bs.toast", () => toastEl.remove());
}