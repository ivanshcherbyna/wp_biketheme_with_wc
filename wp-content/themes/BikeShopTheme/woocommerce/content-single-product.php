<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * woocommerce_before_single_product_summary hook.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */

	the_title( '<h1 class="product_title entry-title">', '</h1>' );//ADDING TITLE PRODUCT IN UP LEFT
	do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

		<?php
		/**
		 * woocommerce_single_product_summary hook.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title ', 5);
		do_action( 'woocommerce_single_product_summary' );

		?>

	</div><!-- .summary -->
	<div class="woocommerce-tabs wc-tabs-wrapper custom-cat-cloud-tegs">
		<?php
		//CUSTOM CODE
		global $product;?>

		<div class="product_meta_once">


			<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category of this bike:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

			<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag of this bike:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>
		</div>

		<?php
		/*
        * CUSTOM  VIEW ALL CATEGORIES
        *
        */
					  $cats = get_terms(array('taxonomy' => 'product_cat'));
					if ($cats) {

							echo '<div class="div-categories-links">';
								echo '<h2>List categories </h2>';
						foreach ($cats as $cat) {
							$link = get_term_link($cat);
							$name_link = $cat->name;
							echo '<div><a class="categories-links" href="' . $link . '"><i class="fa fa-bicycle" aria-hidden="true"></i>  ' . $name_link . '</a></div>';
						}

							echo '</div>';
					}
		/*
        * CUSTOM ALL VIEW CLOUD TEGS
        *
        */
					  $args_tag = array(
						  'taxonomy'  => 'product_tag');
					  if ($args_tag) {
						  	echo '<div class="div-categories-links">';
						 		 echo '<h2>Cloud Tegs </h2>';
						  			echo wp_tag_cloud($args_tag);
						 	echo '</div>';
					  }
		?>

	</div>
	<?php

	/**
	 * woocommerce_after_single_product_summary hook.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display ', 15);
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
	do_action( 'woocommerce_after_single_product_summary' );
	?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>

