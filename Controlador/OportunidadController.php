<?php
// Controlador/OportunidadController.php
session_start();

require_once __DIR__ . '/../Modelo/Oportunidad.php';
require_once __DIR__ . '/../Modelo/Cliente.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Vista/Auth/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$es_admin   = ($_SESSION['rol'] === 'admin');

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

$oportunidadModel = new Oportunidad();

switch ($accion) {

    case 'crear':
        $id_cliente  = $_POST['id_cliente'] ?? '';
        $titulo      = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $valor       = $_POST['valor_estimado'] ?? 0;
        $estado      = $_POST['estado'] ?? 'progreso';

        if ($id_cliente === '' || $titulo === '') {
            header("Location: /Vista/Oportunidades/formOportunidad.php?error=1");
            exit;
        }

        $data = [
            'id_cliente'     => $id_cliente,
            'titulo'         => $titulo,
            'descripcion'    => $descripcion,
            'valor_estimado' => $valor,
            'estado'         => $estado
        ];

        $oportunidadModel->crear($data, $id_usuario);

        header("Location: /Vista/Oportunidades/listarOportunidad.php?msg=creada");
        exit;

    case 'actualizar':
        $id_oportunidad = $_POST['id_oportunidad'] ?? '';
        $titulo         = $_POST['titulo'] ?? '';
        $descripcion    = $_POST['descripcion'] ?? '';
        $valor          = $_POST['valor_estimado'] ?? 0;
        $estado         = $_POST['estado'] ?? 'progreso';

        if ($id_oportunidad === '' || $titulo === '') {
            header("Location: /Vista/Oportunidades/formOportunidad.php?error=1&id=" . $id_oportunidad);
            exit;
        }

        $data = [
            'titulo'         => $titulo,
            'descripcion'    => $descripcion,
            'valor_estimado' => $valor,
            'estado'         => $estado
        ];

        $oportunidadModel->editar($id_oportunidad, $data);

        header("Location: /Vista/Oportunidades/listarOportunidad.php?msg=actualizada");
        exit;

    case 'eliminar':
        $id_oportunidad = $_GET['id'] ?? '';

        if ($id_oportunidad !== '') {
            $oportunidadModel->eliminar($id_oportunidad);
        }

        header("Location: /Vista/Oportunidades/listarOportunidad.php?msg=eliminada");
        exit;

    default:
        header("Location: /Vista/Oportunidades/listarOportunidad.php");
        exit;
}
