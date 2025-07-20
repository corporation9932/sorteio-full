<?php
require_once '../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Marcar como lido
if ($_POST['action'] ?? '' == 'mark_read') {
    $query = "UPDATE contacts SET status = 'read' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$_POST['id']]);
}

// Buscar contatos
$query = "SELECT * FROM contacts ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Contatos</h2>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Assunto</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                    <tr class="<?= $contact['status'] == 'new' ? 'table-warning' : '' ?>">
                        <td><?= htmlspecialchars($contact['name']) ?></td>
                        <td><?= htmlspecialchars($contact['email']) ?></td>
                        <td><?= htmlspecialchars($contact['phone']) ?></td>
                        <td><?= htmlspecialchars($contact['subject']) ?></td>
                        <td>
                            <span class="badge bg-<?= $contact['status'] == 'new' ? 'warning' : 'success' ?>">
                                <?= ucfirst($contact['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" onclick="viewContact(<?= $contact['id'] ?>)" data-bs-toggle="modal" data-bs-target="#contactModal">
                                <i class="bi bi-eye"></i>
                            </button>
                            <?php if ($contact['status'] == 'new'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="mark_read">
                                <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Visualizar Contato -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Contato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contactDetails">
                <!-- Conteúdo carregado via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
function viewContact(id) {
    // Buscar dados do contato via AJAX
    const contacts = <?= json_encode($contacts) ?>;
    const contact = contacts.find(c => c.id == id);
    
    if (contact) {
        document.getElementById('contactDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nome:</strong> ${contact.name}</p>
                    <p><strong>Email:</strong> ${contact.email}</p>
                    <p><strong>Telefone:</strong> ${contact.phone}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Campanha:</strong> ${contact.campaign || 'Não informado'}</p>
                    <p><strong>Assunto:</strong> ${contact.subject}</p>
                    <p><strong>Data:</strong> ${new Date(contact.created_at).toLocaleString('pt-BR')}</p>
                </div>
            </div>
            <hr>
            <div>
                <strong>Mensagem:</strong>
                <div class="border p-3 mt-2" style="background-color: #f8f9fa;">
                    ${contact.message.replace(/\n/g, '<br>')}
                </div>
            </div>
        `;
    }
}
</script>