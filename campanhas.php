<?php
require_once 'classes/Campaign.php';

$campaign = new Campaign();
$campaigns = $campaign->getActiveCampaigns();
?>
<!DOCTYPE html>
<html translate="no" lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campanhas - Gêmeos Brasil</title>
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
                        <li><a class="text-white" alt="Campanhas" href="/campanhas.php"><i class="icone bi bi-megaphone"></i><span>Campanhas</span></a></li>
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
         <h1>📢 Campanhas Ativas</h1>
         <div class="app-title-desc">Escolha sua rifa e boa sorte!</div>
      </div>

      <?php if (empty($campaigns)): ?>
      <div class="app-card card mb-2">
         <div class="card-body text-center">
            <h5>🎲 Nenhuma campanha ativa no momento</h5>
            <p>Novas rifas serão lançadas em breve. Fique atento!</p>
            <a href="/" class="btn btn-primary">Voltar ao Início</a>
         </div>
      </div>
      <?php else: ?>
      <?php foreach ($campaigns as $camp): ?>
      <div class="SorteioTpl_sorteioTpl__2s2Wu SorteioTpl_destaque__3vnWR pointer custom-highlight-card mb-3" onclick="location.href='/?campaign=<?= $camp['id'] ?>'">
         <div class="custom-badge-display">
            <span class="badge bg-success blink bg-opacity-75 font-xsss">Adquira já!</span>
         </div>
         <div class="SorteioTpl_imagemContainer__2-pl4 col-auto">
            <div class="custom-image">
               <img src="./js/<?= $camp['image'] ?>" alt="<?= $camp['title'] ?>" class="SorteioTpl_imagem__2GXxI" style="width:100%">
            </div>
         </div>
         <div class="SorteioTpl_info__t1BZr custom-content-wrapper custom-content-wrapper-details">
            <h1 class="SorteioTpl_title__3RLtu"><?= htmlspecialchars($camp['title']) ?></h1>
            <p class="SorteioTpl_descricao__1b7iL" style="margin-bottom:1px"><?= htmlspecialchars($camp['description']) ?></p>
            
            <div class="row mt-2">
               <div class="col-6">
                  <small class="text-muted">Preço por número:</small><br>
                  <strong>R$ <?= number_format($camp['price'], 2, ',', '.') ?></strong>
               </div>
               <div class="col-6">
                  <small class="text-muted">Sorteio:</small><br>
                  <strong><?= date('d/m/Y', strtotime($camp['draw_date'])) ?></strong>
               </div>
            </div>
            
            <div class="progress mt-2" style="height: 8px;">
               <?php 
               $percentage = ($camp['sold_numbers'] / $camp['total_numbers']) * 100;
               ?>
               <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="text-muted"><?= number_format($camp['sold_numbers']) ?> de <?= number_format($camp['total_numbers']) ?> números vendidos</small>
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

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>