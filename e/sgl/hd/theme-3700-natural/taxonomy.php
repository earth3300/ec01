<?php

defined( 'ABSPATH' ) || die();

function add_hd_post_type() {
    $labels = array(
        'name'              => 'HD', 
        'singular_name'     => 'HD',
        'search_items'      => 'Search HD',
        'add_new'            => 'Add New', 'hd',
        'all_items'         => 'All HDs',
        'parent_item'       => 'Parent HD',
        'parent_item_colon' => 'Parent HD:',
        'edit_item'         => 'Edit HD',
        'view_item'         => 'View HD',
        'update_item'       => 'Update HD',
        'add_new_item'      => 'Add New HD',
        'new_item_name'     => 'New HD Name',
        'menu_name'         => 'HD',
        'search_items'       => 'Search HDS',
        'parent_item_colon'  => 'Parent HDs:',
        'not_found'          => 'No HDs found.',
        'not_found_in_trash' => 'No HDs found in Trash.',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'rewrite'           => array( 'slug' => 'hd' ),
        'description'        => 'HD format: 16x9 aspect ratio. Default 1920x1080.',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'menu_position'      => 10,
        'supports'           => array( 'title', 'excerpt', 'author', 'thumbnail', 'page-attributes' ),
    );

    register_post_type( 'hd', $args );
}
add_action( 'init', 'add_hd_post_type' );

