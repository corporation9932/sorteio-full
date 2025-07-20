<?php
require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

$query = "SELECT u.*, COUNT(o.id) as total_orders, SUM(o.total_amount) as total_spent 
          FROM users u 
          LEFT JOIN orders o ON u.id = o.user_id AND o.payment_status = 'paid'
          GROUP BY u.id 
          ORDER BY u.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Usuários</h2>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Total Pedidos</th>
                        <th>Total Gasto</th>
                        <th>Data Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['firstname'] . ' ' . $user['lastname'] ?></td>
                        <td><?= $user['phone'] ?></td>
                        <td><?= $user['email'] ?: '-' ?></td>
                        <td><?= $user['total_orders'] ?></td>
                        <td>R$ <?= number_format($user['total_spent'] ?: 0, 2, ',', '.') ?></td>
                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>