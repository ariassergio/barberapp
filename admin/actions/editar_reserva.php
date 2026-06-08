<?php

header("Content-Type: application/json");

require_once '../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id_turno'])) {
    echo json_encode(["ok" => false, "mensaje" => "Datos inválidos"]);
    exit;
}

$id       = (int) $data['id_turno'];
$estado   = $data['estado'];
$fecha    = $data['fecha'];        // formato Y-m-d
$hora     = $data['hora'];         // formato H:i
$id_prof  = (int) $data['id_profesional'];
$id_serv  = (int) $data['id_servicio'];

$fecha_inicio = $fecha . " " . $hora . ":00";
$fecha_fin    = date("Y-m-d H:i:s", strtotime($fecha_inicio . " +1 hour"));

$sql = "UPDATE turnos
        SET estado         = ?,
            fecha_inicio   = ?,
            fecha_fin      = ?,
            id_profesional = ?,
            id_servicio    = ?
        WHERE id_turno = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiii", $estado, $fecha_inicio, $fecha_fin, $id_prof, $id_serv, $id);
$ok = $stmt->execute();

echo json_encode(["ok" => $ok]);