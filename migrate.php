<?php
require 'admin/config/db.php';

// Agregar columnas a configuracion_sistema
$cols = [
    "ALTER TABLE configuracion_sistema ADD COLUMN IF NOT EXISTS quienes_somos TEXT",
    "ALTER TABLE configuracion_sistema ADD COLUMN IF NOT EXISTS slogan VARCHAR(255)",
    "ALTER TABLE configuracion_sistema ADD COLUMN IF NOT EXISTS banner1 VARCHAR(255)",
    "ALTER TABLE configuracion_sistema ADD COLUMN IF NOT EXISTS banner2 VARCHAR(255)",
    "ALTER TABLE configuracion_sistema ADD COLUMN IF NOT EXISTS banner3 VARCHAR(255)",
    "ALTER TABLE configuracion_sistema ADD COLUMN IF NOT EXISTS logo_url VARCHAR(255)",
    "ALTER TABLE configuracion_sistema ADD COLUMN IF NOT EXISTS titulo_pagina VARCHAR(100)",
];

foreach ($cols as $sql) {
    $r = mysqli_query($conn, $sql);
    echo $sql . " -> " . ($r ? "OK" : mysqli_error($conn)) . "\n";
}

// Agregar imagen_url a servicios
$r = mysqli_query($conn, "ALTER TABLE servicios ADD COLUMN IF NOT EXISTS imagen_url VARCHAR(255)");
echo "ALTER TABLE servicios ADD COLUMN imagen_url -> " . ($r ? "OK" : mysqli_error($conn)) . "\n";

// Crear carpeta uploads
$uploadDir = __DIR__ . '/assets/img/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
    echo "Carpeta uploads creada\n";
} else {
    echo "Carpeta uploads ya existe\n";
}

echo "DONE";
