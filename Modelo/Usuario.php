<?php
// Modelo/Usuario.php

require_once __DIR__ . '/DBconnexion.php';

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Login simple: email + password en texto plano
    public function getByEmailYPassword($email, $password) {
        $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && $usuario['password_hash'] === $password) {
            return $usuario;
        }
        return null;
    }

    // Obtener todos los usuarios
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios ORDER BY fecha_registro DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener usuario por ID
    public function getById($id) {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear usuario
    public function crear($data) {
        $sql = "INSERT INTO usuarios (nombre_usuario, email, password_hash, rol)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nombre_usuario'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['rol'] ?? 'vendedor'
        ]);
    }

    // Actualizar usuario (sin cambiar contraseña)
    public function editar($id, $data) {
        $sql = "UPDATE usuarios
                SET nombre_usuario = ?, email = ?, rol = ?
                WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nombre_usuario'] ?? '',
            $data['email'] ?? '',
            $data['rol'] ?? 'vendedor',
            $id
        ]);
    }

    // Eliminar usuario
    public function eliminar($id) {
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
    }
}
