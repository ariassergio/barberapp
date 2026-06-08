<?php

header("Content-Type: application/json");

require_once '../config/db.php';

$id = (int) $_GET['id'];

$sql  = "UPDATE turnos SET estado = 'cancelado' WHERE id_turno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$ok = $stmt->execute();

echo json_encode(["ok" => $ok]);