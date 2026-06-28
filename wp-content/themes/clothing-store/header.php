<?php
/**
 * The header for our theme
 *
 * @subpackage Clothing Store
 * @since 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
	if ( function_exists( 'wp_body_open' ) ) {
	    wp_body_open();
	} else {
	    do_action( 'wp_body_open' );
	}
?>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'clothing-store' ); ?></a>
	<?php if( get_option('clothing_store_theme_loader',true) != 'off'){ ?>
		<?php $clothing_store_loader_option = get_theme_mod( 'clothing_store_loader_style','style_one');
		if($clothing_store_loader_option == 'style_one'){ ?>
			<div id="preloader" class="circle">
				<div id="loader"></div>
			</div>
		<?php }
		else if($clothing_store_loader_option == 'style_two'){ ?>
			<div id="preloader">
				<div class="spinner">
					<div class="rect1"></div>
					<div class="rect2"></div>
					<div class="rect3"></div>
					<div class="rect4"></div>
					<div class="rect5"></div>
				</div>
			</div>
		<?php }?>
	<?php }?>
	<div id="page" class="site">
		<div id="header">
			<div class="wrap_figure">
				<div class="top_bar py-2 text-center text-lg-start text-md-start wow slideInDown">
					<div class="container">
						<div class="row">							
							<div class="col-lg-4 col-md-4 col-sm-4 align-self-center text-md-center text-lg-start header-text">
								<?php if( get_theme_mod('clothing_store_top_phone') != '' ){ ?>
									<span><i class="<?php echo esc_html(get_theme_mod('clothing_store_top_phone_icon','fas fa-phone-volume')); ?> me-3"></i><?php esc_html_e('HOTLINE','clothing-store'); ?> <?php echo esc_html(get_theme_mod('clothing_store_top_phone','')); ?></span>
								<?php }?>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 align-self-center text-md-center text-lg-start header-text">
								<?php if( get_theme_mod('clothing_store_top_text') != '' ){ ?>
									<span><?php echo esc_html(get_theme_mod('clothing_store_top_text','')); ?></span>
								<?php }?>
							</div>							
							<div class="col-lg-4 col-md-4 col-sm-4 align-self-center text-md-end social-box">
								<?php if( get_option('clothing_store_social_enable',false) != 'off'){ ?>
									<?php
										$clothing_store_header_facebook_target = esc_attr(get_option('clothing_store_header_facebook_target','true'));
							            $clothing_store_header_twt_target = esc_attr(get_option('clothing_store_header_twt_target','true'));
							            $clothing_store_header_linkedin_target = esc_attr(get_option('clothing_store_header_linkedin_target','true'));
							            $clothing_store_header_pinterest_target = esc_attr(get_option('clothing_store_header_pinterest_target','true'));
	          						?>
									<?php if( get_theme_mod('clothing_store_facebook') != ''){ ?>
							            <a target="<?php echo $clothing_store_header_facebook_target !='off' ? '_blank' : '' ?>" href="<?php echo esc_url(get_theme_mod('clothing_store_facebook','')); ?>">
							              <i class="<?php echo esc_attr(get_theme_mod('clothing_store_facebook_icon','fab fa-facebook')); ?>"></i>
							            </a>
							        <?php }?>
									<?php if( get_theme_mod('clothing_store_twitter') != ''){ ?>
								            <a target="<?php echo $clothing_store_header_twt_target !='off' ? '_blank' : '' ?>" href="<?php echo esc_url(get_theme_mod('clothing_store_twitter','')); ?>">
								              <i class="<?php echo esc_attr(get_theme_mod('clothing_store_twitter_icon','fab fa-x-twitter')); ?>"></i>
								            </a>
						          	<?php }?>
						          	<?php if( get_theme_mod('clothing_store_linkedin') != ''){ ?>
							            <a target="<?php echo $clothing_store_header_linkedin_target !='off' ? '_blank' : '' ?>" href="<?php echo esc_url(get_theme_mod('clothing_store_linkedin','')); ?>">
							              <i class="<?php echo esc_attr(get_theme_mod('clothing_store_linkedin_icon','fab fa-linkedin')); ?>"></i>
							            </a>
						          	<?php }?>
								    <?php if( get_theme_mod('clothing_store_pinterest') != ''){ ?>
								            <a target="<?php echo $clothing_store_header_pinterest_target !='off' ? '_blank' : '' ?>" href="<?php echo esc_url(get_theme_mod('clothing_store_pinterest','')); ?>">
								              <i class="<?php echo esc_attr(get_theme_mod('clothing_store_pinterest_icon','fab fa-pinterest-p')); ?>"></i>
								            </a>
							        <?php }?>
						        <?php } ?>
						        <?php if( get_option('clothing_store_myaccount_enable',false) != 'off'){ ?>
									<?php if ( class_exists( 'WooCommerce' ) ) { ?>
										<?php if ( is_user_logged_in() ) { ?>
											<a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="woo me-3 mx-md-3"><i class="fas fa-user me-3"></i><?php esc_html_e( 'MY ACCOUNT','clothing-store');?></a>
											<?php } else { ?>
												<a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="me-3 mx-md-3"><i class="fas fa-sign-out-alt me-3"></i><?php esc_html_e( 'LOGIN / REGISTER','clothing-store');?></a>
											<?php } ?>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="menu_header py-3">
					<div class="container">
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-3 align-self-center">
								<div class="logo text-center text-md-start text-sm-start py-3 py-md-0">
							        <?php if ( has_custom_logo() ) : ?>
					            		<?php the_custom_logo(); ?>
						            <?php endif; ?>
					              	<?php $clothing_store_blog_info = get_bloginfo( 'name' ); ?>
						                <?php if ( ! empty( $clothing_store_blog_info ) ) : ?>
						                  	<?php if ( is_front_page() && is_home() ) : ?>
												<?php if( get_option('clothing_store_logo_title',false) != 'off'){ ?>
						                    	<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
												<?php }?>
						                  	<?php else : ?>
												<?php if( get_option('clothing_store_logo_title',false) != 'off'){ ?>
					                      		<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
												<?php }?>
					                  		<?php endif; ?>
						                <?php endif; ?>
						                <?php
					                  		$clothing_store_description = get_bloginfo( 'description', 'display' );
						                  	if ( $clothing_store_description || is_customize_preview() ) :
						                ?>
						                <?php if( get_option('clothing_store_logo_text',true) != 'off'){ ?>
					                  	<p class="site-description">
					                    	<?php echo esc_html($clothing_store_description); ?>
					                  	</p>
					                  <?php }?>
					              	<?php endif; ?>
							    </div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 align-self-center">
								<?php if( get_option('clothing_store_product_search_enable',false) != 'off'){ ?>
								<div class="product-search">
									<?php
									if ( class_exists( 'WooCommerce' ) ) { ?>
										<?php get_product_search_form(); ?>
									<?php }?>
								</div>
								<?php }?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 ps-0 align-self-center text-center mt-2 mt-md-0 text-md-end">
								<?php if( get_option('clothing_store_cart_enable',false) != 'off'){ ?>
									<?php
										if ( class_exists( 'WooCommerce' ) ) { ?>
										<?php global $woocommerce; ?>
										<a href="<?php echo esc_html( wc_get_cart_url()) ?>" class="header-cart"><i class="fas fa-shopping-cart"></i> <?php esc_html_e('CART','clothing-store'); ?> <span>( <?php echo esc_html( $woocommerce->cart->cart_contents_count )?></span> )</a>
									<?php }?>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
				<div class="menu_header_box py-2 wow slideInUp">
					<div class="fixed_header">
					<div class="container">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-6 col-9 align-self-center">
								<?php if ( class_exists( 'WooCommerce' ) ) { ?>
									<div class="head_category">        
										<a class="cat-dropdown-toggle" href="#" role="button" id="dropdownMenuLink-cat" data-toggle="dropdown-cat" aria-haspopup="true" aria-expanded="false">CATEGORIES</a>
										<div class="cat-dropdown-menu" aria-labelledby="dropdownMenuLink-cat">
									        <ul class="cat-list">
									            <?php
									            $args = array(
									                'orderby'    => 'title',
									                'order'      => 'ASC',
									                'hide_empty' => 0,
									                'parent'     => 0
									            );
									            $product_categories = get_terms('product_cat', $args);
									            $count = count($product_categories);
									            if ($count > 0) {
									                foreach ($product_categories as $product_category) {
									                    $product_cat_id = $product_category->term_id;
									                    $cat_link = get_category_link($product_cat_id);
									                    if ($product_category->category_parent == 0) {
									            ?>
									                        <li class="cat-inn">
									                            <a href="<?php echo esc_url($cat_link); ?>"><?php echo esc_html($product_category->name); ?></a>
									                        </li>
									            <?php
									                    }
									                }
									            }
									            ?>
									        </ul>
										</div>
									</div>
								<?php }?>
							</div>
							<div class="col-lg-8 col-md-8 col-sm-6 col-3 align-self-center">
								<div class="toggle-menu gb_menu text-end">
									<button onclick="clothing_store_gb_Menu_open()" class="gb_toggle p-2"><i class="fas fa-ellipsis-h"></i><p class="mb-0"><?php esc_html_e('Menu','clothing-store'); ?></p></button>
								</div>
				   				<?php get_template_part('template-parts/navigation/navigation'); ?>
			   				</div>
			   			</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
