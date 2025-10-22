-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-10-2025 a las 18:53:13
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `compras`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `requisicion_id` int(11) NOT NULL,
  `producto` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `cantidad` varchar(200) NOT NULL,
  `estado` enum('Pendiente','En proceso','Adquirido','Rechazado') DEFAULT 'Pendiente',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `comentarios` text DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `cedis` varchar(100) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `entrega_estado` enum('Recibido','Rechazado','Pendiente') DEFAULT 'Pendiente',
  `foto_evidencia` varchar(255) DEFAULT NULL,
  `factura_pdf` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`, `requisicion_id`, `producto`, `descripcion`, `cantidad`, `estado`, `updated_at`, `created_at`, `comentarios`, `motivo`, `area`, `cedis`, `clave`, `entrega_estado`, `foto_evidencia`, `factura_pdf`) VALUES
(1, 1, 'CYBERPOWER', 'No Break CyberPower OM750ATLCD L?nea Interactiva 420W 750VA Entrada 82-148V 6 Contactos', '9Pieza(s)', 'Adquirido', '2025-03-05 17:00:00', '2025-03-04 12:00:00', NULL, 'Remplazos de no-break ', 'Telemarketing', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(2, 1, 'MAUSE', 'Kit de Teclado y Mouse Logitech MK220 Inal?mbrico USB Negro (Espanol)', '1Kit(s)', 'Adquirido', '2025-03-05 17:00:00', '2025-03-04 12:00:00', NULL, 'YA NO FUNCIONA BIEN EL LECTOR DEL MOUSE', 'DEVOLUACIONES', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(3, 1, 'NO BREAK', 'No Break CyberPower OM750ATLCD L?nea Interactiva 420W 750VA Entrada 82-148V 6 Contactos ', '2Pieza(s)', 'Adquirido', '2025-03-05 17:00:00', '2025-03-04 12:00:00', NULL, 'REMPLAZO DE NO BREAK POR FALA', 'EMBARQUES', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(4, 1, 'BATERIA', 'modelo de MAcbook: A2289', '1Pieza(s)', 'Adquirido', '2025-03-05 17:00:00', '2025-03-04 12:00:00', NULL, 'FALLA LA BATERIA POR FINAL DE VIDA UTIL ', 'IMPLEMENTACIONES', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(5, 1, 'KIT DE MANTENIMIENTO ', 'CANNON D1620', '1Kit(s)', 'Adquirido', '2025-03-05 17:00:00', '2025-03-04 12:00:00', NULL, 'KIT DE MANTENIMIENTO DAnADO', 'FACTURACION', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(6, 1, 'CAMARA', 'Dahua C?mara CCTV Domo IR para Interiores/Exteriores HAC-HDW1500TLQ-A Al?mbrico 2880 x 1620 Pixeles D?a/Noche', '1Pieza(s)', 'Adquirido', '2025-03-05 17:00:00', '2025-03-04 12:00:00', NULL, 'REPOSICION DE CAMARA ROTA POR ESCALERA ', 'ALMACEN', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(7, 1, 'TARJETA MADRE', 'Tarjeta madre para laptop Dell modelo INSPIRON 13 5310', '1Pieza(s)', 'Adquirido', '2025-03-05 17:00:00', '2025-03-04 12:00:00', NULL, 'FALLA DE TARJETA MADRE ', 'VENTAS', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(8, 2, 'KIT ', 'Kit de Teclado y Mouse Dell KM300C Al?mbrico USB Negro Espanol', '9Kit(s)', 'Adquirido', '2025-03-16 17:00:00', '2025-03-15 12:00:00', NULL, 'mucho desgaste y falla por equipo viejo ', 'CREDITO Y COBRANZA', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(9, 2, 'Disco duro ', 'Disco ssd 240 GB Wester Digital', '1Pieza(s)', 'Adquirido', '2025-03-16 17:00:00', '2025-03-15 12:00:00', NULL, 'mucho desgaste y falla por equipo viejo ', 'CREDITO Y COBRANZA', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(10, 3, 'CPU', 'PC COREI3 8 RAM SSD MAYOR A 256', '1Pieza(s)', 'Adquirido', '2025-03-20 17:00:00', '2025-03-19 12:00:00', NULL, 'QUIPO YA NO ES FUNCIONAL', 'DEVOLUCIONES', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(11, 3, 'MONITOR ', '22 PULGADAS ', '1Pieza(s)', 'Adquirido', '2025-03-20 17:00:00', '2025-03-19 12:00:00', NULL, 'QUIPO YA NO ES FUNCIONAL', 'DEVOLUCIONES', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(12, 3, 'NO BREAK', 'NO BREACK ', '1Pieza(s)', 'Adquirido', '2025-03-20 17:00:00', '2025-03-19 12:00:00', NULL, 'QUIPO YA NO ES FUNCIONAL', 'DEVOLUCIONES', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(13, 4, 'AP', 'Access Point TP-Link de Banda Dual EAP225 V3', '1Pieza(s)', 'Adquirido', '2025-03-21 17:00:00', '2025-03-20 12:00:00', NULL, 'Material para mantenimiento y actualizacion de DVR', 'CAMARAS', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(14, 4, 'DISCO DURO', 'Disco Duro 8tb Western Digital Purple Pro', '1Pieza(s)', 'Adquirido', '2025-03-21 17:00:00', '2025-03-20 12:00:00', NULL, 'Material para mantenimiento y actualizacion de DVR', 'CAMARAS', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(15, 5, 'LECTOR ', 'Honeywell Voyager 1202G Lector de Codigo de Barras Laser 1D - incluye Cable USB y Base', '1Pieza(s)', 'Adquirido', '2025-03-22 17:00:00', '2025-03-21 12:00:00', NULL, 'APERTURA DE MESA PARA SURTIDO DE PEDIDOS', 'MESA 8', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(16, 6, 'IMPRESORA DE ETIQUETAS', 'Zebra ZD220 Impresora de Etiquetas', '2Pieza(s)', 'Adquirido', '2025-03-27 17:00:00', '2025-03-26 12:00:00', NULL, 'IMPRESORA PARA MESAS DE SURTIDO', 'SURTIDO', 'Pachuca', NULL, 'Recibido', NULL, NULL),
(17, 7, 'KX-TGB110MEB', 'Panasonic Tel?fono Inal?mbrico DECT KX-TGB110', '1Pieza(s)', 'Adquirido', '2025-04-03 17:00:00', '2025-04-02 12:00:00', NULL, 'Panasonic Telefono Inalambrico DECT KX-TGB110', 'VERACRUZ', 'Veracruz', NULL, 'Recibido', NULL, NULL),
(18, 8, '46901', 'ROLLO DE 50 METROS DE TUBO FLEXIBLE METALICO 3/4 VOLTECK', '1Rollo(s)', 'Adquirido', '2025-04-02 17:00:00', '2025-04-01 12:00:00', NULL, 'REACTIVACION DE NODOS DE RED PARA RECIBO DE MATERIALES', 'RECIBO DE MATERIALES', 'Pachuca', '1', 'Recibido', NULL, NULL),
(19, 8, '47341', 'CONECTOR RECTO 3/4 PARA TUBO FLEXIBLE METALICO VOLTECK', '10Pieza(s)', 'Adquirido', '2025-04-02 17:00:00', '2025-04-01 12:00:00', NULL, 'REACTIVACION DE NODOS DE RED PARA RECIBO DE MATERIALES', 'RECIBO DE MATERIALES', 'Pachuca', '1', 'Recibido', NULL, NULL),
(20, 8, '46901', 'ROLLO DE 50 METROS DE TUBO FLEXIBLE METALICO 3/4 VOLTECK', '1Rollo(s)', 'Adquirido', '2025-04-02 17:00:00', '2025-04-01 12:00:00', NULL, 'REACTIVACION DE NODOS DE RED PARA RECIBO DE MATERIALES', 'RECIBO DE MATERIALES', 'Pachuca', '1', 'Recibido', NULL, NULL),
(21, 8, '47341', 'CONECTOR RECTO 3/4 PARA TUBO FLEXIBLE METALICO VOLTECK', '10Pieza(s)', 'Adquirido', '2025-04-02 17:00:00', '2025-04-01 12:00:00', NULL, 'REACTIVACION DE NODOS DE RED PARA RECIBO DE MATERIALES', 'RECIBO DE MATERIALES', 'Pachuca', '1', 'Recibido', NULL, NULL),
(22, 8, 'TRIOLINA', 'TRIOLINA DE 3/4 METALICA', '10Pieza(s)', 'Adquirido', '2025-04-02 17:00:00', '2025-04-01 12:00:00', NULL, 'REACONDICIONAMIENTO DE NODOS DE RED RECIBO DE MATERIALES', 'RECIBO DE MATERIALES', 'Pachuca', '1', 'Recibido', NULL, NULL),
(23, 8, 'Kit de mantto Canon', 'Genuine Canon imageCLASS D1620 Fuser Maintenance Kit - 120V', '1Pieza(s)', 'Adquirido', '2025-04-02 17:00:00', '2025-04-01 12:00:00', NULL, 'Falla por desgaste de gomas y fusor por vida util en Impresora Canon.', 'FACTURACION CUERNAVACA', 'Cuernavaca', 'CVIMCD01', 'Recibido', NULL, NULL),
(24, 8, 'Especs: Kit de mantenimiento para Impresora Canon D1620', 'Genuine Canon imageCLASS D1620 Fuser Maintenance Kit - 120V Referencia de piezas: https://www.precisionroller.com/maintenance-kits-for-canon-imageclass-d1620/details_96853.html?srsltid=AfmBOooWJ5wp_mMX2UWs2fVD1ZM-eJ35lu1JJORDFFvGxVlC7Ah83hwh', '1Pieza(s)', 'Adquirido', '2025-04-02 17:00:00', '2025-04-01 12:00:00', NULL, 'Falla por desgaste de gomas y fusor por vida util en Impresora Canon', 'Facturación Cuernavaca', 'Cuernavaca', 'CVIMCD01', 'Recibido', NULL, NULL),
(25, 9, 'ZD22042-T01G00EZ', 'Zebra ZD220 Impresora de Etiquetas Transferencia Térmica 203DPI USB Negro Requiere Cinta de Impresión', '1Pieza(s)', 'Adquirido', '2025-04-12 17:00:00', '2025-04-11 12:00:00', NULL, 'IMPRESORA PARA MESA DE SURTIDO NUMERO 8', 'ALMACEN MESAS', 'Pachuca', '1', 'Recibido', NULL, NULL),
(26, 10, 'Mouse', 'Logitech M90 Mouse con Cable USB 3 Botones Seguimiento Óptico 1000 DPI Ambidiestro Compatible con PC Mac Laptop - Negro Title Generator', '1Pieza(s)', 'Adquirido', '2025-04-26 17:00:00', '2025-04-25 12:00:00', NULL, 'MOUSE NO RESPONDE  FALLA EN LECTOR OPTICO', 'DEVOLUCIONES', 'Pachuca', 'PAPCDE01', 'Recibido', NULL, NULL),
(27, 11, '210591', 'ntellinet Jack Categoría 6 RJ-45 Blanco', '6Pieza(s)', 'Adquirido', '2025-05-10 17:00:00', '2025-05-09 12:00:00', NULL, 'MANTENIMIENTO A LA SUCURSAL', 'OAXACA', 'Oaxaca', 'CLV-20250509110049-87', 'Recibido', NULL, NULL),
(28, 11, 'Plugs', 'Intellinet Plugs Modulares RJ-45 Cat6 Bote con 100 Pieza', '1Bote(s)', 'Adquirido', '2025-05-10 17:00:00', '2025-05-09 12:00:00', NULL, 'MANTENIMIENTO A LA SUCURSAL', 'OAXACA', 'Oaxaca', 'CLV-20250509110049-87', 'Recibido', NULL, NULL),
(29, 11, 'Cincho', 'Bolsa Cincho 18 cm', '2Bolsa(s)', 'Adquirido', '2025-05-10 17:00:00', '2025-05-09 12:00:00', NULL, 'MANTENIMIENTO A LA SUCURSAL', 'OAXACA', 'Oaxaca', 'CLV-20250509110049-87', 'Recibido', NULL, NULL),
(30, 11, 'SSD HP S650', 'SSD HP S650 240GB SATA III 2.5\" 7mm', '7Pieza(s)', 'Adquirido', '2025-05-10 17:00:00', '2025-05-09 12:00:00', NULL, 'MANTENIMIENTO A LA SUCURSAL', 'OAXACA', 'Oaxaca', 'CLV-20250509110049-87', 'Recibido', NULL, NULL),
(31, 11, 'Memoria RAM', 'Kingston KCP426NS6/8 DDR4 2666MHz 8GB CL19 PARA PC', '7Pieza(s)', 'Adquirido', '2025-05-10 17:00:00', '2025-05-09 12:00:00', NULL, 'MANTENIMIENTO A LA SUCURSAL', 'OAXACA', 'Oaxaca', 'CLV-20250509110049-87', 'Recibido', NULL, NULL),
(32, 11, 'UT750GU', 'No Break UT750GU PIEZAS', '6Pieza(s)', 'Adquirido', '2025-05-10 17:00:00', '2025-05-09 12:00:00', NULL, 'MANTENIMIENTO A LA SUCURSAL', 'OAXACA', 'Oaxaca', 'CLV-20250509110049-87', 'Recibido', NULL, NULL),
(33, 12, 'SKU: 15-FD0005DX', 'Laptop HP 15-FD0005DX 15.6\" 1366x768 HD Táctil Intel Core i5-1235U 8GB 512GB SSD Windows 11 S Inglés', '1Pieza(s)', 'Adquirido', '2025-05-14 17:00:00', '2025-05-13 12:00:00', NULL, 'Solicitud de equipo para gerente de cordoba', 'CORDOBA', 'Cordoba', 'CLV-20250513155654-93', 'Recibido', NULL, NULL),
(34, 12, 'LIC-OFFICE', 'Microsoft 365 Personal 1 Usuario', '1Pieza(s)', 'Adquirido', '2025-05-14 17:00:00', '2025-05-13 12:00:00', NULL, 'Renovacion de Licenia', 'JURIDICO', 'Pachuca', 'MP1P3ZTK', 'Recibido', NULL, NULL),
(35, 13, '1145747470', 'MacBook Pro 14 pulgadas M3 18 GB RAM 1 TB SSD negro espcial LIGA DE REFEFENCIA https://www.liverpool.com.mx/tienda/pdp/apple-macbook-pro-14-pulgadas-m3-18-gb-ram-1-tb-ssd-negro-espcial/1145747470?gfeed=true&gclsrc=aw.ds&gad_source=1&gad_campaignid=20046765049&gclid=Cj0KCQjwoZbBBhDCARIsAOqMEZWueyqG4', '1Pieza(s)', 'Adquirido', '2025-05-16 17:00:00', '2025-05-15 12:00:00', NULL, 'RENOVACION DE EQUIPO DE COMPUTO POR ESTADO DEL DISPOSITIVO YA SE PRESENTAN FALLAS TANTA DE ESTRUCTURA DE VISAGRAS COMO EN CALENTAMIENTO DE TARJETA PRICIPAL', 'JURIDICO', 'Pachuca', 'CLV-20250515132914-31', 'Recibido', NULL, NULL),
(36, 14, 'SKU 235908', 'TUBO CONDUIT LIGERO RO 13 MM (1/2\") ABOC Modelo 12454', '15Pieza(s)', 'Adquirido', '2025-05-17 17:00:00', '2025-05-16 12:00:00', NULL, 'esta tuberia es paar las camras que se encuantran instaladas en la periferia incluyando ptz. para proteccion del cableado y extender la veda del cable', 'VIGILANCIA', 'Pachuca', 'CLV-20250516171444-42', 'Recibido', NULL, NULL),
(37, 14, 'SKU 861999', 'UÑA PARED DELGADA DE 1/2 PULGADA PLATA Modelo 20089', '50Pieza(s)', 'Adquirido', '2025-05-17 17:00:00', '2025-05-16 12:00:00', NULL, 'esta tuberia es paar las camras que se encuantran instaladas en la periferia incluyando ptz. para proteccion del cableado y extender la veda del cable', 'VIGILANCIA', 'Pachuca', 'CLV-20250516171444-42', 'Recibido', NULL, NULL),
(38, 14, 'SKU 311767', 'TAPA P/CAJA CUADRADA 13MM (1/2\") Modelo 311767', '15Pieza(s)', 'Adquirido', '2025-05-17 17:00:00', '2025-05-16 12:00:00', NULL, 'esta tuberia es paar las camras que se encuantran instaladas en la periferia incluyando ptz. para proteccion del cableado y extender la veda del cable', 'VIGILANCIA', 'Pachuca', 'CLV-20250516171444-42', 'Recibido', NULL, NULL),
(39, 14, 'SKU 273526', 'CURVA CONDUIT 90 R-1 (PESADA) 13 MM (1/2\") Modelo 90 R1', '10Pieza(s)', 'Adquirido', '2025-05-17 17:00:00', '2025-05-16 12:00:00', NULL, 'esta tuberia es paar las camras que se encuantran instaladas en la periferia incluyando ptz. para proteccion del cableado y extender la veda del cable', 'VIGILANCIA', 'Pachuca', 'CLV-20250516171444-42', 'Recibido', NULL, NULL),
(40, 14, 'SKU 3103', 'CAJA CONDUIT CUADRADA 1/2 PULGADA VERDE Modelo 942534', '15Pieza(s)', 'Adquirido', '2025-05-17 17:00:00', '2025-05-16 12:00:00', NULL, 'esta tuberia es paar las camras que se encuantran instaladas en la periferia incluyando ptz. para proteccion del cableado y extender la veda del cable', 'VIGILANCIA', 'Pachuca', 'CLV-20250516171444-42', 'Recibido', NULL, NULL),
(41, 14, 'SKU 252766', 'CONECTOR CONDUIT R-1 (PESADO) 13 MM (1/2\") Modelo R-1', '40Pieza(s)', 'Adquirido', '2025-05-17 17:00:00', '2025-05-16 12:00:00', NULL, 'esta tuberia es paar las camras que se encuantran instaladas en la periferia incluyando ptz. para proteccion del cableado y extender la veda del cable', 'VIGILANCIA', 'Pachuca', 'CLV-20250516171444-42', 'Recibido', NULL, NULL),
(42, 14, 'CAMARA', 'CAMARAS TIPO BALA DH-HAC-HFW1500TLN-0280B-S2', '2Pieza(s)', 'Adquirido', '2025-05-17 17:00:00', '2025-05-16 12:00:00', NULL, 'esta tuberia es paar las camras que se encuantran instaladas en la periferia incluyando ptz. para proteccion del cableado y extender la veda del cable', 'VIGILANCIA', 'Pachuca', 'CLV-20250516171444-42', 'Recibido', NULL, NULL),
(43, 14, 'CAJA', 'CAJAS NEMA 8x8x4.5CM 7 ENTRADAS IP55 CONTRA AGUA', '4Pieza(s)', 'Adquirido', '2025-05-17 17:00:00', '2025-05-16 12:00:00', NULL, 'esta tuberia es paar las camras que se encuantran instaladas en la periferia incluyando ptz. para proteccion del cableado y extender la veda del cable', 'VIGILANCIA', 'Pachuca', 'CLV-20250516171444-42', 'Recibido', NULL, NULL),
(44, 14, 'BALUM', '(BALUM) Dahua Transceptor Pasivo de Video PFM800-E BNC', '4Pieza(s)', 'Adquirido', '2025-05-17 17:00:00', '2025-05-16 12:00:00', NULL, 'esta tuberia es paar las camras que se encuantran instaladas en la periferia incluyando ptz. para proteccion del cableado y extender la veda del cable', 'VIGILANCIA', 'Pachuca', 'CLV-20250516171444-42', 'Recibido', NULL, NULL),
(45, 15, 'CAMARA', 'CAMARA VARIFOCAL (DOMO) DH-HAC-D3A21N-VF-2712', '1Pieza(s)', 'Adquirido', '2025-05-24 17:00:00', '2025-05-23 12:00:00', NULL, 'Camara para aAlacena Direccion', 'DIRECCION', 'Pachuca', 'CLV-20250523114315-63', 'Recibido', NULL, NULL),
(46, 15, 'CAMARA', 'CAMARA VARIFOCAL (DOMO) DH-HAC-D3A21N-VF-2712', '1Pieza(s)', 'Adquirido', '2025-05-24 17:00:00', '2025-05-23 12:00:00', NULL, 'CAMARA PARA ALACENA DE DIRECCION', 'DIRECCION', 'Pachuca', 'CLV-20250523114500-46', 'Recibido', NULL, NULL),
(47, 16, 'Zebra DS2208', 'Lector de Código de Barras LED 1D/2D - incluye Cable USB y Base. https://www.cyberpuerta.mx/Punto-de-Venta-POS/Lectores-y-Terminales/Lectores-de-Codigo-de-Barras/Zebra-DS2208-Lector-de-Codigo-de-Barras-LED-1D-2D-incluye-Cable-USB-y-Base-1.html', '2Pieza(s)', 'Adquirido', '2025-05-31 17:00:00', '2025-05-30 12:00:00', NULL, 'HABILITAR MESAS PARA SURTIDO', 'CEDIS GUADALAJARA', 'Guadalajara', 'CLV-20250530104430-78', 'Recibido', NULL, NULL),
(48, 16, 'KM300C', 'Kit de Teclado y Mouse Dell KM300C Alámbrico USB Negro (Español).https://www.cyberpuerta.mx/Computo-Hardware/Dispositivos-de-Entrada/Kits-de-Teclado-y-Mouse/Kit-de-Teclado-y-Mouse-Dell-KM300C-Alambrico-USB-Negro-Espanol.html', '2Kit(s)', 'Adquirido', '2025-05-31 17:00:00', '2025-05-30 12:00:00', NULL, 'HABILITAR MESAS PARA SURTIDO', 'CEDIS GUADALAJARA', 'Guadalajara', 'CLV-20250530104430-78', 'Recibido', NULL, NULL),
(49, 16, 'ARCHER T2U NANO', 'TP-Link Adaptador de Red USB ARCHER T2U NANO Inalámbrico WLAN 633 Mbit/s Doble Banda 2.4/5 GHz', '2Pieza(s)', 'Adquirido', '2025-05-31 17:00:00', '2025-05-30 12:00:00', NULL, 'HABILITAR MESAS DE TRABAJO', 'CHIHUAHUA', 'Chihuahua', 'CLV-20250530104943-45', 'Recibido', NULL, NULL),
(50, 16, 'UT550GU', 'No Break CyberPower UT550GU Línea Interactiva 275W 550VA Entrada 86V - 148V Salida 110V - 130V 8 Salidas.https://www.abasteo.mx/Energia/Proteccion-Contra-Descargas/No-Break-UPS/No-Break/No-Break-CyberPower-UT550GU-Linea-Interactiva-275W-550VA-Entrada-86V-148V-Salida-110V-130V-8-Salidas.html', '2Pieza(s)', 'Adquirido', '2025-05-31 17:00:00', '2025-05-30 12:00:00', NULL, 'REMPLAZO DE NO-BREAK', 'RECIBO DE MATERIALES', 'Pachuca', 'CLV-20250530105305-61', 'Recibido', NULL, NULL),
(51, 17, 'SKU: PFM800-E', 'Dahua Transceptor Pasivo de Video PFM800-E BNC Macho hasta 400 Metros', '1Pieza(s)', 'Adquirido', '2025-06-06 17:00:00', '2025-06-05 12:00:00', NULL, 'FALLA EN BALUM DE CAMARA', 'VIGILANCIA', 'Pachuca', 'CLV-20250605140451-61', 'Recibido', NULL, NULL),
(52, 18, 'DVR', 'Dahua Xvr5232an-4kl-i3 Dvr 32 Canales 4k 8mp Wizsense Con Ia DH-XVR5232AN-4KL-I3', '1Pieza(s)', 'Adquirido', '2025-06-07 17:00:00', '2025-06-06 12:00:00', NULL, 'MANTENIMIENTO CORRECTIVO A CCTV', 'CEDIS CHIHUAHUA', 'Chihuahua', 'CLV-20250606170224-12', 'Recibido', NULL, NULL),
(53, 18, 'NOBREAK', 'NO BREAK CYBERPOWER OR500LCDRM1U 500VA / 1000W INTERACTIVO SOLO RACK 1U 6 × NEMA 5-15R 4 RESPALDO Y 2 SUPRESIÓN', '3Pieza(s)', 'Adquirido', '2025-06-07 17:00:00', '2025-06-06 12:00:00', NULL, 'MANTENIMIENTO CORRECTIVO A CCTV', 'CEDIS CHIHUAHUA', 'Chihuahua', 'CLV-20250606170224-12', 'Recibido', NULL, NULL),
(54, 18, 'DISCO', 'HDD INTERNO WD PURPLE WD63PURZ 8TB SATA3 3.5PLG', '2Pieza(s)', 'Adquirido', '2025-06-07 17:00:00', '2025-06-06 12:00:00', NULL, 'MANTENIMIENTO CORRECTIVO A CCTV', 'CEDIS CHIHUAHUA', 'Chihuahua', 'CLV-20250606170224-12', 'Recibido', NULL, NULL),
(55, 18, 'FUENTE', 'Epcom Fuente de Poder Profesional Heavy Duty de 11-15 Vcd @ 30 Amper para 16 cámaras con Voltaje de Entrada de: 110-220 Vca', '3Pieza(s)', 'Adquirido', '2025-06-07 17:00:00', '2025-06-06 12:00:00', NULL, 'MANTENIMIENTO CORRECTIVO A CCTV', 'CEDIS CHIHUAHUA', 'Chihuahua', 'CLV-20250606170224-12', 'Recibido', NULL, NULL),
(56, 18, 'CAMARA', 'CAMARA DAHUA DH-HAC-HDW1500TLQN-A DOMO 5MP MICROFONO INTEGRADO LENTE  2.8MM IR 30MTS IP67 CVI/CVBS/AHD/TVI/ METALICA', '16Pieza(s)', 'Adquirido', '2025-06-07 17:00:00', '2025-06-06 12:00:00', NULL, 'MANTENIMIENTO CORRECTIVO A CCTV', 'CEDIS CHIHUAHUA', 'Chihuahua', 'CLV-20250606170224-12', 'Recibido', NULL, NULL),
(57, 18, 'CONECTORES', 'conectores de corriente AC hembra y macho', '16Pieza(s)', 'Adquirido', '2025-06-07 17:00:00', '2025-06-06 12:00:00', NULL, 'MANTENIMIENTO CORRECTIVO A CCTV', 'CEDIS CHIHUAHUA', 'Chihuahua', 'CLV-20250606170224-12', 'Recibido', NULL, NULL),
(58, 18, 'JACK', 'Jack Categoría 6 RJ-45 Blanco para pared', '10Pieza(s)', 'Adquirido', '2025-06-07 17:00:00', '2025-06-06 12:00:00', NULL, 'MANTENIMIENTO CORRECTIVO A CCTV', 'CEDIS CHIHUAHUA', 'Chihuahua', 'CLV-20250606170224-12', 'Recibido', NULL, NULL),
(59, 18, 'TRANSEPTORES', 'VIDEO BALUMS MARCA DAHUA', '20Juego(s)', 'Adquirido', '2025-06-07 17:00:00', '2025-06-06 12:00:00', NULL, 'MANTENIMIENTO CORRECTIVO A CCTV', 'CEDIS CHIHUAHUA', 'Chihuahua', 'CLV-20250606170224-12', 'Recibido', NULL, NULL),
(60, 18, 'BOBINA', 'BOBINA DE CABLE UTP 305 MTS MARCA DAHUA', '3Pieza(s)', 'Adquirido', '2025-06-07 17:00:00', '2025-06-06 12:00:00', NULL, 'MANTENIMIENTO CORRECTIVO A CCTV', 'CEDIS CHIHUAHUA', 'Chihuahua', 'CLV-20250606170224-12', 'Recibido', NULL, NULL),
(61, 18, 'SAPPA', 'ROLLOS DE SAPPA 3/4', '3Rollo(s)', 'Adquirido', '2025-06-07 17:00:00', '2025-06-06 12:00:00', NULL, 'MANTENIMIENTO CORRECTIVO A CCTV', 'CEDIS CHIHUAHUA', 'Chihuahua', 'CLV-20250606170224-12', 'Recibido', NULL, NULL),
(62, 18, 'C11CJ68301', 'Multifuncional Epson EcoTank L3210 Color Inyección de Tinta Tanque de Tinta Print/Copy/Scan', '1Pieza(s)', 'Adquirido', '2025-06-12 17:00:00', '2025-06-11 12:00:00', NULL, 'FALLA EL SENSOR DE PAPEL', 'JURIDICO', 'Pachuca', 'CLV-20250611170526-72', 'Recibido', NULL, NULL),
(63, 19, 'SKU: R7XKG', 'Laptop Dell Inspiron 3520 15.6\" 1920x1080 Full HD Intel Core i5-1235U 16GB 512GB SSD Windows 11 Pro Español', '1Pieza(s)', 'Adquirido', '2025-06-20 17:00:00', '2025-06-19 12:00:00', NULL, 'CAMBIO DE EQUIPO DE COMPUTO POR FALLA CONSTANTE EN TARJETA PRINCIPAL', 'ADMIN REFAS', 'Pachuca', 'CLV-20250619183352-82', 'Recibido', NULL, NULL),
(64, 20, 'SKU: 580-AKKW', 'Kit de Teclado y Mouse Dell KM300C Alámbrico USB Negro (Español)', '12Pieza(s)', 'Adquirido', '2025-06-20 17:00:00', '2025-06-19 12:00:00', NULL, 'MEMBRANAS DAÑADAS POR POLVO Y TECLAS SIN YA SIN LETRAS', 'REFACCIONARIAS', 'Pachuca', 'CLV-20250619183658-55', 'Recibido', NULL, NULL),
(65, 20, 'SKU: WDS240G3G0A', 'SSD WD Green 240GB 2.5\" 545 MB/s Lectura SATA III', '1Pieza(s)', 'Adquirido', '2025-06-24 17:00:00', '2025-06-23 12:00:00', NULL, 'ACTUALIZACION DE SISTEMA OPERATIVO', 'EMBARQUES', 'Pachuca', 'PAPCEM07', 'Recibido', NULL, NULL),
(66, 21, 'SKU: WDS240G3G0A', 'SSD WD Green 240GB 2.5\" 545 MB/s Lectura SATA III', '1Pieza(s)', 'Adquirido', '2025-06-24 17:00:00', '2025-06-23 12:00:00', NULL, 'ACTUALIZACION DE SISTEMA OPERATIVO', 'EMBARQUES', 'Pachuca', 'PAPCEM07', 'Recibido', NULL, NULL),
(67, 21, 'SKU: 467S0LS', 'Computadora HP ProDesk 400 G7 SFF Intel Core i3-10100 8GB 512GB SSD Windows 11 Pro + Teclado/Mouse ― ¡Envío gratis limitado a 5 productos por cliente!', '1Pieza(s)', 'Adquirido', '2025-06-24 17:00:00', '2025-06-23 12:00:00', NULL, 'RENOVACION DE EQUIPO OBSOLETO', 'EMBARQUES', 'Pachuca', 'CLV-20250623140129-17', 'Recibido', NULL, NULL),
(68, 21, 'SKU: AK2F1UT', 'Monitor HP Series 3 Pro LCD 21.5\" 1920x1080 Full HD 100Hz HDMI Negro', '1Pieza(s)', 'Adquirido', '2025-06-24 17:00:00', '2025-06-23 12:00:00', NULL, 'RENOVACION DE EQUIPO OBSOLETO', 'EMBARQUES', 'Pachuca', 'CLV-20250623140129-17', 'Recibido', NULL, NULL),
(69, 21, 'Filimina', 'Filmina Fusor Compatible Con Hp Laserjet Pro Mfp M521dn', '1Pieza(s)', 'Adquirido', '2025-07-10 17:00:00', '2025-07-09 12:00:00', NULL, 'DAÑO EN FILIMINAPOR USO', 'OAXACA', 'Oaxaca', 'CLV-20250709135906-35', 'Recibido', NULL, NULL),
(70, 22, 'SKU: 12LM000GLS', 'Computadora Lenovo ThinkCentre neo 50q Gen 4 Intel Core i3-1215U 8GB 512GB SSD Wi-Fi Windows 11 Pro + Teclado/Mouse', '1Pieza(s)', 'Adquirido', '2025-07-10 17:00:00', '2025-07-09 12:00:00', NULL, 'EQUIPO DAÑADO YA CON MAS DE 6 AÑOS DE ANTIGUEDAD  EQUIPO ENSAMBLADO', 'FACTURACION HERMOSILLO', 'Hermosillo', 'CLV-20250709142205-51', 'Recibido', NULL, NULL),
(71, 22, 'SKU: 12LM000GLS', 'Computadora Lenovo ThinkCentre neo 50q Gen 4 Intel Core i3-1215U 8GB 512GB SSD Wi-Fi Windows 11 Pro', '1Pieza(s)', 'Adquirido', '2025-07-10 17:00:00', '2025-07-09 12:00:00', NULL, 'EQUIPO DAÑADO YA CON MAS DE 6 AÑOS DE ANTIGUEDAD EQUIPO ENSAMBLADO', 'FACTURACION HERMOSILLO', 'Hermosillo', 'CLV-20250709142314-59', 'Recibido', NULL, NULL),
(72, 22, '12LM000GLS', 'Computadora Lenovo ThinkCentre neo 50q Gen 4 Intel Core i3-1215U 8GB 512GB SSD Wi-Fi Windows 11 Pro', '1Pieza(s)', 'Adquirido', '2025-07-10 17:00:00', '2025-07-09 12:00:00', NULL, 'EQUIPO DAÑADO YA CON MAS DE 6 AÑOS DE ANTIGUEDAD EQUIPO ENSAMBLADO', 'FACTURACION HERMOSILLO', 'Hermosillo', 'CLV-20250709142434-40', 'Recibido', NULL, NULL),
(73, 22, '12LM000GLS', 'Computadora Lenovo ThinkCentre neo 50q Gen 4 Intel Core i3-1215U 8GB 512GB SSD Wi-Fi', '1Pieza(s)', 'Adquirido', '2025-07-10 17:00:00', '2025-07-09 12:00:00', NULL, 'EQUIPO DAÑADO YA CON MAS DE 6 AÑOS DE ANTIGUEDAD EQUIPO ENSAMBLADO', 'FACTURACION HERMOSILLO', 'Hermosillo', 'CLV-20250709142518-85', 'Recibido', NULL, NULL),
(74, 22, 'SKU: 5161C005', 'Multifuncional Canon imageClass MF455dw Blanco y Negro Láser Inalámbrico Print/Scan/Copy/Fax', '1Pieza(s)', 'Adquirido', '2025-07-19 17:00:00', '2025-07-18 12:00:00', NULL, 'FALLA FUSOR DE IMPRESORA MODELO M521DN YA OBSOLETO ESTE MODELO', 'FACTURACION GUADALAJARA', 'Guadalajara', 'CLV-20250718164922-94', 'Recibido', NULL, NULL),
(75, 23, 'FILIMINA', 'Filmina Hp Para P3015 / M521 Calidad Oem - Fijación', '2Pieza(s)', 'Adquirido', '2025-07-19 17:00:00', '2025-07-18 12:00:00', NULL, 'LA FILIMINA SE ROMPIO POR USO   IMPRESORA UN FUNCIONAL', 'FACTURACION QUERETARO', 'Queretaro', 'CLV-20250718171225-65', 'Recibido', NULL, NULL),
(76, 23, 'SKU: A8KC0LT', 'Laptop HP ZBook Firefly 16 G11 16\" 1920x1200 WUXGA Intel Core Ultra 155H NVIDIA RTX A500 16GB 512GB SSD Windows 11 Pro Español', '1Pieza(s)', 'Adquirido', '2025-07-22 17:00:00', '2025-07-21 12:00:00', NULL, 'NUEVO PUESTO', 'MERCADOTECNIA', 'Pachuca', 'CLV-20250721175000-61', 'Recibido', NULL, NULL),
(77, 24, 'SKU: SLB 12-4.5', 'CDP Batería de Reemplazo para No Break SLB 12-4.5 12V 4.5Ah', '2Pieza(s)', 'Adquirido', '2025-07-22 17:00:00', '2025-07-21 12:00:00', NULL, 'No-Break Funcional solo remplazar bateria', 'MERCADOTECNIA', 'Pachuca', 'CLV-20250721175349-86', 'Recibido', NULL, NULL),
(78, 24, 'SKU: 5161C005', 'Multifuncional Canon imageClass MF455dw Blanco y Negro Láser Inalámbrico Print/Scan/Copy/Fax', '1Pieza(s)', 'Adquirido', '2025-07-24 17:00:00', '2025-07-23 12:00:00', NULL, 'FALLA DE MOTOR DE ROTACION  Y  FUSOR  YA NO APLICA PARA REPARACION', 'RECIBO', 'Pachuca', 'CLV-20250723112157-21', 'Recibido', NULL, NULL),
(79, 25, 'SKU: 24504', 'Silla Ejecutiva 4Tune Poliuretano Negro OFFICE DEPOT', '2Pieza(s)', 'Adquirido', '2025-07-26 17:00:00', '2025-07-25 12:00:00', NULL, 'LAS SILLAS YA ESTAN MUY DAÑADAS ( JONATHAN Y GUILLERMINA TORRES )', 'SISTEMAS', 'Pachuca', 'CLV-20250725165159-77', 'Recibido', NULL, NULL),
(80, 26, 'SKU: OC302', 'Modamob Silla OC302 Respaldo acolchado Gris', '2Pieza(s)', 'Adquirido', '2025-07-26 17:00:00', '2025-07-25 12:00:00', NULL, 'CAMBIO DE SILLA YA EN MAL ESTADO  ( GUILLERMINA TORRES Y JONATHAN )', 'SISTEMAS', 'Pachuca', '2', 'Recibido', NULL, NULL),
(81, 26, 'SKU: DECO XE75 PRO(3-PACK)', 'Router TP-Link con Sistema de Red Wi-Fi en Malla Deco XE75 Pro con WiFi Mesh Inalámbrico 2402Mbit/s 3x RJ-45 2.4/5/6GHz Antenas Internas Blanco - 3 Piezas', '3Kit(s)', 'Adquirido', '2025-07-31 17:00:00', '2025-07-30 12:00:00', NULL, 'RENOVACION DE ANTENAS WIFI CASA DEL SR JAIME', 'CASA', 'Pachuca', 'CLV-20250730170949-87', 'Recibido', NULL, NULL),
(82, 27, 'SKU:    SNV3S/1000G', 'SSD Kingston SNV3S NVMe 1TB M.2 4000 MB/s Escritura 6000 MB/s Lectura PCI Express 4.0', '1Pieza(s)', 'Adquirido', '2025-08-01 17:00:00', '2025-07-31 12:00:00', NULL, 'DISCO PARA LAPTOP DE MERCADOTECNIA', 'MERCADOTECNIA', 'Pachuca', 'CLV-20250731131807-40', 'Recibido', NULL, NULL),
(83, 28, 'SKU: 130639', 'Manhattan Gabinete de SSD M.2 USB-A/USB-C Negro', '1Pieza(s)', 'Adquirido', '2025-08-02 17:00:00', '2025-08-01 12:00:00', NULL, 'PARA EL CONTADOR ROBERTO GONZALEZ', 'DIRECCION', 'Pachuca', 'CLV-20250801164959-88', 'Recibido', NULL, NULL),
(84, 29, 'SKU: 580-AKKW', 'Kit de Teclado y Mouse Dell KM300C Alámbrico USB Negro (Español)', '6Pieza(s)', 'Adquirido', '2025-08-02 17:00:00', '2025-08-01 12:00:00', NULL, 'CAMBIO POR EQUIPO YA EN MALAS CONDICIONES', 'CREDITO Y COBRANZA', 'Pachuca', 'CLV-20250801180216-31', 'Recibido', NULL, NULL),
(85, 29, 'SKU: UT750GU', 'No Break CyberPower UT750GU 375W 750VA Entrada 86 - 148V Salida 120V 8 Contactos', '4Pieza(s)', 'Adquirido', '2025-08-02 17:00:00', '2025-08-01 12:00:00', NULL, 'CAMBIO POR EQUIPO YA EN MALAS CONDICIONES', 'CREDITO Y COBRANZA', 'Pachuca', 'CLV-20250801180216-31', 'Recibido', NULL, NULL),
(86, 29, 'SKU: KB216-BK-LTN', 'Teclado Dell KB216 Alámbrico USB Negro (Español)', '12Pieza(s)', 'Adquirido', '2025-08-09 17:00:00', '2025-08-08 12:00:00', NULL, 'RENOVACION DE TECLADOS YA SE ENCUENTRAN DESGASTADOS Y ALGUNOS CON FALLA', 'CREDITO Y COBRANZA', 'Pachuca', 'CLV-20250808132542-22', 'Recibido', NULL, NULL),
(87, 30, 'SKU: 5161C005', 'Multifuncional Canon imageClass MF455dw Blanco y Negro LÃ¡ser InalÃ¡mbrico Print/Scan/Copy/Fax', '1Pieza(s)', 'Adquirido', '2025-08-08 17:00:00', '2025-08-07 12:00:00', NULL, 'REPOSICION DE EQUIPO CON FALLA', 'QUERETARO', 'Pachuca', 'CLV-20250808140044-67', 'Recibido', NULL, NULL),
(88, 31, 'SKU: 314956', 'Bobina de cable Cat6 (UTP) Belden 2412 de 305m Gris 23AWG.', '1Pieza(s)', 'Adquirido', '2025-08-09 17:00:00', '2025-08-08 12:00:00', NULL, 'RECABLEAR CAMATRAS DE EMBARQUE CEDIS', 'EMBARQUES CEDIS', 'Pachuca', 'CLV-20250808141704-85', 'Recibido', NULL, NULL),
(89, 30, 'SKU: PFM800-E', 'Dahua Transceptor Pasivo de Video PFM800-E BNC Macho hasta 400 Metros', '3Pieza(s)', 'Adquirido', '2025-08-09 17:00:00', '2025-08-08 12:00:00', NULL, 'RECABLEAR CAMATRAS DE EMBARQUE CEDIS', 'EMBARQUES CEDIS', 'Pachuca', 'CLV-20250808141704-85', 'Recibido', NULL, NULL),
(90, 30, 'SKU: VVCONEC5210P', 'X-Case Adaptador de Corriente Macho para Cámaras - Paquete con 10 Piezas', '3Pieza(s)', 'Adquirido', '2025-08-09 17:00:00', '2025-08-08 12:00:00', NULL, 'RECABLEAR CAMATRAS DE EMBARQUE CEDIS', 'EMBARQUES CEDIS', 'Pachuca', 'CLV-20250808141704-85', 'Recibido', NULL, NULL),
(91, 30, 'SKU: 314956', 'Bobina de cable Cat6 (UTP) Belden 2412 de 305m', '1Pieza(s)', 'Adquirido', '2025-08-09 17:00:00', '2025-08-08 12:00:00', NULL, 'RECABLEAR CAMATRAS DE EMBARQUE CEDIS', 'EMBARQUES CEDIS', 'Pachuca', 'CLV-20250808142252-92', 'Recibido', NULL, NULL),
(92, 30, 'SKU: PFM800-E', 'Dahua Transceptor Pasivo de Video PFM800-E BNC Macho hasta 400 Metros', '3Pieza(s)', 'Adquirido', '2025-08-09 17:00:00', '2025-08-08 12:00:00', NULL, 'RECABLEAR CAMATRAS DE EMBARQUE CEDIS', 'EMBARQUES CEDIS', 'Pachuca', 'CLV-20250808142252-92', 'Recibido', NULL, NULL),
(93, 30, 'SKU: VVCONEC5210P', 'X-Case Adaptador de Corriente Macho para Cámaras - Paquete con 10 Piezas', '3Pieza(s)', 'Adquirido', '2025-08-09 17:00:00', '2025-08-08 12:00:00', NULL, 'RECABLEAR CAMATRAS DE EMBARQUE CEDIS', 'EMBARQUES CEDIS', 'Pachuca', 'CLV-20250808142252-92', 'Recibido', NULL, NULL),
(94, 30, 'SKU: 314956', 'Bobina de cable Cat6 (UTP) Belden 2412 de 305m', '1Pieza(s)', 'Adquirido', '2025-08-09 17:00:00', '2025-08-08 12:00:00', NULL, 'CABLEAR CAMARAS DE EMBARQUE CEDIS CABLE DAÑADO', 'EMBARQUES CEDIS', 'Pachuca', 'CLV-20250808142830-68', 'Recibido', NULL, NULL),
(95, 30, 'SKU: PFM800-E', 'Dahua Transceptor Pasivo de Video PFM800-E BNC Macho hasta 400 Metros', '3Pieza(s)', 'Adquirido', '2025-08-09 17:00:00', '2025-08-08 12:00:00', NULL, 'CABLEAR CAMARAS DE EMBARQUE CEDIS CABLE DAÑADO', 'EMBARQUES CEDIS', 'Pachuca', 'CLV-20250808142830-68', 'Recibido', NULL, NULL),
(96, 30, 'SKU: VVCONEC5210P', 'X-Case Adaptador de Corriente Macho para Cámaras - Paquete con 10 Piezas', '3Pieza(s)', 'Adquirido', '2025-08-09 17:00:00', '2025-08-08 12:00:00', NULL, 'CABLEAR CAMARAS DE EMBARQUE CEDIS CABLE DAÑADO', 'EMBARQUES CEDIS', 'Pachuca', 'CLV-20250808142830-68', 'Recibido', NULL, NULL),
(97, 30, 'Fuente', 'Fuente De Poder Profesional Heavy Duty 16 Amp Para 16 Cámaras', '2Pieza(s)', 'Adquirido', '2025-08-09 17:00:00', '2025-08-08 12:00:00', NULL, 'CABLEAR CAMARAS DE EMBARQUE CEDIS CABLE DAÑADO', 'EMBARQUES CEDIS', 'Pachuca', 'CLV-20250808142830-68', 'Recibido', NULL, NULL),
(98, 30, 'SKU: 82TT00SJLM', 'Laptop Lenovo V15 G3 IAP 15.6\" 1920x1080 Full HD Intel Core i5-1235U 16GB 512GB SSD Windows 11 Pro Español', '1Pieza(s)', 'Adquirido', '2025-08-13 17:00:00', '2025-08-12 12:00:00', NULL, 'SOLICITA ERNESTO PINTADO PARA SU PERSONAL Gerente Volante Jesús Dávila', 'CEDIS', 'Pachuca', 'CLV-20250812133535-16', 'Recibido', NULL, NULL),
(99, 30, 'SKU: WDS100T3X0E', 'SSD WD Black SN770 NVMe 1TB M.2 4900 MB/s Escritura 5150 MB/s Lectura PCI Express 4.0', '1Pieza(s)', 'Adquirido', '2025-08-15 17:00:00', '2025-08-14 12:00:00', NULL, 'DISCO CON POCA CAPACIDAD DE ALMACENAMIENTO', 'IMPLEMENTACIONES', 'Pachuca', 'CLV-20250814120919-18', 'Recibido', NULL, NULL),
(100, 32, 'SKU: WDS100T3X0E', 'SSD WD Black SN770 NVMe 1TB M.2 4900 MB/s Escritura 5150 MB/s Lectura PCI Express 4.0', '1Pieza(s)', 'Adquirido', '2025-08-15 17:00:00', '2025-08-14 12:00:00', NULL, 'DISCO CON POCA CAPACIDAD DE ALMACENAMIENTO', 'IMPLEMENTACIONES', 'Pachuca', 'CLV-20250814121425-70', 'Recibido', NULL, NULL),
(101, 33, 'Bateria', 'Batería para laptop Dell Vostro 3400 Battery Type YRDD6  11.4V  42Wh', '1Pieza(s)', 'Adquirido', '2025-08-15 17:00:00', '2025-08-14 12:00:00', NULL, 'BATERIA YA NO RETIENE CARGA  FIN DE VIDA UTIL', 'SUPERVISORES', 'Pachuca', 'CLV-20250814131924-87', 'Recibido', NULL, NULL),
(102, 33, 'SKU: KF432S20IB/16', 'Memoria RAM Kingston FURY Impact DDR4 3200MHz 16GB Non-ECC CL20 SO-DIMM XMP', '1Pieza(s)', 'Adquirido', '2025-08-16 17:00:00', '2025-08-15 12:00:00', NULL, 'EQUIPO DE CRISTIAN VERA', 'SISTEMAS', 'Pachuca', 'CLV-20250815094608-79', 'Recibido', NULL, NULL),
(103, 33, 'SKU: KF432S20IB/16', 'Memoria RAM Kingston FURY Impact DDR4 3200MHz 16GB Non-ECC CL20 SO-DIMM XMP', '1Pieza(s)', 'Adquirido', '2025-08-16 17:00:00', '2025-08-15 12:00:00', NULL, 'EQUIPO DE CRISTIAN VERA', 'SISTEMAS', 'Pachuca', 'CLV-20250815094610-95', 'Recibido', NULL, NULL),
(104, 34, 'SKU: 12LM000GLS', 'Computadora Lenovo ThinkCentre neo 50q Gen 4 Intel Core i3-1215U 8GB 512GB SSD Wi-Fi Windows 11 Pro + Teclado/Mouse', '3Pieza(s)', 'Adquirido', '2025-08-16 17:00:00', '2025-08-15 12:00:00', NULL, 'EQUIPOS AN DEJADO DE TENER EFICIANCIA POR ANTIGUEDAD Y FALLA EN LAS PRUEBAS DE DIAGNOSTICO', 'EMBARQUES', 'Pachuca', 'CLV-20250815135827-77', 'Recibido', NULL, NULL),
(105, 34, 'SKU: 63FCKARBLA', 'Monitor Lenovo ThinkVision S22I-30 LCD 21.5\" 1920x1080 Full HD 75Hz HDMI Negro/Gris', '3Pieza(s)', 'Adquirido', '2025-08-16 17:00:00', '2025-08-15 12:00:00', NULL, 'EQUIPOS AN DEJADO DE TENER EFICIANCIA POR ANTIGUEDAD Y FALLA EN LAS PRUEBAS DE DIAGNOSTICO', 'EMBARQUES', 'Pachuca', 'CLV-20250815135827-77', 'Recibido', NULL, NULL),
(106, 34, 'SKU: 83A0008HLM', 'Laptop Lenovo V14 G4 IRU 14\" 1920x1080 Full HD Intel Core i7-1355U 16GB 512GB SSD Windows 11 Pro Español', '1Pieza(s)', 'Adquirido', '2025-08-29 17:00:00', '2025-08-28 12:00:00', NULL, 'Renovacion de equipo para Ernesto Pintado', 'CEDIS', 'Pachuca', 'CLV-20250828110507-77', 'Recibido', NULL, NULL),
(107, 34, 'DVR DH-XVR5232AN-I3', 'Dahua DVR de 32 Canales DH-XVR5232AN-4KL-I3 para 2 Discos Duros máx. 16TB 1x USB 2.0 1x RJ-45 1x HDMI', '1Pieza(s)', 'Adquirido', '2025-09-05 17:00:00', '2025-09-04 12:00:00', NULL, 'CAMBIO DE DVR POR DESCARGA ELECTRICA', 'LEON', 'Leon', 'CLV-20250904111150-23', 'Recibido', NULL, NULL),
(108, 35, 'SKU: WD85PURZ', 'Disco Duro para Videovigilancia Western Digital WD Purple Surveillance 3.5\" 8TB SATA III 6 Gbit/s 5400RPM 256MB Caché', '1Pieza(s)', 'Adquirido', '2025-09-05 17:00:00', '2025-09-04 12:00:00', NULL, 'CAMBIO DE DVR POR DESCARGA ELECTRICA', 'LEON', 'Leon', 'CLV-20250904111150-23', 'Recibido', NULL, NULL),
(109, 36, 'SKU: PIENECR2032T5', 'Energizer Pila de Botón Desechable 3V 5 Piezas', '5Paquete(s)', 'Adquirido', '2025-09-05 17:00:00', '2025-09-04 12:00:00', NULL, 'CAMBIO DE BATERIA DE BIOS POR MANTENMIENTO', 'COMPRASVENTAS', 'Pachuca', 'CLV-20250904114948-32', 'Recibido', NULL, NULL),
(110, 36, 'SKU: 12LM000GLS', 'Computadora Lenovo ThinkCentre neo 50q Gen 4 Intel Core i3-1215U 8GB 512GB SSD Wi-Fi Windows 11 Pro + Teclado/Mouse', '1Pieza(s)', 'Adquirido', '2025-09-05 17:00:00', '2025-09-04 12:00:00', NULL, 'RENOVACION DE EQUIPO DE COMPUTO  YA FALLA LA TARJETA PRINCIPAL', 'TULANCINGO', 'Pachuca', 'CLV-20250904131934-68', 'Recibido', NULL, NULL),
(111, 36, 'SKU: 63EBMAR2LA', 'Monitor Lenovo ThinkVision E22-30 LED 21.5\" 1920x1080 Full HD 75Hz HDMI/DisplayPort Bocinas Integradas Negro', '1Pieza(s)', 'Adquirido', '2025-09-05 17:00:00', '2025-09-04 12:00:00', NULL, 'RENOVACION DE EQUIPO DE COMPUTO  YA FALLA LA TARJETA PRINCIPAL', 'TULANCINGO', 'Pachuca', 'CLV-20250904131934-68', 'Recibido', NULL, NULL),
(112, 36, 'SKU: 345M8AA', '2   SSD HP S650 240GB 2.5\" 490 MB/s Escritura 560 MB/s Lectura SATA III', '2Pieza(s)', 'Adquirido', '2025-09-12 17:00:00', '2025-09-11 12:00:00', NULL, 'Actualizacion de equipos discos mecanicos', 'Recibo de Materiales', 'Pachuca', 'CLV-20250911111935-83', 'Recibido', NULL, NULL),
(113, 36, 'SKU: 83A0008MLM', 'Laptop Lenovo V14 14\" 1920x1080 Full HD Intel Core i3-1315U 8GB 256GB SSD Windows 11 Home Español', '1Pieza(s)', 'Adquirido', '2025-09-12 17:00:00', '2025-09-11 12:00:00', NULL, 'Sustitucion de Laptop dañada', 'BODEGAS', 'Pachuca', 'PALABO10', 'Recibido', NULL, NULL),
(114, 37, 'LA45NM140', 'Cargador para laptop Dell vostro 14 3400     19.5v  2.31a modelo cargador  LA45NM140', '1Pieza(s)', 'Adquirido', '2025-09-14 17:00:00', '2025-09-13 12:00:00', NULL, 'Falla de cargador por descarga electrica  Laptop de ventas', 'VENTAS', 'Pachuca', 'PALAVT04', 'Recibido', NULL, NULL),
(115, 37, 'SKU: LP-SFP-10G-RJ45', 'LinkedPRO Módulo Transceptor LP-SFP-10G-RJ45 SFP+ RJ-45 10000 Mbits/s 30 Metros', '2Pieza(s)', 'Adquirido', '2025-09-23 17:00:00', '2025-09-22 12:00:00', NULL, 'Se requieren para el incremento de banda en el firewall uso de la truncal de ISP de aplicativos', 'Sistemas', 'Pachuca', 'CLV-20250922125633-19', 'Recibido', NULL, NULL),
(116, 38, 'MEMORIA RAM', 'Memoria RAM para PC Kingston KCP432NS6/8 DDR4 3200MHz 8GB CL22 Verde SKU: KCP432NS6/8', '1Pieza(s)', 'Adquirido', '2025-09-25 17:00:00', '2025-09-24 12:00:00', NULL, 'MANTENIIENTO DE PC?s  RECIBO Y TELEMARKETI ADICIONAL CAMBIO DE EQUIPO EN JEFE DE ALMACEN', 'CEDIS MERIDA', 'Merida', 'SKU: KCP432NS6/8', 'Recibido', NULL, NULL),
(117, 39, 'MEMORIA RAM', 'Memoria RAM para PC Kingston KCP426NS6/8 DDR4 2666MHz 8GB CL19 Verde SKU: KCP426NS6/8', '1Pieza(s)', 'Adquirido', '2025-09-25 17:00:00', '2025-09-24 12:00:00', NULL, 'MANTENIIENTO DE PC?s  RECIBO Y TELEMARKETI ADICIONAL CAMBIO DE EQUIPO EN JEFE DE ALMACEN', 'CEDIS MERIDA', 'Merida', 'SKU: KCP426NS6/8', 'Recibido', NULL, NULL),
(118, 40, 'COMPUTADORA', 'Computadora Lenovo ThinkCentre neo 50q Gen 4 Intel Core i3-1215U 8GB 512GB SSD Wi-Fi Windows 11 Pro + Teclado/Mouse', '1Pieza(s)', 'Adquirido', '2025-09-25 17:00:00', '2025-09-24 12:00:00', NULL, 'MANTENIIENTO DE PC?s  RECIBO Y TELEMARKETI ADICIONAL CAMBIO DE EQUIPO EN JEFE DE ALMACEN', 'CEDIS MERIDA', 'Merida', 'SKU: 12LM000GLS', 'Recibido', NULL, NULL),
(119, 40, 'NO BREAK', 'No Break CyberPower UT750GU', '1Pieza(s)', 'Adquirido', '2025-09-25 17:00:00', '2025-09-24 12:00:00', NULL, 'MANTENIIENTO DE PC?s  RECIBO Y TELEMARKETI ADICIONAL CAMBIO DE EQUIPO EN JEFE DE ALMACEN', 'CEDIS MERIDA', 'Merida', 'SKU: UT750GU', 'Recibido', NULL, NULL),
(120, 40, 'SKU: KCP432NS6/8', 'Memoria RAM para PC Kingston KCP432NS6/8 DDR4 3200MHz 8GB CL22 Verde', '1Pieza(s)', 'Adquirido', '2025-09-25 17:00:00', '2025-09-24 12:00:00', NULL, 'MANTENIIENTO DE PC?s  RECIBO Y TELEMARKETI ADICIONAL CAMBIO DE EQUIPO EN JEFE DE ALMACEN', 'CEDIS MERIDA', 'Merida', 'CLV-20250924181411-95', 'Recibido', NULL, NULL),
(121, 40, 'SKU: KCP426NS6/8', 'Memoria RAM para PC Kingston KCP426NS6/8 DDR4 2666MHz 8GB CL19 Verde', '1Pieza(s)', 'Adquirido', '2025-09-25 17:00:00', '2025-09-24 12:00:00', NULL, 'MANTENIIENTO DE PC?s  RECIBO Y TELEMARKETI ADICIONAL CAMBIO DE EQUIPO EN JEFE DE ALMACEN', 'CEDIS MERIDA', 'Merida', 'CLV-20250924181411-95', 'Recibido', NULL, NULL),
(122, 40, 'SKU: 12LM000GLS', 'Computadora Lenovo ThinkCentre neo 50q Gen 4 Intel Core i3-1215U 8GB 512GB SSD Wi-Fi Windows 11 Pro + Teclado/Mouse', '1Pieza(s)', 'Adquirido', '2025-09-25 17:00:00', '2025-09-24 12:00:00', NULL, 'MANTENIIENTO DE PC?s  RECIBO Y TELEMARKETI ADICIONAL CAMBIO DE EQUIPO EN JEFE DE ALMACEN', 'CEDIS MERIDA', 'Merida', 'CLV-20250924181411-95', 'Recibido', NULL, NULL),
(123, 40, 'SKU: UT750GU', 'No Break CyberPower UT750GU', '1Pieza(s)', 'Adquirido', '2025-09-25 17:00:00', '2025-09-24 12:00:00', NULL, 'MANTENIIENTO DE PC?s  RECIBO Y TELEMARKETI ADICIONAL CAMBIO DE EQUIPO EN JEFE DE ALMACEN', 'CEDIS MERIDA', 'Merida', 'CLV-20250924181411-95', 'Recibido', NULL, NULL),
(124, 40, 'SKU: NX.B64AL.006', 'Laptop Acer Travelmate P2 14\" 1920x1200 WUXGA Intel Core i7-1355U 16GB 512GB SSD Windows 11 Pro Español', '1Pieza(s)', 'Adquirido', '2025-09-27 17:00:00', '2025-09-26 12:00:00', NULL, 'RENOVACION DE LAPTOP PARA FERNANDO REYES EQUIPO PROPENSO A FALLAS POR SESULTADOS DE TEST', 'MERCADOTECNIA', 'Pachuca', 'CLV-20250926110755-51', 'Recibido', NULL, NULL),
(125, 40, 'SKU: ZD22042-D01G00EZ', 'Zebra ZD220 Impresora de Etiquetas Térmica Directa USB 203 x 203DPI Negro ― No Requiere Cinta de Impresión', '2Pieza(s)', 'Adquirido', '2025-09-28 17:00:00', '2025-09-27 12:00:00', NULL, 'DAÑO DE IMPRESORA POR USO.', 'MONTERREY', 'Monterrey', 'CLV-20250927121302-81', 'Recibido', NULL, NULL),
(126, 40, 'Almoadillas', 'Almohadillas (sin base) para Impresora Epson L3150', '1Pieza(s)', 'Adquirido', '2025-10-02 17:00:00', '2025-10-01 12:00:00', NULL, 'MANTENIMIENTO A IMPRESORA', 'REFACCIONARIAS', 'Pachuca', 'CLV-20251001142558-98', 'Recibido', NULL, NULL),
(127, 44, 'Laptop Hp', 'Core i7, 16Gb de RAM', '1Pz', 'En proceso', '2025-10-10 10:40:32', '2025-10-10 10:30:22', '', 'Mi lap esta fea', 'Sistemas', 'PACHUCA', 'PALASI05', 'Pendiente', NULL, NULL),
(128, 44, 'Silla Gamer', 'Para evitar dolores de espalda', '1Pz', 'En proceso', '2025-10-10 10:40:28', '2025-10-10 10:30:22', '', 'Cambio', 'Sistemas', 'PACHUCA', '', 'Pendiente', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requisiciones`
--

