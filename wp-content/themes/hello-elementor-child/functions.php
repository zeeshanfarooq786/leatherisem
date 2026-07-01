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
	wp_enqueue_style('hello-elementor-child-style',get_stylesheet_directory_uri() . '/style.css',['hello-elementor-theme-style',],'1.0.4');
	wp_enqueue_script('custom-js-script', get_stylesheet_directory_uri() . '/script.js', array('jquery'), '1.0.4', true);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

add_filter( 'woocommerce_product_description_heading', '__return_null' );
add_filter( 'woocommerce_product_review_heading', '__return_null' );

/**
 * Size dropdown on simple products (uses WooCommerce "size" product attribute).
 */
function hello_elementor_child_get_size_options( WC_Product $product ) {
	$attributes = $product->get_attributes();
	if ( empty( $attributes['size'] ) ) {
		return array();
	}

	$attribute = $attributes['size'];
	if ( ! $attribute->get_visible() ) {
		return array();
	}

	$options = $attribute->get_options();
	if ( empty( $options ) ) {
		return array();
	}

	return array_map( 'trim', $options );
}

function hello_elementor_child_render_size_dropdown() {
	if ( ! is_product() ) {
		return;
	}

	global $product;
	if ( ! $product instanceof WC_Product || ! $product->is_type( 'simple' ) ) {
		return;
	}

	$sizes = hello_elementor_child_get_size_options( $product );
	if ( empty( $sizes ) ) {
		return;
	}

	echo '<div class="leatherismus-size-field">';
	echo '<select id="leatherismus_size" class="leatherismus-size-select" name="leatherismus_size" required>';
	echo '<option value="">' . esc_html__( 'Size', 'hello-elementor-child' ) . '</option>';
	foreach ( $sizes as $size ) {
		printf(
			'<option value="%1$s">%2$s</option>',
			esc_attr( $size ),
			esc_html( $size )
		);
	}
	echo '</select>';
	echo '</div>';
}
add_action( 'woocommerce_before_add_to_cart_quantity', 'hello_elementor_child_render_size_dropdown', 10 );

function hello_elementor_child_validate_size_selection( $passed, $product_id ) {
	$product = wc_get_product( $product_id );
	if ( ! $product instanceof WC_Product || ! $product->is_type( 'simple' ) ) {
		return $passed;
	}

	if ( empty( hello_elementor_child_get_size_options( $product ) ) ) {
		return $passed;
	}

	if ( empty( $_POST['leatherismus_size'] ) ) {
		wc_add_notice( __( 'Please select a size.', 'hello-elementor-child' ), 'error' );
		return false;
	}

	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'hello_elementor_child_validate_size_selection', 10, 2 );

function hello_elementor_child_add_size_to_cart_item( $cart_item_data, $product_id ) {
	if ( empty( $_POST['leatherismus_size'] ) ) {
		return $cart_item_data;
	}

	$cart_item_data['leatherismus_size'] = sanitize_text_field( wp_unslash( $_POST['leatherismus_size'] ) );

	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'hello_elementor_child_add_size_to_cart_item', 10, 2 );

function hello_elementor_child_display_size_in_cart( $item_data, $cart_item ) {
	if ( empty( $cart_item['leatherismus_size'] ) ) {
		return $item_data;
	}

	$item_data[] = array(
		'name'  => __( 'Size', 'hello-elementor-child' ),
		'value' => $cart_item['leatherismus_size'],
	);

	return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'hello_elementor_child_display_size_in_cart', 10, 2 );

function hello_elementor_child_save_size_to_order( $item, $cart_item_key, $values ) {
	if ( empty( $values['leatherismus_size'] ) ) {
		return;
	}

	$item->add_meta_data( __( 'Size', 'hello-elementor-child' ), $values['leatherismus_size'], true );
}
add_action( 'woocommerce_checkout_create_order_line_item', 'hello_elementor_child_save_size_to_order', 10, 3 );