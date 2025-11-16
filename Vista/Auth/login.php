<?php
// views/auth/login.php
// Mostra el formulari de login

// Incloem header (pujem 2 carpetes: /views/auth -> /)
include __DIR__ . '/../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <h2 class="mb-4 text-center">Iniciar sesión</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Usuario o contraseña incorrectos.
            </div>
        <?php endif; ?>

        <form method="post" action="/Controlador/AuthController.php?accion=login">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>

        <hr>

        <p class="text-muted small">
            Recuerda: el usuario inicial es por ejemplo:<br>
            <strong>email:</strong> admin@empresa.com<br>
            <strong>password:</strong> admin123
        </p>
    </div>
</div>

<?php
include __DIR__ . '/../../includes/footer.php';
