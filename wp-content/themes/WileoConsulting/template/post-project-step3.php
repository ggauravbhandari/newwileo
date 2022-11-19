<?php
   global $user_ID;
   $step = 3;
   $class_active = '';
   $is_post_free = is_post_project_free();
   if( $is_post_free ) {
       $step--;
       $class_active = 'active';
   }
   if($user_ID) $step--;
   $post = '';
   $current_skills = '';
   
   ?>
<!-- jQuery library -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<style type="text/css">
   .fre-account-info.dropdown-toggle::after {
   display: none!important;
   }
   .fre-account .dropdown-menu {
   font-size: 14px!important
   }
   select.fre-chosen-single{
   opacity: 1!important;
   border: 1px solid #BFC2BA !important;
   border-radius: 8px !important;
   }
   select.fre-chosen-skill{
   opacity: 1!important;
   }
   .fade {
   opacity: 1;
   }   
   .step-post-project>h2 {
   margin-top: 20px ;
   }
   ul.button-main-tabs li a {
   background-color: #465250;
   border-color: snow;
   color: white;
   font-size: 14px;
   font-weight: 700;
   color: #FFFFFF;
   padding: 10px 25px;
   border-radius: 4px;
   min-width: 190px;
   border: 0px;
   margin-right: 20px;
   }
   ul.button-main-tabs li a.active {
   background-color: #88c050;
   border-color: snow;
   color: white;
   font-size: 14px;
   font-weight: 700;
   color: #FFFFFF;
   padding: 10px 25px;
   border-radius: 4px;
   min-width: 190px;
   border: 0px;
   margin-right: 20px;
   }
   nav {
   /*background-color: #5dba9d;*/
   width: 100%;
   display: block;
   /*float: left;*/
   padding: 10px;
   }
   ul.button-main-tabs {
   display: flex;
   justify-content: center;
   }
   ul {
   list-style: none;
   padding: 0;
   margin: 0;
   display:block;
   }
   .ToggleFlyout {
   /*background-color: #F2F2F2;*/
   padding-top: 50px;
   /*width: 250px;*/
   /*position: absolute;*/
   top: 35px;
   left: 0;
   }
   .ToggleFlyout:before{
   content: "";
   width: 20px;
   height: 20px;
   display: block;   
   background-color: #F2F2F2;
   position: absolute;
   top: -10px;
   left: 10px;
   -ms-transform: rotate(45deg);
   -webkit-transform: rotate(45deg);
   transform: rotate(45deg);
   }
   .errorCheckbox .fa-exclamation-triangle {
    display: none !important;
   }
