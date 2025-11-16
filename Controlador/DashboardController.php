<?php
// controladores/DashboardController.php
session_start();
require_once __DIR__ . '/../Configuracion/configuracionBD.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /Vista/auth/login.php");
    exit;
}

// Tot clients
$sqlClientes = "SELECT COUNT(*) AS total_clientes FROM clientes";
$resClientes = mysqli_query($conn, $sqlClientes);
$rowClientes = mysqli_fetch_assoc($resClientes);
$total_clientes = $rowClientes['total_clientes'] ?? 0;

// Oportunitats per estat
$sqlOport = "SELECT estado, COUNT(*) AS total FROM oportunidades GROUP BY estado";
$resOport = mysqli_query($conn, $sqlOport);
$oportunidades_por_estado = [];
if ($resOport) {
    while ($fila = mysqli_fetch_assoc($resOport)) {
        $oportunidades_por_estado[$fila['estado']] = $fila['total'];
    }
}

// Tasques pendents
$sqlTareas = "SELECT COUNT(*) AS total_pendientes FROM tareas WHERE estado = 'pendiente'";
$resTareas = mysqli_query($conn, $sqlTareas);
$rowTareas = mysqli_fetch_assoc($resTareas);
$total_tareas_pendientes = $rowTareas['total_pendientes'] ?? 0;

// Aquí simplement incloem una vista que mostrarà aquestes variables:
include __DIR__ . '/../Vista/dashboard/index.php';
