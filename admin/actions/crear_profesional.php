<?php

require_once '../config/db.php';

$nombre = $_POST['nombre'];

$especialidades = implode(
    ", ",
    $_POST['especialidades']
);

$sql = "INSERT INTO profesionales(
            nombre,
            especialidad
        )
        VALUES(
            '$nombre',
            '$especialidades'
        )";

mysqli_query($conn, $sql);

header("Location: ../barberos.php");

?>