</style>
<div id="fre-post-project-2 step-post" class="fre-post-project-step step-wrapper step-post <?php echo $class_active;?> template\post-project-step3.php">
   <?php
      $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
         if( $id ) {
             $post = get_post($id);
             if($post) {
                 global $ae_post_factory;
                 $post_object = $ae_post_factory->get($post->post_type);
                 $post_convert = $post_object->convert($post);
                 echo '<script type="data/json"  id="edit_postdata">'. json_encode($post_convert) .'</script>';
             }
             //get skills
             $current_skills = get_the_terms( $_REQUEST['id'], 'skill' );
         }
         if( ! is_acti_fre_membership() ){
             if( !$is_post_free ) {
                 $total_package = ae_user_get_total_package($user_ID); ?>
   <div class="fre-post-project-box">
      <div class="step-change-package show_select_package">
         <p class="package_title"><i class="fa fa-plus primary-color" aria-hidden="true"></i>&nbsp;<?php _e('You are selecting the package:', ET_DOMAIN);?> <strong></strong></p>
         <p class="package_description pdl-10"></p>
         <p class="pdl-10"><?php _e('The number of posts included in this package will be added to your total posts after this project is posted.',ET_DOMAIN) ?></p>
         <br>
         <?php // printf(__('1The premium package you purchased has <span class="post-number">%s</span> post(s) left', ET_DOMAIN), $total_package); ?>
         </p>
         <?php
            ob_start();
            ae_user_package_info($user_ID);
            $package = ob_get_clean();
            
            if($package != '') { ?>
         <p><i class="fa fa-check primary-color" aria-hidden="true"></i>&nbsp;<?php _e('Your purchased package details.',ET_DOMAIN);?></p>
         <p><?php
            echo $package;
            }
            ?>
            <a class="fre-btn-o fre-post-project-previous-btn fre-btn-previous primary-color" href="#"><?php _e('Change package', ET_DOMAIN);?></a>
      </div>
      <div class="step-change-package show_had_package" style="display:none;">
         <?php //printf(__('2The premium package you purchased has <span class="post-number">%s</span> post(s) left.', ET_DOMAIN), $total_package); ?>
         </p>
         <?php
            if($package != '') { ?>
         <p><i class="fa fa-check primary-color" aria-hidden="true"></i>&nbsp;<?php _e('Your purchased package details.',ET_DOMAIN);?></p>
         <p>
            <?php
               echo $package;
               }
               ?>
         <p><em><?php _e('You are choosing a package that still available to post or pending so can not buy again. If you want to get more posts, you can directly move on the posting project plan by clicking the next "Add more" button.', ET_DOMAIN);?></em></p>
         <a class="fre-btn-o fre-post-project-previous-btn fre-btn-previous" href="#"><?php _e('Add more', ET_DOMAIN);?></a>
      </div>
   </div>
   <?php } } else { do_action('fre_above_post_project'); } ?>
   <div class="fre-post-project-box">

    <?php 

    //$chek = wp_set_post_categories('5353', array(734), $append = true);
   /*$get_res = get_post_meta(5374,'et_commite_min_hour');

   echo'<pre>ooooooooo = ';
   print_r($get_res);
   echo'</pre>';  */

    $args = array(
    'hide_empty' => false, // also retrieve terms which are not used yet
    'meta_query' => array(
        array(
           'key'       => 'user_status_cs',
           'value'     => 'Approved',
        )
    ),
    'taxonomy'  => 'project_type',
    );
    $terms = get_terms( $args );
    if(!empty($terms)){
    ?>
        <nav>
            <ul class="button-main-tabs">
                <?php 
                foreach ($terms as $key => $ter_val) {
                ?>
                <li >
               <a class="link<?php echo $key+1; ?> get_project_type" get_section="sec_<?php echo $key+1; ?>" get-taxonomy="<?php echo $ter_val->taxonomy; ?>" get_term_id="<?php echo $ter_val->term_id; ?>" href="javascript:void(0)"><?php echo $ter_val->name; ?></a>
            </li>
                <?php
                }

                ?>
            </ul>
        </nav>
    <?php
    }
    ?>
         
      <div class="link1Div ToggleFlyout">
         <form class="post mian1 " style=""  role="form" >
            <div class="step-post-project" id="fre-post-project">
               <h2><?php _e('Your Project Details', ET_DOMAIN);?></h2>
               <div class="fre-input-field">
                  <label class="fre-field-title" for="project_category"><?php _e('What categories do your project work in?', ET_DOMAIN);?></label>
                  <?php
                     $cate_arr = array();
                     if(!empty($post_convert->tax_input['project_category'])){
                         foreach ($post_convert->tax_input['project_category'] as $key => $value) {
                             $cate_arr[] = $value->term_id;
                         };
                     }
                     ae_tax_dropdown( 'project_category' ,
                       array(  'attr' => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="'.sprintf(__("Choose maximum %s categories", ET_DOMAIN), ae_get_option('max_cat', 5)).'"',
                               'class' => 'fre-chosen-category',
                               //'class' => 'fre-chosen-
                               'hide_empty' => false,
                               'hierarchical' => true ,
                               'id' => 'project_category' ,
                               'show_option_all' => false,
                               'selected'        => $cate_arr,
                               'name' => 'project_category[]',
                           )
                     );
                     ?>
               </div>
               
               <div class="fre-input-field">
                  <label class="fre-field-title" for="fre-project-title"><?php _e('Your project title', ET_DOMAIN);?></label>
                  <input class="input-item text-field" id="fre-project-title" type="text" name="post_title">
               </div>
               <div class="fre-input-field">
                  <label class="fre-field-title" for="fre-project-describe"><?php _e('Describe what you need done', ET_DOMAIN);?></label>
                  <?php wp_editor( '', 'post_content', ae_editor_settings() );  ?>
               </div>
               <div class="fre-input-field" id="gallery_place">
                  <label class="fre-field-title" for=""><?php _e('Attachments (optional)', ET_DOMAIN);?></label>
                  <div class="edit-gallery-image" id="gallery_container">
                     <ul class="fre-attached-list gallery-image carousel-list" id="image-list"></ul>
                     <div  id="carousel_container">
                        <a href="javascript:void(0)" style="display: block"
                           class="img-gallery fre-project-upload-file secondary-color" id="carousel_browse_button">
                        <?php _e("Upload Files", ET_DOMAIN); ?>
                        </a>
                        <span class="et_ajaxnonce hidden" id="<?php echo wp_create_nonce( 'ad_carousels_et_uploader' ); ?>"></span>
                     </div>
                     <p class="fre-allow-upload"><?php _e('(Upload maximum 5 files with extensions including png, jpg, pdf, xls, and doc format)', ET_DOMAIN);?></p>
                  </div>
               </div>

               <div class="fre-input-field exp_commitex_min_max_hour" id="">
                  <label class="fre-field-title" for="">Expected commitment in hours per week</label>
                 
                  <div >
                     <div class="row justify-between-between">
                        <div class="col-sm-4">
                           <div class="form-group row align-items-center fre-input-field">
                              <label  class="col-sm-3 col-form-label">Min</label>
                              <div class="col-sm-8">
                                <input type="text" name="et_commite_min_hour" value="" class="form-control input-item text-field commite_min_hour" placeholder="Min hour">
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-4">
                           <div class="form-group row align-items-center fre-input-field">
                              <label  class="col-sm-3 col-form-label">Max</label>
                              <div class="col-sm-8">
                                <input type="text" name="et_commite_max_hour" class="form-control input-item text-field commite_max_hour" value="" placeholder="Max hour" >
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="fre-input-field skill_required_section">
                  <label class="fre-field-title" for="skill"><?php _e('What skills do you require?', ET_DOMAIN);?></label>
                  <?php
                     $c_skills = array();
                     if(!empty($post_convert->tax_input['skill'])){
                         foreach ($post_convert->tax_input['skill'] as $key => $value) {
                             $c_skills[] = $value->term_id;
                         };
                     }
                     ae_tax_dropdown( 'skill' , array(  'attr' => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="'.sprintf(__("Choose maximum %s skills", ET_DOMAIN), ae_get_option('fre_max_skill', 5)).'"',
                                         'class' => ' fre-chosen-skill skill_required required',
                                         //'class' => ' fre-chosen-multi required',
                                         'hide_empty' => false,
                                         'hierarchical' => true ,
                                         'id' => 'skill' ,
                                         'show_option_all' => false,
                                         'selected' => $c_skills
                                 )
                     );
                     ?>
               </div>
               <div class="fre-input-field">
                  <label class="fre-field-title" for="project-budget"><?php _e('Your project budget', ET_DOMAIN);?></label>
                  <div class="fre-project-budget">
                     <input id="project-budget" step="5" required type="number" class="input-item text-field is_number numberVal" name="et_budget" min="1">
                     <span><?php echo fre_currency_sign(false);?></span>
                  </div>
               </div>
               <div class="fre-input-field">
                  <label class="fre-field-title" for="project-location"><?php _e('Location (optional)', ET_DOMAIN);?></label>
                  <?php
                     ae_tax_dropdown( 'country' ,array(
                             'attr'            => 'data-chosen-width="100%" data-chosen-disable-search="" data-placeholder="'.__("Choose country", ET_DOMAIN).'"',
                             'class'           => 'fre-chosen-single',
                             'hide_empty'      => false,
                             'hierarchical'    => true ,
                             'id'              => 'country',
                             'show_option_all' => __("Choose country", ET_DOMAIN)
                         )
                     );
                     ?>
               </div>

                    <input type="hidden" required name="et_t_type" value=""  class="input-item text-field t_type" >
                
                    <input type="hidden" required name="et_t_id" value=""  class="input-item text-field t_id" >
               
                    <input type="hidden" name="custom_form" value="custom_form"  class="input-item text-field custom_form" >
                    
                  
               
               <?php
                  // Add hook: add more field
                  echo '<ul class="fre-custom-field milestone_section" kkkkkkk>';
                  do_action( 'ae_submit_post_form', PROJECT, $post );
                  echo '</ul>';
                  ?>
              <div class="fre-input-field">
                  
                  <div class="fre-project-accept-terms">
                     <input id="fre-project-accept-terms" step="5" required type="checkbox" class="input-item text-field  " name="fre-project-accept-terms"> 
                     <label class="form-check-label ml-5" for="defaultCheck1">
                     I accept the  <a href="https://www.wileo.com/legal" target="_blank"> terms and condition </a> for this type of project
                     </label>
                  </div>
               </div>
               
               <div class="fre-post-project-btn">
                  <button class="fre-btn fre-post-project-next-btn primary-bg-color" type="submit"><?php _e("Submit Project", ET_DOMAIN); ?></button>
               </div>
            </div>
         </form>
      </div>
     
      
      <style type="text/css">
         nav {
         /*background-color: #5dba9d;*/
         width: 100%;
         display: block;
         /*float: left;*/
         padding: 10px;
         }

         .js .tmce-active .wp-editor-area {
            color: #415161;
         }
         
         
         ul.button-main-tabs {
    display: flex;
    justify-content: center;
}
         ul {
         list-style: none;
         padding: 0;
         margin: 0;
         display:block;
         }
 
         .ToggleFlyout {
         /*background-color: #F2F2F2;*/
         padding-top: 50px;
         /*width: 250px;*/
         /*position: absolute;*/
         top: 35px;
         left: 0;
         }
         .ToggleFlyout:before{
         content: "";
         width: 20px;
         height: 20px;
         display: block;   
         background-color: #F2F2F2;
         position: absolute;
         top: -10px;
         left: 10px;
         -ms-transform: rotate(45deg);
         -webkit-transform: rotate(45deg);
         transform: rotate(45deg);
         }
      </style>
      <script type="text/javascript">
         $('.link1Div, .link2Div, .link3Div').hide();
         $('.link1').click(function(e){
          e.stopPropagation();
         
          //$('.link1Div').fadeToggle();
         //$('.link2Div,.link3Div').slideUp();
         $('.link1Div').show();
         $('.link1').addClass('active');
         $('.link2, .link3').removeClass('active');

         });
         $('.link2').click(function(e){
            $('.link1Div').show();
            e.stopPropagation();
            //$('.link1Div').fadeToggle();
            $('.link2').addClass('active');
            $('.link1, .link3').removeClass('active');

         });
         $('.link3').click(function(e){
          e.stopPropagation();
            $('.link1Div').show();
            $('.link3').addClass('active');
            $('.link1, .link2').removeClass('active');
         });
         
 
      </script>
      <div class="text-center mt-5">
         <a data-toggle="modal" data-target="#help_popup">
         <u>Help</u> 
         </a>
      </div>
   </div>
   <div class="modal" id="help_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Wileo Payments</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body mx-4 my-3">
               <h2>Payments on Wileo Platform</h2>
               <div class="my-5">
                  <h4>Escrow:-</h4>
                  <p>Escrow is for small projects / tasks of value less than $3000AUD. Each can be splitted in different milestones to be approved by the client. The client pays upfront for the expected value of the project via credit card to an escrow account managed by our partner Stripe. Once the project is completed, the client can release the money to the consultant account. If a dispute occur, Wileo will do its best to act as independant party and resolve in favor of a party following investigation. </p>
               </div>
               <div class="mb-5">
                  <h4>Time based:-</h4>
                  <p>Time based arrangement is using a standard and local Professional Services contract between the client and Wileo. The consultant will fill in detailed hours in Wileo timesheet system which will need to be processed by the client on a weekly basis. The client can approve or reject the timesheet (with a comment). In case of rejection, the consultant can re-submit or escalate to Wileo team.
                     All the approved timesheet will be billed to the client on a regular basis as per the executed Professional Services contract.
                     . 
                  </p>
               </div>
               <div class="mb-5">
                  <h4>Projects/Panels:-</h4>
                  <p>Projects/Panels is a contract with local terms and conditions between the client and Wileo. These projects have to be negotiated locally between the client and Wileo.</p>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="modal" id="termsPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModal1Label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Wileo T&C</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body mx-4 my-3">
               <h2 class="text-center">Wileo Terms and conditions</h2>
               <div class="my-5">
                  <p>Wileo Terms and conditions
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas porttitor congue massa. Fusce posuere, magna sed pulvinar ultricies, purus lectus malesuada libero, sit amet commodo magna eros quis urna. Nunc viverra imperdiet enim. Fusce est.
                     Vivamus a tellus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin pharetra nonummy pede. Mauris et orci. Aenean nec lorem.
                     In porttitor. Donec laoreet nonummy augue. Suspendisse dui purus, scelerisque at, vulputate vitae, pretium mattis, nunc. Mauris eget neque at sem venenatis eleifend. Ut nonummy.
                     Fusce aliquet pede non pede. Suspendisse dapibus lorem pellentesque magna. Integer nulla. Donec blandit feugiat ligula. Donec hendrerit, felis et imperdiet euismod, purus ipsum pretium metus, in lacinia nulla nisl eget sapien.
                     Donec ut est in lectus consequat consequat. Etiam eget dui. Aliquam erat volutpat. Sed at lorem in nunc porta tristique. Proin nec augue.
                     Quisque aliquam tempor magna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc ac magna. Maecenas odio dolor, vulputate vel, auctor ac, accumsan id, felis. Pellentesque cursus sagittis felis.
                     Pellentesque porttitor, velit lacinia egestas auctor, diam eros tempus arcu, nec vulputate augue magna vel risus. Cras non magna vel ante adipiscing rhoncus. Vivamus a mi. Morbi neque. Aliquam erat volutpat.
                      
                  </p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>



