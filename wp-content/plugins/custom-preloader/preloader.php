<?php

/*
Plugin Name: Custom Preloader
Plugin URI: https://wordpress.org/plugins/custom-preloader/
Description: Custom Preloader it’s a Plugin for making your Website More Cool! This plugin runs when your Website Loads and hiding the front page until your browser download the Page Perfectly.
Version: 2.0
Author: NikosTsolakos
Author URI: https://profiles.wordpress.org/nikostsolakos
License: GPLv2
*/


// Set plugin URL
define( 'AP_PATH', plugin_dir_url(__FILE__) );

// =====
// 1. Plugin Activation and Deactivation
// =====

// Activation
register_activation_hook( __FILE__, "custom_preloader_");

function custom_preloader_() {
    
	$default_settings = array(
        'enabled_settings' 			=> 'off',
		'bg_gradient_enabled' 		=> 'off',
		'is_home_' 					=> 'off',
		'is_front_page_'			=> 'off',
		'is_page_'					=> 'off',
		'is_category_'				=> 'off',
		'is_404_'					=> 'off',
		'is_search_'				=> 'off',
		'is_single_'				=> 'off',
		'is_tag_'					=> 'off',
        'bg_color_settings' 		=> '#eeeeee',
		'bg_gradient_code' 			=> 'background: -webkit-linear-gradient(45deg, hsla(340, 100%, 55%, 1) 0%, hsla(340, 100%, 55%, 0) 70%), -webkit-linear-gradient(315deg, hsla(225, 95%, 50%, 1) 10%, hsla(225, 95%, 50%, 0) 80%), -webkit-linear-gradient(225deg, hsla(140, 90%, 50%, 1) 10%, hsla(140, 90%, 50%, 0) 80%), -webkit-linear-gradient(135deg, hsla(35, 95%, 55%, 1) 100%, hsla(35, 95%, 55%, 0) 70%); background: linear-gradient(45deg, hsla(340, 100%, 55%, 1) 0%, hsla(340, 100%, 55%, 0) 70%), linear-gradient(135deg, hsla(225, 95%, 50%, 1) 10%, hsla(225, 95%, 50%, 0) 80%), linear-gradient(225deg, hsla(140, 90%, 50%, 1) 10%, hsla(140, 90%, 50%, 0) 80%), linear-gradient(315deg, hsla(35, 95%, 55%, 1) 100%, hsla(35, 95%, 55%, 0) 70%);',
        'image_settings' 			=> 'https://i.imgur.com/PfLlKuV.png',
        'image_width_settings' 		=> '150px',
        'image_height_settings' 	=> 'auto',
		'image_margin_top' 			=> '20%',
		'image_margin_left' 		=> '45%',
		'image_margin_right' 		=> 'auto',
		'image_margin_bottom' 		=> 'auto'
		);

	add_option("custom_preloader_", $default_settings);
}

$options = get_option('custom_preloader_');
if(isset($options['enabled_settings']) && isset($options['bg_gradient_enabled']))
{
	$toremove = $options['bg_gradient_enabled'];
	$selectid = get_option('custom_preloader_');
	$key = array_search ($toremove, $selectid);
	unset($selectid[$key]);
	update_option('custom_preloader_', $selectid);
}
if(isset($options['enabled_settings']) && isset($options['bg_gradient_enabled']))
{
	$toremove = $options['enabled_settings'];
	$selectid = get_option('custom_preloader_');
	$key = array_search ($toremove, $selectid);
	unset($selectid[$key]);
	update_option('custom_preloader_', $selectid);
}

// Deactivation
register_deactivation_hook(__FILE__, 'pr_deactivated');

function pr_deactivated() {
	delete_option('custom_preloader_');
}

$plugin_dir = plugin_dir_path( __FILE__ );

// =====
// 2. Admin Init
// =====

