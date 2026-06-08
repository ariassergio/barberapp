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

// panel/index.php — validación real
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id  = intval($_POST["peluquero"] ?? 0);
    $pin = $_POST["pin"] ?? '';

    $stmt = $conn->prepare("SELECT id_profesional, nombre FROM profesionales WHERE id_profesional = ? AND pin = ? AND activo = 1");
    $stmt->bind_param("is", $id, $pin);
    $stmt->execute();
    $prof = $stmt->get_result()->fetch_assoc();

    if ($prof) {
        $_SESSION["peluquero_id"]     = $prof["id_profesional"];
        $_SESSION["peluquero_nombre"] = $prof["nombre"];
        header("Location: dashboard.php");
        exit;
    }
    $error = "PIN incorrecto";
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