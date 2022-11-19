<!--Page Content-->
<?php
/*$user = wp_get_current_user();
$userEmailActive = get_user_meta($user->ID, 'register_status', true);
$progress = 10;
if($userEmailActive !== 'unconfirm'){
    $progress += 10;
}*/
?>
<div class="col-md-8 col-sm-12 col-xs-12" id="left_content">
    <div class="dashboard-content">
        <div class="db-header">
            <h1>Your account has been created</h1>
        </div>
        <div class="db-content">
            <div class="icon-container pull-left">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                            d="M19 3C13.488 3 9.00001 7.488 9.00001 13C9.00001 15.395 9.84001 17.59 11.25 19.313L3.28101 27.28L4.72001 28.72L12.688 20.751C14.4676 22.2099 16.6989 23.005 19 23C24.512 23 29 18.512 29 13C29 7.488 24.512 3 19 3ZM19 5C23.43 5 27 8.57 27 13C27 17.43 23.43 21 19 21C14.57 21 11 17.43 11 13C11 8.57 14.57 5 19 5Z"
                            fill="#88C050"/>
                    <path
                            d="M21.5186 11.1555C21.5186 12.5669 20.3744 13.7111 18.963 13.7111C17.5516 13.7111 16.4075 12.5669 16.4075 11.1555C16.4075 9.74414 17.5516 8.59998 18.963 8.59998C20.3744 8.59998 21.5186 9.74414 21.5186 11.1555Z"
                            stroke="#88C050" stroke-width="2"/>
                    <path
                            d="M13.5302 20.1481C14.4446 18.0552 16.533 16.5926 18.963 16.5926C21.393 16.5926 23.4814 18.0552 24.3958 20.1481"
                            stroke="#88C050" stroke-width="2"/>
                </svg>

            </div>
            <div class="cont-container pull-left">
                <h4>We are now verifying your identity</h4>
                <p>You should receive an email notification that your account has been verified <strong>within 1
                        business
                        day</strong>. Wileo will contact you if your account can’t be verified.</p>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-8">
                <div class="db-content">
                    <div class="icon-container pull-left">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M16 3C8.832 3 3 8.832 3 16C3 23.168 8.832 29 16 29C23.168 29 29 23.168 29 16C29 8.832 23.168 3 16 3ZM16 5C22.086 5 27 9.914 27 16C27 22.086 22.086 27 16 27C9.914 27 5 22.086 5 16C5 9.914 9.914 5 16 5ZM15 10V12H17V10H15ZM15 14V22H17V14H15Z"
                                    fill="#88C050"/>
                        </svg>


                    </div>
                    <div class="cont-container pull-left">
                        <h4>Why this is necessary</h4>
                        <p>Wileo does a quick information search to verify that you are a real person associated with an
                            organisation. This is to protect the information consultants share about themselves from
                            misappropriation or identity theft, as well as to prevent the bad faith solicitation of
                            proposals
                            from
                            them.
                            <br>
                            <br>
                            We also conduct a more thorough verification of consultants and their qualifications so that
                            clients
                            are confident that consultants legitimately possess the expertise, qualifications and
                            experience
                            levels designated on their profiles.
                        </p>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="db-content">
                    <div class="icon-container pull-left">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M18.719 6.78101L17.28 8.22001L24.063 15H4V17H24.063L17.281 23.781L18.719 25.219L27.219 16.719L27.906 16L27.219 15.281L18.719 6.78101Z"
                                    fill="#88C050"/>
                        </svg>


                    </div>
                    <div class="cont-container pull-left">
                        <h4>Next Step</h4>
                        <p>After verification, you can create projects and browse profiles but you will need to choose a
                            billing
                            method and complete your profile with a short organisation description before you can submit
                            projects
                            to the platform.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/db-image-mp.png" alt="">

            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<?php
    $user = wp_get_current_user();
    $userEmailActive = get_user_meta($user->ID, 'register_status', true);
    $personalInfo = get_user_meta($user->ID, 'personal_information');
    $progress = 20;
    if ($userEmailActive !== 'unconfirm') {
        $progress += 10;
    }
?>
<!--Dashboard Sidebar/Profile Percentage-->
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
                    <?php if($userEmailActive !== 'unconfirm'){
                        echo 'clip: rect(0, 0.7em, 1em, 0);';
                    }else{
                       echo 'clip: rect(0, 0.5em, 1em, 0);';
                    }?>

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
                    transform: rotate(36deg);
                }

                .pie-wrapper.progress-45 .pie .right-side {
                    display: none;
                }
            </style>
        </div>
        <div class="bs-vertical-wizard">

            <ul>
                <li class="complete">
                    <a href="#">Sign up <span class="icon-container"><i class="ico fa fa-check ico-green"></i></span>
                        <!--                  <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span>-->
                    </a>
                </li>
                <?php if ($userEmailActive === 'unconfirm') {
                    ?>
                    <li class="current">
                        <a href="#">Email Confirmation <span class="icon-container"><i
                                        class="ico ico-green"> 2 </i></span>
                                              <span class="desc">Please check your email and confirm your email.</span>
                        </a>
                    </li>
                    <li class="incomplete-s">
                        <a href="#">Account verification <span class="icon-container"><i
                                        class="ico ico-green"> 3 </i></span>
                            <!--                  <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span>-->
                        </a>
                    </li>
                    <?php
                } else {
                    ?>
                    <li class="complete">
                        <a href="#">Email Confirmation <span class="icon-container"><i
                                        class="ico fa fa-check ico-green"></i></span>
                            <!--                  <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span>-->
                        </a>
                    </li>
                    <li class="current">
                        <a href="#">Account verification <span class="icon-container"><i
                                        class="ico ico-green"> 3 </i></span>
                            <!--                  <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span>-->
                        </a>
                    </li>
                    <?php
                } ?>
                <li class="incomplete-s">
                    <a href="#">Complete your profile <span class="icon-container"><i
                                    class="ico ico-green"> 4 </i></span>
                        <!--                  <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span>-->
                    </a>
                </li>
                <li class="incomplete-s">
                    <a href="#">Post a project <span class="icon-container"><i class="ico ico-green"> 5 </i></span>
                        <!--                  <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span>-->
                    </a>
                </li>
                <li class="incomplete-s">
                    <a href="#">Hire consultants <span class="icon-container"><i class="ico ico-green"> 6 </i></span>
                        <!--                  <span class="desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, cumque.</span>-->
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div><!-- RIGHT CONTENT -->
