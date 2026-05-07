<?php
session_start();

// 🔒 proteger acceso
if (!isset($_SESSION["peluquero_id"])) {

    header("Location: index.php");
    exit;
}

// 🔹 datos sesión
$peluqueroId = $_SESSION["peluquero_id"];
$peluqueroNombre = $_SESSION["peluquero_nombre"];

// 🔹 archivo reservas
$archivo = "../data/reservas.json";

// 🔹 obtener reservas
$reservas = [];

if (file_exists($archivo)) {

    $json = file_get_contents($archivo);

    $reservas = json_decode($json, true) ?? [];
}

// 🔹 filtrar reservas del peluquero
$reservasPeluquero = array_filter($reservas, function($r) use ($peluqueroId){

    return $r["peluqueroId"] == $peluqueroId;
});

// 🔹 ordenar por fecha y hora
usort($reservasPeluquero, function($a, $b){

    return strtotime($a["fecha"] . " " . $a["hora"])
        - strtotime($b["fecha"] . " " . $b["hora"]);
});
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Dashboard</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet" href="assets/css/dashboard.css">

</head>
<body>

<!-- 🔹 navbar -->
<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <span class="navbar-brand">
            💈 Panel de <?= $peluqueroNombre ?>
        </span>

        <a href="logout.php" class="btn btn-outline-light btn-sm">
            Cerrar sesión
        </a>

    </div>

</nav>

<!-- 🔹 contenido -->
<div class="container my-5">

    <h2 class="mb-4">
        Tus turnos
    </h2>

    <?php if(empty($reservasPeluquero)): ?>

        <div class="alert alert-info">
            No tenés turnos asignados
        </div>

    <?php else: ?>

        <div class="row g-4">

            <?php foreach($reservasPeluquero as $r): ?>

                <div class="col-md-4">

                    <div class="turno-card">

                        <div class="hora">
                            <?= $r["hora"] ?>
                        </div>

                        <div class="fecha">
                            <?= $r["fecha"] ?>
                        </div>

                        <h5>
                            <?= $r["cliente"] ?>
                        </h5>

                        <p class="servicio">
                            <?= $r["servicio"] ?>
                        </p>
                        <p class="precio">
                            💲 <?= number_format($r["precio"], 0, ",", ".") ?>
                        </p>
                        <p class="telefono">
                            📞 <?= $r["telefono"] ?>
                        </p>

                        <div class="estado estado-<?= $r["estado"] ?>">

                            <?= strtoupper($r["estado"]) ?>

                        </div>

                        <div class="acciones mt-3">

                            <button class="btn btn-success btn-sm">
                                ✔ Realizado
                            </button>

                            <button class="btn btn-danger btn-sm">
                                ❌ Ausente
                            </button>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

</body>
</html>