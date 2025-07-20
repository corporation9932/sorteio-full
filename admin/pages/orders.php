<?php
require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

$query = "SELECT o.*, u.firstname, u.lastname, u.phone, c.title as campaign_title 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          JOIN campaigns c ON o.campaign_id = c.id 
          ORDER BY o.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Pedidos</h2>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Telefone</th>
                        <th>Campanha</th>
                        <th>Quantidade</th>
                        <th>Valor Total</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['firstname'] . ' ' . $order['lastname'] ?></td>
                        <td><?= $order['phone'] ?></td>
                        <td><?= $order['campaign_title'] ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></td>
                        <td>
                            <span class="badge bg-<?= $order['payment_status'] == 'paid' ? 'success' : ($order['payment_status'] == 'pending' ? 'warning' : 'danger') ?>">
                                <?= ucfirst($order['payment_status']) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" onclick="viewOrder(<?= $order['id'] ?>)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>