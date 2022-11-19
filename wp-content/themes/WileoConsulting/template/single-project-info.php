<?php
global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object    = $ae_post_factory->get( PROJECT );
$convert        = $project = $post_object->convert( $post );
$project_status = $project->post_status;

$user_role = ae_user_role( $user_ID );

$et_expired_date = $convert->et_expired_date;
$bid_accepted    = $convert->accepted;
$project_status  = $convert->post_status;

$profile_id   = get_user_meta( $post->post_author, 'user_profile_id', true );
$project_link = get_permalink( $post->ID );
$currency     = ae_get_option( 'currency', array( 'align' => 'left', 'code' => 'USD', 'icon' => '$' ) );
$avg          = 0;
$status = get_user_meta($user_ID, 'user_status_cs', true );
if($status === 'Disapproved'){
    wp_redirect(home_url('dashboard'));
}

$postmeta =  get_post_meta($post->ID); 
if ( is_user_logged_in() && ( ( fre_share_role() || $user_role == FREELANCER ) ) ) {
    $bidding_id  = 0;
    $child_posts = get_children(
        array(
            'post_parent' => $project->ID,
            'post_type'   => BID,
            'post_status' => 'publish',
            'author'      => $user_ID
        )
    );
    if ( ! empty( $child_posts ) ) {
        foreach ( $child_posts as $key => $value ) {
            $bidding_id = $value->ID;
        }
    }
}

?>

<style type="text/css">
    
    .project-bid-info-list>li {
    
    text-align: center;
}

.project-detail-action .fre-action-btn {
    background-color: #88c050;
    color: white;
    border-radius: 50px;
}
.project-detail-skill .fre-label {
    border-radius: 20px;}
