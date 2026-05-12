<?php

header("Content-Type: application/json");

$fecha = $_GET['fecha'] ?? '';
$peluqueroId = $_GET['peluqueroId'] ?? '';

$horariosBase = [
    "09:00",
    "10:00",
    "11:00",
    "12:00",
    "14:00",
    "15:00",
    "16:00",
    "17:00"
];

$archivo = "../data/reservas.json";

$reservas = [];

if (file_exists($archivo)) {

    $json = file_get_contents($archivo);

    $reservas = json_decode($json, true) ?? [];

}

$horariosOcupados = [];

foreach ($reservas as $reserva) {

    if (
        $reserva['fecha'] === $fecha &&
        $reserva['peluqueroId'] == $peluqueroId &&
        $reserva['estado'] !== 'cancelado'
    ) {

        $horariosOcupados[] = $reserva['hora'];

    }

}

$horariosDisponibles = array_values(
    array_diff($horariosBase, $horariosOcupados)
);

echo json_encode($horariosDisponibles);