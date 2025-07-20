<?php
require_once 'config/database.php';

class Campaign {
    private $conn;
    private $table_name = "campaigns";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getActiveCampaigns() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE status = 'active' ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCampaignById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCampaign($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (title, description, image, price, total_numbers, min_purchase, max_purchase, draw_date) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['image'],
            $data['price'],
            $data['total_numbers'],
            $data['min_purchase'],
            $data['max_purchase'],
            $data['draw_date']
        ]);
    }

    public function updateCampaign($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = ?, description = ?, image = ?, price = ?, 
                      total_numbers = ?, min_purchase = ?, max_purchase = ?, 
                      draw_date = ?, status = ? 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['image'],
            $data['price'],
            $data['total_numbers'],
            $data['min_purchase'],
            $data['max_purchase'],
            $data['draw_date'],
            $data['status'],
            $id
        ]);
    }

    public function deleteCampaign($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getDiscounts($campaign_id) {
        $query = "SELECT * FROM discounts WHERE campaign_id = ? ORDER BY quantity ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $campaign_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addDiscount($campaign_id, $quantity, $discount_amount) {
        $query = "INSERT INTO discounts (campaign_id, quantity, discount_amount) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$campaign_id, $quantity, $discount_amount]);
    }

    public function getRanking($campaign_id, $limit = 10) {
        $query = "SELECT r.*, u.firstname, u.lastname 
                  FROM ranking r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.campaign_id = ? 
                  ORDER BY r.total_numbers DESC 
                  LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $campaign_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>