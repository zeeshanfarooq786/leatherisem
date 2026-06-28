<?php
/**
 * Template Name: Custom Home Page
 */
get_header(); ?>

<main id="content">
  <?php if( get_option('clothing_store_slider_arrows') == '1'){ ?>
    <section id="slider" class="py-md-5">
      <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
        <?php
          for ( $i = 1; $i <= 4; $i++ ) {
            $clothing_store_mod =  get_theme_mod( 'clothing_store_post_setting' . $i );
            if ( 'page-none-selected' != $clothing_store_mod ) {
              $clothing_store_slide_post[] = $clothing_store_mod;
            }
          }

          if( !empty($clothing_store_slide_post) ) :
          $clothing_store_args = array(
            'post_type' =>array('post'),
            'post__in' => $clothing_store_slide_post,
            'ignore_sticky_posts'  => true, // Exclude sticky posts by default
          );

          // Check if specific posts are selected
          if (empty($clothing_store_slide_post) && is_sticky()) {
              $clothing_store_args['post__in'] = get_option('sticky_posts');
          }

          $clothing_store_query = new WP_Query( $clothing_store_args );
          if ( $clothing_store_query->have_posts() ) :
            $i = 1;
        ?>
        <div class="carousel-inner" role="listbox">
          <div class="container-md">
            <?php  while ( $clothing_store_query->have_posts() ) : $clothing_store_query->the_post(); ?>
            <div <?php if($i == 1){echo 'class="carousel-item active"';} else{ echo 'class="carousel-item"';}?>>
              <div class="row">
                <div class="col-lg-6 col-md-6 slide-content">
                  <div class="carousel-caption slider-inner">
                    <h2 class="slider-title"><?php the_title();?></h2>
                    <?php if( get_option('clothing_store_slider_excerpt_show_hide',false) != 'off'){ ?>
                      <p class="slider-excerpt mb-0"><?php echo wp_trim_words(get_the_content(), get_theme_mod('clothing_store_slider_excerpt_count',20) );?></p>
                    <?php } ?>
                    <div class="home-btn my-4">
                      <a class="py-md-3 px-md-4 py-2 px-3" href="<?php the_permalink(); ?>"><i class="fas fa-shopping-cart me-3"></i><?php echo esc_html(get_theme_mod('clothing_store_slider_read_more',__('SHOP NOW','clothing-store'))); ?></a>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 image-content">
                  <?php if(has_post_thumbnail()){ ?>
                  <img src="<?php the_post_thumbnail_url('full'); ?>"/>
                <?php }else{?>
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/slider.jpg" alt="" />
                <?php } ?>
                  <div class="discount-box">
                    <?php if( get_theme_mod('clothing_store_product_discount_text') != '' ){ ?>
                      <h3><?php echo esc_html(get_theme_mod('clothing_store_product_discount_text','')); ?></h3>
                    <?php }?>
                    <div class="row">
                      <div class="col-8 align-self-center mx-0 px-0">
                        <?php if( get_theme_mod('clothing_store_product_discount_number') != '' ){ ?>
                          <p class="disc-no my-0 text-center"><?php echo esc_html(get_theme_mod('clothing_store_product_discount_number','')); ?></p>
                        <?php }?>
                      </div>
                      <div class="col-4 align-self-center mx-0 px-0">
                        <p class="my-0 disc-sign"><?php esc_html_e('%','clothing-store'); ?></p>
                        <p class="my-0"><?php esc_html_e('Off','clothing-store'); ?></p>
                      </div>
                    </div>
                   
                  </div>
                </div>
              </div>
            </div>
            <?php $i++; endwhile;
            wp_reset_postdata();?>
          </div>
        </div>
        <?php else : ?>
        <div class="no-postfound"></div>
          <?php endif;
        endif;?>
          <a class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fa fa-chevron-left"></i></span>
          </a>
          <a class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>
          </a>
      </div>
      <div class="clearfix"></div>
    </section>
  <?php }?>

  <?php if( get_option('clothing_store_product_enable') == '1'){ ?>
    <section id="millions-of-hours" class="py-5">
      <div class="container">
        <div class="border-box p-3 p-lg-5 text-center text-md-start">
          <div class="row">
            <div class="col-lg-7 col-md-6 align-self-center">
              <?php if( get_theme_mod('clothing_store_millions_of_hours_heading') != '' ){ ?>
                <h3><?php echo esc_html(get_theme_mod('clothing_store_millions_of_hours_heading')); ?></h3>
              <?php }?>
              <?php if( get_theme_mod('clothing_store_millions_of_hours_sub_heading') != '' ){ ?>
                <h6 class=""><?php echo esc_html(get_theme_mod('clothing_store_millions_of_hours_sub_heading')); ?></h6>
              <?php }?>
            </div>
            <div class="col-lg-5 col-md-6 align-self-center">
            <?php if( get_theme_mod('clothing_store_millions_of_hours_countdown_timer') != '' ){ ?>
              <div id="countdown-timer" class="text-center">
                <input type="hidden" name="new-year-date" id="new-year-date" value="<?php echo esc_attr(get_theme_mod('clothing_store_millions_of_hours_countdown_timer','')); ?>">
                <div class="time-box me-2 me-lg-3"><strong id="days" class="bold-number">118 </strong> <p class="slim-countdown-text mb-0"><?php esc_html_e('Days','clothing-store'); ?></p><span class="timer"></span></div>
                <div class="time-box me-2 me-lg-3"><strong id="hours" class="bold-number"> 14 </strong> <p class="slim-countdown-text mb-0"><?php esc_html_e('Hours','clothing-store'); ?></p><span class="timer"></span></div>
                <div class="time-box me-2 me-lg-3"><strong id="mins" class="bold-number"> 36 </strong> <p class="slim-countdown-text mb-0"><?php esc_html_e('Minutes','clothing-store'); ?></p><span class="timer"></span></div>                
                <div class="time-box"><strong id="seconds" class="bold-number"> 24 </strong> <p class="slim-countdown-text mb-0"><?php esc_html_e('Seconds','clothing-store'); ?></p><span class="timer"></span></div>
              </div>
            <?php }?>
          </div>
          </div>
          <div class="row mt-5">
            <?php
            $clothing_store_catData = get_theme_mod('clothing_store_millions_of_hours_category');
            $clothing_store_count_catData = get_theme_mod('clothing_store_millions_of_hours_number');
            if ( class_exists( 'WooCommerce' ) ) {
            $clothing_store_args = array(
              'post_type' => 'product',
              'posts_per_page' => $clothing_store_count_catData,
              'product_cat' => $clothing_store_catData,
              'order' => 'ASC'
            );
            $loop = new WP_Query( $clothing_store_args );
            while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
              <div class="col-lg-3 col-md-4 col-sm-4 mb-3">
                <div class="product-img wrapper wow zoomIn">
                  <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, ''); else echo '<img src="'.esc_url(wc_placeholder_img_src()).'" />'; ?>
                  <div class="box-content">
                    <?php if( $product->is_type( 'simple' ) ) { woocommerce_template_loop_add_to_cart(  $loop->post, $product );} ?>
                  </div>
                  <div class="sale-tag">
                    <span><?php woocommerce_show_product_sale_flash( $post, $product ); ?></span>
                  </div>
                </div>
                <div class="product-details mt-2">
                  <h4><a href="<?php echo esc_url(get_permalink( $loop->post->ID )); ?>"><?php the_title(); ?></a></h4>
                  <div class="rate-box">
                    <span><?php esc_attr( apply_filters( 'woocommerce_product_price_class', '' ) ); ?><?php echo ( $product->get_price_html()); ?></span>
                  
                  <?php if( $product->is_type( 'simple' ) ){ woocommerce_template_loop_rating( $loop->post, $product ); } ?>
                  </div>
                </div>
              </div>
            <?php endwhile; wp_reset_query(); ?>
            <?php } ?>
          </div>
        </div>
      </div>
    </section>
  <?php } ?>
  <section id="custom-page-content" <?php if ( have_posts() && trim( get_the_content() ) !== '' ) echo 'class="pt-3"'; ?>>
    <div class="container">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
