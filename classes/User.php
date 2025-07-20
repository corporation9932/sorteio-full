<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createUser($data) {
        // Verificar se o telefone já existe
        if ($this->getUserByPhone($data['phone'])) {
            return ['status' => 'phone_already', 'msg' => 'Telefone já cadastrado'];
        }

        $query = "INSERT INTO " . $this->table_name . " (firstname, lastname, phone, email, cpf) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute([
            $data['firstname'],
            $data['lastname'],
            $data['phone'],
            $data['email'] ?? null,
            $data['cpf'] ?? null
        ])) {
            return ['status' => 'success', 'user_id' => $this->conn->lastInsertId()];
        }
        
        return ['status' => 'error', 'msg' => 'Erro ao criar usuário'];
    }

    public function getUserByPhone($phone) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE phone = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $phone);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserOrders($user_id) {
        $query = "SELECT o.*, c.title as campaign_title 
                  FROM orders o 
                  JOIN campaigns c ON o.campaign_id = c.id 
                  WHERE o.user_id = ? 
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>