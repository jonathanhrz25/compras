<?php
$host = 'mail.smtp2go.com';
$ports = [2525, 587, 8025, 80, 443];

foreach ($ports as $port) {
    echo "Probando $host:$port ... ";
    $connection = @fsockopen($host, $port, $errno, $errstr, 5);
    if ($connection) {
        echo "✅ Conexión exitosa<br>";
        fclose($connection);
    } else {
        echo "❌ Falló ($errstr)<br>";
    }
}
