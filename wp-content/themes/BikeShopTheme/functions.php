<?php
/*
 *  Author: Lenlay
 */

define('THEME_OPT', 'lwp', true);
define('DEV_MODE', false);
/*------------------------------------*\
	Theme Support
\*------------------------------------*/




if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail

    // Localisation Support
    // load_theme_textdomain(THEME_OPT, get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/


// Load scripts
function lwp_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

       // wp_register_script('my_slider_script', get_template_directory_uri() . '/inc/slider.js', array('jquery'), '1.0.0'); // Custom scripts

        wp_register_script('themescripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), '1.0.0'); // Custom scripts

        wp_enqueue_script('themescripts');
       // wp_enqueue_script('my_slider_script');
    }
}
//RESET STYLES
function reset_styles() {

    wp_register_style('reset_styles', get_stylesheet_directory_uri() . '/inc/reset.css');
    wp_enqueue_style('reset_styles');
}
// Load styles
function lwp_styles() {
    
    wp_register_style('themestyle', get_template_directory_uri() . '/assets/css/style.css', array(), filemtime(get_template_directory() . '/assets/css/style.css'), 'all');
    wp_enqueue_style('themestyle');
}

// HTML5 Blank navigation
function lwp_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

// Register Navigation
function register_lwp_menu()
{
    register_nav_menus(array(
        'header-menu' => __('Header Menu', THEME_OPT),
        'footer-menu' => __('Footer Menu', THEME_OPT),
    ));
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'teatrhotel'),
        'description' => __('Description for this widget-area...', THEME_OPT),
        'id' => 'widget-area-1',
        'before_widget' => '<ul class="off-canvas-list">',
        'after_widget' => '</ul>',
        'before_title' => '<li><label><h3>',
        'after_title' => '</h3></label></li>'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function lwp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function lwp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function lwp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function lwp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function lwpcomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

/*------------------------------------*\
	Actions + Filters
\*------------------------------------*/

// Add Actions
add_action('wp_enqueue_scripts', 'reset_styles'); //Reset ALL BROWSERS Styles
add_action('wp_enqueue_scripts', 'lwp_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_enqueue_scripts', 'lwp_styles'); // Add Theme Stylesheet
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
//add_action('init', 'register_lwp_menu'); // Add HTML5 Blank Menu
add_action('init', 'register_bike_menu'); // Add HTML5 BIKE Menu
add_action('init', 'lwp_pagination'); // Add our HTML5 Pagination
 
// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether




include_once 'inc/loader.php';
include_once 'inc/custom_Post_Product.php';


add_action('wp_enqueue_scripts','register_styles');
function register_styles()
{
    wp_register_style('bikeshop_style_css', get_stylesheet_directory_uri() . '/inc/style.css');
    wp_register_style('awesome-icons-min', get_stylesheet_directory_uri().'/inc/font-awesome.min.css');

    wp_enqueue_style('bikeshop_style_css');
    wp_enqueue_style('awesome-icons-min');
  ;
}
include_once 'inc/categories.php';
include_once 'inc/products.php';
include_once 'inc/slider.php';
include_once 'inc/menu_nav.php';
include_once 'inc/checkout_page_custom.php';
function include_my_slider_click_script()
{
    //wp_deregister_script( 'my_slider_script' );
    wp_register_script( 'my_slider_script', get_stylesheet_directory_uri() . '/inc/slider.js', array('jquery'), '1.0.0', true);
    wp_register_script( 'my_click_items_script', get_stylesheet_directory_uri() . '/inc/click_items.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script( 'my_slider_script' );
    wp_enqueue_script( 'my_click_items_script' );

}
add_action( 'wp_enqueue_scripts', 'include_my_slider_click_script' );

//AJAX SCRIPT add to cart in home page
add_action( 'wp_enqueue_scripts', function(){
    wp_register_script( 'my_js', get_stylesheet_directory_uri() . '/inc/ajax_add_cart_custom.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script( 'my_js' );
});

// add_action('wp_ajax_custom_add_to_cart', 'my_add_to_cart');
// add_action('wp_ajax_nopriv_custom_add_to_cart', 'my_add_to_cart');

//HOOK FROM ADMIN_PANEL FOR CHECK ACTIVATE WOOCOMMERCE PLUGIN (use in liqPay custom plugin)
    function check_wc_plugin_activate(){
        // check of woocommerce active plugin
        if( !is_plugin_active( 'woocommerce/woocommerce.php' ) ){
            wp_die('<h2 style="max-width: 960px; margin: 0 auto;">Sorry! You can activated woocommerce plugin in your site!</h2>');
            return;
        }

        //echo '<h2 style="max-width: 960px; margin: 0 auto;">Well done! Woocommerce plugin is activated for correct work this plugin</h2>';
    }
add_action('admin_init','check_wc_plugin_activate');

add_filter('woocommerce_email_order_items_args', 'my_filter');
function my_filter($args){
    return $args;
}
//include_once 'inc/custom_metabox_from_post.php'; IT WORKS NORMAL
//include_once 'inc/custom_filters_actions.php'; //testing mode
//include_once 'inc/custom_metabox_from_taxonomy.php'; IT WORKS NORMAL
//include_once 'inc/custom_metabox_from_post.php'; IT WORKS NORMAL


/*
include_once 'inc/custom_image_gallery.php'; // IT WORKS NORMAL

function isDev() { //this if works for not use standart cache browser, and uising in function include_my_upload_script() 
    return defined( 'DEV_MODE' ) && DEV_MODE;
}

function include_my_upload_script(){

    $button_array = array('title' =>  'Choose your upload medias','button'=> 'Select');
    wp_enqueue_script( 'my_upload_script', get_stylesheet_directory_uri() . '/inc/script.js' . ( (isDev()) ? '?' . time() : '' ) ); //this IF delete when finish product (because his adds time GET PARAM for unuse cache)
    wp_localize_script( 'my_upload_script', 'button_text', $button_array );
}

add_action('admin_enqueue_scripts','include_my_upload_script');
*/