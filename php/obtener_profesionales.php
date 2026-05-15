<?php

include("../admin/config/db.php");

$sql = "SELECT 
            id_profesional AS id,
            nombre,
            especialidad
        FROM profesionales
        WHERE activo = 1";

$resultado = mysqli_query($conn, $sql);

$profesionales = [];

while($fila = mysqli_fetch_assoc($resultado)){

    $profesionales[] = $fila;
}

header('Content-Type: application/json');

echo json_encode($profesionales);

?>