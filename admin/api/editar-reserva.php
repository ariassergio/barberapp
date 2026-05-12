<?php

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$archivo = "../../data/reservas.json";

if (!file_exists($archivo)) {

    echo json_encode([
        "ok" => false,
        "mensaje" => "Archivo no encontrado"
    ]);

    exit;
}

$reservas = json_decode(
    file_get_contents($archivo),
    true
);

if (!$reservas) {
    $reservas = [];
}

foreach ($reservas as &$reserva) {

    if ($reserva["id"] == $data["id"]) {

        $reserva["cliente"] = $data["cliente"];
        $reserva["telefono"] = $data["telefono"];

        $reserva["serviciosTexto"] = $data["servicio"];

        $reserva["peluquero"] = $data["peluquero"];

        $reserva["fecha"] = $data["fecha"];
        $reserva["hora"] = $data["hora"];

        $reserva["estado"] = $data["estado"];
    }
}

file_put_contents(
    $archivo,
    json_encode(
        $reservas,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    )
);

echo json_encode([
    "ok" => true
]);