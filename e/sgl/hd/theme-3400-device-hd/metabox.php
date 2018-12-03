<?php

defined( 'ABSPATH' ) || die();

function add_hd_meta_box() {
    require_once( dirname(__FILE__) . '/data.php' );
    $screens = array( 'hd' );
    $items = get_hd_metabox_data();
    foreach ( $screens as $screen ) {
        add_meta_box( 'metabox', $items['title'], 'hd_meta_box_html', $screen, 'advanced' );
    }
}
add_action( 'add_meta_boxes', 'add_hd_meta_box' );

function hd_meta_box_html( $post ) {
    $items = get_hd_metabox_data();
    wp_nonce_field( 'hd_metabox_save', 'hd_meta_box_nonce' );
    $str = get_hd_metabox_fields();
    $str .= sprintf( '<p>%s</p>%s', $items['desc'], PHP_EOL );
    echo $str;
}

function get_hd_metabox_fields(){
    global $post;
    $items = get_hd_metabox_items();
    if ( ! empty ( $items ) ){
        $str = '';
        foreach ( $items as $item ) {
            if ( $item['show'] ) {
                $value = get_post_meta( $post->ID, '_' . $item['name'], true );    
                $str .= sprintf( '<p><strong>%s:</strong><br /><input type="text" id="%s" name="%s" placeholder="%s" style="width: 100%%; max-width: 500px;" value="%s" /></p>%s', $item['title'], $item['name'], $item['name'], $item['title'], $value, PHP_EOL );        
            }
        }   
        return $str;
    }
    else {
        return false;
    }
}

function save_hd_meta_box_data( $post_id ) {
    require_once( dirname(__FILE__) . '/data.php' );
    if ( isset( $_POST['hd_meta_box_nonce'] )  
        && wp_verify_nonce( $_POST['hd_meta_box_nonce'], 'hd_metabox_save' )
        && ! defined( 'DOING_AUTOSAVE' ) 
        && isset( $_POST['post_type'] ) && $_POST['post_type'] == 'hd'
        && ( current_user_can( 'edit_page', $post_id ) ) ) {
            $items = get_hd_metabox_items();
            if ( ! empty ( $items ) ) {
                foreach ( $items as $item ) {
                    if ( isset( $_POST[ $item['name'] ] ) ) {
                    $value = sanitize_text_field( $_POST[ $item['name'] ] );
                    update_post_meta( $post_id, '_' . $item['name'], $value );
                }
            }
        }
    }
}
add_action( 'save_post', 'save_hd_meta_box_data' ); //Whew!!!

function order_hd_by_date_desc_admin( $query ) {
    if ( is_admin() ) {
        $post_type = $query -> query['post_type'];
        if ( $post_type == 'hd' ) {
            if ( $query -> get( 'orderby' ) != 'date' && ! isset( $_GET['orderby'] ) ) {
                $query -> set( 'orderby', 'date' );
            }

        if ( $query -> get( 'order' ) != 'DESC' && ! isset( $_GET['order'] ) ) {
            $query -> set( 'order', 'DESC' );
            }
        }
    }
}
add_filter( 'pre_get_posts', 'order_hd_by_date_desc_admin' );