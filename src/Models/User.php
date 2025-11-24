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
     * Soporta OAuth (provider, provider_id, estado, avatar, email_verified)
     */
    public function create($data) {
        $role = $data['role'] ?? 'cliente';
        $provider = $data['provider'] ?? 'local';
        $estado = $data['estado'] ?? 'activo';
        
        $stmt = $this->db->prepare("
            INSERT INTO users (
                name, email, password, role, provider, provider_id, 
                estado, avatar, email_verified, created_at
            ) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password'] ?? null,
            $role,
            $provider,
            $data['provider_id'] ?? null,
            $estado,
            $data['avatar'] ?? null,
            $data['email_verified'] ?? 0
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualizar usuario existente
     */
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        $allowedFields = [
            'name', 'email', 'password', 'role', 'provider', 'provider_id',
            'estado', 'avatar', 'email_verified', 'last_login', 'company_id'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $values[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    /**
     * Buscar usuarios por rol
     */
    public function findByRole($role) {
        $stmt = $this->db->prepare("
            SELECT id, name, email, role, created_at, updated_at 
            FROM users 
            WHERE role = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$role]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Eliminar usuario por ID
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Actualizar último login
     */
    public function updateLastLogin($id) {
        $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
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