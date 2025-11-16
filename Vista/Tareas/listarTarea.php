<?php
// Vista/Tareas/listarTarea.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Vista/Auth/login.php");
    exit;
}

require_once __DIR__ . '/../../Modelo/Tarea.php';
require_once __DIR__ . '/../../Modelo/Oportunidad.php';
include __DIR__ . '/../../includes/header.php';

$id_usuario = $_SESSION['id_usuario'];

$id_oportunidad = isset($_GET['id_oportunidad']) ? (int)$_GET['id_oportunidad'] : 0;
$mis_pendientes = isset($_GET['mis_pendientes']) ? (int)$_GET['mis_pendientes'] : 0;

$tareaModel       = new Tarea();
$oportunidadModel = new Oportunidad();

$oportunidad = null;
if ($id_oportunidad > 0) {
    $oportunidad = $oportunidadModel->getById($id_oportunidad);
}

$tareas = [];
$titulo_pagina = '';

if ($id_oportunidad > 0) {
    $todasTareas = $tareaModel->getAll();
    $tareas = array_filter($todasTareas, function ($t) use ($id_oportunidad) {
        return isset($t['id_oportunidad']) && (int)$t['id_oportunidad'] === $id_oportunidad;
    });
    $tareas = array_values($tareas);

    $titulo_pagina = "Tareas de la oportunidad #" . $id_oportunidad;
} else {
    $todasTareas        = $tareaModel->getAll();
    $todasOportunidades = $oportunidadModel->getAll();

    $ids_oportunitats_usuario = [];
    foreach ($todasOportunidades as $op) {
        if (isset($op['usuario_responsable']) && (int)$op['usuario_responsable'] === $id_usuario) {
            $ids_oportunitats_usuario[] = (int)$op['id_oportunidad'];
        }
    }

    $tareas = array_filter($todasTareas, function ($t) use ($ids_oportunitats_usuario) {
        return in_array((int)$t['id_oportunidad'], $ids_oportunitats_usuario, true);
    });

    $solo_pendientes = true;
    if ($mis_pendientes == 0) {
        $solo_pendientes = true;
    }

    if ($solo_pendientes) {
        $tareas = array_filter($tareas, function ($t) {
            return isset($t['estado']) && $t['estado'] === 'pendiente';
        });
    }

    $tareas = array_values($tareas);

    $titulo_pagina = "Mis tareas pendientes";
}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2><?php echo $titulo_pagina; ?></h2>
        <?php if ($oportunidad): ?>
            <p class="text-muted">
                Oportunidad: <strong><?php echo htmlspecialchars($oportunidad['titulo']); ?></strong>
            </p>
        <?php endif; ?>
    </div>

    <div class="text-end">
        <?php if ($id_oportunidad > 0): ?>
            <a href="/Vista/Tareas/formTarea.php?id_oportunidad=<?php echo $id_oportunidad; ?>" class="btn btn-primary mb-2">
                Nueva tarea
            </a>
        <?php else: ?>
            <a href="/Vista/Tareas/formTarea.php" class="btn btn-primary mb-2">
                Nueva tarea
            </a>
        <?php endif; ?>

        <br>

        <a href="/Vista/Tareas/listarTarea.php?mis_pendientes=1" class="btn btn-outline-secondary btn-sm">
            Ver mis tareas pendientes
        </a>
    </div>
</div>

<!-- Mensajes -->
<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] === 'creada'): ?>
        <div class="alert alert-success">Tarea creada correctamente.</div>
    <?php elseif ($_GET['msg'] === 'completada'): ?>
        <div class="alert alert-success">Tarea marcada como completada.</div>
    <?php elseif ($_GET['msg'] === 'eliminada'): ?>
        <div class="alert alert-success">Tarea eliminada correctamente.</div>
    <?php endif; ?>
<?php endif; ?>

<?php if (empty($tareas)): ?>
    <div class="alert alert-info">No se encontraron tareas.</div>
<?php else: ?>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <?php if ($id_oportunidad == 0): ?>
                <th>ID Oportunidad</th>
            <?php endif; ?>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tareas as $t): ?>
            <tr>
                <td><?php echo $t['id_tarea']; ?></td>

                <?php if ($id_oportunidad == 0): ?>
                    <td><?php echo $t['id_oportunidad']; ?></td>
                <?php endif; ?>

                <td><?php echo htmlspecialchars($t['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($t['fecha']); ?></td>
                <td><?php echo htmlspecialchars($t['estado']); ?></td>
                <td>
                    <?php if ($t['estado'] === 'pendiente'): ?>
                        <a href="/Controlador/TareaController.php?accion=completar&id=<?php echo $t['id_tarea']; ?>&id_oportunidad=<?php echo $id_oportunidad; ?>"
                           class="btn btn-sm btn-success">
                            Completar
                        </a>
                    <?php endif; ?>

                    <a href="/Controlador/TareaController.php?accion=eliminar&id=<?php echo $t['id_tarea']; ?>&id_oportunidad=<?php echo $id_oportunidad; ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Seguro que deseas eliminar esta tarea?');">
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
include __DIR__ . '/../../includes/footer.php';
