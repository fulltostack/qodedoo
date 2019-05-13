<?php
/*
Template Name: Forgot password page
Template Post Type: page
*/
?>
<?php get_header('signup') ?>
<div class="login-form text-left">
 <h1 class="login-content mt50" > <?php echo get_option('forgot-title') ?><span class="orange-color" >.</span></h1>
 <h3 class="orange-color"> <?php echo get_option('fogot-label') ?> </h3>
 <div class="mt50">
    <h3 class=""> forgot your password? </h3>
 </div>
 <div class="mt50">
       <div class="form-relative" >
       <input placeholder="Email address" id="forgotEmail" type="text" class="full-width-input error-msg">
       <div class="error-icon" > <img src="<?php echo get_template_directory_uri() ?>/images/cancel.png"> </div>
       <span>Please Fill your Input</span>
        </div>
 </div>
 <div class="mt40">
    <div class="container-fluid" >
       <div class="col-fluid-12 text-right">
          <a class="btn-back"> <img src="<?php echo get_template_directory_uri() ?>/images/arrow-back.png"> </a>
          <button class="btn-next" onclick="forgot_password()"> Reset </button>
       </div>
    </div>
 </div>
</div>

<div class="modal  forgot-popup">
  <div class="modal-dialog modal-sm-as">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-body-content">
          <div class="signup-popup-active text-center">
            <img class="popup-image" src="<?php echo get_template_directory_uri() ?>/images/sent-email.png">
            <h3 class="mt20" > Email Sent </h3>
            <p class="mt20" > you will shortly receive an email with a link to reset your password.</p>
            <a class="signup-btn mt30 " href="<?php echo get_permalink(9) ?>"> Login </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php get_footer('signup') ?>