<?php
session_start();
require_once 'classes/User.php';
require_once 'classes/Campaign.php';
require_once 'classes/Order.php';

class Main {
    private $user;
    private $campaign;
    private $order;

    public function __construct() {
        $this->user = new User();
        $this->campaign = new Campaign();
        $this->order = new Order();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? '';
        
        switch ($action) {
            case 'registration':
                $this->registration();
                break;
            case 'login_customer':
                $this->loginCustomer();
                break;
            case 'add_to_card':
                $this->addToCart();
                break;
            case 'place_order_process':
                $this->placeOrder();
                break;
            case 'check_order':
            case 'check_payment_status':
                $this->checkPaymentStatus();
                break;
            case 'search_orders_by_phone':
                $this->searchOrdersByPhone();
                break;
            default:
                echo json_encode(['status' => 'error', 'msg' => 'Ação não encontrada']);
        }
    }

    private function registration() {
        $data = [
            'firstname' => $_POST['firstname'] ?? '',
            'lastname' => $_POST['lastname'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'cpf' => $_POST['cpf'] ?? ''
        ];

        $result = $this->user->createUser($data);
        
        if ($result['status'] == 'success') {
            $_SESSION['user_id'] = $result['user_id'];
        }
        
        echo json_encode($result);
    }

    private function loginCustomer() {
        $phone = $_POST['phone'] ?? '';
        $user = $this->user->getUserByPhone($phone);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Usuário não encontrado']);
        }
    }

    private function addToCart() {
        $_SESSION['cart'] = [
            'product_id' => $_POST['product_id'] ?? '',
            'qty' => $_POST['qty'] ?? 1
        ];
        echo json_encode(['status' => 'success']);
    }

    private function placeOrder() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart'])) {
            echo json_encode(['status' => 'error', 'error' => 'Sessão inválida']);
            return;
        }

        $user_id = $_SESSION['user_id'];
        $campaign_id = $_SESSION['cart']['product_id'];
        $quantity = $_SESSION['cart']['qty'];
        
        // Calcular valor total
        $campaign = $this->campaign->getCampaignById($campaign_id);
        $discounts = $this->campaign->getDiscounts($campaign_id);
        
        $total_amount = $campaign['price'] * $quantity;
        
        // Aplicar desconto se houver
        foreach ($discounts as $discount) {
            if ($quantity >= $discount['quantity']) {
                $total_amount = $total_amount - $discount['discount_amount'];
            }
        }

        $result = $this->order->createOrder($user_id, $campaign_id, $quantity, $total_amount);
        
        if ($result['status'] == 'success') {
            echo json_encode([
                'status' => 'success',
                'redirect' => '/compra/index.php?token=' . $result['order_token']
            ]);
        } else {
            echo json_encode(['status' => 'error', 'error' => 'Erro ao criar pedido']);
        }
    }

    private function checkPaymentStatus() {
        $order_token = $_POST['order_token'] ?? '';
        $result = $this->order->checkPaymentStatus($order_token);
        echo json_encode($result);
    }

    private function searchOrdersByPhone() {
        $phone = $_POST['phone'] ?? '';
        $user = $this->user->getUserByPhone($phone);
        
        if ($user) {
            $orders = $this->user->getUserOrders($user['id']);
            if (!empty($orders)) {
                echo json_encode([
                    'status' => 'success',
                    'redirect' => '/meus-numeros.php?phone=' . urlencode($phone)
                ]);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Nenhuma compra encontrada']);
            }
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Usuário não encontrado']);
        }
    }
}

// Processar requisição
$main = new Main();
$main->handleRequest();
?>