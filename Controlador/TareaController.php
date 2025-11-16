<?php
// Controlador/TareaController.php
session_start();

require_once __DIR__ . '/../Modelo/Tarea.php';
require_once __DIR__ . '/../Modelo/Oportunidad.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Vista/Auth/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

$tareaModel = new Tarea();

switch ($accion) {

    case 'crear':
        $id_oportunidad = $_POST['id_oportunidad'] ?? '';
        $descripcion    = $_POST['descripcion'] ?? '';
        $fecha          = $_POST['fecha'] ?? '';

        if ($id_oportunidad === '' || $descripcion === '') {
            header("Location: /Vista/Tareas/formTarea.php?error=1");
            exit;
        }

        $data = [
            'id_oportunidad' => $id_oportunidad,
            'descripcion'    => $descripcion,
            'fecha'          => $fecha,
            'estado'         => 'pendiente'
        ];

        $tareaModel->crear($data);

        header("Location: /Vista/Tareas/listarTarea.php?msg=creada&id_oportunidad=" . $id_oportunidad);
        exit;

    case 'completar':
        $id_tarea       = $_GET['id'] ?? '';
        $id_oportunidad = $_GET['id_oportunidad'] ?? '';

        if ($id_tarea !== '') {
            $tareaModel->marcarCompleta($id_tarea);
        }

        if ($id_oportunidad !== '') {
            header("Location: /Vista/Tareas/listarTarea.php?msg=completada&id_oportunidad=" . $id_oportunidad);
        } else {
            header("Location: /Vista/Tareas/listarTarea.php?msg=completada");
        }
        exit;

    case 'eliminar':
        $id_tarea       = $_GET['id'] ?? '';
        $id_oportunidad = $_GET['id_oportunidad'] ?? '';

        if ($id_tarea !== '') {
            $tareaModel->eliminar($id_tarea);
        }

        if ($id_oportunidad !== '') {
            header("Location: /Vista/Tareas/listarTarea.php?msg=eliminada&id_oportunidad=" . $id_oportunidad);
        } else {
            header("Location: /Vista/Tareas/listarTarea.php?msg=eliminada");
        }
        exit;

    default:
        header("Location: /Vista/Tareas/listarTarea.php");
        exit;
}
