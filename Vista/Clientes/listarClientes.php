<?php
// Vista/Clientes/listarClientes.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Vista/Auth/login.php");
    exit;
}

require_once __DIR__ . '/../../Modelo/Cliente.php';
include __DIR__ . '/../../includes/header.php';

$id_usuario = $_SESSION['id_usuario'];
$es_admin   = ($_SESSION['rol'] === 'admin');

$clienteModel = new Cliente();

// Buscar
$termino_busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

// 1) Obtener todos los clientes
$clientes = $clienteModel->getAll();

// 2) Si no es admin, solo sus clientes
if (!$es_admin) {
    $clientes = array_filter($clientes, function ($c) use ($id_usuario) {
        return isset($c['usuario_responsable']) && $c['usuario_responsable'] == $id_usuario;
    });
}

// 3) Filtro por búsqueda (nombre o empresa)
if ($termino_busqueda !== '') {
    $busca = mb_strtolower($termino_busqueda);
    $clientes = array_filter($clientes, function ($c) use ($busca) {
        $nombre  = mb_strtolower($c['nombre_completo'] ?? '');
        $empresa = mb_strtolower($c['empresa'] ?? '');
        return str_contains($nombre, $busca) || str_contains($empresa, $busca);
    });
}

// Normalizar índices
$clientes = array_values($clientes);
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Clientes</h2>
    <a href="/Vista/Clientes/formClientes.php" class="btn btn-primary">Nuevo cliente</a>
</div>

<!-- Mensajes -->
<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] === 'creado'): ?>
        <div class="alert alert-success">Cliente creado correctamente.</div>
    <?php elseif ($_GET['msg'] === 'actualizado'): ?>
        <div class="alert alert-success">Cliente actualizado correctamente.</div>
    <?php elseif ($_GET['msg'] === 'eliminado'): ?>
        <div class="alert alert-success">Cliente eliminado correctamente.</div>
    <?php endif; ?>
<?php endif; ?>

<!-- Formulario de búsqueda -->
<form class="row mb-3" method="get" action="listarClientes.php">
    <div class="col-md-4">
        <input type="text" name="buscar" class="form-control"
               placeholder="Buscar por nombre o empresa"
               value="<?php echo htmlspecialchars($termino_busqueda); ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-secondary">Buscar</button>
    </div>
</form>

<?php if (empty($clientes)): ?>
    <div class="alert alert-info">No se encontraron clientes.</div>
<?php else: ?>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre completo</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Empresa</th>
            <th>Fecha registro</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?php echo $cliente['id_cliente']; ?></td>
                <td><?php echo htmlspecialchars($cliente['nombre_completo']); ?></td>
                <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                <td><?php echo htmlspecialchars($cliente['tlf']); ?></td>
                <td><?php echo htmlspecialchars($cliente['empresa']); ?></td>
                <td><?php echo $cliente['fecha_registro']; ?></td>
                <td>
                    <a href="/Vista/Clientes/formClientes.php?id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-sm btn-warning">
                        Editar
                    </a>
                    <a href="/Controlador/ClienteController.php?accion=eliminar&id=<?php echo $cliente['id_cliente']; ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Seguro que deseas eliminar este cliente?');">
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
