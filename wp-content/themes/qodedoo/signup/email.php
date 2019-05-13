<span style="display: none;" class="signup-steps" id="step5">
   <div class="mt50">
      <h3 class="">  Email Address </h3>
   </div>
   <div class="mt50">
         <div class="form-relative" >
         <input id="userEmail" placeholder="Email Address" type="email" class="full-width-input error-msg">
         <div class="error-icon error-cancel" style="display: none;"> <img src="<?php echo get_template_directory_uri() ?>/images/cancel.png"> </div>
         <div class="error-icon error-verified" style="display: none;"> <img src="<?php echo get_template_directory_uri() ?>/images/verified.png"> </div>
         <span id="showEmailErrorMsg" style="display: none;">Please Fill your Input</span>
         </div>
   </div>
   <div class="mt40">
      <p class="text-gray font-xsmall"> You email address will be kept confidential and will never be shared with any third parties. </p>
   </div>
   <div class="mt40">
      <div class="container-fluid" >
         <div class="col-fluid-12 text-right">
            <a onclick="showSignupStep('step4')" class="btn-back" > <img src="<?php echo get_template_directory_uri() ?>/images/arrow-back.png"> </a>
            <input onclick="showSignupStep('step6')" id="emailNext" disabled="" class="btn-next" type="button" value="Next">
         </div>
      </div>
   </div>
</span>