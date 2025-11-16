<?php
// Vista/Tareas/formTarea.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Vista/Auth/login.php");
    exit;
}

require_once __DIR__ . '/../../Modelo/Tarea.php';
require_once __DIR__ . '/../../Modelo/Oportunidad.php';
include __DIR__ . '/../../includes/header.php';

$id_usuario = $_SESSION['id_usuario'];
$es_admin   = ($_SESSION['rol'] === 'admin');

$id_oportunidad = isset($_GET['id_oportunidad']) ? (int)$_GET['id_oportunidad'] : 0;

$oportunidadModel = new Oportunidad();

$oportunidades = $oportunidadModel->getAll();
if (!$es_admin) {
    $oportunidades = array_filter($oportunidades, function ($op) use ($id_usuario) {
        return isset($op['usuario_responsable']) && $op['usuario_responsable'] == $id_usuario;
    });
    $oportunidades = array_values($oportunidades);
}
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4 text-center">Nueva tarea</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Faltan datos obligatorios (oportunidad y descripción).
            </div>
        <?php endif; ?>

        <form method="post" action="/Controlador/TareaController.php?accion=crear">

            <div class="mb-3">
                <label for="id_oportunidad" class="form-label">Oportunidad *</label>
                <select name="id_oportunidad" id="id_oportunidad" class="form-select" required>
                    <option value="">-- Selecciona una oportunidad --</option>
                    <?php foreach ($oportunidades as $op): ?>
                        <option value="<?php echo $op['id_oportunidad']; ?>"
                            <?php echo ($id_oportunidad == $op['id_oportunidad']) ? 'selected' : ''; ?>>
                            #<?php echo $op['id_oportunidad']; ?> - 
                            <?php echo htmlspecialchars($op['titulo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción *</label>
                <textarea name="descripcion"
                          id="descripcion"
                          class="form-control"
                          required
                          rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha (opcional)</label>
                <input type="date" name="fecha" id="fecha" class="form-control">
            </div>

            <button type="submit" class="btn btn-success w-100">Crear tarea</button>
        </form>

        <hr>

        <?php if ($id_oportunidad > 0): ?>
            <a href="/Vista/Tareas/listarTarea.php?id_oportunidad=<?php echo $id_oportunidad; ?>" class="btn btn-secondary w-100">
                Volver al listado de tareas de la oportunidad
            </a>
        <?php else: ?>
            <a href="/Vista/Tareas/listarTarea.php" class="btn btn-secondary w-100">
                Volver al listado de tareas
            </a>
        <?php endif; ?>
    </div>
</div>

<?php
include __DIR__ . '/../../includes/footer.php';
