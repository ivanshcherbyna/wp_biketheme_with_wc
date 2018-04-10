

jQuery(document).ready( function ($) {
    //slide down hide
    $("ul.product-ul").on("click", ".select-option-product", function () {
        $(this).closest("ul.product-ul").children('li:not(.select-option-product)').slideDown();
    });
    //save all items in object
    var allOptions = $("ul.product-ul").children('li:not(.select-option-product)');
    //change html inner when selected item and change name classes
    $("ul.product-ul").on("click", "li:not(.select-option-product)", function () {
        allOptions.removeClass('selected');
        $(this).addClass('selected');

        var  select = $('.hidden-select');
        $(this).parent("ul.product-ul").children('.select-option-product').html($(this).html());
        $(this).parent("ul.product-ul").parent("div.flex-option").children('select').children('option:selected').html($(this).html());//link UL li whith sinc work Select

        //select.val(value);
        select.trigger('change');

        allOptions.slideUp();


    })
    $(document).mouseup(function (e){ // event onclick on element
        var el = $(".select-option-product"); // find element by class name
        if (!el.is(this) // if click has been by not this element
            && el.has(e.target).length === 0 )  // and not child elements

        {
            allOptions.slideUp(); // hide elements
        }
    });
})

jQuery(document).ready( function ($) {
    //slide down hide
    $("ul.product-color-ul").on("click", ".select-color-product", function () {
        $(this).closest("ul.product-color-ul").children('li:not(.select-color-product)').slideDown();
    });
    //save all items in object
    var allOptions = $("ul.product-color-ul").children('li:not(.select-color-product)');
    //change html inner when selected item and change name classes
    $("ul.product-color-ul").on("click", "li:not(.select-color-product)", function () {

        var value = $(this).data('value'),
            select = $('#other-color');

        allOptions.removeClass('selected');
        $(this).addClass('selected');
        $(this).parent("ul.product-color-ul").children('.select-color-product').html($(this).html());

        select.val(value);
        select.trigger('change');

        //$(this).parent("ul.product-color-ul").parent(".value").children('select').children('option:selected').html($(this).html());//link UL li whith sinc work Select
        allOptions.slideUp();


    })
    $(document).mouseup(function (e){ // event onclick on element
        var el = $(".select-color-product"); // find element by class name
        if (!el.is(this) // if click has been by not this element
            && el.has(e.target).length === 0 )  // and not child elements

        {
            allOptions.slideUp(); // hide elements
        }
    });
})

jQuery(document).ready(function($) {

// Dropdown toggle
    $('.dropdown-toggle').click(function(){
        $(this).nextAll('.menu-item').toggle();
    });

    $(document).click(function(e) {
        var target = e.target;
        if (!$(target).is('.dropdown-toggle') && !$(target).parents().is('.dropdown-toggle')) {
            $('.menu-item').hide(); 
            $('.dropdown-toggle').show();
        }
    });

});