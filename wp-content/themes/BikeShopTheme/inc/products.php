<?php


function show_products()
{
    global $woocommerce;
    $currenturl=get_permalink();
    $popular_products = wc_get_products(array(
        'slug' => 'popular-bikes',
        'posts_per_page' => 3,
        'post_type' => 'product',
        'post_status' => 'publish')
    );


    if (!empty($popular_products)) {
        echo '<span class="products-head"> POPULAR BIKES</span>';
        echo '<div class="shop-group">';
        echo '<ul class="categories-ul">';
        foreach ($popular_products as $item) {

            $product=wc_get_product($item);
            $id_product=$product->get_id();
            $title = $product->get_name();
            $price = $product->get_price_html();
            $id_img = $product->get_image_id();
            $prod_thumb_url = wp_get_attachment_image_url( $id_img, array(319, 255));
            $quantity_product= $product->get_stock_quantity();
            $link_add_tocard=$currenturl.'/?add-to-cart=';
            //$link_add_tocard=$currenturl.'checkout/?add-to-cart='.$id_product;// get link to add to cart
            $link_porduct =$product->get_permalink();
            //$link_add_tocard=$woocommerce->cart->add_to_cart->$id_product;
            
            echo '<li>';
            echo "<form action='.$link_add_tocard.'>";
                echo "<div class='div-wrap-product'>";
                    echo "<div class='div-prodict-img'><a href={$link_porduct}><img src={$prod_thumb_url} alt='$title'  width='100%'/></a></div>";

                    echo "<div class='flex-div-product'><div class='flex-titles-product'><div class='product-name-text'>{$title}</div>";

                        echo "<div><span class='price-span'>{$price}</span></div></div>";

                        echo '<div class="flex-option">
                    
                            <ul class="product-ul">
                                <li class="select-option-product">OPTION</li>';
                                   for ($i=1; $i<$quantity_product; $i++) {

                                        echo '<li class="option-product" data-value="' . esc_attr($i) . '">'.$i.'</li>';
                                };

                            echo '</ul>';
                        if($quantity_product<=0)  echo 'out of stock';
                                echo '<select class="hidden-select" name="quantity">';
                                echo '<option value="" selected="selected">Select</option>';
                        for ($i=1; $i<$quantity_product; $i++) {
                          echo '<option value='.$i.'></option>';
                                };

                  echo '</select></div>';



            echo '<div class="flex-button-submit">';
            echo '<input type="hidden" name="id_product" value="'.esc_attr($id_product).'">';
            echo '<input type="hidden" name="url_add_to_cart"   value="'.esc_attr($link_add_tocard).'">';
            echo '<input type="submit" class="button-by-text" value="BUY" />';


            echo '</div></form>';
            echo '</div>';

            echo '</li>';


        };     // echo '</div>';
          //  echo '</div>';
       // echo '</div>';

    }
    wp_reset_postdata();
}
add_action('my_show_products','show_products');


/* RESERVED WORK FUNC with use template wc
function show_products()
{
$args = array(
'post_type' => 'product',
'posts_per_page' => 3
);
$loop = new WP_Query($args);
if ($loop->have_posts())
{
while ($loop->have_posts()) : $loop->the_post();
wc_get_template_part('content', 'product');

endwhile;

}
else {
echo __('No products found');
}
wp_reset_postdata();
}
*/
?>