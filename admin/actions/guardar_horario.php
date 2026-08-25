<?php

require_once '../config/db.php';

$id_profesional = $_POST['id_profesional'];

$dia_franco = $_POST['dia_franco'];

$hora_inicio = $_POST['hora_inicio'];

$hora_fin = $_POST['hora_fin'];

$check = "SELECT id_horario FROM horarios_profesionales WHERE id_profesional = '$id_profesional'";
$res = mysqli_query($conn, $check);

if (mysqli_num_rows($res) > 0) {
    // Update
    $sql = "UPDATE horarios_profesionales SET 
                hora_inicio = '$hora_inicio',
                hora_fin = '$hora_fin',
                dia_franco = '$dia_franco'
            WHERE id_profesional = '$id_profesional'";
} else {
    // Insert
    $sql = "INSERT INTO horarios_profesionales(id_profesional, hora_inicio, hora_fin, dia_franco)
            VALUES('$id_profesional', '$hora_inicio', '$hora_fin', '$dia_franco')";
}

mysqli_query($conn, $sql);

header("Location: ../barberos.php");

?>