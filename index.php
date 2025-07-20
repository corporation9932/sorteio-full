<?php
require_once 'classes/Campaign.php';

$campaign = new Campaign();
$campaigns = $campaign->getActiveCampaigns();
$current_campaign = $campaigns[0] ?? null;

if (!$current_campaign) {
    // Criar campanha padrão se não existir
    $default_campaign = [
        'title' => 'EDIÇÃO 08 - CEGONHA MILIONÁRIA',
        'description' => 'são 8 caminhonetas + cegonha para sua mudança de vida',
        'image' => 'cegonha caminhonetas.jpg',
        'price' => 5.00,
        'total_numbers' => 1000000,
        'min_purchase' => 1,
        'max_purchase' => 500,
        'draw_date' => '2024-12-31 19:00:00'
    ];
    $campaign->createCampaign($default_campaign);
    $campaigns = $campaign->getActiveCampaigns();
    $current_campaign = $campaigns[0];
}

$discounts = $campaign->getDiscounts($current_campaign['id']);
$ranking = $campaign->getRanking($current_campaign['id'], 3);
?>
<!DOCTYPE html>
<html translate="no" lang="pt-br">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title><?= $current_campaign['title'] ?> - Gemeos Brasil</title>
    <meta name="description" content="">
    <meta property="og:title" content="<?= $current_campaign['title'] ?> - Gemeos Brasil">
    <meta property="og:description" content="">
    <meta property="og:image" content="">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="">
    <meta property="og:image:height" content="">
    <link rel="shortcut icon" href="/js/favicon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/js/favicon.png"> 
    <link rel="icon" type="image/png" sizes="32x32" href="/js/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/js/favicon.png">
    <meta name="theme-color" content="#000000">
    <link rel="stylesheet" href="./js/style.css">
    <script src="./js/jquery.min.js"></script>   
    <script> var _base_url_ = './'; </script>