CREATE TABLE `requisiciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `requisiciones`
--

INSERT INTO `requisiciones` (`id`, `usuario_id`, `fecha`, `nombre`) VALUES
(1, 1, '2025-03-04 18:00:00', NULL),
(2, 1, '2025-03-15 18:00:00', ''),
(3, 1, '2025-03-19 18:00:00', ''),
(4, 1, '2025-03-20 18:00:00', ''),
(5, 1, '2025-03-21 18:00:00', ''),
(6, 1, '2025-03-26 18:00:00', ''),
(7, 1, '2025-04-02 18:00:00', ''),
(8, 1, '2025-04-01 18:00:00', ''),
(9, 1, '2025-04-11 18:00:00', ''),
(10, 1, '2025-04-25 18:00:00', ''),
(11, 1, '2025-05-09 18:00:00', ''),
(12, 1, '2025-05-13 18:00:00', ''),
(13, 1, '2025-05-15 18:00:00', ''),
(14, 1, '2025-05-16 18:00:00', ''),
(15, 1, '2025-05-23 18:00:00', ''),
(16, 1, '2025-05-30 18:00:00', ''),
(17, 1, '2025-06-05 18:00:00', ''),
(18, 1, '2025-06-06 18:00:00', ''),
(19, 1, '2025-06-11 18:00:00', ''),
(20, 1, '2025-06-19 18:00:00', ''),
(21, 1, '2025-06-23 18:00:00', ''),
(22, 1, '2025-07-09 18:00:00', ''),
(23, 1, '2025-07-18 18:00:00', ''),
(24, 1, '2025-07-21 18:00:00', ''),
(25, 1, '2025-07-23 18:00:00', ''),
(26, 1, '2025-07-25 18:00:00', ''),
(27, 1, '2025-07-30 18:00:00', ''),
(28, 1, '2025-07-31 18:00:00', ''),
(29, 1, '2025-08-01 18:00:00', ''),
(30, 1, '2025-08-08 18:00:00', ''),
(31, 1, '2025-08-07 18:00:00', ''),
(32, 1, '2025-08-12 18:00:00', ''),
(33, 1, '2025-08-14 18:00:00', ''),
(34, 1, '2025-08-15 18:00:00', ''),
(35, 1, '2025-08-28 18:00:00', ''),
(36, 1, '2025-09-04 18:00:00', ''),
(37, 1, '2025-09-11 18:00:00', ''),
(38, 1, '2025-09-13 18:00:00', ''),
(39, 1, '2025-09-22 18:00:00', ''),
(40, 1, '2025-09-24 18:00:00', ''),
(41, 1, '2025-09-26 18:00:00', ''),
(42, 1, '2025-09-27 18:00:00', ''),
(43, 1, '2025-10-01 18:00:00', ''),
(44, 1, '2025-10-10 16:30:22', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `area` varchar(100) NOT NULL,
  `cedis` varchar(200) NOT NULL,
  `rol` enum('TI','Operador') DEFAULT 'TI'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `area`, `cedis`, `rol`) VALUES
(1, 'Jonathan', '$2y$10$Wsy3hBNnLpByTjAquHxdquCAgFc1F206oYJ/f4n5DRTsWvJgm4eoi', 'SISTEMAS', 'Pachuca', 'TI'),
(2, 'JonathanR', '$2y$10$l.trYKpgxQxVNbbLwiBsC.Axw6nvuW9YoQpP4v1GwK.ZHoHkCHNea', 'SISTEMAS', 'Pachuca', 'Operador');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requisicion_id` (`requisicion_id`);

--
-- Indices de la tabla `requisiciones`
--
ALTER TABLE `requisiciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT de la tabla `requisiciones`
--
ALTER TABLE `requisiciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`requisicion_id`) REFERENCES `requisiciones` (`id`);

--
-- Filtros para la tabla `requisiciones`
--
ALTER TABLE `requisiciones`
  ADD CONSTRAINT `requisiciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
