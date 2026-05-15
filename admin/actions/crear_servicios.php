<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../servicios.php");
    exit;
}

$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$duracion = $_POST['duracion'];

$sql = "INSERT INTO servicios (
            nombre,
            precio,
            duracion_minutos
        ) VALUES (
            '$nombre',
            '$precio',
            '$duracion'
        )";

$query = mysqli_query($conn, $sql);

if (!$query) {
    die(mysqli_error($conn));
}

header("Location: ../servicios.php");
exit;