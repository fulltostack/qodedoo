<span style="display: none;" class="signup-steps" id="step3">
   <div class="mt50">
      <h3 class="">  Create a password </h3>
   </div>
   <div class="mt50">
         <div class="form-relative" >
         <input id="password" placeholder="Password" type="password" class="full-width-input error-msg">
         <div class="error-icon error-cancel" style="display: none;"> <img src="<?php echo get_template_directory_uri() ?>/images/cancel.png"> </div>
         <div class="error-icon error-verified" style="display: none;"> <img src="<?php echo get_template_directory_uri() ?>/images/verified.png"> </div>
         <span id="showPasswordErrorMsg" style="display: none;">Please Fill your Input</span>
         </div>
   </div>
   <div class="mt40">
      <p class="text-gray font-xsmall"> Must be 8 characters & 1 special character </p>
   </div>
   <div class="mt40">
      <div class="container-fluid" >
         <div class="col-fluid-12 text-right">
            <a onclick="showSignupStep('step2')" class="btn-back" > <img src="<?php echo get_template_directory_uri() ?>/images/arrow-back.png"> </a>
            <input onclick="showSignupStep('step4')" id="passwordNext" disabled="" class="btn-next" type="button" value="Next">
         </div>
      </div>
   </div>
</span>