<?php
require_once '../classes/Order.php';

$order_token = $_GET['token'] ?? '';
if (!$order_token) {
    header('Location: /');
    exit;
}

$order = new Order();
$order_data = $order->getOrderByToken($order_token);

if (!$order_data) {
    header('Location: /');
    exit;
}

// Verificar se o pedido expirou
if (strtotime($order_data['expires_at']) < time()) {
    $expired = true;
} else {
    $expired = false;
}
?>
<!DOCTYPE html>
<html translate="no" lang="pt-br">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title>Checkout - Gemeos Brasil</title>
    <meta name="description" content="">
    <meta property="og:title" content="Checkout - Gemeos Brasil">
    <meta property="og:description" content="">
    <link rel="shortcut icon" href="/js/favicon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/js/favicon.png"> 
    <link rel="icon" type="image/png" sizes="32x32" href="/js/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/js/favicon.png">
    <meta name="theme-color" content="#000000">
    <link rel="stylesheet" href="../js/style.css">
    <script src="../js/jquery.min.js"></script>   
    <script> var _base_url_ = '../'; </script>
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
                    <img src="../js/logo.png" class="header-app-brand">
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

   <div class="app-main container">
      <div class="compra-status">
         <?php if ($expired): ?>
         <div class="app-alerta-msg mb-2">
            <i class="app-alerta-msg--icone bi bi-x-circle text-danger"></i>
            <div class="app-alerta-msg--txt">
               <h3 class="app-alerta-msg--titulo">Pedido Expirado!</h3>
               <p>O tempo para pagamento expirou</p>
            </div>
         </div>
         <?php elseif ($order_data['payment_status'] == 'paid'): ?>
         <div class="app-alerta-msg mb-2">
            <i class="app-alerta-msg--icone bi bi-check-circle text-success"></i>
            <div class="app-alerta-msg--txt">
               <h3 class="app-alerta-msg--titulo">Pagamento Confirmado!</h3>
               <p>Seus números foram gerados com sucesso</p>
            </div>
         </div>
         <?php else: ?>
         <div class="app-alerta-msg mb-2">
            <i class="app-alerta-msg--icone bi bi-check-circle text-warning"></i>
            <div class="app-alerta-msg--txt">
               <h3 class="app-alerta-msg--titulo">Aguardando pagamento!</h3>
               <p>Finalize o pagamento via PIX</p>
            </div>
         </div>
         <?php endif; ?>
         <hr class="my-2">
      </div>

      <?php if (!$expired && $order_data['payment_status'] != 'paid'): ?>
      <div class="compra-pagamento">
         <div class="pagamentoQrCode text-center">
            <div class="pagamento-rapido">
               <div class="app-card card rounded-top rounded-0 shadow-none border-bottom">
                  <div class="card-body">
                     <div class="pagamento-rapido--progress">
                        <div class="d-flex justify-content-center align-items-center mb-1 font-md">
                           <div><small>Você tem</small></div>
                           <div class="mx-1"><b class="font-md" id="tempo-restante">--:--</b></div>
                           <div><small>para pagar</small></div>
                        </div>
                        <div class="progress bg-dark bg-opacity-50">
                           <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="barra-progresso" style="width: 0%;"></div>
                        </div>
                     </div>                  
                  </div>
               </div>
            </div>

            <div class="app-card card rounded-bottom rounded-0 rounded-bottom b-1 border-dark mb-2">
               <div class="card-body">
                  <div class="row justify-content-center mb-2">
                     <div class="col-12 text-start">
                        <div class="mb-1"><span class="badge bg-success badge-xs">1</span><span class="font-xs"> Copie o código PIX abaixo.</span></div>
                        <div class="input-group mb-2">
                           <input id="pixCopiaCola" type="text" class="form-control" value="<?= $order_data['pix_code'] ?>">
                           <div class="input-group-append">
                              <button onclick="copyPix()" class="app-btn btn btn-success rounded-0 rounded-end">Copiar</button>
                           </div>
                        </div>
                        <div class="mb-2"><span class="badge bg-success">2</span> <span class="font-xs">Abra o app do seu banco e escolha a opção PIX, como se fosse fazer uma transferência.</span></div>
                        <p><span class="badge bg-success">3</span> <span class="font-xs">Selecione a opção PIX cópia e cola, cole a chave copiada e confirme o pagamento.</span></p>
                     </div>
                     <div class="col-12 my-2">
                        <p class="alert alert-warning p-2 font-xss" style="text-align: justify;">Este pagamento só pode ser realizado dentro do tempo, após este período, caso o pagamento não for confirmado os números voltam a ficar disponíveis.</p>
                     </div>
                     <div class="col-12">
                        <button id="check_payment" class="app-btn btn btn-success btn-sm mt-1"><i class="bi bi-check-all"></i> Já realizei o pagamento</button>
                        <p id="timeLeft" class="font-xss mt-1"></p>
                     </div>
                  </div>

                  <div style="background-image: url('../assets/img/bg-btn-qr.png'); text-align: center;">
                     <input id="btmqr" class="btn-qr" type="button" value="Mostrar QR Code" onclick="mostraqr()">
                  </div>
                  <div id="exibeqr" class="hidden" style="display: none;">
                     <div class="input-group-append">
                        <table style="width:100%">
                           <tbody>
                              <tr>
                                 <td style="width:50%; vertical-align: middle;">
                                    <b>QR Code</b>
                                    <span class="font-xs m-0"><br>Acesse ao app do seu banco e escolha a opção <b>Pagar com QR Code</b>, scaneie o código ao lado e confirme o pagamento.</span>
                                 </td>
                                 <td>
                                    <div id="img-qrcode" class="d-inline-block bg-white rounded">
                                       <img src="data:image/png;base64,<?= $order_data['qr_code'] ?>" class="img-fluid">
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php endif; ?>

      <div class="detalhes-compra">
         <div class="compra-campanha mb-2">                 
            <div class="SorteioTpl_sorteioTpl__2s2Wu pointer">
               <div class="SorteioTpl_imagemContainer__2-pl4 col-auto">
                  <div style="display: block; overflow: hidden; position: absolute; inset: 0px; box-sizing: border-box; margin: 0px;">
                     <img alt="<?= $order_data['campaign_title'] ?>" src="../js/cegonha caminhonetas.jpg" decoding="async" data-nimg="fill" class="SorteioTpl_imagem__2GXxI" style="position: absolute; inset: 0px; box-sizing: border-box; padding: 0px; border: none; margin: auto; display: block; width: 0px; height: 0px; min-width: 100%; max-width: 100%; min-height: 100%; max-height: 100%;">
                  </div>
               </div>
               <div class="SorteioTpl_info__t1BZr">
                  <h1 class="SorteioTpl_title__3RLtu"><a href="/"><?= $order_data['campaign_title'] ?></a></h1>
                  <p class="SorteioTpl_descricao__1b7iL" style="margin-bottom: 1px;">Sua participação na rifa</p>
                  <span class="badge bg-success blink bg-opacity-75 font-xsss">Pedido #<?= $order_data['id'] ?></span>
               </div>
            </div>
         </div>
         
         <div class="detalhes app-card card mb-2">
            <div class="card-body font-xs">
               <div class="font-xs opacity-75 mb-2 border-bottom-rgba">
                  <i class="bi bi-info-circle"></i> Detalhes da sua compra&nbsp;
                  <div class="pt-1 opacity-50 mb-1"><?= $order_data['order_token'] ?></div>
               </div>
               <div class="item d-flex align-items-baseline mb-1 pb-1">
                  <div class="title me-1">
                     <i class="bi bi-check-circle"></i> Transação
                  </div>
                  <div class="result font-xs"><?= $order_data['id'] ?></div>
               </div>
               <div class="item d-flex align-items-baseline mb-1 pb-1">
                  <div class="title me-1">
                     <i class="bi bi-person"></i> Cliente
                  </div>
                  <div class="result font-xs"><?= $order_data['firstname'] . ' ' . $order_data['lastname'] ?></div>
               </div>
               <div class="item d-flex align-items-baseline mb-1 pb-1">
                  <div class="title me-1">
                     <i class="bi bi-phone"></i> Telefone
                  </div>
                  <div class="result font-xs"><?= $order_data['phone'] ?></div>
               </div>
               <div class="item d-flex align-items-baseline mb-1 pb-1">
                  <div class="title me-1">
                     <i class="bi bi-ticket-detailed"></i> Quantidade
                  </div>
                  <div class="result font-xs"><?= $order_data['quantity'] ?> cotas</div>
               </div>
               <div class="item d-flex align-items-baseline mb-1 pb-1 border-bottom-rgba">
                  <div class="title me-1">
                     <i class="bi bi-currency-dollar"></i> Valor Total
                  </div>
                  <div class="result font-xs">R$ <?= number_format($order_data['total_amount'], 2, ',', '.') ?></div>
               </div>
               <div class="item d-flex align-items-baseline">
                  <div class="result font-xs" data-nosnippet="true" style="overflow:hidden;">
                     <?php if ($order_data['payment_status'] == 'paid'): ?>
                        Seus números foram gerados e enviados por WhatsApp.
                     <?php else: ?>
                        As cotas serão geradas após o pagamento.
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         </div>
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
               <div class="col-12 font-xs">Desenvolvido por <a href="https://sistemaderifa.com.br/" target="_blank" class="font-weight-600 font-xs badge" rel="noreferrer" style="background-color:#ff7e01;">Sistema de Rifa</a></div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
