<?php
/**
 * Clothing Store functions and definitions
 *
 * @subpackage Clothing Store
 * @since 1.0
 */

//woocommerce//
//shop page no of columns
function clothing_store_woocommerce_loop_columns() {
	
	$retrun = get_theme_mod( 'clothing_store_archieve_item_columns', 3 );
    
    return $retrun;
}
add_filter( 'loop_shop_columns', 'clothing_store_woocommerce_loop_columns' );
function clothing_store_woocommerce_products_per_page() {

		$retrun = get_theme_mod( 'clothing_store_archieve_shop_perpage', 6 );
    
    return $retrun;
}
add_filter( 'loop_shop_per_page', 'clothing_store_woocommerce_products_per_page' );
// related products
function clothing_store_related_products_args( $args ) {
    $defaults = array(
        'posts_per_page' => get_theme_mod( 'clothing_store_related_shop_perpage', 3 ),
        'columns'        => get_theme_mod( 'clothing_store_related_item_columns', 3),
    );

    $args = wp_parse_args( $defaults, $args );

    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'clothing_store_related_products_args' );
function clothing_store_related_products_heading($clothing_store_translated_text, $text, $domain) {
    $clothing_store_heading = get_theme_mod('woocommerce_related_products_heading', 'Related products');

    if ($text === 'Related products' && $domain === 'woocommerce') {
        $clothing_store_translated_text = $clothing_store_heading;
    }
    return $clothing_store_translated_text;
}
add_filter('gettext', 'clothing_store_related_products_heading', 20, 3);
// breadcrumb seperator
function clothing_store_woocommerce_breadcrumb_separator($clothing_store_defaults) {
    $clothing_store_separator = get_theme_mod('woocommerce_breadcrumb_separator', ' / ');

    // Update the separator
    $clothing_store_defaults['delimiter'] = $clothing_store_separator;

    return $clothing_store_defaults;
}
add_filter('woocommerce_breadcrumb_defaults', 'clothing_store_woocommerce_breadcrumb_separator');

//add animation class
if ( class_exists( 'WooCommerce' ) ) { 
	add_filter('post_class', function($clothing_store_classes, $class, $product_id) {
	    if( is_shop() || is_product_category() ){
	        
	        $clothing_store_classes = array_merge(['wow','zoomIn'], $clothing_store_classes);
	    }
	    return $clothing_store_classes;
	},10,3);
}
//woocommerce-end//

// Get start function

// Enqueue scripts and styles
function clothing_store_enqueue_admin_script($hook) {
    // Admin JS
    wp_enqueue_script('clothing-store-admin-js', get_theme_file_uri('/assets/js/clothing-store-admin.js'), array('jquery'), true);
    wp_localize_script(
		'clothing-store-admin-js',
		'clothing_store',
		array(
			'admin_ajax'	=>	admin_url('admin-ajax.php'),
			'wpnonce'			=>	wp_create_nonce('clothing_store_dismissed_notice_nonce')
		)
	);
	wp_enqueue_script('clothing-store-admin-js');

    wp_localize_script( 'clothing-store-admin-js', 'clothing_store_scripts_localize',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
    );
}
add_action('admin_enqueue_scripts', 'clothing_store_enqueue_admin_script');

//dismiss function 
add_action( 'wp_ajax_clothing_store_dismissed_notice_handler', 'clothing_store_ajax_notice_dismiss_fuction' );

function clothing_store_ajax_notice_dismiss_fuction() {
	if (!wp_verify_nonce($_POST['wpnonce'], 'clothing_store_dismissed_notice_nonce')) {
		exit;
	}
    if ( isset( $_POST['type'] ) ) {
        $type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
        update_option( 'dismissed-' . $type, TRUE );
    }
}

