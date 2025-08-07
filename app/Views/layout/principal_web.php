<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title><?= $this->renderSection('titulo') ?> - Restaurante</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
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
    
    <!-- Estilos personalizados -->
    <?= $this->renderSection('estilos') ?>
  </head>
  <body>
  	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
		      <a class="navbar-brand" href="<?= site_url('/') ?>"><span class="flaticon-pizza-1 mr-1"></span>No Kapricho<br><small>A melhor pizzaria da cidade</small></a>
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
	          <li class="nav-item"><a href="<?= site_url('login') ?>" class="nav-link">Admin</a></li>
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
	    						<h3>(11) 9999-9999</h3>
	    						<p>Faça seu pedido por telefone</p>
	    					</div>
	    				</div>
	    				<div class="col-md-4 d-flex ftco-animate">
	    					<div class="icon"><span class="icon-my_location"></span></div>
	    					<div class="text">
	    						<h3>Rua das Flores, 123</h3>
	    						<p>Centro - São Paulo - SP</p>
	    					</div>
	    				</div>
	    				<div class="col-md-4 d-flex ftco-animate">
	    					<div class="icon"><span class="icon-clock-o"></span></div>
	    					<div class="text">
	    						<h3>Aberto Segunda-Domingo</h3>
	    						<p>18:00 - 23:00</p>
	    					</div>
	    				</div>
	    			</div>
	    		</div>
	    		<div class="social d-md-flex pl-md-5 p-4 align-items-center">
	    			<ul class="social-icon">
              <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
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
    		<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4">Nossos Serviços</h2>
            <p>Oferecemos os melhores serviços para garantir sua satisfação e comodidade.</p>
          </div>
        </div>
    		<div class="row">
          <div class="col-md-4 ftco-animate">
            <div class="media d-block text-center block-6 services">
              <div class="icon d-flex justify-content-center align-items-center mb-5">
              	<span class="flaticon-diet"></span>
              </div>
              <div class="media-body">
                <h3 class="heading">Comida Saudável</h3>
                <p>Ingredientes frescos e selecionados, preparados com técnicas que preservam os nutrientes.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-4 ftco-animate">
            <div class="media d-block text-center block-6 services">
              <div class="icon d-flex justify-content-center align-items-center mb-5">
              	<span class="flaticon-bicycle"></span>
              </div>
              <div class="media-body">
                <h3 class="heading">Entrega Rápida</h3>
                <p>Delivery eficiente para que você receba seu pedido quentinho e no tempo certo.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-4 ftco-animate">
            <div class="media d-block text-center block-6 services">
              <div class="icon d-flex justify-content-center align-items-center mb-5"><span class="flaticon-pizza-1"></span></div>
              <div class="media-body">
                <h3 class="heading">Receitas Originais</h3>
                <p>Pratos preparados com receitas tradicionais e o toque especial da nossa cozinha.</p>
              </div>
            </div>    
          </div>
        </div>
    	</div>
    </section>

    <!-- Menu Section -->
    <section class="ftco-section" id="menu">
    	<div class="container">
    		<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4">Nosso Menu</h2>
            <p>Descubra nossos pratos especiais, preparados com ingredientes frescos e muito sabor.</p>
          </div>
        </div>
        
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
    				<div id="map" style="width: 100%; height: 400px; background: #ddd; display: flex; align-items: center; justify-content: center;">
    					<p>Mapa do Google aqui</p>
    				</div>
    			</div>
	    		<div class="col-md-6 appointment ftco-animate">
	    			<h3 class="mb-3">Entre em Contato</h3>
	    			<form action="#" class="appointment-form">
	    				<div class="d-md-flex">
		    				<div class="form-group">
		    					<input type="text" class="form-control" placeholder="Nome">
		    				</div>
	    				</div>
	    				<div class="d-me-flex">
	    					<div class="form-group">
		    					<input type="text" class="form-control" placeholder="Telefone">
		    				</div>
	    				</div>
	    				<div class="form-group">
	              <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Mensagem"></textarea>
	            </div>
	            <div class="form-group">
	              <input type="submit" value="Enviar" class="btn btn-primary py-3 px-4">
	            </div>
	    			</form>
	    		</div>    			
    		</div>
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
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-5 mb-md-5">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Horário de Funcionamento</h2>
              <div class="opening-hours">
                <h4>Dias da Semana:</h4>
                <p class="pl-3">
                  <span>Segunda - Domingo: 18:00 - 23:00</span>
                </p>
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
	                <li><span class="icon icon-map-marker"></span><span class="text">Rua das Flores, 123 - Centro, São Paulo - SP</span></li>
	                <li><a href="#"><span class="icon icon-phone"></span><span class="text">(11) 9999-9999</span></a></li>
	                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">contato@restaurante.com</span></a></li>
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
    
    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

    <!-- Scripts -->
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
    
    <!-- Scripts personalizados -->
    <?= $this->renderSection('scripts') ?>
    
  </body>
</html>