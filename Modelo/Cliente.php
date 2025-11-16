<?php
// Modelo/Cliente.php

require_once __DIR__ . '/DBconnexion.php';

class Cliente {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Obtener todos los clientes
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM clientes ORDER BY fecha_registro DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener cliente por ID
    public function getById($id) {
        $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear cliente
    public function crear($data, $usuario_responsable = 1) {
        $sql = "INSERT INTO clientes (nombre_completo, email, tlf, empresa, usuario_responsable)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nombre_completo'] ?? '',
            $data['email'] ?? null,
            $data['tlf'] ?? null,
            $data['empresa'] ?? null,
            $usuario_responsable
        ]);
    }

    // Actualizar cliente
    public function editar($id, $data) {
        $sql = "UPDATE clientes
                SET nombre_completo = ?, email = ?, tlf = ?, empresa = ?
                WHERE id_cliente = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nombre_completo'] ?? '',
            $data['email'] ?? null,
            $data['tlf'] ?? null,
            $data['empresa'] ?? null,
            $id
        ]);
    }

    // Eliminar cliente
    public function eliminar($id) {
        $sql = "DELETE FROM clientes WHERE id_cliente = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
    }
}
