<?php
/**
 * Clothing Store: Customizer
 *
 * @subpackage Clothing Store
 * @since 1.0
 */

function clothing_store_customize_register( $wp_customize ) {

	wp_enqueue_style('customizercustom_css', esc_url( get_template_directory_uri() ). '/assets/css/customizer.css');

	// fontawesome icon-picker

	load_template( trailingslashit( get_template_directory() ) . '/inc/icon-picker.php' );

	// Add custom control.

  	require get_parent_theme_file_path( 'inc/switch/control_switch.php' );

  	require get_parent_theme_file_path( 'inc/custom-control.php' );

  	//Register the sortable control type.
	$wp_customize->register_control_type( 'Clothing_Store_Control_Sortable' );

  	// Add homepage customizer file
  	require get_template_directory() . '/inc/customizer-home-page.php';

  	// pro secion
 	$wp_customize->add_section('clothing_store_pro', array(
        'title'    => __('UPGRADE CLOTHING STORE PREMIUM', 'clothing-store'),
        'priority' => 1,
    ));
    $wp_customize->add_setting('clothing_store_pro', array(
        'default'           => null,
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control(new Clothing_Store_Pro_Control($wp_customize, 'clothing_store_pro', array(
        'label'    => __('Clothing Store PREMIUM', 'clothing-store'),
        'section'  => 'clothing_store_pro',
        'settings' => 'clothing_store_pro',
        'priority' => 1,
    )));

    //Logo
	$wp_customize->add_setting('clothing_store_logo_max_height',array(
		'default'=> '100',
		'transport' => 'refresh',
		'sanitize_callback' => 'clothing_store_sanitize_integer'
	));
	$wp_customize->add_control(new Clothing_Store_Slider_Custom_Control( $wp_customize, 'clothing_store_logo_max_height',array(
		'label'	=> esc_html__('Logo Width','clothing-store'),
		'section'	=> 'title_tagline',
		'settings'=>'clothing_store_logo_max_height',
		'input_attrs' => array(
			'reset' 		   => 100,
            'step'             => 1,
			'min'              => 0,
			'max'              => 250,
        ),
	)));
	$wp_customize->add_setting('clothing_store_logo_title',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_logo_title',
			array(
				'settings'        => 'clothing_store_logo_title',
				'section'         => 'title_tagline',
				'label'           => __( 'Show Site Title', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting('clothing_store_logo_text',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => 'off',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_logo_text',
			array(
				'settings'        => 'clothing_store_logo_text',
				'section'         => 'title_tagline',
				'label'           => __( 'Show Site Tagline', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);

  	// typography
	$wp_customize->add_section( 'clothing_store_typography_settings', array(
		'title'       => __( 'Typography Settings', 'clothing-store' ),
		'priority'       => 3,
	) );
	$font_choices = array(
		'' => 'Select',
		'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',
		'Open Sans:400italic,700italic,400,700' => 'Open Sans',
		'Oswald:400,700' => 'Oswald',
		'Playfair Display:400,700,400italic' => 'Playfair Display',
		'Montserrat:400,700' => 'Montserrat',
		'Raleway:400,700' => 'Raleway',
		'Droid Sans:400,700' => 'Droid Sans',
		'Lato:400,700,400italic,700italic' => 'Lato',
		'Arvo:400,700,400italic,700italic' => 'Arvo',
		'Lora:400,700,400italic,700italic' => 'Lora',
		'Merriweather:400,300italic,300,400italic,700,700italic' => 'Merriweather',
		'Oxygen:400,300,700' => 'Oxygen',
		'PT Serif:400,700' => 'PT Serif',
		'PT Sans:400,700,400italic,700italic' => 'PT Sans',
		'PT Sans Narrow:400,700' => 'PT Sans Narrow',
		'Cabin:400,700,400italic' => 'Cabin',
		'Fjalla One:400' => 'Fjalla One',
		'Francois One:400' => 'Francois One',
		'Josefin Sans:400,300,600,700' => 'Josefin Sans',
		'Libre Baskerville:400,400italic,700' => 'Libre Baskerville',
		'Arimo:400,700,400italic,700italic' => 'Arimo',
		'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
		'Bitter:400,700,400italic' => 'Bitter',
		'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
		'Roboto:400,400italic,700,700italic' => 'Roboto',
		'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
		'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
		'Roboto Slab:400,700' => 'Roboto Slab',
		'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
		'Rokkitt:400' => 'Rokkitt',
	);
	$wp_customize->add_setting( 'clothing_store_section_typo_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_typo_heading', array(
		'label'       => esc_html__( 'Typography Settings', 'clothing-store' ),
		'section'     => 'clothing_store_typography_settings',
		'settings'    => 'clothing_store_section_typo_heading',
	) ) );
	$wp_customize->add_setting( 'clothing_store_headings_text', array(
		'sanitize_callback' => 'clothing_store_sanitize_fonts',
	));
	$wp_customize->add_control( 'clothing_store_headings_text', array(
		'type' => 'select',
		'description' => __('Select your suitable font for the headings.', 'clothing-store'),
		'section' => 'clothing_store_typography_settings',
		'choices' => $font_choices
	));
	$wp_customize->add_setting( 'clothing_store_body_text', array(
		'sanitize_callback' => 'clothing_store_sanitize_fonts'
	));
	$wp_customize->add_control( 'clothing_store_body_text', array(
		'type' => 'select',
		'description' => __( 'Select your suitable font for the body.', 'clothing-store' ),
		'section' => 'clothing_store_typography_settings',
		'choices' => $font_choices
	) );
    
    // Theme General Settings
    $wp_customize->add_section('clothing_store_theme_settings',array(
        'title' => __('Theme General Settings', 'clothing-store'),
        'priority' => 3,
    ) );
	$wp_customize->add_setting( 'clothing_store_sticky_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_sticky_heading', array(
		'label'       => esc_html__( 'Sticky Header Settings', 'clothing-store' ),
		'section'     => 'clothing_store_theme_settings',
		'settings'    => 'clothing_store_sticky_heading',
	) ) );
    $wp_customize->add_setting(
		'clothing_store_sticky_header',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => 'off',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_sticky_header',
			array(
				'settings'        => 'clothing_store_sticky_header',
				'section'         => 'clothing_store_theme_settings',
				'label'           => __( 'Show Sticky Header', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'clothing_store_loader_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_loader_heading', array(
		'label'       => esc_html__( 'Loader Settings', 'clothing-store' ),
		'section'     => 'clothing_store_theme_settings',
		'settings'    => 'clothing_store_loader_heading',
	) ) );
	$wp_customize->add_setting(
		'clothing_store_theme_loader',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => 'off',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(
		new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_theme_loader',
			array(
				'settings'        => 'clothing_store_theme_loader',
				'section'         => 'clothing_store_theme_settings',
				'label'           => __( 'Show Site Loader', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);

	$wp_customize->add_setting('clothing_store_loader_style',array(
        'default' => 'style_one',
        'sanitize_callback' => 'clothing_store_sanitize_choices'
	));
	$wp_customize->add_control('clothing_store_loader_style',array(
        'type' => 'select',
        'label' => __('Select Loader Design','clothing-store'),
        'section' => 'clothing_store_theme_settings',
        'choices' => array(
            'style_one' => __('Circle','clothing-store'),
            'style_two' => __('Bar','clothing-store'),
        ),
	) );
	
	$wp_customize->add_setting( 'clothing_store_theme_width_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_theme_width_heading', array(
		'label'       => esc_html__( 'Theme Width Settings', 'clothing-store' ),
		'section'     => 'clothing_store_theme_settings',
		'settings'    => 'clothing_store_theme_width_heading',
	) ) );
	$wp_customize->add_setting('clothing_store_width_options',array(
        'default' => 'full_width',
        'sanitize_callback' => 'clothing_store_sanitize_choices'
	));
	$wp_customize->add_control('clothing_store_width_options',array(
        'type' => 'select',
        'label' => __('Theme Width Option','clothing-store'),
        'section' => 'clothing_store_theme_settings',
        'choices' => array(
            'full_width' => __('Fullwidth','clothing-store'),
            'container' => __('Container','clothing-store'),
            'container_fluid' => __('Container Fluid','clothing-store'),
        ),
	) );
	$wp_customize->add_setting( 'clothing_store_menu_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_menu_heading', array(
		'label'       => esc_html__( 'Menu Settings', 'clothing-store' ),
		'section'     => 'clothing_store_theme_settings',
		'settings'    => 'clothing_store_menu_heading',
	) ) );
	$wp_customize->add_setting('clothing_store_menu_text_transform',array(
        'default' => 'CAPITALISE',
        'sanitize_callback' => 'clothing_store_sanitize_choices'
	));
	$wp_customize->add_control('clothing_store_menu_text_transform',array(
        'type' => 'select',
        'label' => __('Menus Text Transform','clothing-store'),
        'section' => 'clothing_store_theme_settings',
        'choices' => array(
            'CAPITALISE' => __('CAPITALISE','clothing-store'),
            'UPPERCASE' => __('UPPERCASE','clothing-store'),
            'LOWERCASE' => __('LOWERCASE','clothing-store'),
        ),
	) );
	$wp_customize->add_setting( 'clothing_store_section_scroll_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_scroll_heading', array(
		'label'       => esc_html__( 'Scroll Top Settings', 'clothing-store' ),
		'section'     => 'clothing_store_theme_settings',
		'settings'    => 'clothing_store_section_scroll_heading',
	) ) );
	$wp_customize->add_setting(
		'clothing_store_scroll_enable',
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
			'clothing_store_scroll_enable',
			array(
				'settings'        => 'clothing_store_scroll_enable',
				'section'         => 'clothing_store_theme_settings',
				'label'           => __( 'show Scroll Top', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'clothing_store_scroll_options',
		array(
			'default' => 'right_align',
			'transport' => 'refresh',
			'sanitize_callback' => 'clothing_store_sanitize_choices'
		)
	);
	$wp_customize->add_control( new Clothing_Store_Text_Radio_Button_Custom_Control( $wp_customize, 'clothing_store_scroll_options',
		array(
			'type' => 'select',
			'label' => esc_html__( 'Scroll Top Alignment', 'clothing-store' ),
			'section' => 'clothing_store_theme_settings',
			'choices' => array(
				'left_align' => __('LEFT','clothing-store'),
				'center_align' => __('CENTER','clothing-store'),
				'right_align' => __('RIGHT','clothing-store'),
			)
		)
	) );
	$wp_customize->add_setting('clothing_store_scroll_top_icon',array(
		'default'	=> 'fas fa-chevron-up',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_scroll_top_icon',array(
		'label'	=> __('Add Scroll Top Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_theme_settings',
		'setting'	=> 'clothing_store_scroll_top_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting( 'clothing_store_section_cursor_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_cursor_heading', array(
		'label'       => esc_html__( 'Cursor Setting', 'clothing-store' ),
		'section'     => 'clothing_store_theme_settings',
		'settings'    => 'clothing_store_section_cursor_heading',
	) ) );

	$wp_customize->add_setting(
		'clothing_store_enable_custom_cursor',
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
			'clothing_store_enable_custom_cursor',
			array(
				'settings'        => 'clothing_store_enable_custom_cursor',
				'section'         => 'clothing_store_theme_settings',
				'label'           => __( 'show custom cursor', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);

	$wp_customize->add_setting( 'clothing_store_section_animation_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_animation_heading', array(
		'label'       => esc_html__( 'Animation Setting', 'clothing-store' ),
		'section'     => 'clothing_store_theme_settings',
		'settings'    => 'clothing_store_section_animation_heading',
	) ) );

	$wp_customize->add_setting(
		'clothing_store_animation_enable',
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
			'clothing_store_animation_enable',
			array(
				'settings'        => 'clothing_store_animation_enable',
				'section'         => 'clothing_store_theme_settings',
				'label'           => __( 'show/Hide Animation', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);

	// Post Layouts
	$wp_customize->add_panel( 'clothing_store_post_panel', array(
		'title' => esc_html__( 'Post Layout', 'clothing-store' ),
		'priority' => 4,
	));
    $wp_customize->add_section('clothing_store_layout',array(
        'title' => __('Single-Post Layout', 'clothing-store'),
        'panel' => 'clothing_store_post_panel',
    ) );
    $wp_customize->add_setting( 'clothing_store_section_post_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_post_heading', array(
		'label'       => esc_html__( 'Single Post Structure', 'clothing-store' ),
		'section'     => 'clothing_store_layout',
		'settings'    => 'clothing_store_section_post_heading',
	) ) );
	$wp_customize->add_setting( 'clothing_store_single_post_option',
		array(
			'default' => 'single_right_sidebar',
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);
	$wp_customize->add_control( new Clothing_Store_Radio_Image_Control( $wp_customize, 'clothing_store_single_post_option',
		array(
			'type'=>'select',
			'label' => __( 'select Single Post Page Layout', 'clothing-store' ),
			'section' => 'clothing_store_layout',
			'choices' => array(

				'single_right_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/2column.jpg',
					'name' => __( 'Right Sidebar', 'clothing-store' )
				),
				'single_left_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/left.png',
					'name' => __( 'Left Sidebar', 'clothing-store' )
				),
				'single_full_width' => array(
					'image' => get_template_directory_uri().'/assets/images/1column.jpg',
					'name' => __( 'One Column', 'clothing-store' )
				),
			)
		)
	) );
	$wp_customize->add_setting('clothing_store_single_post_date',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_single_post_date',
			array(
				'settings'        => 'clothing_store_single_post_date',
				'section'         => 'clothing_store_layout',
				'label'           => __( 'Show Date', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'clothing_store_single_post_date', array(
		'selector' => '.date-box',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_single_post_date',
	) );
	$wp_customize->add_setting('clothing_store_single_date_icon',array(
		'default'	=> 'far fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_single_date_icon',array(
		'label'	=> __('date Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_layout',
		'setting'	=> 'clothing_store_single_date_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_single_post_admin',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_single_post_admin',
			array(
				'settings'        => 'clothing_store_single_post_admin',
				'section'         => 'clothing_store_layout',
				'label'           => __( 'Show Author/Admin', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'clothing_store_single_post_admin', array(
		'selector' => '.entry-author',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_single_post_admin',
	) );
	$wp_customize->add_setting('clothing_store_single_author_icon',array(
		'default'	=> 'fas fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_single_author_icon',array(
		'label'	=> __('Author Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_layout',
		'setting'	=> 'clothing_store_single_author_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_single_post_comment',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_single_post_comment',
			array(
				'settings'        => 'clothing_store_single_post_comment',
				'section'         => 'clothing_store_layout',
				'label'           => __( 'Show Comment', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting('clothing_store_single_comment_icon',array(
		'default'	=> 'fas fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_single_comment_icon',array(
		'label'	=> __('comment Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_layout',
		'setting'	=> 'clothing_store_single_comment_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_single_post_tag_count',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_single_post_tag_count',
			array(
				'settings'        => 'clothing_store_single_post_tag_count',
				'section'         => 'clothing_store_layout',
				'label'           => __( 'Show tag count', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting('clothing_store_single_tag_icon',array(
		'default'	=> 'fas fa-tags',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_single_tag_icon',array(
		'label'	=> __('tag Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_layout',
		'setting'	=> 'clothing_store_single_tag_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_single_post_tag',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_single_post_tag',
			array(
				'settings'        => 'clothing_store_single_post_tag',
				'section'         => 'clothing_store_layout',
				'label'           => __( 'Show Tags', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'clothing_store_single_post_tag', array(
		'selector' => '.single-tags',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_single_post_tag',
	) );
	$wp_customize->add_setting('clothing_store_similar_post',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_similar_post',
			array(
				'settings'        => 'clothing_store_similar_post',
				'section'         => 'clothing_store_layout',
				'label'           => __( 'Show Similar post', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting('clothing_store_similar_text',array(
		'default' => 'Explore More',
		'sanitize_callback' => 'sanitize_text_field'
	)); 
	$wp_customize->add_control('clothing_store_similar_text',array(
		'label' => esc_html__('Similar Post Heading','clothing-store'),
		'section' => 'clothing_store_layout',
		'setting' => 'clothing_store_similar_text',
		'type'    => 'text'
	));
	$wp_customize->add_section('clothing_store_archieve_post_layot',array(
        'title' => __('Archieve-Post Layout', 'clothing-store'),
        'panel' => 'clothing_store_post_panel',
    ) );
	$wp_customize->add_setting( 'clothing_store_section_archive_post_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_archive_post_heading', array(
		'label'       => esc_html__( 'Archieve Post Structure', 'clothing-store' ),
		'section'     => 'clothing_store_archieve_post_layot',
		'settings'    => 'clothing_store_section_archive_post_heading',
	) ) );
    $wp_customize->add_setting( 'clothing_store_post_option',
		array(
			'default' => 'right_sidebar',
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);
	$wp_customize->add_control( new Clothing_Store_Radio_Image_Control( $wp_customize, 'clothing_store_post_option',
		array(
			'type'=>'select',
			'label' => __( 'select Post Page Layout', 'clothing-store' ),
			'section' => 'clothing_store_archieve_post_layot',
			'choices' => array(
				'right_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/2column.jpg',
					'name' => __( 'Right Sidebar', 'clothing-store' )
				),
				'left_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/left.png',
					'name' => __( 'Left Sidebar', 'clothing-store' )
				),
				'one_column' => array(
					'image' => get_template_directory_uri().'/assets/images/1column.jpg',
					'name' => __( 'One Column', 'clothing-store' )
				),
				'three_column' => array(
					'image' => get_template_directory_uri().'/assets/images/3column.jpg',
					'name' => __( 'Three Column', 'clothing-store' )
				),
				'four_column' => array(
					'image' => get_template_directory_uri().'/assets/images/4column.jpg',
					'name' => __( 'Four Column', 'clothing-store' )
				),
				'grid_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/grid-sidebar.jpg',
					'name' => __( 'Grid-Right-Sidebar Layout', 'clothing-store' )
				),
				'grid_left_sidebar' => array(
					'image' => get_template_directory_uri().'/assets/images/grid-left.png',
					'name' => __( 'Grid-Left-Sidebar Layout', 'clothing-store' )
				),
				'grid_post' => array(
					'image' => get_template_directory_uri().'/assets/images/grid.jpg',
					'name' => __( 'Grid Layout', 'clothing-store' )
				)
			)
		)
	) );
	$wp_customize->add_setting( 'clothing_store_grid_column',
		array(
			'default' => '3_column',
			'transport' => 'refresh',
			'sanitize_callback' => 'clothing_store_sanitize_choices'
		)
	);
	$wp_customize->add_control( new Clothing_Store_Text_Radio_Button_Custom_Control( $wp_customize, 'clothing_store_grid_column',
		array(
			'type' => 'select',
			'label' => esc_html__('Grid Post Per Row','clothing-store'),
			'section' => 'clothing_store_archieve_post_layot',
			'choices' => array(
				'1_column' => __('1','clothing-store'),
	            '2_column' => __('2','clothing-store'),
	            '3_column' => __('3','clothing-store'),
	            '4_column' => __('4','clothing-store'),
			)
		)
	) );
	$wp_customize->add_setting('archieve_post_order', array(
        'default' => array('title', 'image', 'meta','excerpt','btn'),
        'sanitize_callback' => 'clothing_store_sanitize_sortable',
    ));
    $wp_customize->add_control(new Clothing_Store_Control_Sortable($wp_customize, 'archieve_post_order', array(
    	'label' => esc_html__('Post Order', 'clothing-store'),
        'description' => __('Drag & Drop post items to re-arrange the order and also hide and show items as per the need by clicking on the eye icon.', 'clothing-store') ,
        'section' => 'clothing_store_archieve_post_layot',
        'choices' => array(
            'title' => __('title', 'clothing-store') ,
            'image' => __('media', 'clothing-store') ,
            'meta' => __('meta', 'clothing-store') ,
            'excerpt' => __('excerpt', 'clothing-store') ,
            'btn' => __('Read more', 'clothing-store') ,
        ) ,
    )));
	$wp_customize->add_setting('clothing_store_post_excerpt',array(
		'default'=> 30,
		'transport' => 'refresh',
		'sanitize_callback' => 'clothing_store_sanitize_integer'
	));
	$wp_customize->add_control(new Clothing_Store_Slider_Custom_Control( $wp_customize, 'clothing_store_post_excerpt',array(
		'label' => esc_html__( 'Excerpt Limit','clothing-store' ),
		'section'=> 'clothing_store_archieve_post_layot',
		'settings'=>'clothing_store_post_excerpt',
		'input_attrs' => array(
			'reset'			   => 30,
            'step'             => 1,
			'min'              => 0,
			'max'              => 100,
        ),
	)));
	$wp_customize->add_setting('clothing_store_read_more_text',array(
		'default' => 'Read More',
		'sanitize_callback' => 'sanitize_text_field'
	)); 
	$wp_customize->add_control('clothing_store_read_more_text',array(
		'label' => esc_html__('Read More Text','clothing-store'),
		'section' => 'clothing_store_archieve_post_layot',
		'setting' => 'clothing_store_read_more_text',
		'type'    => 'text'
	));
	$wp_customize->add_setting('clothing_store_date',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_date',
			array(
				'settings'        => 'clothing_store_date',
				'section'         => 'clothing_store_archieve_post_layot',
				'label'           => __( 'Show Date', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'clothing_store_date', array(
		'selector' => '.date-box',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_date',
	) );
	$wp_customize->add_setting('clothing_store_date_icon',array(
		'default'	=> 'far fa-calendar-alt',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_date_icon',array(
		'label'	=> __('date Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_archieve_post_layot',
		'setting'	=> 'clothing_store_date_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_sticky_icon',array(
		'default'	=> 'fas fa-thumb-tack',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_sticky_icon',array(
		'label'	=> __('Sticky Post Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_archieve_post_layot',
		'setting'	=> 'clothing_store_sticky_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_admin',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_admin',
			array(
				'settings'        => 'clothing_store_admin',
				'section'         => 'clothing_store_archieve_post_layot',
				'label'           => __( 'Show Author/Admin', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'clothing_store_admin', array(
		'selector' => '.entry-author',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_admin',
	) );
	$wp_customize->add_setting('clothing_store_author_icon',array(
		'default'	=> 'fas fa-user',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_author_icon',array(
		'label'	=> __('Author Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_archieve_post_layot',
		'setting'	=> 'clothing_store_author_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_comment',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_comment',
			array(
				'settings'        => 'clothing_store_comment',
				'section'         => 'clothing_store_archieve_post_layot',
				'label'           => __( 'Show Comment', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'clothing_store_comment', array(
		'selector' => '.entry-comments',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_comment',
	) );
	$wp_customize->add_setting('clothing_store_comment_icon',array(
		'default'	=> 'fas fa-comments',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_comment_icon',array(
		'label'	=> __('comment Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_archieve_post_layot',
		'setting'	=> 'clothing_store_comment_icon',
		'type'		=> 'icon'
	)));
	$wp_customize->add_setting('clothing_store_tag',
		array(
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'theme_supports'       => '',
			'default'              => '1',
			'transport'            => 'refresh',
			'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
		)
	);
	$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch(
			$wp_customize,
			'clothing_store_tag',
			array(
				'settings'        => 'clothing_store_tag',
				'section'         => 'clothing_store_archieve_post_layot',
				'label'           => __( 'Show tag count', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->selective_refresh->add_partial( 'clothing_store_tag', array(
		'selector' => '.tags',
		'render_callback' => 'clothing_store_customize_partial_clothing_store_tag',
	) );
	$wp_customize->add_setting('clothing_store_tag_icon',array(
		'default'	=> 'fas fa-tags',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Clothing_Store_Fontawesome_Icon_Chooser(
        $wp_customize,'clothing_store_tag_icon',array(
		'label'	=> __('tag Icon','clothing-store'),
		'transport' => 'refresh',
		'section'	=> 'clothing_store_archieve_post_layot',
		'setting'	=> 'clothing_store_tag_icon',
		'type'		=> 'icon'
	)));

	// header-image
	$wp_customize->add_setting( 'clothing_store_section_header_image_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_header_image_heading', array(
		'label'       => esc_html__( 'Header Image Settings', 'clothing-store' ),
		'section'     => 'header_image',
		'settings'    => 'clothing_store_section_header_image_heading',
		'priority'    =>'1',
	) ) );

	$wp_customize->add_setting('clothing_store_show_header_image',array(
        'default' => 'on',
        'sanitize_callback' => 'clothing_store_sanitize_choices'
	));
	$wp_customize->add_control('clothing_store_show_header_image',array(
        'type' => 'select',
        'label' => __('Select Option','clothing-store'),
        'section' => 'header_image',
        'choices' => array(
            'on' => __('With Header Image','clothing-store'),
            'off' => __('Without Header Image','clothing-store'),
        ),
	) );

	// breadcrumb
	$wp_customize->add_section('clothing_store_breadcrumb_settings',array(
        'title' => __('Breadcrumb Settings', 'clothing-store'),
        'priority' => 4
    ) );
	$wp_customize->add_setting( 'clothing_store_section_breadcrumb_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_breadcrumb_heading', array(
		'label'       => esc_html__( 'Theme Breadcrumb Settings', 'clothing-store' ),
		'section'     => 'clothing_store_breadcrumb_settings',
		'settings'    => 'clothing_store_section_breadcrumb_heading',
	) ) );
	$wp_customize->add_setting(
		'clothing_store_enable_breadcrumb',
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
			'clothing_store_enable_breadcrumb',
			array(
				'settings'        => 'clothing_store_enable_breadcrumb',
				'section'         => 'clothing_store_breadcrumb_settings',
				'label'           => __( 'Show Breadcrumb', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting('clothing_store_breadcrumb_separator', array(
        'default' => ' / ',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('clothing_store_breadcrumb_separator', array(
        'label' => __('Breadcrumb Separator', 'clothing-store'),
        'section' => 'clothing_store_breadcrumb_settings',
        'type' => 'text',
    ));
	$wp_customize->add_setting( 'clothing_store_single_breadcrumb_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_single_breadcrumb_heading', array(
		'label'       => esc_html__( 'Single post & Page', 'clothing-store' ),
		'section'     => 'clothing_store_breadcrumb_settings',
		'settings'    => 'clothing_store_single_breadcrumb_heading',
	) ) );
	$wp_customize->add_setting(
		'clothing_store_single_enable_breadcrumb',
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
			'clothing_store_single_enable_breadcrumb',
			array(
				'settings'        => 'clothing_store_single_enable_breadcrumb',
				'section'         => 'clothing_store_breadcrumb_settings',
				'label'           => __( 'Show Breadcrumb', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	if ( class_exists( 'WooCommerce' ) ) { 
		$wp_customize->add_setting( 'clothing_store_woocommerce_breadcrumb_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_woocommerce_breadcrumb_heading', array(
			'label'       => esc_html__( 'Woocommerce Breadcrumb', 'clothing-store' ),
			'section'     => 'clothing_store_breadcrumb_settings',
			'settings'    => 'clothing_store_woocommerce_breadcrumb_heading',
		) ) );
		$wp_customize->add_setting(
			'clothing_store_woocommerce_enable_breadcrumb',
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
				'clothing_store_woocommerce_enable_breadcrumb',
				array(
					'settings'        => 'clothing_store_woocommerce_enable_breadcrumb',
					'section'         => 'clothing_store_breadcrumb_settings',
					'label'           => __( 'Show Breadcrumb', 'clothing-store' ),				
					'choices'		  => array(
						'1'      => __( 'On', 'clothing-store' ),
						'off'    => __( 'Off', 'clothing-store' ),
					),
					'active_callback' => '',
				)
			)
		);
		$wp_customize->add_setting('woocommerce_breadcrumb_separator', array(
	        'default' => ' / ',
	        'sanitize_callback' => 'sanitize_text_field',
	    ));
	    $wp_customize->add_control('woocommerce_breadcrumb_separator', array(
	        'label' => __('Breadcrumb Separator', 'clothing-store'),
	        'section' => 'clothing_store_breadcrumb_settings',
	        'type' => 'text',
	    ));
	}

	// woocommerce
	if ( class_exists( 'WooCommerce' ) ){
		$wp_customize->add_section('clothing_store_wocommerce_section',array(
			'title' =>__('WooCommerce Settings','clothing-store'),
			'priority' =>4,
		));
		$wp_customize->add_setting( 'clothing_store_section_shoppage_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_shoppage_heading', array(
			'label'       => esc_html__( 'Sidebar Settings', 'clothing-store' ),
			'section'     => 'clothing_store_wocommerce_section',
			'settings'    => 'clothing_store_section_shoppage_heading',
		) ) );
		$wp_customize->add_setting( 'clothing_store_shop_page_sidebar',
			array(
				'default' => 'right_sidebar',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_text_field'
			)
		);
		$wp_customize->add_control( new Clothing_Store_Radio_Image_Control( $wp_customize, 'clothing_store_shop_page_sidebar',
			array(
				'type'=>'select',
				'label' => __( 'Show Shop Page Sidebar', 'clothing-store' ),
				'section'     => 'clothing_store_wocommerce_section',
				'choices' => array(

					'right_sidebar' => array(
						'image' => get_template_directory_uri().'/assets/images/2column.jpg',
						'name' => __( 'Right Sidebar', 'clothing-store' )
					),
					'left_sidebar' => array(
						'image' => get_template_directory_uri().'/assets/images/left.png',
						'name' => __( 'Left Sidebar', 'clothing-store' )
					),
					'full_width' => array(
						'image' => get_template_directory_uri().'/assets/images/1column.jpg',
						'name' => __( 'Full Width', 'clothing-store' )
					),
				)
			)
		) );
		$wp_customize->add_setting( 'clothing_store_wocommerce_single_page_sidebar',
			array(
				'default' => 'right_sidebar',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_text_field'
			)
		);
		$wp_customize->add_control( new Clothing_Store_Radio_Image_Control( $wp_customize, 'clothing_store_wocommerce_single_page_sidebar',
			array(
				'type'=>'select',
				'label'           => __( 'Show Single Product Page Sidebar', 'clothing-store' ),
				'section'     => 'clothing_store_wocommerce_section',
				'choices' => array(

					'right_sidebar' => array(
						'image' => get_template_directory_uri().'/assets/images/2column.jpg',
						'name' => __( 'Right Sidebar', 'clothing-store' )
					),
					'left_sidebar' => array(
						'image' => get_template_directory_uri().'/assets/images/left.png',
						'name' => __( 'Left Sidebar', 'clothing-store' )
					),
					'full_width' => array(
						'image' => get_template_directory_uri().'/assets/images/1column.jpg',
						'name' => __( 'Full Width', 'clothing-store' )
					),
				)
			)
		) );
		$wp_customize->add_setting( 'clothing_store_section_archieve_product_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_archieve_product_heading', array(
			'label'       => esc_html__( 'Archieve Product Settings', 'clothing-store' ),
			'section'     => 'clothing_store_wocommerce_section',
			'settings'    => 'clothing_store_section_archieve_product_heading',
		) ) );
		$wp_customize->add_setting('clothing_store_archieve_item_columns',array(
	        'default' => '3',
	        'sanitize_callback' => 'clothing_store_sanitize_choices'
		));
		$wp_customize->add_control('clothing_store_archieve_item_columns',array(
	        'type' => 'select',
	        'label' => __('Select No of Columns','clothing-store'),
	        'section' => 'clothing_store_wocommerce_section',
	        'choices' => array(
	            '1' => __('One Column','clothing-store'),
	            '2' => __('Two Column','clothing-store'),
	            '3' => __('Three Column','clothing-store'),
	            '4' => __('four Column','clothing-store'),
	            '5' => __('Five Column','clothing-store'),
	            '6' => __('Six Column','clothing-store'),
	        ),
		) );
		$wp_customize->add_setting( 'clothing_store_archieve_shop_perpage', array(
			'default'              => 6,
			'type'                 => 'theme_mod',
			'transport' 		   => 'refresh',
			'sanitize_callback'    => 'clothing_store_sanitize_number_absint',
			'sanitize_js_callback' => 'absint',
		) );
		$wp_customize->add_control( 'clothing_store_archieve_shop_perpage', array(
			'label'       => esc_html__( 'Display Products','clothing-store' ),
			'section'     => 'clothing_store_wocommerce_section',
			'type'        => 'number',
			'input_attrs' => array(
				'step'             => 1,
				'min'              => 0,
				'max'              => 30,
			),
		) );
		$wp_customize->add_setting( 'clothing_store_section_related_heading', array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_related_heading', array(
			'label'       => esc_html__( 'Related Product Settings', 'clothing-store' ),
			'section'     => 'clothing_store_wocommerce_section',
			'settings'    => 'clothing_store_section_related_heading',
		) ) );
		$wp_customize->add_setting('woocommerce_related_products_heading', array(
	        'default' => 'Related products',
	        'sanitize_callback' => 'sanitize_text_field',
	    ));
	    $wp_customize->add_control('woocommerce_related_products_heading', array(
	        'label' => __('Related Products Heading', 'clothing-store'),
	        'section' => 'clothing_store_wocommerce_section',
	        'type' => 'text',
	    ));
		$wp_customize->add_setting('clothing_store_related_item_columns',array(
	        'default' => '3',
	        'sanitize_callback' => 'clothing_store_sanitize_choices'
		));
		$wp_customize->add_control('clothing_store_related_item_columns',array(
	        'type' => 'select',
	        'label' => __('Select No of Columns','clothing-store'),
	        'section' => 'clothing_store_wocommerce_section',
	        'choices' => array(
	            '1' => __('One Column','clothing-store'),
	            '2' => __('Two Column','clothing-store'),
	            '3' => __('Three Column','clothing-store'),
	            '4' => __('four Column','clothing-store'),
	            '5' => __('Five Column','clothing-store'),
	            '6' => __('Six Column','clothing-store'),
	        ),
		) );
		$wp_customize->add_setting( 'clothing_store_related_shop_perpage', array(
			'default'              => 3,
			'type'                 => 'theme_mod',
			'transport' 		   => 'refresh',
			'sanitize_callback'    => 'clothing_store_sanitize_number_absint',
			'sanitize_js_callback' => 'absint',
		) );
		$wp_customize->add_control( 'clothing_store_related_shop_perpage', array(
			'label'       => esc_html__( 'Display Products','clothing-store' ),
			'section'     => 'clothing_store_wocommerce_section',
			'type'        => 'number',
			'input_attrs' => array(
				'step'             => 1,
				'min'              => 0,
				'max'              => 10,
			),
		) );
		$wp_customize->add_setting(
			'clothing_store_related_product',
			array(
				'type'                 => 'option',
				'capability'           => 'edit_theme_options',
				'theme_supports'       => '',
				'default'              => '1',
				'transport'            => 'refresh',
				'sanitize_callback'    => 'clothing_store_callback_sanitize_switch',
			)
		);
		$wp_customize->add_control(new Clothing_Store_Customizer_Customcontrol_Switch($wp_customize,'clothing_store_related_product',
			array(
				'settings'        => 'clothing_store_related_product',
				'section'         => 'clothing_store_wocommerce_section',
				'label'           => __( 'show Related Products', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		));
	}
	
	// mobile width
	$wp_customize->add_section('clothing_store_mobile_options',array(
        'title' => __('Mobile Media settings', 'clothing-store'),
        'priority' => 4,
    ) );
    $wp_customize->add_setting( 'clothing_store_section_mobile_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_mobile_heading', array(
		'label'       => esc_html__( 'Mobile Media settings', 'clothing-store' ),
		'section'     => 'clothing_store_mobile_options',
		'settings'    => 'clothing_store_section_mobile_heading',
	) ) );
	$wp_customize->add_setting(
		'clothing_store_slider_button_mobile_show_hide',
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
			'clothing_store_slider_button_mobile_show_hide',
			array(
				'settings'        => 'clothing_store_slider_button_mobile_show_hide',
				'section'         => 'clothing_store_mobile_options',
				'label'           => __( 'Show Slider Button', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting(
		'clothing_store_scroll_enable_mobile',
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
			'clothing_store_scroll_enable_mobile',
			array(
				'settings'        => 'clothing_store_scroll_enable_mobile',
				'section'         => 'clothing_store_mobile_options',
				'label'           => __( 'Show Scroll Top', 'clothing-store' ),	
				'description' => __('Control wont function if scroll-top is off in the main settings.', 'clothing-store') ,			
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting( 'clothing_store_section_mobile_breadcrumb_heading', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Clothing_Store_Customizer_Customcontrol_Section_Heading( $wp_customize, 'clothing_store_section_mobile_breadcrumb_heading', array(
		'label'       => esc_html__( 'Mobile Breadcrumb settings', 'clothing-store' ),
		'description' => __('Controls wont function if the breadcrumb is off in the main breadcrumb settings.', 'clothing-store') ,
		'section'     => 'clothing_store_mobile_options',
		'settings'    => 'clothing_store_section_mobile_breadcrumb_heading',
	) ) );
	$wp_customize->add_setting(
		'clothing_store_enable_breadcrumb_mobile',
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
			'clothing_store_enable_breadcrumb_mobile',
			array(
				'settings'        => 'clothing_store_enable_breadcrumb_mobile',
				'section'         => 'clothing_store_mobile_options',
				'label'           => __( 'Theme Breadcrumb', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	$wp_customize->add_setting(
		'clothing_store_single_enable_breadcrumb_mobile',
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
			'clothing_store_single_enable_breadcrumb_mobile',
			array(
				'settings'        => 'clothing_store_single_enable_breadcrumb_mobile',
				'section'         => 'clothing_store_mobile_options',
				'label'           => __( 'Single Post & Page', 'clothing-store' ),				
				'choices'		  => array(
					'1'      => __( 'On', 'clothing-store' ),
					'off'    => __( 'Off', 'clothing-store' ),
				),
				'active_callback' => '',
			)
		)
	);
	if ( class_exists( 'WooCommerce' ) ) {
		$wp_customize->add_setting(
			'clothing_store_woocommerce_enable_breadcrumb_mobile',
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
				'clothing_store_woocommerce_enable_breadcrumb_mobile',
				array(
					'settings'        => 'clothing_store_woocommerce_enable_breadcrumb_mobile',
					'section'         => 'clothing_store_mobile_options',
					'label'           => __( 'wooCommerce Breadcrumb', 'clothing-store' ),				
					'choices'		  => array(
						'1'      => __( 'On', 'clothing-store' ),
						'off'    => __( 'Off', 'clothing-store' ),
					),
					'active_callback' => '',
				)
			)
		);
	}

	$wp_customize->get_setting( 'blogname' )->transport          = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport   = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport  = 'postMessage';

	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.site-title a',
		'render_callback' => 'clothing_store_customize_partial_blogname',
	) );
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => '.site-description',
		'render_callback' => 'clothing_store_customize_partial_blogdescription',
	) );

	//front page
	$num_sections = apply_filters( 'clothing_store_front_page_sections', 4 );

	// Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
		$wp_customize->add_setting( 'panel_' . $i, array(
			'default'           => false,
			'sanitize_callback' => 'clothing_store_sanitize_dropdown_pages',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'panel_' . $i, array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Content', 'clothing-store' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Select pages to feature in each area from the dropdowns. Add an image to a section by setting a featured image in the page editor. Empty sections will not be displayed.', 'clothing-store' ) ),
			'section'        => 'theme_options',
			'type'           => 'dropdown-pages',
			'allow_addition' => true,
			'active_callback' => 'clothing_store_is_static_front_page',
		) );

		$wp_customize->selective_refresh->add_partial( 'panel_' . $i, array(
			'selector'            => '#panel' . $i,
			'render_callback'     => 'clothing_store_front_page_section',
			'container_inclusive' => true,
		) );
	}
}
add_action( 'customize_register', 'clothing_store_customize_register' );

function clothing_store_customize_partial_blogname() {
	bloginfo( 'name' );
}
function clothing_store_customize_partial_blogdescription() {
	bloginfo( 'description' );
}
function clothing_store_is_static_front_page() {
	return ( is_front_page() && ! is_home() );
}
function clothing_store_is_view_with_layout_option() {
	return ( is_page() || ( is_archive() && ! is_active_sidebar( 'sidebar-1' ) ) );
}

define('CLOTHING_STORE_PRO_LINK',__('https://www.ovationthemes.com/products/clothing-store-wordpress-theme','clothing-store'));

/* Pro control */
if (class_exists('WP_Customize_Control') && !class_exists('Clothing_Store_Pro_Control')):
    class Clothing_Store_Pro_Control extends WP_Customize_Control{

    public function render_content(){?>
        <label style="overflow: hidden; zoom: 1;">
	        <div class="col-md upsell-btn">
                <a href="<?php echo esc_url( CLOTHING_STORE_PRO_LINK ); ?>" target="blank" class="btn btn-success btn"><?php esc_html_e('UPGRADE CLOTHING STORE PREMIUM','clothing-store');?> </a>
	        </div>
            <div class="col-md">
                <img class="clothing_store_img_responsive " src="<?php echo esc_url(get_template_directory_uri()); ?>/screenshot.png">
            </div>
	        <div class="col-md">
	            <h3 style="margin-top:10px; margin-left: 20px; text-decoration:underline; color:#333;"><?php esc_html_e('Clothing Store PREMIUM - Features', 'clothing-store'); ?></h3>
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
		    <div class="col-md upsell-btn upsell-btn-bottom">
	            <a href="<?php echo esc_url( CLOTHING_STORE_PRO_LINK ); ?>" target="blank" class="btn btn-success btn"><?php esc_html_e('UPGRADE CLOTHING STORE PREMIUM','clothing-store');?> </a>
		    </div>
        </label>
    <?php } }
endif;
