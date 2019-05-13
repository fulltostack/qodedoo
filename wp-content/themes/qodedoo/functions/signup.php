<?php
	function theme_name_scripts() {
	    wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	}
	
	add_action('wp_head', 'myplugin_ajaxurl');

	function myplugin_ajaxurl() {
	    echo '<script type="text/javascript">
	           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
	         </script>';
	}
	
	add_action('wp_ajax_check_username', function(){
	    if(username_exists($_GET['username']) == null)
	        echo 0;
	    else echo 1;
	    die();
	});

	add_action('wp_ajax_register_user', function(){
		$username 	= isset($_POST['username']) ? $_POST['username'] 	: '' ;
		$password 	= isset($_POST['password']) ? $_POST['password'] 	: '' ;
		$email 		= isset($_POST['email']) 	? $_POST['email'] 		: '' ;
		$status 	= wp_create_user( $username, $password ,$email );

		$dob = explode('-',$_POST['dob']);
		$dob = $dob[1].'/'.$dob[2].'/'.$dob[0];

		$body = array(
		    'email' => $email,
		    'password' => $password,
		    'phone_number' => $_POST['mobile'],
		    'country_code' => '+91',
		    'dob' => $dob,
		    'name' => $_POST['persona'],
		    'username' => $username,
		);

		$args = array(
		    'body' => $body,
		    'timeout' => '5',
		    'redirection' => '5',
		    'httpversion' => '1.0',
		    'blocking' => true,
		    'headers' => array(),
		    'cookies' => array()
		);

		$response = wp_remote_post( 'http://qodedoo.herokuapp.com/api/users/sign_up', $args );

		die();
	});

	add_action( 'user_register', 'my_save_extra_profile_fields' );
	add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
   	add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

   	function my_save_extra_profile_fields( $user_id ) {
       	$mobile 	= isset($_POST['mobile']) 	? $_POST['mobile'] 		: '' ;
		$dob 		= isset($_POST['dob']) 		? $_POST['dob'] 		: '' ;
		$persona 	= isset($_POST['persona']) 	? $_POST['persona'] 	: '' ;
		$password 	= isset($_POST['password']) ? $_POST['password'] 	: '' ;

		if ( $_FILES ) { 
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');
			$file_handler = 'updoc';
			$attach_id = media_handle_upload($file_handler,$pid );
		}

       	update_user_meta( $user_id, 'mobile', sanitize_text_field($mobile));
       	update_user_meta( $user_id, 'dob', sanitize_text_field($dob));
       	update_user_meta( $user_id, 'persona', sanitize_text_field($persona));
       	update_user_meta( $user_id, 'password', sanitize_text_field($password));
       	update_user_meta( $user_id, 'media', sanitize_text_field($attach_id));
   	}

   	add_action('wp_ajax_forgot_password', function(){
	   	$user = get_user_by('email', sanitize_text_field($_POST['email']));

	    $email = $user->user_email;
	    $adt_rp_key = get_password_reset_key( $user );
	    $user_login = $user->user_login;
	    $rp_link = '<a href="' . wp_login_url()."/resetpass/?key=$adt_rp_key&login=" . rawurlencode($user_login) . '">' . wp_login_url()."/resetpass/?key=$adt_rp_key&login=" . rawurlencode($user_login) . '</a>';

	    $message = "Hi,<br>";
	    $message .= "Please click the link below to reset your password<br>";
	    $message .= $rp_link.'<br>';

	   $subject = __("Reset Password Link");
	   $headers = array();

	   add_filter( 'wp_mail_content_type', function( $content_type ) {return 'text/html';});
	   $headers[] = 'From: Your company name <info@your-domain.com>'."\r\n";
	   echo wp_mail( $email, $subject, $message, $headers);

	   remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
   	});


   	
?>