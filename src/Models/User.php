<?php

namespace Factapex\Models;

use PDO;
use PDOException;
use Factapex\Core\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Buscar usuario por email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Buscar usuario por ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Crear nuevo usuario
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password']
        ]);
    }

    /**
     * Actualizar último login
     */
    public function updateLastLogin($id) {
        $stmt = $this->db->prepare("UPDATE users SET updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function authenticate($email, $password) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $this->db->commit();
                return $user;
            }

            $this->db->commit();
            return false;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new PDOException($e->getMessage());
        }
    }
}