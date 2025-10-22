<?php
date_default_timezone_set('America/Mexico_City');
session_name('Compras');
session_start();
require '../php/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./inicioSesion.php");
    exit();
}

$conn = connectMysqli();

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

$requisiciones_pendientes = [];
$requisiciones_confirmadas = [];

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
        $requisiciones_pendientes[] = $row;
    } else {
        $requisiciones_confirmadas[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../img/icono2.png" type="image/x-icon">
    <!-- 🔹 DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- 🔹 Bootstrap 5 (si aún no está incluido) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <link rel="stylesheet" href="../css/principal_style.css">
    <title>Principal</title>
</head>

<body style="padding-top: 70px;">

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark bg-dark fixed-top" style="background-color: #081856!important;">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="../php/principal.php">
                <img src="../img/loguito2.png" alt="" height="45">
            </a>
            <div class="text-white d-none d-md-block">
                Bienvenido de nuevo, <?php echo $_SESSION['usuario']; ?>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                <i class="fas fa-user-circle fa-2x"></i>
            </button>

            <div class="offcanvas offcanvas-end bg-dark text-white" id="offcanvasNavbar"
                style="background-color: #081856!important;">
                <div class="offcanvas-header">
                    <span class="text-white font-size-lg"> <?php echo htmlspecialchars($_SESSION['usuario']); ?> </span>
                    <button type="button" class="btn-close btn-lg" style="background-color: white"
                        data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav mr-auto">
                        <?php if ($_SESSION['rol'] === 'TI'): ?>
                            <li class="nav-item mb-2">
                                <a class="btn btn-outline-info w-100" href="../menu/usuarios.php">
                                    <i class="fa fa-users"></i> Usuarios
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <br><br>
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="cerrarSesion.php">Cerrar Sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Animación de entrada -->
    <div id="entradaAnimacion"
        style="position:fixed; top:0; left:0; width:100%; height:100%; background:white; display:flex; align-items:center; justify-content:center; z-index:9999;">
        <lottie-player src="https://assets1.lottiefiles.com/packages/lf20_49rdyysj.json" background="transparent"
            speed="1" style="width: 350px; height: 350px;" autoplay>
        </lottie-player>
    </div>

    <div class="container-fluid mt-4 px-4">
        <!-- <h2 class="display-6 text-center mb-4">Dashboard de Requisiciones</h2> -->

        <!-- BOTONES DE SELECCIÓN -->
        <div class="row mb-3 justify-content-center text-center botones-requisiciones">
            <?php if ($_SESSION['rol'] === 'TI'): ?>
                <div class="col-12 col-md-4 d-flex justify-content-center mb-2">
                    <button
                        class="btn btn-agregar-requisicion btn-toggle btn-actual w-100 p-4 d-flex flex-column justify-content-center align-items-center text-center"
                        data-bs-toggle="modal" data-bs-target="#modalNueva">
                        <i class="bi bi-plus-circle-fill fs-1"></i><br> Agregar nueva requisición
                    </button>
                </div>
            <?php endif; ?>

            <div class="col-12 col-md-4 d-flex justify-content-center mb-2">
                <button id="btnPendientes"
                    class="btn-toggle btn-actual w-100 p-4 d-flex flex-column justify-content-center align-items-center text-center">
                    <i class="bi bi-clock-history fs-1"></i><br>Requisiciones Pendientes
                </button>
            </div>
            <div class="col-12 col-md-4 d-flex justify-content-center mb-2">
                <button id="btnConfirmadas"
                    class="btn-toggle btn-pasadas w-100 p-4 d-flex flex-column justify-content-center align-items-center text-center">
                    <i class="bi bi-check2-circle fs-1"></i><br>Requisiciones Cerradas
                </button>
            </div>
        </div>

        <!-- Sección Pendientes -->
        <div id="seccionPendientes" class="accordion requisiciones-section mt-4">
            <?php foreach ($requisiciones_pendientes as $req): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header card-header-azul-claro d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Requisición #<?= $req['requisicion_id'] ?></strong> |
                            Fecha: <?= date("d/m/Y H:i", strtotime($req['fecha'])) ?> |
                            Solicitante: <?= $req['solicitante'] ?>
                        </div>
                        <?php if ($_SESSION['rol'] === 'TI'): ?>
                            <!-- Botón para reenviar correo -->
                            <button type="button" class="btn btn-sm btn-dark btn-reenviar-correo"
                                data-id="<?= $req['requisicion_id'] ?>">
                                <i class="bi bi-envelope-fill"></i> Reenviar correo
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <?php
                        $items = $conn->query("
                    SELECT id, producto, descripcion, cantidad, motivo, area, cedis, clave,
                        estado, comentarios, created_at, updated_at, foto_evidencia, factura_pdf
                    FROM items 
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
                                        <th>Motivo</th>
                                        <th>Área</th>
                                        <th>Cedis</th>
                                        <th>Clave Inventario</th>
                                        <th>Estado</th>
                                        <?php if ($_SESSION['rol'] === 'Operador'): ?>
                                            <th class="acciones-col" style="text-align: center;">Acciones</th>
                                        <?php endif; ?>
                                        <th>Fecha de solicitud</th>
                                        <th>Última actualización</th>
                                        <th>Comentarios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($item = $items->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['producto']) ?></td>

                                            <!-- Descripción -->
                                            <td class="colapsable-td" data-label="Descripción"
                                                style="min-width:250px; max-width:350px;">
                                                <?php
                                                $descripcion_completa = htmlspecialchars($item['descripcion']);
                                                $descripcion_corta = mb_strimwidth($descripcion_completa, 0, 60, '...');
                                                $mostrar_ver_mas = mb_strlen($descripcion_completa) > 60;
                                                ?>
                                                <span class="texto-corta"><?= $descripcion_corta ?></span>
                                                <?php if ($mostrar_ver_mas): ?>
                                                    <span class="texto-completa d-none"><?= $descripcion_completa ?></span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center"><?= htmlspecialchars($item['cantidad']) ?></td>

                                            <!-- Motivo -->
                                            <td class="colapsable-td" data-label="Motivo"
                                                style="min-width:250px; max-width:350px;">
                                                <?php
                                                $motivo_completo = htmlspecialchars($item['motivo']);
                                                $motivo_corta = mb_strimwidth($motivo_completo, 0, 60, '...');
                                                $mostrar_ver_mas_motivo = mb_strlen($motivo_completo) > 60;
                                                ?>
                                                <span class="texto-corta"><?= $motivo_corta ?></span>
                                                <?php if ($mostrar_ver_mas_motivo): ?>
                                                    <span class="texto-completa d-none"><?= $motivo_completo ?></span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center"><?= htmlspecialchars($item['area']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($item['cedis']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($item['clave']) ?></td>

                                            <td class="text-center">
                                                <span id="estado-<?= $item['id'] ?>" class="badge 
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
                                                </span><br>

                                                <!-- Contenedor flex para las miniaturas -->
                                                <div class="d-flex justify-content-between mt-2">
                                                    <!-- Miniatura de Foto -->
                                                    <?php if (!empty($item['foto_evidencia'])): ?>
                                                        <img src="../<?= htmlspecialchars($item['foto_evidencia']) ?>"
                                                            alt="Evidencia" class="mini-foto rounded border"
                                                            style="width:50px; height:50px; object-fit:cover; cursor:pointer;"
                                                            data-foto="../<?= htmlspecialchars($item['foto_evidencia']) ?>"
                                                            data-bs-toggle="tooltip" data-bs-title="Ver evidencia">
                                                    <?php endif; ?>

                                                    <!-- Miniatura de PDF -->
                                                    <?php if (!empty($item['factura_pdf'])): ?>
                                                        <div class="mini-pdf d-inline-flex align-items-center justify-content-center border rounded"
                                                            style="width:50px; height:50px; background:#f8f9fa; cursor:pointer; text-decoration:none; transition:transform 0.2s;"
                                                            data-pdf="../<?= htmlspecialchars($item['factura_pdf']) ?>"
                                                            data-bs-toggle="tooltip" data-bs-title="Ver factura PDF"
                                                            onmouseover="this.style.transform='scale(1.1)'"
                                                            onmouseout="this.style.transform='scale(1)'">
                                                            <i class="bi bi-file-earmark-pdf-fill text-danger fs-4"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                            <?php if ($_SESSION['rol'] === 'Operador'): ?>
                                                <td class="acciones-col">
                                                    <form method="post" action="../php/update_estado.php"
                                                        class="d-flex flex-column flex-sm-row align-items-start form-actualizar-estado"
                                                        enctype="multipart/form-data">
                                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">

                                                        <!-- Select estado -->
                                                        <select name="estado"
                                                            class="form-select form-select-sm me-2 mb-2 mb-sm-0 select-estado"
                                                            data-estado-inicial="<?= $item['estado'] ?>" style="max-width: 200px;">
                                                            <option value="Pendiente" <?= $item['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                            <option value="En proceso" <?= $item['estado'] === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                                                            <option value="Adquirido" <?= $item['estado'] === 'Adquirido' ? 'selected' : '' ?>>Adquirido</option>
                                                            <option value="Rechazado" <?= $item['estado'] === 'Rechazado' ? 'selected' : '' ?>>Rechazado</option>
                                                        </select>

                                                        <!-- Foto existente -->
                                                        <input type="hidden" name="foto_existente"
                                                            value="<?= !empty($item['foto_evidencia']) ? '../' . htmlspecialchars($item['foto_evidencia']) : '' ?>">

                                                        <!-- Campo para subir/tomar foto -->
                                                        <div class="campo-foto mb-2 mb-sm-0 me-2"
                                                            style="display:none; max-width: 230px;">
                                                            <div class="input-group">
                                                                <input type="file" name="foto" accept="image/*;capture=camera"
                                                                    class="form-control form-control-sm"
                                                                    id="fotoInput-<?= $item['id'] ?>">
                                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                                    onclick="document.getElementById('fotoInput-<?= $item['id'] ?>').click()">
                                                                    <i class="bi bi-camera-fill"></i> Tomar foto
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <!-- Campo PDF -->
                                                        <div class="campo-pdf mb-2 mb-sm-0 me-2"
                                                            style="display:none; max-width: 230px;">
                                                            <div class="input-group">
                                                                <input type="file" name="factura_pdf" accept="application/pdf"
                                                                    class="form-control form-control-sm"
                                                                    id="pdfInput-<?= $item['id'] ?>">
                                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                                    onclick="document.getElementById('pdfInput-<?= $item['id'] ?>').click()">
                                                                    <i class="bi bi-file-earmark-arrow-up"></i> Adjuntar PDF
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <!-- Comentarios -->
                                                        <div class="flex-grow-1 me-2 mb-2 mb-sm-0" style="min-width: 150px;">
                                                            <textarea name="comentarios" class="form-control form-control-sm"
                                                                rows="2" placeholder="Comentarios opcionales"></textarea>
                                                        </div>

                                                        <button type="submit" class="btn btn-sm btn-success">Actualizar</button>
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


        <!-- Sección Confirmadas -->
        <div id="seccionConfirmadas" class="accordion requisiciones-section mt-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header card-header-azul-claro">
                    <strong>Requisiciones Confirmadas (Items Adquiridos o Rechazados)</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table id="tablaConfirmadas"
                            class="display table table-bordered table-sm align-middle responsive nowrap"
                            style="width:100%">
                            <thead class="thead">
                                <tr>
                                    <th># Req</th>
                                    <th>Producto</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Motivo</th>
                                    <th>Área</th>
                                    <th>Cedis</th>
                                    <th>Clave</th>
                                    <th>Estado</th>
                                    <th>📷</th>
                                    <th>📄</th>
                                    <th>Solicitada</th>
                                    <th>Actualización</th>
                                    <th>Comentarios</th>
                                    <th>Solicitante</th>
                                    <?php if (in_array($_SESSION['rol'], ['TI', 'Operador'])): ?>
                                        <th>Entrega</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Traer todos los items con estado 'Adquirido' o 'Rechazado' (independiente del estado de la requisición)
                                $items_adquiridos = $conn->query("
                                    SELECT i.*, r.id AS requisicion_id, u.usuario AS solicitante
                                    FROM items i
                                    JOIN requisiciones r ON i.requisicion_id = r.id
                                    JOIN usuarios u ON r.usuario_id = u.id
                                    WHERE i.estado IN ('Adquirido', 'Rechazado')
                                    ORDER BY i.updated_at DESC, i.id DESC
                                ");
                                if ($items_adquiridos) {
                                    while ($item = $items_adquiridos->fetch_assoc()):
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['requisicion_id']) ?></td>
                                            <td><?= htmlspecialchars($item['producto']) ?></td>

                                            <!-- Descripción -->
                                            <td class="colapsable-td" data-label="Descripción">
                                                <?php
                                                $descripcion_completa = htmlspecialchars($item['descripcion']);
                                                $descripcion_corta = mb_strimwidth($descripcion_completa, 0, 60, '...');
                                                $mostrar_ver_mas = mb_strlen($descripcion_completa) > 60;
                                                ?>
                                                <span class="texto-corta"><?= $descripcion_corta ?></span>
                                                <?php if ($mostrar_ver_mas): ?>
                                                    <span class="texto-completa d-none"><?= $descripcion_completa ?></span>

                                                <?php endif; ?>
                                            </td>

                                            <td><?= htmlspecialchars($item['cantidad']) ?></td>

                                            <!-- Motivo -->
                                            <td class="colapsable-td" data-label="Motivo">
                                                <?php
                                                $motivo_completo = htmlspecialchars($item['motivo']);
                                                $motivo_corta = mb_strimwidth($motivo_completo, 0, 60, '...');
                                                $mostrar_ver_mas_motivo = mb_strlen($motivo_completo) > 60;
                                                ?>
                                                <span class="texto-corta"><?= $motivo_corta ?></span>
                                                <?php if ($mostrar_ver_mas_motivo): ?>
                                                    <span class="texto-completa d-none"><?= $motivo_completo ?></span>

                                                <?php endif; ?>
                                            </td>

                                            <td><?= htmlspecialchars($item['area']) ?></td>
                                            <td><?= htmlspecialchars($item['cedis']) ?></td>
                                            <td><?= htmlspecialchars($item['clave']) ?></td>

                                            <td>
                                                <?php
                                                $color = ($item['estado'] === 'Rechazado') ? 'bg-danger' : 'bg-success';
                                                ?>
                                                <span
                                                    class="badge <?= $color ?>"><?= htmlspecialchars($item['estado']) ?></span>
                                            </td>

                                            <td>
                                                <?php if (!empty($item['foto_evidencia'])): ?>
                                                    <img src="../<?= htmlspecialchars($item['foto_evidencia']) ?>" alt="Evidencia"
                                                        class="mini-foto rounded border"
                                                        style="width:50px; height:50px; object-fit:cover; cursor:pointer;"
                                                        data-foto="../<?= htmlspecialchars($item['foto_evidencia']) ?>"
                                                        data-bs-toggle="tooltip" data-bs-title="Ver foto">
                                                <?php else: ?>
                                                    <span class="text-muted">Sin foto</span>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <?php if (!empty($item['factura_pdf'])): ?>
                                                    <div class="mini-pdf ms-2 d-inline-flex align-items-center justify-content-center border rounded"
                                                        style="width:50px; height:50px; background:#f8f9fa; cursor:pointer;"
                                                        data-pdf="../<?= htmlspecialchars($item['factura_pdf']) ?>"
                                                        data-bs-toggle="tooltip" data-bs-title="Ver factura PDF">
                                                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-4"></i>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin PDF</span>
                                                <?php endif; ?>
                                            </td>

                                            <td><?= $item['created_at'] ? date("d/m/Y H:i", strtotime($item['created_at'])) : '-' ?>
                                            </td>
                                            <td><?= $item['updated_at'] ? date("d/m/Y H:i", strtotime($item['updated_at'])) : '-' ?>
                                            </td>
                                            <td><?= !empty($item['comentarios']) ? htmlspecialchars($item['comentarios']) : '<span class="text-muted">Sin comentarios</span>' ?>
                                            </td>
                                            <td><?= htmlspecialchars($item['solicitante']) ?></td>

                                            <?php if (in_array($_SESSION['rol'], ['TI', 'Operador'])): ?>
                                                <td class="text-center">
                                                    <?php
                                                    // Determinar color del badge según el estado de entrega
                                                    if ($item['entrega_estado'] === 'Recibido') {
                                                        $color = 'bg-success text-white';
                                                    } elseif ($item['entrega_estado'] === 'Rechazado') {
                                                        $color = 'bg-danger text-white';
                                                    } else {
                                                        $color = 'bg-warning text-dark';
                                                    }
                                                    ?>

                                                    <?php if ($_SESSION['rol'] === 'TI' && $item['entrega_estado'] === 'Pendiente'): ?>
                                                        <!-- 🔹 TI puede modificar si está Pendiente -->
                                                        <select class="form-select form-select-sm estado-entrega <?= $color ?>"
                                                            data-id="<?= $item['id'] ?>"
                                                            style="min-width: 160px; max-width: 200px; text-align:center; font-weight:bold;">
                                                            <option value="Pendiente" <?= $item['entrega_estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                            <option value="Recibido" <?= $item['entrega_estado'] === 'Recibido' ? 'selected' : '' ?>>Recibido</option>
                                                            <option value="Rechazado" <?= $item['entrega_estado'] === 'Rechazado' ? 'selected' : '' ?>>Rechazado</option>
                                                        </select>
                                                    <?php else: ?>
                                                        <!-- 🔹 Operador o TI (cuando ya no es Pendiente) solo visualiza -->
                                                        <span class="badge <?= $color ?>"
                                                            style="font-size: 0.9rem; padding: 0.5em 1em;">
                                                            <?= htmlspecialchars($item['entrega_estado']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                        <?php
                                    endwhile;
                                } // endif query
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL Nueva requisición -->
        <div class="modal fade" id="modalNueva" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form id="formNuevaRequisicion">
                        <div class="modal-header">
                            <h5 class="modal-title">Nueva requisición</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div id="productosNueva">
                                <!-- Bloque producto -->
                                <div class="producto-item">
                                    <div class="row g-2 align-items-center">

                                        <!-- Producto -->
                                        <div class="col-md-1">
                                            <input type="text" name="producto[]" class="form-control"
                                                placeholder="Producto" required>
                                        </div>

                                        <!-- Clave inventario -->
                                        <div class="col-md-2">
                                            <input type="text" name="clave[]" class="form-control"
                                                placeholder="Clave Invent (Opcional)">
                                        </div>

                                        <!-- Descripción -->
                                        <div class="col-12 col-md-2">
                                            <textarea name="descripcion[]" class="form-control"
                                                placeholder="Descripción (opcional)" rows="1"></textarea>
                                        </div>

                                        <!-- Cantidad con unidad en el mismo campo -->
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <!-- Cantidad con unidad -->
                                                <input type="text" name="cantidad[]" class="form-control"
                                                    placeholder="Cantidad" id="cantidadInput" required>

                                                <!-- Unidad de Medida -->
                                                <select name="unidad[]" class="form-select" id="unidadSelect" required>
                                                    <option value="Pz">Pieza(s)</option>
                                                    <option value="m">Metro(s)</option>
                                                    <option value="Kit">Kit(s)</option>
                                                    <option value="Juego(s)">Juego(s)</option>
                                                    <option value="Bolsa">Bolsa(s)</option>
                                                    <option value="Bote">Bote(s)</option>
                                                    <option value="Paquete">Paquete(s)</option>
                                                    <option value="Rollo">Rollo(s)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Motivo -->
                                        <div class="col-md-2">
                                            <input type="text" name="motivo[]" class="form-control"
                                                placeholder="Motivo de la Solicitud" required>
                                        </div>

                                        <!-- Área -->
                                        <div class="col-md-1">
                                            <select class="form-control" name="area[]" required aria-required="true">
                                                <option value="">Área…</option>
                                                <option value="Adquisiciones">Adquisiciones</option>
                                                <option value="Administracion Cedis">Administracion Cedis</option>
                                                <option value="Administracion Refaccionaria">Administracion
                                                    Refaccionaria</option>
                                                <option value="Almacen">Almacen</option>
                                                <option value="Asistente de Direccion">Asistente de Direccion</option>
                                                <option value="Centro de Atencion al Clientes">Centro de Atención al
                                                    Cliente</option>
                                                <option value="Bodegas">Bodegas</option>
                                                <option value="Cedis">Cedis</option>
                                                <option value="Compras">Compras</option>
                                                <option value="Contabilidad">Contabilidad</option>
                                                <option value="Credito y Cobranza">Credito y Cobranza</option>
                                                <option value="Devoluciones">Devoluciones</option>
                                                <option value="Embarques">Embarques</option>
                                                <option value="Facturacion">Facturacion</option>
                                                <option value="Finanzas">Finanzas</option>
                                                <option value="Flotillas">Flotillas</option>
                                                <option value="Gerencia">Gerencia</option>
                                                <option value="IFuel">IFuel</option>
                                                <option value="Inventarios">Inventarios</option>
                                                <option value="Juridico">Juridico</option>
                                                <option value="Mercadotecnia">Mercadotecnia</option>
                                                <option value="Modelado de Productos">Modelado de Productos</option>
                                                <option value="Precios Especiales">Precios Especiales</option>
                                                <option value="Recursos Humanos">Recursos Humanos</option>
                                                <option value="Recepcion">Recepcion</option>
                                                <option value="Recepcion de Materiales">Recepcion de Materiales</option>
                                                <option value="Refaccionaria Actopan">Refaccionaria Actopan</option>
                                                <option value="Refaccionaria Madero">Refaccionaria Madero</option>
                                                <option value="Refaccionaria Minero">Refaccionaria Minero</option>
                                                <option value="Refaccionaria Tulancingo">Refaccionaria Tulancingo
                                                </option>
                                                <option value="Reabastos">Reabastos</option>
                                                <option value="Servicio Medico">Servicio Medico</option>
                                                <option value="Sistemas">Sistemas</option>
                                                <option value="Surtido Cedis">Surtido Cedis</option>
                                                <option value="Telemarketing">Telemarketing</option>
                                                <option value="Vigilancia">Vigilancia</option>
                                                <option value="Ventas">Ventas</option>
                                            </select>
                                        </div>

                                        <!-- CEDIS -->
                                        <div class="col-md-1">
                                            <select class="form-control" name="cedis[]" required aria-required="true">
                                                <option value="">Cedis…</option>
                                                <option value="PACHUCA">Pachuca</option>
                                                <option value="CANCUN">Cancun</option>
                                                <option value="CHIHUAHUA">Chihuahua</option>
                                                <option value="CULIACAN">Culiacan</option>
                                                <option value="CUERNAVACA">Cuernavaca</option>
                                                <option value="CORDOBA">Cordoba</option>
                                                <option value="GUADALAJARA">Guadalajara</option>
                                                <option value="HERMOSILLO">Hermosillo</option>
                                                <option value="LEON">Leon</option>
                                                <option value="MERIDA">Merida</option>
                                                <option value="MONTERREY">Monterrey</option>
                                                <option value="OAXACA">Oaxaca</option>
                                                <option value="PUEBLA">Puebla</option>
                                                <option value="QUERETARO">Queretaro</option>
                                                <option value="SAN LUIS POTOSI">San Luis Potosi</option>
                                                <option value="TUXTLA GUTIERREZ">Tuxtla Gutuerrez</option>
                                                <option value="VERACRUZ">Veracruz</option>
                                                <option value="VILLAHERMOSA">Villahermosa</option>
                                            </select>
                                        </div>

                                        <!-- Botón eliminar -->
                                        <div class="col-md-1 text-center">
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm eliminarProducto">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <hr> <!-- Línea separadora -->
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="agregarProductoNueva">
                                    ➕ Agregar otro
                                </button>
                            </div>
                        </div>

                        <!-- Campo para ingresar correos -->
                        <div class="form-group mb-3">
                            <label for="correos" class="form-label">📧 Enviar correo a:</label>
                            <div id="correos-container">
                                <div class="input-group mb-2">
                                    <input type="email" name="correos[]" class="form-control"
                                        placeholder="Ingrese un correo" required />
                                    <button type="button" class="btn btn-outline-secondary add-email-btn">
                                        <i class="bi bi-plus-square-fill"></i>
                                    </button>
                                </div>
                            </div>
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

        <!-- Modal Reenviar Correo -->
        <div class="modal fade" id="modalReenviarCorreo" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="formReenviarCorreo">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Reenviar correo de requisición</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="reenviarID" name="requisicion_id">

                            <label class="form-label">📧 Correos destinatarios:</label>
                            <div id="correos-container-reenviar">
                                <div class="input-group mb-2">
                                    <input type="email" name="correos_reenvio[]" class="form-control"
                                        placeholder="Ingrese un correo" required />
                                    <button type="button" class="btn btn-outline-secondary add-email-btn">
                                        <i class="bi bi-plus-square-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">📤 Enviar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contenedor de toasts -->
        <div id="toastContainer" class="toast-container position-fixed top-50 start-50 translate-middle p-3"></div>


        <div class="row justify-content-center text-center" id="contenedorGraficos">
            <!-- 🔹 Gráfico principal (más grande y arriba) -->
            <div class="col-12 mb-4 d-flex justify-content-center">
                <div id="grafico_area" class="grafico-principal"></div>
            </div>

            <!-- 🔹 Gráficos secundarios (dos en fila debajo, se apilan en móvil) -->
            <div class="col-12 col-md-6 d-flex justify-content-center mb-4">
                <div id="grafico_estado" class="grafico-secundario"></div>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center mb-4">
                <div id="grafico_mes" class="grafico-secundario"></div>
            </div>
        </div>
</body>

<!-- 🔹 Animación de entrada (solo una vez por sesión) -->
<script>
    window.addEventListener("DOMContentLoaded", () => {
        const hasVisited = sessionStorage.getItem('hasVisited');
        const anim = document.getElementById("entradaAnimacion");

        if (!hasVisited) {
            sessionStorage.setItem('hasVisited', 'true');
            setTimeout(() => {
                if (anim) anim.style.display = "none";
            }, 2000);
        } else {
            if (anim) anim.style.display = "none";
        }
    });
</script>

<!-- 🔹 jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- 🔹 Responsive plugin -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- 🔹 Inicializar DataTables -->
<script>
    $(document).ready(function () {
        let idx = -1;
        $('#tablaConfirmadas thead th').each(function (i) {
            const txt = $(this).text().trim().toLowerCase();
            if (txt.includes('actualización') || txt.includes('actualizacion')) {
                idx = i;
                return false;
            }
        });

        $('#tablaConfirmadas').DataTable({
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            scrollX: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[0, 'desc']],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: 1 },
                { responsivePriority: 3, targets: 2 },
                { responsivePriority: 4, targets: -1 }
            ]
        });
    });
</script>

<!-- 🔹 Etiquetas responsivas (data-label para celdas) -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const table = document.querySelector("#tablaConfirmadas");
        if (!table) return;

        const headers = Array.from(table.querySelectorAll("thead th")).map(th => th.textContent.trim());
        const rows = table.querySelectorAll("tbody tr");

        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            cells.forEach((cell, i) => {
                if (headers[i]) {
                    cell.setAttribute("data-label", headers[i]);
                }
            });
        });
    });
