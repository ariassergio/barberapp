<?php
require 'admin/config/db.php';
mysqli_query($conn, 'CREATE TABLE IF NOT EXISTS configuracion_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    nombre_barberia VARCHAR(100), 
    telefono VARCHAR(50), 
    instagram VARCHAR(100), 
    facebook VARCHAR(100), 
    intervalo_turnos INT DEFAULT 30, 
    hora_apertura TIME, 
    hora_cierre TIME
)');
mysqli_query($conn, 'INSERT INTO configuracion_sistema (nombre_barberia, telefono, intervalo_turnos, hora_apertura, hora_cierre) VALUES (\'BarberApp\', \'+54 11 1234-5678\', 30, \'09:00\', \'20:00\') ON DUPLICATE KEY UPDATE id=id');
echo 'OK';
