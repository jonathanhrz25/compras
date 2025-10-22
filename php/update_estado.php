<?php
date_default_timezone_set('America/Mexico_City');
session_name('Compras');
session_start();
require '../php/connect.php';

// Solo Operadores pueden actualizar estado
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Operador') {
    http_response_code(403);
    echo json_encode(["success" => false, "error" => "No autorizado"]);
    exit();
}

$conn = connectMysqli();

// Validar entrada
$item_id = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;
$estado = $_POST['estado'] ?? '';
$comentarios = $_POST['comentarios'] ?? '';

$estados_validos = ['Pendiente', 'En proceso', 'Adquirido', 'Rechazado'];

if ($item_id > 0 && in_array($estado, $estados_validos)) {

    $rutaFoto = null;
    $rutaPDF = null;

    // 🟢 Guardar foto si se subió
    if ($estado === "Adquirido" && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $carpetaDestino = dirname(__DIR__) . '/fotos/';
        if (!is_dir($carpetaDestino)) mkdir($carpetaDestino, 0777, true);

        $nombreArchivo = uniqid("foto_") . "_" . basename($_FILES["foto"]["name"]);
        $rutaRelativa = "fotos/" . $nombreArchivo;
        $rutaCompleta = $carpetaDestino . $nombreArchivo;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaCompleta)) {
            $rutaFoto = $rutaRelativa;
        }
    }

    // 🟢 Guardar PDF si se subió
    if ($estado === "Adquirido" && isset($_FILES['factura_pdf']) && $_FILES['factura_pdf']['error'] === UPLOAD_ERR_OK) {
        $carpetaPDF = dirname(__DIR__) . '/factu_pdf/';
        if (!is_dir($carpetaPDF)) mkdir($carpetaPDF, 0777, true);

        $nombrePDF = uniqid("factura_") . "_" . basename($_FILES["factura_pdf"]["name"]);
        $rutaRelPDF = "factu_pdf/" . $nombrePDF;
        $rutaAbsPDF = $carpetaPDF . $nombrePDF;

        $ext = strtolower(pathinfo($nombrePDF, PATHINFO_EXTENSION));
        if ($ext === 'pdf' && move_uploaded_file($_FILES["factura_pdf"]["tmp_name"], $rutaAbsPDF)) {
            $rutaPDF = $rutaRelPDF;
        }
    }

    // 🧩 Armar la consulta dinámica
    if ($rutaFoto && $rutaPDF) {
        $stmt = $conn->prepare("UPDATE items SET estado = ?, comentarios = ?, foto_evidencia = ?, factura_pdf = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $estado, $comentarios, $rutaFoto, $rutaPDF, $item_id);
    } elseif ($rutaFoto) {
        $stmt = $conn->prepare("UPDATE items SET estado = ?, comentarios = ?, foto_evidencia = ? WHERE id = ?");
        $stmt->bind_param("sssi", $estado, $comentarios, $rutaFoto, $item_id);
    } elseif ($rutaPDF) {
        $stmt = $conn->prepare("UPDATE items SET estado = ?, comentarios = ?, factura_pdf = ? WHERE id = ?");
        $stmt->bind_param("sssi", $estado, $comentarios, $rutaPDF, $item_id);
    } else {
        $stmt = $conn->prepare("UPDATE items SET estado = ?, comentarios = ? WHERE id = ?");
        $stmt->bind_param("ssi", $estado, $comentarios, $item_id);
    }

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "estado" => $estado,
            "comentarios" => $comentarios !== "" ? $comentarios : "Sin comentarios",
            "foto" => $rutaFoto,
            "pdf" => $rutaPDF,
            "updated_at" => date("Y-m-d H:i")
        ]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Datos inválidos"]);
    exit();
}
?>
