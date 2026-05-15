<?php

header("Content-Type: application/json");

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

// 🔹 fin
$fecha_fin = date(
    "Y-m-d H:i:s",
    strtotime($fecha_inicio . " +1 hour")
);

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