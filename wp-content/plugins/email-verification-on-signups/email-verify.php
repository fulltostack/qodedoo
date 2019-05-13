<?php
/**
 * Plugin Name: 	  Email Verification on Signups
 * Description: 	  Send a verification email to newly registered users.
 * Version:           1.1.2
 * Author:            Am!n
 * Author URI: 		  http://www.dornaweb.com
 * License:           MIT
 * Text Domain:       dwverify
 * Domain Path:   	  /lang
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . '/vendor/autoload.php';
load_plugin_textdomain( 'dwverify', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

class DWEmailVerify{

	/**
	 * Version
	 */
	const PLUGIN_VERSION = '1.1.2';

	/**
	 * @var str $secret
	 */
	public $secret = "25#-asdv8+abox";

	/**
	 * Construct
	 */
	public function __construct(){
		$this->includes();

		add_action( 'user_register', array( $this, 'user_register' ) );
		add_filter( 'authenticate', array( $this, 'check_active_user' ), 100, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'assets' ) );
	}

	/**
	 * Include required fiels
	 */
	public function includes(){
		include_once $this->path() . 'settings.php';
		include_once $this->path() . 'shortcode.php';
		include_once $this->path() . 'user-mods.php';
	}

	/**
	 * Instanciating
	 */
	public static function instance(){
		return new self();
	}

	/**
	 * Triggers when plugin gets activated
	 */
	public static function plugin_activated() {
		self::instance()->create_plugin_pages();
		self::instance()->set_default_settings();
	}

	/**
	 * Plugin assets
	 */
	public function assets(){
		wp_register_script( 'dw-verify-js', $this->url() . 'assets/js/verify-email.js', array('jquery'), null, true );
		wp_localize_script( 'dw-verify-js', 'dwverify', array(
			'ajaxurl'		=> admin_url('admin-ajax.php'),
			'confirm_text'	=> __('Are you sure you want to re-send verification link?', 'dwverify')
		));
		wp_enqueue_script( 'dw-verify-js' );
	}

	/**
	 * Create default pages
	 */
	public function create_plugin_pages() {
		$pages = array(
			'authorize' => array(
				'title' => __( 'Authorize', 'dwverify' ),
				'content' => '[dw-verify-email]',
				'option_id' => 'dw_verify_authorize_page'
			)
		);

		$pages_option = array();

		foreach( $pages as $slug => $page ) {
			$query = new WP_Query( 'pagename=' . $slug );
			if ( ! $query->have_posts() ) {

				// Add the page using the data from the array above
				update_option( $page['option_id'],
					wp_insert_post(
						array(
							'post_content'   => $page['content'],
							'post_name'      => $slug,
							'post_title'     => $page['title'],
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'ping_status'    => 'closed',
							'comment_status' => 'closed',
						)
					)
				);
			}
		}
	}

	/**
	 * Set default settings
	 */
	public function set_default_settings(){
		update_option('dw_verify_max_resend_allowed', 5);
	}

	/**
	 * Creates a hash when new user registers and stores the hash as a meta value
	 *
	 * @param int $user_id
	 */
	public function user_register( $user_id ){
		if( is_admin() && current_user_can( 'create_users') && ! empty( $_POST['skip_verification'] ) ){
			return; // ignore adding verify lock
		}
		$this->send_verification_link( $user_id );
		//wp_redirect( add_query_arg( 'awaiting-verification', 'true', $this->authorize_page_url() ) );
		return;
	}

	/**
	 * Lock user's account, send a verification email and ask them to verify their email address
	 * @param int  $user_id
	 */
	public function send_verification_link( $user_id ){
		$user = get_user_by('id', $user_id);

		$this->lock_user( $user_id );
		$this->send_email( $user );
	}

	/**
	 * Lock user
	 * @param int  $user_id
	 */
	public function lock_user( $user_id ){
		$user = get_user_by('id', $user_id);
		add_user_meta( $user_id, 'verify-lock', $this->generate_hash( $user->data->user_email ) );
	}

	/**
	 * Unlock user
	 * @param int  $user_id
	 */
	public function unlock_user( $user_id ){
		delete_user_meta( $user_id, 'verify-lock' );
	}

	/**
	 * Generate a url-friendly verification hash
	 *
	 * @param str $email
	 */
	public function generate_hash( $email = '' ){
		$key = $email.$this->secret . rand(0, 1000);

		return MD5( $key );
	}

	/**
	 * Prevents users from loggin in, if they have not verified their email address
	 *
	 * @param WP_User   $user
	 * @param str       $username
	 */
	public function check_active_user( $user, $username ){
		if(isset($user->ID))
		{
			$lock = get_user_meta( $user->ID, "verify-lock", true );

			if( $lock && ! empty( $lock ) ) {
				return new WP_Error( 'email_not_verified', sprintf(
					__('You have not verified your email address, please check your email and click on verification link we sent you, <a href="#resend" onClick="%s">Re-send the link</a>', 'dwverify'),
					"resend_verify_link('{$username}'); return false;"
				));
			}
		}

		return $user;
	}

	/**
	 * Send verification email
	 *
	 * @param WP_User $user
	 */
	public function send_email( $user = false ){
		
		if( ! $user || ! $user instanceof WP_User )
			return;
		$lock = get_user_meta( $user->ID, "verify-lock", true );
		// Ignore if there is no lock
		if( ! $lock || empty( $lock ) )
			return;
		$user_email = $user->data->user_email;
		/**
		 * Add support for localized templates, just append your locale code to your template file name
		 *     - eg. verify-fa_IR.php
		 *			 verify-en-GB.php
		 */
		$template = file_exists( $this->path() .'tpl/emails/verify-'. get_locale() .'.php' ) ? $this->path() .'tpl/emails/verify-'. get_locale() .'.php' : $this->path() .'tpl/emails/verify.php';

		$template = apply_filters( 'dw_verify_email_template_path', $template );
		$link = add_query_arg( array('user_id' => $user->ID, 'verify_email' => $lock), $this->authorize_page_url() );
	
	$mail_path = get_template_directory_uri().'/mail/';

	$my_current_lang = apply_filters( 'wpml_current_language', NULL );

	if($my_current_lang == 'zh-hans')
	{
		$subject = '确认你的邮件地址';
			    $body = '<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="format-detection" content="telephone=no"/>

    <style>

body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
#outlook a { padding: 0; }
.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
.skewed_button {
    background: #c89c3d;
    color: #000;
    text-decoration: none;
    font-size: 20px;
    display: inline-block;
    height: 30px;
    margin-left: 15px;
    padding: 6px 20px 0;
}


@media all and (min-width: 560px) {
    .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
}


a, a:hover {
    color: #127DB3;
}

.footer a, .footer a:hover {
    color: #999999;
}

    </style>


    <title>Fantasy Esports</title>

</head>


<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;

    color: #000000;"

    text="#000000">

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
    >

<table border="0" cellpadding="0" cellspacing="0" align="center"
    width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0 15px; width: inherit;
    max-width: 1280px; background: #fff; margin: 20px 0 0 0; border: 5px rgb(237,125,49) solid" class="container">

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
            padding-top: 25px;
            color: rgb(237,125,49);
            font-family: sans-serif;" class="header">
           恭喜！
        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
            padding-top: 5px;
            color: #000000;
            font-family: sans-serif;" class="subheader">

        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">你已经在qodedoo上正式保留了你的用户名; 世界上第一个以区块链为动力的通用社交媒体平台！ 不久你就可以开始赚钱并花费uDoo了</td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 15px; font-weight: 400; line-height: 160%;
            padding-top: 25px;
            color: #fff;
            font-family: sans-serif;" class="paragraph">
                 <a  href="'.$link.'" style="color: rgb(237,125,49);  font-size: 20px; font-weight: 500; font-family: arial;">点击此处立即验证您的用户名。</span>
        </td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">请务必关注以下社交网站，以便您随时了解qodedoo的最新消息
来自我们的支持者，活动，平台更新和发布的更新。
非常感谢qodedoo团队，并在测试版发布时见到你！
</td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
            padding-top: 40px;
            padding-bottom: 30px;" class="button"><a
            href="#"  style="">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle"
                    >
                    <img src="'.$mail_path.'images/logo.png" style="max-width: 100%;">

            </td></tr></table></a>
                <div style=" margin: 50px auto;">
        <div style="padding: 0 30px; display: inline-block;"><a href="https://www.facebook.com/qodedooHQ/"><img src="'.$mail_path.'images/facebook.png"></a></div>
         <div style="padding: 0 30px; display: inline-block;"><a href="https://www.instagram.com/qodedoo_hq/"><img src="'.$mail_path.'images/instagram.png"></a></div>
          <div style="padding: 0 30px; display: inline-block;"><a href="https://www.youtube.com/channel/UC_pyFmgep3yNEZ5yysXCkrA"><img src="'.$mail_path.'images/youtube.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://medium.com/qodedoo"><img src="'.$mail_path.'images/medium-hover.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://twitter.com/qodedoohq?lang=en"><img src="'.$mail_path.'images/twitter.png"></a></div>
    </div>
        </td>

    </tr>
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
            padding-top: 20px;
            padding-bottom: 20px;
            color: #000;
            font-size: 16px;
            font-weight: 600;
            font-family: sans-serif;" class="footer">

                版权所有2018 qodedoo ECO.保留所有权利。
        </td>
    </tr>
</table>
</td></tr></table>

</body>
</html>
';
	}
	else if($my_current_lang == 'pt-pt')
	{
		$subject = 'Verifique seu endereço de e-mail';
			    $body = '<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="format-detection" content="telephone=no"/>

    <style>

body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
#outlook a { padding: 0; }
.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
.skewed_button {
    background: #c89c3d;
    color: #000;
    text-decoration: none;
    font-size: 20px;
    display: inline-block;
    height: 30px;
    margin-left: 15px;
    padding: 6px 20px 0;
}


@media all and (min-width: 560px) {
    .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
}


a, a:hover {
    color: #127DB3;
}

.footer a, .footer a:hover {
    color: #999999;
}

    </style>


    <title>Fantasy Esports</title>

</head>


<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;

    color: #000000;"

    text="#000000">

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
    >

<table border="0" cellpadding="0" cellspacing="0" align="center"
    width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0 15px; width: inherit;
    max-width: 1280px; background: #fff; margin: 20px 0 0 0; border: 5px rgb(237,125,49) solid" class="container">

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
            padding-top: 25px;
            color: rgb(237,125,49);
            font-family: sans-serif;" class="header">
           Parabéns!
        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
            padding-top: 5px;
            color: #000000;
            font-family: sans-serif;" class="subheader">

        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Você reservou oficialmente seu nome de usuário no qodedoo; a primeira plataforma de mídia social para todos os propósitos do mundo alimentada pelo blockchain! Não demorará muito até você começar a ganhar e a gastar o uDoo.</td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 15px; font-weight: 400; line-height: 160%;
            padding-top: 25px;
            color: #fff;
            font-family: sans-serif;" class="paragraph">
                 <a  href="'.$link.'" style="color: rgb(237,125,49);  font-size: 20px; font-weight: 500; font-family: arial;">Clique aqui para validar seu nome de usuário agora.</span>
        </td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Não deixe de nos acompanhar nas redes sociais abaixo, para que você esteja sempre informado sobre as últimas novidades do qodedoo
atualizações de nossos apoiadores, eventos, atualizações de plataforma e lançamentos.
Muito obrigado da equipe qodedoo e até a versão beta do lançamento!
</td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
            padding-top: 40px;
            padding-bottom: 30px;" class="button"><a
            href="#"  style="">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle"
                    >
                    <img src="'.$mail_path.'images/logo.png" style="max-width: 100%;">

            </td></tr></table></a>
                <div style=" margin: 50px auto;">
        <div style="padding: 0 30px; display: inline-block;"><a href="https://www.facebook.com/qodedooHQ/"><img src="'.$mail_path.'images/facebook.png"></a></div>
         <div style="padding: 0 30px; display: inline-block;"><a href="https://www.instagram.com/qodedoo_hq/"><img src="'.$mail_path.'images/instagram.png"></a></div>
          <div style="padding: 0 30px; display: inline-block;"><a href="https://www.youtube.com/channel/UC_pyFmgep3yNEZ5yysXCkrA"><img src="'.$mail_path.'images/youtube.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://medium.com/qodedoo"><img src="'.$mail_path.'images/medium-hover.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://twitter.com/qodedoohq?lang=en"><img src="'.$mail_path.'images/twitter.png"></a></div>
    </div>
        </td>

    </tr>
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
            padding-top: 20px;
            padding-bottom: 20px;
            color: #000;
            font-size: 16px;
            font-weight: 600;
            font-family: sans-serif;" class="footer">

                Copyright 2018 qodedoo ECO. Todos os Direitos Reservados.
        </td>
    </tr>
</table>
</td></tr></table>

</body>
</html>
';
	}
	else if($my_current_lang == 'ko')
	{
		$subject = '이메일 주소를 확인';
			    $body = '<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="format-detection" content="telephone=no"/>

    <style>

body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
#outlook a { padding: 0; }
.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
.skewed_button {
    background: #c89c3d;
    color: #000;
    text-decoration: none;
    font-size: 20px;
    display: inline-block;
    height: 30px;
    margin-left: 15px;
    padding: 6px 20px 0;
}


@media all and (min-width: 560px) {
    .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
}


a, a:hover {
    color: #127DB3;
}

.footer a, .footer a:hover {
    color: #999999;
}

    </style>


    <title>Fantasy Esports</title>

</head>


<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;

    color: #000000;"

    text="#000000">

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
    >

<table border="0" cellpadding="0" cellspacing="0" align="center"
    width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0 15px; width: inherit;
    max-width: 1280px; background: #fff; margin: 20px 0 0 0; border: 5px rgb(237,125,49) solid" class="container">

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
            padding-top: 25px;
            color: rgb(237,125,49);
            font-family: sans-serif;" class="header">
           축하해!
        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
            padding-top: 5px;
            color: #000000;
            font-family: sans-serif;" class="subheader">

        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">qodedoo에서 공식적으로 사용자 이름을 예약했습니다. 블록 체인에 의해 연료가 공급되는 세계의 모든 목적을위한 최초의 소셜 미디어 플랫폼! uDoo를 벌기 시작하기 전에 오래 걸리지 않을 것입니다.</td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 15px; font-weight: 400; line-height: 160%;
            padding-top: 25px;
            color: #fff;
            font-family: sans-serif;" class="paragraph">
                 <a  href="'.$link.'" style="color: rgb(237,125,49);  font-size: 20px; font-weight: 500; font-family: arial;">지금 사용자 이름을 확인하려면 여기를 클릭하십시오.</span>
        </td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">아래의 소셜 네트워크에서 우리를 따라 가십시오. 그러면 qodedoo의 최신 개발에 대해 항상 알 수 있습니다.
후원자, 이벤트, 플랫폼 업데이트 및 릴리스의 업데이트.
qodedoo 팀과 베타 출시까지 너무 감사드립니다!
</td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
            padding-top: 40px;
            padding-bottom: 30px;" class="button"><a
            href="#"  style="">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle"
                    >
                    <img src="'.$mail_path.'images/logo.png" style="max-width: 100%;">

            </td></tr></table></a>
                <div style=" margin: 50px auto;">
        <div style="padding: 0 30px; display: inline-block;"><a href="https://www.facebook.com/qodedooHQ/"><img src="'.$mail_path.'images/facebook.png"></a></div>
         <div style="padding: 0 30px; display: inline-block;"><a href="https://www.instagram.com/qodedoo_hq/"><img src="'.$mail_path.'images/instagram.png"></a></div>
          <div style="padding: 0 30px; display: inline-block;"><a href="https://www.youtube.com/channel/UC_pyFmgep3yNEZ5yysXCkrA"><img src="'.$mail_path.'images/youtube.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://medium.com/qodedoo"><img src="'.$mail_path.'images/medium-hover.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://twitter.com/qodedoohq?lang=en"><img src="'.$mail_path.'images/twitter.png"></a></div>
    </div>
        </td>

    </tr>
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
            padding-top: 20px;
            padding-bottom: 20px;
            color: #000;
            font-size: 16px;
            font-weight: 600;
            font-family: sans-serif;" class="footer">

                저작권 2018 년 qodedoo ECO. 판권 소유.
        </td>
    </tr>
</table>
</td></tr></table>

</body>
</html>
';
	}
	else if($my_current_lang == 'de')
    {
    	$subject = 'Überprüfen Sie Ihre e-Mail-Adresse';
    	$body = '<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="format-detection" content="telephone=no"/>

    <style>

body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
#outlook a { padding: 0; }
.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
.skewed_button {
    background: #c89c3d;
    color: #000;
    text-decoration: none;
    font-size: 20px;
    display: inline-block;
    height: 30px;
    margin-left: 15px;
    padding: 6px 20px 0;
}


@media all and (min-width: 560px) {
    .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
}


a, a:hover {
    color: #127DB3;
}

.footer a, .footer a:hover {
    color: #999999;
}

    </style>


    <title>Fantasy Esports</title>

</head>


<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;

    color: #000000;"

    text="#000000">

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
    >

<table border="0" cellpadding="0" cellspacing="0" align="center"
    width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0 15px; width: inherit;
    max-width: 1280px; background: #fff; margin: 20px 0 0 0; border: 5px rgb(237,125,49) solid" class="container">

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
            padding-top: 25px;
            color: rgb(237,125,49);
            font-family: sans-serif;" class="header">
           Herzlichen Glückwunsch!
        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
            padding-top: 5px;
            color: #000000;
            font-family: sans-serif;" class="subheader">

        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Sie haben Ihren Benutzernamen auf qodedoo offiziell reserviert; die weltweit erste Allzweck social Media Plattform angetrieben durch die Blockchain! Es wird nicht lange dauern, bis Sie beginnen können, sammeln und Einlösen von uDoo sein.</td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 15px; font-weight: 400; line-height: 160%;
            padding-top: 25px;
            color: #fff;
            font-family: sans-serif;" class="paragraph">
                 <a  href="'.$link.'" style="color: rgb(237,125,49);  font-size: 20px; font-weight: 500; font-family: arial;">Klicken Sie hier, um jetzt Ihren Benutzernamen zu validieren.</span>
        </td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Achten Sie darauf, uns zu folgen, auf die unter Socials, sodass Sie immer auf dem laufenden auf qodedoos neuesten Updates von unseren Unterstützern, Veranstaltungen, Plattform aktualisiert und veröffentlicht. Vielen Dank aus dem qodedoo-Team und wir sehen uns bei der Beta Launch!
</td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
            padding-top: 40px;
            padding-bottom: 30px;" class="button"><a
            href="#"  style="">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle"
                    >
                    <img src="'.$mail_path.'images/logo.png" style="max-width: 100%;">

            </td></tr></table></a>
                <div style=" margin: 50px auto;">
        <div style="padding: 0 30px; display: inline-block;"><a href="https://www.facebook.com/qodedooHQ/"><img src="'.$mail_path.'images/facebook.png"></a></div>
         <div style="padding: 0 30px; display: inline-block;"><a href="https://www.instagram.com/qodedoo_hq/"><img src="'.$mail_path.'images/instagram.png"></a></div>
          <div style="padding: 0 30px; display: inline-block;"><a href="https://www.youtube.com/channel/UC_pyFmgep3yNEZ5yysXCkrA"><img src="'.$mail_path.'images/youtube.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://medium.com/qodedoo"><img src="'.$mail_path.'images/medium-hover.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://twitter.com/qodedoohq?lang=en"><img src="'.$mail_path.'images/twitter.png"></a></div>
    </div>
        </td>

    </tr>
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
            padding-top: 20px;
            padding-bottom: 20px;
            color: #000;
            font-size: 16px;
            font-weight: 600;
            font-family: sans-serif;" class="footer">

                Copyright 2018 qodedoo ECO. Alle Rechte vorbehalten.
        </td>
    </tr>
</table>
</td></tr></table>

</body>
</html>
';
    }
    else if($my_current_lang == 'es')
    {
    	$subject = 'Verificar su dirección de correo electrónico';
    	$body = '<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="format-detection" content="telephone=no"/>

    <style>

body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
#outlook a { padding: 0; }
.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
.skewed_button {
    background: #c89c3d;
    color: #000;
    text-decoration: none;
    font-size: 20px;
    display: inline-block;
    height: 30px;
    margin-left: 15px;
    padding: 6px 20px 0;
}


@media all and (min-width: 560px) {
    .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
}


a, a:hover {
    color: #127DB3;
}

.footer a, .footer a:hover {
    color: #999999;
}

    </style>


    <title>Fantasy Esports</title>

</head>


<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;

    color: #000000;"

    text="#000000">

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
    >

<table border="0" cellpadding="0" cellspacing="0" align="center"
    width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0 15px; width: inherit;
    max-width: 1280px; background: #fff; margin: 20px 0 0 0; border: 5px rgb(237,125,49) solid" class="container">

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
            padding-top: 25px;
            color: rgb(237,125,49);
            font-family: sans-serif;" class="header">
           ¡Felicidades!
        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
            padding-top: 5px;
            color: #000000;
            font-family: sans-serif;" class="subheader">

        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Oficialmente ha reservado su nombre de usuario en qodedoo; ¡la mundos primer uso múltiple plataforma de medios sociales impulsada por el blockchain! No pasará mucho tiempo hasta que puede empezar a ganar y pasar de uDoo.</td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 15px; font-weight: 400; line-height: 160%;
            padding-top: 25px;
            color: #fff;
            font-family: sans-serif;" class="paragraph">
                 <a  href="'.$link.'" style="color: rgb(237,125,49);  font-size: 20px; font-weight: 500; font-family: arial;">Haga clic aquí para validar tu nombre de usuario ahora.</span>
        </td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">No olvide seguirnos en el debajo de sociales, por lo que siempre está en el saber sobre las últimas actualizaciones de qodedoo de nuestros patrocinadores, eventos, plataforma actualiza y libera. Muchas gracias desde el equipo de qodedoo y nos vemos en la beta de lanzamiento!
</td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
            padding-top: 40px;
            padding-bottom: 30px;" class="button"><a
            href="#"  style="">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle"
                    >
                    <img src="'.$mail_path.'images/logo.png" style="max-width: 100%;">

            </td></tr></table></a>
                <div style=" margin: 50px auto;">
        <div style="padding: 0 30px; display: inline-block;"><a href="https://www.facebook.com/qodedooHQ/"><img src="'.$mail_path.'images/facebook.png"></a></div>
         <div style="padding: 0 30px; display: inline-block;"><a href="https://www.instagram.com/qodedoo_hq/"><img src="'.$mail_path.'images/instagram.png"></a></div>
          <div style="padding: 0 30px; display: inline-block;"><a href="https://www.youtube.com/channel/UC_pyFmgep3yNEZ5yysXCkrA"><img src="'.$mail_path.'images/youtube.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://medium.com/qodedoo"><img src="'.$mail_path.'images/medium-hover.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://twitter.com/qodedoohq?lang=en"><img src="'.$mail_path.'images/twitter.png"></a></div>
    </div>
        </td>

    </tr>
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
            padding-top: 20px;
            padding-bottom: 20px;
            color: #000;
            font-size: 16px;
            font-weight: 600;
            font-family: sans-serif;" class="footer">

                Copyright 2018 qodedoo ECO. Todos los derechos reservados.
        </td>
    </tr>
</table>
</td></tr></table>

</body>
</html>
';
    }
    else if($my_current_lang == 'pl')
    {
    	$subject = 'Zweryfikuj swój adres e-mail';
    	$body = '<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="format-detection" content="telephone=no"/>

    <style>

body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
#outlook a { padding: 0; }
.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
.skewed_button {
    background: #c89c3d;
    color: #000;
    text-decoration: none;
    font-size: 20px;
    display: inline-block;
    height: 30px;
    margin-left: 15px;
    padding: 6px 20px 0;
}


@media all and (min-width: 560px) {
    .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
}


a, a:hover {
    color: #127DB3;
}

.footer a, .footer a:hover {
    color: #999999;
}

    </style>


    <title>Fantasy Esports</title>

</head>


<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;

    color: #000000;"

    text="#000000">

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
    >

<table border="0" cellpadding="0" cellspacing="0" align="center"
    width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0 15px; width: inherit;
    max-width: 1280px; background: #fff; margin: 20px 0 0 0; border: 5px rgb(237,125,49) solid" class="container">

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
            padding-top: 25px;
            color: rgb(237,125,49);
            font-family: sans-serif;" class="header">
           Gratulacje!
        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
            padding-top: 5px;
            color: #000000;
            font-family: sans-serif;" class="subheader">

        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Już oficjalnie zastrzeżone nazwy użytkownika na qodedoo; światy pierwszy uniwersalny platformy social media zasilany przez blockchain! To nie będzie długo, aż zaczniesz zarabiać i wydatków uDoo.</td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 15px; font-weight: 400; line-height: 160%;
            padding-top: 25px;
            color: #fff;
            font-family: sans-serif;" class="paragraph">
                 <a  href="'.$link.'" style="color: rgb(237,125,49);  font-size: 20px; font-weight: 500; font-family: arial;">Kliknij tutaj, aby teraz potwierdzić swoją nazwę użytkownika.</span>
        </td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Koniecznie śledź nas na poniżej towarzyskie, więc zawsze jesteś na bieżąco na qodedoo najnowsze aktualizacje z naszych kibiców, wydarzenia, platforma aktualizuje i zwalnia. Wielkie dzięki z zespołu qodedoo i do zobaczenia na rozpoczęcie beta!
</td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
            padding-top: 40px;
            padding-bottom: 30px;" class="button"><a
            href="#"  style="">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle"
                    >
                    <img src="'.$mail_path.'images/logo.png" style="max-width: 100%;">

            </td></tr></table></a>
                <div style=" margin: 50px auto;">
        <div style="padding: 0 30px; display: inline-block;"><a href="https://www.facebook.com/qodedooHQ/"><img src="'.$mail_path.'images/facebook.png"></a></div>
         <div style="padding: 0 30px; display: inline-block;"><a href="https://www.instagram.com/qodedoo_hq/"><img src="'.$mail_path.'images/instagram.png"></a></div>
          <div style="padding: 0 30px; display: inline-block;"><a href="https://www.youtube.com/channel/UC_pyFmgep3yNEZ5yysXCkrA"><img src="'.$mail_path.'images/youtube.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://medium.com/qodedoo"><img src="'.$mail_path.'images/medium-hover.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://twitter.com/qodedoohq?lang=en"><img src="'.$mail_path.'images/twitter.png"></a></div>
    </div>
        </td>

    </tr>
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
            padding-top: 20px;
            padding-bottom: 20px;
            color: #000;
            font-size: 16px;
            font-weight: 600;
            font-family: sans-serif;" class="footer">

                Prawa autorskie 2018 qodedoo ECO. Wszelkie prawa zastrzeżone.
        </td>
    </tr>
</table>
</td></tr></table>

</body>
</html>
';
    }
    else if($my_current_lang == 'ro')
    {
    	$subject = 'Verifica adresa de e-mail';
    	$body = '<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="format-detection" content="telephone=no"/>

    <style>

body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
#outlook a { padding: 0; }
.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
.skewed_button {
    background: #c89c3d;
    color: #000;
    text-decoration: none;
    font-size: 20px;
    display: inline-block;
    height: 30px;
    margin-left: 15px;
    padding: 6px 20px 0;
}


@media all and (min-width: 560px) {
    .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
}


a, a:hover {
    color: #127DB3;
}

.footer a, .footer a:hover {
    color: #999999;
}

    </style>


    <title>Fantasy Esports</title>

</head>


<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;

    color: #000000;"

    text="#000000">

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
    >

<table border="0" cellpadding="0" cellspacing="0" align="center"
    width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0 15px; width: inherit;
    max-width: 1280px; background: #fff; margin: 20px 0 0 0; border: 5px rgb(237,125,49) solid" class="container">

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
            padding-top: 25px;
            color: rgb(237,125,49);
            font-family: sans-serif;" class="header">
           Felicitări!
        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
            padding-top: 5px;
            color: #000000;
            font-family: sans-serif;" class="subheader">

        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Te-am rezervat oficial numele de utilizator pe qodedoo; lumi prima toate-scop social media platformă puternic by blockchain! Acesta nu va fi mult timp până când puteţi începe castiguri si cheltuieli sardari pe.</td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 15px; font-weight: 400; line-height: 160%;
            padding-top: 25px;
            color: #fff;
            font-family: sans-serif;" class="paragraph">
                 <a  href="'.$link.'" style="color: rgb(237,125,49);  font-size: 20px; font-weight: 500; font-family: arial;">Click aici pentru a valida acum numele de utilizator.</span>
        </td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Asiguraţi-vă că urmaţi-ne sub socials, astfel încât sunteţi întotdeauna în stiu pe qodedoo pe cele mai recente actualizări de la suporterii noştri, evenimente, platforma actualizări şi comunicate. Multumiri din partea echipei qodedoo si ne vedem la lansarea beta!
</td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
            padding-top: 40px;
            padding-bottom: 30px;" class="button"><a
            href="#"  style="">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle"
                    >
                    <img src="'.$mail_path.'images/logo.png" style="max-width: 100%;">

            </td></tr></table></a>
                <div style=" margin: 50px auto;">
        <div style="padding: 0 30px; display: inline-block;"><a href="https://www.facebook.com/qodedooHQ/"><img src="'.$mail_path.'images/facebook.png"></a></div>
         <div style="padding: 0 30px; display: inline-block;"><a href="https://www.instagram.com/qodedoo_hq/"><img src="'.$mail_path.'images/instagram.png"></a></div>
          <div style="padding: 0 30px; display: inline-block;"><a href="https://www.youtube.com/channel/UC_pyFmgep3yNEZ5yysXCkrA"><img src="'.$mail_path.'images/youtube.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://medium.com/qodedoo"><img src="'.$mail_path.'images/medium-hover.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://twitter.com/qodedoohq?lang=en"><img src="'.$mail_path.'images/twitter.png"></a></div>
    </div>
        </td>

    </tr>
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
            padding-top: 20px;
            padding-bottom: 20px;
            color: #000;
            font-size: 16px;
            font-weight: 600;
            font-family: sans-serif;" class="footer">

                Copyright 2018 qodedoo ECO. Toate drepturile rezervate.
        </td>
    </tr>
</table>
</td></tr></table>

</body>
</html>
';
    }
    else if($my_current_lang == 'ru')
    {
    	$subject = 'Проверьте свой адрес электронной почты';
    	$body = '<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="format-detection" content="telephone=no"/>

    <style>

body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
#outlook a { padding: 0; }
.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
.skewed_button {
    background: #c89c3d;
    color: #000;
    text-decoration: none;
    font-size: 20px;
    display: inline-block;
    height: 30px;
    margin-left: 15px;
    padding: 6px 20px 0;
}


@media all and (min-width: 560px) {
    .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
}


a, a:hover {
    color: #127DB3;
}

.footer a, .footer a:hover {
    color: #999999;
}

    </style>


    <title>Fantasy Esports</title>

</head>


<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;

    color: #000000;"

    text="#000000">

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
    >

<table border="0" cellpadding="0" cellspacing="0" align="center"
    width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0 15px; width: inherit;
    max-width: 1280px; background: #fff; margin: 20px 0 0 0; border: 5px rgb(237,125,49) solid" class="container">

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
            padding-top: 25px;
            color: rgb(237,125,49);
            font-family: sans-serif;" class="header">
           Поздравляю!
        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
            padding-top: 5px;
            color: #000000;
            font-family: sans-serif;" class="subheader">

        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Вы официально зарезервировали имя пользователя на qodedoo; миров первый универсальные социальные медиа-платформы работает на blockchain! Он не будет долго, пока вы можете начать зарабатывать и тратить в uDoo.</td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 15px; font-weight: 400; line-height: 160%;
            padding-top: 25px;
            color: #fff;
            font-family: sans-serif;" class="paragraph">
                 <a  href="'.$link.'" style="color: rgb(237,125,49);  font-size: 20px; font-weight: 500; font-family: arial;">Нажмите здесь, чтобы проверить ваше имя пользователя теперь.</span>
        </td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Будьте уверены, чтобы следовать за нами ниже socials, так что вы всегда в курсе на qodedoo в последних обновлений от наших сторонников, события, платформа обновления и выпуски. Большое спасибо от команды qodedoo и видеть вас на бета запуска!
</td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
            padding-top: 40px;
            padding-bottom: 30px;" class="button"><a
            href="#"  style="">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle"
                    >
                    <img src="'.$mail_path.'images/logo.png" style="max-width: 100%;">

            </td></tr></table></a>
                <div style=" margin: 50px auto;">
        <div style="padding: 0 30px; display: inline-block;"><a href="https://www.facebook.com/qodedooHQ/"><img src="'.$mail_path.'images/facebook.png"></a></div>
         <div style="padding: 0 30px; display: inline-block;"><a href="https://www.instagram.com/qodedoo_hq/"><img src="'.$mail_path.'images/instagram.png"></a></div>
          <div style="padding: 0 30px; display: inline-block;"><a href="https://www.youtube.com/channel/UC_pyFmgep3yNEZ5yysXCkrA"><img src="'.$mail_path.'images/youtube.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://medium.com/qodedoo"><img src="'.$mail_path.'images/medium-hover.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://twitter.com/qodedoohq?lang=en"><img src="'.$mail_path.'images/twitter.png"></a></div>
    </div>
        </td>

    </tr>
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
            padding-top: 20px;
            padding-bottom: 20px;
            color: #000;
            font-size: 16px;
            font-weight: 600;
            font-family: sans-serif;" class="footer">

                Авторское право 2018 qodedoo ECO. Все права защищены.
        </td>
    </tr>
</table>
</td></tr></table>

</body>
</html>
';
    }
	else
	{
		$subject = 'Verify your email address';
	    $body = '<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="format-detection" content="telephone=no"/>

    <style>

body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
#outlook a { padding: 0; }
.ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
.skewed_button {
    background: #c89c3d;
    color: #000;
    text-decoration: none;
    font-size: 20px;
    display: inline-block;
    height: 30px;
    margin-left: 15px;
    padding: 6px 20px 0;
}


@media all and (min-width: 560px) {
    .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
}


a, a:hover {
    color: #127DB3;
}

.footer a, .footer a:hover {
    color: #999999;
}

    </style>


    <title>Fantasy Esports</title>

</head>


<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;

    color: #000000;"

    text="#000000">

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
    >

<table border="0" cellpadding="0" cellspacing="0" align="center"
    width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0 15px; width: inherit;
    max-width: 1280px; background: #fff; margin: 20px 0 0 0; border: 5px rgb(237,125,49) solid" class="container">

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
            padding-top: 25px;
            color: rgb(237,125,49);
            font-family: sans-serif;" class="header">
           Congratulations!
        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
            padding-top: 5px;
            color: #000000;
            font-family: sans-serif;" class="subheader">

        </td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">You’ve officially reserved your username on qodedoo; the worlds first all-purpose social media <br> platform powered by the blockchain! It won’t be long until you can start earning and spending <br> uDoo’s.</td>
    </tr>


    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 15px; font-weight: 400; line-height: 160%;
            padding-top: 25px;
            color: #fff;
            font-family: sans-serif;" class="paragraph">
                 <a  href="'.$link.'" style="color: rgb(237,125,49);  font-size: 20px; font-weight: 500; font-family: arial;">Click here to validate your username now.</span>
        </td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0 30px;
            padding-top: 20px; font-size: 22px; line-height: 30px; font-family: arial; color: #000;" class="hero">Be sure to follow us on the below socials, so you’re always in the know on qodedoo’s latest
updates from our supporters, events, platform updates and releases.<br>
Many thanks from the qodedoo team and see you at the beta Launch!
</td>
    </tr>

    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
            padding-top: 40px;
            padding-bottom: 30px;" class="button"><a
            href="#"  style="">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle"
                    >
                    <img src="'.$mail_path.'images/logo.png" style="max-width: 100%;">

            </td></tr></table></a>
                <div style=" margin: 50px auto;">
        <div style="padding: 0 30px; display: inline-block;"><a href="https://www.facebook.com/qodedooHQ/"><img src="'.$mail_path.'images/facebook.png"></a></div>
         <div style="padding: 0 30px; display: inline-block;"><a href="https://www.instagram.com/qodedoo_hq/"><img src="'.$mail_path.'images/instagram.png"></a></div>
          <div style="padding: 0 30px; display: inline-block;"><a href="https://www.youtube.com/channel/UC_pyFmgep3yNEZ5yysXCkrA"><img src="'.$mail_path.'images/youtube.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://medium.com/qodedoo"><img src="'.$mail_path.'images/medium-hover.png"></a></div>
           <div style="padding: 0 30px; display: inline-block;"><a href="https://twitter.com/qodedoohq?lang=en"><img src="'.$mail_path.'images/twitter.png"></a></div>
    </div>
        </td>

    </tr>
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
            padding-top: 20px;
            padding-bottom: 20px;
            color: #000;
            font-size: 16px;
            font-weight: 600;
            font-family: sans-serif;" class="footer">

                Copyright 2018 qodedoo ECO. All Rights Reserved.
        </td>
    </tr>
</table>
</td></tr></table>

</body>
</html>
';
	}


   	add_filter( 'wp_mail_content_type', function( $content_type ) {return 'text/html';});
 
   	/*$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
   	$headers .= 'From: '.get_bloginfo('name').' <info@qodedoo.website>'."\r\n";
	mail($user_email,mb_convert_encoding($subject,'UTF-8'),mb_convert_encoding($body,'UTF-8'),$headers);*/
	

	/*include_once 'mandrill/Mandrill.php';

	$mandrill = new Mandrill(); */

	// If are not using environment variables to specific your API key, use:
 	//$mandrill = new Mandrill("16RXlVEYZIr4nCKlZu9Q_Q")

	/*$message = array(
	    'subject' => mb_convert_encoding($subject,'UTF-8'),
	    'from_email' => 'info@qodedoo.io',
	    'html' => mb_convert_encoding($body,'UTF-8'),
	    'to' => array(array('email' => $user_email))
	);
	var_dump($mandrill->messages->send("16RXlVEYZIr4nCKlZu9Q_Q", $message));*/


	include_once 'mandrill/src/Mandrill.php';

    $mandrill = new Mandrill("16RXlVEYZIr4nCKlZu9Q_Q");

    // If are not using environment variables to specific your API key, use:
    //$mandrill = new Mandrill("16RXlVEYZIr4nCKlZu9Q_Q")

    $message = array(
        'subject' => mb_convert_encoding($subject,'UTF-8'),
	    'from_email' => 'info@qodedoo.io',
	    'html' => mb_convert_encoding($body,'UTF-8'),
	    'to' => array(array('email' => $user_email))
    );
    $mandrill->messages->send($message);

	}
 
	/**
	 * Authorize page url
	 * This is a regular wordpress page that contains the [dw-verify-email] shortcode
	 */
	public function authorize_page_url(){
		return apply_filters( 'dw_verify_authorize_page_url', get_permalink( $this->authorize_page_id() ) );
	}

	/**
	 * Authorize page ID
	 * This is a regular wordpress page that contains the [dw-verify-email] shortcode
	 */
	public function authorize_page_id(){
		return get_option('dw_verify_authorize_page');
	}

	/**
	 * Does user needs email validation?
	 */
	public function needs_validation( $user_id ){
		return ( get_user_meta( $user_id, 'verify-lock', true ) ) ? true : false ;
	}

	/**
	 * Validate hash
	 */
	public function hash_valid(){
		if( empty( $_GET['verify_email'] ) || empty( $_GET['user_id'] ) || ! preg_match( '/^[a-f0-9]{32}$/', $_GET['verify_email'] ) ) return;

		$user_id = absint( $_GET['user_id'] );

		// user already verified
		if( ! $this->needs_validation( $user_id ) ) {
			return;
		}

		$hash =  $_GET['verify_email'];

		if( $hash === get_user_meta( $user_id, 'verify-lock', true ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Verify user's email
	 */
	public function verify_if_valid( $signon = false ){
		if( ! $this->hash_valid() ) return;

		$user_id = absint( $_GET['user_id'] );
		$user = get_user_by('id', $user_id);

		// Unlock user from loggin in
		$this->unlock_user( $user_id );
		
		do_action('add_to_rails',$user_id);

		if( get_option('dw_verify_autologin') ) {
			wp_clear_auth_cookie();
		    wp_set_current_user ( $user->ID );
		    wp_set_auth_cookie  ( $user->ID );
		}

		return true;
	}

	/**
	 * Redirect after verification
	 */
	public function redirect_url(){
		return ( $red_page = get_option('dw_verify_redirect_page') ) ?
			apply_filters( 'dw_verify_redirect_url', get_permalink( $red_page ) ) :
			apply_filters( 'dw_verify_redirect_url', home_url() );

	}

	/**
	 * Return the plugin's path
	 */
	public function path(){
		return plugin_dir_path( __FILE__ );
	}

	/**
	 * Return the plugin's url
	 */
	public function url(){
		return plugins_url( '', __FILE__ ) . '/';
	}
}

new DWEmailVerify();

// hook plugin activated!
register_activation_hook( __FILE__, array( 'DWEmailVerify', 'plugin_activated' ) );


// functions
/**
 * Var_dump pre-ed!
 * For debugging purposes
 *
 * @param mixed $val desired variable to var_dump
 * @uses var_dump
 *
 * @return string
*/
if( !function_exists('dumpit') ) {
	function dumpit( $val ) {
		echo '<pre style="direction:ltr;text-align:left;">';
		var_dump( $val );
		echo '</pre>';
	}
}