function custom_preloader__init(){
	register_setting('custom_preloader_', 'custom_preloader_', 'custom_preloader__validate');
	
	// Main Section
	add_settings_section('main_section', 'Settings', 'main_section_text', 'main_section_text');
   
   // Fields Of Main Section
	add_settings_field('bg_color_settings', 'Background Color:', 'bg_color_settings', __FILE__, 'main_section');
	add_settings_field('image_settings', 'Set Image:', 'image_settings', __FILE__, 'main_section');
	add_settings_field('bg_gradient_code', 'Set ColorFul Background:', 'bg_gradient_code', __FILE__, 'main_section');
	
	// Gradient Color
	add_settings_section('gradient_section', 'ColorFul Background', 'gradient_section_text', 'gradient_section_text');
	
	// Fields Of Gradient Color
	add_settings_field('bg_gradient_enabled', '', 'bg_gradient_enabled', __FILE__, 'gradient_section');
		
	add_settings_section('advanced_section', 'Positions', 'advanced_section_text', 'advanced_section_text');
	// Fields Of Advanced Section
	add_settings_field('image_width_settings', '', 'image_width_settings', __FILE__, 'advanced_section');
	add_settings_field('image_height_settings', '', 'image_height_settings', __FILE__, 'advanced_section');
	add_settings_field('image_margin_top', '', 'image_margin_top', __FILE__, 'advanced_section');
	add_settings_field('image_margin_left', '', 'image_margin_left', __FILE__, 'advanced_section');
	add_settings_field('image_margin_right', '', 'image_margin_right', __FILE__, 'advanced_section');
	add_settings_field('image_margin_bottom', '', 'image_margin_bottom', __FILE__, 'advanced_section');
	
	// Section of Run The Plugin
	add_settings_section('run_the_plugin_section', 'Visibility', 'run_the_plugin_section_text', 'run_the_plugin_section_text');
	// Fields of Run The Plugin
	add_settings_field('is_home_', 'Homepage', 'is_home_', __FILE__, 'run_the_plugin_section');
	add_settings_field('is_front_page_', 'Pront Page', 'is_front_page_', __FILE__, 'run_the_plugin_section');
	add_settings_field('is_page_', 'Page', 'is_page_', __FILE__, 'run_the_plugin_section');
	add_settings_field('is_category_', 'Category', 'is_category_', __FILE__, 'run_the_plugin_section');
	add_settings_field('is_404_', '404', 'is_404_', __FILE__, 'run_the_plugin_section');
	add_settings_field('is_search_', 'Search', 'is_search_', __FILE__, 'run_the_plugin_section');
	add_settings_field('is_single_', 'Post', 'is_single_', __FILE__, 'run_the_plugin_section');
	add_settings_field('is_tag_', 'Tag', 'is_tag_', __FILE__, 'run_the_plugin_section');
}
add_action('admin_init', 'custom_preloader__init' );

include('include/functions.php');

// Options' HTML output

add_action('admin_menu', 'ap_admin_actions');

