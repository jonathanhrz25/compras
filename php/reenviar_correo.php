<?php
// php/reenviar_correo.php
header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');
session_name('Compras');
session_start();
require 'connect.php';

require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

if (!isset($_POST['requisicion_id'])) {
    echo json_encode(["status" => "error", "message" => "ID no proporcionado"]);
    exit;
}
$requisicion_id = (int) $_POST['requisicion_id'];
if ($requisicion_id <= 0) {
    echo json_encode(["status" => "error", "message" => "ID inválido"]);
    exit;
}

$correos_raw = $_POST['correos'] ?? [];
if (!is_array($correos_raw))
    $correos_raw = [$correos_raw];

$correos = [];
foreach ($correos_raw as $c) {
    $c = trim($c);
    if ($c !== '' && filter_var($c, FILTER_VALIDATE_EMAIL)) {
        $correos[] = $c;
    }
}
$correos = array_values(array_unique($correos));
if (empty($correos)) {
    echo json_encode(["status" => "error", "message" => "No hay correos válidos"]);
    exit;
}

$conn = connectMysqli();

// ===================================================
// 🔹 Actualizar correos en la base de datos sin duplicar
// ===================================================
$stmt_correos = $conn->prepare("SELECT correo_destinatario FROM items WHERE requisicion_id = ?");
$stmt_correos->bind_param("i", $requisicion_id);
$stmt_correos->execute();
$res_correos = $stmt_correos->get_result();

$existentes = [];
while ($row = $res_correos->fetch_assoc()) {
    $lista = array_map('trim', explode(',', $row['correo_destinatario'] ?? ''));
    foreach ($lista as $mail) {
        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $existentes[] = strtolower($mail);
        }
    }
}
$stmt_correos->close();

// 🔸 Normalizar nuevos correos en minúsculas
$nuevos = array_map('strtolower', $correos);

// 🔸 Fusionar sin duplicar
$todos = array_unique(array_merge($existentes, $nuevos));

// 🔸 Si hay nuevos correos que antes no estaban, actualiza el campo
$nuevos_a_guardar = array_diff($nuevos, $existentes);

if (!empty($nuevos_a_guardar)) {
    $correos_str = implode(', ', $todos);
    $stmt_update = $conn->prepare("UPDATE items SET correo_destinatario = ? WHERE requisicion_id = ?");
    $stmt_update->bind_param("si", $correos_str, $requisicion_id);
    $stmt_update->execute();
    $stmt_update->close();
}

// =================================================================
// 🔹 Obtener información de la requisición - En estado "Pendientes"
// =================================================================
$stmt = $conn->prepare("
    SELECT i.*, r.id AS requisicion_id, r.fecha AS req_fecha, u.usuario AS solicitante
    FROM items i
    JOIN requisiciones r ON i.requisicion_id = r.id
    JOIN usuarios u ON r.usuario_id = u.id
    WHERE i.requisicion_id = ? 
      AND i.estado = 'Pendiente'
    ORDER BY i.id ASC
");
$stmt->bind_param("i", $requisicion_id);
$stmt->execute();
$result = $stmt->get_result();

$items_for_email = [];
$solicitante = '';
$req_fecha = null;

while ($row = $result->fetch_assoc()) {
    $items_for_email[] = [
        'producto' => $row['producto'],
        'descripcion' => $row['descripcion'],
        'cantidad' => $row['cantidad'],
        'motivo' => $row['motivo'],
        'area' => $row['area'],
        'cedis' => $row['cedis'],
        'clave' => $row['clave'],
        'estado' => $row['estado']
    ];
    if (empty($solicitante))
        $solicitante = $row['solicitante'] ?? '';
    if (empty($req_fecha))
        $req_fecha = $row['req_fecha'] ?? null;
}
$stmt->close();

if (empty($items_for_email)) {
    echo json_encode([
        "status" => "error",
        "message" => "No hay ítems pendientes para reenviar en esta requisición"
    ]);
    exit;
}

// ===================================================
// 🔹 Crear Excel con mismo diseño que guardar_requisicion.php
// ===================================================
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Requisición');

$logoPath = realpath(__DIR__ . '/../img/icono2.png');
if ($logoPath && file_exists($logoPath)) {
    $drawing = new Drawing();
    $drawing->setName('Logo');
    $drawing->setDescription('Logo Serva');
    $drawing->setPath($logoPath);
    $drawing->setHeight(60);
    $drawing->setCoordinates('A1');
    $drawing->setOffsetX(5);
    $drawing->setOffsetY(5);
    $drawing->setWorksheet($sheet);
}

$sheet->mergeCells('C2:H2');
$sheet->setCellValue('C2', 'REQUISICIÓN DE COMPRA');
$sheet->getStyle('C2')->applyFromArray([
    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1F4E78']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

$startRow = 4;

$headers = ['ID Requisición', 'Producto', 'Descripción', 'Cantidad', 'Motivo', 'Área', 'CEDIS', 'Clave', 'Estado', 'Solicitante', 'Fecha de creación'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . $startRow, $header);
    $col++;
}

$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '081856']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];
$sheet->getStyle('A' . $startRow . ':K' . $startRow)->applyFromArray($headerStyle);

