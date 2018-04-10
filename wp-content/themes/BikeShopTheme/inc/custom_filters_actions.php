<?php

add_action('my_action','my_func_from_action');
function my_func_from_action($post_id){
    //do something
    $arg = get_meta_custom_post($post_id);//1513867704:1
    echo $arg;
}
//do_action('my_action');


add_filter('my_filter','my_func_from_filter');

function my_func_from_filter($arg){
    $arg = $arg . ' + NEW AGR FROM RETURN FILTER';

    return $arg;
}