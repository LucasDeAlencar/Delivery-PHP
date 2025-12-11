<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title><?= $this->renderSection('titulo') ?> - Restaurante</title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
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
    <link rel="stylesheet" href="<?= site_url('assets/css/mobile-responsive.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/mobile-touch.css?v=' . time()) ?>">
    
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
    </style>

    
    <!-- CSRF token para uso em requisições AJAX -->
    <?= csrf_meta('csrf_token_meta') ?>
    
    <!-- Estilos personalizados -->
    <?= $this->renderSection('estilos') ?>
  </head>
  <body>
  	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
		      <a class="navbar-brand" href="<?= site_url('/') ?>"><span class="flaticon-pizza-1 mr-1"></span>No Kapricho<br><small>A melhor pizzaria da cidade</small></a>
		      
		      <!-- Ícone do Carrinho independente -->
		      <div class="carrinho-navbar d-flex align-items-center">
		          <a href="#" onclick="window.Carrinho && window.Carrinho.mostrar(); return false;" 
		             class="carrinho-link d-flex align-items-center text-decoration-none">
		              <div class="carrinho-icon-container position-relative">
		                  <i class="fas fa-shopping-cart text-warning" style="font-size: 28px;"></i>
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
    <section class="home-slider owl-carousel img" style="background-image: url(<?= site_url('web/src/images/bg_1.jpg') ?>);">
      <div class="slider-item">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text align-items-center" data-scrollax-parent="true">

            <div class="col-md-6 col-sm-12 ftco-animate">
            	<span class="subheading">Delicioso</span>
              <h1 class="mb-4">Culinária Italiana</h1>
              <p class="mb-4 mb-md-5">Sabores autênticos que conquistam o paladar, preparados com ingredientes frescos e receitas tradicionais.</p>
              <p><a href="#menu" class="btn btn-primary p-3 px-xl-4 py-xl-3">Fazer Pedido</a> <a href="#menu" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Ver Menu</a></p>
            </div>
            <div class="col-md-6 ftco-animate">
            	<img src="<?= site_url('web/src/images/bg_1.png') ?>" class="img-fluid" alt="">
            </div>

          </div>
        </div>
      </div>

      <div class="slider-item">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text align-items-center" data-scrollax-parent="true">

            <div class="col-md-6 col-sm-12 order-md-last ftco-animate">
            	<span class="subheading">Crocante</span>
              <h1 class="mb-4">Pizza Italiana</h1>
              <p class="mb-4 mb-md-5">Massas artesanais, molhos especiais e ingredientes selecionados para uma experiência única.</p>
              <p><a href="#menu" class="btn btn-primary p-3 px-xl-4 py-xl-3">Fazer Pedido</a> <a href="#menu" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Ver Menu</a></p>
            </div>
            <div class="col-md-6 ftco-animate">
            	<img src="<?= site_url('web/src/images/bg_2.png') ?>" class="img-fluid" alt="">
            </div>

          </div>
        </div>
      </div>

      <div class="slider-item" style="background-image: url(<?= site_url('web/src/images/bg_3.jpg') ?>);">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

            <div class="col-md-7 col-sm-12 text-center ftco-animate">
            	<span class="subheading">Bem-vindo</span>
              <h1 class="mb-4">Preparamos suas receitas favoritas</h1>
              <p class="mb-4 mb-md-5">Cada prato é preparado com carinho e dedicação para proporcionar momentos especiais.</p>
              <p><a href="#menu" class="btn btn-primary p-3 px-xl-4 py-xl-3">Fazer Pedido</a> <a href="#menu" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Ver Menu</a></p>
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
              <li class="ftco-animate"><a href="<?= !empty($dadosCorporativos->twitter) ? 'https://twitter.com/' . ltrim($dadosCorporativos->twitter, '@') : '#' ?>" target="_blank"><span class="icon-twitter"></span></a></li>
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
          <h2 class="mb-4">Bem-vindo ao <span class="flaticon-pizza">Nosso</span> Restaurante</h2>
        </div>
        <div>
  				<p>Há mais de 20 anos servindo os melhores pratos da culinária italiana com ingredientes frescos e receitas tradicionais. Nossa paixão pela gastronomia se reflete em cada prato que preparamos, sempre buscando proporcionar uma experiência única aos nossos clientes.</p>
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
					<div class="col-md-3 ftco-animate">
						<a href="#" class="gallery img d-flex align-items-center" style="background-image: url(<?= site_url('web/src/images/gallery-1.jpg') ?>);">
							<div class="icon mb-4 d-flex align-items-center justify-content-center">
    						<span class="icon-search"></span>
    					</div>
						</a>
					</div>
					<div class="col-md-3 ftco-animate">
						<a href="#" class="gallery img d-flex align-items-center" style="background-image: url(<?= site_url('web/src/images/gallery-2.jpg') ?>);">
							<div class="icon mb-4 d-flex align-items-center justify-content-center">
    						<span class="icon-search"></span>
    					</div>
						</a>
					</div>
					<div class="col-md-3 ftco-animate">
						<a href="#" class="gallery img d-flex align-items-center" style="background-image: url(<?= site_url('web/src/images/gallery-3.jpg') ?>);">
							<div class="icon mb-4 d-flex align-items-center justify-content-center">
    						<span class="icon-search"></span>
    					</div>
						</a>
					</div>
					<div class="col-md-3 ftco-animate">
						<a href="#" class="gallery img d-flex align-items-center" style="background-image: url(<?= site_url('web/src/images/gallery-4.jpg') ?>);">
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
    		<div class="row no-gutters d-md-flex align-items-center">
    			<div class="col-md-6 d-flex align-self-stretch">
    				<div class="contact-wrapper" style="width: 100%; height: 400px; background: url('<?= base_url('web/src/images/bg_2.jpg') ?>') center/cover; position: relative;">
    					<div class="contact-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center;">
    						<div class="contact-content text-center text-white p-4">
    							<h3 class="mb-4" style="color: #f8b531; font-family: 'Josefin Sans', sans-serif; font-size: 2rem;">Encontre-nos</h3>
    							
    							<div class="contact-info">
    								<div class="info-item d-flex align-items-center mb-3">
    									<div class="icon-box mr-3" style="width: 40px; height: 40px; background: #f8b531; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
    										<i class="fas fa-map-marker-alt text-dark"></i>
    									</div>
    									<div class="text-left">
    										<small class="text-muted">Endereço</small><br>
    										<span><?= $dadosCorporativos->endereco ?? 'Rua das Flores, 123 - Centro, SP' ?></span>
    									</div>
    								</div>
    								
    								<div class="info-item d-flex align-items-center mb-3">
    									<div class="icon-box mr-3" style="width: 40px; height: 40px; background: #f8b531; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
    										<i class="fas fa-phone text-dark"></i>
    									</div>
    									<div class="text-left">
    										<small class="text-muted">Telefone</small><br>
    										<span><?= $dadosCorporativos->numero ?? '(11) 9999-9999' ?></span>
    									</div>
    								</div>
    								
    								<div class="info-item d-flex align-items-center mb-4">
    									<div class="icon-box mr-3" style="width: 40px; height: 40px; background: #f8b531; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
    										<i class="fas fa-clock text-dark"></i>
    									</div>
    									<div class="text-left">
    										<small class="text-muted">Horário</small><br>
    										<span>Seg-Dom: 18h às 23h</span>
    									</div>
    								</div>
    								
    								<div class="delivery-badge" style="background: rgba(248,181,49,0.2); border: 1px solid #f8b531; border-radius: 25px; padding: 8px 16px; display: inline-block;">
    									<i class="fas fa-motorcycle mr-2" style="color: #f8b531;"></i>
    									<small style="color: #f8b531; font-weight: 600;">Delivery disponível</small>
    								</div>
    							</div>
    						</div>
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
		    					<input type="text" id="telefoneContato" class="form-control" placeholder="Telefone (00) 00000-0000 *" required>
		    				</div>
	    				</div>
	    				<div class="form-group">
	              <textarea id="mensagemContato" cols="30" rows="3" class="form-control" placeholder="Como podemos ajudá-lo? Descreva sua dúvida ou pedido... *" required></textarea>
	            </div>
	            <div class="form-group">
	              <button type="submit" class="btn btn-primary py-3 px-4">
	                  <i class="fab fa-whatsapp mr-2"></i>Enviar via WhatsApp
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
    		    const telefone = document.getElementById('telefoneContato').value.trim();
    		    const mensagem = document.getElementById('mensagemContato').value.trim();
    		    
    		    // Validações
    		    if (!nome) {
    		        alert('⚠️ Por favor, informe seu nome completo');
    		        document.getElementById('nomeContato').focus();
    		        return;
    		    }
    		    
    		    if (!telefone) {
    		        alert('⚠️ Por favor, informe seu telefone para contato');
    		        document.getElementById('telefoneContato').focus();
    		        return;
    		    }
    		    
    		    if (telefone.replace(/\D/g, '').length < 10) {
    		        alert('⚠️ Por favor, informe um telefone válido com DDD');
    		        document.getElementById('telefoneContato').focus();
    		        return;
    		    }
    		    
    		    if (!mensagem) {
    		        alert('⚠️ Por favor, descreva como podemos ajudá-lo');
    		        document.getElementById('mensagemContato').focus();
    		        return;
    		    }
    		    
    		    // Montar mensagem sofisticada do WhatsApp
    		    const agora = new Date();
    		    const horario = agora.toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'});
    		    
    		    let textoWhatsApp = `🍕 *No Kapricho Pizzaria* 🍕\n\n`;
    		    textoWhatsApp += `Olá! Espero que estejam bem! 😊\n\n`;
    		    textoWhatsApp += `📋 *Meus dados:*\n`;
    		    textoWhatsApp += `👤 Nome: ${nome}\n`;
    		    textoWhatsApp += `📱 Telefone: ${telefone}\n`;
    		    textoWhatsApp += `🕐 Horário do contato: ${horario}\n\n`;
    		    textoWhatsApp += `💬 *Minha mensagem:*\n`;
    		    textoWhatsApp += `"${mensagem}"\n\n`;
    		    textoWhatsApp += `Aguardo retorno! Muito obrigado(a)! 🙏`;
    		    
    		    // Número do WhatsApp (remover caracteres especiais)
    		    const numeroWhatsApp = '<?= preg_replace("/[^0-9]/", "", $dadosCorporativos->numero ?? "11999999999") ?>';
    		    
    		    // Abrir WhatsApp
    		    const urlWhatsApp = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(textoWhatsApp)}`;
    		    window.open(urlWhatsApp, '_blank');
    		    
    		    // Feedback de sucesso
    		    alert('✅ Redirecionando para o WhatsApp! Sua mensagem foi preparada.');
    		    
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
                <li class="ftco-animate"><a href="<?= !empty($dadosCorporativos->twitter) ? 'https://twitter.com/' . ltrim($dadosCorporativos->twitter, '@') : '#' ?>" target="_blank"><span class="icon-twitter"></span></a></li>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCarrinhoLabel">
                        <i class="fas fa-shopping-cart mr-2"></i>Meu Carrinho
                        <span class="badge-total-itens" id="modal-carrinho-total-itens">0 itens</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-carrinho-body">
                    <!-- Itens do carrinho serão inseridos aqui via JavaScript -->
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <!-- Resumo do Total -->
                        <div class="carrinho-resumo-total">
                            <div class="resumo-linha">
                                <span>Total do Pedido:</span>
                                <strong id="modal-carrinho-total">R$ 0,00</strong>
                            </div>
                        </div>
                        
                        <!-- Botões de Ação -->
                        <div class="row" style="margin: 0;">
                            <div class="col-12 col-md-6 mb-2 mb-md-0" style="padding: 0 5px;">
                                <button type="button" class="btn btn-limpar-carrinho w-100" id="btn-limpar-carrinho" onclick="Carrinho.limpar()">
                                    <i class="fas fa-trash mr-2"></i>Limpar Carrinho
                                </button>
                            </div>
                            <div class="col-12 col-md-6" style="padding: 0 5px;">
                                <button type="button" class="btn btn-finalizar-carrinho w-100" id="btn-finalizar-pedido" onclick="Carrinho.finalizar()">
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
    <script src="<?= site_url('assets/js/carrinho-modal-v2.js?v=' . time()) ?>"></script>
    <script src="<?= site_url('assets/js/finalizar-pedido.js?v=' . time()) ?>"></script>
    <script src="<?= site_url('web/src/js/carrinho-menu.js?v=' . time()) ?>"></script>
    <script src="<?= site_url('web/src/js/carrinho-trigger.js?v=' . time()) ?>"></script>
    
    <style>
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
            <div class="modal-content" style="background: #1a1a1a; border: 1px solid #333;">
                <div class="modal-header" style="border-bottom: 1px solid #333;">
                    <h5 class="modal-title" id="modalEdicaoLabel" style="color: #f8b531;">
                        <i class="fas fa-edit mr-2"></i>Editar Meus Dados
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: #fff;">
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
                <div class="modal-footer" style="border-top: 1px solid #333;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn" id="btnSalvarEdicao" style="background: #f8b531; color: #000;">
                        <i class="fas fa-save mr-2"></i>Salvar Alterações
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
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
                localStorage.removeItem('cliente_email');
                window.location.href = '<?= site_url('login') ?>';
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
                    console.error('Erro:', error);
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
                    console.error('Erro:', error);
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
                .catch(error => console.error('Erro ao buscar CEP:', error));
            }
        });
    </script>
    
    <!-- Scripts personalizados -->
    <?= $this->renderSection('scripts') ?>
    
  </body>
</html>