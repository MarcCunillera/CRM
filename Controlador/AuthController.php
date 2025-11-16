<?php
// Controlador/AuthController.php
session_start();

require_once __DIR__ . '/../Modelo/Usuario.php';

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

if ($accion === 'login') {

    $email    = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($email === '' || $password === '') {
        header("Location: /Vista/Auth/login.php?error=1");
        exit;
    }

    $usuarioModel = new Usuario();
    $usuario      = $usuarioModel->getByEmailYPassword($email, $password);

    if ($usuario) {
        // Login correcto
        $_SESSION['id_usuario']      = $usuario['id_usuario'];
        $_SESSION['nombre_usuario']  = $usuario['nombre_usuario'];
        $_SESSION['rol']             = $usuario['rol'];

        header("Location: /index.php");
        exit;
    } else {
        // Usuario o contraseña incorrectos
        header("Location: /Vista/Auth/login.php?error=1");
        exit;
    }

} elseif ($accion === 'logout') {

    // Cerrar sesión
    session_unset();
    session_destroy();
    header("Location: /Vista/Auth/login.php");
    exit;

} else {
    // Acción no válida
    header("Location: /Vista/Auth/login.php");
    exit;
}