function cp_admin_panel()
{
	if ( !current_user_can( 'manage_options' ) ) 
	{
		wp_die( __( 'You do not have permissions to access this page.' ) );
	}
	$options = get_option('custom_preloader_');
?>
	<?php
	if(isset($options['enabled_settings']) && isset($options['bg_gradient_enabled']))
	{
		echo '<div class="ERROR">ONLY ONE OPTION HAS TO BE ENABLED</div>';
		$cssvar = 'errorbor';
	} else {
		$cssvar = '';
	}
	$options = get_option('custom_preloader_');
	?>
	<style>
		<?php
			$style_path = $plugin_dir . 'css/style.css';
			include($style_path);
		?>
	</style>
	<div class="wrap" id="custom_preloader">
		<form action="options.php" method="post">
			<?php settings_fields('custom_preloader_'); ?>
			<?php my_enqueue_media_lib_uploader(); ?>
			<div id="poststuff">
					<div class="title_box">
						<img class="image_box" src="<?php echo plugins_url( 'images/custom-preloader-cover.png', __FILE__ ); ?>"/>						
					</div>
					<div class="postbox half floatleft <?php echo $cssvar;?>">
						<div class="center" style="padding: 0!important;">
							<h2><?php _e('Switch On/Off'); ?></h2>
						</div>
						<div class="manage">
							<div class="on-off">
								<h2><?php _e('Simple Background'); ?>: </h2>
									<div class="checkedbox">
										<?php enabled_settings();?>
										<label for="enabled_settings" id="gettooltip"></label>
									</div>
							</div>
							
							<div class="on-off">
								<h2><?php _e('ColorFul Background'); ?>: </h2>
									<div class="checkedbox">
										<?php bg_gradient_enabled();?>
										<label for="bg_gradient_enabled" id="gettooltip_s"></label>
									</div>
							</div>
						</div>
					</div>
						<?php
						
						$background = null;
						if(isset($options['bg_gradient_enabled']))
						{
							$background = $options['bg_gradient_code'];
						}
						elseif(isset($options['enabled_settings']))
						{
							$background = 'background: '.$options['bg_color_settings'].';';
						}
						if(empty($background))
						{
							$background = '#c3c3c3';
						}
						//Get Plugins Version
						$plugin_data = get_plugin_data( __FILE__ );
						$plugin_version = preg_replace("/\s+/","",$plugin_data['Version']);
						
						?>
					<!-- Visibility -->
					<div class="postbox half floatright" style="width: 49%;">
						<?php do_settings_sections('run_the_plugin_section_text'); ?>
					</div>
					
					<div class="postbox half floatleft">
						<?php do_settings_sections('main_section_text'); ?>
						<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
					</div>
					<!-- ColorFul Background -->
					<div class="postbox half floatright" id="bg_img" style="width: 49%;">
						<div class="cpbody">
							<?php do_settings_sections('gradient_section_text'); ?>
						</div>
					</div>
					<!-- Positions -->
					<div class="postbox half floatleft">
						<div class="cpbody">
							<?php do_settings_sections('advanced_section_text'); ?>
						</div>
					</div>
					
					<div class="postbox cp_footer">
						<div class="buttons_ext">
							
								<div class="submit">
									<a href="https://paypal.me/NikosTsolakos" target="_blank">
										<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif">
									</a>
								</div>
								<!-- Save Changes -->
								<div class="submit">
									<input id="submit-cp-options" name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
								</div>
								<!-- Preview -->
								<div class="submit">
									<section class="">
										<a href="#" id="preview" style="background: #F44336!important;border-color: #a92f27!important;" class="button-primary rt-plug"><?php _e('Preview'); ?></a>
									</section>
									
									<section class="pmodal" id="previewbg" style="<?php echo $background; ?>">
										<a href="#" class="pmodalx">&times;</a>
										<?php
											$imgt = $options['image_settings'];
										  ?>
										  <img id="previmg" onchange="previewImg()" src="<?php echo $imgt; ?>" style="<?php echo 'margin-top: '.$options['image_margin_top'].'; margin-right: '.$options['image_margin_right'].'; margin-bottom: '.$options['image_margin_bottom'].'; margin-left: '.$options['image_margin_left'].'; width: '.$options['image_width_settings'].'; height: '.$options['image_height_settings'].''; ?>" />
										<style>
											<?php $css_nav_path = plugin_dir_path( __FILE__ ).'css/nav.css'; include($css_nav_path); ?>
										</style>
										<span style="font-size:30px;color: #FFFFFF;cursor:pointer;float: left;position: absolute;top: 5px;left: 5px;" onclick="openNav()">&#9776; </span>
										<div id="mySidenav" class="sidenav">
										<h2 class="cp_h2nav">Custom Preloader</h2>
										  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
											<div class="sidenav-ctrl">
												<div class="sidenav-positions">
												<h2>Positions</h2>
													<div class="sidenav-ctrl-fields">
													  <label for="set_width">Width</label>
													  <input type="text" id="set_width" value="<?php echo $options['image_width_settings']; ?>"/>
													</div> 
													<div class="sidenav-ctrl-fields">
													  <label for="set_height">Height</label>
													  <input type="text" id="set_height" value="<?php echo $options['image_height_settings']; ?>"/>
													</div>
													<div class="sidenav-ctrl-fields">
													  <label for="set_margin-top">Margin Top</label>
													  <input type="text" id="set_margin-top" value="<?php echo $options['image_margin_top']; ?>"/>
													</div>
													<div class="sidenav-ctrl-fields">
													  <label for="set_margin-left">Margin Left</label>
													  <input type="text" id="set_margin-left" value="<?php echo $options['image_margin_left']; ?>"/>
													</div>
													<div class="sidenav-ctrl-fields">
													  <label for="set_margin-right">Margin Right</label>
													  <input type="text" id="set_margin-right" value="<?php echo $options['image_margin_right']; ?>"/>
													</div>  
													<div class="sidenav-ctrl-fields">
													 <label for="set_margin-bottom">Margin Bottom</label>
													 <input type="text" id="set_margin-bottom" value="<?php echo $options['image_margin_bottom']; ?>"/>
													</div>
												</div>
												<div class="sidenav-bgsmpl">
												<h2>Simple Background</h2>
													<div class="sidenav-ctrl-fields">
														<input type="text" class="jscolor widthfull floatleft" id="prvforbg" value="<?php echo $options['bg_color_settings']; ?>"/>
													</div>
												</div>
												<div class="sidenav-bgclrfl">
												<h2>ColorFul Background</h2>
													<div class="sidenav-ctrl-fields">
														<div style="z-index: 999999999;margin: 0;position: absolute;top: 47%;left: 30%;width: 100px;" class="tool-tip top"><?php _e('Paste your Colorful Code Here'); ?></div>
														<input type="text" class="widthfull floatleft" id="prvclrfl" value="<?php echo $options['bg_gradient_code']; ?>" />
													</div>
												</div>
												<div class="sidenav-img">
												<h2>Your Image</h2>
													<div class="sidenav-ctrl-fields">
														<div style="z-index: 999999999;margin: 0;position: absolute;top: 57%;left: 30%;width: 100px;" class="tool-tip top"><?php _e('Paste your Image URL Here'); ?></div>
														<input type="text" class="imgprv widthfull floatleft" id="imgprv" value="<?php echo $options['image_settings']; ?>"/>
													</div>
												</div>
												<div class="sidenav-footer">
												<h2>Preview Mode</h2>
												<img alt="Custom Preloader v<?php echo $plugin_version; ?>" src="<?php echo plugins_url( 'images/custom-preloader-logo.png', __FILE__ ); ?>"/>
												</div>
											</div>
										</div>
									</section>
									<script>
									/** Width **/
									$('#set_width').change(function() {
										  $('#image_width_settings').val($(this).val());
									});
									/** Height **/
									$('#set_height').change(function() {
										  $('#image_height_settings').val($(this).val());
									});
									/** Margin Top **/
									$('#set_margin-top').change(function() {
										  $('#image_margin_top').val($(this).val());
									});
									/** Margin Left **/
									$('#set_margin-left').change(function() {
										  $('#image_margin_left').val($(this).val());
									});
									/** Margin Right **/
									$('#set_margin-right').change(function() {
										  $('#image_margin_right').val($(this).val());
									});
									/** Margin Bottom **/
									$('#set_margin-bottom').change(function() {
										  $('#image_margin_bottom').val($(this).val());
									});
									/** ColorFul Background Preview to Settings **/
									$('#bg_gradient_code').change(function() {
										  $('#prvclrfl').val($(this).val());
									});
									/** ColorFul Background Preview to Settings **/
									$('#prvclrfl').change(function() {
										  $('#bg_gradient_code').val($(this).val());
									});
									/** Simple Background Preview to Settings **/
									$('#prvforbg').change(function() {
										  $('#smplbg').val($(this).val());
									});
									/** Simple Background Settings to Preview **/
									$('#smplbg').change(function() {
										  $('#prvforbg').val($(this).val());
									});
									/** Image Preview to Settings **/
									$('#imgprv').change('input', function() {
										  $('#image_settings').val($(this).val());
									});
									/** Image Settings to Preview **/
									$('#image_settings').change('input', function() {
										  $('#imgprv').val($(this).val());
									});
									/** Simple Background for Preview BG **/
									$('#prvforbg').on('change click', function() {
										$('.pmodal').css('background', $(this).val());
									});
									</script>
									<script type="text/javascript" src="<?php echo plugins_url( '/js/jscolor.js', __FILE__ ); ?>"></script>
									<script type="text/javascript" src="<?php echo plugins_url( '/js/mod-settings.js', __FILE__ ); ?>"></script>
									<script>
									
										function openNav() {
											document.getElementById("mySidenav").style.width = "250px";
										}

										function closeNav() {
											document.getElementById("mySidenav").style.width = "0";
										}
									</script>
									<script>
										$('#preview').click(function(e) {
										  $('.pmodal').addClass('active');
										  e.preventDefault();
										});

										$('.pmodalx').click(function(e) {
										  $('.pmodal').removeClass('active');
										  e.preventDefault();
										});
									</script>
								</div>
								
								<!-- Rate Plugin -->
								<div class="submit">
									<a href="https://wordpress.org/support/view/plugin-reviews/custom-preloader" target="_blank" class="button-secondary rt-plug"><?php _e('Rate Plugin'); ?></a>
								</div>
								<!-- Need Help -->
								<div class="submit">
									<a href="https://wordpress.org/support/plugin/custom-preloader" target="_blank" class="button-primary suppor"><?php _e('Found Bug?'); ?></a>
								</div>
								
						</div>
						<div class="cp_cum_foo">
							<div class="ctrl_foo">
								<img src="<?php echo plugins_url( 'images/custom-preloader-logo.png', __FILE__ ); ?>"/>
								<p>Custom Preloader <?php echo $plugin_version;?></p>
								
							</div>
						</div>
					</div>
					<script>
					jQuery(document).ready(function($){

					  var mediaUploader;

					  $('#upload-button').click(function(e) {
						e.preventDefault();
						// If the uploader object has already been created, reopen the dialog
						  if (mediaUploader) {
						  mediaUploader.open();
						  return;
						}
						// Extend the wp.media object
						mediaUploader = wp.media.frames.file_frame = wp.media({
						  title: 'Choose Image',
						  button: {
						  text: 'Choose Image'
						}, multiple: false });

						// When a file is selected, grab the URL and set it as the text field's value
						mediaUploader.on('select', function() {
						  attachment = mediaUploader.state().get('selection').first().toJSON();
						  $('#image_settings').val(attachment.url);
						});
						// Open the uploader dialog
						mediaUploader.open();
					  });

					});
					</script>
			</div>
		</form>
	</div>
	
<?php }

function ap_admin_actions() {
	add_options_page("Custom Preloader", "Custom Preloader", 'manage_options', "Custom_Preloader", "cp_admin_panel");
}

function settings_page_link($links) { 
  $settings_link = '<a href="options-general.php?page=Custom_Preloader">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'settings_page_link' );
// =====
// 4. Frontend
// =====
include('include/frontend.php');
?>