</script>

<!-- 🔹 Expandir/colapsar Descripción y Motivo al hacer clic -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Escucha clics en celdas colapsables
        document.addEventListener("click", function (e) {
            const td = e.target.closest(".colapsable-td");
            if (!td) return;

            const corta = td.querySelector(".texto-corta");
            const completa = td.querySelector(".texto-completa");
            if (!corta || !completa) return;

            const mostrandoCompleta = !completa.classList.contains("d-none");

            if (mostrandoCompleta) {
                completa.classList.add("d-none");
                corta.classList.remove("d-none");
            } else {
                completa.classList.remove("d-none");
                corta.classList.add("d-none");
            }
        });
    });
</script>

<!-- 🔹 Correo -->
<script>
    document.addEventListener('click', function (event) {
        // ➕ Añadir nuevo campo de correo
        if (event.target.closest('.add-email-btn')) {
            const container = document.getElementById('correos-container');

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

        // ❌ Eliminar campo de correo
        if (event.target.closest('.remove-email-btn')) {
            event.target.closest('.input-group').remove();
        }
    });

    // Validar correos al enviar formulario
    document.getElementById('formNuevaRequisicion').addEventListener('submit', function (e) {
        const emailInputs = document.querySelectorAll('input[name="correos[]"]');
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
            e.preventDefault();
            alert('⚠️ Por favor, ingrese todos los correos antes de continuar.');
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function actualizarOpciones(select) {
            const selected = select.value;

            // Habilitar todas las opciones primero
            select.querySelectorAll('option').forEach(opt => opt.disabled = false);

            if (selected === 'En proceso') {
                select.querySelector('option[value="Pendiente"]').disabled = true;
            }

            if (selected === 'Adquirido' || selected === 'Rechazado') {
                select.querySelector('option[value="Pendiente"]').disabled = true;
                select.querySelector('option[value="En proceso"]').disabled = true;
            }
        }

        // Al cargar la página, aplica la lógica según el estado actual
        document.querySelectorAll('.select-estado').forEach(select => {
            actualizarOpciones(select);

            select.addEventListener('change', function () {
                actualizarOpciones(this);
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        // 1️⃣ Ocultar todos los cuerpos al iniciar
        $('.card-body').hide();

        // 2️⃣ Mostrar por defecto las confirmadas
        $('#seccionConfirmadas .card-body').show();
        $('#seccionConfirmadas .card-header').addClass('active');

        // 3️⃣ Toggle al hacer clic en el encabezado
        $(document).on('click', '.card-header', function (e) {
            // Evita que el botón "Reenviar correo" dispare el toggle
            if ($(e.target).closest('.btn-reenviar-correo').length) return;

            const $card = $(this).closest('.card');
            const $body = $card.find('.card-body');
            const $icon = $(this).find('.toggle-icon');

            // Alternar visibilidad
            $body.stop(true, true).slideToggle(250);
            $(this).toggleClass('active');

            // Rotar icono si existe
            if ($icon.length) {
                $icon.toggleClass('rotado');
            }
        });
    });
</script>


<!-- Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<script src="../js/botones_secciones.js"></script>
<script src="../js/principal.js"></script>
<script src="../js/guardar_requisicion.js"></script>
<script src="../js/actualizar_estado_item.js"></script>
<script src="../js/mostrar_ocultar_foto_pdf.js"></script>
<script src="../js/requisicion_confirmadas.js"></script>
<script src="../js/reenviar_correo.js"></script>
<script src="../js/graficas.js"></script>


<?php include '../css/footer.php'; ?>

</html>