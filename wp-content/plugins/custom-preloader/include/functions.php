<?php
// =====
// 3. Admin Panel (Settings > Custom Preloader)
// =====

// Options' functions
function enabled_settings()
{
	$bvalue = null;
	$options = get_option('custom_preloader_');
	if(isset($options['enabled_settings']) && !isset($options['bg_gradient_enabled']))
	{
		$bvalue = 'checked';
	}
	elseif(!isset($options['enabled_settings']) && isset($options['bg_gradient_enabled']))
	{
		$bvalue = 'disabled';
		echo '<div class="tool-tip slideIn top" style="left: 67.2%;top: 7px;">'; _e('Set <font color="red">OFF</font> Colorful Background'); echo '</div>';
	}
	
	echo '<input type="checkbox" class="ch_location" id="enabled_settings" name="custom_preloader_[enabled_settings]" '.$bvalue.' />';
}

function bg_gradient_enabled()
{
	$bvalue = null;
	$options = get_option('custom_preloader_');
	if(isset($options['bg_gradient_enabled']) && !isset($options['enabled_settings']))
	{
		$bvalue = 'checked';
	}
	elseif(!isset($options['bg_gradient_enabled']) && isset($options['enabled_settings']))
	{
		$bvalue = 'disabled';
		echo '<div class="tool-tip slideIn top" style="left: 67.2%;top: 43px;">'; _e('Set <font color="red">OFF</font> Simple Background'); echo '</div>';
	}
	
	echo '<input type="checkbox" class="ch_location" id="bg_gradient_enabled" name="custom_preloader_[bg_gradient_enabled]" '.$bvalue.' />';
}

function run_the_plugin_section_text()
{
	$options = get_option('custom_preloader_');
	if(isset($options['is_home_']))
	{
		$is_home_status = 'checked';
	}else {
		$is_home_status = '';
	}
	if(isset($options['is_front_page_']))
	{
		$is_front_page_status = 'checked';
	}else {
		$is_front_page_status = '';
	}
	if(isset($options['is_page_']))
	{
		$is_page_status = 'checked';
	}else {
		$is_page_status = '';
	}
	if(isset($options['is_category_']))
	{
		$is_category_status = 'checked';
	}else {
		$is_category_status = '';
	}
	if(isset($options['is_404_']))
	{
		$is_404_status = 'checked';
	}else {
		$is_404_status = '';
	}
	if(isset($options['is_search_']))
	{
		$is_search_status = 'checked';
	}else {
		$is_search_status = '';
	}
	if(isset($options['is_single_']))
	{
		$is_single_status = 'checked';
	}else {
		$is_single_status = '';
	}
	if(isset($options['is_tag_']))
	{
		$is_tag_status = 'checked';
	}else {
		$is_tag_status = '';
	}
	echo '<div class="cpbody">
	       <table class="form-table rtp">
			<tbody>';
?>
			<!-- is_home_ -->
				<tr>
					<th scope="row"><?php _e('Home'); ?></th>
						<td>
							<input type="checkbox" class="rtp_input" id="" id="is_home_" name="custom_preloader_[is_home_]" <?php echo $is_home_status; ?> value="" />
						</td>
					</tr>

				<!-- is_front_page_	-->
				<tr>
					<th scope="row"><?php _e('Front Page'); ?></th>
						<td>
							<input type="checkbox" class="rtp_input" id="is_front_page_" name="custom_preloader_[is_front_page_]" <?php echo $is_front_page_status; ?> value="" />
						</td>
					</tr>
				
				<!-- is_page_ -->
				<tr>
					<th scope="row"><?php _e('Page'); ?></th>
						<td>
							<input type="checkbox" class="rtp_input" id="is_page_" name="custom_preloader_[is_page_]" <?php echo $is_page_status; ?> value="" />
						</td>
				</tr>
				<!-- is_single_ -->
				<tr>
					<th scope="row"><?php _e('Post'); ?></th>
						<td>
							<input type="checkbox" class="rtp_input" id="is_single_" name="custom_preloader_[is_single_]" <?php echo $is_single_status; ?> value="" />
						</td>
				</tr>				
				<!-- is_category_ -->
				<tr>
					<th scope="row"><?php _e('Category'); ?></th>
						<td>
							<input type="checkbox" class="rtp_input" id="is_category_" name="custom_preloader_[is_category_]" <?php echo $is_category_status; ?> value="" />
						</td>
					</tr>
				<!-- is_tag_ -->
				<tr>
					<th scope="row"><?php _e('Tag'); ?></th>
						<td>
							<input type="checkbox" class="rtp_input" id="is_tag_" name="custom_preloader_[is_tag_]" <?php echo $is_tag_status; ?> value="" />
						</td>
				</tr>
				<!-- is_search_ -->
				<tr>
					<th scope="row"><?php _e('Search'); ?></th>
						<td>
							<input type="checkbox" class="rtp_input" id="is_search_" name="custom_preloader_[is_search_]" <?php echo $is_search_status; ?> value="" />
						</td>
				</tr>
				<!-- is_404_ -->
				<tr>
					<th scope="row"><?php _e('404'); ?></th>
						<td>
							<input type="checkbox" class="rtp_input" id="is_404_" name="custom_preloader_[is_404_]" <?php echo $is_404_status; ?> value="" />
						</td>
				</tr>
				</table>
				</tbody>
		  </div>
<?php
}

