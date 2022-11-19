<!--Page Content-->
<div class="col-md-8 col-sm-12 col-xs-12" id="left_content">
    <div class="dashboard-content before-verication-wrapper">
        <div class="db-header">
            <h1 class="account-created-title">Your account has been created</h1>
            <h5 class="join-cummunity pt-20 mt-20">You can now access our community.</h5>
        </div>
        <div class="db-content col">

            <div class="cont-container pull-left">
                <h4 class="how-it-works-title">How it works?</h4>

                <div class="bs-horizontal-wizard">
                    <ul>
                        <li class="current">
                            <a href="#">Sign up <span class="icon-container"><i class="ico ico-green"> 1 </i></span>
                            </a>
                            <span class="desc">You are signed up. You can now access the community forum and interact with other members.</span>
                        </li>

                        <li class="current prev-step">
                            <a href="#">Get you profile approved <span class="icon-container"><i
                                            class="ico ico-green"> 2 </i></span>

                            </a>
                            <div class="desc">
                                Tell us about your skills, experience, and motivations

                            </div>
                        </li>
                        <li class="current">
                            <a href="#">Bid on projects<span class="icon-container"><i
                                            class="ico ico-green"> 3 </i></span>
                            </a>

                            <div class="desc">
                                Once approved, you can view and bid on projects on the platform.
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="db-content mt-10">
                    <div class="cont-container pull-left faq-wrappper">
                        <h4 class="faq-title">FAQs</h4>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading collapsed" role="tab" id="headingOne" data-toggle="collapse"
                                     data-parent="#accordion" href="#collapseOne" aria-expanded="false"
                                     aria-controls="collapseOne">
                                    <h4 class="panel-title">
                                        <a role="button">
                                            What qualifications do I need?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        The minimum qualifications and experience level for each expertise type is
                                        listed in the work
                                        eligibility section of the Find projects page. If your expertise type is not
                                        currently listed
                                        we’ll base the minimum assessment on the standard qualifications and experience
                                        levels
                                        sufficient for unsupervised work.
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading collapsed" role="tab" id="headingTwo" role="button"
                                     data-toggle="collapse"
                                     data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                     aria-controls="collapseTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed">
                                            How long does it take to review my application?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                        richardson ad
                                        squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food
                                        truck quinoa
                                        nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on
                                        it squid
                                        single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh
                                        helvetica, craft beer
                                        labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur
                                        butcher vice lomo.
                                        Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt
                                        you probably
                                        haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading collapsed" role="tab" id="headingThree" role="button"
                                     data-toggle="collapse"
                                     data-parent="#accordion" href="#collapseThree" aria-expanded="false"
                                     aria-controls="collapseThree">
                                    <h4 class="panel-title">
                                        <a class="collapsed">
                                            Do consultants need to be in the same country/region/city as the project?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingThree">
                                    <div class="panel-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                        richardson ad
                                        squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food
                                        truck quinoa
                                        nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on
                                        it squid
                                        single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh
                                        helvetica, craft beer
                                        labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur
                                        butcher vice lomo.
                                        Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt
                                        you probably
                                        haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

            </div>

        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!--Dashboard Sidebar/Profile Percentage-->
<!--  Add custom code by developer -->
<?php 
    $user = wp_get_current_user();
    $userEmailActive = get_user_meta($user->ID, 'register_status', true);
    $personalInfo = get_user_meta($user->ID, 'personal_information');
    $progress = 20;
    $class ='left-side half-circle 20_per';
    if ($userEmailActive !== 'unconfirm') {
        $progress += 20;
        $class ='half-circle fourty_per';
    }
    if (!empty($personalInfo)) {
        $progress += 20;
        $class ='half-circle sixty_per';
    }
    if ($progress === 40) {
        ?>
        <style>
            .pie-wrapper.progress-45 .pie .fourty_per {
                transform: rotate(144deg) !important;
            }
        </style>
        <?php
    }
     if ($progress === 60) {
        ?>
        <style>
            .pie-wrapper.progress-45 .pie .sixty_per {
                transform: rotate(175deg) !important;
            }
        </style>
        <?php
    }
?>
<!--  End code -->
<div class="col-md-4 col-sm-12 col-xs-12" id="right_content">

  
    <div class="prof-progress">
        <div class="set-size charts-container">

            <div class="pie-wrapper progress-45 style-2">
                <div role="progressbar"  aria-valuenow="<?php echo $progress ?>" aria-valuemin="0" aria-valuemax="100" style="--value:<?php echo $progress ?>"></div>
            </div>
            
            <h4 class="pull-left vc-heading">Profile progress</h4>


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

                /* demo */
               
                /*************************/

                .set-size {
                    font-size: 3em;
                }

                .charts-container:after {
                    clear: both;
                    content: "";
                    display: table;
                }

                .pie-wrapper {
                    height: 1em;
                    width: 1em;
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
                
            </style>
        </div>
        
        <!-- <div class="col overall-main">

            <div class="progress green ">
                <span class="progress-left pull-left">
                    <span class="progress-bar"></span>
                </span>
                <span class="progress-right">
                    <span class="progress-bar"></span>
                </span>
                <div class="progress-value"><?php echo $progress; ?>%</div>
            </div>
            <h4 class="pull-left vc-heading">Profile progress</h4>
        </div> -->
        <div class="bs-vertical-wizard">
            <ul>
                <li class="complete prev-step">
                    <a href="#">Sign up <span class="icon-container"><i class="ico fa fa-check ico-green"></i></span>
                        <!--                  <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span>-->
                    </a>
                </li>
                <?php
                if($userEmailActive !== 'unconfirm'){
                ?>
                    <li class="complete prev-step">
                        <a href="#">Email verification <span class="icon-container"><i class="ico fa fa-check ico-green"></i></span>
                        </a>
                    </li>
                     <?php
                     }else{
                    ?>
                    <li class="incomplete-s">
                        <a href="#">Email verification <span class="icon-container"><i class="ico ico-green"> 2 </i></span>
                        </a>
                    </li>
                    <?php
                     }
                    ?>
                    
                    <?php 
                    if (empty(!$personalInfo)) {
                    ?>
                        <li class="complete prev-step">
                            <a href="#">Join as a consultant <span class="icon-container"><i class="ico fa fa-check ico-green"></i></span>
                            </a>

                        </li>
                    
                    <?php 
                    }else{
                    ?>
                        <li class="incomplete-s">
                            <a href="#">Join as a consultant <span class="icon-container"><i class="ico ico-green"> 4 </i></span>
                            </a>
                            <div class="desc" style="margin-left:40px">
                                Consulting is not for everyone. We need to understand your motivations and vet your qualifications, experience, and work eligibility.
                            </div>
                            <?php if($userEmailActive !== 'unconfirm'){?>
                            <a href="<?php echo home_url('personal-information'); ?>" class="btn greenbtn" style="margin-left:40px">Apply Now</a>
                        <?php } ?>
                        </li>
                    <?php
                    }
                    ?>

                <li class="incomplete-s">
                    <a href="#">Profile approval <span class="icon-container"><i class="ico ico-green"> 4 </i></span>
                    </a>
                </li>
                <li class="incomplete-s">
                    <a href="<?php echo home_url('profile'); ?>">Complete your profile <span class="icon-container"><i class="ico ico-green"> 5 </i></span>

                    </a>
                </li>
            </ul>
        </div>
    </div>
</div><!-- RIGHT CONTENT -->
