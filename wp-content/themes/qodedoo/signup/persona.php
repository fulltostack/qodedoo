<span style="display: none;" class="signup-steps" id="step2">
   <div class="mt50">
      <h3 class="">  Create your persona </h3>
      <p class="mt10 text-gray font-xsmall"> Your persona is your online identity used to post, broadcast and connect with people. </p>
   </div>
   <div class="mt50">
         <div class="form-relative" >
         <input id="persona" placeholder="Persona Name" type="text" class="full-width-input error-msg">
         <div class="error-icon error-cancel" style="display: none;"> <img src="<?php echo get_template_directory_uri() ?>/images/cancel.png"> </div>
         <div class="error-icon error-verified" style="display: none;"> <img src="<?php echo get_template_directory_uri() ?>/images/verified.png"> </div>
         <span id="showPersonaErrorMsg" style="display: none;">Please Fill your Input</span>
         </div>
   </div>
   <div class="mt40">
      <p class="text-gray font-xsmall"> Limit - 30 symbols. user must contains only letter, number, periods and underscores </p>
   </div>
   <div class="mt40">
      <div class="container-fluid" >
         <div class="col-fluid-12 text-right">
            <a onclick="showSignupStep('step1')" class="btn-back" > <img src="<?php echo get_template_directory_uri() ?>/images/arrow-back.png"> </a>
            <input onclick="showSignupStep('step3')" id="personaNext" disabled="" class="btn-next" type="button" value="Next">
         </div>
      </div>
   </div>
</span>