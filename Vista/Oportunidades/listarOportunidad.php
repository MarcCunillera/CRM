<?php
// Vista/Oportunidades/listarOportunidad.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Vista/Auth/login.php");
    exit;
}

require_once __DIR__ . '/../../Modelo/Oportunidad.php';
require_once __DIR__ . '/../../Modelo/Cliente.php';
include __DIR__ . '/../../includes/header.php';

$id_usuario = $_SESSION['id_usuario'];
$es_admin   = ($_SESSION['rol'] === 'admin');

$oportunidadModel = new Oportunidad();
$clienteModel     = new Cliente();

$estado_filtro     = isset($_GET['estado']) ? $_GET['estado'] : '';
$id_cliente_filtro = isset($_GET['id_cliente']) ? (int)$_GET['id_cliente'] : 0;

// Lista de clientes
$lista_clientes = $clienteModel->getAll();
if (!$es_admin) {
    $lista_clientes = array_filter($lista_clientes, function ($c) use ($id_usuario) {
        return isset($c['usuario_responsable']) && $c['usuario_responsable'] == $id_usuario;
    });
    $lista_clientes = array_values($lista_clientes);
}

// Oportunidades
$oportunidades = $oportunidadModel->getAll();

if (!$es_admin) {
    $oportunidades = array_filter($oportunidades, function ($op) use ($id_usuario) {
        return isset($op['usuario_responsable']) && $op['usuario_responsable'] == $id_usuario;
    });
}

if ($estado_filtro !== '') {
    $oportunidades = array_filter($oportunidades, function ($op) use ($estado_filtro) {
        return isset($op['estado']) && $op['estado'] === $estado_filtro;
    });
}

if ($id_cliente_filtro > 0) {
    $oportunidades = array_filter($oportunidades, function ($op) use ($id_cliente_filtro) {
        return isset($op['id_cliente']) && (int)$op['id_cliente'] === $id_cliente_filtro;
    });
}

$oportunidades = array_values($oportunidades);

// Map id_cliente -> nombre
$clientes_por_id = [];
foreach ($lista_clientes as $cli) {
    $clientes_por_id[$cli['id_cliente']] = $cli['nombre_completo'];
}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Oportunidades</h2>
    <a href="/Vista/Oportunidades/formOportunidad.php" class="btn btn-primary">Nueva oportunidad</a>
</div>

<!-- Mensajes -->
<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] === 'creada'): ?>
        <div class="alert alert-success">Oportunidad creado correctamente.</div>
    <?php elseif ($_GET['msg'] === 'actualizada'): ?>
        <div class="alert alert-success">Oportunidad actualizada correctamente.</div>
    <?php elseif ($_GET['msg'] === 'eliminada'): ?>
        <div class="alert alert-success">Oportunidad eliminada correctamente.</div>
    <?php endif; ?>
<?php endif; ?>

<!-- Filtros -->
<form class="row mb-3" method="get" action="listarOportunidad.php">
    <div class="col-md-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
            <option value="">-- Todos --</option>
            <option value="progreso" <?php echo $estado_filtro === 'progreso' ? 'selected' : ''; ?>>En progreso</option>
            <option value="ganada"   <?php echo $estado_filtro === 'ganada' ? 'selected' : ''; ?>>Ganada</option>
            <option value="perdida"  <?php echo $estado_filtro === 'perdida' ? 'selected' : ''; ?>>Perdida</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Cliente</label>
        <select name="id_cliente" class="form-select">
            <option value="0">-- Todos --</option>
            <?php foreach ($lista_clientes as $cli): ?>
                <option value="<?php echo $cli['id_cliente']; ?>"
                    <?php echo ($id_cliente_filtro == $cli['id_cliente']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cli['nombre_completo']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-secondary w-100">Filtrar</button>
    </div>
</form>

<?php if (empty($oportunidades)): ?>
    <div class="alert alert-info">No se encontraron oportunidades.</div>
<?php else: ?>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Título</th>
            <th>Valor estimado (€)</th>
            <th>Estado</th>
            <th>Fecha creación</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($oportunidades as $op): ?>
            <tr>
                <td><?php echo $op['id_oportunidad']; ?></td>
                <td>
                    <?php
                    $id_cli = $op['id_cliente'];
                    echo isset($clientes_por_id[$id_cli])
                        ? htmlspecialchars($clientes_por_id[$id_cli])
                        : 'Cliente #' . $id_cli;
                    ?>
                </td>
                <td><?php echo htmlspecialchars($op['titulo']); ?></td>
                <td><?php echo number_format($op['valor_estimado'], 2); ?></td>
                <td><?php echo htmlspecialchars($op['estado']); ?></td>
                <td><?php echo $op['fecha_creacion']; ?></td>
                <td>
                    <a href="/Vista/Oportunidades/formOportunidad.php?id=<?php echo $op['id_oportunidad']; ?>"
                       class="btn btn-sm btn-warning">Editar</a>

                    <a href="/Controlador/OportunidadController.php?accion=eliminar&id=<?php echo $op['id_oportunidad']; ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Seguro que deseas eliminar esta oportunidad?');">
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
