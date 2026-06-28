<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */

function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style('hello-elementor-child-style',get_stylesheet_directory_uri() . '/style.css',['hello-elementor-theme-style',],'1.0.0');
	wp_enqueue_script('custom-js-script', get_stylesheet_directory_uri() . '/script.js', array('jquery'), '1.0.0', true);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

add_filter( 'woocommerce_product_description_heading', '__return_null' );
add_filter( 'woocommerce_product_review_heading', '__return_null' );