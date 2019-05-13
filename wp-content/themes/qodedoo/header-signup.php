<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title><?php echo get_the_title().' | '.get_bloginfo('site_name') ?></title>
      <link rel="icon" href="images/favicon.png" type="image/x-icon" />
      <!-- Bootstrap -->
      <link href="<?php echo get_template_directory_uri() ?>/css/style.css" rel="stylesheet">
      <link href="<?php echo get_template_directory_uri() ?>/fonts/font-awesome.min.css" rel="stylesheet" type="text/css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="<?php echo get_template_directory_uri() ?>/js/custom.js"></script>
      <?php wp_head(); ?>
   </head>
   <body>
      <div class="container-fluid" >
      <div class="login-signup-page">
      <div class="col-fluid-lg-8 col-fluid-md-6 bg-gray">
         <div class="auth-left-part">
         <img class="login-signup-bg" src="<?php echo get_option('login-background') ?>">
        </div>
      </div>
      <div class="col-fluid-lg-4 col-fluid-md-6">
         <div class="text-center auth-right-part">
            <a class="text-center" href="<?php echo get_permalink(19) ?>"><img class="qodedoo-logo mt100" src="<?php echo get_option('qodedoo-logo') ?>"> </a>