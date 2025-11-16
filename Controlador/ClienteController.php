<?php
// Controlador/ClienteController.php
session_start();

require_once __DIR__ . '/../Modelo/Cliente.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Vista/Auth/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$es_admin   = ($_SESSION['rol'] === 'admin');

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

$clienteModel = new Cliente();

switch ($accion) {

    case 'crear':
        $nombre  = $_POST['nombre_completo'] ?? '';
        $email   = $_POST['email'] ?? '';
        $tlf     = $_POST['tlf'] ?? '';
        $empresa = $_POST['empresa'] ?? '';

        if ($nombre === '') {
            header("Location: /Vista/Clientes/formClientes.php?error=1");
            exit;
        }

        $data = [
            'nombre_completo' => $nombre,
            'email'           => $email,
            'tlf'             => $tlf,
            'empresa'         => $empresa
        ];

        // El responsable es el usuario logueado
        $clienteModel->crear($data, $id_usuario);

        header("Location: /Vista/Clientes/listarClientes.php?msg=creado");
        exit;

    case 'actualizar':
        $id      = $_POST['id_cliente'] ?? '';
        $nombre  = $_POST['nombre_completo'] ?? '';
        $email   = $_POST['email'] ?? '';
        $tlf     = $_POST['tlf'] ?? '';
        $empresa = $_POST['empresa'] ?? '';

        if ($id === '' || $nombre === '') {
            header("Location: /Vista/Clientes/formClientes.php?error=1&id=" . $id);
            exit;
        }

        $data = [
            'nombre_completo' => $nombre,
            'email'           => $email,
            'tlf'             => $tlf,
            'empresa'         => $empresa
        ];

        $clienteModel->editar($id, $data);

        header("Location: /Vista/Clientes/listarClientes.php?msg=actualizado");
        exit;

    case 'eliminar':
        $id = $_GET['id'] ?? '';

        if ($id !== '') {
            $clienteModel->eliminar($id);
        }

        header("Location: /Vista/Clientes/listarClientes.php?msg=eliminado");
        exit;

    default:
        header("Location: /Vista/Clientes/listarClientes.php");
        exit;
}
