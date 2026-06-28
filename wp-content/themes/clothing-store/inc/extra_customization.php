<?php

$clothing_store_custom_style= "";

//theme width

$clothing_store_theme_width = get_theme_mod( 'clothing_store_width_options','full_width');

if($clothing_store_theme_width == 'full_width'){

$clothing_store_custom_style .='body{';

	$clothing_store_custom_style .='max-width: 100%;';

$clothing_store_custom_style .='}';

}else if($clothing_store_theme_width == 'container'){

$clothing_store_custom_style .='body{';

	$clothing_store_custom_style .='width: 100%; padding-right: 15px; padding-left: 15px;  margin-right: auto !important; margin-left: auto !important;';

$clothing_store_custom_style .='}';

$clothing_store_custom_style .='@media screen and (min-width: 601px){';

$clothing_store_custom_style .='body{';

    $clothing_store_custom_style .='max-width: 720px;';
    
$clothing_store_custom_style .='} }';

$clothing_store_custom_style .='@media screen and (min-width: 992px){';

$clothing_store_custom_style .='body{';

    $clothing_store_custom_style .='max-width: 960px;';
    
$clothing_store_custom_style .='} }';

$clothing_store_custom_style .='@media screen and (min-width: 1200px){';

$clothing_store_custom_style .='body{';

    $clothing_store_custom_style .='max-width: 1140px;';
    
$clothing_store_custom_style .='} }';

$clothing_store_custom_style .='@media screen and (min-width: 1400px){';

$clothing_store_custom_style .='body{';

    $clothing_store_custom_style .='max-width: 1320px;';
    
$clothing_store_custom_style .='} }';

$clothing_store_custom_style .='.carousel-control-prev, .carousel-control-next{';

	$clothing_store_custom_style .='top:auto';

$clothing_store_custom_style .='}';

$clothing_store_custom_style .='.discount-box {';

	$clothing_store_custom_style .='right: 9px';

$clothing_store_custom_style .='}';

$clothing_store_custom_style .='@media screen and (max-width:600px){';

$clothing_store_custom_style .='body{';

    $clothing_store_custom_style .='max-width: 100%; padding-right:0px; padding-left: 0px';
    
$clothing_store_custom_style .='} }';

}else if($clothing_store_theme_width == 'container_fluid'){

$clothing_store_custom_style .='body{';

	$clothing_store_custom_style .='width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;';

$clothing_store_custom_style .='}';

$clothing_store_custom_style .='@media screen and (max-width:600px){';

$clothing_store_custom_style .='body{';

    $clothing_store_custom_style .='max-width: 100%; padding-right:0px; padding-left: 0px';
    
$clothing_store_custom_style .='} }';
}

// sticky header

	if (false === get_option('clothing_store_sticky_header')) {
	    add_option('clothing_store_sticky_header', 'off');
	}

	// Define the custom CSS based on the 'clothing_store_sticky_header' option

	if (get_option('clothing_store_sticky_header', 'off') !== 'on') {
	    $clothing_store_custom_style .= '.fixed_header.fixed {';
	    $clothing_store_custom_style .= 'position: static;';
	    $clothing_store_custom_style .= '}';
	}

	if (get_option('clothing_store_sticky_header', 'off') !== 'off') {
	    $clothing_store_custom_style .= '.fixed_header.fixed {';
	    $clothing_store_custom_style .= 'position: fixed; background: #fff; padding: 10px 5px;';
	    $clothing_store_custom_style .= '}';

	    $clothing_store_custom_style .= '.admin-bar .fixed {';
	    $clothing_store_custom_style .= ' margin-top: 32px;';
	    $clothing_store_custom_style .= '}';
	}
	
// Scroll-top-position

$clothing_store_scroll_options = get_theme_mod( 'clothing_store_scroll_options','right_align');

if($clothing_store_scroll_options == 'right_align'){

$clothing_store_custom_style .='.scroll-top button{';

	$clothing_store_custom_style .='';

$clothing_store_custom_style .='}';

}else if($clothing_store_scroll_options == 'center_align'){

$clothing_store_custom_style .='.scroll-top button{';

	$clothing_store_custom_style .='right: 0; left:0; margin: 0 auto; top:85% !important';

$clothing_store_custom_style .='}';

}else if($clothing_store_scroll_options == 'left_align'){

$clothing_store_custom_style .='.scroll-top button{';

	$clothing_store_custom_style .='right: auto; left:5%; margin: 0 auto';

$clothing_store_custom_style .='}';
}

