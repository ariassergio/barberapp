<?php
// Subimos un nivel para llegar a la carpeta admin y entrar a config/db.php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar los datos
    $usuario = mysqli_real_escape_string($conn, trim($_POST['usuario']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password_plana = trim($_POST['password']);
    $rol = mysqli_real_escape_string($conn, $_POST['rol']);
    
    // Si es admin, id_profesional es NULL, sino tomamos el valor del select
    $id_profesional = !empty($_POST['id_profesional']) ? intval($_POST['id_profesional']) : 'NULL';

    // 1. Validar duplicados
    $vacheck = "SELECT id_usuario FROM usuarios WHERE usuario = '$usuario' OR email = '$email' LIMIT 1";
    $res_check = mysqli_query($conn, $vacheck);

    if (mysqli_num_rows($res_check) > 0) {
        // Redirigimos un nivel atrás, a la carpeta 'admin' donde está 'usuarios.php'
        header("Location: ../usuarios.php?error=existente");
        exit;
    }

    // 2. Encriptar contraseña
    $password_encriptada = password_hash($password_plana, PASSWORD_DEFAULT);

    // 3. Insertar
    $sql = "INSERT INTO usuarios (usuario, email, password, rol, id_profesional) 
            VALUES ('$usuario', '$email', '$password_encriptada', '$rol', $id_profesional)";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../usuarios.php?success=creado");
    } else {
        header("Location: ../usuarios.php?error=db");
    }
    exit;
} else {
    // Si entran directo, volvemos a la tabla
    header("Location: ../usuarios.php");
    exit;
}