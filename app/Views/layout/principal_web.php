<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title><?= $this->renderSection('titulo') ?> - Delivery</title>
    
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
    <link rel="stylesheet" href="<?= site_url('web/src/css/space-theme.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= site_url('assets/css/carrinho-modal.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/carrinho-popup.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/modal-fix.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/mobile-responsive.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/mobile-touch.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= site_url('assets/css/about-center.css?v=' . time()) ?>">
    
    <!-- Estilos do carrinho navbar -->
    <style>
        .brand-name { font-size: 1.6rem; }
        .brand-sub  { font-size: 0.8rem; }
        .logo-space { font-size: 1.6rem; }

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
            background: rgba(248,181,49,0.12);
            border: 1.5px solid rgba(248,181,49,0.35);
            border-radius: 10px;
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease, border-color 0.2s ease;
        }
        .carrinho-icon-container:hover {
            background: rgba(248,181,49,0.22);
            border-color: rgba(248,181,49,0.7);
        }
        #carrinho-icon {
            font-size: 20px !important;
        }
        
        .carrinho-counter {
            top: -6px;
            right: -6px;
            background: #f8b531;
            color: #111;
            font-size: 10px;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            text-align: center;
            line-height: 18px;
            padding: 0 4px;
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
            #ftco-navbar .container {
                display: flex;
                flex-wrap: nowrap;
                align-items: center;
                padding-left: 10px;
                padding-right: 10px;
            }

            #ftco-navbar .navbar-brand {
                flex: 1 1 0;
                min-width: 0;
                margin-right: 8px;
            }

            #ftco-navbar .carrinho-navbar {
                flex-shrink: 0;
                margin-left: 0;
                margin-right: 8px;
                order: 2;
            }

            #ftco-navbar .navbar-toggler {
                flex-shrink: 0;
                order: 3;
                margin-left: 0;
                padding: 4px 8px;
            }
        }

        @media (max-width: 991px) {
            #ftco-navbar .brand-name {
                font-size: 1rem !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
            }

            #ftco-navbar .brand-sub {
                font-size: 0.65rem !important;
            }

            #ftco-navbar .logo-space {
                font-size: 1.2rem !important;
            }
        }

        @media (max-width: 480px) {
            #ftco-navbar .brand-name {
                font-size: 0.85rem !important;
            }

            #ftco-navbar .brand-sub {
                display: none !important;
            }

            #ftco-navbar .logo-space {
                font-size: 1rem !important;
                margin-right: 5px !important;
            }

            #ftco-navbar .carrinho-link i {
                font-size: 22px !important;
            }

            #ftco-navbar .carrinho-navbar {
                margin-right: 6px;
            }

            #ftco-navbar .navbar-toggler {
                padding: 3px 6px;
                font-size: 0.85rem;
            }
        }
    </style>

    
    <!-- CSRF token para uso em requisições AJAX -->
    <?= csrf_meta('csrf_token_meta') ?>
    
    <!-- Estilos personalizados -->
    <?= $this->renderSection('estilos') ?>

    <!-- iOS: bloqueia overscroll/bounce na página (fallback para Safari < 16) -->
    <script>
    (function() {
        var isIOS = /iP(hone|ad|od)/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        if (!isIOS) return;
        var startY = 0;
        document.addEventListener('touchstart', function(e) {
            startY = e.touches[0].clientY;
        }, { passive: true });
        document.addEventListener('touchmove', function(e) {
            var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            var dy = e.touches[0].clientY - startY;
            // Bloqueia pull-down quando já está no topo
            if (scrollTop <= 0 && dy > 0) {
                e.preventDefault();
            }
        }, { passive: false });
    })();
    </script>
  </head>
  <body>
  	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
		      <a class="navbar-brand" href="<?= site_url('/') ?>">
 		          <i class="fas fa-hamburger logo-space" style="color: #f5a623; margin-right: 8px; line-height: 1;"></i>
 		          <span class="brand-name" style="color: #fff !important; line-height: 1.2;">Space Burger Dog Do Paulista<span class="brand-sub" style="text-transform: uppercase; display: block; color: #ccc !important">O seu delivery preferido</span></span>
		      </a>
		      
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
		      
		      <button class="navbar-toggler d-none d-lg-block" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
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
    <section class="home-slider owl-carousel img" style="background-image: url('<?= site_url('web/src/images/burger-1.jpg') ?>');">
      <div class="slider-item" style="background-image: url('<?= site_url('web/src/images/burger-1.jpg') ?>');">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text align-items-center" data-scrollax-parent="true">
            <div class="col-md-6 col-sm-12 ftco-animate">
            	<span class="subheading">Irresistível</span>
              <h1 class="mb-4">Hamburgueres Artesanais</h1>
              <p class="mb-4 mb-md-5">Nossos hamburgueres são feitos com carne fresquinha, pães artesanais e ingredientes selecionados. Sabor único que você não encontra em outro lugar!</p>
              <p><a href="#menu" class="btn btn-primary p-3 px-xl-4 py-xl-3">Fazer Pedido</a> <a href="#about" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Ver Home</a></p>
            </div>
            <div class="col-md-6 ftco-animate">
            	<img src="<?= site_url('web/src/images/ImagemPromocional2.jpeg') ?>" class="img-fluid" alt="Hamburguer Artesanal">
            </div>
          </div>
        </div>
      </div>

      <div class="slider-item" style="background-image: url('<?= site_url('web/src/images/ImagemPromocional8.jpeg') ?>');">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text align-items-center" data-scrollax-parent="true">
            <div class="col-md-6 col-sm-12 order-md-last ftco-animate">
            	<span class="subheading">Saboroso</span>
              <h1 class="mb-4">HotDogs Especiais</h1>
              <p class="mb-4 mb-md-5">Cachorros-quentes com salsichas premium, molhos especiais e muito queijo mussarela. Uma experiência única!</p>
              <p><a href="#menu" class="btn btn-primary p-3 px-xl-4 py-xl-3">Fazer Pedido</a> <a href="#about" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Ver Home</a></p>
            </div>
            <div class="col-md-6 ftco-animate">
            	<img src="<?= site_url('web/src/images/ImagemPromocional8.jpeg') ?>" class="img-fluid" alt="HotDog Especial">
            </div>
          </div>
        </div>
      </div>

      <div class="slider-item" style="background-image: url('<?= site_url('web/src/images/ImagemPromocional3.jpeg') ?>');">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">
            <div class="col-md-7 col-sm-12 text-center ftco-animate">
            	<span class="subheading">Bem-vindo</span>
              <h1 class="mb-4">Space Burger Dog Do Paulista & HotDog</h1>
              <p class="mb-4 mb-md-5">A melhor combinação de hamburgueres e hotdogs da cidade. Entrega rápida, sabor garantido!</p>
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
	    						<p>Precisa de ajuda? Solicite o suporte comercial pelo telefone.</p>
	    					</div>
	    				</div>
	    				<div class="col-md-4 d-flex ftco-animate">
	    					<div class="icon"><span class="icon-my_location"></span></div>
	    					<div class="text">
	    						<h3><?= $dadosCorporativos->endereco ?? 'Rua das Flores, 123' ?></h3>
	    						<p>Ponto de retirada</p>
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
            <h2 class="mb-4">Bem-vindo ao <span class="fas fa-hamburger" style="color: #00567e">Space Burger Dog Do Paulista</span></h2>
        </div>
        <div>
  				<p>Somos especializados em hamburgueres artesanais e hotdogs especiais. Nossos produtos são feitos com ingredientes frescos e de qualidade, proporcionando o melhor sabor para você e sua família.</p>
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
						<a href="#" class="gallery img d-flex align-items-center" style="background-image: url('<?= site_url('web/src/images/ImagemPromocional4.jpeg') ?>');">
							<div class="icon mb-4 d-flex align-items-center justify-content-center">
    						<span class="icon-search"></span>
    					</div>
						</a>
					</div>
					<div class="col-md-6 ftco-animate">
						<a href="#" class="gallery img d-flex align-items-center" style="background-image: url('<?= site_url('web/src/images/ImagemPromocional9.jpeg') ?>');">
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
	              <textarea id="mensagemContato" cols="30" rows="3" class="form-control" placeholder="Como podemos lhe ajudar?&#10;Descreva sua dúvida. *" required></textarea>
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
              <p>Somos especializado em hamburgueres artesanais e hotdogs especiais, com ingredientes frescos e de qualidade.</p>
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
                    <p class="pl-3" style="<?= $exp->dia == $diaAtual ? 'font-weight: bold; color: #00557f;' : '' ?>">
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
	                <li><a href="#"><span class="icon icon-envelope"></span><span class="text"><?= '&nbsp;' . $dadosCorporativos->email ?? 'contato@nokapricho.com' ?></span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">
            <p>&copy; <?= date('Y') ?> Space Burger Dog Do Paulista. Todos os direitos reservados.</p>
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
                                <button type="button" class="btn btn-warning w-100" id="btn-finalizar-pedido" style="background: linear-gradient(135deg, #00557f 0%, #003f5e 100%); border: none; color: #fff; font-weight: 600; font-family: 'Poppins', sans-serif;">
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
                            if (status === 'finalizado' || status === 'cancelado') {
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
    });
    </script>
    
    <style>
        /* Backdrop com blur de 80% */
        .modal-backdrop {
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            background-color: rgba(0, 0, 0, 0.8) !important;
        }
        
        /* Nav inferior sempre acima do backdrop e dos modais */
        #mobile-bottom-nav {
            z-index: 1055 !important;
        }
        #mob-user-btn {
            z-index: 1055 !important;
        }


        /* Scroll e padding-right controlados via modal-fix.css */
        
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
            border: 1px solid #00557f;
            background: transparent;
            color: #00557f;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
        }
        
        .btn-qty:hover {
            background: #00557f;
            color: #fff;
        }
        
        .qty-valor {
            color: #00557f;
            font-weight: bold;
            min-width: 20px;
            text-align: center;
        }
    </style>
    
    <!-- Indicador de cliente logado (desktop) -->
    <div id="cliente-logado" style="position: fixed; bottom: 20px; right: 20px; background: rgba(0,0,0,0.8); color: #00557f; padding: 10px 15px; border-radius: 25px; font-size: 14px; cursor: pointer; z-index: 1055; display: none; border: 1px solid #00557f;">
        <i class="fas fa-user-circle mr-2"></i>
        <span id="email-cliente"></span>
    </div>
    <!-- Menu logout independente — funciona no mobile e desktop -->
    <div id="menu-logout" style="position: fixed; bottom: 70px; right: 12px; background: rgba(0,0,0,0.95); border: 1px solid #00557f; border-radius: 8px; padding: 10px; margin-bottom: 5px; display: none; white-space: nowrap; z-index: 1060;">
        <a href="#" id="btn-editar" style="color: #00557f; text-decoration: none; font-size: 13px; display: block; margin-bottom: 8px;">
            <i class="fas fa-edit mr-2"></i>Editar Dados
        </a>
        <a href="#" id="btn-logout" style="color: #ff6b6b; text-decoration: none; font-size: 13px;">
            <i class="fas fa-sign-out-alt mr-2"></i>Sair
        </a>
    </div>

    <!-- Modal de Edição de Dados -->

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
                // Desktop: mostrar flutuante
                clienteLogado.style.display = 'block';
                // Mobile: mostrar botão na nav inferior
                const mobBtn = document.getElementById('mob-user-btn');
                if (mobBtn) {
                    mobBtn.style.display = 'flex';
                    document.getElementById('mob-user-label').textContent = emailSalvo.split('@')[0];
                }
            }

            // Toggle menu logout (desktop — clique no flutuante)
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

                fetch('<?= site_url('cliente/dados') ?>', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
                    body: JSON.stringify({email})
                })
                .then(r => r.json())
                .then(data => {
                    if (!data.sucesso) { alert('Erro ao carregar dados do cliente'); return; }
                    const c = data.cliente;

                    // Remover popup anterior se existir
                    document.getElementById('edicao-popup')?.remove();

                    const popup = document.createElement('div');
                    popup.id = 'edicao-popup';
                    popup.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:9999;display:flex;align-items:center;justify-content:center;padding:0 12px;box-sizing:border-box;';
                    popup.innerHTML = `
                        <div style="background:#1a1a1a;width:100%;max-width:500px;max-height:90vh;min-height:0;border-radius:15px;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.5);">
                            <!-- Header -->
                            <div style="background:linear-gradient(135deg,#2d2d2d,#1a1a1a);border-bottom:1px solid #333;padding:16px 20px;flex-shrink:0;display:flex;align-items:center;justify-content:center;position:relative;border-radius:15px 15px 0 0;">
                                <h5 style="color:#f8b531;font-family:'Poppins',sans-serif;font-weight:600;margin:0;font-size:1rem;">
                                    <i class="fas fa-edit" style="margin-right:8px;"></i>Editar Meus Dados
                                </h5>
                                <button onclick="document.getElementById('edicao-popup').remove()" style="position:absolute;right:16px;background:none;border:none;color:#fff;font-size:1.4rem;cursor:pointer;opacity:.7;line-height:1;">&times;</button>
                            </div>
                            <!-- Body -->
                            <div style="flex:1 1 auto;overflow-y:auto;padding:20px;-webkit-overflow-scrolling:touch;">
                                <form id="formEdicaoCliente">
                                    <div style="margin-bottom:14px;">
                                        <label style="color:#00557f;font-size:.85rem;display:block;margin-bottom:4px;">Nome Completo *</label>
                                        <input type="text" id="edit-nome" class="form-control" readonly style="background:#1a1a1a;border:1px solid #444;color:#ccc;font-size:16px;">
                                    </div>
                                    <div style="margin-bottom:14px;">
                                        <label style="color:#00557f;font-size:.85rem;display:block;margin-bottom:4px;">Telefone *</label>
                                        <input type="tel" id="edit-telefone" class="form-control" required style="background:#2d2d2d;border:1px solid #444;color:#fff;font-size:16px;">
                                    </div>
                                    <div style="margin-bottom:14px;">
                                        <label style="color:#00557f;font-size:.85rem;display:block;margin-bottom:4px;">CEP *</label>
                                        <input type="text" id="edit-cep" class="form-control" required style="background:#2d2d2d;border:1px solid #444;color:#fff;font-size:16px;">
                                    </div>
                                    <div style="margin-bottom:14px;">
                                        <label style="color:#00557f;font-size:.85rem;display:block;margin-bottom:4px;">Cidade *</label>
                                        <input type="text" id="edit-cidade" class="form-control" readonly style="background:#1a1a1a;border:1px solid #444;color:#ccc;font-size:16px;">
                                    </div>
                                    <div style="margin-bottom:14px;position:relative;">
                                        <label style="color:#00557f;font-size:.85rem;display:block;margin-bottom:4px;">Bairro *</label>
                                        <input type="text" id="edit-bairro" class="form-control" autocomplete="off" style="background:#1a1a1a;border:1px solid #444;color:#ccc;font-size:16px;">
                                        <div id="edit-bairro-sugestoes" style="position:absolute;z-index:9999;width:100%;background:#2d2d2d;border:1px solid #444;border-radius:0 0 6px 6px;max-height:160px;overflow-y:auto;display:none;"></div>
                                        <div id="edit-bairro-aviso" style="display:none;color:#ff6b6b;font-size:.78rem;margin-top:3px;"><i class="fas fa-exclamation-triangle"></i> Bairro não encontrado na área de entrega.</div>
                                    </div>
                                    <div style="margin-bottom:14px;">
                                        <label style="color:#00557f;font-size:.85rem;display:block;margin-bottom:4px;">Logradouro *</label>
                                        <input type="text" id="edit-endereco" class="form-control" style="background:#1a1a1a;border:1px solid #444;color:#ccc;font-size:16px;">
                                    </div>
                                    <div style="margin-bottom:14px;">
                                        <label style="color:#00557f;font-size:.85rem;display:block;margin-bottom:4px;">Número</label>
                                        <input type="text" id="edit-numero" class="form-control" style="background:#2d2d2d;border:1px solid #444;color:#fff;font-size:16px;">
                                    </div>
                                    <div style="margin-bottom:14px;">
                                        <label style="color:#00557f;font-size:.85rem;display:block;margin-bottom:4px;">Complemento</label>
                                        <input type="text" id="edit-complemento" class="form-control" style="background:#2d2d2d;border:1px solid #444;color:#fff;font-size:16px;">
                                    </div>
                                </form>
                            </div>
                            <!-- Footer -->
                            <div style="background:#1a1a1a;border-top:1px solid #333;padding:14px 20px;flex-shrink:0;display:flex;gap:10px;border-radius:0 0 15px 15px;">
                                <button onclick="document.getElementById('edicao-popup').remove()" style="flex:1;padding:12px;background:#333;border:1px solid #555;color:#ccc;border-radius:8px;font-weight:600;cursor:pointer;">
                                    <i class="fas fa-times" style="margin-right:6px;"></i>Cancelar
                                </button>
                                <button id="btnSalvarEdicao" style="flex:1;padding:12px;background:linear-gradient(135deg,#0055ff,#1a1866);border:none;color:#fff;border-radius:8px;font-weight:600;cursor:pointer;font-family:'Poppins',sans-serif;">
                                    <i class="fas fa-save" style="margin-right:6px;"></i>Salvar Alterações
                                </button>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(popup);

                    // Preencher campos
                    document.getElementById('edit-nome').value        = c.nome || '';
                    document.getElementById('edit-telefone').value    = c.telefone || '';
                    document.getElementById('edit-cep').value         = c.cep || '';
                    document.getElementById('edit-cidade').value      = c.Cidade || '';
                    document.getElementById('edit-bairro').value      = c.Bairro || '';
                    document.getElementById('edit-endereco').value    = c.Endereco || '';
                    document.getElementById('edit-numero').value      = c.Numero || '';
                    document.getElementById('edit-complemento').value = c.complemento || '';

                    // Carregar bairros da cidade já preenchida
                    if (c.Cidade) carregarBairrosModal(c.Cidade);

                    // Fechar ao clicar no overlay
                    popup.addEventListener('click', function(e) {
                        if (e.target === popup) popup.remove();
                    });

                    // Salvar
                    document.getElementById('btnSalvarEdicao').addEventListener('click', function() {
                        const bairroVal   = document.getElementById('edit-bairro').value.trim();
                        const enderecoVal = document.getElementById('edit-endereco').value.trim();
                        const avisoEl     = document.getElementById('edit-bairro-aviso');

                        if (!bairroVal) {
                            avisoEl.innerHTML = '<i class="fas fa-exclamation-triangle"></i> O campo Bairro é obrigatório.';
                            avisoEl.style.display = 'block';
                            document.getElementById('edit-bairro').focus();
                            return;
                        }
                        if (!enderecoVal) {
                            avisoEl.innerHTML = '<i class="fas fa-exclamation-triangle"></i> O campo Logradouro é obrigatório.';
                            avisoEl.style.display = 'block';
                            document.getElementById('edit-endereco').focus();
                            return;
                        }
                        avisoEl.style.display = 'none';

                        const dados = {
                            email,
                            telefone:    document.getElementById('edit-telefone').value,
                            cep:         document.getElementById('edit-cep').value,
                            cidade:      document.getElementById('edit-cidade').value,
                            bairro:      document.getElementById('edit-bairro').value,
                            endereco:    document.getElementById('edit-endereco').value,
                            numero:      document.getElementById('edit-numero').value,
                            complemento: document.getElementById('edit-complemento').value
                        };

                        fetch('<?= site_url('cliente/atualizar') ?>', {
                            method: 'POST',
                            headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
                            body: JSON.stringify(dados)
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.sucesso) {
                                document.getElementById('edicao-popup').remove();
                                alert('Dados atualizados com sucesso!');
                            } else {
                                alert('Erro ao salvar: ' + (data.msg || 'Tente novamente'));
                            }
                        })
                        .catch(() => alert('Erro ao salvar dados'));
                    });

                    // Máscara telefone
                    document.getElementById('edit-telefone').addEventListener('input', function(e) {
                        let v = e.target.value.replace(/\D/g, '');
                        v = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                        if (v.length < 15) v = v.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                        e.target.value = v;
                    });

                    // Máscara + busca CEP
                    document.getElementById('edit-cep').addEventListener('input', function(e) {
                        let v = e.target.value.replace(/\D/g, '');
                        if (v.length <= 8) v = v.replace(/(\d{5})(\d{3})/, '$1-$2');
                        e.target.value = v;
                        if (v.replace(/\D/g,'').length === 8) buscarCEPModal(v.replace(/\D/g,''));
                    });

                    // Autocomplete bairro
                    const editBairroEl    = document.getElementById('edit-bairro');
                    const editSugestoesEl = document.getElementById('edit-bairro-sugestoes');
                    editBairroEl.addEventListener('input', function() {
                        const termo = this.value.trim().toLowerCase();
                        editSugestoesEl.innerHTML = '';
                        editSugestoesEl.style.display = 'none';
                        verificarBairroModal();
                        if (!termo || !bairrosModal.length) return;
                        const filtrados = bairrosModal.filter(b => b.toLowerCase().includes(termo)).slice(0, 8);
                        if (!filtrados.length) return;
                        filtrados.forEach(b => {
                            const item = document.createElement('div');
                            item.textContent = b;
                            item.style.cssText = 'padding:7px 12px;cursor:pointer;color:#fff;border-bottom:1px solid #444;font-size:.85rem;';
                            item.addEventListener('mousedown', () => { editBairroEl.value = b; editSugestoesEl.style.display = 'none'; verificarBairroModal(); });
                            item.addEventListener('mouseover', () => item.style.background = '#3a3a3a');
                            item.addEventListener('mouseout',  () => item.style.background = '');
                            editSugestoesEl.appendChild(item);
                        });
                        editSugestoesEl.style.display = 'block';
                    });
                    editBairroEl.addEventListener('blur', () => setTimeout(() => editSugestoesEl.style.display = 'none', 150));
                })
                .catch(() => alert('Erro ao carregar dados'));
            }

            function buscarCEPModal(cep) {
                fetch('<?= site_url('login/buscar_cep') ?>', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
                    body: JSON.stringify({cep})
                })
                .then(r => r.json())
                .then(data => {
                    if (!data.erro) {
                        const cidade = data.localidade || '';
                        document.getElementById('edit-cidade').value = cidade;
                        const bairroEl   = document.getElementById('edit-bairro');
                        const enderecoEl = document.getElementById('edit-endereco');
                        if (data.bairro)     { bairroEl.value = data.bairro; bairroEl.readOnly = true; }
                        else                 { bairroEl.value = ''; bairroEl.readOnly = false; bairroEl.placeholder = 'Digite seu bairro'; }
                        if (data.logradouro) { enderecoEl.value = data.logradouro; enderecoEl.readOnly = true; }
                        else                 { enderecoEl.value = ''; enderecoEl.readOnly = false; enderecoEl.placeholder = 'Digite seu logradouro'; }
                        verificarBairroModal();
                        if (cidade) carregarBairrosModal(cidade);
                    }
                })
                .catch(() => {});
            }

            let bairrosModal = [];
            function carregarBairrosModal(cidade) {
                fetch('<?= site_url('registrar/bairros_cidade') ?>?cidade=' + encodeURIComponent(cidade), {
                    headers: {'X-Requested-With':'XMLHttpRequest'}
                })
                .then(r => r.json())
                .then(d => { bairrosModal = d.bairros || []; });
            }

            function verificarBairroModal() {
                const val   = document.getElementById('edit-bairro')?.value.trim().toLowerCase();
                const aviso = document.getElementById('edit-bairro-aviso');
                if (!val || !aviso || !bairrosModal.length) { if(aviso) aviso.style.display = 'none'; return; }
                aviso.style.display = bairrosModal.some(b => b.toLowerCase() === val) ? 'none' : 'block';
            }
        });
    </script>
    
    <style>
        /* Responsividade geral — ver produto-modal.css e mobile-responsive.css */
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
    <!-- Nav inferior mobile (substitui o botão Menu) -->
    <nav id="mobile-bottom-nav" class="d-lg-none" style="position:fixed;bottom:0;left:0;right:0;z-index:1050;background:#111;border-top:1px solid #333;display:flex;">
        <a href="<?= site_url('/') ?>" class="mob-nav-btn" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:.5rem .2rem;color:#fff;text-decoration:none;font-size:.7rem;border-top:3px solid transparent;">
            <i class="fas fa-home" style="font-size:1.2rem;margin-bottom:2px;"></i>Home
        </a>
        <a href="#menu" class="mob-nav-btn" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:.5rem .2rem;color:#fff;text-decoration:none;font-size:.7rem;border-top:3px solid transparent;">
            <i class="fas fa-hamburger" style="font-size:1.2rem;margin-bottom:2px;"></i>Menu
        </a>
        <a href="#services" class="mob-nav-btn" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:.5rem .2rem;color:#fff;text-decoration:none;font-size:.7rem;border-top:3px solid transparent;">
            <i class="fas fa-concierge-bell" style="font-size:1.2rem;margin-bottom:2px;"></i>Serviços
        </a>
        <a href="#about" class="mob-nav-btn" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:.5rem .2rem;color:#fff;text-decoration:none;font-size:.7rem;border-top:3px solid transparent;">
            <i class="fas fa-info-circle" style="font-size:1.2rem;margin-bottom:2px;"></i>Sobre
        </a>
        <a href="#contact" class="mob-nav-btn" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:.5rem .2rem;color:#fff;text-decoration:none;font-size:.7rem;border-top:3px solid transparent;">
            <i class="fas fa-envelope" style="font-size:1.2rem;margin-bottom:2px;"></i>Contato
        </a>
        <button id="mob-user-btn" onclick="var ml=document.getElementById('menu-logout');ml.style.display=ml.style.display==='block'?'none':'block';event.stopPropagation();" style="display:none;flex:1;flex-direction:column;align-items:center;justify-content:center;padding:.5rem .2rem;color:#ffc107;font-size:.7rem;border:none;border-top:3px solid #ffc107;background:transparent;cursor:pointer;">
            <i class="fas fa-user-circle" style="font-size:1.2rem;margin-bottom:2px;"></i>
            <span id="mob-user-label">Conta</span>
        </button>
    </nav>
    <style>
        @media (max-width: 991px) {
            body { padding-bottom: 56px; }
            .mob-nav-btn:hover, .mob-nav-btn.active { color: #ffc107 !important; border-top-color: #ffc107 !important; }
            #cliente-logado { display: none !important; }
        }
        @media (min-width: 992px) {
            /* Desktop: menu aparece acima do flutuante */
            #menu-logout { bottom: 70px; right: 20px; }
        }
    </style>
    <script>
        // Destacar item ativo conforme seção visível
        (function() {
            var links = document.querySelectorAll('.mob-nav-btn');
            function setActive(hash) {
                links.forEach(function(l) {
                    var isActive = l.getAttribute('href') === hash || (hash === '' && l.getAttribute('href') === '<?= site_url('/') ?>');
                    l.classList.toggle('active', isActive);
                    l.style.color = isActive ? '#ffc107' : '#fff';
                    l.style.borderTopColor = isActive ? '#ffc107' : 'transparent';
                });
            }
            window.addEventListener('hashchange', function() { setActive(location.hash); });
            setActive(location.hash || '<?= site_url('/') ?>');
        })();
    </script>

    <?= $this->renderSection('scripts') ?>
    
  </body>
</html>