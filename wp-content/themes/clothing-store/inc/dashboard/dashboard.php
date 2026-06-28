<?php

add_action( 'admin_menu', 'clothing_store_gettingstarted' );
function clothing_store_gettingstarted() {    	
	add_theme_page( esc_html__('Begin Installation', 'clothing-store'), esc_html__('Begin Installation', 'clothing-store'), 'edit_theme_options', 'clothing-store-guide-page', 'clothing_store_guide');   
}

function clothing_store_admin_theme_style() {
   wp_enqueue_style('clothing-store-custom-admin-style', esc_url(get_template_directory_uri()) . '/inc/dashboard/dashboard.css');
}
add_action('admin_enqueue_scripts', 'clothing_store_admin_theme_style');

if ( ! defined( 'CLOTHING_STORE_SUPPORT' ) ) {
	define('CLOTHING_STORE_SUPPORT',__('https://wordpress.org/support/theme/clothing-store/','clothing-store'));
}
if ( ! defined( 'CLOTHING_STORE_REVIEW' ) ) {
	define('CLOTHING_STORE_REVIEW',__('https://wordpress.org/support/theme/clothing-store/reviews/','clothing-store'));
}
if ( ! defined( 'CLOTHING_STORE_LIVE_DEMO' ) ) {
define('CLOTHING_STORE_LIVE_DEMO',__('https://trial.ovationthemes.com/clothing-store/','clothing-store'));
}
if ( ! defined( 'CLOTHING_STORE_BUY_PRO' ) ) {
define('CLOTHING_STORE_BUY_PRO',__('https://www.ovationthemes.com/products/clothing-store-wordpress-theme','clothing-store'));
}
if ( ! defined( 'CLOTHING_STORE_PRO_DOC' ) ) {
define('CLOTHING_STORE_PRO_DOC',__('https://trial.ovationthemes.com/docs/ot-clothing-store-pro-doc/','clothing-store'));
}
if ( ! defined( 'CLOTHING_STORE_FREE_DOC' ) ) {
define('CLOTHING_STORE_FREE_DOC',__('https://trial.ovationthemes.com/docs/ot-clothing-store-free-doc/','clothing-store'));
}
if ( ! defined( 'CLOTHING_STORE_THEME_NAME' ) ) {
define('CLOTHING_STORE_THEME_NAME',__('Premium Clothing Store Theme','clothing-store'));
}

/**
 * Theme Info Page
 */