//get start box
function clothing_store_custom_admin_notice() {
    // Check if the notice is dismissed
    if ( ! get_option('dismissed-get_started_notice', FALSE ) )  {
        // Check if not on the theme documentation page
        $clothing_store_current_screen = get_current_screen();
        if ($clothing_store_current_screen && $clothing_store_current_screen->id !== 'appearance_page_clothing-store-guide-page') {
            $clothing_store_theme = wp_get_theme();
            ?>
            <div class="notice notice-info is-dismissible" data-notice="get_started_notice">
                <div class="notice-div">
                    <div>
                        <p class="theme-name"><?php echo esc_html($clothing_store_theme->get('Name')); ?></p>
                        <p><?php _e('For information and detailed instructions, check out our theme documentation.', 'clothing-store'); ?></p>
                    </div>
                    <div class="notice-buttons-box">
                        <a class="button-primary livedemo" href="<?php echo esc_url( CLOTHING_STORE_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'clothing-store'); ?></a>
                        <a class="button-primary buynow" href="<?php echo esc_url( CLOTHING_STORE_BUY_PRO ); ?>" target="_blank"><?php esc_html_e('Buy Now', 'clothing-store'); ?></a>
                        <a class="button-primary theme-install" href="themes.php?page=clothing-store-guide-page"><?php _e('Begin Installation', 'clothing-store'); ?></a> 
                    </div>
                </div>
            </div>
        <?php
        }
    }
}
add_action('admin_notices', 'clothing_store_custom_admin_notice');

//after switch theme
add_action('after_switch_theme', 'clothing_store_after_switch_theme');
function clothing_store_after_switch_theme () {
    update_option('dismissed-get_started_notice', FALSE );
}
//get-start-function-end//

// tag count
function clothing_store_display_post_tag_count() {
    $clothing_store_tags = get_the_tags();
    $clothing_store_tag_count = ($clothing_store_tags) ? count($clothing_store_tags) : 0;
    $clothing_store_tag_text = ($clothing_store_tag_count === 1) ? 'tag' : 'tags';
    echo $clothing_store_tag_count . ' ' . $clothing_store_tag_text;
}

//media post format
function clothing_store_get_media($clothing_store_type = array()){
	$clothing_store_content = apply_filters( 'the_content', get_the_content() );
  	$output = false;

  // Only get media from the content if a playlist isn't present.
  if ( false === strpos( $clothing_store_content, 'wp-playlist-script' ) ) {
    $output = get_media_embedded_in_content( $clothing_store_content, $clothing_store_type );
    return $output;
  }
}

// front page template
function clothing_store_front_page_template( $template ) {
	return is_home() ? '' : $template;
}
add_filter( 'frontpage_template',  'clothing_store_front_page_template' );

// excerpt function
function clothing_store_custom_excerpt() {
    $clothing_store_excerpt = get_the_excerpt();
    $clothing_store_plain_text_excerpt = wp_strip_all_tags($clothing_store_excerpt);
    
    // Get dynamic word limit from theme mod
    $clothing_store_word_limit = esc_attr(get_theme_mod('clothing_store_post_excerpt', '30'));
    
    // Limit the number of words
    $clothing_store_limited_excerpt = implode(' ', array_slice(explode(' ', $clothing_store_plain_text_excerpt), 0, $clothing_store_word_limit));

    echo esc_html($clothing_store_limited_excerpt);
}

