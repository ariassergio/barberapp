<?php

include("../admin/config/db.php");

$sql = "SELECT 
            id_servicio AS id,
            nombre,
            precio,
            duracion,
            unidad_tiempo
        FROM servicios
        WHERE activo = 1";

$resultado = mysqli_query($conn, $sql);

$servicios = [];

while($fila = mysqli_fetch_assoc($resultado)){

    $servicios[] = $fila;
}

header('Content-Type: application/json');

echo json_encode($servicios);

?>