</head>
<body>
<div id="__next">
   <header class="header-app-header">
      <div class="header-app-header-container">
         <div class="container container-600 font-mdd">
                          <?php 
                          $original_price = $current_campaign['price'] * $discount['quantity'];
                          $final_price = $original_price - $discount['discount_amount'];
                          echo number_format($final_price, 2, ',', '.');
                          ?>
                <button type="button" aria-label="Menu" class="btn btn-link text-white font-lgg ps-0" data-bs-toggle="modal" data-bs-target="#mobileMenu" style="margin-top:5px">
                    <i class="bi bi-filter-left"></i>
                </button>
                <a class="flex-grow-1 text-center" href="#">
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
                  <a href="#">
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
                        <li><a class="text-white" alt="Página Principal" href="#"><i class="icone bi bi-house"></i><span>Início</span></a></li>
                        <li><a class="text-white" alt="Campanhas" href="#"><i class="icone bi bi-megaphone"></i><span>Campanhas</span></a></li>
                        <li><a class="text-white" alt="Meus Números" href="#"><i class="icone bi bi-card-list"></i><span>Meus números</span></a></li>
                        <li><a alt="Cadastre-se" class="text-white" href="/cadastro/index.html"><i class="icone bi bi-box-arrow-in-right"></i><span>Cadastro</span></a></li>
                        <li><a alt="Ganhadores" class="text-white" href="#"><i class="icone bi bi-trophy"></i><span>Ganhadores</span></a></li>
                        <li><a alt="Termos de Uso" class="text-white" href="#"><i class="icone bi bi-blockquote-right"></i><span>Termos de uso</span></a></li>
                        <li class="col-contato-display"><a alt="Entre em contato conosco" class="text-white" href="/suporte/index.html"><i class="icone bi bi-envelope"></i><span>Entrar em contato</span></a></li>
                     </ul>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </menu>

   <div id="overlay" style="display: none;">
      <div class="cv-spinner">
         <div class="card" style="border:none; padding:10px;background: transparent;color: #fff !important;font-weight: 800;">
            <span class="spinner mb-2" style="align-self:center;"></span>
            <div class="text-center font-xs">Estamos gerando seu pedido, aguarde...</div>
         </div>
      </div>
   </div>

   <div class="container app-main">
      <div class="SorteioTpl_sorteioTpl__2s2Wu SorteioTpl_destaque__3vnWR pointer custom-highlight-card">
         <div class="custom-badge-display">
            <span class="badge bg-success blink bg-opacity-75 font-xsss">Adquira já!</span>
         </div>
         <div class="SorteioTpl_imagemContainer__2-pl4 col-auto">
            <div id="carouselSorteio640d0a84b1fef407920230311" class="carousel slide carousel-dark carousel-fade" data-bs-ride="carousel">
               <div class="carousel-inner">
                  <div class="custom-image carousel-item active">
                     <img src="./js/<?= $current_campaign['image'] ?>" alt="<?= $current_campaign['title'] ?>" class="SorteioTpl_imagem__2GXxI" style="width:100%">
                  </div>
               </div>
            </div>
         </div>
         <div class="SorteioTpl_info__t1BZr custom-content-wrapper custom-content-wrapper-details">
            <h1 class="SorteioTpl_title__3RLtu"><?= $current_campaign['title'] ?></h1>
            <p class="SorteioTpl_descricao__1b7iL" style="margin-bottom:1px"><?= $current_campaign['description'] ?></p>
            <div class="btn btn-sm btn-success box-shadow-08 w-100" data-bs-toggle="modal" data-bs-target="#modal-consultaCompras">
               <i class="bi bi-cart"></i> Ver meus números
            </div>
         </div>
      </div>

      <div class="campanha-preco porApenas font-xs d-flex align-items-center justify-content-center mt-2 mb-2 font-weight-500">
         <div class="item d-flex align-items-center font-xs me-2">
            <span class="ms-2 me-1">Campanha</span>
            <div class="tag btn btn-sm bg-white bg-opacity-50 font-xss box-shadow-08">
               28/08/24 às 19h00
            </div>
         </div>
         <div class="item d-flex align-items-center font-xs">
            <div class="me-1">por apenas</div>
            <div class="tag btn btn-sm bg-cor-primaria text-cor-primaria-link box-shadow-08">R$ 5,00</div>
         </div>
      </div>

      <div class="app-card card mb-2">
         <div class="card-body text-center">
            <p class="font-xs">Quanto mais comprar, maiores são as suas chances de ganhar!</p>
         </div>
      </div>

      <?php if (!empty($discounts)): ?>
      <div class="app-promocao-numeros mb-2">
         <div class="app-title mb-2">
            <h1>📣 Promoção</h1>
            <div class="app-title-desc">Compre mais barato!</div>
         </div>
         <div class="app-card card">
            <div class="card-body pb-1">
               <div class="row px-2">
                  <?php foreach ($discounts as $index => $discount): ?>
                  <div class="col-auto px-1 mb-2">
                     <button data-bs-toggle="modal" data-bs-target="#loginModal" onclick="qtyRaffle('<?= $discount['quantity'] ?>', true);" class="btn btn-success w-100 btn-sm py-0 px-2 text-nowrap font-xss">
                        <span class="font-weight-500">
                           <b class="font-weight-600"><span id="discount_qty_<?= $index ?>"><?= $discount['quantity'] ?></span></b> 
                           <small>por R$</small> 
                           <span class="font-weight-600">
                              <span id="discount_amount_<?= $index ?>" style="display:none"><?= $discount['discount_amount'] ?></span>
                              <?= number_format(($current_campaign['price'] * $discount['quantity']) - $discount['discount_amount'], 2, ',', '.') ?>
                           </span>
                        </span>
                     </button>
                  </div>
                  <?php endforeach; ?>
               </div>
            </div>
         </div>
      </div>
      <?php endif; ?>

      <div class="app-vendas-express mb-2">
         <div class="numeros-select d-flex align-items-center justify-content-center flex-column">
            <div class="vendasExpressNumsSelect v2">
               <div onclick="qtyRaffle(10, false);" class="item mb-2">
                  <div class="item-content flex-column p-2">
                     <h3 class="mb-0"><small class="item-content-plus font-xsss">+</small>10</h3>
                     <p class="item-content-txt font-xss text-uppercase mb-0">Selecionar</p>
                  </div>
               </div>
               <div onclick="qtyRaffle(20, false);" class="item mb-2">
                  <div class="item-content flex-column p-2">
                     <h3 class="mb-0"><small class="item-content-plus font-xsss">+</small>20</h3>
                     <p class="item-content-txt font-xss text-uppercase mb-0">Selecionar</p>
                  </div>
               </div>
               <div onclick="qtyRaffle(50, false);" class="item mb-2 mais-popular">
                  <div class="item-content flex-column p-2">
                     <h3 class="mb-0"><small class="item-content-plus font-xsss">+</small>50</h3>
                     <p class="item-content-txt font-xss text-uppercase mb-0" style="color:#fff;">Selecionar</p>
                  </div>
               </div>
               <div onclick="qtyRaffle(100, false);" class="item mb-2">
                  <div class="item-content flex-column p-2">
                     <h3 class="mb-0"><small class="item-content-plus font-xsss">+</small>100</h3>
                     <p class="item-content-txt font-xss text-uppercase mb-0">Selecionar</p>
                  </div>
               </div>
               <div onclick="qtyRaffle(200, false);" class="item mb-2">
                  <div class="item-content flex-column p-2">
                     <h3 class="mb-0"><small class="item-content-plus font-xsss">+</small>200</h3>
                     <p class="item-content-txt font-xss text-uppercase mb-0">Selecionar</p>
                  </div>
               </div>
               <div onclick="qtyRaffle(300, false);" class="item mb-2">
                  <div class="item-content flex-column p-2">
                     <h3 class="mb-0"><small class="item-content-plus font-xsss">+</small>300</h3>
                     <p class="item-content-txt font-xss text-uppercase mb-0">Selecionar</p>
                  </div>
               </div>
            </div>

            <div class="vendasExpressNums app-card card mb-2 w-100 font-xs">
               <div class="card-body d-flex align-items-center justify-content-center font-xss p-1">
                  <div class="left pointer">
                     <div class="removeNumero numeroChange"><i class="bi bi-dash-circle"></i></div>
                  </div>
                  <div class="center">
                     <input class="form-control text-center qty" readonly="" value="<?= $current_campaign['min_purchase'] ?>" aria-label="Quantidade de números" placeholder="<?= $current_campaign['min_purchase'] ?>">
                  </div>
                  <div class="right pointer">
                     <div class="addNumero numeroChange"><i class="bi bi-plus-circle"></i></div>
                  </div>
               </div>
            </div>
         </div>

         <button data-bs-toggle="modal" data-bs-target="#loginModal" class="btn btn-success w-100 py-3">
            <div class="row align-items-center" style="line-height:85%;">
               <div class="col pe-0 text-nowrap"><i class="bi bi-check2-circle me-1"></i><span>Quero participar</span></div>
               <div class="col pe-0 text-nowrap price-mobile">
                  <span id="total">R$ 5,00</span>
               </div>
            </div>
         </button>
      </div>

      <?php if (!empty($ranking)): ?>
      <div class="app-title mb-2">
         <h1>🏆 Ranking</h1>
         <div class="app-title-desc">Quem comprar mais cotas, 1º lugar ganha: R$50.000 , 2º lugar ganha: R$25.000 e 3º lugar ganha: R$10.000</div>
      </div>
      
      <div class="app-card top-compradores" style="padding: 20 0 10 10;border-radius:10px;margin-top:0px;margin-bottom:10px;">
         <?php 
         $medals = ['🥇', '🥈', '🥉'];
         foreach ($ranking as $index => $rank): 
         ?>
         <div class="item-content flex-column" style="max-width:32.7%;min-width:32.7%;">
            <div class="text-center customer-details" style="border:1px solid;padding:10px;border-radius:5px;margin:5px;">
               <span style="font-size:20px;"><?= $medals[$index] ?? '🏆' ?></span><br>
               <span class="ganhador-name"><?= $rank['firstname'] ?></span>
               <p class="font-xss mb-0"><?= $rank['total_numbers'] ?> COTAS</p>
            </div>
         </div>
         <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Modais -->
      <div class="modal fade" id="modal-consultaCompras">
         <div class="modal-dialog modal-md">
            <div class="modal-content">
               <form id="consultMyNumbers">
                  <div class="modal-header">
                     <h6 class="modal-title">Consulta de compras</h6>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <div class="form-group">
                        <label class="form-label">Informe seu telefone</label>
                        <div class="input-group mb-2">
                           <input onkeyup="formatarTEL(this);" maxlength="15" class="form-control" aria-label="Número de telefone" id="phone" name="phone" required="" value="">
                           <button class="btn btn-secondary" type="submit" id="button-addon2">
                              <div class=""><i class="bi bi-check-circle"></i></div>
                           </button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>

      <!-- Modal Login -->
      <form class="modal fade" id="loginModal">
         <div class="modal-dialog modal-sm modal-fullscreen-md-down modal-dialog-centered">
            <div class="modal-content rounded-0">
               <div class="modal-header">
                  <h5 class="modal-title">Login</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body app-form">
                  <p class="text-muted font-xs">Por favor, entre com seus dados ou faça um cadastro.</p>
                  <span id="aviso-login"></span>
                  <div class="mb-2">
                     <div class="form-floating font-weight-500">
                        <input onkeyup="formatarTEL(this);" maxlength="15" name="phone" id="phone" required="" class="form-control text-black" placeholder="(00) 0000-0000" value="">
                        <label for="username">Telefone</label>
                     </div>
                  </div>
                  <div class="d-flex justify-content-center align-items-center flex-column">
                     <button type="submit" class="btn btn-wide-in btn-primary font-weight-500 rounded-pill mb-2">Continuar</button>
                     <div class="btn btn-link btn-sm text-decoration-none"><a href="/cadastro/index.html">Criar conta</a></div>
                  </div>
               </div>
            </div>
         </div>
      </form>

      <!-- Modal Cadastro -->
      <span id="openCadastro" data-bs-toggle="modal" data-bs-target="#cadastroModal" style="display:none;"></span>
      <form class="modal fade" id="cadastroModal">
         <div class="modal-dialog modal-sm modal-fullscreen-md-down modal-dialog-centered">
            <div class="modal-content rounded-0">
               <div class="modal-header">
                  <h5 class="modal-title">Cadastro</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body app-form">
                  <p class="text-muted font-xs">Por favor, entre com seus dados para finalizar o cadastro.</p>
                  <div class="mb-2">
                     <label for="firstname" class="form-label">Nome</label>
                     <input type="text" name="firstname" class="form-control text-black" id="firstname" placeholder="Nome" required="">
                  </div>
                  <div class="mb-2">
                     <label for="lastname" class="form-label">Sobrenome</label>
                     <input type="text" name="lastname" class="form-control text-black" id="lastname" placeholder="Sobrenome" required="">
                  </div>
                  <div class="mb-2">
                     <label for="phone" class="form-label">Telefone</label>
                     <input onkeyup="formatarTEL(this);" maxlength="15" name="phone" id="phone" required="" class="phone form-control text-black" placeholder="(00) 0000-0000" value="">
                  </div>
                  <div class="mb-2">
                     <label for="phone_confirm" class="form-label">Confirme seu telefone</label>
                     <input onkeyup="formatarTEL(this);" maxlength="15" name="phone_confirm" id="phone_confirm" required="" class="phone_confirm form-control text-black" placeholder="(00) 0000-0000" value="">
                  </div>
                  <div class="alert alert-primary mt-3 font-xss">
                     Ao se cadastrar você concorda com nossos <a style="color:var(--incrivel-primaria);" href="#" target="_blank">termos</a>.
                  </div>
                  <div class="d-flex justify-content-center align-items-center flex-column">
                     <button type="submit" class="btn btn-wide-in btn-primary font-weight-500 rounded-pill mb-2">Continuar</button>
                  </div>
               </div>
            </div>
         </div>
      </form>
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

