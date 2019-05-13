               <?php if(!is_page(19) && !is_page(9)): ?>
               <!-- <div class="mt40">
                  <h4 class=""> Already have an account?<a class="orange-color login-link" href="<?php echo get_permalink(9) ?>"> Login </a> </h4>
               </div> -->
               <?php endif; ?>
               <!-- <div class="social-section text-center mt50 ">
                  <a href="<?php echo get_option('ios-application') ?>"> <img src="<?php echo get_template_directory_uri() ?>/images/appstore.png"> </a>
                  <a href="<?php echo get_option('android-application') ?>"> <img src="<?php echo get_template_directory_uri() ?>/images/googleplay.png"> </a>
               </div> -->
               <p class="mt20 text-center" > <?php echo get_option('terms-and-policy-message') ?></p>
         </div>
      </div>
      <?php wp_footer(); ?>
   </body>
</html>