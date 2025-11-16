<?php
// Vista/Clientes/formClientes.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Vista/Auth/login.php");
    exit;
}

require_once __DIR__ . '/../../Modelo/Cliente.php';
include __DIR__ . '/../../includes/header.php';

$id_cliente   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$modo_edicion = ($id_cliente > 0);

$clienteModel = new Cliente();

$cliente = [
    'id_cliente'      => '',
    'nombre_completo' => '',
    'email'           => '',
    'tlf'             => '',
    'empresa'         => ''
];

if ($modo_edicion) {
    $clienteBD = $clienteModel->getById($id_cliente);
    if ($clienteBD) {
        $cliente = $clienteBD;
    } else {
        echo '<div class="alert alert-danger">Cliente no encontrado.</div>';
        include __DIR__ . '/../../includes/footer.php';
        exit;
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4 text-center">
            <?php echo $modo_edicion ? 'Editar cliente' : 'Nuevo cliente'; ?>
        </h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Faltan datos obligatorios (nombre).
            </div>
        <?php endif; ?>

        <form method="post"
              action="/Controlador/ClienteController.php?accion=<?php echo $modo_edicion ? 'actualizar' : 'crear'; ?>">

            <?php if ($modo_edicion): ?>
                <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="nombre_completo" class="form-label">Nombre completo *</label>
                <input type="text"
                       name="nombre_completo"
                       id="nombre_completo"
                       class="form-control"
                       required
                       value="<?php echo htmlspecialchars($cliente['nombre_completo']); ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email"
                       name="email"
                       id="email"
                       class="form-control"
                       value="<?php echo htmlspecialchars($cliente['email']); ?>">
            </div>

            <div class="mb-3">
                <label for="tlf" class="form-label">Teléfono</label>
                <input type="text"
                       name="tlf"
                       id="tlf"
                       class="form-control"
                       value="<?php echo htmlspecialchars($cliente['tlf']); ?>">
            </div>

            <div class="mb-3">
                <label for="empresa" class="form-label">Empresa</label>
                <input type="text"
                       name="empresa"
                       id="empresa"
                       class="form-control"
                       value="<?php echo htmlspecialchars($cliente['empresa']); ?>">
            </div>

            <button type="submit" class="btn btn-success w-100">
                <?php echo $modo_edicion ? 'Guardar cambios' : 'Crear cliente'; ?>
            </button>
        </form>

        <hr>

        <a href="/Vista/Clientes/listarClientes.php" class="btn btn-secondary w-100">Volver al listado</a>
    </div>
</div>

<?php
include __DIR__ . '/../../includes/footer.php';
