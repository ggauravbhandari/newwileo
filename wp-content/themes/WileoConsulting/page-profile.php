<?php
/**
 * Template Name: Member Profile Page
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage FreelanceEngine
 * @since FreelanceEngine 1.0
 */
global $wp_query, $ae_post_factory, $post, $current_user, $user_ID;
//convert current user
$ae_users  = AE_Users::get_instance();
$user_data = $ae_users->convert( $current_user->data );
$user_role = new_ae_user_role( $current_user->ID );
//convert current profile
$post_object = $ae_post_factory->get( PROFILE );

$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );

$profile = array();
if ( $profile_id ) {
    $profile_post = get_post( $profile_id );
    if ( $profile_post && ! is_wp_error( $profile_post ) ) {
        $profile = $post_object->convert( $profile_post );
    }
}
//test
//get profile skills
$current_skills = get_the_terms( $profile, 'skill' );
//define variables:
$skills         = isset( $profile->tax_input['skill'] ) ? $profile->tax_input['skill'] : array();
$job_title      = isset( $profile->et_professional_title ) ? $profile->et_professional_title : '';
$hour_rate      = isset( $profile->hour_rate ) ? $profile->hour_rate : '';
$currency       = isset( $profile->currency ) ? $profile->currency : '';
$experience     = isset( $profile->et_experience ) ? $profile->et_experience : '';
$hour_rate      = isset( $profile->hour_rate ) ? $profile->hour_rate : '';
$about          = isset( $profile->post_content ) ? $profile->post_content : '';
$display_name   = $user_data->display_name;
$user_available = isset( $user_data->user_available ) && $user_data->user_available == "on" ? 'checked' : '';
$country        = isset( $profile->tax_input['country'][0] ) ? $profile->tax_input['country'][0]->name : '';
$category       = isset( $profile->tax_input['project_category'][0] ) ? $profile->tax_input['project_category'][0]->slug : '';

get_header();
// Handle email change requests
$user_meta = get_user_meta( $user_ID, 'adminhash', true );

if ( ! empty( $_GET['adminhash'] ) ) {
    if ( is_array( $user_meta ) && $user_meta['hash'] == $_GET['adminhash'] && ! empty( $user_meta['newemail'] ) ) {
        $confirm_new_email = wp_update_user( array(
            'ID'         => $user_ID,
            'user_email' => $user_meta['newemail']
        ) );

        do_action('confirm_new_email', $confirm_new_email, $user_meta['newemail'] );
        delete_user_meta( $user_ID, 'adminhash' );
    }
    echo "<script> window.location.href = '" . et_get_page_link( "profile" ) . "'</script>";
} elseif ( ! empty( $_GET['dismiss'] ) && 'new_email' == $_GET['dismiss'] ) {
    delete_user_meta( $user_ID, 'adminhash' );
    echo "<script> window.location.href = '" . et_get_page_link( "profile" ) . "'</script>";
}

$rating        = Fre_Review::employer_rating_score( $user_ID );
$role_template = 'employer';
if ( fre_share_role() || new_ae_user_role( $user_ID ) == FREELANCER ) {
    $rating        = Fre_Review::freelancer_rating_score( $user_ID );
    $role_template = 'freelance';
}
if ( $user_ID ) {
	echo '<script type="data/json"  id="user_id">' . json_encode( array(
       'id' => $user_ID,
       'ID' => $user_ID
   ) ) . '</script>';
}
?>


<div class="fre-page-wrapper list-profile-wrapper">
    <div class="fre-page-title">
        <div class="container">
            <h2><?php _e( 'My Profile', ET_DOMAIN ) ?></h2>
        </div>
    </div>

    <div class="fre-page-section">
        <style>
            .free-profile-wrap .row{
                background: #fff;
            }
            .free-profile-wrap .row .col-md-4 {
                border-right: 1px solid #eee;
            }
            .free-profile-wrap .row .fre-profile-box {
                border-right: 0;
            }
            .freelance-portfolio {
                min-height: 100px;
            }
            .fre-profile-box, .author-project-list>li{
                border-bottom: 1px solid #eee;
            }
            .profile-freelance-experience form{
                width: 300px;
                margin-top: 20px;
            }
        </style>
        <div class="free-profile-wrap">
            <div class="profile-<?php echo $role_template; ?>-wrap">
                <?php if ( empty( $profile_id ) && ( fre_share_role() || in_array(FREELANCER, $current_user->roles ) ) ) { ?>
                    <div class="notice-first-login">
                        <p>
                            <i class="fa fa-warning"></i><?php _e( 'You must complete your profile to do any activities on site', ET_DOMAIN ) ?>
                        </p>
                    </div>
                <?php } ?>

                <?php get_template_part( 'profile', 'info-block' ); ?>
                <?php do_action('after_my_account_block', $user_role);?>
                <div class="row">
                    <div class="col-md-4">
                        <?php
                        if ( fre_share_role() || in_array(FREELANCER, $current_user->roles)) {
                           get_template_part( 'list', 'educations' );
                           get_template_part( 'list', 'certifications' );
                           get_template_part( 'list', 'portfolios' );
                            get_template_part( 'list', 'experiences' );
                           wp_reset_query();
                       } ?>
                      
                </div>
                <div class="col-md-8">
                 <?php if ( fre_share_role() || in_array(FREELANCER, $current_user->roles) ) {
                    get_template_part( 'template/author', 'freelancer-history' );

                        wp_reset_query();
                } ?>
            </div>
        </div>
        <?php do_action('multi_currencies_profile_setting', $user_role);?>

        <?php do_action('my_profile_section', $user_role);?>
    </div>
</div>
</div>
</div>



<?php
get_footer();
?>

