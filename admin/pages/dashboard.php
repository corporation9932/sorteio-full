<?php
require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Estatísticas gerais
$stats = [];

// Total de campanhas
$query = "SELECT COUNT(*) as total FROM campaigns";
$stmt = $conn->prepare($query);
$stmt->execute();
$stats['campaigns'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total de usuários
$query = "SELECT COUNT(*) as total FROM users";
$stmt = $conn->prepare($query);
$stmt->execute();
$stats['users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total de pedidos
$query = "SELECT COUNT(*) as total FROM orders";
$stmt = $conn->prepare($query);
$stmt->execute();
$stats['orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Faturamento total
$query = "SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid'";
$stmt = $conn->prepare($query);
$stmt->execute();
$stats['revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
?>

<h2>Dashboard</h2>

<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Campanhas</h5>
                <h2><?= $stats['campaigns'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Usuários</h5>
                <h2><?= $stats['users'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Pedidos</h5>
                <h2><?= $stats['orders'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Faturamento</h5>
                <h2>R$ <?= number_format($stats['revenue'], 2, ',', '.') ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Últimos Pedidos</h5>
            </div>
            <div class="card-body">
                <?php
                $query = "SELECT o.*, u.firstname, u.lastname, c.title 
                          FROM orders o 
                          JOIN users u ON o.user_id = u.id 
                          JOIN campaigns c ON o.campaign_id = c.id 
                          ORDER BY o.created_at DESC 
                          LIMIT 10";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Campanha</th>
                                <th>Quantidade</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td><?= $order['id'] ?></td>
                                <td><?= $order['firstname'] . ' ' . $order['lastname'] ?></td>
                                <td><?= $order['title'] ?></td>
                                <td><?= $order['quantity'] ?></td>
                                <td>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="badge bg-<?= $order['payment_status'] == 'paid' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($order['payment_status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>