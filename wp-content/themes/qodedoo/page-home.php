<?php
/*
Template Name: Home page
Template Post Type: page
*/
?>
<?php get_header() ?>

      <div class="inner-wrapper">
        <section class="first-slide">
        <?php include 'home-banner.php' ?>
        </section>
        <section id="second" class="second-slide">
           <?php include 'home-introduction.php' ?>
        </section>
        <section class="third-slide animated zoomIn duration1 eds-on-scroll">
        <?php include 'slider.php' ?>
        </section>
        <section class="forth-slide">
        <?php include 'services.php' ?>
        </section>
        <section class="fifth-slide">
        <?php include 'home-bottom-section-1.php' ?>
        </section>
        <section class="six-slide">
        <?php include 'home-bottom-section-2.php' ?>
        </section>
        <section class="seven-slide">
        <?php include 'home-bottom-section-3.php' ?>
        </section>

<?php get_footer() ?>