function gradient_section_text(){
//	echo '<div id="colorful_bg" style="display:none">';
		$gradient_path = plugin_dir_path( __FILE__ ).'gradient.php';
		include($gradient_path);
//	echo '</div>';
}

function width_settings(){
	$options = get_option('custom_preloader_');
	echo $options['image_width_settings'];
}

function height_settings()
{
	$options = get_option('custom_preloader_');
	echo $options['image_height_settings'];
}

function margin_top()
{
	$options = get_option('custom_preloader_');
	echo $options['image_margin_top'];
}

function margin_left()
{
	$options = get_option('custom_preloader_');
	echo $options['image_margin_left'];
}

function margin_right()
{
	$options = get_option('custom_preloader_');
	echo $options['image_margin_right'];
}

function margin_bottom()
{
	$options = get_option('custom_preloader_');
	echo $options['image_margin_bottom'];
}

// Position Options
function advanced_section_text()
{
	$options = get_option('custom_preloader_');
?>
	
<div class="form-group">	
	<!-- image_width_settings -->
	<div class="settings_css">
		<span class="ginput input--grad">
			<input type="text" class="input__field width_half" id="image_width_settings" name="custom_preloader_[image_width_settings]" onchange="document.getElementById('set_width').value = document.getElementById('image_width_settings').value" value="<?php width_settings(); ?>" />
			<label class="input__label in_label_" for="image_width_settings">
				<span class="input__label-content input__label-content--grad"><?php _e('Width'); ?>:</span>
			</label>
		</span>
	</div>

	<!-- image_height_settings -->
	<div class="settings_css">
		<span class="ginput input--grad">
			<input type="text" class="input__field width_half" id="image_height_settings" name="custom_preloader_[image_height_settings]" onchange="document.getElementById('set_height').value = document.getElementById('image_height_settings').value" value="<?php height_settings(); ?>" />
			<label class="input__label in_label_" for="image_height_settings">
				<span class="input__label-content input__label-content--grad"><?php _e('Height'); ?>:</span>
			</label>
		</span>
	</div>
	
	<!-- image_margin_top -->
	<div class="settings_css">
		<span class="ginput input--grad">
			<input type="text" class="input__field width_half" id="image_margin_top" name="custom_preloader_[image_margin_top]" onchange="document.getElementById('set_margin-top').value = document.getElementById('image_margin_top').value" value="<?php margin_top(); ?>" />
			<label class="input__label in_label_" for="image_margin_top">
				<span class="input__label-content input__label-content--grad"><?php _e('Margin Top'); ?>:</span>
			</label>
		</span>
	</div>

	<!-- image_margin_left -->
	<div class="settings_css">
		<span class="ginput input--grad">
			<input type="text" class="input__field width_half" id="image_margin_left" name="custom_preloader_[image_margin_left]" onchange="document.getElementById('set_margin-left').value = document.getElementById('image_margin_left').value" value="<?php margin_left(); ?>" />
			<label class="input__label in_label_" for="image_margin_left">
				<span class="input__label-content input__label-content--grad"><?php _e('Margin Left'); ?>:</span>
			</label>
		</span>
	</div>
	
	<!-- image_margin_right -->
	<div class="settings_css">
		<span class="ginput input--grad">
			<input type="text" class="input__field width_half" id="image_margin_right" name="custom_preloader_[image_margin_right]" onchange="document.getElementById('set_margin-right').value = document.getElementById('image_margin_right').value" value="<?php margin_right(); ?>" />
			<label class="input__label in_label_" for="image_margin_right">
				<span class="input__label-content input__label-content--grad"><?php _e('Margin Right'); ?>:</span>
			</label>
		</span>
	</div>

	<!-- image_margin_bottom -->
	<div class="settings_css">
		<span class="ginput input--grad">
			<input type="text" class="input__field width_half" id="image_margin_bottom" name="custom_preloader_[image_margin_bottom]" onchange="document.getElementById('set_margin-bottom').value = document.getElementById('image_margin_bottom').value" value="<?php margin_bottom(); ?>" />
			<label class="input__label in_label_" for="image_margin_bottom">
				<span class="input__label-content input__label-content--grad"><?php _e('Margin Bottom'); ?>:</span>
			</label>
		</span>
	</div>
</div>
<?php
}


