<?php
/**
 * Template Name: Page Post Project
*/
global $user_ID, $profile, $current_user, $profile_id, $ae_post_factory;

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
get_header();
?>
<div class="fre-page-wrapper">
    <div class="fre-page-title">
        <div class="container">
            <h2><?php the_title(); ?></h2>
        </div>
    </div>

    <div class="fre-page-section">
        <div class="container">
            <div class="page-post-project-wrap" id="post-place">

                <?php
                    // check disable payment plan or not
                    if( is_acti_fre_membership() ){
                        if( LIVE_MODE ){
                            get_template_part( 'template/post-project', 'step3' );
                        } else {
                            fre_generator_project_form();// Develop mode
                        }
                    } else {
                        $post_free = is_post_project_free();;
                        if(!$post_free) {
                            // template/post-place-step1.php
                            get_template_part( 'template/post-project', 'step1' );
                        }

                        // template/post-place-step3.php
                        if( LIVE_MODE ){
                            get_template_part( 'template/post-project', 'step3' );
                        } else {
                            // Develop mode
                            fre_generator_project_form();
                        }

                        if(!$post_free) {
                            // template/post-place-step4.php
                            get_template_part( 'template/post-project', 'step4' );
                        }
                    }
                ?>
            </div>
            <?php do_action('right_post_project_form');?>
        </div>
    </div>
</div>

    <script type="text/template" id="ae_carousel_template">
        <li class="image-item" id="{{= attach_id }}">
            <div class="attached-name"><p>{{= name }}</p></div>
            <div class="attached-size">{{= size }}</div>
            <div class="attached-remove"><span class=" delete-img delete"><i class="fa fa-times"></i></span></div>
        </li>
    </script>
<?php
get_footer();
