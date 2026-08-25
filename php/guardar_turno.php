<?php
header("Content-Type: application/json");

// Forzamos errores visibles si algo llega a fallar
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../admin/config/db.php";

// Sincronizamos la conexión con tu db.php
if (isset($conn) && !isset($conexion)) {
    $conexion = $conn;
}

if (!isset($conexion)) {
    $conexion = new mysqli("localhost", "root", "", "peluqueria");
}

if ($conexion->connect_error) {
    echo json_encode(["success" => false, "error" => "Error de conexión"]);
    exit;
}

// 🔹 Recibir JSON del frontend
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "error" => "No JSON received"]);
    exit;
}

$id_profesional = intval($data["peluqueroId"]);
$id_servicio    = intval($data["servicioId"]);
$nombre         = $data["cliente"];
$telefono       = $data["telefono"];
$fecha          = $data["fecha"];
$hora           = $data["hora"];
$estado         = $data["estado"] ?? "pendiente";

// Datetime de inicio
$fecha_inicio = $fecha . " " . $hora . ":00";

// ✅ CORRECCIÓN DE LA COLUMNA: Ahora usamos 'duracion' que es la real en tu tabla
$stmtSrv = $conexion->prepare("SELECT duracion FROM servicios WHERE id_servicio = ?");
$stmtSrv->bind_param("i", $id_servicio);
$stmtSrv->execute();
$srv = $stmtSrv->get_result()->fetch_assoc();

// Obtenemos los minutos del servicio (si no existe o es 0, por defecto le damos 60)
$duracion_minutos = ($srv && $srv['duracion'] > 0) ? intval($srv['duracion']) : 60;

// Calculamos la fecha de fin exacta sumando los minutos correspondientes
$fecha_fin = date("Y-m-d H:i:s", strtotime($fecha_inicio . " +{$duracion_minutos} minutes"));

// 🔹 Insertar en la tabla turnos
$sql = "INSERT INTO turnos (
    id_profesional,
    id_servicio,
    nombre,
    telefono,
    fecha_inicio,
    fecha_fin,
    estado
) VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Error SQL: " . $conexion->error]);
    exit;
}

$stmt->bind_param(
    "iisssss",
    $id_profesional,
    $id_servicio,
    $nombre,
    $telefono,
    $fecha_inicio,
    $fecha_fin,
    $estado
);

$ok = $stmt->execute();

// Respuesta al JS
echo json_encode([
    "success" => $ok,
    "error" => $ok ? null : $stmt->error
]);