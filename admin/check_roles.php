<?php
require_once 'config/db.php';
$r = mysqli_query($conn, 'SELECT id_usuario, usuario, rol FROM usuarios');
echo "<pre>";
while($row = mysqli_fetch_assoc($r)) {
    echo $row['id_usuario'] . ' | ' . $row['usuario'] . ' | ' . $row['rol'] . PHP_EOL;
}
echo "</pre>";
?>
