<?php
/*
Template Name: Login page
Template Post Type: page
*/
?>
<?php get_header('signup') ?>
<?php $redirect_to = '' ?>
<div class="login-form text-left">
   <h1 class="login-content mt50" > <?php echo get_option('login-title') ?><span class="orange-color" >.</span></h1>
   <h3 class="orange-color"> <?php echo get_option('login-label') ?> </h3>
   <div class="mt50">
      <h3> login details </h3>
   </div>
   <form name="loginform" id="loginform" action="<?php echo site_url( '/wp-login.php' ); ?>" method="post">
      <div class="mt50">
            <div class="form-relative" >
            <input id="user_login" name="log" placeholder="Email Address" type="email" class="full-width-input error-msg">
            <span>Please Fill your Input</span>
            </div>
             <div class="form-relative">
            <div class="mt40">
            <input id="user_pass" placeholder="password" type="password" class="full-width-input error-msg " name="pwd">
             <a href="JavaScript:void(0);" toggle="#password-field" class="field-icon toggle-password fa-eyes"></a>
            </button>
            </div>
         </div>
          </div>
       <div class="mt5">
         <div class="container-fluid" >
            <div class="col-fluid-6 text-left">
              <input id="rememberme" type="checkbox" value="forever" name="rememberme">
              <label class="form-label" for="rememberme"><span class="font-xsmall" >Remember me</span></label>
             </div>
            <div class="col-fluid-6 text-right">
               <a class="forgot-link font-xsmall" href="<?php echo get_permalink(11) ?>"> Forgot password ? </a>
            </div>
         </div>
      </div>
      
      <div class="mt40">
         <div class="container-fluid" >
            <div class="col-fluid-12 text-right">
               <a class="btn-back" href="javascript:history.back()" > <img src="<?php echo get_template_directory_uri() ?>/images/arrow-back.png"> </a>
               <input class="btn-next" id="wp-submit" type="submit" value="Login" name="wp-submit">
            </div>
         </div>
      </div>
   </form>
</div>


<?php get_footer('signup') ?>