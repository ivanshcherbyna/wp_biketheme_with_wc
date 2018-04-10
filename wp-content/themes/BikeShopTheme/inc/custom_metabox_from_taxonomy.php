<?php
// create custom taxonomy
add_action( 'init', 'create_my_custom_tax' );


function create_my_custom_tax() {
    register_taxonomy(
        'my-tax',  'products',
        array('label' => __( 'Category product' ),'rewrite' => array( 'slug' => 'custom-taxonomy' ),'hierarchical' => true )
    );
}

// регистрируем metabpox в taxonomy
add_action( 'init', 'register_term_meta_text' );
function register_term_meta_text() {
    register_meta( 'term', 'term_meta_text', 'clear_term_meta_text');
}


//  wordpress очистка
function clear_term_meta_text ( $value ) {
    return esc_html($value);
}




// получаем GET мета текст, должен быть очищен clear
function get_term_meta_text( $term_id ) {
    $value = get_term_meta( $term_id, 'term_meta_text', true ); // получаем терм по ИДшнику и ключу
    $value = clear_term_meta_text( $value ); // очищаем
    return $value;
}




// добавляем custom поле в category term page
add_action( 'my-tax_add_form_fields', 'add_form_field_term_meta_text' );
function add_form_field_term_meta_text() { ?>



    <div class="form-field term-meta-text-wrap">
        <label for="term-meta-text"><?php _e( 'YOUR META TEXT', 'text_domain' ); ?></label>
        <input type="text" name="term_meta_text" id="term-meta-text" value="" class="term-meta-text-field" />
    </div>
<?php }






// добавляем в категорию поле страницы  для редактирования
add_action( 'my-tax_edit_form_fields', 'edit_form_field_term_meta_text' );
function edit_form_field_term_meta_text( $term ) {
    $value  = get_term_meta_text( $term->term_id );
    if ( ! $value )  $value = ""; ?> <!-- если пусто значение терма переписывем-->

    <tr class="form-field term-meta-text-wrap">
        <th scope="row"><label for="term-meta-text"><?php _e( 'MY META TEXT', 'text_domain' ); ?></label></th>
        <td>

            <input type="text" name="term_meta_text" id="term-meta-text" value="<?php echo esc_attr( $value ); ?>" class="term-meta-text-field"  />
        </td>
    </tr>
<?php }






// сохранение и редактирование регистрируем
add_action( 'edit_my-tax',   'save_term_meta_text' );
add_action( 'create_my-tax', 'save_term_meta_text' );


function save_term_meta_text( $term_id ) {


    $old_value  = get_term_meta_text( $term_id );// вызов своей функции выше объявленной
    $new_value = isset( $_POST['term_meta_text'] ) ? clear_term_meta_text ( $_POST['term_meta_text'] ) : '';
    if ( $old_value && '' === $new_value ) //если старое значение  присутствует
        delete_term_meta( $term_id, 'term_meta_text' ); //удаляем старую запись
    else if ( $old_value !== $new_value ) //если старое значение не равно новому
        update_term_meta( $term_id, 'term_meta_text', $new_value ); // обновляем (заново записываем в базу новое значение)
}

//
function get_name_custom_terms($post_id){
    $post_id = (int) $post_id;
    $key_of_tax='my-tax';
    $args=array('fields' => 'names');
       
    $postTerms = wp_get_post_terms($post_id, $key_of_tax, $args); //вызов метода Вордпресс поиска терма по ключу конкретного поста
    if( ! empty($postTerms) ) {


        foreach( $postTerms as $term ) {
            echo $term;
        }
    }

   
}
