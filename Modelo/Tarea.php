<?php
// Modelo/Tarea.php

require_once __DIR__ . '/DBconnexion.php';

class Tarea {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Obtener todas las tareas
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM tareas ORDER BY fecha ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener tarea por ID
    public function getById($id) {
        $sql = "SELECT * FROM tareas WHERE id_tarea = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear tarea
    public function crear($data) {
        $sql = "INSERT INTO tareas (id_oportunidad, descripcion, fecha, estado)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['id_oportunidad'],
            $data['descripcion'] ?? '',
            $data['fecha'] ?? null,
            $data['estado'] ?? 'pendiente'
        ]);
    }

    // Marcar tarea como completada
    public function marcarCompleta($id) {
        $sql = "UPDATE tareas SET estado = 'completada' WHERE id_tarea = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    // Eliminar tarea
    public function eliminar($id) {
        $sql = "DELETE FROM tareas WHERE id_tarea = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
    }
}