//typography
function clothing_store_fonts_scripts() {
	$headings_font = esc_html(get_theme_mod('clothing_store_headings_text'));
	$body_font = esc_html(get_theme_mod('clothing_store_body_text'));

	if( $headings_font ) {
		wp_enqueue_style( 'clothing-store-headings-fonts', '//fonts.googleapis.com/css?family='. $headings_font );
	} else {
		wp_enqueue_style( 'clothing-store-source-sans', '//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
	}
	if( $body_font ) {
		wp_enqueue_style( 'clothing-store-body-fonts', '//fonts.googleapis.com/css?family='. $body_font );
	} else {
		wp_enqueue_style( 'clothing-store-source-body', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,700,600');
	}
}
add_action( 'wp_enqueue_scripts', 'clothing_store_fonts_scripts' );

// Footer Text
function clothing_store_copyright_link() {
    $architecture_building_footer_text = get_theme_mod('clothing_store_footer_text', esc_html__('Clothing Store WordPress Theme', 'clothing-store'));
    $architecture_building_credit_link = esc_url('https://www.ovationthemes.com/products/free-clothing-store-wordpress-theme');

    echo '<a href="' . $architecture_building_credit_link . '" target="_blank">' . esc_html($architecture_building_footer_text) . '<span class="footer-copyright">' . esc_html__(' By Ovation Themes', 'clothing-store') . '</span></a>';
}

// custom sanitizations
// dropdown
function clothing_store_sanitize_dropdown_pages( $page_id, $setting ) {
	$page_id = absint( $page_id );
	return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
}
// slider custom control
if ( ! function_exists( 'clothing_store_sanitize_integer' ) ) {
	function clothing_store_sanitize_integer( $input ) {
		return (int) $input;
	}
}
// range contol
function clothing_store_sanitize_number_absint( $number, $setting ) {

	// Ensure input is an absolute integer.
	$number = absint( $number );

	// Get the input attributes associated with the setting.
	$atts = $setting->manager->get_control( $setting->id )->input_attrs;

	// Get minimum number in the range.
	$min = ( isset( $atts['min'] ) ? $atts['min'] : $number );

	// Get maximum number in the range.
	$max = ( isset( $atts['max'] ) ? $atts['max'] : $number );

	// Get step.
	$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );

	// If the number is within the valid range, return it; otherwise, return the default
	return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
}
// select post page
function clothing_store_sanitize_select( $input, $setting ){
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
// toggle switch
function clothing_store_callback_sanitize_switch( $value ) {
	// Switch values must be equal to 1 of off. Off is indicator and should not be translated.
	return ( ( isset( $value ) && $value == 1 ) ? 1 : 'off' );
}
//choices control
function clothing_store_sanitize_choices( $input, $setting ) {
    global $wp_customize;
    $control = $wp_customize->get_control( $setting->id );
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}
// phone number
function clothing_store_sanitize_phone_number( $phone ) {
  return preg_replace( '/[^\d+]/', '', $phone );
}
// Sanitize Sortable control.
function clothing_store_sanitize_sortable( $val, $setting ) {
	if ( is_string( $val ) || is_numeric( $val ) ) {
		return array(
			esc_attr( $val ),
		);
	}
	$sanitized_value = array();
	foreach ( $val as $item ) {
		if ( isset( $setting->manager->get_control( $setting->id )->choices[ $item ] ) ) {
			$sanitized_value[] = esc_attr( $item );
		}
	}
	return $sanitized_value;
}

// customizer-dropdowns
function clothing_store_slider_dropdown(){
	if(get_option('clothing_store_slider_arrows') == true ) {
		return true;
	}
	return false;
}
function clothing_store_product_dropdown(){
	if(get_option('clothing_store_product_enable') == true ) {
		return true;
	}
	return false;
}

// theme setup
function clothing_store_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( "align-wide" );
	add_theme_support( "wp-block-styles" );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( "responsive-embeds" );
	add_theme_support( 'title-tag' );
	add_theme_support('custom-background',array(
		'default-color' => 'ffffff',
	));
	add_image_size( 'clothing-store-featured-image', 2000, 1200, true );
	add_image_size( 'clothing-store-thumbnail-avatar', 100, 100, true );

	$GLOBALS['content_width'] = 525;
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'clothing-store' ),
	) );

	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array('image','video','gallery','audio','quote',) );
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */
	add_editor_style( array( 'assets/css/editor-style.css', clothing_store_fonts_url() ) );
}
add_action( 'after_setup_theme', 'clothing_store_setup' );

