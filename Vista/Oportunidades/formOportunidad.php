<?php
// Vista/Oportunidades/formOportunidad.php
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

$id_oportunidad = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$modo_edicion   = ($id_oportunidad > 0);

$oportunidad = [
    'id_oportunidad' => '',
    'id_cliente'     => '',
    'titulo'         => '',
    'descripcion'    => '',
    'valor_estimado' => 0,
    'estado'         => 'progreso'
];

if ($modo_edicion) {
    $opBD = $oportunidadModel->getById($id_oportunidad);
    if ($opBD) {
        $oportunidad = $opBD;
    } else {
        echo '<div class="alert alert-danger">Oportunidad no encontrada.</div>';
        include __DIR__ . '/../../includes/footer.php';
        exit;
    }
}

// Clientes (todos si admin, o solo los suyos)
$lista_clientes = $clienteModel->getAll();
if (!$es_admin) {
    $lista_clientes = array_filter($lista_clientes, function ($c) use ($id_usuario) {
        return isset($c['usuario_responsable']) && $c['usuario_responsable'] == $id_usuario;
    });
    $lista_clientes = array_values($lista_clientes);
}
?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <h2 class="mb-4 text-center">
            <?php echo $modo_edicion ? 'Editar oportunidad' : 'Nueva oportunidad'; ?>
        </h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Faltan datos obligatorios (cliente y título).
            </div>
        <?php endif; ?>

        <form method="post"
              action="/Controlador/OportunidadController.php?accion=<?php echo $modo_edicion ? 'actualizar' : 'crear'; ?>">

            <?php if ($modo_edicion): ?>
                <input type="hidden" name="id_oportunidad"
                       value="<?php echo $oportunidad['id_oportunidad']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="id_cliente" class="form-label">Cliente *</label>
                <select name="id_cliente" id="id_cliente" class="form-select" required>
                    <option value="">-- Selecciona un cliente --</option>
                    <?php foreach ($lista_clientes as $cli): ?>
                        <option value="<?php echo $cli['id_cliente']; ?>"
                            <?php echo ($oportunidad['id_cliente'] == $cli['id_cliente']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cli['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="titulo" class="form-label">Título *</label>
                <input type="text"
                       name="titulo"
                       id="titulo"
                       class="form-control"
                       required
                       value="<?php echo htmlspecialchars($oportunidad['titulo']); ?>">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion"
                          id="descripcion"
                          class="form-control"
                          rows="4"><?php echo htmlspecialchars($oportunidad['descripcion']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="valor_estimado" class="form-label">Valor estimado (€)</label>
                <input type="number"
                       step="0.01"
                       name="valor_estimado"
                       id="valor_estimado"
                       class="form-control"
                       value="<?php echo htmlspecialchars($oportunidad['valor_estimado']); ?>">
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="progreso" <?php echo $oportunidad['estado'] === 'progreso' ? 'selected' : ''; ?>>
                        En progreso
                    </option>
                    <option value="ganada" <?php echo $oportunidad['estado'] === 'ganada' ? 'selected' : ''; ?>>
                        Ganada
                    </option>
                    <option value="perdida" <?php echo $oportunidad['estado'] === 'perdida' ? 'selected' : ''; ?>>
                        Perdida
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <?php echo $modo_edicion ? 'Guardar cambios' : 'Crear oportunidad'; ?>
            </button>
        </form>

        <hr>

        <a href="/Vista/Oportunidades/listarOportunidad.php" class="btn btn-secondary w-100">Volver al listado</a>
    </div>
</div>

<?php
include __DIR__ . '/../../includes/footer.php';
