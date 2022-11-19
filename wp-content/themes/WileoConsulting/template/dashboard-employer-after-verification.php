<?php
global $wpdb, $wp_query, $ae_post_factory, $post, $current_user, $user_ID;
$user_role = ae_user_role( $user_ID );
define( 'NO_RESULT', __( '<span class="project-no-results">There are no activities yet.</span>', ET_DOMAIN ) );
$currency = ae_get_option( 'currency', array( 'align' => 'left', 'code' => 'USD', 'icon' => '$' ) );
$available = FRE_Credit_Users()->getUserWallet($user_ID);
$total_withdraw  = credit_get_total_withdraw($user_ID);

?>
<!--Page Content-->
<div class="col-md-8 col-sm-12 col-xs-12" id="left_content">
    <div id="current-project-tab" class="freelancer-current-project-tab fre-panel-tab active">
        <div class="fre-work-project-box">
            <div class="contract-wrapper">
                <div class="active-contract">
                    <h2>Active Contracts</h2>
                </div>
                <div class="contract-view-more">
                    <a href="<?php echo site_url('my-project/')?>" target=""><?php _e('View More', ET_DOMAIN); ?></a>
                </div>
            </div>
            <!-- <hr/> -->
            <?php
            $is_author   = is_author();
            $post_parent = array();
            $result      = $wpdb->get_col( "SELECT * FROM $wpdb->posts WHERE 1=1 AND post_type = 'project' AND post_status IN ( 'publish', 'close', 'archive', 'disputing' )" );
            if ( ! empty( $result ) ) {
                $post_parent = $result;
            }
            $freelancer_current_project_query = $employer_current_project_query = new WP_Query(
                array(
                    'post_status'      => array(
                        'close'
                    ),
                    'posts_per_page'    => 3,
                    'post_type'        => PROJECT,
                    'author'           => $current_user->ID,
                    'accepted'         => 1,
                    'is_author'        => true,
                    'suppress_filters' => true,
                    'orderby'          => 'date',
                    'order'            => 'DESC'
                )
            );
            $post_object                      = $ae_post_factory->get( BID );
            $no_result_current                = '';
            ?>
            <div class="current-freelance-project">
                <div class="fre-table active-contract-table">
                    <div class="fre-table-head">
                        <div class="fre-table-col project-title-col"><?php _e( 'Name', ET_DOMAIN ); ?></div>
                        <div class="fre-table-col project-bids-col"><?php _e( 'Next Milestone', ET_DOMAIN ); ?></div>
                        <div class="fre-table-col project-bid-col"><?php _e( 'progress', ET_DOMAIN ); ?></div>
                    </div>
                    <div class="fre-current-table-rows" style="display: table-row-group;">
                        <?php
                        $postdata = array();
                        if ( $freelancer_current_project_query->have_posts() ) {
                        while ( $freelancer_current_project_query->have_posts() ) {
                            $freelancer_current_project_query->the_post();
                            $convert    = $post_object->convert( $post );
                            $postdata[] = $convert;
                            $bid_status = $convert->post_status;
                            $milestone_args_all = array(
                                'post_type'      => 'ae_milestone',
                                'posts_per_page' => -1,
                                'post_status'    => 'any',
                                'post_parent'    => $convert->project_id,
                                'orderby'        => 'meta_value',
                                'order'          => 'ASC',
                                'meta_key'       => 'position_order'
                            );
                            $milestone_args_resolved = array(
                                'post_type'      => 'ae_milestone',
                                'posts_per_page' => -1,
                                'post_status'    => 'resolve',
                                'post_parent'    => $convert->project_id,
                                'orderby'        => 'meta_value',
                                'order'          => 'ASC',
                                'meta_key'       => 'position_order'
                            );

                            $milestone_all = get_posts( $milestone_args_all );
                            $milestone_resolved = get_posts( $milestone_args_resolved );
                            ?>

                            <div class="fre-table-row">
                                <div class="fre-table-col project-title-col">
                                    <?php echo $convert->project_title; ?>
                                </div>
                                <?php
                                if ( count($milestone_all) > 0 ):
                                    $current_milestone = false;
                                    foreach ($milestone_all as $milestone) {
                                        if ($milestone->post_status == 'publish') {
                                            $current_milestone = $milestone;
                                            break;
                                        }
                                    }
                                    ?>
                                    <div class="fre-table-col project-bids-col">
                                        <?php
                                        if($current_milestone) {
                                            echo $current_milestone->post_title;
                                        } else {
                                            _e( '---', ET_DOMAIN );
                                        }
                                        ?>
                                    </div>
                                    <div class="fre-table-col project-bid-col">
                                        <?php
                                        if(!empty($milestone_resolved)) {
                                            $milestone_resolved = count($milestone_resolved);
                                            echo floor(($milestone_resolved / count($milestone_all))*100) . '%';
                                        } else {
                                            _e( '0%', ET_DOMAIN );
                                        }
                                        ?>
                                    </div>
                                <?php else:?>
                                    <div class="fre-table-col project-bids-col"><?php _e( 'No Milestone', ET_DOMAIN ); ?></div>
                                    <div class="fre-table-col project-bid-col"><?php _e( '---', ET_DOMAIN ); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php } ?>
                            <script type="data/json"
                                    id="current_project_post_data"><?php echo json_encode( $postdata ); ?></script>
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
        <?php
        wp_reset_postdata();
        wp_reset_query();
        ?>
    </div>

    <div class="fre-tab-content" style="margin: 20px 0;">
        <div id="fre-credit-balance" class="fre-panel-tab active">
            <div class="fre-credit-box">
                <div class="report-summary-wrapper">
                    <div class="report-summary">
                        <h2><?php _e('Report summary', ET_DOMAIN); ?></h2>

                    </div>
                    <div class="report-view-more">
                        <a href="<?php echo site_url('my-reports/')?>" target=""><?php _e('View More', ET_DOMAIN); ?></a>
                    </div>
                </div>
                <table class="report-summary-table" style="border-top: 2px solid #DDE0D9; border-collapse: collapse; width:100%">
                    <tr>
                        <td rowspan="5" class="balance-column" style="border-right: 1.5px solid #DDE0D9;">
                            <h4 class="report-avilable-earning"><?php _e('Available Earnings', ET_DOMAIN)?></h4>
                            <h3 class="report-available-blance"><?php echo fre_price_format($available->balance) ?></h3>
                            <h4 class="report-pending"><?php _e('Pending', ET_DOMAIN)?></h4>
                            <h4 class="report-total-withdraw"><?php echo fre_price_format($total_withdraw) ?></h4>
                        </td>
                        <td colspan="4" class="transactions-header" style="background-color: #DDE0D9; border-bottom: 1.5px solid #DDE0D9;"><?php _e('Recent Transactions', ET_DOMAIN); ?></td>
                    </tr>
                    <?php
                    $args = array(
                        'post_type'=> 'fre_credit_history',
                        'post_status'=> 'publish',
                        'author'=> $user_ID,
                        'post_per_page' => 3,
                        'meta_query' => array(
                            array(
                                'key' => 'history_status',
                                'value' => 'completed',
                            )
                        )
                    );
                    $new_query = get_posts($args);
                    if( !empty($new_query) ) {
                        foreach ( $new_query as $transaction ):
                            $his_obj  = $ae_post_factory->get( 'fre_credit_history' );
                            $convert  = $his_obj->convert( $transaction );
                            $project  = get_post( get_post_meta( $convert->ID, 'payment', true ) );
                            $employer = get_user_by( 'ID', $project->post_author );
                            $ae_users = AE_Users::get_instance();
                            $employer = $ae_users->convert( $employer->data );
                            echo '
                                    <tr class="report-summary-employer-detail">
                                        <td class="employer-person-detail">
                                            <span class="employer-avatar">' . get_avatar( $employer->ID, 64 ) . '</span><strong class="employer-name">' . $employer->display_name . '</strong>
                                        </td>
                                        <td class="employer-project-detail">' . $project->post_title . '<br></td>
                                        <td class="employer-project-date"><span class="text-right">' . date( 'M jS', strtotime( $convert->post_date ) ) . '</span></td>
                                        <td class="employer-project-balance">' . $convert->user_balance . '</td>
                                    </tr>
                                ';
                        endforeach;
                        if ( count( $new_query ) < 3 ) {
                            echo '<tr><td colspan="3" rowspan="' . ( 3 - count( $new_query ) ) . '">&nbsp;</td></tr>';
                        }
                    }
                    else{ ?>
                        <tr><td colspan="3" rowspan="3"><?php _e("There isn't any transaction!",ET_DOMAIN) ?></td></tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

    <div class="fre-tab-content" style="margin-top: 20px">
        <div id="fre-credit-balance" class="fre-panel-tab active">
            <div class="fre-credit-box">
                <div class="report-summary-wrapper">
                    <div class="report-summary">
                        <h2><?php _e('Recent Messages', ET_DOMAIN); ?></h2>

                    </div>
                    <div class="report-view-more">
                        <a href="<?php echo site_url('private-message/')?>" target=""><?php _e('View More', ET_DOMAIN); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
 $user = wp_get_current_user();
    $userEmailActive = get_user_meta($user->ID, 'register_status', true);
    $personalInfo = get_user_meta($user->ID, 'personal_information');
    $profileActive = get_user_meta($user->ID, 'user_status_cs', true);
    $user_profile_id = get_user_meta($user->ID, 'user_profile_id', true);


    /* check current user post if exist*/
    $args = array(
    'author'        =>  $user->ID,
    'orderby'       =>  'post_date',
    'order'         =>  'ASC',
    'posts_per_page' => -1,
    'post_type' => 'project',
    'post_status' => 'any',
    );
    $current_user_post_arr = get_posts( $args );

    /* check current user post is come one make offer and employer accept consultant offer  */

    $accept_offer = false;
    
    if(!empty($current_user_post_arr)){
        foreach ($current_user_post_arr as $key => $post_val) {
            $accept_offer_args = array(
            'orderby'       =>  'post_date',
            'order'         =>  'ASC',
            'posts_per_page'=> -1,
            'post_parent'   => $post_val->ID,
            'post_type' => 'bid',
            'post_status' => 'any',
            );
           
            $accept_offer_res = get_posts( $accept_offer_args );
            
            if(!empty($accept_offer_res)){
                foreach ($accept_offer_res as $key => $val) {
                    $pay_accept_offer_args = array(
                        'orderby'       =>  'post_date',
                        'order'         =>  'ASC',
                        'posts_per_page'=> 1,
                        'post_parent'   => $val->ID,
                        'post_type' => 'fre_order',
                        
                        );
                    $pay_accept_offer_res = get_posts( $pay_accept_offer_args );

                   
                    if(!empty($pay_accept_offer_res[0])){
                        $accept_offer = true;
                        break 2;
                    }
                }
            }
        }
    }
    
    $progress = 20;
    if ($userEmailActive !== 'unconfirm') {
        $progress += 10;
    }
    if (!empty($personalInfo)) {
        $progress += 20;
    }

    if ($profileActive === 'Approved') {
        $progress += 20;
    }
    if (!empty($user_profile_id)) {
        $progress += 20;
    }

    $is_post = false;
    if (!empty($current_user_post_arr) && count($current_user_post_arr) > 0) {
        $progress += 10;
        $is_post = true;
    }
    
    $offer_accepted = false;
    if($accept_offer == true){
        $progress += 20;
        $offer_accepted = true;
    }

?>
<!--Dashboard Sidebar/Profile Percentage   hjhjjjjjjjj-->
<div class="col-md-4 col-sm-12 col-xs-12" id="right_content">
    <div class="prof-progress">
        <div class="col overall-main">
             <div class="pie-wrapper progress-45 style-2">
                <div role="progressbar"  aria-valuenow="<?php echo $progress ?>" aria-valuemin="0" aria-valuemax="100" style="--value:<?php echo $progress ?>"></div>
            </div>
            <h4 class="pull-left vc-heading">Profile progress</h4>
        </div>
        <style>

                /*************************/
                @keyframes growProgressBar {
                  0%, 33% { --pgPercentage: 0; }
                  100% { --pgPercentage: var(--value); }
                }

                @property --pgPercentage {
                  syntax: '<number>';
                  inherits: false;
                  initial-value: 0;
                }

                div[role="progressbar"] {
                    font-weight: 800;
                    --size: 42px;
                    --fg: #369;
                    --bg: #def;
                    --pgPercentage: var(--value);
                    animation: growProgressBar 3s 1 forwards;
                    width: var(--size);
                    height: var(--size);
                    border-radius: 50%;
                    display: grid;
                    place-items: center;
                    background: radial-gradient(closest-side, #eee 80%, transparent 0 99.9%, white 0), conic-gradient(#88c050 calc(var(--pgPercentage) * 1%), #bdc3c7 0);
                    font-family: inherit;
                    font-size: calc(var(--size) / 4);
                    color: #666f78;
                }

                div[role="progressbar"]::before {
                  counter-reset: percentage var(--value);
                  content: counter(percentage) '%';
                }

                i.ico.fa.fa-check.ico-green.offer_accepted {
                    background: #88C050 !important;
                    color: #ffffff !important;
                    border: 2px solid #88C050 !important;
                }

                /* end */
                .set-size {
                    font-size: 3em;
                }

                .charts-container:after {
                    clear: both;
                    content: "";
                    display: table;
                }

                .pie-wrapper {
                    height: auto;
                    width: auto;
                    float: left;
                    margin: 0 15px 0 25px;
                    position: relative;
                }
                .pie-wrapper:nth-child(3n+1) {
                    clear: both;
                }
                .pie-wrapper .pie {
                    height: 100%;
                    width: 100%;
                    clip: rect(0, 1em, 1em, 0.5em);
                    left: 0;
                    position: absolute;
                    top: 0;
                }
                .pie-wrapper .pie .half-circle {
                    height: 100%;
                    width: 100%;
                    border: 0.1em solid #3498db;
                    border-radius: 50%;
                    clip: rect(0, 0.5em, 1em, 0);
                    left: 0;
                    position: absolute;
                    top: 0;
                }
                .pie-wrapper .label {
                    background: #34495e;
                    border-radius: 50%;
                    bottom: 0.4em;
                    color: #ecf0f1;
                    cursor: default;
                    display: block;
                    font-size: 0.25em;
                    left: 0.4em;
                    line-height: 2.8em;
                    position: absolute;
                    right: 0.4em;
                    text-align: center;
                    top: 0.4em;
                }
                .pie-wrapper .label .smaller {
                    color: #bdc3c7;
                    font-size: 0.45em;
                    padding-bottom: 20px;
                    vertical-align: super;
                }
                .pie-wrapper .shadow {
                    height: 100%;
                    width: 100%;
                    border: 0.1em solid #bdc3c7;
                    border-radius: 50%;
                }
                .pie-wrapper.style-2 .label {
                    background: none;
                    color: #7f8c8d;
                }
                .pie-wrapper.style-2 .label .smaller {
                    color: #bdc3c7;
                }
                .pie-wrapper.progress-45 .pie .half-circle {
                    border-color: #88C050;
                }
                .pie-wrapper.progress-45 .pie .left-side {
                    transform: rotate(72deg);
                }
                .pie-wrapper.progress-45 .pie .right-side {
                    display: none;
                }

               
            /*************************/
        </style>

        <div class="bs-vertical-wizard">
            <ul>
                <li class="complete">
                    <a href="#">Sign up <span class="icon-container"><i class="ico fa fa-check ico-green"></i></span>
                        <!--                  <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span>-->
                    </a>
                </li>
                <li class="complete">
                    <a href="#">Email Confirmation <span class="icon-container"><i class="ico fa fa-check ico-green"></i></span>
                        <!--                  <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span>-->
                    </a>
                </li>
                <li class="complete prev-step">
                    <a href="#">Account verification <span class="icon-container"><i class="ico fa fa-check ico-green"></i></span>
                        <!--                  <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span>-->
                    </a>
                </li>
                <li class="<?php echo !empty($user_profile_id) ? 'complete prev-step' : 'current' ?>">
                    <?php 
                       if(empty($user_profile_id)){
                        ?>
                            <a href="<?php echo home_url('profile'); ?>">Complete your profile <span class="icon-container"><i class="ico ico-green"> 4 </i></span>
                            </a>
                            <div class="desc" style="margin-left:35px;" >
                            <a href="<?php echo home_url('profile-settings'); ?>" class="btn greenbtn" >Complete Profile</a>
                            </div>
                            
                        <?php
                        }else{
                        ?>
                            <a href="<?php echo home_url('profile'); ?>">Complete your profile <span class="icon-container"><i class="ico fa fa-check ico-green"></i></span>
                            </a>
                        <?php
                        }
                    ?>
                </li>
                
                <li class="<?php echo $is_post == true ? 'complete prev-step' : 'incomplete-s'; ?>">
                    <?php
                        if($is_post == true){
                            ?>
                            <a href="#">Post a project <span class="icon-container"><i class="ico fa fa-check ico-green"></i></span>
                            </a>
                        <?php
                        }else{
                        ?>
                            <a href="<?php echo  site_url('submit-project') ?>">Post a project <span class="icon-container"><i class="ico ico-green"> 5 </i></span>
                            </a>
                        <?php
                        }
                    ?>
                    
                </li>
                
                <li class="incomplete-s">
                    <?php
                    
                    if($offer_accepted == true){
                    ?>
                        <a href="#">Hire consultants <span class="icon-container"><i class="ico fa fa-check ico-green offer_accepted"></i></span></a>
                    <?php
                    }else{
                    ?>
                         <a href="#">Hire consultants <span class="icon-container"><i class="ico ico-green"> 6 </i></span>
                    <?php
                    }
                    ?>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div><!-- RIGHT CONTENT -->
