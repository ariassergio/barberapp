<?php

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$archivo = "../data/reservas.json";

$reservas = [];

if (file_exists($archivo)) {

    $json = file_get_contents($archivo);

    $reservas = json_decode($json, true) ?? [];
}

$data["id"] = count($reservas) + 1;

$reservas[] = $data;

file_put_contents(
    $archivo,
    json_encode($reservas, JSON_PRETTY_PRINT)
);

echo json_encode([
    "ok" => true
]);