<?php

require_once '../config/db.php';

$id_profesional = $_POST['id_profesional'];

$dia_franco = $_POST['dia_franco'];

$hora_inicio = $_POST['hora_inicio'];

$hora_fin = $_POST['hora_fin'];

$sql = "INSERT INTO horarios_profesionales(

            id_profesional,
            hora_inicio,
            hora_fin,
            dia_franco

        )

        VALUES(

            '$id_profesional',
            '$hora_inicio',
            '$hora_fin',
            '$dia_franco'

        )";

mysqli_query($conn, $sql);

header("Location: ../barberos.php");

?>