<?php
	function sn_custom_post_type()
	{
	    register_post_type('social_accounts',
           	array(
               	'labels'      => array(
                   	'name'          => __('Social Accounts'),
                   	'singular_name' => __('Social'),
               	),
               	'public'      => true,
               	'has_archive' => false,
           	)
	    );
	}
	add_action('init', 'sn_custom_post_type');

	add_action('init', 'init_remove_support',100);
	function init_remove_support(){
	    $post_type = 'social_accounts';
	    remove_post_type_support( $post_type, 'editor');
	}

	register_nav_menus( array(
		'main_menu' => 'Main Menu',
	) );

	function mytheme_post_thumbnails() {
	    add_theme_support( 'post-thumbnails' );
	}
	add_image_size( 'listing-thumb', 422, 248, false );
	add_action( 'after_setup_theme', 'mytheme_post_thumbnails' );

	function my_scripts() {
	    
	    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
	    wp_enqueue_style( 'style', get_template_directory_uri() . '/assets/css/style.css' );
	    wp_enqueue_style( 'custom', get_template_directory_uri() . '/assets/css/custom.css' );
	    wp_enqueue_style( 'blog', get_template_directory_uri() . '/assets/css/blog.css' );
	    wp_enqueue_style( 'responsive', get_template_directory_uri() . '/assets/css/responsive.css' );
	    
	    wp_enqueue_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js' );
	    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js' );
	    wp_enqueue_script( 'custom', get_template_directory_uri() . '/assets/js/custom.js' );
	    wp_enqueue_script( 'TweenMax', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.2/TweenMax.min.js' );
	}

	add_action( 'wp_enqueue_scripts', 'my_scripts' );

	add_filter ( 'nav_menu_css_class', 'menu_item_class', 10, 4 );

	function menu_item_class ( $classes, $item, $args, $depth ){
	  $classes[] = 'menu-underline';
	  return $classes;
	}
?>