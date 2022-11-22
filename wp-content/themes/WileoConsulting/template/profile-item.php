<?php
/**
 * The template for displaying profile in a loop
 * @since  1.0
 * @package FreelanceEngine
 * @category Template
 */
global $wp_query, $ae_post_factory, $post;
$post_object = $ae_post_factory->get( PROFILE );
$current = $post_object->current_post;
if(!$current){
    return;
}
$hou_rate = (int) $current->hour_rate;

$curr_user_ID = $current->post_author;
$is_profile_visibility = '';
$is_profile_visibility = get_user_meta($curr_user_ID,'user_profile_visibility',true);
if($is_profile_visibility != "no"){
?>
    <li class="profile-item" tid="<?php echo$is_profile_visibility; ?>" >
        <div class="profile-list-wrap">
            <a class="profile-list-avatar" href="<?php echo $current->permalink; ?>">
                <?php echo get_avatar($post->post_author); ?>
            </a>
            <h2 class="profile-list-title">
                <a href="<?php echo $current->permalink; ?>"><?php echo $current->author_name; ?></a>
            </h2>
            <p class="profile-list-subtitle"><?php echo $current->et_professional_title;?>
            <span><i class="fa fa-circle" style="font-size: 5px;color:#BFC2BA; margin-left: 6px; margin-right: 6px;"></i></span>
            <span><?php echo $current->experience ?></span></p>

            <div class="profile-list-info">
                <div class="profile-list-detail">
                    <?php if( $hou_rate > 0 ) { echo '<span class="consultant-hourly-rate 00003">'; echo $current->hourly_rate_price; echo '</span>'; } ?>
                    <span class="rate-it" data-score="<?php echo $current->rating_score ; ?>"></span>
                    <!-- <span><?php echo $current->experience ?></span> -->
                     <span style="color: #465250;font-weight: 500;font-size: 14px;"><?php echo ($current->earned); ?></span>
                    <span><?php echo $current->project_worked; ?></span>
                </div>
                <!-- <div class="profile-list-desc">
    	            <?php echo $current->excerpt;?>
                </div> -->
            </div>
        </div>
    </li>
<?php } ?>