function custom_preloader__validate($input) {
	return $input; 
}



function bg_gradient_code() 
{
	$options = get_option('custom_preloader_');
	$value = preg_replace("/\s+/","",$options['bg_gradient_code']);
	
	//echo '<div style="top: 130px;margin: 0px;font-size: .7em;" class="tool-tip top">'; _e('Add your Colorful Code Here'); echo '</div>';
	//echo '<textarea type="text" id="bg_gradient_code" name="custom_preloader_[bg_gradient_code]" class=" input__field input__field--grad" > '.$value.' </textarea>';
	echo '<input type="text" id="bg_gradient_code" name="custom_preloader_[bg_gradient_code]" class=" input__field width_half" value="'.$value.'"/>';
}

function bg_color_settings()
{
	$value = null;
	$options = get_option('custom_preloader_');
	if(isset($options['bg_color_settings']))
	{  
		$value = $options['bg_color_settings'];
	}	
?>
	<input type="text" class="jscolor width_half input__field" id="smplbg" name="custom_preloader_[bg_color_settings]" value="<?php echo $value; ?>" <?php if(isset($options['bg_gradient_enabled']) && !isset($options['enabled_settings'])) { echo ' disabled '; echo ' style="cursor: no-drop;"';}?>/>
<?php 
}

function main_section_text(){
?>
<div class="form-group">

	<!-- Simple Background -->
	<div class="settings_css">
		<span class="ginput input--grad">
			<?php bg_color_settings(); ?>
			<label class="input__label in_label_" for="smplbg">
				<span class="input__label-content input__label-content--grad"><?php _e('Pick Simple Color'); ?></span>
			</label>
		</span>
	</div>
	
	<!-- Set Image -->
	<div class="settings_css">
		<span class="ginput input--grad">
			<?php image_settings(); ?>
			<label class="input__label in_label_" for="image_settings">
				<span id="upload-button" class="input__label-content input__label-content--grad"><?php _e('Pick Your Image'); ?></span>
			</label>
		</span>
	</div>
	
	<!-- Colorful Background -->
	<div class="settings_css">
	<span class="ginput input--grad">
			<?php bg_gradient_code(); ?>
			<label class="input__label in_label_" for="bg_gradient_code">
				<span class="input__label-content input__label-content--grad"><?php _e('Paste Your Colorful Code'); ?></span>
			</label>
		</span>
	</div>
	
</div>
<?php
}

function image_settings() {
	$options = get_option('custom_preloader_');
	$value = $options['image_settings'];
?>
	<input type="text" id="image_settings" name="custom_preloader_[image_settings]" class="imgsettingsprv input__field width_half" value="<?php echo $value; ?>" />

<?php }

function image_width_settings() {
	$options = get_option('custom_preloader_');
	if(!isset($options['image_width_settings'])){
		$value = '150px';
	}else{
		$value = $options['image_width_settings'];
	}
?>
	<input type="text" id="image_width_settings" name="custom_preloader_[image_width_settings]" value="<?php echo $value; ?>" />
<?php 
}

function image_height_settings() {
	$options = get_option('custom_preloader_');
	if(!isset($options['image_height_settings'])){
		$value = '150px';
	}else{
		$value = $options['image_height_settings'];
	}
?>
	<input type="text" id="image_height_settings" name="custom_preloader_[image_height_settings]" value="<?php echo $value; ?>" />
<?php
}

function my_enqueue_media_lib_uploader() {

    //Core media script
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'my_enqueue_media_lib_uploader');