<script>
$(function(){
    $('#add_to_cart').click(function(){
        add_cart();
    })

    $(".addNumero").click(function() {
        let value = parseInt($(".qty").val());
        value++;
        $(".qty").val(value);
        calculatePrice(value);
    })

    $(".removeNumero").click(function() {
        let value = parseInt($(".qty").val());
        if (value <= <?= $current_campaign['min_purchase'] ?>) {
            value = <?= $current_campaign['min_purchase'] ?>;
        } else {
            value--;
        }
        $(".qty").val(value);
        calculatePrice(value);
    })
})

function formatCurrency(total) {
    var decimalSeparator = ',';
    var thousandsSeparator = '.';
    var formattedTotal = total.toFixed(2);
    formattedTotal = formattedTotal.replace('.', decimalSeparator);
    var parts = formattedTotal.split(decimalSeparator);
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);
    return parts.join(decimalSeparator);
}

function calculatePrice(qty){   
    let price = 5.00; 
    let total = price * qty;  
    var max = 500;
    var min = 1;

    if (qty < min) {
        $(".qty").val(min);
        total = price * min; 
        calculatePrice(min);
        return; 
    } 
    
    if(qty > max){
        $(".qty").val(max); 
        total = price * max;
        calculatePrice(max);
        return;
    }

    // Aplicar descontos
    <?php if (!empty($discounts)): ?>
    let dropeDescontos = [
        <?php foreach ($discounts as $index => $discount): ?>
        {qtd: <?= $discount['quantity'] ?>, vlr: <?= $discount['discount_amount'] ?>},
        <?php endforeach; ?>
    ];

    var drope_desconto_qty = null;
    var drope_desconto = null;

    for (i = 0; i < dropeDescontos.length; i++) {
        if (qty >= dropeDescontos[i].qtd) {
            drope_desconto_qty = dropeDescontos[i].qtd;
            drope_desconto = dropeDescontos[i].vlr;
        }
    }

    if (parseInt(qty) >= parseInt(drope_desconto_qty)) {
        var drope_desconto_aplicado = total - drope_desconto;
        $('#total').html('De <strike>R$ ' + formatCurrency(total) + '</strike> por R$ ' + formatCurrency(drope_desconto_aplicado));
    } else {
        $('#total').html('R$ ' + formatCurrency(total));  
    }
    <?php else: ?>
    $('#total').html('R$ ' + formatCurrency(total));
    <?php endif; ?>
}