</style>
<div class="project-detail-box">
    <div class="project-detail-info">
        <div class="row">
            <div class="col-lg-8 col-md-7">
                <h1 class="project-detail-title"><?php the_title(); ?></h1>
                
                <ul class="project-bid-info-list ocean_project1">
                    <li>
                        <?php if ( $project->total_bids > 0 ) {
                            if ( $project->total_bids == 1 ) {
                                printf( __( '<span>Offer</span><span class="secondary-color">%s</span>', ET_DOMAIN ), $project->total_bids );
                            } else {
                                printf( __( '<span>Offer</span><span class="secondary-color">%s</span>', ET_DOMAIN ), $project->total_bids );
                            }
                        } else {
                            printf( __( '<span>Offer</span><span class="secondary-color">%s</span>', ET_DOMAIN ), $project->total_bids );
                        } ?>
                    </li>
                    <li>
                        <span>

                            <?php _e('Budget',ET_DOMAIN);?></span>
                        <span class="secondary-color" ><?php echo $project->budget; ?></span>
                    </li>
                    <li>
                        <span><?php _e('Average Offer',ET_DOMAIN);?></span>
                        <span class="secondary-color">
                            <?php
                            if ( $project->total_bids > 0 ) {
                                $avg = get_total_cost_bids( $project->ID ) / $project->total_bids;
                            }
                            echo fre_price_format( $avg );
                            ?>
                        </span>
                    </li>
                    <?php if ( $project->text_country != '' ) { ?>
                        <li>
                            <span><?php _e( 'Location', ET_DOMAIN ); ?></span>
                            <span class="secondary-color"><?php echo $project->text_country; ?></span>
                        </li>
                    <?php } ?>

                    <li>
                        <span>

                            <?php _e('Project Type',ET_DOMAIN);?></span>
                        <span class="secondary-color" ><?php echo $project->tax_input['project_type'][0]->name; ?></span>
                    </li>

                    <?php if($project->tax_input['project_type'][0]->name== "Time Based") {
                         
                          $postmeta =  get_post_meta($post->ID); 
                          
                        ?>
                    <li>
                        <span>

                            <?php _e('Min Hours',ET_DOMAIN);?></span>
                        <span class="secondary-color" ><?php echo $postmeta['et_commite_min_hour'][0] ?></span>
                    </li>
                    <li>
                        <span>

                            <?php _e('Max Hours',ET_DOMAIN);?></span>
                        <span class="secondary-color" ><?php echo $postmeta['et_commite_max_hour'][0]; ?></span>
                    </li>
                <?php } ?>
                </ul>
            </div>
            <div class="col-lg-4 col-md-5">
                <p class="project-detail-posted"><?php printf( __( 'Posted on %s', ET_DOMAIN ), $project->post_date ); ?></p>
                <span class="project-detail-status secondary-color">
                    <?php
                    $status_arr = array(
                        'close'     => __( "Processing", ET_DOMAIN ),
                        'complete'  => __( "Completed", ET_DOMAIN ),
                        'disputing' => __( "Disputed", ET_DOMAIN ),
                        'disputed'  => __( "Resolved", ET_DOMAIN ),
                        'publish'   => __( "Active", ET_DOMAIN ),
                        'pending'   => __( "Pending", ET_DOMAIN ),
                        'draft'     => __( "Draft", ET_DOMAIN ),
                        'reject'    => __( "Rejected", ET_DOMAIN ),
                        'archive'   => __( "Archived", ET_DOMAIN ),
                        'private'     => __( "Private", ET_DOMAIN ),
                    );
                    echo $status_arr[ $post->post_status ];
                    echo '<span>'; // 1.8.5 add
                    if( ! empty( $et_expired_date ) ) {
                        printf(__(' - %s left',ET_DOMAIN), human_time_diff( time(), strtotime($et_expired_date)) );
                    }
                    echo '</span>';
                    ?>

                </span>
                <div class="project-detail-action">
                    <?php
                    if ( is_user_logged_in() ) {
                        if ( $project_status == 'publish' ) {
                            if ( ( fre_share_role() || $user_role == FREELANCER ) && $user_ID != $project->post_author ) {
                                $has_bid = fre_has_bid( get_the_ID() );
                                if ( $has_bid ) {
                                    echo '<a class="fre-normal-btn primary-bg-color bid-action" data-action="cancel" data-bid-id="' . $bidding_id . '">' . __( 'Cancel', ET_DOMAIN ) . '</a>';
                                } else {
                                   // echo '<p>test001</p>';
                                    fre_button_bid( $project->ID );
                                }
                            } else if ( ( ( fre_share_role() || $user_role == EMPLOYER ) || current_user_can( 'manage_options' ) ) && $user_ID == $project->post_author ) {
                                echo '<a class="fre-action-btn  project-action" data-action="archive" data-project-id="' . $project->ID . '">' . __( 'Archive', ET_DOMAIN ) . '</a>';
                            } else {
                                echo '<a href="' . et_get_page_link( 'submit-project' ) . '" class="fre-normal-btn primary-bg-color">' . __( 'Post Project Like This', ET_DOMAIN ) . '</a>';
                            }
                        }else if( $project_status == 'private' ){
                            echo '<a href="' . et_get_page_link( 'submit-project' ) . '" class="fre-normal-btn primary-bg-color">' . __( 'Post Project Like This', ET_DOMAIN ) . '</a>';
                        }
                         else if ( $project_status == 'disputing' || $project_status == 'disputed' ) {
                            $bid_accepted_author = get_post_field( 'post_author', $bid_accepted );
                            if ( (int) $project->post_author == $user_ID || $bid_accepted_author == $user_ID || current_user_can( 'manage_options' ) ) {
                                echo '<a class="fre-normal-btn" href="' . add_query_arg( array( 'dispute' => 1 ), $project_link ) . '">' . __( 'Dispute Page', ET_DOMAIN ) . '</a>';
                            }
                        } else if ( $project_status == 'close' ) {
                            $bid_accepted_author = get_post_field( 'post_author', $bid_accepted );
                            if ( (int) $project->post_author == $user_ID || $bid_accepted_author == $user_ID ) {
                                echo '<a class="fre-normal-btn" href="' . add_query_arg( array( 'workspace' => 1 ), $project_link ) . '">' . __( 'Workspace', ET_DOMAIN ) . '</a>';
                            }
                        } else if ( $project_status == 'complete' ) {
                            $bid_accepted_author = get_post_field( 'post_author', $bid_accepted );
                            if ( (int) $project->post_author == $user_ID || $bid_accepted_author == $user_ID ) {
                                echo '<a class="fre-normal-btn" href="' . add_query_arg( array( 'workspace' => 1 ), $project_link ) . '">' . __( 'Workspace', ET_DOMAIN ) . '</a>';
                            } else if ( current_user_can( 'manage_options' ) && ae_get_option( 'use_escrow' ) ) {
                                $bid_id_accepted = get_post_meta( $post->ID, 'accepted', true );
                                $order           = get_post_meta( $bid_id_accepted, 'fre_bid_order', true );
                                $order_status    = get_post_field( 'post_status', $order );
                                $commission      = get_post_meta( $bid_id_accepted, 'commission_fee', true );
                                if ( $commission ) {
                                    if ( $order_status != 'finish' ) {
                                        echo '<a class="fre-normal-btn primary-bg-color manual-transfer" data-project-id="' . $project->ID . '">' . __( "Transfer Money", ET_DOMAIN ) . '</a>';
                                    } else {
                                        if ( ae_get_option( 'manual_transfer', false ) ) {
                                            echo '<span class="fre-money-transfered">';
                                            _e( "Already transfered", ET_DOMAIN );
                                            echo '</span>';
                                        }
                                    }
                                }
                            }
                        } else if ( $project_status == 'pending' ) {
                            if ( ( fre_share_role() || $user_role == EMPLOYER ) && $user_ID == $project->post_author ) {
                                echo '<a class="fre-action-btn" href="' . et_get_page_link( 'edit-project', array( 'id' => $project->ID ) ) . '">' . __( 'Edit', ET_DOMAIN ) . '</a>';
                            }if($project->tax_input['project_type'][0]->name== "Projects/Panels") {
                               
                               if ( ( $user_role == "bbp_keymaster" )) 
                               {
                                echo '<a class="fre-normal-btn primary-bg-color add-private-action" get-project-id="' . $project->ID . '">' . __( 'Private', ET_DOMAIN ) . '</a>';
                                }
                            }
                            else if ( current_user_can( 'manage_options' ) ) {
                                echo '<a class="fre-normal-btn primary-bg-color project-action" data-action="approve" data-project-id="' . $project->ID . '">' . __( 'Approve', ET_DOMAIN ) . '</a>';
                                echo '<a class="fre-normal-btn primary-bg-color project-action" data-action="reject" data-project-id="' . $project->ID . '">' . __( 'Reject', ET_DOMAIN ) . '</a>';
                            }
                        } else if ( $project_status == 'reject' ) {
                            if ( ( fre_share_role() || $user_role == EMPLOYER ) && $user_ID == $project->post_author ) {
                                echo '<a class="fre-action-btn" href="' . et_get_page_link( 'edit-project', array( 'id' => $project->ID ) ) . '">' . __( 'Edit', ET_DOMAIN ) . '</a>';
                            }
                        } else if ( $project_status == 'draft' ) {
                            if ( ( fre_share_role() || $user_role == EMPLOYER ) && $user_ID == $project->post_author ) {
                                echo '<a class="fre-action-btn" href="' . et_get_page_link( 'submit-project', array( 'id' => $project->ID ) ) . '">' . __( 'Edit', ET_DOMAIN ) . '</a>';
                                echo '<a class="fre-action-btn project-action" data-action="delete" data-project-id="' . $project->ID . '">' . __( 'Delete', ET_DOMAIN ) . '</a>';
                            } else if ( current_user_can( 'manage_options' ) ) {
                                echo '<a class="fre-action-btn project-action" data-action="delete" data-project-id="' . $project->ID . '">' . __( 'Delete', ET_DOMAIN ) . '</a>';
                            }
                        } else if ( $project_status == 'archive' ) {
                            if ( ( fre_share_role() || $user_role == EMPLOYER ) && $user_ID == $project->post_author ) {
                                echo '<a class="fre-action-btn" href="' . et_get_page_link( 'submit-project', array( 'id' => $project->ID ) ) . '">' . __( 'Renew', ET_DOMAIN ) . '</a>';
                                echo '<a class="fre-action-btn project-action" data-action="delete" data-project-id="' . $project->ID . '">' . __( 'Delete', ET_DOMAIN ) . '</a>';
                            } else if ( current_user_can( 'manage_options' ) ) {
                                echo '<a class="fre-action-btn project-action" data-action="delete" data-project-id="' . $project->ID . '">' . __( 'Delete', ET_DOMAIN ) . '</a>';
                            }
                        }
                    } else {
                        if ( $project_status == 'publish' ) {
                            echo '<a class="fre-normal-btn primary-bg-color" href="' . et_get_page_link( 'login', array( 'ae_redirect_url' => $project->permalink ) ) . '">' . __( 'Offer', ET_DOMAIN ) . '</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(function($, Views, Models, Collections){
       jQuery('.add-private-action').click(function(){
        var provate_act = jQuery('.add-private-action').attr('get-project-id');
          // alert('val = '+provate_act+ '  action url ='+ae_globals.ajaxURL);
           jQuery.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: {
                        ID: provate_act,
                        action: 'ae-project-private',
                        method: 'private'
                    },
                    success: function (res) {
                        console.log(res);
                        //view.blockUi.unblock();
                        if (res.success) {
                            window.location.href = res.permalink
                        }
                    }
            });
       })
       
    })
</script>
