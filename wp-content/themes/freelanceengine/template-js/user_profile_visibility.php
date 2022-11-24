<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #88c050;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.profile-setting-profile-visibility-title{
  padding: 20px 35px;
  font-size: 24px;
}
</style>

<div class="modal fade" id="modal_change_pass">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
         
        </button>
      </div>
      <?php 
      if ( is_user_logged_in() && $user_role == "freelancer") { ?>
        <h2 class="profile-setting-profile-visibility-title">Profile Visibility</h2>
        <hr>
      <?php 
      } 
      ?>
        
      <div class="modal-body">
        <?php 
        $user_profile_visi = '';
        $user_role   = ae_user_role( $user_ID );
        if ( is_user_logged_in() && $user_role == "freelancer") {
          global  $ae_post_factory, $user_ID;

          
          if($user_ID){
            $user_profile_visi = get_user_meta($user_ID,'user_profile_visibility', true);
            $user_profile_visi = !empty($user_profile_visi) && $user_profile_visi == 'yes' ? "checked" : "";
          }
           

        ?>
        <form role="form" id="profile_visible_action" class="fre-modal-form auth-form profile_visible ">
          <div class="fre-input-field">
            
            <label class="switch">
              <input type="checkbox" <?php echo $user_profile_visi; ?> class="check" id="profile_visible">
              <span class="slider round"></span>
            </label>
            
          </div>
          <?php
          if($user_ID){
            $user_profile_visi = get_user_meta($user_ID,'user_profile_visibility', true);
            
            ?>
            <div class="fre-input-field">
              <?php 
              if($user_profile_visi == 'yes' || $user_profile_visi == ''){
                ?>
                
                <p class="visible_invi__content"><?php _e('Make my profile invisible for other users', ET_DOMAIN) ?></p>
                <?php
              }else{
                ?>
                <p class="visible_invi__content"><?php _e('Your profile is now invisible for clients on the platform. Please note that your profile may still be used for marketing or biding purpose as per Terms and Condition. If you wish to delete your profile. please contact xxx@wileo.com', ET_DOMAIN) ?></p>
                <?php
              }
              ?>
              
            </div>
          <?php
          }

          ?>
          
          <div class="fre-form-btn text-right">
            <button type="submit" class="fre-normal-btn btn-submit">
              <?php _e('Save', ET_DOMAIN) ?>
            </button>
           
          </div>
        </form>
        <?php } ?>
       
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
