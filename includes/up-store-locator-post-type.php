<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Initialization function
add_action('init', 'up_cpt_stroe_init');

function up_cpt_stroe_init() {
  // Create new Store custom post type
    $store_labels = array(
                    'name'                 => _x('Store', 'up-store-locator'),
                    'singular_name'        => _x('store', 'up-store-locator'),
                    'add_new'              => _x('Add Store Item', 'up-store-locator'),
                    'add_new_item'         => __('Add New Store Item', 'up-store-locator'),
                    'edit_item'            => __('Edit Store Item', 'up-store-locator'),
                    'new_item'             => __('New Store Item', 'up-store-locator'),
                    'view_item'            => __('View Store Item', 'up-store-locator'),
                    'search_items'         => __('Search  Store Items','up-store-locator'),
                    'not_found'            =>  __('No Store Items found', 'up-store-locator'),
                    'not_found_in_trash'   => __('No Store Items found in Trash', 'up-store-locator'),
                    'parent_item_colon'    => '',
                    'menu_name'          => _x( 'Store Locator', 'admin menu', 'up-store-locator' )
  );
  $store_args = array(
                    'labels'              => $store_labels,
                    'public'              => true,
                    'publicly_queryable'  => true,
                    'exclude_from_search' => false,
                    'show_ui'             => true,
                    'show_in_menu'        => true, 
                    'query_var'           => true,
                    'rewrite'             => array( 
                    							'slug'       => 'store-locator',
                    							'with_front' => false
                							),
                    'capability_type'     => 'post',
                    'has_archive'         => true,
                    'hierarchical'        => false,
                    'menu_position'       => 5,
                	'menu_icon'   		  => 'dashicons-store',
                    'supports'            => array('title','editor','thumbnail'),
					'show_in_rest'		  => true,
                    'taxonomies'          => array('')
  );
    
	register_post_type( WPSL_POST_TYPE, apply_filters( 'up_store_registered_post_type_args', $store_args ) );
}

/* Register Taxonomy */
add_action( 'init', 'store_taxonomies');

function store_taxonomies() {
    $labels = array(
                'name'              => _x( 'Category', 'up-store-locator' ),
                'singular_name'     => _x( 'Category', 'up-store-locator' ),
                'search_items'      => __( 'Search Category', 'up-store-locator' ),
                'all_items'         => __( 'All Category', 'up-store-locator' ),
                'parent_item'       => __( 'Parent Category', 'up-store-locator' ),
                'parent_item_colon' => __( 'Parent Category:', 'up-store-locator' ),
                'edit_item'         => __( 'Edit Category', 'up-store-locator' ),
                'update_item'       => __( 'Update Category', 'up-store-locator' ),
                'add_new_item'      => __( 'Add New Category', 'up-store-locator' ),
                'new_item_name'     => __( 'New Category Name', 'up-store-locator' ),
                'menu_name'         => __( 'Category', 'up-store-locator' ),
    );

    $args = array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => 'store-category' ),
    );
    register_taxonomy( WPSL_CAT, array( WPSL_POST_TYPE ), $args );
}

function wpstore_rewrite_flush() {
	up_cpt_stroe_init();
    store_taxonomies();

    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wpstore_rewrite_flush' );