function qtyRaffle(qty, opt) {
    qty = parseInt(qty);
    let value = parseInt($(".qty").val());  
    let qtyTotal = (value + qty);
    if(opt === true){
        qtyTotal = qty;
    }
    $(".qty").val(qtyTotal);
    calculatePrice(qtyTotal);  
}

function add_cart(){
    let qty = $('.qty').val();
    $.ajax({
        url: "classes/Main.php?action=add_to_card",
        method: "POST",
        data: {product_id: "<?= $current_campaign['id'] ?>", qty: qty},
        dataType: "json",
        success: function(resp){
            if(typeof resp == 'object' && resp.status == 'success'){
                // Sucesso
            }
        }
    })
}

$(document).ready(function(){
    $('#consultMyNumbers').submit(function(e){
        e.preventDefault()
        $.ajax({
            url: "classes/Main.php?action=search_orders_by_phone",
            method: 'POST',
            data: new FormData($(this)[0]),
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            success: function(resp){
                if(resp.status == 'success'){
                    location.href = resp.redirect;
                } else {
                    alert('Nenhum registro de compra foi encontrado');
                }
            }
        })
    })

    $('#form-cadastrar, #cadastroModal').submit(function (e) {
        e.preventDefault();
        var phoneValue = $('.phone').val();
        var phoneConfirmValue = $('.phone_confirm').val();
        
        if (phoneValue.length < 15 || phoneValue.length > 15) {
            alert('Telefone inválido. Por favor corrija.');
            return;
        }
        
        if (phoneConfirmValue && phoneConfirmValue != phoneValue){
            alert('Telefone inválido. Por favor corrija');
            return;
        }

        $.ajax({
            url: "classes/Main.php?action=registration",
            method: 'POST',
            data: new FormData($(this)[0]),
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            success: function (resp) {
                if (resp.status == 'success') {
                    $('.btn-close').click();
                    $('#overlay').fadeIn(300);
                    setTimeout(function () {
                        add_cart();
                        $.ajax({
                            url: "classes/Main.php?action=place_order_process",
                            method: 'POST',
                            data: {},
                            dataType: 'json',
                            success: function(resp) {
                                if(resp.status == 'success') {
                                    location.replace(resp.redirect);
                                }
                            }
                        });
                    }, 2000);
                } else if (resp.status == 'phone_already') {
                    alert(resp.msg);
                } else {
                    alert('Erro ao cadastrar');
                }
            }
        })
    })

    $('#loginModal').submit(function (e) {
        e.preventDefault()
        $.ajax({
            url: "classes/Main.php?action=login_customer",
            method: 'POST',
            data: new FormData($(this)[0]),
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            success: function (resp) {
                if (resp.status == 'success') {
                    $('.btn-close').click();
                    $('#overlay').fadeIn(300);
                    setTimeout(function () {
                        add_cart();
                        $.ajax({
                            url: "classes/Main.php?action=place_order_process",
                            method: 'POST',
                            data: {},
                            dataType: 'json',
                            success: function(resp) {
                                if(resp.status == 'success') {
                                    location.replace(resp.redirect);
                                }
                            }
                        });
                    }, 2000);
                } else {
                    var phone = $('#loginModal #phone').val();
                    $('#cadastroModal #phone').val(phone);
                    $('#openCadastro').click();
                }
            }
        })
    })
})

function formatarTEL(e) { 
    v = e.value;
    v = v.replace(/\D/g, "");
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
    v = v.replace(/(\d)(\d{4})$/, "$1-$2");
    e.value = v;
}

</script>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>