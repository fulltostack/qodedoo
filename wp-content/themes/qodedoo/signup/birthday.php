<span style="display: none;" class="signup-steps" id="step4">
   <div class="mt50">
      <h3 class="">  What’s your birthdate? </h3>
   </div>
   <div class="mt50">
         <div class="form-relative calender-wrapper-field">
         <input id="userDate" placeholder="dd/mm/yyyy" type="date" class="full-width-input error-msg">
         <div class="error-icon error-cancel" style="display: none;"> <img src="<?php echo get_template_directory_uri() ?>/images/cancel.png"> </div>
         <div class="error-icon error-verified" style="display: none;"> <img src="<?php echo get_template_directory_uri() ?>/images/verified.png"> </div>
         <span id="showDateErrorMsg" style="display: none;">Please Fill your Input</span>
         </div>
   </div>
   <div class="mt40">
      <p class="text-gray font-xsmall"> By sharing your DOB with us, we well be able to give you the best experience for your age. </p>
   </div>
   <div class="mt40">
      <div class="container-fluid" >
         <div class="col-fluid-12 text-right">
            <a onclick="showSignupStep('step3')" class="btn-back" > <img src="<?php echo get_template_directory_uri() ?>/images/arrow-back.png"> </a>
            <input onclick="showSignupStep('step5')" id="dateNext" disabled="" class="btn-next" type="button" value="Next">
         </div>
      </div>
   </div>
</span>