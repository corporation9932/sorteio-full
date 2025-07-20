<?php
session_start();
require_once 'classes/User.php';

// Verificar se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || empty($_GET['firstname']) || empty($_GET['lastname']) || empty($_GET['phone'])) {
    header('Location: /');
    exit;
}

$user = new User();

// Dados do formulário
$data = [
    'firstname' => $_GET['firstname'],
    'lastname' => $_GET['lastname'],
    'phone' => $_GET['phone'],
    'email' => $_GET['email'] ?? '',
    'cpf' => $_GET['cpf'] ?? ''
];

// Criar ou buscar usuário
$existing_user = $user->getUserByPhone($data['phone']);
if ($existing_user) {
    $_SESSION['user_id'] = $existing_user['id'];
    $user_data = $existing_user;
} else {
    $result = $user->createUser($data);
    if ($result['status'] == 'success') {
        $_SESSION['user_id'] = $result['user_id'];
        $user_data = $user->getUserById($result['user_id']);
    } else {
        header('Location: /cadastro/');
        exit;
    }
}
?>
<!DOCTYPE html>
<html translate="no" lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações do Usuário - Gêmeos Brasil</title>
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
         <h1>✅ Cadastro Realizado</h1>
         <div class="app-title-desc">Bem-vindo(a), <?= htmlspecialchars($user_data['firstname']) ?>!</div>
      </div>

      <div class="app-card card mb-2">
         <div class="card-body">
            <h5>Suas Informações:</h5>
            <p><strong>Nome:</strong> <?= htmlspecialchars($user_data['firstname'] . ' ' . $user_data['lastname']) ?></p>
            <p><strong>Telefone:</strong> <?= htmlspecialchars($user_data['phone']) ?></p>
            <?php if ($user_data['email']): ?>
            <p><strong>Email:</strong> <?= htmlspecialchars($user_data['email']) ?></p>
            <?php endif; ?>
            <p><strong>Cadastrado em:</strong> <?= date('d/m/Y H:i', strtotime($user_data['created_at'])) ?></p>
         </div>
      </div>

      <div class="text-center">
         <a href="/" class="btn btn-success btn-lg">Participar de uma Rifa</a>
         <br><br>
         <a href="/meus-numeros.php?phone=<?= urlencode($user_data['phone']) ?>" class="btn btn-outline-primary">Ver Meus Números</a>
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

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>