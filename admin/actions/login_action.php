<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = mysqli_real_escape_string($conn, trim($_POST['usuario']));
    $password_plana = trim($_POST['password']);

    if (empty($usuario) || empty($password_plana)) {
        header("Location: ../login.php?error=empty");
        exit;
    }

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {

        $user = mysqli_fetch_assoc($result);

        // Verificar contraseña (hash o texto plano)
        if (
            password_verify($password_plana, $user['password']) ||
            $password_plana === $user['password']
        ) {

            // Si estaba en texto plano, actualizar a hash
            if ($password_plana === $user['password']) {
                $hash = password_hash($password_plana, PASSWORD_DEFAULT);
                $id_user = $user['id_usuario'];

                mysqli_query(
                    $conn,
                    "UPDATE usuarios SET password = '$hash' WHERE id_usuario = $id_user"
                );
            }

            // Crear sesión
            $_SESSION['admin_id'] = $user['id_usuario'];
            $_SESSION['admin_usuario'] = $user['usuario'];
            $_SESSION['admin_rol'] = $user['rol'];

            // Redirigir siempre al panel principal
            header('Location: ../admin.php');
            exit;

        } else {
            header("Location: ../login.php?error=invalid");
            exit;
        }

    } else {
        header("Location: ../login.php?error=invalid");
        exit;
    }

} else {
    header("Location: ../login.php");
    exit;
}