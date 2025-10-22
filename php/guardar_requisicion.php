<?php
date_default_timezone_set('America/Mexico_City');
session_name('Compras');
session_start();
require '../php/connect.php';

// Rutas a PHPMailer
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

// PhpSpreadsheet para generar el Excel
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "No autorizado"]);
    exit();
}

$conn = connectMysqli();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto'])) {
    $usuario_id = $_SESSION['user_id'];
    $usuario_nombre = $_SESSION['usuario'] ?? 'Usuario';
    $fecha = date("Y-m-d H:i:s");

    // ------------------------
    // Validar y limpiar correos
    // ------------------------
    $correos_raw = $_POST['correos'] ?? [];
    if (!is_array($correos_raw)) {
        $correos_raw = [$correos_raw];
    }

    $correos = [];
    foreach ($correos_raw as $c) {
        $c = trim($c);
        if ($c !== '' && filter_var($c, FILTER_VALIDATE_EMAIL)) {
            $correos[] = $c;
        }
    }
    $correo_item = implode(',', $correos);

    // ------------------------
    // Insertar cabecera requisición
    // ------------------------
    $stmt = $conn->prepare("INSERT INTO requisiciones (usuario_id, fecha) VALUES (?, ?)");
    $stmt->bind_param("is", $usuario_id, $fecha);
    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "error" => "Error al crear requisición"]);
        exit();
    }
    $requisicion_id = $stmt->insert_id;
    $stmt->close();

    // ------------------------
    // Insertar items
    // ------------------------
    $stmt = $conn->prepare("INSERT INTO items 
        (requisicion_id, producto, descripcion, cantidad, motivo, area, cedis, clave, correo_destinatario, estado, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pendiente', NOW(), NULL)");

    $productos = $_POST['producto'];
    $descripciones = $_POST['descripcion'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];
    $motivos = $_POST['motivo'] ?? [];
    $areas = $_POST['area'] ?? [];
    $cedises = $_POST['cedis'] ?? [];
    $claves = $_POST['clave'] ?? [];

    $items_for_email = [];

    foreach ($productos as $i => $producto) {
        $producto = trim((string) $producto);
        $descripcion = trim((string) ($descripciones[$i] ?? ''));
        $cantidad = trim((string) ($cantidades[$i] ?? ''));
        $motivo = trim((string) ($motivos[$i] ?? ''));
        $area = trim((string) ($areas[$i] ?? ''));
        $cedis = trim((string) ($cedises[$i] ?? ''));
        $clave = trim((string) ($claves[$i] ?? ''));

        if ($producto !== "" && $cantidad !== "") {
            $stmt->bind_param(
                "issssssss",
                $requisicion_id,
                $producto,
                $descripcion,
                $cantidad,
                $motivo,
                $area,
                $cedis,
                $clave,
                $correo_item
            );
            $stmt->execute();

            $items_for_email[] = [
                'producto' => $producto,
                'descripcion' => $descripcion,
                'cantidad' => $cantidad,
                'motivo' => $motivo,
                'area' => $area,
                'cedis' => $cedis,
                'clave' => $clave
            ];
        }
    }
    $stmt->close();

    // ------------------------
    // Crear Excel con logo y estilo profesional
    // ------------------------
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Requisición');

    // Agregar el logo
    $logoPath = realpath(__DIR__ . '/../img/icono2.png');
    if (file_exists($logoPath)) {
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

    // Encabezado del documento
    $sheet->mergeCells('C2:H2');
    $sheet->setCellValue('C2', 'REQUISICIÓN DE COMPRA');
    $sheet->getStyle('C2')->applyFromArray([
        'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1F4E78']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
    ]);

    // Espaciado
    $startRow = 4;

    // Encabezados de tabla
    $headers = ['ID Requisición', 'Producto', 'Descripción', 'Cantidad', 'Motivo', 'Área', 'CEDIS', 'Clave', 'Estado', 'Solicitante', 'Fecha de creación'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . $startRow, $header);
        $col++;
    }

    // Estilo de encabezados
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '081856']],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ];
    $sheet->getStyle('A' . $startRow . ':K' . $startRow)->applyFromArray($headerStyle);

    // Llenar los datos
    $row = $startRow + 1;
    $fecha_display = date("d/m/Y H:i", strtotime($fecha));
    foreach ($items_for_email as $it) {
        $sheet->setCellValue('A' . $row, $requisicion_id);
        $sheet->setCellValue('B' . $row, $it['producto']);
        $sheet->setCellValue('C' . $row, $it['descripcion']);
        $sheet->setCellValue('D' . $row, $it['cantidad']);
        $sheet->setCellValue('E' . $row, $it['motivo']);
        $sheet->setCellValue('F' . $row, $it['area']);
        $sheet->setCellValue('G' . $row, $it['cedis']);
        $sheet->setCellValue('H' . $row, $it['clave']);
        $sheet->setCellValue('I' . $row, 'Pendiente');
        $sheet->setCellValue('J' . $row, $usuario_nombre);
        $sheet->setCellValue('K' . $row, $fecha_display);
        $row++;
    }

    // Bordes, alineación y autoajuste
    $sheet->getStyle('A' . ($startRow + 1) . ':K' . ($row - 1))->applyFromArray([
        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);
    foreach (range('A', 'K') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Guardar temporalmente
    $excelFilePath = sys_get_temp_dir() . "/requisicion_{$requisicion_id}.xlsx";
    $writer = new Xlsx($spreadsheet);
    $writer->save($excelFilePath);

    // ------------------------
    // Construir correo
    // ------------------------
    $emailBody = '<html><head><meta charset="UTF-8"></head><body>';
    $emailBody .= "<p>Hola,</p>";
    $emailBody .= "<p>Se ha generado una nueva requisición <strong>#"
        . htmlspecialchars($requisicion_id) . "</strong> en fecha <strong>" . $fecha_display . "</strong>.</p>";
    $emailBody .= "<p><a href='https://serva.com.mx:5002/compras/php/inicioSesion.php' target='_blank' 
            style='display:inline-block; padding:10px 18px; background:#0d6efd; color:#fff; 
            text-decoration:none; border-radius:5px; font-weight:bold;'>Ingresar a Sistema de Adquisiciones</a></p>";
    $emailBody .= '<p><strong>Resumen de artículos:</strong></p>';
    $emailBody .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">';
    $emailBody .= '<thead><tr style="background:#f2f2f2;"><th>Producto</th><th>Descripción</th><th>Cantidad</th><th>Motivo</th><th>Área</th><th>CEDIS</th><th>Clave</th></tr></thead><tbody>';
    foreach ($items_for_email as $it) {
        $emailBody .= '<tr>';
        $emailBody .= '<td>' . htmlspecialchars($it['producto']) . '</td>';
        $emailBody .= '<td>' . htmlspecialchars($it['descripcion']) . '</td>';
        $emailBody .= '<td style="text-align:center;">' . htmlspecialchars($it['cantidad']) . '</td>';
        $emailBody .= '<td>' . htmlspecialchars($it['motivo']) . '</td>';
        $emailBody .= '<td>' . htmlspecialchars($it['area']) . '</td>';
        $emailBody .= '<td>' . htmlspecialchars($it['cedis']) . '</td>';
        $emailBody .= '<td>' . htmlspecialchars($it['clave']) . '</td>';
        $emailBody .= '</tr>';
    }
    $emailBody .= '</tbody></table>';
    $emailBody .= '</body></html>';


    // ------------------------
    // Enviar correo con Excel adjunto
    // ------------------------
    $mail_sent = false;
    $mail_error = null;
    if (!empty($correos)) {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        try {
            $mail->isSMTP();
            $mail->Host = 'mail.smtp2go.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ticket@serva.com.mx';
            $mail->Password = 'Serva123.*';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;

            $mail->setFrom('ticket@serva.com.mx', 'Solicitudes Sistemas');
            foreach ($correos as $c) {
                $mail->addAddress($c);
            }

            if (file_exists($excelFilePath)) {
                $mail->addAttachment($excelFilePath, "Requisicion_{$requisicion_id}.xlsx");
            }

            $mail->isHTML(true);
            $mail->Subject = "Nueva requisición #{$requisicion_id} - {$usuario_nombre}";
            $mail->Body = $emailBody;
            $mail->AltBody = strip_tags($emailBody);

            $mail_sent = $mail->send();

            // Eliminar archivo temporal
            if (file_exists($excelFilePath)) {
                unlink($excelFilePath);
            }
        } catch (Exception $e) {
            $mail_error = $mail->ErrorInfo ?? $e->getMessage();
        }
    }

    echo json_encode([
        "success" => true,
        "requisicion_id" => $requisicion_id,
        "fecha" => $fecha_display,
        "solicitante" => $usuario_nombre,
        "mail_sent" => $mail_sent,
        "mail_error" => $mail_error
    ]);
    exit();
}

http_response_code(400);
echo json_encode(["error" => "Solicitud inválida"]);
