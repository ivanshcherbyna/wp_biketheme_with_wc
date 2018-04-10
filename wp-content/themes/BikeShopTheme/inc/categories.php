<?php

function product_categories( )
{


    $args = array( 'taxonomy' => 'product_cat'  );
    $product_categories = get_terms($args);

    if (!empty($product_categories)) {
        echo '<span class="categories-head"> CATEGORIES</span>';
        echo '<div id="categories" class="categories-group">';
        foreach ( $product_categories as $category) {

            $link = get_term_link($category);
            $name = $category->name;
            $description = $category->description;

            if ( $category->parent != 0 )
                continue;
            $cat_thumb_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
            $cat_thumb_url = wp_get_attachment_image_url( $cat_thumb_id,'full' );


            echo '<div class="categories">';
            echo "<div class='parent-div-category'>";
            echo '<a href='.$link.'><div class="hovered-triangle-div"></div>';
            echo "<div class='child-div-category'>";
            echo "<img src={$cat_thumb_url} width='275' />";
            echo "<div class='category-name-text'>{$name}</div>";
            echo "<div class='category-description-text'>{$description}</div>";
            echo "<span class='button-go-from-category' type='submit'>GO TO STORE</span>";
            echo "</div>";
            echo "</a></div>";
            echo '</div>';
        };
      echo '</div>';

    }

    wp_reset_postdata();
}



add_action( 'my_show_categories', 'product_categories' );

