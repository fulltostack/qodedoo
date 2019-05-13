<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>
      <?php 
        if(is_home())
        {
          echo get_bloginfo('site_name');
        }
        else
        {
          echo get_the_title().' | '.get_bloginfo('site_name');
        }
      ?>
    </title>

    <!-- Bootstrap -->
    <link href="<?php echo get_template_directory_uri() ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri() ?>/assets/css/style.css" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri() ?>/assets/css/custom.css" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri() ?>/assets/css/blog.css" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri() ?>/assets/css/responsive.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php wp_head() ?>
    <script type="text/javascript">
      var is_front_page = '<?php echo (is_front_page()) ? 1 : 0 ; ?>';
    </script>
  </head>
  <body>
      <div class="top-strip"> </div>
      <div class="bottom-strip"> </div>
      <div class="left-strip"> </div>
      <div class="right-strip"> </div>
     <div class="wrapper">
       <section class="navigation">
        <?php get_sidebar() ?>
      </section>