<?php /**
 * 
 *
 *
 */?>

<?php get_header(); ?>
<?php  global $biketheme;  //echo whith REDUX FRAMEWORK?>

<main role="main">
	<!-- section -->
	<section>
		<!--<div><?php// do_action('woocommerce_before_shop_loop'); ?></div>-->
		
	</section>
	<!-- /section -->
	<!-- section -->
	<section>

		<div class="container contact-form"
			 style="background:url(<?php echo  $biketheme['background-contact-img']['background-image']; ?>) center;">
			<p class=""> CONTACT US</p>
			<br>
			<p class=""> Please contact us for all inquiries and purchase options.</p>
		<form action="">


				<input type="text" id="fname" name="name" placeholder="NAME">


				<input type="text" id="lname" name="surname" placeholder="SURNAME">
				<br>

				<input type="text" id="mail" name="mail" placeholder="USER@DOMAIN.COM">
				<br>


				<textarea id="message" name="message" placeholder="MESSAGE"></textarea>
				<br>

				<input type="submit" id="send-button" value="SEND">

			</form>
		</div>


	</section>
	<!-- /section -->
</main>

<?php get_footer(); ?>
