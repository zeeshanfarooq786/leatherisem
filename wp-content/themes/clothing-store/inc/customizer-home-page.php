<?php
/**
 * Clothing Store: Customizer-home-page
 *
 * @subpackage Clothing Store
 * @since 1.0
 */
	//  Home Page Panel
	$wp_customize->add_panel( 'clothing_store_custompage_panel', array(
		'title' => esc_html__( 'Custom Page Settings', 'clothing-store' ),
		'priority' => 2,
	));
	// Top Header
    $wp_customize->add_section('clothing_store_top',array(
        'title' => __('header', 'clothing-store'),
        'priority' => 3,
        'panel' => 'clothing_store_custompage_panel',
    ) );
    $wp_customize->add_setting( 'clothing_store_section_contact_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_contact_heading', array(
		'label'       => esc_html__( 'Header Settings', 'clothing-store' ),			
		'section'     => 'clothing_store_top',
		'settings'    => 'clothing_store_section_contact_heading',
	) ) );
    $wp_customize->add_setting('clothing_store_top_phone',array(
		'default' => '',
		'sanitize_callback' => 'clothing_store_sanitize_phone_number'
	));
	$wp_customize->add_control('clothing_store_top_phone',array(
		'label' => esc_html__('Add Phone','clothing-store'),
		'section' => 'clothing_store_top',
		'setting' => 'clothing_store_top_phone',
		'type'    => 'text',
	));
	$wp_customize->selective_refresh->add_partial( 'clothing_store_top_phone', array(
		'selector' => '.header-text',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_top_phone',
	) );
	$wp_customize->add_setting('clothing_store_top_phone_icon',array(
		'default'	=> 'fas fa-phone-volume',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_top_phone_icon',array(
		'label'	=> __('Add phone Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_top',
		'setting'	=> 'clothing_store_top_phone_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_top_text',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('clothing_store_top_text',array(
		'label' => esc_html__('Add Text','clothing-store'),
		'section' => 'clothing_store_top',
		'setting' => 'clothing_store_top_text',
		'type'    => 'text',
	));
	$wp_customize->add_setting(
		'clothing_store_myaccount_enable',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_myaccount_enable',
			array(
				'settings'        => 'clothing_store_myaccount_enable',
				'section'         => 'clothing_store_top',
				'label'           => __( 'show login/register', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting(
		'clothing_store_product_search_enable',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_product_search_enable',
			array(
				'settings'        => 'clothing_store_product_search_enable',
				'section'         => 'clothing_store_top',
				'label'           => __( 'show product search', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting(
		'clothing_store_cart_enable',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_cart_enable',
			array(
				'settings'        => 'clothing_store_cart_enable',
				'section'         => 'clothing_store_top',
				'label'           => __( 'show cart', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'clothing_store_cart_enable', array(
		'selector' => '.header-cart',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_cart_enable',
	) );

    // Social Media
    $wp_customize->add_section('clothing_store_urls',array(
        'title' => __('Social Media', 'clothing-store'),
        'priority' => 3,
        'panel' => 'clothing_store_custompage_panel',
    ) );
    $wp_customize->add_setting( 'clothing_store_section_social_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_social_heading', array(
		'label'       => esc_html__( 'Social Media Settings', 'clothing-store' ),
		'description' => __( 'Add social media links in the below feilds', 'clothing-store' ),			
		'section'     => 'clothing_store_urls',
		'settings'    => 'clothing_store_section_social_heading',
	) ) );
	$wp_customize->add_setting(
		'header_social_icon_enable',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'header_social_icon_enable',
			array(
				'settings'        => 'header_social_icon_enable',
				'section'         => 'clothing_store_urls',
				'label'           => __( 'Check to show social fields', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'clothing_store_facebook_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_facebook_heading', array(
		'label'       => esc_html__( 'Facebook Settings', 'clothing-store' ),
		'section'     => 'clothing_store_urls',
		'settings'    => 'clothing_store_facebook_heading',
	) ) );
	$wp_customize->add_setting('clothing_store_facebook_icon',array(
		'default'	=> 'fab fa-facebook',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_facebook_icon',array(
		'label'	=> __('Add Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_urls',
		'setting'	=> 'clothing_store_facebook_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_facebook',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
	$wp_customize->add_control('clothing_store_facebook',array(
		'label' => esc_html__('Add URL','clothing-store'),
		'section' => 'clothing_store_urls',
		'setting' => 'clothing_store_facebook',
		'type'    => 'url'
	));
	$wp_customize->add_setting(
		'clothing_store_header_facebook_target',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_header_facebook_target',
			array(
				'settings'        => 'clothing_store_header_facebook_target',
				'section'         => 'clothing_store_urls',
				'label'           => __( 'Open link in a new tab', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'clothing_store_twitter_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_twitter_heading', array(
		'label'       => esc_html__( 'Twitter Settings', 'clothing-store' ),
		'section'     => 'clothing_store_urls',
		'settings'    => 'clothing_store_twitter_heading',
	) ) );
	$wp_customize->add_setting('clothing_store_twitter_icon',array(
		'default'	=> 'fab fa-x-twitter',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_twitter_icon',array(
		'label'	=> __('Add Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_urls',
		'setting'	=> 'clothing_store_twitter_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->selective_refresh->add_partial( 'clothing_store_twitter', array(
		'selector' => '.social-icon a i',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_twitter',
	) );
	$wp_customize->add_setting('clothing_store_twitter',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
	$wp_customize->add_control('clothing_store_twitter',array(
		'label' => esc_html__('Add URL','clothing-store'),
		'section' => 'clothing_store_urls',
		'setting' => 'clothing_store_twitter',
		'type'    => 'url'
	));
	$wp_customize->add_setting(
		'clothing_store_header_twt_target',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_header_twt_target',
			array(
				'settings'        => 'clothing_store_header_twt_target',
				'section'         => 'clothing_store_urls',
				'label'           => __( 'Open link in a new tab', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'clothing_store_linkedin_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_linkedin_heading', array(
		'label'       => esc_html__( 'Linkedin Settings', 'clothing-store' ),
		'section'     => 'clothing_store_urls',
		'settings'    => 'clothing_store_linkedin_heading',
	) ) );
	$wp_customize->add_setting('clothing_store_linkedin_icon',array(
		'default'	=> 'fab fa-linkedin',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_linkedin_icon',array(
		'label'	=> __('Add Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_urls',
		'setting'	=> 'clothing_store_linkedin_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_linkedin',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
	$wp_customize->add_control('clothing_store_linkedin',array(
		'label' => esc_html__('Add URL','clothing-store'),
		'section' => 'clothing_store_urls',
		'setting' => 'clothing_store_linkedin',
		'type'    => 'url'
	));
	$wp_customize->add_setting(
		'clothing_store_header_linkedin_target',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_header_linkedin_target',
			array(
				'settings'        => 'clothing_store_header_linkedin_target',
				'section'         => 'clothing_store_urls',
				'label'           => __( 'Open link in a new tab', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'clothing_store_pinterest_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_pinterest_heading', array(
		'label'       => esc_html__( 'Pinterest Settings', 'clothing-store' ),
		'section'     => 'clothing_store_urls',
		'settings'    => 'clothing_store_pinterest_heading',
	) ) );
	$wp_customize->add_setting('clothing_store_pinterest_icon',array(
		'default'	=> 'fab fa-pinterest-p',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_pinterest_icon',array(
		'label'	=> __('Add Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_urls',
		'setting'	=> 'clothing_store_pinterest_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_pinterest',array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
	$wp_customize->add_control('clothing_store_pinterest',array(
		'label' => esc_html__('Add URL','clothing-store'),
		'section' => 'clothing_store_urls',
		'setting' => 'clothing_store_pinterest',
		'type'    => 'url'
	));
	$wp_customize->add_setting(
		'clothing_store_header_pinterest_target',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_header_pinterest_target',
			array(
				'settings'        => 'clothing_store_header_pinterest_target',
				'section'         => 'clothing_store_urls',
				'label'           => __( 'Open link in a new tab', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);

    //Slider
	$wp_customize->add_section( 'clothing_store_slider_section' , array(
    	'title'      => __( 'Slider Settings', 'clothing-store' ),    	
		'priority'   => 3,
		'panel' => 'clothing_store_custompage_panel',
	) );
	$wp_customize->add_setting( 'clothing_store_section_slide_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_slide_heading', array(
		'label'       => esc_html__( 'Slider Settings', 'clothing-store' ),
		'description' => __( 'Slider Image Dimension ( 600px x 700px )', 'clothing-store' ),		
		'section'     => 'clothing_store_slider_section',
		'settings'    => 'clothing_store_section_slide_heading',
		'priority'   => 1,
	) ) );
	$wp_customize->add_setting(
		'clothing_store_slider_arrows',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_slider_arrows',
			array(
				'settings'        => 'clothing_store_slider_arrows',
				'section'         => 'clothing_store_slider_section',
				'label'           => __( 'Check To show Slider', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'priority'   => 1,
				'active_callback' => '',
			)
		)
	);
	
	$clothing_store_args = array('numberposts' => -1);
	$post_list = get_posts($clothing_store_args);
	$i = 0;
	$pst_sls[]= __('Select','clothing-store');
	foreach ($post_list as $key => $p_post) {
		$pst_sls[$p_post->ID]=$p_post->post_title;
	}
	for ( $i = 1; $i <= 4; $i++ ) {
		$wp_customize->add_setting('clothing_store_post_setting'.$i,array(
			'sanitize_callback' => 'clothing_store_sanitize_select',
		));
		$wp_customize->add_control('clothing_store_post_setting'.$i,array(
			'type'    => 'select',
			'choices' => $pst_sls,
			'label' => __('Select post','clothing-store'),
			'section' => 'clothing_store_slider_section',
			'priority'   => 1,
			'active_callback' => 'clothing_store_slider_dropdown'
		));
		$wp_customize->selective_refresh->add_partial( 'clothing_store_post_setting'.$i, array(
			'selector' => '.carousel-caption h2',
			'render_callback' => 'clothing_store_customize_partial_clothing_store_post_setting'.$i,
		) );
	}
	wp_reset_postdata();

	$wp_customize->add_setting(
		'clothing_store_slider_excerpt_show_hide',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_slider_excerpt_show_hide',
			array(
				'settings'        => 'clothing_store_slider_excerpt_show_hide',
				'section'         => 'clothing_store_slider_section',
				'label'           => __( 'Show Hide excerpt', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'priority'   => 1,
				'active_callback' => 'clothing_store_slider_dropdown',
			)
		)
	);
	$wp_customize->add_setting('clothing_store_slider_excerpt_count',array(
		'default'=> 20,
		'transport' => 'refresh',
		'sanitize_callback' => 'clothing_store_sanitize_integer'
	));
	$wp_customize->add_control(new Clothing_Store_Slider_Custom_Control( $wp_customize, 'clothing_store_slider_excerpt_count',array(
		'label' => esc_html__( 'Excerpt Limit','clothing-store' ),
		'section'=> 'clothing_store_slider_section',
		'settings'=>'clothing_store_slider_excerpt_count',
		'input_attrs' => array(
			'reset'			   => 20,
            'step'             => 1,
			'min'              => 0,
			'max'              => 50,
        ),
        'priority'   => 1,
        'active_callback' => 'clothing_store_slider_dropdown',
	)));
	$wp_customize->add_setting(
		'clothing_store_slider_button_show_hide',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_slider_button_show_hide',
			array(
				'settings'        => 'clothing_store_slider_button_show_hide',
				'section'         => 'clothing_store_slider_section',
				'label'           => __( 'Show Hide Button', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'priority'   => 1,
				'active_callback' => 'clothing_store_slider_dropdown',
			)
		)
	);
	$wp_customize->add_setting('clothing_store_slider_read_more',array(
		'default' => 'SHOP NOW',
		'sanitize_callback' => 'sanitize_text_field'
	)); 
	$wp_customize->add_control('clothing_store_slider_read_more',array(
		'label' => esc_html__('Button Text','clothing-store'),
		'section' => 'clothing_store_slider_section',
		'setting' => 'clothing_store_slider_read_more',
		'type'    => 'text',
		'active_callback' => 'clothing_store_slider_dropdown',
	));

	$wp_customize->add_setting('clothing_store_product_discount_text',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('clothing_store_product_discount_text',array(
		'label' => esc_html__('Discount Text','clothing-store'),
		'section' => 'clothing_store_slider_section',
		'setting' => 'clothing_store_product_discount_text',
		'type'    => 'text',
		'active_callback' => 'clothing_store_slider_dropdown'
	));
	$wp_customize->selective_refresh->add_partial( 'clothing_store_product_discount_text', array(
		'selector' => '.discount-box h3',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_product_discount_text',
	) );
	$wp_customize->add_setting('clothing_store_product_discount_number',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('clothing_store_product_discount_number',array(
		'label' => esc_html__('Discount Number','clothing-store'),
		'section' => 'clothing_store_slider_section',
		'setting' => 'clothing_store_product_discount_number',
		'type'    => 'text',
		'active_callback' => 'clothing_store_slider_dropdown'
	));
	$wp_customize->add_setting( 'clothing_store_slider_content_alignment',
		array(
			'default' => 'LEFT-ALIGN',
			'transport' => 'refresh',
			'sanitize_callback' => 'clothing_store_sanitize_choices'
		)
	);
	$wp_customize->add_control( new Clothing_Store_Text_Radio_Button_Custom_Control( $wp_customize, 'clothing_store_slider_content_alignment',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Slider Content Alignment', 'clothing-store' ),
			'section' => 'clothing_store_slider_section',
			'choices' => array(
				'LEFT-ALIGN' => __('LEFT','clothing-store'),
	            'CENTER-ALIGN' => __('CENTER','clothing-store'),
	            'RIGHT-ALIGN' => __('RIGHT','clothing-store'),
			),
			'active_callback' => 'clothing_store_slider_dropdown',
		)
	) );


	// Product
    $wp_customize->add_section('clothing_store_millions_of_hours_section',array(
		'title'	=> __('Product Settings','clothing-store'),
		'priority'	=> 4,
		'panel' => 'clothing_store_custompage_panel',
	));
    $wp_customize->add_setting( 'clothing_store_section_product_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_product_heading', array(
		'label'       => esc_html__( 'Product Settings', 'clothing-store' ),	
		'section'     => 'clothing_store_millions_of_hours_section',
		'settings'    => 'clothing_store_section_product_heading',
	) ) );
	$wp_customize->add_setting(
		'clothing_store_product_enable',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_product_enable',
			array(
				'settings'        => 'clothing_store_product_enable',
				'section'         => 'clothing_store_millions_of_hours_section',
				'label'           => __( 'Check To Show Product Settings', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting('clothing_store_millions_of_hours_heading',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control('clothing_store_millions_of_hours_heading',array(
		'type' => 'text',
		'label' => __('Heading Text','clothing-store'),
		'section' => 'clothing_store_millions_of_hours_section',
		'active_callback' => 'clothing_store_product_dropdown'
	));
	$wp_customize->selective_refresh->add_partial( 'clothing_store_millions_of_hours_heading', array(
		'selector' => '#millions-of-hours h3',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_millions_of_hours_heading',
	) );
	$wp_customize->add_setting('clothing_store_millions_of_hours_sub_heading',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control('clothing_store_millions_of_hours_sub_heading',array(
		'type' => 'text',
		'label' => __('Sub Heading Text','clothing-store'),
		'section' => 'clothing_store_millions_of_hours_section',
		'active_callback' => 'clothing_store_product_dropdown'
	));
    $wp_customize->add_setting('clothing_store_millions_of_hours_countdown_timer',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('clothing_store_millions_of_hours_countdown_timer',array(
		'label'	=> esc_html__('Add Countdown Timer','clothing-store'),
		'input_attrs' => array(
            	'placeholder' => __( 'Ex: 20 November 2024','clothing-store' ),
        	),
		'section'	=> 'clothing_store_millions_of_hours_section',
		'type'		=> 'text',
		'active_callback' => 'clothing_store_product_dropdown'
	));

	// Product settings
	$clothing_store_args = array(
	    'type'                     => 'product',
	    'child_of'                 => 0,
	    'parent'                   => '',
	    'orderby'                  => 'term_group',
	    'order'                    => 'ASC',
	    'hide_empty'               => false,
	    'hierarchical'             => 1,
	    'number'                   => '',
	    'taxonomy'                 => 'product_cat',
	    'pad_counts'               => false
	);
	$categories = get_categories($clothing_store_args);
	$cat_posts = array();
	$m = 0;
	$cat_posts[]='Select';
	foreach($categories as $category){
	if($m==0){
		$default = $category->slug;
			$m++;
		}
		$cat_posts[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('clothing_store_millions_of_hours_category',array(
		'default'	=> 'select',
		'sanitize_callback' => 'clothing_store_sanitize_select',
	));
	$wp_customize->add_control('clothing_store_millions_of_hours_category',array(
		'type'    => 'select',
		'choices' => $cat_posts,
		'label' => __('Select category to display products ','clothing-store'),
		'section' => 'clothing_store_millions_of_hours_section',
		'active_callback' => 'clothing_store_product_dropdown'
	));

	//Footer
    $wp_customize->add_section( 'clothing_store_footer_copyright', array(
    	'title'      => esc_html__( 'Footer Text', 'clothing-store' ),
    	'priority' => 6,
    	'panel' => 'clothing_store_custompage_panel',
	) );
	$wp_customize->add_setting( 'clothing_store_section_footer_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_footer_heading', array(
		'label'       => esc_html__( 'Footer Settings', 'clothing-store' ),		
		'section'     => 'clothing_store_footer_copyright',
		'settings'    => 'clothing_store_section_footer_heading',
		'priority' => 1,
	) ) );
    $wp_customize->add_setting('clothing_store_footer_text',array(
		'default'	=> 'Clothing Store WordPress Theme',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('clothing_store_footer_text',array(
		'label'	=> esc_html__('Copyright Text','clothing-store'),
		'section'	=> 'clothing_store_footer_copyright',
		'type'		=> 'textarea'
	));
	$wp_customize->selective_refresh->add_partial( 'clothing_store_footer_text', array(
		'selector' => '.site-info a',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_footer_text',
	) );
	$wp_customize->add_setting( 'clothing_store_footer_content_alignment',
		array(
			'default' => 'CENTER-ALIGN',
			'transport' => 'refresh',
			'sanitize_callback' => 'clothing_store_sanitize_choices'
		)
	);
	$wp_customize->add_control( new Clothing_Store_Text_Radio_Button_Custom_Control( $wp_customize, 'clothing_store_footer_content_alignment',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Footer Content Alignment', 'clothing-store' ),
			'section' => 'clothing_store_footer_copyright',
			'choices' => array(
				'LEFT-ALIGN' => __('LEFT','clothing-store'),
	            'CENTER-ALIGN' => __('CENTER','clothing-store'),
	            'RIGHT-ALIGN' => __('RIGHT','clothing-store'),
			),
			'active_callback' => '',
		)
	) );
	$wp_customize->add_setting( 'clothing_store_footer_widget',
		array(
			'default' => '4',
			'transport' => 'refresh',
			'sanitize_callback' => 'clothing_store_sanitize_choices'
		)
	);
	$wp_customize->add_control( new Clothing_Store_Text_Radio_Button_Custom_Control( $wp_customize, 'clothing_store_footer_widget',
		array(
			'type' => 'select',
			'label' => esc_html__('Footer Per Column','clothing-store'),
			'section' => 'clothing_store_footer_copyright',
			'choices' => array(
				'1' => __('1','clothing-store'),
	            '2' => __('2','clothing-store'),
	            '3' => __('3','clothing-store'),
	            '4' => __('4','clothing-store'),
			)
		)
	) );