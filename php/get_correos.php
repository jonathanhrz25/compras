<?php
// php/get_correos.php
header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');
session_name('Compras');
session_start();
require 'connect.php';

if (!isset($_GET['requisicion_id'])) {
    echo json_encode(["success" => false, "error" => "ID no proporcionado"]);
    exit;
}

$requisicion_id = (int) $_GET['requisicion_id'];
if ($requisicion_id <= 0) {
    echo json_encode(["success" => false, "error" => "ID inválido"]);
    exit;
}

$conn = connectMysqli();

// Obtener todos los valores de correo_destinatario de items de esa requisición
$stmt = $conn->prepare("SELECT DISTINCT correo_destinatario FROM items WHERE requisicion_id = ?");
$stmt->bind_param("i", $requisicion_id);
$stmt->execute();
$res = $stmt->get_result();

$emails = [];
while ($row = $res->fetch_assoc()) {
    $val = trim($row['correo_destinatario']);
    if ($val === '') continue;
    // correo_destinatario puede contener múltiples correos separados por coma
    $parts = array_map('trim', explode(',', $val));
    foreach ($parts as $p) {
        if ($p !== '' && filter_var($p, FILTER_VALIDATE_EMAIL)) {
            $emails[] = $p;
        }
    }
}
$stmt->close();
$conn->close();

// eliminar duplicados y reindexar
$emails = array_values(array_unique($emails));

echo json_encode(["success" => true, "emails" => $emails]);
exit;
