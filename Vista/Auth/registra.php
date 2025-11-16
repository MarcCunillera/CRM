<?php
// views/auth/register.php

session_start();

// Només hauria de poder accedir l'admin
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /Vista/Auth/login.php");
    exit;
}

include __DIR__ . '/../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4 text-center">Registrar nuevo usuario</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Faltan datos obligatorios.
            </div>
        <?php endif; ?>

        <form method="post" action="/Controlador/UsuarioController.php?accion=crear">
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de usuario</label>
                <input type="text" name="nombre_usuario" id="nombre_usuario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select name="rol" id="rol" class="form-select">
                    <option value="vendedor">Vendedor</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">Crear usuario</button>
        </form>

        <hr>

        <a href="/Vista/Usuarios/listarUsuario.php" class="btn btn-secondary w-100">Volver al listado de usuarios</a>
    </div>
</div>

<?php
include __DIR__ . '/../../includes/footer.php';