function clothing_store_guide() {

	// Theme info
	$return = add_query_arg( array()) ;
	$theme = wp_get_theme(); ?>

	<div class="getting-started__header">
		<div class="col-md-10">
			<h2><?php echo esc_html( $theme ); ?></h2>
			<p><?php esc_html_e('Version: ', 'clothing-store'); ?><?php echo esc_html($theme['Version']);?></p>
		</div>
		<div class="col-md-2">
			<div class="btn_box">
				<a class="button-primary" href="<?php echo esc_url( CLOTHING_STORE_FREE_DOC ); ?>" target="_blank"><?php esc_html_e('Theme Documentation', 'clothing-store'); ?></a>
				<a class="button-primary" href="<?php echo esc_url( CLOTHING_STORE_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Support', 'clothing-store'); ?></a>
				<a class="button-primary" href="<?php echo esc_url( CLOTHING_STORE_REVIEW ); ?>" target="_blank"><?php esc_html_e('Review', 'clothing-store'); ?></a>
			</div>
		</div>
	</div>

	<div class="wrap getting-started">
		<div class="container">
			<div class="col-md-9">
				<div class="leftbox">
					<h3><?php esc_html_e('Documentation','clothing-store'); ?></h3>
					<p><?php esc_html_e('To step the Clothing Store theme follow the below steps.','clothing-store'); ?></p>

					<h4><?php esc_html_e('1. Setup Logo','clothing-store'); ?></h4>
					<p><?php esc_html_e('Go to dashboard >> Appearance >> Customize >> Site Identity >> Upload your logo or Add site title and site description.','clothing-store'); ?></p>
					<a class="button-primary" href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','clothing-store'); ?></a>

					<h4><?php esc_html_e('2. Setup Contact Info','clothing-store'); ?></h4>
					<p><?php esc_html_e('Go to dashboard >> Appearance >> Customize >> Contact info >> Add your phone number and email address.','clothing-store'); ?></p>
					<a class="button-primary" href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=clothing_store_top') ); ?>" target="_blank"><?php esc_html_e('Add Contact Info','clothing-store'); ?></a>

					<h4><?php esc_html_e('3. Setup Menus','clothing-store'); ?></h4>
					<p><?php esc_html_e('Go to dashboard >> Appearance >> Menus >> Create Menus >> Add pages, post or custom link then save it.','clothing-store'); ?></p>
					<a class="button-primary" href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Add Menus','clothing-store'); ?></a>

					<h4><?php esc_html_e('4. Setup Social Icons','clothing-store'); ?></h4>
					<p><?php esc_html_e('Go to dashboard >> Appearance >> Customize >> Social Media >> Add social links.','clothing-store'); ?></p>
					<a class="button-primary" href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=clothing_store_urls') ); ?>" target="_blank"><?php esc_html_e('Add Social Icons','clothing-store'); ?></a>

					<h4><?php esc_html_e('5. Setup Footer','clothing-store'); ?></h4>
					<p><?php esc_html_e('Go to dashboard >> Appearance >> Widgets >> Add widgets in footer 1, footer 2, footer 3, footer 4. >> ','clothing-store'); ?></p>
					<a class="button-primary" href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widgets','clothing-store'); ?></a>

					<h4><?php esc_html_e('5. Setup Footer Text','clothing-store'); ?></h4>
					<p><?php esc_html_e('Go to dashboard >> Appearance >> Customize >> Footer Text >> Add copyright text. >> ','clothing-store'); ?></p>
					<a class="button-primary" href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=clothing_store_footer_copyright') ); ?>" target="_blank"><?php esc_html_e('Footer Text','clothing-store'); ?></a>

					<h3><?php esc_html_e('Setup Home Page','clothing-store'); ?></h3>
					<p><?php esc_html_e('To step the home page follow the below steps.','clothing-store'); ?></p>

					<h4><?php esc_html_e('1. Setup Page','clothing-store'); ?></h4>
					<p><?php esc_html_e('Go to dashboard >> Pages >> Add New Page >> Select "Custom Home Page" from templates >> Publish it.','clothing-store'); ?></p>
					<a class="dashboard_add_new_page button-primary"><?php esc_html_e('Add New Page','clothing-store'); ?></a>

					<h4><?php esc_html_e('2. Setup Slider','clothing-store'); ?></h4>
					<p><?php esc_html_e('Go to dashboard >> Post >> Add New Post >> Add title, content and featured image >> Publish it.','clothing-store'); ?></p>
					<p><?php esc_html_e('Go to dashboard >> Appearance >> Customize >> Slider Settings >> Select post.','clothing-store'); ?></p>
					<a class="button-primary" href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=clothing_store_slider_section') ); ?>" target="_blank"><?php esc_html_e('Add Slider','clothing-store'); ?></a>

					<h4><?php esc_html_e('3. Setup Products','clothing-store'); ?></h4>
					<p><?php esc_html_e('Go to dashboard >> Woo-commerce >> Add New Product >> Add title, content, Select >> Publish it.','clothing-store'); ?></p>
					<a class="button-primary" href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=clothing_store_millions_of_hours_section') ); ?>" target="_blank"><?php esc_html_e('Add Product','clothing-store'); ?></a>
				</div>
          	</div>
			<div class="col-md-3">
				<h3><?php echo esc_html(CLOTHING_STORE_THEME_NAME); ?></h3>
				<img class="clothing_store_img_responsive" style="width: 100%;" src="<?php echo esc_url( $theme->get_screenshot() ); ?>" />
				<div class="pro-links">
					<hr>
					<a class="button-primary livedemo" href="<?php echo esc_url( CLOTHING_STORE_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'clothing-store'); ?></a>
					<a class="button-primary buynow" href="<?php echo esc_url( CLOTHING_STORE_BUY_PRO ); ?>" target="_blank"><?php esc_html_e('Buy Now', 'clothing-store'); ?></a>
					<a class="button-primary docs" href="<?php echo esc_url( CLOTHING_STORE_PRO_DOC ); ?>" target="_blank"><?php esc_html_e('Documentation', 'clothing-store'); ?></a>
					<hr>
				</div>
				<ul style="padding-top:10px">
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Responsive Design', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Boxed or fullwidth layout', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Shortcode Support', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Demo Importer', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Section Reordering', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Contact Page Template', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Multiple Blog Layouts', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Unlimited Color Options', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Designed with HTML5 and CSS3', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Customizable Design & Code', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Cross Browser Support', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Detailed Documentation Included', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Stylish Custom Widgets', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Patterns Background', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('WPML Compatible (Translation Ready)', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Woo-commerce Compatible', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Full Support', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('10+ Sections', 'clothing-store');?> </li>
                    <li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Live Customizer', 'clothing-store');?> </li>
                   	<li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('AMP Ready', 'clothing-store');?> </li>
                   	<li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Clean Code', 'clothing-store');?> </li>
                   	<li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('SEO Friendly', 'clothing-store');?> </li>
                   	<li class="upsell-clothing_store"> <div class="dashicons dashicons-yes"></div> <?php esc_html_e('Supper Fast', 'clothing-store');?> </li>                    
                </ul>
        	</div>
		</div>
	</div>

<?php }?>
