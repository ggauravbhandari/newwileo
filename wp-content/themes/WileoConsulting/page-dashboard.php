<?php
/**
 * Template Name: Dashboard Page
 */
global $post, $ae_post_factory;
if (!is_user_logged_in()) {
    wp_redirect(home_url('login'));
    exit;
}
$user = wp_get_current_user();
$post_object = $ae_post_factory->get( PROFILE );
$profile_verified = get_user_meta( $user->ID, 'user_status_cs', true );
//useful later
/*$profile_id = get_user_meta( $user->ID, 'user_profile_id', true );
var_dump($profile_id);
$profile = array();
if ( $profile_id ) {
    $profile_post = get_post( $profile_id );
    if ( $profile_post && ! is_wp_error( $profile_post ) ) {
        $profile = $post_object->convert( $profile_post );
    }
}*/
if (!empty($_GET['add_test-credit'])) {
	$Test_available = FRE_Credit_Users()->getUserWallet($user->ID);
	$Test_available->setBalance(500);
	update_user_meta($user->ID, 'fre_user_wallet', $Test_available);
	unset($Test_available);
}

get_header();
?>
    <div class="container-fluid page-container">
        <!-- block control  -->
        <div class="row block-posts dashboard-main">
            <?php

            if(in_array('employer', (array) $user->roles)) {
                if ($profile_verified === 'Approved') {
                    get_template_part('template/dashboard-employer', 'after-verification');
                } else {
                    get_template_part('template/dashboard-employer', 'before-verification');
                }
            } elseif (in_array('freelancer', (array) $user->roles)) {
                //if (property_exists($profile, 'user_profile_verified') && $profile->user_profile_verified === 'verified') {
                if ($profile_verified === 'Approved') {
                    get_template_part('template/dashboard-freelancer', 'after-verification');
                } else {
                    get_template_part('template/dashboard-freelancer', 'before-verification');
                }
            }
            ?>
        </div>
    </div>
<?php
get_footer();


