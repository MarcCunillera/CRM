<?php
// Modelo/Oportunidad.php

require_once __DIR__ . '/DBconnexion.php';

class Oportunidad {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Obtener todas las oportunidades
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM oportunidades ORDER BY fecha_creacion DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener oportunidad por ID
    public function getById($id) {
        $sql = "SELECT * FROM oportunidades WHERE id_oportunidad = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear oportunidad
    public function crear($data, $usuario_responsable) {
        $sql = "INSERT INTO oportunidades
                (id_cliente, usuario_responsable, titulo, descripcion, valor_estimado, estado)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['id_cliente'],
            $usuario_responsable,
            $data['titulo'] ?? '',
            $data['descripcion'] ?? null,
            $data['valor_estimado'] ?? 0,
            $data['estado'] ?? 'progreso'
        ]);
    }

    // Actualizar oportunidad
    public function editar($id, $data) {
        $sql = "UPDATE oportunidades
                SET titulo = ?, descripcion = ?, valor_estimado = ?, estado = ?
                WHERE id_oportunidad = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['titulo'] ?? '',
            $data['descripcion'] ?? null,
            $data['valor_estimado'] ?? 0,
            $data['estado'] ?? 'progreso',
            $id
        ]);
    }

    // Eliminar oportunidad
    public function eliminar($id) {
        $sql = "DELETE FROM oportunidades WHERE id_oportunidad = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
    }
}
