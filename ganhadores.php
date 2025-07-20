<?php
require_once 'classes/Campaign.php';
require_once 'config/database.php';

$campaign = new Campaign();
$database = new Database();
$conn = $database->getConnection();

// Buscar ganhadores de campanhas finalizadas
$query = "SELECT c.title, c.draw_date, u.firstname, u.lastname, u.phone, rn.number
          FROM campaigns c
          JOIN raffle_numbers rn ON c.id = rn.campaign_id
          JOIN orders o ON rn.order_id = o.id
          JOIN users u ON o.user_id = u.id
          WHERE c.status = 'finished' AND rn.status = 'paid'
          ORDER BY c.draw_date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$winners = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html translate="no" lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganhadores - Gêmeos Brasil</title>
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
                <button type="button" aria-label="Menu" class="btn btn-link text-white font-lgg ps-0" data-bs-toggle="modal" data-bs-target="#mobileMenu" style="margin-top:5px">
                    <i class="bi bi-filter-left"></i>
                </button>
                <a class="flex-grow-1 text-center" href="/">
                    <img src="./js/logo.png" class="header-app-brand">
                </a>
                <a class="btn btn-link text-white pe-0 text-right text-decoration-none" href="/suporte/index.html">
                    <div class="suporte d-flex justify-content-end opacity-50"><i class="bi bi-chat-right-dots-fill"></i></div>
                    <div class="suporte text-yellow font-xss">Suporte</div>
                </a>
            </div>
        </div>
      </div>
   </header>
   
   <div class="black-bar fuse"></div>

   <menu id="mobileMenu" class="modal fade modal-fluid" tabindex="-1" aria-labelledby="mobileMenuLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen">
         <div class="modal-content bg-cor-primaria">
            <header class="app-header app-header-mobile--show">
               <div class="container container-600 h-100 d-flex align-items-center justify-content-between">
                  <a href="/">
                     <img src="./js/logo.png" class="app-brand img-fluid">
                  </a>
                  <div class="app-header-mobile">
                     <button type="button" class="btn btn-link text-white menu-mobile--button pe-0 font-lgg" data-bs-dismiss="modal" aria-label="Fechar">
                        <i class="bi bi-x-circle"></i>
                     </button>
                  </div>
               </div>
            </header>
            <div class="modal-body">
               <div class="container container-600">
                  <nav class="nav-vertical nav-submenu font-xs mb-2">
                     <ul>
                        <li><a class="text-white" alt="Página Principal" href="/"><i class="icone bi bi-house"></i><span>Início</span></a></li>
                        <li><a class="text-white" alt="Campanhas" href="#"><i class="icone bi bi-megaphone"></i><span>Campanhas</span></a></li>
                        <li><a class="text-white" alt="Meus Números" href="#"><i class="icone bi bi-card-list"></i><span>Meus números</span></a></li>
                        <li><a alt="Cadastre-se" class="text-white" href="/cadastro/index.html"><i class="icone bi bi-box-arrow-in-right"></i><span>Cadastro</span></a></li>
                        <li><a alt="Ganhadores" class="text-white" href="/ganhadores.php"><i class="icone bi bi-trophy"></i><span>Ganhadores</span></a></li>
                        <li><a alt="Termos de Uso" class="text-white" href="/termos.php"><i class="icone bi bi-blockquote-right"></i><span>Termos de uso</span></a></li>
                        <li class="col-contato-display"><a alt="Entre em contato conosco" class="text-white" href="/suporte/index.html"><i class="icone bi bi-envelope"></i><span>Entrar em contato</span></a></li>
                     </ul>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </menu>

   <div class="container app-main">
      <div class="app-title mb-2">
         <h1>🏆 Ganhadores</h1>
         <div class="app-title-desc">Confira os sortudos das nossas rifas!</div>
      </div>

      <?php if (empty($winners)): ?>
      <div class="app-card card mb-2">
         <div class="card-body text-center">
            <h5>🎲 Ainda não temos ganhadores</h5>
            <p>Os sorteios acontecem nas datas programadas. Participe das rifas ativas para concorrer aos prêmios!</p>
            <a href="/" class="btn btn-success">Ver Rifas Ativas</a>
         </div>
      </div>
      <?php else: ?>
      <?php foreach ($winners as $winner): ?>
      <div class="app-card card mb-2">
         <div class="card-body">
            <div class="row align-items-center">
               <div class="col-auto">
                  <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                     <i class="bi bi-trophy-fill text-white" style="font-size: 24px;"></i>
                  </div>
               </div>
               <div class="col">
                  <h5 class="mb-1"><?= htmlspecialchars($winner['title']) ?></h5>
                  <p class="mb-1"><strong>Ganhador:</strong> <?= htmlspecialchars($winner['firstname'] . ' ' . $winner['lastname']) ?></p>
                  <p class="mb-1"><strong>Número Sorteado:</strong> <?= str_pad($winner['number'], 6, '0', STR_PAD_LEFT) ?></p>
                  <small class="text-muted">Sorteio realizado em <?= date('d/m/Y', strtotime($winner['draw_date'])) ?></small>
               </div>
            </div>
         </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>

      <div class="text-center mt-4">
         <a href="/" class="btn btn-primary">Participar de uma Rifa</a>
      </div>
   </div>

   <!-- Footer -->
   <div class="container-fluid rodape">
      <div class="row justify-content-center align-items-center" style="padding:15px">
         <div class="col-md-12 col-12">
            <ul class="list-unstyled d-flex flex-wrap justify-content-center social" style="margin-bottom:0px;">
               <li class="spacing-icon">
                  <a class="twitter1" target="_blank" href="https://twitter.com/" title="Twitter">
                     <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                        <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"></path>
                     </svg>
                  </a>
               </li>
               <li class="spacing-icon">
                  <a class="youtube1" target="_blank" href="https://youtube.com/" title="Youtube">
                     <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-play-btn-fill" viewBox="0 0 16 16">
                        <path d="M0 12V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm6.79-6.907A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"></path>
                     </svg>
                  </a>
               </li>
            </ul>
         </div>
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