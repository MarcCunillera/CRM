<?php
// Vista/Usuarios/formUsuario.php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /Vista/Auth/login.php");
    exit;
}

require_once __DIR__ . '/../../Modelo/Usuario.php';
include __DIR__ . '/../../includes/header.php';

$id_usuario = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_usuario <= 0) {
    echo '<div class="alert alert-danger">ID de usuario no válido.</div>';
    include __DIR__ . '/../../includes/footer.php';
    exit;
}

$usuarioModel = new Usuario();
$usuario      = $usuarioModel->getById($id_usuario);

if (!$usuario) {
    echo '<div class="alert alert-danger">Usuario no encontrado.</div>';
    include __DIR__ . '/../../includes/footer.php';
    exit;
}
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4 text-center">Editar usuario</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Faltan datos obligatorios.
            </div>
        <?php endif; ?>

        <form method="post" action="/Controlador/UsuarioController.php?accion=actualizar">
            <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">

            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de usuario</label>
                <input type="text"
                       name="nombre_usuario"
                       id="nombre_usuario"
                       class="form-control"
                       required
                       value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email"
                       name="email"
                       id="email"
                       class="form-control"
                       required
                       value="<?php echo htmlspecialchars($usuario['email']); ?>">
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select name="rol" id="rol" class="form-select">
                    <option value="vendedor" <?php echo $usuario['rol'] === 'vendedor' ? 'selected' : ''; ?>>
                        Vendedor
                    </option>
                    <option value="admin" <?php echo $usuario['rol'] === 'admin' ? 'selected' : ''; ?>>
                        Administrador
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">Guardar cambios</button>
        </form>

        <hr>

        <a href="/Vista/Usuarios/listarUsuario.php" class="btn btn-secondary w-100">Volver al listado</a>
    </div>
</div>

<?php
include __DIR__ . '/../../includes/footer.php';
