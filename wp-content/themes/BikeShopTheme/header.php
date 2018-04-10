<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>

	</head>
	<body <?php body_class(); ?>>



	<!-- wrapper -->
	<div class="wrapper">
		<header class="header" role="banner">
			<!--<a class="dropdown-toggle" href="#"><i class="fa fa-bars" aria-hidden="true" id="check-toggle"></i></a>--> <!-- SHOW ADDING MENU-->
			<?php wp_nav_menu('menu=menu-dropdown'); ?>
				<!--section redux header slider & logo -->
			<?php global $biketheme; ?>

			<div class="slideshow-container">
                <a class="header-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img  src="<?php echo $biketheme['logo-header']['url'];?>" /></a>

				<?php do_action('my_show_slider'); ?>

			</div>
			<!-- /section redux header slider & logo -->
<!--			<div id="navigation"  class="nav-container">-->

				<!-- nav -->
				<div class="nav-container" role="navigation">

                    <?php bike_nav();?>
				</div>

				<!-- /nav -->
			</div>
		</header>
		

    </body>

