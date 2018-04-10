
<?php /**
 * Template name: Custom front page
 *
 *
 */?>

<?php get_header(); ?>
<!-- section  categories-->

<div class="container categories-section"
     style="background:url(<?php echo  $biketheme['background-categories-img']['background-image']; ?>) center;">


    <?php do_action('my_show_categories'); ?>


</div>


<!-- /section categories-->
<?php  global $biketheme;  //echo whith REDUX FRAMEWORK?>

<main role="main">


    <!-- section  Shop-->
    <section >
        <div class="container shop-section" style="background: #ffffff">
            <ul class="shop">
                <?php  do_action('my_show_products'); ?>
            </ul><!--/.products-->
        </div>

    </section>
    <!-- /section Shop-->
    <!-- section  contact form-->

    <!-- /section contact form-->
</main>

<?php get_footer(); ?>
