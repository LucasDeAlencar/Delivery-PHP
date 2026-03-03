<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title><?= $this->renderSection('titulo') ?> - Restaurante</title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <link rel="icon" type="image/png" href="<?= site_url('logo.png') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= site_url('logo.png') ?>">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do" rel="stylesheet">

    <link rel="stylesheet" href="<?= site_url('web/src/css/open-iconic-bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= site_url('web/src/css/animate.css') ?>">
    
    <link rel="stylesheet" href="<?= site_url('web/src/css/owl.carousel.min.css') ?>">
    <link rel="stylesheet" href="<?= site_url('web/src/css/owl.theme.default.min.css') ?>">
    <link rel="stylesheet" href="<?= site_url('web/src/css/magnific-popup.css') ?>">

    <link rel="stylesheet" href="<?= site_url('web/src/css/aos.css') ?>">

    <link rel="stylesheet" href="<?= site_url('web/src/css/ionicons.min.css') ?>">

    <link rel="stylesheet" href="<?= site_url('web/src/css/bootstrap-datepicker.css') ?>">
    <link rel="stylesheet" href="<?= site_url('web/src/css/jquery.timepicker.css') ?>">

    
    <link rel="stylesheet" href="<?= site_url('web/src/css/flaticon.css') ?>">
    <link rel="stylesheet" href="<?= site_url('web/src/css/icomoon.css') ?>">
    <link rel="stylesheet" href="<?= site_url('web/src/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= site_url('assets/css/carrinho-modal.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/carrinho-popup.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/modal-fix.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/mobile-responsive.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/mobile-touch.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/about-center.css?v=' . time()) ?>">
    
    <!-- Estilos do carrinho navbar -->
    <style>
        .carrinho-navbar {
            transition: all 0.3s ease;
            margin-left: auto;
        }
        
        .carrinho-link:hover {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }
        
        .carrinho-icon-container {
            cursor: pointer;
        }
        
        .carrinho-counter {
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #f8b531 0%, #e6a429 100%);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(248, 181, 49, 0.4);
            animation: pulse 2s infinite;
            text-align: center;
            line-height: 20px;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .carrinho-counter.animate {
            animation: bounce 0.6s ease;
        }
        
        @keyframes bounce {
            0%, 20%, 60%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            80% { transform: translateY(-5px); }
        }
        
        /* Posicionamento responsivo */
        @media (max-width: 991px) {
            .container {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .carrinho-navbar {
                margin-left: 0;
                order: 3;
            }
            
            .navbar-toggler {
                order: 2;
                margin-left: auto;
                margin-right: 15px;
            }
        }
        
        /* Melhor espaçamento para telas pequenas */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 14px;
                margin-right: 5px;
                flex: 1;
                max-width: 60%;
            }
            
            .navbar-brand small {
                font-size: 9px;
                display: block;
            }
            
            .carrinho-navbar {
                margin-left: 5px;
                margin-right: 5px;
            }
            
            .carrinho-link i {
                font-size: 24px;
            }
            
            .navbar-toggler {
                padding: 2px 6px;
                margin-right: 5px;
            }
            
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 12px;
                max-width: 50%;
            }
            
            .navbar-brand small {
                font-size: 8px;
            }
            
            .carrinho-link i {
                font-size: 20px;
            }
            
            .carrinho-counter {
                font-size: 10px;
                min-width: 16px;
                height: 16px;
                line-height: 16px;
            }
        }
    </style>

    
    <!-- CSRF token para uso em requisições AJAX -->
    <?= csrf_meta('csrf_token_meta') ?>
    
    <!-- Estilos personalizados -->
    <?= $this->renderSection('estilos') ?>
  </head>
  <body>
  	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
		      <a class="navbar-brand" href="<?= site_url('/') ?>"><span class="fas fa-hotdog mr-1"></span>Delicias MV<br><small class="d-none d-md-inline">Seu delivery favorito</small></a>
		      
		      <!-- Ícone do Carrinho independente -->
		      <div class="carrinho-navbar d-flex align-items-center">
		          <a href="#" onclick="CarrinhoSimples.mostrar(); return false;" 
		             class="carrinho-link d-flex align-items-center text-decoration-none">
		              <div class="carrinho-icon-container position-relative">
		                  <i id="carrinho-icon" class="fas fa-shopping-cart text-warning" style="font-size: 28px;"></i>
		                  <span id="carrinho-badge" class="carrinho-counter position-absolute" style="display: none;">0</span>
		              </div>
		          </a>
		      </div>
		      
		      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
		        <span class="oi oi-menu"></span> Menu
		      </button>
	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item active"><a href="<?= site_url('/') ?>" class="nav-link">Home</a></li>
	          <li class="nav-item"><a href="#menu" class="nav-link">Menu</a></li>
	          <li class="nav-item"><a href="#services" class="nav-link">Serviços</a></li>
	          <li class="nav-item"><a href="#about" class="nav-link">Sobre</a></li>
	          <li class="nav-item"><a href="#contact" class="nav-link">Contato</a></li>
	        </ul>
	      </div>
		  </div>
	  </nav>
    <!-- END nav -->

    <!-- Hero Section -->
    <section class="home-slider owl-carousel img" style="background-image: url('web/src/images/WSalgados.jpg');">
      <div class="slider-item">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text align-items-center" data-scrollax-parent="true">

            <div class="col-md-6 col-sm-12 ftco-animate">
            	<span class="subheading">Irresistível</span>
              <h1 class="mb-4">Bolinhas de Presunto com Queijo</h1>
              <p class="mb-4 mb-md-5">A clássica receita da casa, com recheio de presunto e queijo que derrete na boca. Perfeito para degustar em um final de semana.</p>
              <p><a href="#menu" class="btn btn-primary p-3 px-xl-4 py-xl-3">Fazer Pedido</a> <a href="#about" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Ver Home</a></p>
            </div>
            <div class="col-md-6 ftco-animate">
            	<img src="<?= base_url('web/src/images/BolinhaDePresunto-1.png') ?>" class="img-fluid" alt="Sonho de Valsa">
            </div>

          </div>
        </div>
      </div>

      <div class="slider-item">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text align-items-center" data-scrollax-parent="true">

            <div class="col-md-6 col-sm-12 order-md-last ftco-animate">
            	<span class="subheading">Saboroso</span>
              <h1 class="mb-4">Coxinha da casa</h1>
              <p class="mb-4 mb-md-5">Recheada com frango e catupiry. Uma combinação fantastica de sabor</p>
              <p><a href="#menu" class="btn btn-primary p-3 px-xl-4 py-xl-3">Fazer Pedido</a> <a href="#about" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Ver Home</a></p>
            </div>
            <div class="col-md-6 ftco-animate">
            	<img src="<?= base_url('web/src/images/Coxinha.png') ?>" class="img-fluid" alt="Serenata de Amor">
            </div>

          </div>
        </div>
      </div>

      <div class="slider-item" style="background-image: url('web/src/images/WSalgados-2.jpg');">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

            <div class="col-md-7 col-sm-12 text-center ftco-animate">
            	<span class="subheading">Bem-vindo</span>
              <h1 class="mb-4">Salgados para todos os momentos</h1>
              <p class="mb-4 mb-md-5">Coxinhas, quibes, empadas e muito mais. Tudo para temperar seu dia com qualidade e sabor.</p>
              <p><a href="#menu" class="btn btn-primary p-3 px-xl-4 py-xl-3">Fazer Pedido</a> <a href="#about" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Ver Home</a></p>
            </div>

          </div>
        </div>
      </div>
    </section>

    <!-- Contact Info Section -->
    <section class="ftco-intro">
    	<div class="container-wrap">
    		<div class="wrap d-md-flex">
	    		<div class="info">
	    			<div class="row no-gutters">
	    				<div class="col-md-4 d-flex ftco-animate">
	    					<div class="icon"><span class="icon-phone"></span></div>
	    					<div class="text">
	    						<h3><?= $dadosCorporativos->numero ?? '(11) 9999-9999' ?></h3>
	    						<p>Faça seu pedido por telefone</p>
	    					</div>
	    				</div>
	    				<div class="col-md-4 d-flex ftco-animate">
	    					<div class="icon"><span class="icon-my_location"></span></div>
	    					<div class="text">
	    						<h3><?= $dadosCorporativos->endereco ?? 'Rua das Flores, 123' ?></h3>
	    						<p>Centro - São Paulo - SP</p>
	    					</div>
	    				</div>
	    				<div class="col-md-4 d-flex ftco-animate">
	    					<div class="icon"><span class="icon-clock-o"></span></div>
	    					<div class="text">
	    						<?php if (isset($estaAberto) && isset($expedienteHoje)): ?>
	    							<?php if ($estaAberto): ?>
	    								<h3 style="color: #28a745;">✅ Aberto Agora</h3>
	    							<?php elseif ($expedienteHoje && $expedienteHoje->situacao == 1): ?>
	    								<h3 style="color: #ffc107;">⏰ Fechado no Momento</h3>
	    							<?php else: ?>
	    								<h3 style="color: #dc3545;">🚫 Fechado Hoje</h3>
	    							<?php endif; ?>
	    							<?php if ($expedienteHoje && $expedienteHoje->situacao == 1): ?>
	    								<p><?= esc($expedienteHoje->dia_descricao) ?>: <?= substr($expedienteHoje->abertura, 0, 5) ?> - <?= substr($expedienteHoje->fechamento, 0, 5) ?></p>
	    							<?php else: ?>
	    								<p>Veja nossos horários abaixo</p>
	    							<?php endif; ?>
	    						<?php else: ?>
	    							<h3>Horário de Atendimento</h3>
	    							<p>Consulte nossos horários</p>
	    						<?php endif; ?>
	    					</div>
	    				</div>
	    			</div>
	    		</div>
	    		<div class="social d-md-flex pl-md-5 p-4 align-items-center">
	    			<ul class="social-icon">
              <li class="ftco-animate"><a href="<?= !empty($dadosCorporativos->whatsapp) ? 'https://wa.me/55' . preg_replace('/\D/', '', $dadosCorporativos->whatsapp) . '?text=Olá! Gostaria de fazer um pedido.' : '#' ?>" target="_blank"><span class="icon-whatsapp"></span></a></li>
              <li class="ftco-animate"><a href="<?= !empty($dadosCorporativos->facebook) ? 'https://facebook.com/' . $dadosCorporativos->facebook : '#' ?>" target="_blank"><span class="icon-facebook"></span></a></li>
              <li class="ftco-animate"><a href="<?= !empty($dadosCorporativos->instagram) ? 'https://instagram.com/' . ltrim($dadosCorporativos->instagram, '@') : '#' ?>" target="_blank"><span class="icon-instagram"></span></a></li>
            </ul>
	    		</div>
    		</div>
    	</div>
    </section>

    <!-- About Section -->
    <section class="ftco-about d-md-flex" id="about">
    	<div class="one-half img" style="background-image: url(<?= site_url('web/src/images/about.jpg') ?>);"></div>
    	<div class="one-half ftco-animate">
        <div class="heading-section ftco-animate ">
          <h2 class="mb-4">Bem-vindo à <span class="fas fa-hotdog">Nossa</span> Salgadaria</h2>
        </div>
        <div>
  				<p>Há mais de 20 anos temperando momentos especiais com os melhores salgados, assados e petiscos selecionados. Nossa paixão por proporcionar satisfação se reflete em cada recheio que oferecemos, sempre buscando trazer sabor e alegria aos nossos clientes</p>
  			</div>
    	</div>
    </section>

    <!-- Services Section -->
    <section class="ftco-section ftco-services" id="services">
    	<div class="overlay"></div>
    	<div class="container">
    		<?= $this->include('Home/servicos') ?>
    	</div>
    </section>

    <!-- Menu Section -->
    <section class="ftco-section" id="menu">
    	<div class="container">
        <!-- Conteúdo dinâmico do menu -->
        <?= $this->renderSection('menu_dinamico') ?>
        
    	</div>
    </section>

    <!-- Gallery Section -->
    <section class="ftco-gallery">
    	<div class="container-wrap">
    		<div class="row no-gutters">
					<div class="col-md-6 ftco-animate">
						<a href="#" class="gallery img d-flex align-items-center" style="background-image: url('web/src/images/WSalgados.jpg');">
							<div class="icon mb-4 d-flex align-items-center justify-content-center">
    						<span class="icon-search"></span>
    					</div>
						</a>
					</div>
					<div class="col-md-6 ftco-animate">
						<a href="#" class="gallery img d-flex align-items-center" style="background-image: url('web/src/images/WSalgados-2.jpg');">
							<div class="icon mb-4 d-flex align-items-center justify-content-center">
    						<span class="icon-search"></span>
    					</div>
						</a>
					</div>
        </div>
    	</div>
    </section>

    <!-- Contact Section -->
		<section class="ftco-appointment" id="contact">
			<div class="overlay"></div>
    	<div class="container-wrap">
    		<div class="row no-gutters d-md-flex align-items-start">
    			<div class="col-md-6 d-flex align-self-stretch">
    				<div class="map-wrapper" style="width: 100%; height: 450px; position: relative; border-radius: 10px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.3);">
    					<div class="map-overlay align-self-stretch " style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(45deg, rgba(248,181,49,0.1), rgba(0,0,0,0.1)); pointer-events: none; z-index: 1;"></div>
    					<iframe 
    						src="https://www.google.com/maps?q=<?= urlencode(($dadosCorporativos->endereco ?? 'Rua das S, 123 - Centro') . ', ' . ($dadosCorporativos->cep ?? '30000-000')) ?>&output=embed"
    						width="100%" 
    						height="100%" 
    						style="border:0; filter: contrast(1.1) saturate(0.8) brightness(0.9);" 
    						allowfullscreen="" 
    						loading="lazy" 
    						referrerpolicy="no-referrer-when-downgrade">
    					</iframe>
    					<div class="map-badge" style="position: absolute; bottom: 15px; left: 15px; background: rgba(248,181,49,0.95); color: #1a1a1a; padding: 8px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; z-index: 2; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
    						<i class="fas fa-map-marker-alt mr-1"></i>
    						Nossa Localização
    					</div>
    				</div>
    			</div>
	    		<div class="col-md-6 appointment ftco-animate">
	    			<h3 class="mb-3">Entre em Contato</h3>
	    			<form id="formContato" class="appointment-form">
	    				<div class="d-md-flex">
		    				<div class="form-group">
		    					<input type="text" id="nomeContato" class="form-control" placeholder="Nome completo *" required>
		    				</div>
	    				</div>
	    				<div class="d-me-flex">
	    					<div class="form-group">
		    					<input type="email" id="emailContato" class="form-control" placeholder="Seu email *" required>
		    				</div>
	    				</div>
	    				<div class="d-me-flex">
	    					<div class="form-group">
		    					<input type="text" id="telefoneContato" class="form-control" placeholder="Telefone (00) 00000-0000 *" required>
		    				</div>
	    				</div>
	    				<div class="form-group">
	              <textarea id="mensagemContato" cols="30" rows="3" class="form-control" placeholder="Como podemos ajudá-lo? Descreva sua dúvida ou pedido... *" required></textarea>
	            </div>
	            <div class="form-group">
	              <button type="submit" class="btn btn-primary py-3 px-4">
	                  <i class="fas fa-envelope mr-2"></i>Enviar por Whatsapp
	              </button>
	            </div>
	    			</form>
	    		</div>    			
    		</div>
    		
    		<script>
    		// Máscara para telefone
    		document.getElementById('telefoneContato').addEventListener('input', function(e) {
    		    let value = e.target.value.replace(/\D/g, '');
    		    if (value.length <= 11) {
    		        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    		        if (value.length < 14) {
    		            value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    		        }
    		    }
    		    e.target.value = value;
    		});
    		
    		document.getElementById('formContato').addEventListener('submit', function(e) {
    		    e.preventDefault();
    		    
    		    const nome = document.getElementById('nomeContato').value.trim();
    		    const email = document.getElementById('emailContato').value.trim();
    		    const telefone = document.getElementById('telefoneContato').value.trim();
    		    const mensagem = document.getElementById('mensagemContato').value.trim();
    		    
    		    // Validações
    		    if (!nome) {
    		        alert('⚠️ Por favor, informe seu nome completo');
    		        return;
    		    }
    		    
    		    if (!email) {
    		        alert('⚠️ Por favor, informe seu email');
    		        return;
    		    }
    		    
    		    if (!telefone) {
    		        alert('⚠️ Por favor, informe seu telefone');
    		        return;
    		    }
    		    
    		    if (!mensagem) {
    		        alert('⚠️ Por favor, descreva sua mensagem');
    		        return;
    		    }
    		    
    		    // Montar mensagem para WhatsApp
    		    const whatsappNumero = '<?= preg_replace('/\D/', '', $dadosCorporativos->whatsapp ?? '') ?>';
    		    
    		    if (!whatsappNumero) {
    		        alert('❌ WhatsApp não configurado. Entre em contato pelo telefone.');
    		        return;
    		    }
    		    
    		    const textoWhatsApp = `*Contato via Site*%0A%0A` +
    		        `*Nome:* ${encodeURIComponent(nome)}%0A` +
    		        `*Email:* ${encodeURIComponent(email)}%0A` +
    		        `*Telefone:* ${encodeURIComponent(telefone)}%0A%0A` +
    		        `*Mensagem:*%0A${encodeURIComponent(mensagem)}`;
    		    
    		    // Abrir WhatsApp
    		    window.open(`https://wa.me/55${whatsappNumero}?text=${textoWhatsApp}`, '_blank');
    		    
    		    // Limpar formulário
    		    this.reset();
    		});
    		</script>
    	</div>
    </section>

    <!-- Footer -->
    <footer class="ftco-footer ftco-section img">
    	<div class="overlay"></div>
      <div class="container">
        <div class="row mb-5">
          <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Sobre Nós</h2>
              <p>Restaurante especializado em culinária italiana, oferecendo pratos tradicionais com ingredientes frescos e de qualidade.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="<?= !empty($dadosCorporativos->whatsapp) ? 'https://wa.me/55' . preg_replace('/\D/', '', $dadosCorporativos->whatsapp) . '?text=Olá! Gostaria de fazer um pedido.' : '#' ?>" target="_blank"><span class="icon-whatsapp"></span></a></li>
                <li class="ftco-animate"><a href="<?= !empty($dadosCorporativos->facebook) ? 'https://facebook.com/' . $dadosCorporativos->facebook : '#' ?>" target="_blank"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="<?= !empty($dadosCorporativos->instagram) ? 'https://instagram.com/' . ltrim($dadosCorporativos->instagram, '@') : '#' ?>" target="_blank"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-5 mb-md-5">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Horário de Funcionamento</h2>
              <div class="opening-hours">
                <?php if (isset($expedientes) && !empty($expedientes)): ?>
                  <?php 
                    $diasSemana = [
                      0 => 'Domingo',
                      1 => 'Segunda-feira',
                      2 => 'Terça-feira',
                      3 => 'Quarta-feira',
                      4 => 'Quinta-feira',
                      5 => 'Sexta-feira',
                      6 => 'Sábado'
                    ];
                    $diaAtual = date('w');
                  ?>
                  <?php foreach ($expedientes as $exp): ?>
                    <p class="pl-3" style="<?= $exp->dia == $diaAtual ? 'font-weight: bold; color: #f8b531;' : '' ?>">
                      <span class="<?= $exp->dia == $diaAtual ? 'text-warning' : '' ?>">
                        <?= $exp->dia == $diaAtual ? '➡️ ' : '' ?>
                        <?= esc($exp->dia_descricao) ?>: 
                        <?php if ($exp->situacao == 1): ?>
                          <?= substr($exp->abertura, 0, 5) ?> - <?= substr($exp->fechamento, 0, 5) ?>
                        <?php else: ?>
                          <span style="color: #dc3545;">Fechado</span>
                        <?php endif; ?>
                      </span>
                    </p>
                  <?php endforeach; ?>
                <?php else: ?>
                  <h4>Dias da Semana:</h4>
                  <p class="pl-3">
                    <span>Consulte nossos horários</span>
                  </p>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-md-6 mb-5 mb-md-5">
             <div class="ftco-footer-widget mb-4 ml-md-4">
              <h2 class="ftco-heading-2">Serviços</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">Delivery</a></li>
                <li><a href="#" class="py-2 d-block">Balcão</a></li>
                <li><a href="#" class="py-2 d-block">Eventos</a></li>
                <li><a href="#" class="py-2 d-block">Catering</a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">Tem Dúvidas?</h2>
            	<div class="block-23 mb-3">
	              <ul>
	                <li><span class="icon icon-map-marker"></span><span class="text"><?= $dadosCorporativos->endereco ?? 'Rua das Flores, 123 - Centro, São Paulo - SP' ?></span></li>
	                <li><a href="#"><span class="icon icon-phone"></span><span class="text"><?= $dadosCorporativos->numero ?? '(11) 9999-9999' ?></span></a></li>
	                <li><a href="#"><span class="icon icon-envelope"></span><span class="text"><?= $dadosCorporativos->email ?? 'contato@nokapricho.com' ?></span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">
            <p>&copy; <?= date('Y') ?> Restaurante. Todos os direitos reservados.</p>
          </div>
        </div>
      </div>
    </footer>
    
    <!-- Modal do Carrinho -->
    <div class="modal fade" id="modalCarrinho" tabindex="-1" role="dialog" aria-labelledby="modalCarrinhoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background: #1a1a1a; border: 1px solid #333; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                <div class="modal-header" style="background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%); border-bottom: 1px solid #333; border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title text-warning" id="modalCarrinhoLabel" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                        <i class="fas fa-shopping-cart mr-2"></i>Meu Carrinho
                        <span class="badge badge-warning ml-2" id="modal-carrinho-total-itens">0 itens</span>
                    </h5>
                    <button type="button" class="btn btn-outline-light" onclick="fecharCarrinhoEVoltarMenu()" style="font-weight: 600;">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar ao Menu
                    </button>
                </div>
                <div class="modal-body" id="modal-carrinho-body" style="background: #1a1a1a; max-height: 60vh; overflow-y: auto; padding: 20px;">
                    <!-- Conteúdo será preenchido via JavaScript -->
                </div>
                <div class="modal-footer" style="background: #1a1a1a; border-top: 1px solid #333; border-radius: 0 0 15px 15px;">
                    <div class="w-100">
                        <!-- Resumo do Total -->
                        <div class="carrinho-resumo-total mb-3 p-3 rounded" style="background: #2d2d2d; border: 1px solid #333;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-light" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Total do Pedido:</span>
                                <strong id="modal-carrinho-total" class="h4 text-warning mb-0" style="font-family: 'Poppins', sans-serif; font-weight: 700;">R$ 0,00</strong>
                            </div>
                        </div>
                        
                        <!-- Botões de Ação -->
                        <div class="row" style="margin: 0;">
                            <div class="col-12 col-md-6 mb-2 mb-md-0" style="padding: 0 5px;">
                                <button type="button" class="btn btn-outline-danger w-100" id="btn-limpar-carrinho" style="border: 2px solid #dc3545; color: #dc3545; font-weight: 600; transition: all 0.3s ease;">
                                    <i class="fas fa-trash mr-2"></i>Limpar Carrinho
                                </button>
                            </div>
                            <div class="col-12 col-md-6" style="padding: 0 5px;">
                                <button type="button" class="btn btn-warning w-100" id="btn-finalizar-pedido" style="background: linear-gradient(135deg, #f8b531 0%, #fac56e 100%); border: none; color: #000; font-weight: 600; font-family: 'Poppins', sans-serif;">
                                    <i class="fas fa-check-circle mr-2"></i>Finalizar Pedido
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

    <!-- Popup de notificação de scroll -->
    <div id="scroll-popup">Posição restaurada!</div>

    <!-- Scripts -->
    <script src="<?= site_url('web/src/js/auth-check.js') ?>"></script>
    <script src="<?= site_url('web/src/js/jquery.min.js') ?>"></script>
    <script src="<?= site_url('web/src/js/jquery-migrate-3.0.1.min.js') ?>"></script>
    <script src="<?= site_url('web/src/js/popper.min.js') ?>"></script>
    <script src="<?= site_url('web/src/js/bootstrap.min.js') ?>"></script>
    <script src="<?= site_url('web/src/js/jquery.easing.1.3.js') ?>"></script>
    <script src="<?= site_url('web/src/js/jquery.waypoints.min.js') ?>"></script>
    <script src="<?= site_url('web/src/js/jquery.stellar.min.js') ?>"></script>
    <script src="<?= site_url('web/src/js/owl.carousel.min.js') ?>"></script>
    <script src="<?= site_url('web/src/js/jquery.magnific-popup.min.js') ?>"></script>
    <script src="<?= site_url('web/src/js/aos.js') ?>"></script>
    <script src="<?= site_url('web/src/js/jquery.animateNumber.min.js') ?>"></script>
    <script src="<?= site_url('web/src/js/bootstrap-datepicker.js') ?>"></script>
    <script src="<?= site_url('web/src/js/jquery.timepicker.min.js') ?>"></script>
    <script src="<?= site_url('web/src/js/scrollax.min.js') ?>"></script>
    <script src="<?= site_url('web/src/js/main.js') ?>"></script>
    <script src="<?= site_url('web/src/js/ajax-config.js?v=' . time()) ?>"></script>
    <script src="<?= site_url('assets/js/sistema-produto.js?v=' . time()) ?>"></script>
    <script src="<?= site_url('assets/js/carrinho-simples.js?v=' . time()) ?>"></script>
    <script src="<?= site_url('assets/js/finalizar-pedido.js?v=' . time()) ?>"></script>
    
    <script>
    // Controlar ícone do carrinho baseado no status do pedido
    $(document).ready(function() {
        // === SISTEMA DE FILTROS ===
        
        function filtrarProdutos(categoria) {
            const produtos = document.querySelectorAll('.filtr-item');
            
            produtos.forEach(function(produto) {
                const categoriaProduto = produto.getAttribute('data-category');
                
                if (categoria === 'all' || categoriaProduto === categoria) {
                    produto.style.display = 'block';
                } else {
                    produto.style.display = 'none';
                }
            });
        }

        const botoesFiltro = document.querySelectorAll('.filter-button');
        
        botoesFiltro.forEach(function(botao) {
            const categoria = botao.getAttribute('data-filter');
            
            botao.addEventListener('click', function(e) {
                e.preventDefault();
                
                document.querySelectorAll('.menu_filter li').forEach(li => li.classList.remove('active'));
                this.parentElement.classList.add('active');
                
                const categoria = this.getAttribute('data-filter');
                filtrarProdutos(categoria);
            });
        });
        
        // Mostrar todos inicialmente
        filtrarProdutos('all');
        
        // === ÍCONE DO CARRINHO ===
        function atualizarIconeCarrinho() {
            const pedidoEmAndamento = localStorage.getItem('pedido_em_andamento');
            const carrinhoIcon = $('#carrinho-icon');
            const emailCliente = localStorage.getItem('cliente_email');
            
            if (pedidoEmAndamento) {
                // Verificar status do pedido
                $.ajax({
                    url: `/acompanhar-pedido/${pedidoEmAndamento}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const status = response.pedido.status;
                            if (status === 'entregue' || status === 'cancelado') {
                                // Pedido finalizado, voltar ao ícone normal
                                localStorage.removeItem('pedido_em_andamento');
                                carrinhoIcon.removeClass('fa-box-open fa-truck').addClass('fa-shopping-cart').removeClass('text-info text-success').addClass('text-warning');
                            } else {
                                // Verificar se o pedido pertence ao cliente logado
                                let pedidoPertence = false;
                                const emailClienteLogado = localStorage.getItem('cliente_email');
                                
                                if (emailClienteLogado && response.pedido.email_cliente) {
                                    // Ambos têm email - comparar
                                    pedidoPertence = (response.pedido.email_cliente.toLowerCase() === emailClienteLogado.toLowerCase());
                                } else if (!emailClienteLogado && !response.pedido.email_cliente) {
                                    // Nenhum tem email - permitir por compatibilidade
                                    pedidoPertence = true;
                                } else if (emailClienteLogado && !response.pedido.email_cliente) {
                                    // Cliente logado mas pedido não tem email - não permitir
                                    pedidoPertence = false;
                                }
                                
                                if (!pedidoPertence) {
                                    // Pedido não pertence ao cliente, limpar
                                    localStorage.removeItem('pedido_em_andamento');
                                    localStorage.removeItem('codigo_pedido_ativo');
                                    carrinhoIcon.removeClass('fa-box-open fa-truck').addClass('fa-shopping-cart').removeClass('text-info text-success').addClass('text-warning');
                                } else {
                                    // Pedido em andamento e pertence ao cliente, alterar ícone
                                    const iconClass = status === 'saiu_entrega' ? 'fa-truck' : 'fa-box-open';
                                    const colorClass = status === 'saiu_entrega' ? 'text-success' : 'text-info';
                                    carrinhoIcon.removeClass('fa-shopping-cart fa-box-open fa-truck text-warning text-info text-success').addClass(iconClass).addClass(colorClass);
                                }
                            }
                        } else {
                            // Pedido não encontrado, remover do localStorage
                            localStorage.removeItem('pedido_em_andamento');
                            carrinhoIcon.removeClass('fa-box-open fa-truck').addClass('fa-shopping-cart').removeClass('text-info text-success').addClass('text-warning');
                        }
                    },
                    error: function() {
                        // Erro na consulta, manter ícone normal
                        carrinhoIcon.removeClass('fa-box-open fa-truck').addClass('fa-shopping-cart').removeClass('text-info text-success').addClass('text-warning');
                    }
                });
            } else {
                // Sem pedido em andamento, ícone normal
                carrinhoIcon.removeClass('fa-box-open fa-truck').addClass('fa-shopping-cart').removeClass('text-info text-success').addClass('text-warning');
            }
        }
        
        // Atualizar ícone ao carregar a página
        atualizarIconeCarrinho();
        
        // Atualizar ícone a cada 30 segundos
        setInterval(atualizarIconeCarrinho, 30000);
        
        // === SISTEMA DE RESTAURAÇÃO DE SCROLL ===
        // Restaurar posição 0.5 segundos após modal abrir
        $(document).on('shown.bs.modal', '#modalCompra, #modalExtras', function() {
            setTimeout(() => {
                if (window.__scrollPos !== undefined) {
                    window.scrollTo(0, window.__scrollPos);
                }
            }, 500);
        });
        
        // Restaurar posição quando modal fechar
        $(document).on('hidden.bs.modal', '#modalCompra, #modalExtras', function() {
            if (window.__scrollPos !== undefined) {
                window.scrollTo(0, window.__scrollPos);
            }
        });
    });
    </script>
    
    <style>
        /* Backdrop com blur de 80% */
        .modal-backdrop {
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            background-color: rgba(0, 0, 0, 0.8) !important;
        }
        
        /* Evitar scroll automático nos modais */
        .modal {
            overflow-y: auto !important;
        }
        .modal.fade .modal-dialog {
            transform: translateY(0) !important;
        }
        .modal.show .modal-dialog {
            transform: translateY(0) !important;
        }
        .modal-open {
            padding-right: 0 !important;
            overflow: auto !important;
        }
        
        /* Popup denotificação de posição */
        #scroll-popup {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(248, 181, 49, 0.95);
            color: #000;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            z-index: 10000;
            display: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            animation: fadeInOut 0.5s ease-out;
            font-size: 14px;
            text-align: center;
            max-width: 90%;
        }
        
        @media (max-width: 480px) {
            #scroll-popup {
                top: 10px;
                padding: 10px 16px;
                font-size: 13px;
                border-radius: 20px;
            }
        }
        
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            20% { opacity: 1; transform: translateX(-50%) translateY(0); }
            80% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        }
        
        /* Animação slide up para mobile */
        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        
        /* Estilos responsivos para modais */
        @media (max-width: 768px) {
            .modal-dialog {
                max-width: 90%;
                margin: 10px auto;
            }
            
            .modal-content {
                border-radius: 15px;
            }
            
            .modal-body {
                padding: 15px;
            }
            
            .modal-header, .modal-footer {
                padding: 12px 15px;
            }
            
            #scroll-popup {
                top: 10px;
                padding: 10px 20px;
                font-size: 14px;
                width: auto;
                max-width: 90%;
            }
        }
        
        @media (max-width: 480px) {
            .modal-dialog {
                max-width: 100%;
                margin: 0;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
            }
            
            .modal-content {
                max-height: 90vh;
                border-radius: 20px 20px 0 0;
                overflow-y: auto;
            }
            
            .modal.show .modal-dialog {
                transform: translateY(0) !important;
            }
            
            .modal-header {
                position: sticky;
                top: 0;
                background: #1a1a1a;
                z-index: 1;
            }
            
            .modal-footer {
                position: sticky;
                bottom: 0;
                background: #1a1a1a;
                z-index: 1;
            }
            
            #scroll-popup {
                top: 15px;
                padding: 8px 16px;
                font-size: 13px;
            }
        }
        
        /* Controles de quantidade dos extras */
        .extra-controles {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-qty {
            width: 30px;
            height: 30px;
            border: 1px solid #f8b531;
            background: transparent;
            color: #f8b531;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
        }
        
        .btn-qty:hover {
            background: #f8b531;
            color: #000;
        }
        
        .qty-valor {
            color: #f8b531;
            font-weight: bold;
            min-width: 20px;
            text-align: center;
        }
    </style>
    
    <!-- Indicador de cliente logado -->
    <div id="cliente-logado" style="position: fixed; bottom: 20px; right: 20px; background: rgba(0,0,0,0.8); color: #f8b531; padding: 10px 15px; border-radius: 25px; font-size: 14px; cursor: pointer; z-index: 1000; display: none; border: 1px solid #f8b531;">
        <i class="fas fa-user-circle mr-2"></i>
        <span id="email-cliente"></span>
        <div id="menu-logout" style="position: absolute; bottom: 100%; right: 0; background: rgba(0,0,0,0.9); border: 1px solid #f8b531; border-radius: 8px; padding: 10px; margin-bottom: 5px; display: none; white-space: nowrap;">
            <a href="#" id="btn-editar" style="color: #f8b531; text-decoration: none; font-size: 13px; display: block; margin-bottom: 8px;">
                <i class="fas fa-edit mr-2"></i>Editar Dados
            </a>
            <a href="#" id="btn-logout" style="color: #ff6b6b; text-decoration: none; font-size: 13px;">
                <i class="fas fa-sign-out-alt mr-2"></i>Sair
            </a>
        </div>
    </div>

    <!-- Modal de Edição de Dados -->
    <div class="modal fade" id="modalEdicaoCliente" tabindex="-1" role="dialog" aria-labelledby="modalEdicaoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="background: #1a1a1a; border: 1px solid #333; margin: 10px;">
                <div class="modal-header" style="border-bottom: 1px solid #333;">
                    <h5 class="modal-title" id="modalEdicaoLabel" style="color: #f8b531;">
                        <i class="fas fa-edit mr-2"></i>Editar Meus Dados
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: #fff; max-height: 60vh; overflow-y: auto;">
                    <form id="formEdicaoCliente">
                        <div class="form-group">
                            <label style="color: #f8b531;">Nome Completo *</label>
                            <input type="text" id="edit-nome" class="form-control" readonly style="background: #1a1a1a; border: 1px solid #444; color: #ccc;">
                        </div>
                        
                        <div class="form-group">
                            <label style="color: #f8b531;">Telefone *</label>
                            <input type="tel" id="edit-telefone" class="form-control" required style="background: #2d2d2d; border: 1px solid #444; color: #fff;">
                        </div>
                        
                        <div class="form-group">
                            <label style="color: #f8b531;">CEP *</label>
                            <input type="text" id="edit-cep" class="form-control" required style="background: #2d2d2d; border: 1px solid #444; color: #fff;">
                        </div>
                        
                        <div class="form-group">
                            <label style="color: #f8b531;">Cidade *</label>
                            <input type="text" id="edit-cidade" class="form-control" readonly placeholder="Campo autopreenchido pelo CEP" style="background: #1a1a1a; border: 1px solid #444; color: #ccc;">
                        </div>
                        
                        <div class="form-group">
                            <label style="color: #f8b531;">Bairro *</label>
                            <input type="text" id="edit-bairro" class="form-control" readonly placeholder="Campo autopreenchido pelo CEP" style="background: #1a1a1a; border: 1px solid #444; color: #ccc;">
                        </div>
                        
                        <div class="form-group">
                            <label style="color: #f8b531;">Logradouro *</label>
                            <input type="text" id="edit-endereco" class="form-control" readonly placeholder="Campo autopreenchido pelo CEP" style="background: #1a1a1a; border: 1px solid #444; color: #ccc;">
                        </div>
                        
                        <div class="form-group">
                            <label style="color: #f8b531;">Número</label>
                            <input type="text" id="edit-numero" class="form-control" style="background: #2d2d2d; border: 1px solid #444; color: #fff;">
                        </div>
                        
                        <div class="form-group">
                            <label style="color: #f8b531;">Complemento</label>
                            <input type="text" id="edit-complemento" class="form-control" style="background: #2d2d2d; border: 1px solid #444; color: #fff;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #333; padding: 10px 15px;">
                    <div class="d-flex flex-column flex-sm-row w-100">
                        <button type="button" class="btn btn-secondary mb-2 mb-sm-0 mr-sm-2 flex-fill" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn flex-fill" id="btnSalvarEdicao" style="background: #f8b531; color: #000;">
                            <i class="fas fa-save mr-2"></i>Salvar Alterações
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Verificar se há cliente logado via sessão PHP (apenas no primeiro carregamento após login)
        <?php if (session()->has('cliente_email')): ?>
            var novoEmailCliente = '<?= session()->get('cliente_email') ?>';
            
            // Pegar o email anterior que estava salvo
            // Prioridade: cliente_email (se ainda existir), depois ultimo_email_logout
            var emailAnterior = localStorage.getItem('cliente_email') || localStorage.getItem('ultimo_email_logout');
            
            // Salvar o novo e-mail
            localStorage.setItem('cliente_email', novoEmailCliente);
            
            // Atualizar o ultimo_email_logout com o email atual para futuras comparações
            localStorage.setItem('ultimo_email_logout', novoEmailCliente);
            
            // Se mudou de usuário (login diferente E temos referência do usuário anterior), limpar pedidos anteriores
            // Se não temos referência anterior (navegador foi fechado), mantemos o pedido por segurança
            if (emailAnterior && emailAnterior.toLowerCase() !== novoEmailCliente.toLowerCase()) {
                localStorage.removeItem('pedido_em_andamento');
                localStorage.removeItem('codigo_pedido_ativo');
            }
            
            console.log('Login detectado:', { novo: novoEmailCliente, anterior: emailAnterior, mudou: emailAnterior && emailAnterior.toLowerCase() !== novoEmailCliente.toLowerCase() });
            <?php session()->remove('cliente_email'); ?>
        <?php endif; ?>

        // Gerenciar cliente logado
        document.addEventListener('DOMContentLoaded', function() {
            const clienteLogado = document.getElementById('cliente-logado');
            const emailCliente = document.getElementById('email-cliente');
            const menuLogout = document.getElementById('menu-logout');
            const btnLogout = document.getElementById('btn-logout');
            const btnEditar = document.getElementById('btn-editar');
            
            // Verificar se há cliente logado
            const emailSalvo = localStorage.getItem('cliente_email');
            if (emailSalvo) {
                emailCliente.textContent = emailSalvo;
                clienteLogado.style.display = 'block';
            }
            
            // Toggle menu logout
            clienteLogado.addEventListener('click', function(e) {
                e.stopPropagation();
                menuLogout.style.display = menuLogout.style.display === 'block' ? 'none' : 'block';
            });
            
            // Fechar menu ao clicar fora
            document.addEventListener('click', function() {
                menuLogout.style.display = 'none';
            });
            
            // Editar dados
            btnEditar.addEventListener('click', function(e) {
                e.preventDefault();
                menuLogout.style.display = 'none';
                abrirModalEdicao();
            });
            
            // Logout
            btnLogout.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Salvar o email do usuário que está fazendo logout APENAS se não existir um anterior
                // Isso mantém referência ao "usuário anterior" para comparação no próximo login
                const emailAtual = localStorage.getItem('cliente_email');
                if (emailAtual && !localStorage.getItem('ultimo_email_logout')) {
                    localStorage.setItem('ultimo_email_logout', emailAtual);
                }
                
                // Limpar dados do localStorage
                localStorage.removeItem('cliente_email');
                localStorage.removeItem('carrinho');
                
                // Atualizar a página (F5)
                window.location.reload();
            });
            
            // Função para abrir modal de edição
            function abrirModalEdicao() {
                const email = localStorage.getItem('cliente_email');
                if (!email) return;
                
                // Buscar dados do cliente
                fetch('<?= site_url('cliente/dados') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({email: email})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        // Preencher formulário
                        document.getElementById('edit-nome').value = data.cliente.nome || '';
                        document.getElementById('edit-telefone').value = data.cliente.telefone || '';
                        document.getElementById('edit-cep').value = data.cliente.cep || '';
                        document.getElementById('edit-cidade').value = data.cliente.Cidade || '';
                        document.getElementById('edit-bairro').value = data.cliente.Bairro || '';
                        document.getElementById('edit-endereco').value = data.cliente.Endereco || '';
                        document.getElementById('edit-numero').value = data.cliente.Numero || '';
                        document.getElementById('edit-complemento').value = data.cliente.complemento || '';
                        
                        $('#modalEdicaoCliente').modal('show');
                    } else {
                        alert('Erro ao carregar dados do cliente');
                    }
                })
                .catch(error => {
                    alert('Erro ao carregar dados');
                });
            }
            
            // Salvar edição
            document.getElementById('btnSalvarEdicao').addEventListener('click', function() {
                const email = localStorage.getItem('cliente_email');
                const dados = {
                    email: email,
                    telefone: document.getElementById('edit-telefone').value,
                    cep: document.getElementById('edit-cep').value,
                    cidade: document.getElementById('edit-cidade').value,
                    bairro: document.getElementById('edit-bairro').value,
                    endereco: document.getElementById('edit-endereco').value,
                    numero: document.getElementById('edit-numero').value,
                    complemento: document.getElementById('edit-complemento').value
                };
                
                fetch('<?= site_url('cliente/atualizar') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(dados)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        $('#modalEdicaoCliente').modal('hide');
                        alert('Dados atualizados com sucesso!');
                    } else {
                        alert('Erro ao salvar: ' + (data.msg || 'Tente novamente'));
                    }
                })
                .catch(error => {
                    alert('Erro ao salvar dados');
                });
            });
            
            // Máscara e busca CEP no modal
            document.getElementById('edit-telefone').addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    if (value.length < 14) {
                        value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                    }
                }
                e.target.value = value;
            });

            document.getElementById('edit-cep').addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 8) {
                    value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
                }
                e.target.value = value;

                if (value.replace(/\D/g, '').length === 8) {
                    buscarCEPModal(value.replace(/\D/g, ''));
                }
            });
            
            function buscarCEPModal(cep) {
                fetch('<?= site_url('login/buscar_cep') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({cep: cep})
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('edit-bairro').value = data.bairro || '';
                        document.getElementById('edit-cidade').value = data.localidade || '';
                        document.getElementById('edit-endereco').value = data.logradouro || '';
                    }
                })
                .catch(error => {});
            }
        });
    </script>
    
    <style>
        /* Responsividade geral para dispositivos móveis */
        @media (max-width: 767px) {
            /* Modal responsivo */
            .modal-dialog {
                margin: 5px !important;
                max-width: calc(100% - 10px) !important;
            }
            
            .modal-content {
                border-radius: 8px !important;
            }
            
            .modal-body {
                padding: 15px !important;
            }
            
            .modal-footer {
                padding: 10px 15px !important;
            }
            
            /* Navbar brand responsivo */
            .navbar-brand {
                font-size: 1.1rem !important;
                line-height: 1.2 !important;
            }
            
            .navbar-brand small {
                font-size: 0.7rem !important;
            }
            
            /* Hero section responsivo */
            .home-slider .slider-item h1 {
                font-size: 1.8rem !important;
            }
            
            .home-slider .slider-item p {
                font-size: 0.9rem !important;
            }
            
            /* Botões responsivos */
            .btn {
                padding: 8px 16px !important;
                font-size: 0.9rem !important;
            }
            
            /* Container responsivo */
            .container-fluid {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
            
            /* Seções com padding reduzido */
            .ftco-section {
                padding: 3em 0 !important;
            }
            
            /* Footer responsivo */
            .ftco-footer {
                padding: 3em 0 2em !important;
            }
            
            .ftco-footer .ftco-footer-widget {
                margin-bottom: 2em !important;
            }
        }
        
        @media (max-width: 575px) {
            /* Telas muito pequenas */
            .modal-dialog {
                margin: 0 !important;
                max-width: 100% !important;
                height: 100vh !important;
            }
            
            .modal-content {
                height: 100% !important;
                border-radius: 0 !important;
            }
            
            .modal-body {
                max-height: calc(100vh - 120px) !important;
                overflow-y: auto !important;
            }
            
            /* Navbar ainda mais compacta */
            .navbar-brand {
                font-size: 1rem !important;
            }
            
            /* Hero ainda mais compacto */
            .home-slider .slider-item h1 {
                font-size: 1.5rem !important;
            }
            
            .home-slider .slider-item .btn {
                padding: 6px 12px !important;
                font-size: 0.8rem !important;
                margin: 2px !important;
            }
        }
    </style>
    
    <!-- Scripts personalizados -->
    <script src="<?= site_url('web/src/js/carrinho-popup.js?v=' . time()) ?>"></script>
    <script>
        function fecharCarrinhoEVoltarMenu() {
            $('#modalCarrinho').modal('hide');
            const menuSection = document.getElementById('menu');
            if (menuSection) {
                menuSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
    </script>
    <?= $this->renderSection('scripts') ?>
    
  </body>
</html>