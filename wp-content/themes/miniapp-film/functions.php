<?php

function add_film_field() {
  global $post;
  $value = get_post_meta( $post->ID, '_film_selected', true );
  if( empty( $value ) ) $value = '';

  $options[''] = 'Chọn phim';
  $options['1'] = "Mắt biếc";
  $options['2'] = "Cuộc chiến cuối cùng";
  $options['3'] = "Iron man";

  echo '<div class="options_group">';
  woocommerce_wp_select(
    array(
      'id'      => '_film_selected',
      'label'   => 'Chọn tên phim',
      'options' =>  $options,
      'value'   => $value,
    )
  );
  echo '</div>';
}

add_action( 'woocommerce_product_options_general_product_data', 'add_film_field' );

function save_film_field($post_id){
  $woocommerce_select = $_POST['_film_selected'];
  if (!empty( $woocommerce_select)) {
    update_post_meta( $post_id, '_film_selected', esc_attr( $woocommerce_select ) );
  } else {
    update_post_meta( $post_id, '_film_selected',  '' );
  }
}

add_action( 'woocommerce_process_product_meta', 'save_film_field' );
