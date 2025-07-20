<?php
require_once '../classes/Campaign.php';

$campaign = new Campaign();

// Processar ações
if ($_POST['action'] ?? '') {
    switch ($_POST['action']) {
        case 'create':
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'image' => $_POST['image'],
                'price' => $_POST['price'],
                'total_numbers' => $_POST['total_numbers'],
                'min_purchase' => $_POST['min_purchase'],
                'max_purchase' => $_POST['max_purchase'],
                'draw_date' => $_POST['draw_date']
            ];
            $campaign->createCampaign($data);
            break;
        case 'delete':
            $campaign->deleteCampaign($_POST['id']);
            break;
    }
}

$campaigns = $campaign->getActiveCampaigns();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Campanhas</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#campaignModal">
        <i class="bi bi-plus"></i> Nova Campanha
    </button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Preço</th>
                        <th>Total Números</th>
                        <th>Vendidos</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($campaigns as $camp): ?>
                    <tr>
                        <td><?= $camp['id'] ?></td>
                        <td><?= $camp['title'] ?></td>
                        <td>R$ <?= number_format($camp['price'], 2, ',', '.') ?></td>
                        <td><?= number_format($camp['total_numbers']) ?></td>
                        <td><?= number_format($camp['sold_numbers']) ?></td>
                        <td>
                            <span class="badge bg-<?= $camp['status'] == 'active' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($camp['status']) ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="editCampaign(<?= $camp['id'] ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $camp['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nova Campanha -->
<div class="modal fade" id="campaignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Campanha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="create">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Imagem</label>
                            <input type="text" name="image" class="form-control" placeholder="nome-da-imagem.jpg">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Preço por Cota</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total de Números</label>
                            <input type="number" name="total_numbers" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data do Sorteio</label>
                            <input type="datetime-local" name="draw_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Compra Mínima</label>
                            <input type="number" name="min_purchase" class="form-control" value="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Compra Máxima</label>
                            <input type="number" name="max_purchase" class="form-control" value="500">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Criar Campanha</button>
                </div>
            </form>
        </div>
    </div>
</div>