// widgets
function clothing_store_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'clothing-store' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'clothing-store' ),
		'before_widget' => '<section id="%1$s" class="widget wow zoomIn %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget_container"><h3 class="widget-title">',
		'after_title'   => '</h3></div>',
	) );

	register_sidebar( array(
		'name'          => __( 'Page Sidebar', 'clothing-store' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Add widgets here to appear in your pages and posts', 'clothing-store' ),
		'before_widget' => '<section id="%1$s" class="widget wow zoomIn %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget_container"><h3 class="widget-title">',
		'after_title'   => '</h3></div>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Sidebar 3', 'clothing-store' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'clothing-store' ),
		'before_widget' => '<section id="%1$s" class="widget wow zoomIn %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<div class="widget_container"><h3 class="widget-title">',
		'after_title'   => '</h3></div>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 1', 'clothing-store' ),
		'id'            => 'footer-1',
		'description'   => __( 'Add widgets here to appear in your footer.', 'clothing-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 2', 'clothing-store' ),
		'id'            => 'footer-2',
		'description'   => __( 'Add widgets here to appear in your footer.', 'clothing-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 3', 'clothing-store' ),
		'id'            => 'footer-3',
		'description'   => __( 'Add widgets here to appear in your footer.', 'clothing-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 4', 'clothing-store' ),
		'id'            => 'footer-4',
		'description'   => __( 'Add widgets here to appear in your footer.', 'clothing-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'clothing_store_widgets_init' );

// fonts
function clothing_store_fonts_url(){
	$font_url = '';
	$font_family = array();
	$font_family[] = 'Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900';

	$clothing_store_query_args = array(
		'family'	=> rawurlencode(implode('|',$font_family)),
	);
	$font_url = add_query_arg($clothing_store_query_args,'//fonts.googleapis.com/css');
	return $font_url;
	$contents = wptt_get_webfont_url( esc_url_raw( $fonts_url ) );
}

//Enqueue scripts and styles.
function clothing_store_scripts() {

	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'clothing-store-fonts', clothing_store_fonts_url(), array());

	//Bootstarp
	wp_enqueue_style( 'bootstrap-style', get_template_directory_uri().'/assets/css/bootstrap.css' );

	// Theme stylesheet.
	wp_enqueue_style( 'clothing-store-style', get_stylesheet_uri() );

		wp_style_add_data('clothing-store-style', 'rtl', 'replace');

	// Theme Customize CSS.
	require get_parent_theme_file_path( 'inc/extra_customization.php' );
	wp_add_inline_style( 'clothing-store-style',$clothing_store_custom_style );

	//font-awesome
	wp_enqueue_style( 'font-awesome-style', get_template_directory_uri().'/assets/css/fontawesome-all.css' );

	// Block Style
	wp_enqueue_style( 'clothing-store-block-style', esc_url( get_template_directory_uri() ).'/assets/css/blocks.css' );

	//Custom JS
	wp_enqueue_script( 'clothing-store-custom.js', get_theme_file_uri( '/assets/js/theme-script.js' ), array( 'jquery' ), true );

	//Nav Focus JS
	wp_enqueue_script( 'clothing-store-navigation-focus', get_theme_file_uri( '/assets/js/navigation-focus.js' ), array( 'jquery' ), true );


	//Bootstarp JS
	wp_enqueue_script( 'bootstrap-js', get_theme_file_uri( '/assets/js/bootstrap.js' ), array( 'jquery' ),true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if (get_option('clothing_store_animation_enable', false) !== 'off') {
		//wow.js
		wp_enqueue_script( 'clothing-store-wow-js', get_theme_file_uri( '/assets/js/wow.js' ), array( 'jquery' ), true );

		//animate.css
		wp_enqueue_style( 'clothing-store-animate-css', get_template_directory_uri().'/assets/css/animate.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'clothing_store_scripts' );

function clothing_store_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'clothing-store-block-editor-style', trailingslashit( esc_url ( get_template_directory_uri() ) ) . '/assets/css/editor-blocks.css' );

	// Add custom fonts.
	wp_enqueue_style( 'clothing-store-fonts', clothing_store_fonts_url(), array());
}
add_action( 'enqueue_block_editor_assets', 'clothing_store_block_editor_styles' );

# Load scripts and styles.(fontawesome)
add_action( 'customize_controls_enqueue_scripts', 'clothing_store_customize_controls_register_scripts' );
function clothing_store_customize_controls_register_scripts() {
	wp_enqueue_style( 'clothing-store-ctypo-customize-controls-style', trailingslashit( esc_url(get_template_directory_uri()) ) . '/assets/css/customize-controls.css' );
}

// enque files
require get_parent_theme_file_path( '/inc/custom-header.php' );
require get_parent_theme_file_path( '/inc/template-tags.php' );
require get_parent_theme_file_path( '/inc/template-functions.php' );
require get_parent_theme_file_path( '/inc/customizer.php' );
require get_parent_theme_file_path( '/inc/typofont.php' );
require get_template_directory() .'/inc/TGM/tgm.php';
require get_parent_theme_file_path( '/inc/dashboard/dashboard.php' );
require get_parent_theme_file_path( '/inc/wptt-webfont-loader.php' );
require get_parent_theme_file_path( '/inc/breadcrumb.php' );
require get_parent_theme_file_path( 'inc/sortable/sortable_control.php' );