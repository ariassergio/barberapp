<?php
$conn = mysqli_connect('localhost', 'root', '', 'peluqueria');
$res = mysqli_query($conn, 'SELECT * FROM turnos');
$turnos = mysqli_fetch_all($res, MYSQLI_ASSOC);
foreach ($turnos as $turno) {
    echo "ID: " . $turno['id_turno'] . " | Fecha: " . $turno['fecha_inicio'] . " | Estado: " . $turno['estado'] . "\n";
}
?>
