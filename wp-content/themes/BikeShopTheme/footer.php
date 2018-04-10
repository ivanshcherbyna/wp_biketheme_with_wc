<?php  global $biketheme;  //echo whith REDUX FRAMEWORK?>
<div class="container contact-form"
	 style="background:url(<?php echo  $biketheme['background-contact-img']['background-image']; ?>) center;">
	<!-- CONTACT FORM -->

	<?php echo do_shortcode('[contact-form-7 id="338" title="Custom contact form"]'); ?>
	<!-- CONTACT FORM /-->

</div>
<!-- footer -->
			<footer class="footer" role="contentinfo">
				<a class="footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img  src="<?php echo $biketheme['logo-footer']['url']?>;"/></a>

				<nav class="nav" role="navigation">
					<?php wp_nav_menu('menu=secondary-menu'); ?>

				</nav>

				<?php wp_footer(); ?>

				<?php  global $biketheme;  //echo whith REDUX FRAMEWORK?>

				<?php echo  $biketheme['copyright-text']; ?>



			</footer>
			<!-- /footer footer-bg -->
		</div>
		<!-- /wrapper -->

	</body>
</html>
