<?php

require_once '../config/db.php';

$id = $_POST['id_servicio'];
$estadoActual = $_POST['estado_actual'];

$nuevoEstado = ($estadoActual == 1) ? 0 : 1;

$sql = "UPDATE servicios
        SET activo = ?
        WHERE id_servicio = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $nuevoEstado,
    $id
);

mysqli_stmt_execute($stmt);

header("Location: ../servicios.php");

?>