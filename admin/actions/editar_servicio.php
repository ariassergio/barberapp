<?php

require_once '../config/db.php';

$id = $_POST['id_servicio'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$duracion = $_POST['duracion'];
$unidad = $_POST['unidad'];

$sql = "UPDATE servicios 
        SET 
            nombre = ?, 
            precio = ?, 
            duracion = ?, 
            unidad_tiempo = ?
        WHERE id_servicio = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sdisi",
    $nombre,
    $precio,
    $duracion,
    $unidad,
    $id
);

mysqli_stmt_execute($stmt);

header("Location: ../servicios.php");

?>