<?php
require_once '../../admin/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conn, trim($_POST['nombre_barberia']));
    $telefono = mysqli_real_escape_string($conn, trim($_POST['telefono']));
    $instagram = mysqli_real_escape_string($conn, trim($_POST['instagram']));
    $facebook = mysqli_real_escape_string($conn, trim($_POST['facebook']));
    $intervalo = intval($_POST['intervalo_turnos']);
    $apertura = mysqli_real_escape_string($conn, trim($_POST['hora_apertura']));
    $cierre = mysqli_real_escape_string($conn, trim($_POST['hora_cierre']));
    
    $check = mysqli_query($conn, "SELECT id FROM configuracion_sistema LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        $sql = "UPDATE configuracion_sistema SET 
                    nombre_barberia = '$nombre',
                    telefono = '$telefono',
                    instagram = '$instagram',
                    facebook = '$facebook',
                    intervalo_turnos = $intervalo,
                    hora_apertura = '$apertura',
                    hora_cierre = '$cierre'";
    } else {
        $sql = "INSERT INTO configuracion_sistema (nombre_barberia, telefono, instagram, facebook, intervalo_turnos, hora_apertura, hora_cierre)
                VALUES ('$nombre', '$telefono', '$instagram', '$facebook', $intervalo, '$apertura', '$cierre')";
    }
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../configuracion.php?success=1");
    } else {
        header("Location: ../configuracion.php?error=db");
    }
    exit;
} else {
    header("Location: ../configuracion.php");
    exit;
}
