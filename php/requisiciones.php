<?php
session_name('Compras');
session_start();
require '../php/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./inicioSesion.php");
    exit();
}

$conn = connectMysqli();

// Mensaje inicial
$msg = '';
$type = 'success';

// Crear o agregar a requisición (solo TI)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto']) && $_SESSION['rol'] === 'TI') {
    $usuario_id = $_SESSION['user_id'];
    $req_id = $_POST['requisicion_id'] ?? '';

    if (empty($req_id)) {
        // Crear requisición nueva
        $conn->query("INSERT INTO requisiciones (usuario_id, fecha) VALUES ($usuario_id, NOW())");
        $req_id = $conn->insert_id;
    }

    // Recorrer los arrays de productos y guardarlos uno a uno
    if (is_array($_POST['producto'])) {
        foreach ($_POST['producto'] as $i => $producto) {
            $producto = $conn->real_escape_string($producto);
            $descripcion = isset($_POST['descripcion'][$i]) ? $conn->real_escape_string($_POST['descripcion'][$i]) : '';
            $cantidad = isset($_POST['cantidad'][$i]) ? intval($_POST['cantidad'][$i]) : 0;

            $conn->query("INSERT INTO items 
                (requisicion_id, producto, descripcion, cantidad, estado, created_at, updated_at) 
                VALUES ($req_id, '$producto', '$descripcion', '$cantidad', 'Pendiente', NOW(), NULL)");
        }
    }

    $msg = "Requisición registrada correctamente ✅";
    $type = 'success';
}

// Consultar requisiciones con sus estados
$sql = "
    SELECT r.id AS requisicion_id, 
           r.fecha, 
           u.usuario AS solicitante,
           GROUP_CONCAT(i.estado) AS estados,
           MAX(i.updated_at) AS ultima_actualizacion
    FROM requisiciones r
    JOIN usuarios u ON r.usuario_id = u.id
    LEFT JOIN items i ON i.requisicion_id = r.id
    GROUP BY r.id, r.fecha, u.usuario
    ORDER BY COALESCE(MAX(i.updated_at), r.fecha) DESC
";
$res = $conn->query($sql);

$requisiciones_actuales = [];
$requisiciones_pasadas = [];

