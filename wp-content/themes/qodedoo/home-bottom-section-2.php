<div class="col-xs-12 for_mob text-center hidden-lg hidden-md hidden-sm">  <img class="img-responsive" src="<?php echo get_template_directory_uri() ?>/assets/images/mobile_xs.png">
</div>
<div class="container-fluid home-mobile-thumb"> 
  <div class="six_big_title animated fadeIn duration1 eds-on-scroll "> <?php echo get_option('hs2-title') ?></div>
  <div class="six_content_row row">
      <div class="col-sm-5"> 
          <div data-bottom-top="transform:translateY(-10%);" data-top-bottom="transform:translateY(15%);"  class="application-image iphone-img six_thumb hidden-xs" style="background-image:url(<?php echo get_option('hs2-image') ?>);background-repeat:no-repeat;background-position:top center;"> 
            <!-- <img src="<?php echo get_option('hs2-image') ?>"> -->
          </div>
      </div>
      <div class="col-md-5 col-sm-7"> 
         <div class="six_content">
            <?php echo stripslashes(get_option('hs2-content')) ?> 
           <div class="btn-box"> 
                 <button onclick="window.top.location='<?php echo get_permalink(19) ?>'" class="btn_lg btn_fill"> Register for Early Access </button>
             </div> 
         </div>
      </div>
  </div>
</div>