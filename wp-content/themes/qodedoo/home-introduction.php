<div class="slide_content">
  <div class="container-fluid"> 
       <div class="row">
           <div class="col-sm-10 black-zone">
             <div style="overflow:hidden">
               <div class="intro-container" style="transform: translateY(0px); transition: transform 1s;">
                 <div class="ic_lg">
                    <img src="<?php echo get_template_directory_uri() ?>/assets/images/icon-logo-mid.png"> 
                 </div>
                 <p class=""> 
                   <?php echo stripslashes(get_option('hb-description')) ?>
                 </p>  

                 <p class="wt_txt hidden-xs"><?php echo get_option('hb-tagline') ?> </p> 

                 <div class="btn-box"> 
                     <button onclick="window.top.location='<?php echo get_permalink(19) ?>'" class="btn_lg btn_transperant"> Register for Early Access </button>
                 </div> 
                  <p class="wt_txt hidden-lg hidden-md hidden-sm"><?php echo get_option('hb-tagline') ?> </p> 
               </div>
             </div>
             <div class="dots_bg"> 
              <img class="hidden-xs" src="<?php echo get_template_directory_uri() ?>/assets/images/dots_bg.png"> 
             <img class="hidden-lg hidden-md hidden-sm" src="<?php echo get_template_directory_uri() ?>/assets/images/sec_dot_mob.png"> </div> 
          </div>
       </div>
  </div> 
</div>