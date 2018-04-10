/**
 * Created by devit on 12.01.18.
 */
jQuery(document).ready(function($) {

    $(".button-by-text").click(function(e) {

        e.preventDefault();
        $(this).addClass('adding-cart');

        var product_id = $(this).parent().find('input[name="id_product"]').val();
        var id = parseInt(product_id);
        //var quantity='1';
        var url=$(this).parent().find('input[name="url_add_to_cart"]').val();
        var quantity = $(this).parent().parent().find(".hidden-select").text();
        var quan = parseInt(quantity);
        //console.log(quantity);


        addToCart(id,url,quan);

        return false;
    });

    function addToCart(id,url,quantity) {
       $.get(url + id +'&quantity=' + quantity, function()
       {
            alert('Well Done! Your product in cart!');
            console.log('Well Done! Your product in cart!');
       });
       }

    })



