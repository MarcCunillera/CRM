<?php
// Controlador/UsuarioController.php
session_start();

require_once __DIR__ . '/../Modelo/Usuario.php';

// Comprobar que el usuario es admin
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /Vista/Auth/login.php");
    exit;
}

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

$usuarioModel = new Usuario();

switch ($accion) {

    case 'crear':
        $nombre = $_POST['nombre_usuario'] ?? '';
        $email  = $_POST['email'] ?? '';
        $pass   = $_POST['password'] ?? '';
        $rol    = $_POST['rol'] ?? 'vendedor';

        if ($nombre === '' || $email === '' || $pass === '') {
            header("Location: /Vista/Auth/registra.php?error=1");
            exit;
        }

        $data = [
            'nombre_usuario' => $nombre,
            'email'          => $email,
            'password'       => $pass,
            'rol'            => $rol
        ];

        $usuarioModel->crear($data);

        header("Location: /Vista/Usuarios/listarUsuario.php?msg=creado");
        exit;

    case 'actualizar':
        $id     = $_POST['id_usuario'] ?? '';
        $nombre = $_POST['nombre_usuario'] ?? '';
        $email  = $_POST['email'] ?? '';
        $rol    = $_POST['rol'] ?? 'vendedor';

        if ($id === '' || $nombre === '' || $email === '') {
            header("Location: /Vista/Usuarios/formUsuario.php?error=1&id=" . $id);
            exit;
        }

        $data = [
            'nombre_usuario' => $nombre,
            'email'          => $email,
            'rol'            => $rol
        ];

        $usuarioModel->editar($id, $data);

        header("Location: /Vista/Usuarios/listarUsuario.php?msg=actualizado");
        exit;

    case 'eliminar':
        $id = $_GET['id'] ?? '';

        if ($id !== '') {
            $usuarioModel->eliminar($id);
        }

        header("Location: /Vista/Usuarios/listarUsuario.php?msg=eliminado");
        exit;

    default:
        header("Location: /Vista/Usuarios/listarUsuario.php");
        exit;
}
