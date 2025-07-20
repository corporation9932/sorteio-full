<?php
require_once 'classes/User.php';

$phone = $_GET['phone'] ?? '';
if (!$phone) {
    header('Location: /');
    exit;
}

$user = new User();
$user_data = $user->getUserByPhone($phone);

if (!$user_data) {
    header('Location: /');
    exit;
}

$orders = $user->getUserOrders($user_data['id']);
?>
<!DOCTYPE html>
<html translate="no" lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Números - Gemeos Brasil</title>
    <link rel="shortcut icon" href="/js/favicon.png">
    <link rel="stylesheet" href="./js/style.css">
    <script src="./js/jquery.min.js"></script>
</head>
<body>
<div id="__next">
   <header class="header-app-header">
      <div class="header-app-header-container">
         <div class="container container-600 font-mdd">
            <div style="text-align-last: justify; padding: 10 0 10 0;">
                <a class="flex-grow-1 text-center" href="/">
                    <img src="./js/logo.png" class="header-app-brand">
                </a>
            </div>
        </div>
      </div>
   </header>
   
   <div class="black-bar fuse"></div>

   <div class="container app-main">
      <div class="app-title mb-2">
         <h1>📱 Meus Números</h1>
         <div class="app-title-desc">Olá, <?= $user_data['firstname'] ?>!</div>
      </div>

      <?php if (empty($orders)): ?>
      <div class="app-card card mb-2">
         <div class="card-body text-center">
            <p>Você ainda não possui nenhuma compra.</p>
            <a href="/" class="btn btn-primary">Participar de uma Rifa</a>
         </div>
      </div>
      <?php else: ?>
      <?php foreach ($orders as $order): ?>
      <div class="app-card card mb-2">
         <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
               <h5><?= $order['campaign_title'] ?></h5>
               <span class="badge bg-<?= $order['payment_status'] == 'paid' ? 'success' : 'warning' ?>">
                  <?= ucfirst($order['payment_status']) ?>
               </span>
            </div>
            <div class="row">
               <div class="col-6">
                  <small class="text-muted">Quantidade:</small><br>
                  <strong><?= $order['quantity'] ?> cotas</strong>
               </div>
               <div class="col-6">
                  <small class="text-muted">Valor:</small><br>
                  <strong>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></strong>
               </div>
            </div>
            <div class="row mt-2">
               <div class="col-12">
                  <small class="text-muted">Data da Compra:</small><br>
                  <strong><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></strong>
               </div>
            </div>
            <?php if ($order['payment_status'] == 'paid'): ?>
            <div class="mt-2">
               <button class="btn btn-sm btn-outline-primary" onclick="viewNumbers(<?= $order['id'] ?>)">
                  Ver Números
               </button>
            </div>
            <?php elseif ($order['payment_status'] == 'pending'): ?>
            <div class="mt-2">
               <a href="/compra/index.php?token=<?= $order['order_token'] ?>" class="btn btn-sm btn-warning">
                  Finalizar Pagamento
               </a>
            </div>
            <?php endif; ?>
         </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>

      <div class="text-center mt-4">
         <a href="/" class="btn btn-primary">Voltar ao Início</a>
      </div>
   </div>

   <!-- Footer -->
   <div class="container-fluid rodape">
      <div class="row justify-content-center align-items-center" style="padding:15px">
         <div class="col-md-12 col-12 font-xs">
            <hr>
            Esta página não faz parte ou está relacionada ao Kwai ou à Kuaishou Technology. Além disso, este site NÃO é endossado pelo Kwai de forma alguma.
            <div class="row mt-2" style="color:var(--incrivel-primariaLink);">
               <div class="col-12 font-xs">Desenvolvido por <a href="https://t.me/TXT_JPGI1" target="_blank" class="font-weight-600 font-xs badge" rel="noreferrer" style="background-color:#ff7e01;">Sistema de Rifa</a></div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
function viewNumbers(orderId) {
    // Implementar visualização dos números
    alert('Funcionalidade em desenvolvimento');
}
</script>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>