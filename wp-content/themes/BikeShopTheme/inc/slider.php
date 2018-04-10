<?php

function include_slider(){

global $biketheme;

foreach ($biketheme['home-slidehome-slider'] as $item)
{   
    ?>
    <div class="mySlides fade">
        <img src="<?php echo $item['image']; ?>" style="width:100%">

        <div class="icon">
            <a id="menu" class="scroll-item-center-before" href="#categories"><img class="icon_image-before" src="<?php echo $biketheme['scroll-item-before']['url'];?>" /></a>
            <a id="menu" class="scroll-item-center-after" href="#categories"><img  class="icon_image-after" src="<?php echo $biketheme['scroll-item-after']['url'];?>" /></a>
        </div>

    </div>
    <?php
}
?>
    <h2 class="general-slider-text">HANDMADE BICYCLE</h2>
    <div class="under-slider-text">You <yellow>create</yellow> the <yellow>journey</yellow> we supply the <yellow>parts</yellow></div>
<!-- Next and previous buttons -->
<a class="prev" onclick="plusSlides(-1)"><img src="<?php echo $biketheme['left-nav-item']['url'];?>"></a>
<a class="next" onclick="plusSlides(1)"><img src="<?php echo $biketheme['right-nav-item']['url'];?>"></a>


    <a href="<?php echo get_permalink().'shop/'; ?>" class="header-shop-button">SHOP BIKES</a>

<!-- The lines -->
    <div class="lines-slider-items" style="text-align:center">
        <?php foreach ($biketheme['home-slidehome-slider'] as $item) {
            $numberCurrentSlide = ($item['sort'] + 1);
            $countOfSlider = count($biketheme['home-slidehome-slider']);//DEFINE Cound elements in slidershow now
            global $countOfSlider;
            ?>
            <span class="line" onclick="currentSlide({$numberCurrentSlide})"></span>
            <?php
        }
        ?>
    </div>

<?php

  

}

add_action( 'my_show_slider', 'include_slider' );
?>

