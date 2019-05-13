<?php
/*
Template Name: Signup page
Template Post Type: page
*/
?>
<?php get_header('signup') ?>
<div class="login-form text-left">
   <h1 class="login-content mt50" > <?php echo get_option('signup-title') ?><span class="orange-color" >.</span></h1>
   <h3 class="orange-color"> <?php echo get_option('signup-label') ?> </h3>
   <?php require 'signup/username.php' ?>
   <?php require 'signup/persona.php' ?>
   <?php require 'signup/password.php' ?>
   <?php require 'signup/birthday.php' ?>
   <?php require 'signup/email.php' ?>
   <?php require 'signup/mobile.php' ?>
   <?php require 'signup/photo.php' ?>
</div>
<?php get_footer('signup') ?>