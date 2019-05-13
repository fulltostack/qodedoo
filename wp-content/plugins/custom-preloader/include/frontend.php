<?php
function enqueue_AP()
{
	$options = get_option('custom_preloader_');
	wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_AP');

function visibility_proccess($techo)
{
	$options = get_option('custom_preloader_');
	if(isset($options['is_home_']) && !isset($options['is_front_page_']))
	{
		if( is_home() )
		{
			echo $techo;
		}
	}
	if(isset($options['is_front_page_']))
	{
		if( is_front_page() )
		{
			echo $techo;
		}
	}
	if(isset($options['is_page_']))
	{
		if( is_page() )
		{
			echo $techo;
		}
	}
	if(isset($options['is_category_']))
	{
		if( is_category() )
		{
			echo $techo;
		}
	}
	if(isset($options['is_404_']))
	{
		if( is_404() )
		{
			echo $techo;
		}
	}
	if(isset($options['is_search_']))
	{
		if( is_search() )
		{
			echo $techo;
		}
	}
	if(isset($options['is_single_']))
	{
		if( is_single() )
		{
			echo $techo;
		}
	}
	if(isset($options['is_tag_']))
	{
		if( is_tag() )
		{
			echo $techo;
		}
	}
}

	// add in <head>
	function hook_preloader_css() {
    
       echo '<style>
            .preloader {
			position: fixed;
			 top: 0;
			 left: 0;
			 right: 0;
			 bottom: 0;
			 z-index: 9999;
			height: 100%;
		}
		.preloader img{
			position: fixed;
			 background-repeat: no-repeat;
			 background-position: center;
		}
        </style>';
    
}
add_action('wp_head', 'hook_preloader_css');
	function footer_cpreloader()
	{
		$options = get_option('custom_preloader_');
		if(isset($options['enabled_settings']))
		{
			$techo = '<script type="text/javascript">
						jQuery(window).load(function() {
							// will first fade out the loading animation
						jQuery("#preloader").fadeOut();
							// will fade out the whole DIV that covers the website.
					jQuery("#preloader").delay(1000).fadeOut("slow");
					jQuery("#preloader_style").remove();
					})
					</script>';
					visibility_proccess($techo);
		}
			
		if(isset($options['bg_gradient_enabled']))
		{
			$techo = '<script type="text/javascript">
					jQuery(window).load(function() {
						// will first fade out the loading animation
					jQuery("#preloader").fadeOut();
						// will fade out the whole DIV that covers the website.
					jQuery("#preloader").delay(1000).fadeOut("slow");
					jQuery("#preloader_style").remove();
					})
				</script>';
				visibility_proccess($techo);
		}
		
	}
	add_action('wp_footer', 'footer_cpreloader');
	// add in Footer
	function head_cpreloader()
	{
		
		$options = get_option('custom_preloader_');
		if(isset($options['enabled_settings']))
		{
			$imgt = '<img src="'.$options['image_settings'].'" style="margin-top: '.$options['image_margin_top'].'; margin-right: '.$options['image_margin_right'].'; margin-bottom: '.$options['image_margin_bottom'].'; margin-left: '.$options['image_margin_left'].'; width: '.$options['image_width_settings'].'; height: '.$options['image_height_settings'].'; " />';
			$techo = '<div id="preloader" class="preloader" style="background-color: '.$options['bg_color_settings'].';">'.$imgt.'</div>';
				visibility_proccess($techo);
		}
		
		if(isset($options['bg_gradient_enabled']))
		{
			$imgt = '<img src="'.$options['image_settings'].'" style="margin-top: '.$options['image_margin_top'].'; margin-right: '.$options['image_margin_right'].'; margin-bottom: '.$options['image_margin_bottom'].'; margin-left: '.$options['image_margin_left'].'; width: '.$options['image_width_settings'].'; height: '.$options['image_height_settings'].'; " />';
			$techo = '<div id="preloader" class="preloader" style="'.$options['bg_gradient_code'].';">'.$imgt.'</div>';
				visibility_proccess($techo);
		}
		
	}
	add_action('wp_head', 'head_cpreloader');