function copyPix() {
    var copyText = document.getElementById("pixCopiaCola");
    copyText.select();
    copyText.setSelectionRange(0, 99999); 
    document.execCommand("copy");
    navigator.clipboard.writeText(copyText.value);
    alert("Chave pix 'Copia e Cola' copiada com sucesso!");
}   

function mostraqr() {
    if (document.getElementById('exibeqr').style.display == 'block'){
        document.getElementById('exibeqr').style.display = 'none';
        document.getElementById('btmqr').value="Mostrar QR Code";
    } else { 
        document.getElementById('exibeqr').style.display = "block";
        document.getElementById('btmqr').value="Ocultar QR Code";
    }
}

$(document).ready(function() {
    <?php if (!$expired && $order_data['payment_status'] != 'paid'): ?>
    var expiresAt = new Date('<?= date('c', strtotime($order_data['expires_at'])) ?>').getTime();
    var progressoMaximo = 100;
    
    var intervalo = setInterval(function() {
        var agora = new Date().getTime();
        var tempoRestante = Math.max(0, Math.floor((expiresAt - agora) / 1000));
        
        var minutos = Math.floor(tempoRestante / 60);
        var segundos = tempoRestante % 60;
        var tempoFormatado = minutos.toString().padStart(2, '0') + ':' + segundos.toString().padStart(2, '0');    
        $('#tempo-restante').text(tempoFormatado);
        
        var tempoTotal = 30 * 60; // 30 minutos em segundos
        var progresso = ((tempoTotal - tempoRestante) / tempoTotal) * progressoMaximo;
        $('#barra-progresso').css('width', progresso + '%').attr('aria-valuenow', progresso);
        
        if (tempoRestante <= 0) {
            clearInterval(intervalo);
            location.reload();
        }
    }, 1000);

    // Verificar status do pagamento a cada 3 segundos
    setInterval(function() {
        $.ajax({
            type: 'POST',
            url: '../classes/Main.php?action=check_payment_status',
            data: {order_token: '<?= $order_token ?>'},
            success: function(resp){
                var returnedData = JSON.parse(resp);
                if(returnedData.status == '2'){
                    location.reload();
                }
            }
        });
    }, 3000);

    // Botão "Já realizei o pagamento"
    window.addEventListener("load", function() {
        document.getElementById("check_payment").disabled = true;
        document.getElementById('timeLeft').innerHTML = 'O botão será liberado em alguns segundos.';
        setTimeout(function() {
            document.getElementById('timeLeft').innerHTML = '';
            $("#check_payment").removeAttr("disabled");
        }, 5000);
    })

    $("#check_payment").click(function() {
        $(this).attr("disabled", "disabled");
        $.ajax({
            type: 'POST',
            url: '../classes/Main.php?action=check_payment_status',
            data: {order_token: '<?= $order_token ?>'},
            success: function(resp){
                var returnedData = JSON.parse(resp);
                if(returnedData.status == '2'){
                    location.reload();
                } else {
                    alert('Pagamento ainda não foi confirmado. Aguarde alguns minutos.');
                }
            }
        });
        document.getElementById('timeLeft').innerHTML = 'O botão será liberado novamente em 60 segundos.';
        setTimeout(function() {
            document.getElementById('timeLeft').innerHTML = '';
            $("#check_payment").removeAttr("disabled");
        }, 60000);
    });
    <?php endif; ?>
});
</script>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>