// logo height

$clothing_store_logo_max_height = get_theme_mod('clothing_store_logo_max_height','100');

if($clothing_store_logo_max_height != false){

$clothing_store_custom_style .='.custom-logo-link img{';

	$clothing_store_custom_style .='max-height: '.esc_html($clothing_store_logo_max_height).'px;';

$clothing_store_custom_style .='}';
}

// text-transform

$clothing_store_text_transform = get_theme_mod( 'clothing_store_menu_text_transform','CAPITALISE');
if($clothing_store_text_transform == 'CAPITALISE'){

$clothing_store_custom_style .='nav#top_gb_menu ul li a{';

	$clothing_store_custom_style .='text-transform: capitalize ; font-size: 14px;';

$clothing_store_custom_style .='}';

}else if($clothing_store_text_transform == 'UPPERCASE'){

$clothing_store_custom_style .='nav#top_gb_menu ul li a{';

	$clothing_store_custom_style .='text-transform: uppercase ; font-size: 14px;';

$clothing_store_custom_style .='}';

}else if($clothing_store_text_transform == 'LOWERCASE'){

$clothing_store_custom_style .='nav#top_gb_menu ul li a{';

	$clothing_store_custom_style .='text-transform: lowercase ; font-size: 14px;';

$clothing_store_custom_style .='}';
}

//Slider-content-alignment

$clothing_store_slider_content_alignment = get_theme_mod( 'clothing_store_slider_content_alignment','LEFT-ALIGN');

if($clothing_store_slider_content_alignment == 'LEFT-ALIGN'){

$clothing_store_custom_style .='.carousel-caption{';

	$clothing_store_custom_style .='text-align:left; right: 20%; left: 15%;';

$clothing_store_custom_style .='}';

$clothing_store_custom_style .='@media screen and (max-width:991px){';

$clothing_store_custom_style .='#slider .carousel-caption{';

    $clothing_store_custom_style .='right: 0; left: 5%;';
    
$clothing_store_custom_style .='} }';

$clothing_store_custom_style .='@media screen and (max-width:936px){';

$clothing_store_custom_style .='#slider .carousel-caption{';

    $clothing_store_custom_style .='right: 5%; left: 15%;';
    
$clothing_store_custom_style .='} }';


}else if($clothing_store_slider_content_alignment == 'CENTER-ALIGN'){

$clothing_store_custom_style .='.carousel-caption{';

	$clothing_store_custom_style .='text-align:center; right: 20%; left: 15%;';

$clothing_store_custom_style .='}';

$clothing_store_custom_style .='@media screen and (max-width:991px){';

$clothing_store_custom_style .='#slider .carousel-caption{';

    $clothing_store_custom_style .='right: 0%; left: 5%';
    
$clothing_store_custom_style .='} }';


}else if($clothing_store_slider_content_alignment == 'RIGHT-ALIGN'){

$clothing_store_custom_style .='.carousel-caption{';

	$clothing_store_custom_style .='text-align:right; right: 20%; left: 15%;';

$clothing_store_custom_style .='}';

$clothing_store_custom_style .='@media screen and (max-width:991px){';

$clothing_store_custom_style .='#slider .carousel-caption{';

    $clothing_store_custom_style .='right: 0%; left: 5%';
    
$clothing_store_custom_style .='} }';

}

//related products
if( get_option( 'clothing_store_related_product',true) != 'on') {

$clothing_store_custom_style .='.related.products{';

	$clothing_store_custom_style .='display: none;';
	
$clothing_store_custom_style .='}';
}

if( get_option( 'clothing_store_related_product',true) != 'off') {

$clothing_store_custom_style .='.related.products{';

	$clothing_store_custom_style .='display: block;';
	
$clothing_store_custom_style .='}';
}
// footer text alignment
$clothing_store_footer_content_alignment = get_theme_mod( 'clothing_store_footer_content_alignment','CENTER-ALIGN');

