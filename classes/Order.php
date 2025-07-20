<?php
require_once 'config/database.php';
require_once 'config/nitro_api.php';

class Order {
    private $conn;
    private $table_name = "orders";
    private $nitro_api;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->nitro_api = new NitroAPI();
    }

    public function createOrder($user_id, $campaign_id, $quantity, $total_amount) {
        $order_token = $this->generateOrderToken();
        $expires_at = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, campaign_id, quantity, total_amount, order_token, expires_at) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute([$user_id, $campaign_id, $quantity, $total_amount, $order_token, $expires_at])) {
            $order_id = $this->conn->lastInsertId();
            
            // Criar pagamento PIX
            $user = $this->getUserById($user_id);
            $campaign = $this->getCampaignById($campaign_id);
            
            $customer_data = [
                'name' => $user['firstname'] . ' ' . $user['lastname'],
                'phone' => $user['phone'],
                'email' => $user['email'] ?? 'noemail@example.com'
            ];
            
            $payment_response = $this->nitro_api->createPixPayment(
                $total_amount,
                $campaign['title'] . ' - ' . $quantity . ' cotas',
                $customer_data
            );
            
            if ($payment_response['status_code'] == 200) {
                $payment_data = $payment_response['data'];
                
                // Atualizar pedido com dados do PIX
                $update_query = "UPDATE " . $this->table_name . " 
                                SET payment_id = ?, pix_code = ?, qr_code = ? 
                                WHERE id = ?";
                $update_stmt = $this->conn->prepare($update_query);
                $update_stmt->execute([
                    $payment_data['id'],
                    $payment_data['pix_code'],
                    $payment_data['qr_code'],
                    $order_id
                ]);
            }
            
            return ['status' => 'success', 'order_id' => $order_id, 'order_token' => $order_token];
        }
        
        return ['status' => 'error'];
    }

    public function getOrderByToken($token) {
        $query = "SELECT o.*, u.firstname, u.lastname, u.phone, c.title as campaign_title 
                  FROM " . $this->table_name . " o
                  JOIN users u ON o.user_id = u.id
                  JOIN campaigns c ON o.campaign_id = c.id
                  WHERE o.order_token = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkPaymentStatus($order_token) {
        $order = $this->getOrderByToken($order_token);
        
        if (!$order || !$order['payment_id']) {
            return ['status' => 'error'];
        }
        
        $payment_response = $this->nitro_api->checkPaymentStatus($order['payment_id']);
        
        if ($payment_response['status_code'] == 200) {
            $payment_data = $payment_response['data'];
            
            if ($payment_data['status'] == 'paid') {
                // Atualizar status do pedido
                $this->updateOrderStatus($order['id'], 'paid');
                
                // Gerar números da rifa
                $this->generateRaffleNumbers($order['id'], $order['campaign_id'], $order['quantity']);
                
                // Atualizar ranking
                $this->updateRanking($order['user_id'], $order['campaign_id'], $order['quantity']);
                
                return ['status' => '2']; // Pago
            }
        }
        
        return ['status' => '1']; // Pendente
    }

    private function generateOrderToken() {
        return md5(uniqid(rand(), true));
    }

    private function updateOrderStatus($order_id, $status) {
        $query = "UPDATE " . $this->table_name . " SET payment_status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $order_id]);
    }

    private function generateRaffleNumbers($order_id, $campaign_id, $quantity) {
        // Buscar números disponíveis
        $query = "SELECT number FROM raffle_numbers WHERE campaign_id = ? AND status = 'paid'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$campaign_id]);
        $used_numbers = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Gerar números aleatórios únicos
        $campaign = $this->getCampaignById($campaign_id);
        $max_number = $campaign['total_numbers'];
        $available_numbers = array_diff(range(1, $max_number), $used_numbers);
        
        $selected_numbers = array_rand(array_flip($available_numbers), $quantity);
        if (!is_array($selected_numbers)) {
            $selected_numbers = [$selected_numbers];
        }
        
        // Inserir números na base de dados
        $insert_query = "INSERT INTO raffle_numbers (order_id, campaign_id, number, status) VALUES (?, ?, ?, 'paid')";
        $insert_stmt = $this->conn->prepare($insert_query);
        
        foreach ($selected_numbers as $number) {
            $insert_stmt->execute([$order_id, $campaign_id, $number]);
        }
    }

    private function updateRanking($user_id, $campaign_id, $quantity) {
        $query = "INSERT INTO ranking (campaign_id, user_id, total_numbers) 
                  VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE total_numbers = total_numbers + ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$campaign_id, $user_id, $quantity, $quantity]);
    }

    private function getUserById($id) {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getCampaignById($id) {
        $query = "SELECT * FROM campaigns WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>