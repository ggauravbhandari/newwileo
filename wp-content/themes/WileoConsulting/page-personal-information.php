<?php
/** * Template Name: Personal Information Page Template */
if (!is_user_logged_in()) {
    wp_redirect(home_url('login'));
    exit;
}

global $post;
get_header(); ?>
    <div class="fre-page-wrapper">
        <div class="fre-page-section">
            <div class="container sign-up-wrapper">
                <div class="fre-authen-wrapper">
                    <div class="wileo-page-logo-wrapper">
                        <svg width="118" height="42" viewBox="0 0 118 42" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M33.028 0.363159V31.1102C33.028 33.007 32.3274 34.7041 30.9262 36.0018C29.525 37.3994 27.9237 37.9984 25.922 37.9984C24.0204 37.9984 22.419 37.2996 21.0178 36.0018C20.5174 35.5027 20.1171 34.9037 19.7167 34.3047V0.363159H16.514V34.4046C16.2137 35.0035 15.7133 35.6025 15.2129 36.1017C13.8117 37.4993 12.2103 38.0982 10.2087 38.0982C8.20696 38.0982 6.6056 37.3994 5.20441 36.1017C3.80322 34.7041 3.10263 33.1068 3.10263 31.2101V0.363159H0V31.3099C0.100085 34.0053 1.10093 36.3013 3.00254 38.2979C5.00424 40.2944 7.40628 41.2927 10.3087 41.2927C13.1111 41.2927 15.6132 40.2944 17.6149 38.2979C17.8151 38.0982 18.0153 37.8986 18.2154 37.6989C18.4156 37.8986 18.6158 38.0982 18.8159 38.2979C20.8176 40.2944 23.2197 41.2927 26.1221 41.2927C28.9245 41.2927 31.4266 40.2944 33.4283 38.2979C35.3299 36.4011 36.3308 34.0053 36.4309 31.3099V0.363159H33.028Z"
                                  fill="#88C050"/>
                            <path d="M45.8388 7.85027C44.7378 6.85199 43.0364 6.85199 42.0355 7.85027C41.5351 8.34942 41.2349 9.04821 41.2349 9.74701C41.2349 10.5456 41.5351 11.1446 42.0355 11.6437C42.536 12.1429 43.2366 12.4424 43.9372 12.4424C44.6377 12.4424 45.3383 12.1429 45.8388 11.6437C46.3392 11.1446 46.6394 10.4458 46.6394 9.74701C46.6394 8.94839 46.3392 8.34942 45.8388 7.85027Z"
                                  fill="#465250"/>
                            <path d="M45.5385 15.637H42.3358V40.8935H45.5385V15.637Z" fill="#465250"/>
                            <path d="M55.647 0.363159H52.4443V40.8934H55.647V0.363159Z" fill="#465250"/>
                            <path d="M83.3706 34.0053L83.0704 34.3047C82.8702 34.6042 82.5699 34.9037 82.2697 35.1034C80.1679 37.1998 77.6658 38.198 74.6632 38.198C72.061 38.198 69.8592 37.3994 67.9576 35.8022L85.4724 18.3322L85.1721 17.9329C85.0721 17.7333 84.8719 17.6334 84.7718 17.4338C84.3715 17.0345 83.9711 16.6352 83.5708 16.3357C81.0687 14.3391 78.1662 13.3408 74.7633 13.3408C70.86 13.3408 67.5572 14.7384 64.7548 17.4338C61.9525 20.1291 60.6514 23.5233 60.6514 27.4166C60.6514 31.3099 62.0526 34.6042 64.7548 37.3994C67.4571 40.0948 70.86 41.4924 74.7633 41.4924C78.6666 41.4924 81.9694 40.0948 84.7718 37.3994C85.3723 36.8004 85.8727 36.2015 86.2731 35.6025L86.6734 35.1034L83.3706 34.0053ZM80.7684 18.6317L65.8558 33.5061C64.6548 31.809 64.0543 29.8125 64.0543 27.5164C64.0543 24.5216 65.1552 22.0259 67.1569 19.9295C69.2587 17.8331 71.7608 16.8348 74.7633 16.8348C76.9652 16.8348 78.9669 17.4338 80.7684 18.6317Z"
                                  fill="#465250"/>
                            <path d="M113.896 17.5336C111.194 14.8383 107.791 13.4407 103.888 13.4407C99.9845 13.4407 96.6817 14.8383 93.8794 17.5336C91.1771 20.229 89.7759 23.6232 89.7759 27.5165C89.7759 31.4098 91.1771 34.7041 93.8794 37.4993C96.5816 40.1946 99.9845 41.5922 103.888 41.5922C107.791 41.5922 111.094 40.1946 113.896 37.4993C116.599 34.8039 118 31.4098 118 27.5165C118 23.6232 116.599 20.3288 113.896 17.5336ZM114.697 27.5165C114.697 30.5113 113.596 33.007 111.594 35.1034C109.493 37.1998 106.99 38.1981 103.988 38.1981C100.985 38.1981 98.4833 37.1 96.3815 35.1034C94.2797 33.007 93.2788 30.5113 93.2788 27.5165C93.2788 24.5216 94.3798 22.0259 96.3815 19.9295C98.4833 17.8331 100.985 16.8348 103.988 16.8348C106.99 16.8348 109.493 17.9329 111.594 19.9295C113.596 22.0259 114.697 24.6214 114.697 27.5165Z"
                                  fill="#465250"/>
                            <path d="M103.788 18.532C103.088 18.532 102.387 18.6318 101.686 18.7316L100.385 19.0311L101.586 19.6301C102.187 19.9296 102.687 20.3289 103.188 20.9279L103.388 21.1275H103.588C105.389 21.0277 107.191 21.7265 108.492 23.0243C109.693 24.2222 110.394 25.9193 110.394 27.6164C110.394 29.3134 109.693 31.0105 108.492 32.2084C107.291 33.4064 105.69 34.1052 103.988 34.1052H103.688L103.588 34.3048C103.188 34.9038 102.787 35.403 102.287 35.7024L101.386 36.4012L102.587 36.6009C102.987 36.7007 103.488 36.7007 103.888 36.7007C108.993 36.7007 113.096 32.6078 113.096 27.5165C112.996 22.6249 108.892 18.532 103.788 18.532Z"
                                  fill="#88C050"/>
                            <path d="M104.989 22.3254L103.988 22.1257L104.389 23.0242C104.589 23.5233 104.789 24.1223 104.989 24.7213L105.089 24.9209L105.289 25.0208C106.29 25.5199 106.891 26.5182 106.891 27.6163C106.891 28.6146 106.39 29.6128 105.49 30.112L105.289 30.2118V30.4115C105.189 31.0104 104.989 31.6094 104.789 32.1086L104.489 33.007L105.389 32.7075C107.691 32.0087 109.293 29.9123 109.293 27.5165C109.193 25.0208 107.491 22.9244 104.989 22.3254Z"
                                  fill="#88C050"/>
                        </svg>
                    </div>
                    <div class="fre-authen-register"><a href="<?= home_url('register'); ?>" class="back-btn">
                            <svg width="20" height="20" viewBox="0 0 16 16" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.6405 3.3905L2.3905 7.6405L2.047 8L2.3905 8.3595L6.6405 12.6095L7.3595 11.8905L3.969 8.5H14V7.5H3.9685L7.3595 4.1095L6.6405 3.3905Z"
                                      fill="#88C050"/>
                            </svg>
                            Back </a>
                        <h2><?php _e('Tell us about yourself', ET_DOMAIN); ?></h2>
                        <div class="personal-info-form">              <?php
                            echo do_shortcode('[wpforms id="3850"]'); ?>            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();