if($clothing_store_footer_content_alignment == 'LEFT-ALIGN'){

$clothing_store_custom_style .='.site-info{';

	$clothing_store_custom_style .='text-align:left; padding-left: 30px;';

$clothing_store_custom_style .='}';

$clothing_store_custom_style .='.site-info a{';

	$clothing_store_custom_style .='padding-left: 30px;';

$clothing_store_custom_style .='}';


}else if($clothing_store_footer_content_alignment == 'CENTER-ALIGN'){

$clothing_store_custom_style .='.site-info{';

	$clothing_store_custom_style .='text-align:center;';

$clothing_store_custom_style .='}';


}else if($clothing_store_footer_content_alignment == 'RIGHT-ALIGN'){

$clothing_store_custom_style .='.site-info{';

	$clothing_store_custom_style .='text-align:right; padding-right: 30px;';

$clothing_store_custom_style .='}';

$clothing_store_custom_style .='.site-info a{';

	$clothing_store_custom_style .='padding-right: 30px;';

$clothing_store_custom_style .='}';

}

// slider button
$mobile_button_setting = get_option('clothing_store_slider_button_mobile_show_hide', '1');
$main_button_setting = get_option('clothing_store_slider_button_show_hide', '1');

$clothing_store_custom_style .= '#slider .home-btn {';

if ($main_button_setting == 'off') {
    $clothing_store_custom_style .= 'display: none;';
}

$clothing_store_custom_style .= '}';

// Add media query for mobile devices
$clothing_store_custom_style .= '@media screen and (max-width: 600px) {';
if ($main_button_setting == 'off' || $mobile_button_setting == 'off') {
    $clothing_store_custom_style .= '#slider .home-btn { display: none; }';
}
$clothing_store_custom_style .= '}';


// scroll button
$mobile_scroll_setting = get_option('clothing_store_scroll_enable_mobile', '1');
$main_scroll_setting = get_option('clothing_store_scroll_enable', '1');

$clothing_store_custom_style .= '.scrollup {';

if ($main_scroll_setting == 'off') {
    $clothing_store_custom_style .= 'display: none;';
}

$clothing_store_custom_style .= '}';

// Add media query for mobile devices
$clothing_store_custom_style .= '@media screen and (max-width: 600px) {';
if ($main_scroll_setting == 'off' || $mobile_scroll_setting == 'off') {
    $clothing_store_custom_style .= '.scrollup { display: none; }';
}
$clothing_store_custom_style .= '}';

// theme breadcrumb
$mobile_breadcrumb_setting = get_option('clothing_store_enable_breadcrumb_mobile', '1');
$main_breadcrumb_setting = get_option('clothing_store_enable_breadcrumb', '1');

$clothing_store_custom_style .= '.archieve_breadcrumb {';

if ($main_breadcrumb_setting == 'off') {
    $clothing_store_custom_style .= 'display: none;';
}

$clothing_store_custom_style .= '}';

// Add media query for mobile devices
$clothing_store_custom_style .= '@media screen and (max-width: 600px) {';
if ($main_breadcrumb_setting == 'off' || $mobile_breadcrumb_setting == 'off') {
    $clothing_store_custom_style .= '.archieve_breadcrumb { display: none; }';
}
$clothing_store_custom_style .= '}';

// single post and page breadcrumb
$mobile_single_breadcrumb_setting = get_option('clothing_store_single_enable_breadcrumb_mobile', '1');
$main_single_breadcrumb_setting = get_option('clothing_store_single_enable_breadcrumb', '1');

$clothing_store_custom_style .= '.single_breadcrumb {';

if ($main_single_breadcrumb_setting == 'off') {
    $clothing_store_custom_style .= 'display: none;';
}

$clothing_store_custom_style .= '}';

// Add media query for mobile devices
$clothing_store_custom_style .= '@media screen and (max-width: 600px) {';
if ($main_single_breadcrumb_setting == 'off' || $mobile_single_breadcrumb_setting == 'off') {
    $clothing_store_custom_style .= '.single_breadcrumb { display: none; }';
}
$clothing_store_custom_style .= '}';

// woocommerce breadcrumb
$mobile_woo_breadcrumb_setting = get_option('clothing_store_woocommerce_enable_breadcrumb_mobile', '1');
$main_woo_breadcrumb_setting = get_option('clothing_store_woocommerce_enable_breadcrumb', '1');

$clothing_store_custom_style .= '.woocommerce-breadcrumb {';

if ($main_woo_breadcrumb_setting == 'off') {
    $clothing_store_custom_style .= 'display: none;';
}

$clothing_store_custom_style .= '}';

// Add media query for mobile devices
$clothing_store_custom_style .= '@media screen and (max-width: 600px) {';
if ($main_woo_breadcrumb_setting == 'off' || $mobile_woo_breadcrumb_setting == 'off') {
    $clothing_store_custom_style .= '.woocommerce-breadcrumb { display: none; }';
}
$clothing_store_custom_style .= '}';