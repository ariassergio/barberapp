<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../config/db.php";

// Capturar el JSON enviado por JavaScript
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && isset($data['estado'])) {
    $id = intval($data['id']);
    $estado = mysqli_real_escape_string($conn, $data['estado']);

    // Actualizamos el estado del turno
    $sql = "UPDATE turnos SET estado = '$estado' WHERE id_turno = $id";
    $ok = mysqli_query($conn, $sql);

    echo json_encode([
        "success" => $ok,
        "error" => $ok ? null : mysqli_error($conn)
    ]);
} else {
    echo json_encode([
        "success" => false, 
        "error" => "Datos de actualización incompletos."
    ]);
}