<?php
date_default_timezone_set('America/Mexico_City');
session_name('Compras');
session_start();
require 'connect.php';

// Solo usuarios con rol TI pueden actualizar estado
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'TI') {
    http_response_code(403);
    echo json_encode(["success" => false, "error" => "No autorizado"]);
    exit();
}

$conn = connectMysqli();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"], $_POST["entrega_estado"])) {
    $id = intval($_POST["id"]);
    $entrega_estado = $_POST["entrega_estado"];

    // ✅ Solo actualiza el campo entrega_estado
    $stmt = $conn->prepare("
        UPDATE items 
        SET entrega_estado = ?
        WHERE id = ?
    ");
    $stmt->bind_param("si", $entrega_estado, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
