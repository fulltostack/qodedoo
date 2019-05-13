<!-- <div class="container-fluid"> 
  <div class="dots animated slideInDown duration1 eds-on-scroll"> <img src="<?php echo get_template_directory_uri() ?>/assets/images/Dots.png"> </div>
   <div class="center-box animated slideInUp duration1 eds-on-scroll">
       <div class="fair_rev">  <?php echo get_option('hs1-title') ?></div>
  <div class="air_rev_list animated slideInBottom duration1 eds-on-scroll"> 

        <p class="list_orng"> <?php echo get_option('hs1-subtitle') ?></p>

        <span class="animated fadeIn duration1 eds-on-scroll ">
          <?php echo get_option('hs1-content') ?>
        </span>

        <div class="btn-box animated shake duration1 eds-on-scroll "> 
               <button onclick="window.top.location='<?php echo get_permalink(19) ?>'" class="btn btn_lg btn_transperant"> Register for Early Access </button>
           </div> 
  </div>
   </div>
</div> -->



<div class="container-fluid"> 
      <div class="dots"> <img src="<?php echo get_template_directory_uri() ?>/assets/images/Dots.png"> </div>
       <div class="center-box">
            <div class="circel-bg">
              
              <div class="fair_rev  animated slideInUp duration1 eds-on-scroll">  <?php echo get_option('hs1-title') ?></div>
              <div class="air_rev_list  animated slideInUp duration1 eds-on-scroll"> 
                     <div class="dollers">
                          <span class="animated slideInUp delay0 duration1 eds-on-scroll ">
                               <img src="<?php echo get_template_directory_uri() ?>/assets/images/$1.png"> 
                          </span>
                          <span class="animated slideInUp delay0 duration2 eds-on-scroll ">
                               <img src="<?php echo get_template_directory_uri() ?>/assets/images/$2.png"> 
                          </span>
                         <span class="animated slideInUp delay0 duration3 eds-on-scroll ">
                               <img src="<?php echo get_template_directory_uri() ?>/assets/images/$3.png"> 
                          </span>
                          <span class="animated slideInUp delay0 duration4 eds-on-scroll ">
                               <img src="<?php echo get_template_directory_uri() ?>/assets/images/$4.png"> 
                          </span>
                     </div>
                    <p class="list_orng"> <?php echo stripslashes(get_option('hs1-subtitle')) ?></p>
                    <span class="animated slideInUp duration1 eds-on-scroll">
                      <?php echo stripslashes(get_option('hs1-content')) ?>
                    </span>

                    <div class="btn-box"> 
                           <button onclick="window.top.location='<?php echo get_permalink(19) ?>'" class="btn_lg btn_transperant"> Register for Early Access </button>
                       </div> 
              </div>
            </div>
       </div>
</div>