<!-- Step 3 / End -->

<script type="text/javascript">
    $(function(){
        $('.get_project_type').click(function(){
           // $('.link3Div')
            var t_type =  $(this).attr('get-taxonomy');
            var t_id =  $(this).attr('get_term_id');
            var get_sec = $(this).attr('get_section');
            $('.link1Div .t_type').val(t_type);
            $('.link1Div .t_id').val(t_id);
            if(get_sec == 'sec_1'){
               $('.exp_commitex_min_max_hour').addClass('hide');
               $('.exp_commitex_min_max_hour').removeClass('show');
               $('.commite_min_hour').attr('required', false);
               $('.commite_max_hour').attr('required', false);
               $('.commite_min_hour').val('');
               $('.commite_max_hour').val('');

               $('.milestone_section').addClass('show');
               $('.milestone_section').removeClass('hide');

            }else if(get_sec == 'sec_3'){
               $('.exp_commitex_min_max_hour').addClass('show');
               $('.exp_commitex_min_max_hour').removeClass('hide');
               $('.commite_min_hour').attr('required', true);
               $('.commite_max_hour').attr('required', true);

               $('.milestone_section').addClass('show');
               $('.milestone_section').removeClass('hide');

            }else if(get_sec == 'sec_2'){
               
               $('.exp_commitex_min_max_hour').addClass('hide');
               $('.exp_commitex_min_max_hour').removeClass('show');
               $('.commite_min_hour').attr('required', false);
               $('.commite_max_hour').attr('required', false);
               $('.commite_min_hour').val('');
               $('.commite_max_hour').val('');

               $('.milestone-item-wrapper').remove();
               $('#milestone-input, .txt-milestone-item').val('');

               $('.milestone_section').addClass('hide');
               $('.milestone_section').removeClass('show');
               

               

            }

        })
    })
</script>
