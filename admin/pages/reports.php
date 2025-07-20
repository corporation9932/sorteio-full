<?php
require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Filtros
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$campaign_id = $_GET['campaign_id'] ?? '';

// Relatório de vendas
$sales_query = "SELECT 
                    DATE(o.created_at) as date,
                    COUNT(o.id) as total_orders,
                    SUM(o.quantity) as total_numbers,
                    SUM(o.total_amount) as total_revenue
                FROM orders o 
                WHERE o.payment_status = 'paid' 
                AND DATE(o.created_at) BETWEEN ? AND ?";

$params = [$start_date, $end_date];

if ($campaign_id) {
    $sales_query .= " AND o.campaign_id = ?";
    $params[] = $campaign_id;
}

$sales_query .= " GROUP BY DATE(o.created_at) ORDER BY date DESC";

$stmt = $conn->prepare($sales_query);
$stmt->execute($params);
$sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Campanhas para filtro
$campaigns_query = "SELECT id, title FROM campaigns ORDER BY created_at DESC";
$stmt = $conn->prepare($campaigns_query);
$stmt->execute();
$campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Totais gerais
$totals_query = "SELECT 
                    COUNT(o.id) as total_orders,
                    SUM(o.quantity) as total_numbers,
                    SUM(o.total_amount) as total_revenue,
                    COUNT(DISTINCT o.user_id) as unique_customers
                FROM orders o 
                WHERE o.payment_status = 'paid' 
                AND DATE(o.created_at) BETWEEN ? AND ?";

$params = [$start_date, $end_date];

if ($campaign_id) {
    $totals_query .= " AND o.campaign_id = ?";
    $params[] = $campaign_id;
}

$stmt = $conn->prepare($totals_query);
$stmt->execute($params);
$totals = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Relatórios</h2>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="page" value="reports">
            <div class="col-md-3">
                <label class="form-label">Data Início</label>
                <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Data Fim</label>
                <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Campanha</label>
                <select name="campaign_id" class="form-control">
                    <option value="">Todas as campanhas</option>
                    <?php foreach ($campaigns as $camp): ?>
                    <option value="<?= $camp['id'] ?>" <?= $campaign_id == $camp['id'] ? 'selected' : '' ?>>
                        <?= $camp['title'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<!-- Resumo -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5>Total de Pedidos</h5>
                <h2><?= number_format($totals['total_orders'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5>Números Vendidos</h5>
                <h2><?= number_format($totals['total_numbers'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5>Faturamento</h5>
                <h2>R$ <?= number_format($totals['total_revenue'] ?? 0, 2, ',', '.') ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5>Clientes Únicos</h5>
                <h2><?= number_format($totals['unique_customers'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Vendas por Dia -->
<div class="card">
    <div class="card-header">
        <h5>Vendas por Dia</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Pedidos</th>
                        <th>Números Vendidos</th>
                        <th>Faturamento</th>
                        <th>Ticket Médio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sales_data)): ?>
                    <tr>
                        <td colspan="5" class="text-center">Nenhum dado encontrado para o período selecionado</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($sales_data as $sale): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($sale['date'])) ?></td>
                        <td><?= number_format($sale['total_orders']) ?></td>
                        <td><?= number_format($sale['total_numbers']) ?></td>
                        <td>R$ <?= number_format($sale['total_revenue'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($sale['total_revenue'] / $sale['total_orders'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>