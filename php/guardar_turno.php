<?php

header("Content-Type: application/json");
require_once __DIR__ . "/../admin/config/db.php";

$conexion = new mysqli(
    "localhost",
    "root",
    "",
    "peluqueria"
);

if ($conexion->connect_error) {

    echo json_encode([
        "success" => false
    ]);

    exit;
}

// 🔹 recibir JSON
$data = json_decode(
    file_get_contents("php://input"),
    true
);

// 🔹 datos
$id_profesional = $data["peluqueroId"];
$id_servicio = $data["servicioId"];

$nombre = $data["cliente"];
$telefono = $data["telefono"];

$fecha = $data["fecha"];
$hora = $data["hora"];

// 🔹 datetime
$fecha_inicio = $fecha . " " . $hora . ":00";

// ✅ CORRECTO — calculá la duración real del servicio
$id_servicio = intval($data["servicioId"]);
$stmtSrv = $conn->prepare("SELECT duracion_minutos FROM servicios WHERE id_servicio = ?");
$stmtSrv->bind_param("i", $id_servicio);
$stmtSrv->execute();
$srv = $stmtSrv->get_result()->fetch_assoc();

$duracion = ($srv && $srv['duracion_minutos'] > 0) ? $srv['duracion_minutos'] : 60;
$fecha_fin = date("Y-m-d H:i:s", strtotime($fecha_inicio . " +{$duracion} minutes"));
// 🔹 insert
$sql = "INSERT INTO turnos (

    id_profesional,
    id_servicio,
    nombre,
    telefono,
    fecha_inicio,
    fecha_fin,
    estado

) VALUES (

    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?

)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(

    "iisssss",

    $id_profesional,
    $id_servicio,
    $nombre,
    $telefono,
    $fecha_inicio,
    $fecha_fin,
    $data["estado"]
);

$ok = $stmt->execute();

echo json_encode([
    "success" => $ok
]);