$row = $startRow + 1;
$fecha_display = $req_fecha ? date("d/m/Y H:i", strtotime($req_fecha)) : date("d/m/Y H:i");
foreach ($items_for_email as $it) {
    $sheet->setCellValue('A' . $row, $requisicion_id);
    $sheet->setCellValue('B' . $row, $it['producto']);
    $sheet->setCellValue('C' . $row, $it['descripcion']);
    $sheet->setCellValue('D' . $row, $it['cantidad']);
    $sheet->setCellValue('E' . $row, $it['motivo']);
    $sheet->setCellValue('F' . $row, $it['area']);
    $sheet->setCellValue('G' . $row, $it['cedis']);
    $sheet->setCellValue('H' . $row, $it['clave']);
    $sheet->setCellValue('I' . $row, $it['estado']);
    $sheet->setCellValue('J' . $row, $solicitante);
    $sheet->setCellValue('K' . $row, $fecha_display);
    $row++;
}

$sheet->getStyle('A' . ($startRow + 1) . ':K' . ($row - 1))->applyFromArray([
    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);
foreach (range('A', 'K') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$tmpFile = sys_get_temp_dir() . "/requisicion_{$requisicion_id}_" . time() . ".xlsx";
$writer = new Xlsx($spreadsheet);
$writer->save($tmpFile);

// ===================================================
// 🔹 Construir cuerpo del correo (solo ítems pendientes)
// ===================================================
$emailBody = '<html><head><meta charset="UTF-8"></head><body>';
$emailBody .= "<p>Hola, buen día.</p>";

$emailBody .= "<p>Se ha generado el <strong>reenvío</strong> de la requisición 
<strong>#".htmlspecialchars($requisicion_id)."</strong> en fecha 
<strong>{$fecha_display}</strong>.</p>";

$emailBody .= "<p><em>Este reenvío incluye únicamente los ítems que permanecen en estado <strong>Pendiente</strong> sobre esta #requisicion.</em></p>";

$emailBody .= "<p><a href='http://localhost/compras/php/inicioSesion.php' target='_blank' 
style='display:inline-block; padding:10px 18px; background:#0d6efd; color:#fff; 
text-decoration:none; border-radius:5px; font-weight:bold;'>Ingresar al Sistema de Adquisiciones</a></p>";

$emailBody .= '<p><strong>Resumen de artículos pendientes:</strong></p>';
$emailBody .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">';
$emailBody .= '<thead><tr style="background:#f2f2f2;">
<th>Producto</th><th>Motivo</th><th>Área</th><th>CEDIS</th><th>Solicitante</th></tr></thead><tbody>';

foreach ($items_for_email as $it) {
    $emailBody .= '<tr>';
    $emailBody .= '<td>' . htmlspecialchars($it['producto']) . '</td>';
    $emailBody .= '<td>' . htmlspecialchars($it['motivo']) . '</td>';
    $emailBody .= '<td>' . htmlspecialchars($it['area']) . '</td>';
    $emailBody .= '<td>' . htmlspecialchars($it['cedis']) . '</td>';
    $emailBody .= '<td>' . htmlspecialchars($solicitante) . '</td>';
    $emailBody .= '</tr>';
}

$emailBody .= '</tbody></table>';
$emailBody .= '<p style="margin-top:15px; font-size:13px; color:#666;">Atentamente,<br><strong>Sistema de Compras Serva</strong></p>';
$emailBody .= '</body></html>';


// ===================================================
// 🔹 Enviar correo con PHPMailer
// ===================================================
$mail_sent = false;
$mail_error = null;

try {
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'mail.smtp2go.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ticket@serva.com.mx';
    $mail->Password = 'Serva123.*';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 2525;

    $mail->setFrom('ticket@serva.com.mx', 'Sistema Compras');
    foreach ($correos as $c) {
        $mail->addAddress($c);
    }

    if (file_exists($tmpFile)) {
        $mail->addAttachment($tmpFile, "Requisicion_{$requisicion_id}.xlsx");
    }

    $mail->isHTML(true);
    $mail->Subject = "Reenvío: Requisición #{$requisicion_id}";
    $mail->Body = $emailBody;
    $mail->AltBody = strip_tags($emailBody);

    $mail_sent = $mail->send();
} catch (Exception $e) {
    $mail_error = $mail->ErrorInfo ?? $e->getMessage();
}

// 🔹 Eliminar archivo temporal
if (file_exists($tmpFile))
    unlink($tmpFile);

if ($mail_sent) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $mail_error ?? 'Error al enviar correo']);
}
exit;
