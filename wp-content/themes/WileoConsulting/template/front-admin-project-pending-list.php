<?php
/**
 * Template Name: Front Admin Pending Project List
 * The template for displaying all pages
 *
 * This is the template that displays all pending project in frontend.
 * Please note this list will show only for admin or administartor
 *
 * @package WordPress
 * @subpackage FreelanceEngine
 * @since FreelanceEngine 1.0
 */

if ( ! is_user_logged_in() ) {
	wp_redirect( et_get_page_link( 'login', array( 'ae_redirect_url' => get_permalink( $post->ID ) ) ) );
}

get_header();
global $wpdb, $wp_query, $ae_post_factory, $post, $current_user, $user_ID;
$user_role = ae_user_role( $user_ID );
$status = get_user_meta($user_ID, 'user_status_cs', true );
if($status === 'Disapproved'){
    wp_redirect(home_url('dashboard'));
}
define( 'NO_RESULT', __( '<span class="project-no-results">There are no pending yet.</span>', ET_DOMAIN ) );
$currency = ae_get_option( 'currency', array( 'align' => 'left', 'code' => 'USD', 'icon' => '$' ) );

?>
<style type="text/css">
	.custom_admin_project_list{
		padding: 0px 15px 15px 10px;
	}
</style>
    <div class="fre-page-wrapper">
        <div class="fre-page-title">
            <div class="container">
                <h2><?php the_title(); ?></h2>
            </div>
        </div>
        <div class="fre-page-section">
            <div class="container">
                <div class="my-work-employer-wrap">
					<?php if ( fre_share_role() || $user_role == FREELANCER ) {
						fre_show_credit( FREELANCER );
					}

                    /* else {
						fre_user_package_info( $user_ID );
					} */ 
					?>
                   
                    <div class="fre-tab-content">
                    	<div class="fre-work-project-box">
                    		<h3 class="text-left custom_admin_project_list"> Project Pending List</h3>
                    	</div>
						<?php //if (  (fre_share_role() && $user_role == FREELANCER ) || $user_role == FREELANCER  ) { 
							if(in_array('administrator',$current_user->roles) && in_array('bbp_keymaster',$current_user->roles)){ ?>
                            <div id="current-project-tab" class="employer-current-project-tab fre-panel-tab active">
								<?php
								$employer_current_project_query = new WP_Query(
									array(
										'post_status'      => array(
											'pending',
										),
										'is_author'        => true,
										'post_type'        => PROJECT,
										// 'author'           => $user_ID,
										'suppress_filters' => true,
										'orderby'          => 'date',
										'order'            => 'DESC'
									)
								);

								$post_object       = $ae_post_factory->get( PROJECT );
								$no_result_current = '';

								?>
                                
                                <div class="fre-work-project-box 22">
                                    <div class="current-employer-project">
                                        <div class="fre-table">
                                            <div class="fre-table-head">
                                                <div class="fre-table-col project-title-col"><?php _e( 'Project Title', ET_DOMAIN ); ?></div>
                                                <div class="fre-table-col project-budget-col"><?php _e( 'Budget', ET_DOMAIN ); ?></div>
                                                <div class="fre-table-col project-open-col"><?php _e( 'Open Date', ET_DOMAIN ); ?></div>
                                                <div class="fre-table-col project-type-col"><?php _e( 'Project Type', ET_DOMAIN ); ?></div>
                                                <div class="fre-table-col project-status-col"><?php _e( 'Status', ET_DOMAIN ); ?></div>
                                                
                                            </div>
                                            <div class="fre-current-table-rows" style="display: table-row-group;">
												<?php

												if ( $employer_current_project_query->have_posts() ) {
													$postdata = array();
												while ( $employer_current_project_query->have_posts() ) {
													$employer_current_project_query->the_post();
													$convert        = $post_object->convert( $post, 'thumbnail' );
													$postdata[]     = $convert;
													
													$term_obj_name = wp_get_post_terms($convert->ID, 'project_type',  array("fields" => "names"));
													
													$project_status = $convert->post_status;
													?>
                                                    <div class="fre-table-row">
                                                        <div class="fre-table-col project-title-col">
                                                            <a  class="secondary-color" href="<?php echo $convert->permalink; ?>"><?php echo $convert->post_title; ?></a>
                                                        </div>
                                                        
                                                        <div class="fre-table-col project-budget-col">
                                                            <span><?php _e( 'Budget', ET_DOMAIN ); ?></span><?php echo $convert->budget; ?>
                                                        </div>
                                                        <div class="fre-table-col project-open-col">
                                                            <span><?php _e( 'Open on', ET_DOMAIN ); ?></span><?php echo $convert->post_date; ?>
                                                        </div>
                                                        <div class="fre-table-col project-status-col"><?php echo !empty($term_obj_name[0]) ? $term_obj_name[0] : ''; ?></div>
                                                        <div class="fre-table-col project-status-col"><?php echo $convert->project_status_view; ?></div>


														
                                                    </div>
												<?php } ?>
                                                    
												<?php } else {
													$no_result_current = NO_RESULT;
												}
												?>
                                            </div>
                                        </div>
										<?php
										if ( $no_result_current != '' ) {
											echo $no_result_current;
										}
										?>
                                    </div>
                                </div>
                                <div class="fre-paginations paginations-wrapper">
                                    <div class="paginations">
										<?php ae_pagination( $employer_current_project_query, get_query_var( 'paged' ) ); ?>
                                    </div>
                                </div>
								<?php
								wp_reset_postdata();
								wp_reset_query();
								?>
                            </div>
                            <div id="" class="
                                <div class="fre-paginations paginations-wrapper">
                                    <div class="paginations">
										<?php ae_pagination( $employer_previous_project_query, get_query_var( 'paged' ) ); ?>
                                    </div>
                                </div>
								<?php
								wp_reset_postdata();
								wp_reset_query();
								?>
                            </div>
						<?php }else{
							wp_redirect(site_url('404'));
							exit;
						} ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>