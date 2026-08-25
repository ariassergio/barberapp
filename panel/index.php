<?php
session_start();
// Ajusta esta ruta si es necesario para llegar a tu archivo de conexión a BD
require_once '../admin/config/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Escapar datos para evitar inyecciones SQL
    $usuario_input = mysqli_real_escape_string($conn, $_POST['usuario']);
    $password_input = $_POST['password'];

    // Buscamos el usuario y su profesional vinculado
    $sql = "SELECT u.id_usuario, u.usuario, u.password, u.rol, u.id_profesional, p.nombre AS nombre_profesional 
            FROM usuarios u 
            LEFT JOIN profesionales p ON u.id_profesional = p.id_profesional 
            WHERE u.usuario = '$usuario_input' LIMIT 1";
    
    $resultado = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($resultado);

    // Verificar si el usuario existe y si la contraseña es correcta
    if ($user && password_verify($password_input, $user['password'])) {
        
        // Guardar datos en sesión
        $_SESSION["usuario_id"] = $user["id_usuario"];
        $_SESSION["peluquero_id"] = $user["id_profesional"]; // NULL si es admin
        $_SESSION["peluquero_nombre"] = $user['nombre_profesional'] ?? $user['usuario'];
        $_SESSION["rol"] = $user["rol"];

        // Redirigir al dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Peluqueros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

<div class="login-card">
    <div class="logo">💈</div>
    <h1>Acceso al Panel</h1>
    <p class="subtitle">Ingresá tus credenciales para administrar turnos</p>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="usuario" class="form-control" required placeholder="Tu nombre de usuario">
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-dark w-100">Ingresar</button>
    </form>
</div>

</body>
</html>