<span style="display: none;" class="signup-steps" id="step7">
   <div class="mt100">
      <div class="container-fluid">
         <div class="col-fluid-4">
            <div class="uplode-profile">
              <input type="file" name="my_file_upload" id="my_file_upload" placeholder="" onchange="imageChange(this.files[0])">
           </div>
         </div>
         <div class="col-fluid-1"></div>
         <div class="col-fluid-7 text-left">
            <p class="mt10" id="usernameValue"></p>
            <p class="mt10" id="personaValue"></p>
            <p class="mt10" id="emailValue"></p>
         </div>
      </div>
   </div>
   <div class="mt40">
      <div class="container-fluid" >
         <div class="col-fluid-12 text-right">
            <a onclick="showSignupStep('step6')" class="btn-back" > <img src="<?php echo get_template_directory_uri() ?>/images/arrow-back.png"> </a>
         </div>
      </div>
   </div>
   <div class="mt50">
      <button class="signup-btn mt30 " onclick="register_user()"  data-toggle="modal" data-target=".signup-popup"> Active </button>
   </div>
</span>