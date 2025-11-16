<?php
// Vista/Usuarios/listarUsuario.php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /Vista/Auth/login.php");
    exit;
}

require_once __DIR__ . '/../../Modelo/Usuario.php';
include __DIR__ . '/../../includes/header.php';

$usuarioModel = new Usuario();
$usuarios     = $usuarioModel->getAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Usuarios</h2>
    <a href="/Vista/Auth/registra.php" class="btn btn-primary">Nuevo usuario</a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] === 'creado'): ?>
        <div class="alert alert-success">Usuario creado correctamente.</div>
    <?php elseif ($_GET['msg'] === 'actualizado'): ?>
        <div class="alert alert-success">Usuario actualizado correctamente.</div>
    <?php elseif ($_GET['msg'] === 'eliminado'): ?>
        <div class="alert alert-success">Usuario eliminado correctamente.</div>
    <?php endif; ?>
<?php endif; ?>

<?php if (empty($usuarios)): ?>
    <div class="alert alert-info">No se encontraron usuarios.</div>
<?php else: ?>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre de usuario</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Fecha registro</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?php echo $u['id_usuario']; ?></td>
                <td><?php echo htmlspecialchars($u['nombre_usuario']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['rol']); ?></td>
                <td><?php echo $u['fecha_registro']; ?></td>
                <td>
                    <a href="/Vista/Usuarios/formUsuario.php?id=<?php echo $u['id_usuario']; ?>"
                       class="btn btn-sm btn-warning">
                        Editar
                    </a>

                    <?php if ($u['id_usuario'] != $_SESSION['id_usuario']): ?>
                        <a href="/Controlador/UsuarioController.php?accion=eliminar&id=<?php echo $u['id_usuario']; ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                            Eliminar
                        </a>
                    <?php else: ?>
                        <span class="text-muted small">No puedes borrarte a ti mismo</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
include __DIR__ . '/../../includes/footer.php';
