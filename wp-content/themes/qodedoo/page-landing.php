<?php
/*
Template Name: Landing page
Template Post Type: page
*/
?>
<?php get_header('signup') ?>
<?php $redirect_to = '' ?>
<div class="login-form">
  <h1 class="login-content mt100"> Hello<span class="orange-color">.</span></h1>

  <div class="">
      <a class="signup-btn mt100" href="<?php echo get_permalink(7) ?>"> sign up </a>
      <!-- <a class="signup-btn mt30" href="<?php echo get_permalink(9) ?>"> login </a> -->
  </div>
</div>


<?php get_footer('signup') ?>