while ($row = $res->fetch_assoc()) {
    $estados = $row['estados'] ? explode(',', $row['estados']) : [];
    $tiene_pendientes = false;

    foreach ($estados as $estado) {
        if ($estado === 'Pendiente' || $estado === 'En proceso') {
            $tiene_pendientes = true;
            break;
        }
    }

    if ($tiene_pendientes) {
        $requisiciones_actuales[] = $row;
    } else {
        $requisiciones_pasadas[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../img/icono2.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Requisiciones</title>
    <style>
        .card-header-azul-claro {
            background-color: #1E8BC3 !important;
            color: #ffffff !important;
        }

        .btn-toggle {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            padding: 12px 20px;
            border: none;
            width: 95%;
            /* siempre ocupa todo el ancho */
            transition: background 0.3s ease, transform 0.1s ease, color 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            display: block;
            /* asegura que se apile */
            margin-bottom: 10px;
            /* 🔹 espacio entre botones */
        }

        /* Estado normal */
        .btn-actual,
        .btn-pasadas {
            background: linear-gradient(90deg, #0d6efd, #0d6efd);
            color: white;
        }

        /* Estado activo */
        .btn-actual.active-btn,
        .btn-pasadas.active-btn {
            background: linear-gradient(90deg, #081856, #081856);
            color: white;
            /* Mantener texto blanco */
        }

        /* Efecto hover */
        .btn-actual:hover,
        .btn-pasadas:hover {
            background: linear-gradient(90deg, #14e7f7da, #14e7f7da);
            transform: scale(1.03);
            color: black;
            /* Texto negro SOLO al pasar el mouse */
            box-shadow: 0px 0px 12px rgba(20, 231, 247, 0.7);
        }

        .btn-toggle.active-btn {
            box-shadow: 0px 4px 10px rgba(15, 15, 15, 1);
            transform: scale(1.02);
            opacity: 1;
        }

        .thead th {
            text-align: center;
        }

        /* 📱 Responsividad */
        @media (max-width: 768px) {
            .btn-toggle {
                font-size: 1rem;
                padding: 10px;
                margin-bottom: 12px;
                /* más espacio en tablets */
            }
        }

        @media (max-width: 480px) {
            .btn-toggle {
                font-size: 0.9rem;
                padding: 8px;
                margin-bottom: 15px;
                /* más espacio en móviles */
            }
        }

        /* Acordeón */
        .requisiciones-section.accordion {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.6s ease, opacity 0.6s ease;
        }

        .requisiciones-section.accordion.show {
            max-height: 2000px;
            /* suficiente para mostrar todo el contenido */
            opacity: 1;
        }
    </style>
</head>

<body class="container-fluid" style="padding-top: 85px;">

    <nav class="navbar navbar-dark bg-dark fixed-top" style="background-color: #081856!important;">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="../php/principal.php">
                <img src="../img/loguito2.png" alt="" height="45" class="d-inline-block align-text-top">
            </a>
        </div>
    </nav>

    <div>
        <h2 class="display-6">Requisiciones</h2>

        <?php if (!empty($msg)): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($msg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION['rol'] === 'TI'): ?>
            <div class="card p-3 mb-4">
                <div class="d-flex gap-3 flex-column flex-sm-row">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNueva">
                        <i class="bi bi-plus-circle-fill"></i> Agregar una nueva lista de productos
                    </button>
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalExistente">
                        <i class="bi bi-file-earmark-plus-fill"></i> Agregar productos a una requisición existente
                    </button>
                </div>
            </div>

            <!-- Modal Nueva requisición -->
            <div class="modal fade" id="modalNueva" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post">
                            <div class="modal-header">
                                <h5 class="modal-title">Nueva requisición</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="productosNueva">
                                    <div class="row g-2 mb-2 producto-item">
                                        <div class="col-md-4">
                                            <input type="text" name="producto[]" class="form-control" placeholder="Producto"
                                                required>
                                        </div>
                                        <div class="col-md-4">
                                            <textarea name="descripcion[]" class="form-control"
                                                placeholder="Descripción (opcional)" style="height: 19px;"></textarea>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="cantidad[]" class="form-control"
                                                placeholder="Ingresa la cantidad" required>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm eliminarProducto"><i
                                                    class="bi bi-trash3"></i></button>
                                        </div>
                                    </div>
                                </div><br>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="agregarProductoNueva">➕
                                    Agregar otro</button>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Guardar todo</button>
                                <button type="button" class="btn btn-outline-danger"
                                    data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Requisición existente -->
            <div class="modal fade" id="modalExistente" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post">
                            <div class="modal-header">
                                <h5 class="modal-title">Agregar productos a requisición existente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label>Seleccionar requisición</label>
                                    <select name="requisicion_id" class="form-select" required>
                                        <?php
                                        $usuario_id = $_SESSION['user_id'];
                                        $res = $conn->query("SELECT id, fecha FROM requisiciones WHERE usuario_id = $usuario_id ORDER BY fecha DESC");
                                        while ($row = $res->fetch_assoc()): ?>
                                            <option value="<?= $row['id'] ?>">#<?= $row['id'] ?> -
                                                <?= date("d/m/Y H:i", strtotime($row['fecha'])) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <hr>
                                <div id="productosExistente">
                                    <div class="row g-2 mb-2 producto-item">
                                        <div class="col-md-4">
                                            <input type="text" name="producto[]" class="form-control"
                                                placeholder="Ingresa Producto/material" required>
                                        </div>
                                        <div class="col-md-4">
                                            <textarea name="descripcion[]" class="form-control"
                                                placeholder="Descripción (opcional)" style="height: 19px;"></textarea>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="cantidad[]" class="form-control"
                                                placeholder="Ingresa la cantidad" required>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm eliminarProducto"><i
                                                    class="bi bi-trash3"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="agregarProductoExistente">➕
                                    Agregar otro</button>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Guardar todo</button>
                                <button type="button" class="btn btn-outline-danger"
                                    data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Botones de selección -->
        <div class="row mb-3 justify-content-center text-center">
            <div class="col-12 col-md-6 d-flex justify-content-center mb-2 mb-md-0">
                <button id="btnActual" class="btn-toggle btn-actual">Requisiciones actuales</button>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center">
                <button id="btnPasadas" class="btn-toggle btn-pasadas">Requisiciones pasadas</button>
            </div>
        </div>

        <!-- Sección requisiciones actuales -->
        <div id="seccionActual" class="requisiciones-section accordion">
            <?php foreach ($requisiciones_actuales as $req): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header card-header-azul-claro">
                        <strong>Requisición #<?= $req['requisicion_id'] ?></strong> |
                        Fecha: <?= date("d/m/Y H:i", strtotime($req['fecha'])) ?> |
                        Solicitante: <?= $req['solicitante'] ?>
                    </div>
                    <div class="card-body">
                        <?php
                        $items = $conn->query("
                            SELECT * FROM items 
                            WHERE requisicion_id = {$req['requisicion_id']}
                            ORDER BY updated_at DESC, id DESC
                        ");
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="thead">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                        <?php if ($_SESSION['rol'] === 'Operador'): ?>
                                            <th>Acciones</th>
                                        <?php endif; ?>
                                        <th>Fecha solicitada</th>
                                        <th>Última actualización</th>
                                        <th>Comentarios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($item = $items->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['producto']) ?></td>
                                            <td><?= htmlspecialchars($item['descripcion']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($item['cantidad']) ?></td>
                                            <td class="text-center">
                                                <span class="badge 
                                                    <?php
                                                    if ($item['estado'] === 'Pendiente')
                                                        echo 'bg-warning';
                                                    elseif ($item['estado'] === 'En proceso')
                                                        echo 'bg-info';
                                                    elseif ($item['estado'] === 'Adquirido')
                                                        echo 'bg-success';
                                                    elseif ($item['estado'] === 'Rechazado')
                                                        echo 'bg-danger';
                                                    ?>">
                                                    <?= $item['estado'] ?>
                                                </span>
                                            </td>
                                            <?php if ($_SESSION['rol'] === 'Operador'): ?>
                                                <td>
                                                    <form method="post" action="update_estado.php"
                                                        class="d-flex flex-column flex-sm-row">
                                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                                        <select name="estado" class="form-select me-2 mb-2 mb-sm-0">
                                                            <option value="Pendiente" <?= $item['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                            <option value="En proceso" <?= $item['estado'] === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                                                            <option value="Adquirido" <?= $item['estado'] === 'Adquirido' ? 'selected' : '' ?>>Adquirido</option>
                                                            <option value="Rechazado" <?= $item['estado'] === 'Rechazado' ? 'selected' : '' ?>>Rechazado</option>
                                                        </select>
                                                        <div class="mt-2">
                                                            <label for="comentarios_<?= $item['id'] ?>">Comentarios</label>
                                                            <textarea name="comentarios" id="comentarios_<?= $item['id'] ?>"
                                                                class="form-control" rows="2" placeholder="Opcional"></textarea>
                                                        </div>
                                                        <button type="submit"
                                                            class="btn btn-sm btn-success mt-2">Actualizar</button>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                            <td class="text-center">
                                                <?= $item['created_at'] ? date("d/m/Y H:i", strtotime($item['created_at'])) : '-' ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $item['updated_at'] ? date("d/m/Y H:i", strtotime($item['updated_at'])) : '-' ?>
                                            </td>
                                            <td><?= !empty($item['comentarios']) ? htmlspecialchars($item['comentarios']) : '<span class="text-muted">Sin comentarios</span>' ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Sección requisiciones pasadas -->
        <div id="seccionPasadas" class="requisiciones-section accordion">
            <?php foreach ($requisiciones_pasadas as $req): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header card-header-azul-claro">
                        <strong>Requisición #<?= $req['requisicion_id'] ?></strong> |
                        Fecha: <?= date("d/m/Y H:i", strtotime($req['fecha'])) ?> |
                        Solicitante: <?= $req['solicitante'] ?>
                    </div>
                    <div class="card-body">
                        <?php
                        $items = $conn->query("
                            SELECT * FROM items 
                            WHERE requisicion_id = {$req['requisicion_id']}
                            ORDER BY updated_at DESC, id DESC
                        ");
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
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
                                    <?php while ($item = $items->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['producto']) ?></td>
                                            <td><?= htmlspecialchars($item['descripcion']) ?></td>
                                            <td><?= htmlspecialchars($item['cantidad']) ?></td>
                                            <td>
                                                <span class="badge 
                                                    <?php
                                                    if ($item['estado'] === 'Pendiente')
                                                        echo 'bg-warning';
                                                    elseif ($item['estado'] === 'En proceso')
                                                        echo 'bg-info';
                                                    elseif ($item['estado'] === 'Adquirido')
                                                        echo 'bg-success';
                                                    elseif ($item['estado'] === 'Rechazado')
                                                        echo 'bg-danger';
                                                    ?>">
                                                    <?= $item['estado'] ?>
                                                </span>
                                            </td>
                                            <td><?= $item['created_at'] ? date("d/m/Y H:i", strtotime($item['created_at'])) : '-' ?>
                                            </td>
                                            <td><?= $item['updated_at'] ? date("d/m/Y H:i", strtotime($item['updated_at'])) : '-' ?>
                                            </td>
                                            <td><?= !empty($item['comentarios']) ? htmlspecialchars($item['comentarios']) : '<span class="text-muted">Sin comentarios</span>' ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Reutilizable: clona campos de producto
        function initProductoCloner(btnId, containerId) {
            document.getElementById(btnId).addEventListener('click', function () {
                const contenedor = document.getElementById(containerId);
                const nuevo = contenedor.querySelector('.producto-item').cloneNode(true);
                nuevo.querySelectorAll('input, textarea').forEach(el => el.value = '');
                contenedor.appendChild(nuevo);
            });

            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('eliminarProducto')) {
                    const item = e.target.closest('.producto-item');
                    const contenedor = item.parentNode;
                    if (contenedor.querySelectorAll('.producto-item').length > 1) {
                        item.remove();
                    }
                }
            });
        }

        initProductoCloner('agregarProductoNueva', 'productosNueva');
        initProductoCloner('agregarProductoExistente', 'productosExistente');
    </script>

    <script>
        const btnActual = document.getElementById('btnActual');
        const btnPasadas = document.getElementById('btnPasadas');
        const seccionActual = document.getElementById('seccionActual');
        const seccionPasadas = document.getElementById('seccionPasadas');

        function activarBoton(boton) {
            btnActual.classList.remove('active-btn');
            btnPasadas.classList.remove('active-btn');
            boton.classList.add('active-btn');
        }

        function mostrarSeccionAccordion(seccionMostrar, seccionOcultar, boton) {
            seccionOcultar.classList.remove("show");
            seccionMostrar.classList.add("show");
            activarBoton(boton);
        }

        btnActual.addEventListener('click', () => {
            mostrarSeccionAccordion(seccionActual, seccionPasadas, btnActual);
        });

        btnPasadas.addEventListener('click', () => {
            mostrarSeccionAccordion(seccionPasadas, seccionActual, btnPasadas);
        });

        // Inicial (Requisiciones actuales activas)
        seccionActual.classList.add("show");
        activarBoton(btnActual);
    </script>

</body>

<?php include '../css/footer.php'; ?>

</html>