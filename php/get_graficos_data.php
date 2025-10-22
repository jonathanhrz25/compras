<?php
require_once "connect.php";
$conn = connectMysqli();

// Validar conexión
if (!$conn) {
    echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
    exit;
}

$data = [];

// 1️⃣ Conteo por estado
$q1 = $conn->query("SELECT COALESCE(estado, 'Sin estado') AS estado, COUNT(*) AS total FROM items GROUP BY estado");
$estado_data = [];
while ($r = $q1->fetch_assoc()) {
    $estado_data[] = [$r['estado'], (int) $r['total']];
}
$data['por_estado'] = $estado_data;

// 2️⃣ Conteo por área
$q2 = $conn->query("SELECT COALESCE(area, 'Sin área') AS area, COUNT(*) AS total FROM items GROUP BY area");
$area_data = [];
while ($r = $q2->fetch_assoc()) {
    $area_data[] = [$r['area'], (int) $r['total']];
}
$data['por_area'] = $area_data;

// 3️⃣ Requisiciones por mes (basado en created_at)
$q3 = $conn->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS mes, COUNT(*) AS total
    FROM items
    WHERE created_at IS NOT NULL
    GROUP BY mes
    ORDER BY mes ASC
");
$mes_data = [];
while ($r = $q3->fetch_assoc()) {
    $mes_data[] = [$r['mes'], (int) $r['total']];
}
$data['por_mes'] = $mes_data;

// 4️⃣ Estado de entrega
$q4 = $conn->query("SELECT COALESCE(entrega_estado, 'Sin definir') AS entrega_estado, COUNT(*) AS total FROM items GROUP BY entrega_estado");
$entrega_data = [];
while ($r = $q4->fetch_assoc()) {
    $entrega_data[] = [$r['entrega_estado'], (int) $r['total']];
}
$data['por_entrega'] = $entrega_data;

header("Content-Type: application/json");
echo json_encode($data, JSON_UNESCAPED_UNICODE);
