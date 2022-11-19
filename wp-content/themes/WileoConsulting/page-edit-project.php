<?php
/**
 * Template Name: Edit Project
*/
global $user_ID;
get_header();
$post = '';
$current_skills = '';
if(isset($_REQUEST['id'])) {
    $post = get_post($_REQUEST['id']);
    $term_obj_list = wp_get_post_terms($post->ID, 'project_type',  array("fields" => "all"));  
    
    if($post) {
        global $ae_post_factory;
        $post_object = $ae_post_factory->get($post->post_type);
        $post_convert = $post_object->convert($post);
        echo '<script type="data/json"  id="edit_postdata">'. json_encode($post_convert) .'</script>';
    }
    //get skills
    $current_skills = get_the_terms( $_REQUEST['id'], 'skill' );
} 

?>
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
<div class="fre-page-wrapper">
    <div class="fre-page-title">
        <div class="container">
            <h2><?php _e('Edit the project', ET_DOMAIN);?></h2>
        </div>
    </div>
    
    

    <div class="fre-page-section">
        
        <div class="container" id="edit_project">
            <div id="fre-post-project-2 step-post" class="fre-post-project-step step-wrapper step-post active">
                <div class="fre-post-project-box">
                    
                    <b> Project Type:</b> <span class="btn btn-primary"><?php echo !empty($term_obj_list) ? $term_obj_list[0]->name : ''; ?> </span>
                    <form class="post" role="form" class="validateNumVal">
                        <div class="step-post-project" id="fre-post-project">
                            <h2><?php _e('Your Project Details', ET_DOMAIN);?></h2>
                            <div class="fre-input-field">
                                <label class="fre-field-title" for="project_category"><?php _e('What category does your project work in?', ET_DOMAIN);?></label>
                                <?php
                                    $cate_arr = array();
                                    if(!empty($post_convert->tax_input['project_category'])){
                                        foreach ($post_convert->tax_input['project_category'] as $key => $value) {
                                            $cate_arr[] = $value->term_id;
                                        };
                                    }
                                    ae_tax_dropdown( 'project_category' ,
                                      array(  'attr' => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="'.sprintf(__("Choose maximum %s categories", ET_DOMAIN), ae_get_option('max_cat', 5)).'"',
                                              'class' => 'fre-chosen-multi',
                                              'hide_empty' => false,
                                              'hierarchical' => true ,
                                              'id' => 'project_category' ,
                                              'show_option_all' => false,
                                              'selected'        => $cate_arr,
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
                                    <div class="plupload_buttons" id="carousel_container">
                                        <label class="img-gallery fre-project-upload-file" id="carousel_browse_button">
                                            <?php _e("Upload Files", ET_DOMAIN); ?>
                                        </label>
                                    </div>
                                    <p class="fre-allow-upload"><?php _e('(Upload maximum 5 files with extensions including png, jpg, pdf, xls, and doc format)', ET_DOMAIN);?></p>
                                    <span class="et_ajaxnonce" id="<?php echo wp_create_nonce( 'ad_carousels_et_uploader' ); ?>"></span>
                                </div>
                            </div>
                            <?php 
                            if(!empty($term_obj_list) && !empty($term_obj_list[0]->name) && ($term_obj_list[0]->name === "Time Based" || $term_obj_list[0]->term_id === 735  )){
                                $min_val = get_post_meta($post->ID,'et_commite_min_hour',true);
                                $max_val = get_post_meta($post->ID,'et_commite_max_hour',true);
                                
                            ?>
                                <div class="fre-input-field exp_commitex_min_max_hour" id="">
                                  <label class="fre-field-title" for="">Expected commitment in hours per week</label>
                                 
                                  <div >
                                     <div class="row justify-between-between">
                                        <div class="col-sm-4">
                                           <div class="form-group row align-items-center fre-input-field">
                                              <label  class="col-sm-3 col-form-label">Min</label>
                                              <div class="col-sm-8">
                                               <span class="commite_min_hour" commite_min_hour="<?php echo !empty($min_val) ? $min_val : '' ?>" ></span>
                                                <input type="number" name="et_commite_min_hour" class="form-control input-item et_commite_min_hour required" value="<?php echo !empty($min_val) ? $min_val : '' ?>" required id="et_commite_min_hour" placeholder="Min hour">
                                              </div>
                                           </div>
                                        </div>
                                        <div class="col-sm-4">
                                           <div class="form-group row align-items-center fre-input-field">
                                              <label  class="col-sm-3 col-form-label">Max</label>
                                              <div class="col-sm-8">
                                                <span class="commite_max_hour" commite_max_hour="<?php echo !empty($max_val) ? $max_val : '' ?>" ></span>
                                                <input type="number" name="et_commite_max_hour" class="input-item form-control et_commite_max_hour required" required value="<?php echo !empty($max_val) ? $max_val : '' ?>" id="et_commite_max_hour" placeholder="Max hour" min="1">
                                              </div>
                                           </div>
                                        </div>
                                        
                                     </div>
                                  </div>
                               </div>

                               <input type="hidden" name="custom_form" value=""  class="input-item text-field custom_form" >
                               <input type="hidden" name="et_t_id" value="5555"  class="input-item text-field t_id" >

                           <?php 
                            }
                           ?>
                           
                            <div class="fre-input-field">
                                <label class="fre-field-title" for="skill"><?php _e('What skills do you require?', ET_DOMAIN);?></label>
                                <?php
                                    ae_tax_dropdown( 'skill' , array(  'attr' => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="'.sprintf(__("Choose maximum %s skills", ET_DOMAIN), ae_get_option('fre_max_skill', 5)).'"',
                                                        'class' => 'fre-chosen-multi required',
                                                        'hide_empty' => false,
                                                        'hierarchical' => true ,
                                                        'id' => 'skill' ,
                                                        'show_option_all' => false,
                                                )
                                    );
                                ?>
                            </div>
                            
                            <div class="fre-input-field">
                                <label class="fre-field-title" for="project-budget"><?php _e('Your project budget', ET_DOMAIN);?></label>
                                <div class="fre-project-budget">
                                    <input id="project-budget" type="number" step="5" required type="number" class="input-item text-field is_number numberVal" name="et_budget" min="1">
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
                                            'show_option_all' => __("Choose country", ET_DOMAIN),
                                        )
                                    );
                                ?>
                            </div>
                            <?php 
                            if(!empty($term_obj_list) && !empty($term_obj_list[0]->name) && ($term_obj_list[0]->name == "rojects/Panels" || $term_obj_list[0]->term_id == 736 )){
                            ?>
                           
                            
                            <input type="hidden" name="custom_form" value="" class="input-item text-field proj_type_panel" >
                            <?php
                            }
                            ?>
                            <?php
                                // Add hook: add more field
                            if(!empty($term_obj_list) && !empty($term_obj_list[0]->name) && ($term_obj_list[0]->name == "Time Based" || $term_obj_list[0]->term_id == 735 || $term_obj_list[0]->name == "Escrow" || $term_obj_list[0]->term_id == 734)){
                                echo '<ul class="fre-custom-field">';
                                do_action( 'ae_submit_post_form', PROJECT, $post );
                                echo '</ul>';
                            }
                            ?>
                            <div class="fre-post-project-btn">
                                <button class="fre-btn fre-post-project-next-btn primary-bg-color" type="submit"><?php _e("Update", ET_DOMAIN); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
get_footer();
?>
<script type="text/javascript">
    jQuery(function() {
        var commite_min_hour = jQuery('.commite_min_hour').attr('commite_min_hour');
        var commite_max_hour = jQuery('.commite_max_hour').attr('commite_max_hour');
        jQuery('.et_commite_min_hour').val(commite_min_hour);
        jQuery('.et_commite_max_hour').val(commite_max_hour);
        if(commite_min_hour != '' && commite_max_hour != ''){
           jQuery('.custom_form').val('update_form'); 
        }

        jQuery('.proj_type_panel').val('update_form_project');
    });
</script>