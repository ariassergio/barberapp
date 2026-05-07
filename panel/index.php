<?php
session_start();

// 🔹 lista de peluqueros
$peluqueros = [
    [
        "id" => 1,
        "nombre" => "Matías"
    ],
    [
        "id" => 2,
        "nombre" => "Ezequiel"
    ],
    [
        "id" => 3,
        "nombre" => "Sergio"
    ]
];

// 🔹 login
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $peluqueroId = $_POST["peluquero"] ?? null;

    foreach ($peluqueros as $p) {

        if ($p["id"] == $peluqueroId) {

            $_SESSION["peluquero_id"] = $p["id"];
            $_SESSION["peluquero_nombre"] = $p["nombre"];

            header("Location: dashboard.php");
            exit;
        }
    }

    $error = "Peluquero inválido";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Panel Peluqueros</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/login.css">

</head>
<body>

<div class="login-card">

    <div class="logo">
        💈
    </div>

    <h1>Panel de Peluqueros</h1>

    <p class="subtitle">
        Accedé para administrar tus turnos
    </p>

    <?php if(isset($error)): ?>

        <div class="alert alert-danger">
            <?= $error ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">

            <label class="form-label">
                Seleccionar peluquero
            </label>

            <select
                name="peluquero"
                class="form-select"
                required
            >

                <option value="">
                    Seleccionar...
                </option>

                <?php foreach($peluqueros as $p): ?>

                    <option value="<?= $p["id"] ?>">
                        <?= $p["nombre"] ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <button type="submit" class="btn btn-dark">
            Ingresar
        </button>

    </form>

</div>

</body>
</html>