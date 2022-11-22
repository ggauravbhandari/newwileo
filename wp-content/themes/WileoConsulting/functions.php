<?php
/*This file is part of WileoConsulting, freelanceengine child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

if (!function_exists('suffice_child_enqueue_child_styles')) {
    function WileoConsulting_enqueue_child_styles()
    {
        // loading parent style
        wp_register_style(
            'parente2-style',
            get_template_directory_uri() . '/style.css'
        );

        wp_enqueue_style('parente2-style');
        // loading child style
        wp_register_style(
            'childe2-style',
            get_stylesheet_directory_uri() . '/style.css'
        );
        wp_enqueue_style('childe2-style');
    }
}
//add_action('wp_enqueue_scripts', 'WileoConsulting_enqueue_child_styles');

/*Write here your own functions */

/**
 * This will fire after successful signup form submission.
 *
 * @link  https://wpforms.com/developers/wpforms_process_complete/
 *
 * @param array $fields Sanitized entry field values/properties.
 * @param array $entry Original $_POST global.
 * @param array $form_data Form data and settings.
 * @param int $entry_id Entry ID. Will return 0 if entry storage is disabled or using WPForms Lite.
 */

function wpf_signup_process_complete($fields, $entry, $form_data)
{
    $ae_users = AE_Users::get_instance();
    add_filter('ae_define_user_meta', 'addNewUserMeta', 10, 3);
    if (absint($form_data['id']) === 2662) {

        if (!email_exists($entry['fields'][10])) {

            $password = $entry['fields'][8]['primary'];
            // Validate password strength
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                wpforms()->process->errors[$form_data['id']] [8] = esc_html__('Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.', ET_DOMAIN);
            } else {
                $username = explode('@', $entry['fields'][10]);
                $data = [
                    'user_login' => $username[0],
                    'user_pass' => $password,
                    'user_email' => $entry['fields'][10],
                    'first_name' => $entry['fields'][1],
                    'last_name' => $entry['fields'][2],
                    'display_name' => $entry['fields'][1] . ' ' . $entry['fields'][2],
                    'role' => $entry['fields'][9],
                    'phone' => $entry['fields'][4],
                    'location' => $entry['fields'][13],
                    'company_name' => $entry['fields'][3],
                    'method' => 'create'
                ];
                $ae_users->sync($data);
            }
        } else {
            wpforms()->process->errors[$form_data['id']] [10] = esc_html__('User already have account with this email.', ET_DOMAIN);
        }
    } elseif (absint($form_data['id']) === 2663) {
        if (!email_exists($entry['fields'][12])) {

            $password = $entry['fields'][8]['primary'];
            // Validate password strength
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                wpforms()->process->errors[$form_data['id']] [8] = esc_html__('Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.', ET_DOMAIN);
            } else {
                $username = explode('@', $entry['fields'][12]);

                $data = [
                    'user_login' => $username[0],
                    'user_pass' => $password,
                    'user_email' => $entry['fields'][12],
                    'first_name' => $entry['fields'][1],
                    'last_name' => $entry['fields'][2],
                    'display_name' => $entry['fields'][1] . ' ' . $entry['fields'][2],
                    'role' => $entry['fields'][9],
                    'phone' => $entry['fields'][4],
                    'location' => $entry['fields'][10],
                    'city' => $entry['fields'][11],
                    'method' => 'create'
                ];
                $ae_users->sync($data);
            }
        } else {
            wpforms()->process->errors[$form_data['id']] [12] = esc_html__('User already have account with this email.', ET_DOMAIN);
        }
    }
}

add_action('wpforms_process', 'wpf_signup_process_complete', 10, 4);
/*
 * addNewUserMeta Extend default theme user meta*/
function addNewUserMeta($metaData)
{
    $metaData = wp_parse_args($metaData, 'city');
    $metaData = wp_parse_args($metaData, 'company_name');
    return $metaData;
}

function wpf_personal_information_process_complete($fields, $entry, $form_data, $entry_id)
{
    $userId = get_current_user_id();
    if ($userId !== 0) {
        update_user_meta($userId, 'personal_information', $fields);
    }
}

add_action('wpforms_process_complete_3850', 'wpf_personal_information_process_complete', 10, 4);

/*
 * Custom Login function for front-end
 */
add_action('init', function () {

    // not the login request?
    if (!isset($_POST['action']) || $_POST['action'] !== 'login_action')
        return;
    // see the codex for wp_signon()
    $result = wp_signon();

    if (is_wp_error($result))
        wp_die('Login failed. Wrong password or user name?');

    // redirect back to the requested page if login was successful
    wp_redirect(home_url('dashboard'));
    exit;
});

/*
 * Reset Password email text update
 */
add_filter('retrieve_password_message', 'my_retrieve_password_message', 10, 4);
/**
 * @param $message
 * @param $key
 * @param $user_login
 * @param $user_data
 * @return array|string|string[]
 */
function my_retrieve_password_message($message, $key, $user_login, $user_data)
{

    $message = ae_get_option('forgotpass_mail_template');

    $activate_url = add_query_arg(array(
        'user_login' => $user_login,
        'key' => $key
    ), et_get_page_link('reset-pass'));

    $activate_url = '<a href="' . $activate_url . '">' . __("Activate Link", ET_DOMAIN) . '</a>';
    $message = str_ireplace('[activate_url]', $activate_url, $message);
    $message = str_ireplace('[display_name]', $user_data->data->display_name, $message);
    $site_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $message = str_ireplace('[blogname]', $site_name, $message);
    return $message;

}

function new_ae_user_role($user_ID)
{
    // get user id 's role
    if ($user_ID != '') {
        $user_info = get_userdata($user_ID);
    } else {
        // get current user role
        global $user_ID;
        $user_info = get_userdata($user_ID);
    }
    // if user exist
    if ($user_info) {
        $roles = $user_info->roles;
        if (array_search(FREELANCER, $roles) !== false) {
            return FREELANCER;
        } elseif (array_search(EMPLOYER, $roles) !== false) {
            return EMPLOYER;
        }

        return array_pop($roles);
    }

    // user not exist or not logged in
    return '';
}

/*
 * Convert WP email to HTML
 */
/**
 * @return string
 */
function wpse27856_set_content_type()
{
    return "text/html";
}

add_filter('wp_mail_content_type', 'wpse27856_set_content_type');

function changeFieldDontDisplay($dont_display)
{
    $dont_display[] = 'password';
    return $dont_display;
}

add_filter('wpforms_pro_admin_entries_edit_fields_dont_display', 'changeFieldDontDisplay', 10, 1);
function changeFieldDontDisplaySingle($field_value, $field, $form_data, $entry_type)
{
    if ($entry_type === 'entry-single' && $field['type'] === 'password') {
        return '*************';
    }

    return $field_value;
}

add_filter('wpforms_html_field_value', 'changeFieldDontDisplaySingle', 10, 4);
function changeFieldDontExport($config)
{
    /*$index = array_search('password', $config['disallowed_fields']);
    if ($index === false) {
        $config['disallowed_fields'][] = 'password';
    }*/
    file_put_contents(dirname(__FILE__) . '/testing.txt', var_export($config, true));
    //$config['disallowed_fields'][] = 'password';
    return $config;
}

add_filter('wpforms_export_fields_allowed', 'changeFieldDontExport', 10, 1);

add_action('get_footer', 'pre_footer_frontjs_templates');

/**
 * Function load js template in footer
 * @return load templates
 */
function pre_footer_frontjs_templates()
{
    $path = get_template_directory();
    require_once $path . '/template-js/post-item.php';
    require_once $path . '/template-js/project-item.php';
    require_once $path . '/template-js/portfolio-item.php';
    require_once $path . '/template-js/profile-item.php';
    require_once $path . '/template-js/report-item.php';
    require_once $path . '/template-js/skill-item.php';
    require_once $path . '/template-js/notification-template.php';
    require_once $path . '/template-js/user-bid-item.php';
    require_once $path . '/template-js/work-history-item.php';
    require_once $path . '/template-js/bid-item.php';
    require_once $path . '/template-js/modal-review.php';
    require_once $path . '/template-js/modal-bid.php';
    require_once $path . '/template-js/modal-can-not-bid.php';
    require_once $path . '/template-js/modal-transfer-money.php';
    require_once $path . '/template-js/message-item.php';
    /*require_once $path . '/template-js/modal-add-portfolio.php';*/
    require_once $path . '/template-js/modal-change-pass.php';
    require_once $path . '/template-js/modal-contact.php';
    require_once $path . '/template-js/modal-delete-file.php';
    require_once $path . '/template-js/modal-delete-meta-history.php';
    require_once $path . '/template-js/modal-delete-portfolio.php';
    require_once $path . '/template-js/modal-edit-portfolio.php';
    require_once $path . '/template-js/modal-forgot-pass.php';
    require_once $path . '/template-js/modal-lock-file.php';
    require_once $path . '/template-js/modal-review.php';
    require_once $path . '/template-js/modal-upload-avatar.php';
    require_once $path . '/template-js/modal-view-portfolio.php';
    require_once $path . '/template-js/freelancer-current-project-item.php';
    require_once $path . '/template-js/freelancer-previous-project-item.php';
    require_once $path . '/template-js/modal-arbitrate.php';
    require_once $path . '/template-js/modal-accept-bid-no-escrow.php';
    //require_once $path . '/template-js/modal-accept-bid.php';
    require_once $path . '/template-js/modal-bid.php';
    require_once $path . '/template-js/modal-invite.php';
    require_once $path . '/template-js/modal-cancel-bid.php';
    require_once $path . '/template-js/modal-can-not-bid.php';
    require_once $path . '/template-js/employer-current-project-item.php';
    require_once $path . '/template-js/employer-previous-project-item.php';
    require_once $path . '/template-js/modal-approve-project.php';
    require_once $path . '/template-js/modal-remove-bid.php';
    require_once $path . '/template-js/modal-reject.php' ;
    require_once $path . '/template-js/modal-reject-project.php' ;


    /* ADD SAME FILE FROM CHOLD THEME */
    $child_path = get_stylesheet_directory();
    require_once $child_path . '/template-js/modal-accept-bid.php';
    require_once $child_path . '/includes/fre_credit/class-credit-users-extends-child.php';

    echo do_shortcode('[load-user-script-tag]');
    echo do_shortcode('[extraModals]');
}



//add_action('get_headers', 'pre_header_frontjs', 100);

function pre_header_frontjs()
{
    global $user_ID;
    if ($user_ID) {
        echo '
    <script type="data/json" id="user_id">' . json_encode(array(
                'id' => $user_ID,
                'ID' => $user_ID
            )) . '
    </script>';
    }
}

add_shortcode('logout-link', 'logOutLink');

/**
 * Function add user dropdown menu for login users
 * @return string
 */
function logOutLink()
{
    global $current_user, $user_ID;
    ?>
    <div class="fre-account-wrap dropdown">
        <div class="fre-account dropdown">
            <div class="fre-account-info dropdown-toggle" data-toggle="dropdown">
                <?php echo get_avatar($user_ID); ?>
                <span><?php //echo $current_user->display_name;
                    ?></span>
                <i class="fa fa-caret-down" aria-hidden="true"></i>
            </div>
            <ul class="dropdown-menu">
                <li>
                    <a href="<?php echo home_url("profile") ?>"><?php _e('MY PROFILE', ET_DOMAIN); ?></a>
                </li>
                <li>
                    <a href="<?php echo home_url("profile-settings") ?>"><?php _e('SETTINGS', ET_DOMAIN); ?></a>
                </li>
                <?php do_action('fre_header_before_notify'); ?>
                <li><a href="<?php echo wp_logout_url(); ?>"><?php _e('LOGOUT', ET_DOMAIN); ?></a></li>
            </ul>
        </div>
    </div>
    <?php
}

add_shortcode('change-profile-image', 'changeProfileImage');
function changeProfileImage()
{
    $path = get_stylesheet_directory();
    require_once $path . '/profile-info-block.php';

    wp_enqueue_script('cropper-js', get_template_directory_uri() . '/assets/js/cropper.min.js', array(

        'jquery',

        'underscore',

        'backbone',

        'appengine',

        'front'

    ), ET_VERSION, true);
    wp_enqueue_script('profile', get_template_directory_uri() . '/assets/js/profile.js', array(

        'jquery',

        'underscore',

        'backbone',

        'appengine',

        'front'

    ), ET_VERSION, true);

}

add_shortcode('change-password', 'changePassword');
/**
 * Function is used to update user password
 * @return change password template
 */
function changePassword()
{
    $path = get_template_directory();
    require_once $path . '/template-js/modal-change-pass.php';
    wp_enqueue_script('fre-lib', get_template_directory_uri() . '/assets/js/fre-lib.js', array(), ET_VERSION, true);
    wp_enqueue_script('front', get_template_directory_uri() . '/assets/js/front.js', array(

        'jquery',

        'underscore',

        'backbone',

        'appengine',

        'front'

    ), ET_VERSION, true);
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/assets/js/custom-script.js', array(

        'jquery',

        'underscore',

        'backbone',

        'appengine',

        'front'

    ), ET_VERSION, true);
}

add_shortcode('user-profile-visibility', 'userProfileVisibility');

/**
 * Function is used to update user profile visibility
 * @return update user profile visibility
 */
function userProfileVisibility()
{
    $path = get_template_directory();
    require_once $path . '/template-js/user_profile_visibility.php';
    wp_enqueue_script('fre-lib', get_template_directory_uri() . '/assets/js/fre-lib.js', array(), ET_VERSION, true);
    wp_enqueue_script('front', get_template_directory_uri() . '/assets/js/front.js', array(

        'jquery',

        'underscore',

        'backbone',

        'appengine',

        'front'

    ), ET_VERSION, true);
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/assets/js/custom-script.js', array(

        'jquery',

        'underscore',

        'backbone',

        'appengine',

        'front'

    ), ET_VERSION, true);
}

add_shortcode('identification-form', 'submitIdentification');

/**
 * Identification form form short code
 * @return string
 */
function submitIdentification()
{
    echo do_shortcode('[wpforms id="4426" title="false"]');
}

/**
 * Load User script tag
 * @return string
 */
function loadUserScriptTag()
{
    global  $ae_post_factory, $user_ID;
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
    if ($profile_id && $profile_post && !is_wp_error($profile_post)) { ?>
        <script type="data/json" id="current_profile">
    <?php echo json_encode($profile) ?>


        </script>
    <?php }
}

add_shortcode('load-user-script-tag', 'loadUserScriptTag');

function wileo_no_breadcrumb ($arg) {
	return true;
}

add_filter('bbp_no_breadcrumb', 'wileo_no_breadcrumb');

/**
 * Logout user if he/she is banned.
 */
function logout_banned_user(){
    $user = wp_get_current_user();
    if (is_a($user, 'WP_User') && !in_array('administrator', $user->roles)) {
        $banned = get_user_meta($user->ID, 'banned', true);
        if ($banned == 1) {
            wp_clear_auth_cookie();
            wp_redirect(site_url('/login/'));
            exit();
        }
    }
}
add_action('template_redirect', 'logout_banned_user');



/**
 * Limit the dates available in the Date Time date picker to anything within last 18 years.
 *
 */
function wpf_dev_limit_date_picker_years() {
    ?>
    <script type="text/javascript">
        var d = new Date();
        window.wpforms_datepicker = {
            disableMobile: true,
            // Don't allow users to pick dates less than 18 years ago
            maxDate: d.setDate( d.getDate() - 6574 ),
        }
    </script>
    <?php
}
//add_action( 'wpforms_wp_footer_end', 'wpf_dev_limit_date_picker_years' );
add_shortcode('extraModals', 'quiteModal');
function quiteModal(){
    ?>
    <!-- MODAL QUIT PROJECT-->
    <div class="modal fade" id="quit_project" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                    <h4 class="modal-title"><?php _e( "Discontinue project", ET_DOMAIN ) ?></h4>
                </div>
                <div class="modal-body">
                    <form role="form" id="quit_project_form" class="quit_project_form fre-modal-form">
                        <p class="notify-form">
                            <?php _e( "This project will be marked as disputed and your case will have resulted soon by admin. Please provide as many as proofs and statement explaining why you quit the project.", ET_DOMAIN ); ?>
                        </p>
                        <p class="notify-form">
                            <?php _e( "Workspace is still available for you to access in case of necessary.", ET_DOMAIN ); ?>
                        </p>
                        <input type="hidden" id="project-id" value="">
                        <div class="fre-input-field">
                            <label class="fre-field-title"
                                   for="comment-content"><?php _e( 'Provide us the reason why you quit:', ET_DOMAIN ) ?></label>
                            <textarea id="comment-content" name="comment_content"></textarea>
                        </div>
                        <div class="fre-form-btn">
                            <button type="submit" class="fre-normal-btn btn-submit">
                                <?php _e( 'Discontinue', ET_DOMAIN ) ?>
                            </button>
                            <span class="fre-form-close" data-dismiss="modal"><?php _e( 'Cancel', ET_DOMAIN ); ?></span>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog login -->
    </div><!-- /.modal -->
    <!--// MODAL QUIT PROJECT-->
    <!-- MODAL CLOSE PROJECT-->
    <div class="modal fade" id="close_project_success" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content-close-wrapper">
                        <p class="alert-close-text">
                            <?php _e( "We will review the reports from both freelancer and employer to give the best decision. It will take 3-5 business days for reviewing after receiving two reports.", ET_DOMAIN ) ?>
                        </p>
                        <button type="submit" class="btn btn-ok">
                            <?php _e( 'OK', ET_DOMAIN ) ?>
                        </button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog login -->
    </div><!-- /.modal -->
    <!--// MODAL CLOSE PROJECT-->
    <?php
}

/* add custome code by developer to get project type of project by post id */
    function fre_getProjectPostType($postID){
        $term_obj_name = wp_get_post_terms($postID, 'project_type',  array("fields" => "all"));
        return $term_obj_name[0];
    }
   
/* End code */


if (!function_exists('wileo_modify_user_columns')) { 
    function wileo_modify_user_columns($column_headers) {
        $column_headers['user_status_cs'] = 'Status';
        return $column_headers;
    }
}


if (!function_exists('wileo_modify_user_columns')) {
    function custom_admin_css() {
        echo '<style>
        .column-custom_field {width: 8%}
        </style>';
    }
}

if (!function_exists('wileo_add_user_column_data')) {
    function wileo_add_user_column_data($value, $column_name, $user_id) {
        $user = get_userdata($user_id);
    
        switch ($column_name) {
            case 'user_status_cs' :
                return ($user->user_status_cs && $user->user_status_cs == 'Approved') ? 'Approved' : (($user->user_status_cs && $user->user_status_cs == 'Disapproved') ? 'Disapproved' : 'Pending') ;
                break;
            default:
                return $value;
                break;
        }
        return $value;
    }
}

add_action('manage_users_columns', 'wileo_modify_user_columns');
add_action('admin_head', 'custom_admin_css');
add_action('manage_users_custom_column', 'wileo_add_user_column_data', 10, 3);

//hook to use after user is approved by admin
add_action( 'profile_update' , 'wileo_user_status_updated', 10, 3);

if (!function_exists('wileo_user_status_updated')) {
    
    function wileo_user_status_updated( $user_id, $old_user_data, $userdata ) {
        
        $old_user_status = $old_user_data->data->user_status;
     
        $user = get_userdata( $user_id );
        $new_user_status = $user->user_status_cs;
     
        if ( $old_user_status == 0 && $new_user_status == 'Approved') {
            $subject = __( "Congratulations! Your account has been activated successfully.", ET_DOMAIN );
            $content = ae_get_option( 'confirmed_mail_template' );
            
            $content = str_ireplace( '[display_name]', $user->data->display_name, $content );
		    $content = str_ireplace( '[blogname]', get_bloginfo( 'name' ), $content );
		    
		    $headers = "From: " . get_option( 'blogname' ) . " < " . get_option( 'admin_email' ) . "> \r\n";
		    
    		$headers = apply_filters( 'ae_mail_header_info', $headers );


            wp_mail( $user->user_email, $subject, $content, $headers);
        }
    }
}

add_action( 'wp_loaded', 'parent_prefix_load_classes', 10 );

function parent_prefix_load_classes()
{
    $classes = [ 'AE_Options' => 'includes/aecore/class-options','FRE_Credit_Users' =>'includes/fre_credit/class-credit-users' ];

    foreach ( $classes as $class => $value )
    {
        locate_template( "$value.php", TRUE, TRUE );
    }
}


function remove_file_upload() {
    remove_action('wp_ajax_ae_upload_files', 'fre_upload_file', 3);
}

add_action('init','remove_file_upload');

// Add our custom function to the 'thematic_header' phase
add_action('wp_ajax_ae_upload_files','wileo_upload_file', 3);

if (!function_exists('wileo_upload_file')) {
    function wileo_upload_file() {
        $res = array(
            'success' => false,
            'msg'     => __( 'There is an error occurred', ET_DOMAIN ),
            'code'    => 400,
        );
    
        // check fileID
        if ( ! isset( $_POST['fileID'] ) || empty( $_POST['fileID'] ) ) {
            $res['msg'] = __( 'Missing image ID', ET_DOMAIN );
        } else {
            $fileID     = $_POST["fileID"];
            $imgType    = $_POST['imgType'];
            $project_id = $_POST['project_id'];
            $author_id  = $_POST['author_id'];
    
            $project_meta = get_post_meta( $project_id );
    
            if ( $imgType == 'file' && isset($project_meta['lock_status']) &&  $project_meta['lock_status'] == 'lock') {
                $res['msg'] = __( 'You cannot upload a new file since partner locked this section. Please refresh the page.', ET_DOMAIN );
            } else {
                // check ajax nonce
                if ( ! de_check_ajax_referer( 'file_et_uploader', false, false ) && ! check_ajax_referer( 'file_et_uploader', false, false ) ) {
                    $res['msg'] = __( 'Security error!', ET_DOMAIN );
                } elseif ( isset( $_FILES[ $fileID ] ) ) {
    
                    // handle file upload
                    $attach_id = et_process_file_upload( $_FILES[ $fileID ], 0, 0, array(
                        'jpg|jpeg|jpe'     => 'image/jpeg',
                        'gif'              => 'image/gif',
                        'png'              => 'image/png',
                        'bmp'              => 'image/bmp',
                        'tif|tiff'         => 'image/tiff',
                        'pdf'              => 'application/pdf',
                        'doc'              => 'application/msword',
                        'docx'             => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'odt'              => 'application/vnd.oasis.opendocument.text',
                        'zip'              => 'application/zip',
                        'rar'              => 'application/rar',
                        'xla|xls|xlt|xlw|' => 'application/vnd.ms-excel',
                        'xlsx'             => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'gz|gzip'          => 'application/x-gzip',
                    ) );
    
                    if ( ! is_wp_error( $attach_id ) ) {
    
                        try {
                            $attach_data = et_get_attachment_data( $attach_id );
    
                            $options = AE_Options::get_instance();
                            global $current_user;
                            $comment_id = wp_insert_comment( array(
                                'comment_post_ID'      => $project_id,
                                'comment_author'       => $current_user->data->user_login,
                                'comment_author_email' => $current_user->data->user_email,
                                'comment_content'      => sprintf( __( "%s has successfully uploaded a file", ET_DOMAIN ), $current_user->data->display_name ),
                                'comment_type'         => 'message',
                                'user_id'              => $current_user->data->ID,
                                'comment_approved'     => 1
                            ) );
                            $file_arr   = array( $attach_id );
                            if ( $imgType == 'file' ) {
                                update_comment_meta( $comment_id, 'fre_comment_file', $file_arr );
                            } else if ( $imgType == 'attach' ) {
                                update_comment_meta( $comment_id, 'fre_comment_file_attach', $file_arr );
                            }
                            update_post_meta( $attach_id, 'comment_file_id', $comment_id );
    
                            global $user_ID; $val = 1;
                            // $project = get_post( $project_id );
                            // Fre_MessageAction::fre_update_project_meta( $project );   version 1.8.14 and prev
                            if ( $user_ID == $author_id ) {
                                update_post_meta( $project_id, 'fre_freelancer_new_msg', $val );
                            } else {
                                update_post_meta( $project_id, 'fre_employer_new_msg', $val );
                            }
                            
                            // save this setting to theme options
                            // $options->$imgType = $attach_data;
                            // $options->save();
                            /**
                             * do action to control how to store data
                             *
                             * @param $attach_data the array of image data
                             * @param $request ['data']
                             * @param $attach_id the uploaded file id
                             */
    
                            //do_action('ae_upload_image' , $attach_data , $_POST['data'], $attach_id );
                            $attachment             = get_post( $attach_id );
                            $attachment->post_date  = get_the_date( 'F j, Y g:i A', $attachment->ID );
                            $attachment->project_id = $project_id;
                            $attachment->comment_id = $comment_id;
                            $attachment->avatar     = get_avatar( $author_id );
                            $attachment->file_size  = size_format( filesize( get_attached_file( $attachment->ID ) ) );
                            $file_type              = wp_check_filetype( get_attached_file( $attachment->ID ) );
                            $attachment->file_type  = $file_type['ext'];
                            $res                    = array(
                                'success'    => true,
                                'msg'        => __( 'File has been uploaded successfully', ET_DOMAIN ),
                                'data'       => $attach_data,
                                'attachment' => $attachment
                            );
                            
                            $bid_id = $project_meta['accepted'][0];
                            
                            $client_id = get_post_field( 'post_author', $project_id );
                            $freelancer_id = get_post_field( 'post_author', $bid_id );
                            
                            
                            $user_id_email_send = ($user_ID == $client_id) ? $freelancer_id : $client_id;
                            
                            $emailUser = get_userdata( $user_id_email_send );
                            $project_title = get_the_title($project_id);
                            
                            $subject = __( "File is added in Project: .".$project_title, ET_DOMAIN );
                            $content = '
                            <p>Hello [display_name],</p>
                            <p>A new document has been uploaded for project : [title].</p>
                            <p>Sincerely,</p>
                            <p>[blogname]</p>
                            ';
                            
                            $content = str_ireplace( '[display_name]', $emailUser->data->display_name, $content );
                            $content = str_ireplace( '[title]', $project_title, $content );
                		    $content = str_ireplace( '[blogname]', get_bloginfo( 'name' ), $content );
                		    
                		    $headers = "From: " . get_option( 'blogname' ) . " < " . get_option( 'admin_email' ) . "> \r\n";
                		    
                    		$headers = apply_filters( 'ae_mail_header_info', $headers );
                    		

                           wp_mail( $emailUser->user_email, $subject, $content, $headers);
                        } catch ( Exception $e ) {
                            $res['msg'] = __( 'Error when updating settings.', ET_DOMAIN );
                        }
                    } else {
                        $res['msg'] = $attach_id->get_error_message();
                    }
                } else {
                    $res['msg'] = __( 'Uploaded file not found', ET_DOMAIN );
                }
            }
        }
    
        // send json to client
        wp_send_json( $res );
    }
}

/* Add modify parent theme function in child theme 02-07-2022*/
function remove_fre_get_accept_bid_info() {
    remove_action('wp_ajax_ae-accept-bid-info', 'fre_get_accept_bid_info', 3);
}
add_action('init','remove_fre_get_accept_bid_info');

/**
 * ajax callback to setup bid info and send to client
 * @author Dakachi
 */
function fre_get_accept_bid_info_child() {
    $bid_id = $_GET['bid_id'];
    global $user_ID;

    $post_id     = get_post_field('post_parent', $bid_id);
    $term_obj_list = wp_get_post_terms($post_id, 'project_type',  array("fields" => "all"));

    $error = array(
        'success' => false,
        'msg' => __('Invalid bid', ET_DOMAIN)
    );
    if (!isset($_REQUEST['bid_id'])) {
        wp_send_json($error);
    }
    $bid_id = $_REQUEST['bid_id'];
    $bid = get_post($bid_id);

    // check bid is valid
    if (!$bid || is_wp_error($bid) || $bid->post_type != BID) {
        wp_send_json($error);
    }

    if(!empty($term_obj_list[0]->slug) && $term_obj_list[0]->slug == 'time-based'){
        $bid_budget = 0;

        // get commission settings
        $commission = 0;
        $commission_fee = $commission;

        // caculate commission fee by percent
        $commission_type = ae_get_option('commission_type');
        if ($commission_type != 'currency') {
            $commission_fee = 0;
        }

        $commission = 0;
        $payer_of_commission = ae_get_option('payer_of_commission', 'project_owner');
        if ($payer_of_commission == 'project_owner') {
            $total = 0;
        }
        else {
            $commission = 0;
            $total = 0;
        }
        $number_format = ae_get_option('number_format');
        $decimal = (isset($number_format['et_decimal'])) ? $number_format['et_decimal'] : get_theme_mod('et_decimal', 2);
        $data = array(
            'budget'=>0,
            'commission'=>0,
            'total'=>0
            );
        $data = apply_filters( 'ae_accept_bid_infor', $data);
        wp_send_json(array(
            'success' => true,
            'data' => array(
                'budget' => fre_price_format($data['budget']) ,
                'commission' => $data['commission'],
                'total' => fre_price_format($data['total']),
                'data_not_format' => $data
            )
        ));
    }else{
        $bid_budget = get_post_meta($bid_id, 'bid_budget', true);

        // get commission settings
        $commission = ae_get_option('commission', 0);
        $commission_fee = $commission;

        // caculate commission fee by percent
        $commission_type = ae_get_option('commission_type');
        if ($commission_type != 'currency') {
            $commission_fee = ((float)($bid_budget * (float)$commission)) / 100;
        }

        $commission = fre_price_format($commission_fee);
        $payer_of_commission = ae_get_option('payer_of_commission', 'project_owner');
        if ($payer_of_commission == 'project_owner') {
            $total = (float)$bid_budget + (float)$commission_fee;
        }
        else {
            $commission = 0;
            $total = $bid_budget;
        }
        $number_format = ae_get_option('number_format');
        $decimal = (isset($number_format['et_decimal'])) ? $number_format['et_decimal'] : get_theme_mod('et_decimal', 2);
        $data = array(
            'budget'=>$bid_budget,
            'commission'=>$commission,
            'total'=>round((double)$total, $decimal)
            );
        $data = apply_filters( 'ae_accept_bid_infor', $data);
        wp_send_json(array(
            'success' => true,
            'data' => array(
                'budget' => fre_price_format($data['budget']) ,
                'commission' => $data['commission'],
                'total' => fre_price_format($data['total']),
                'data_not_format' => $data
            )
        ));
    }

    
}
add_action('wp_ajax_ae-accept-bid-info', 'fre_get_accept_bid_info_child');


/**
 * ajax callback process bid escrow and send redirect url to client
 * This method run after employer accept project for a freelancer.
 * @author Dakachi
 */

function remove_fre_escrow_bid() {
    remove_action('wp_ajax_ae-escrow-bid', 'fre_escrow_bid', 3);
}
add_action('init','remove_fre_escrow_bid');
/* We made function to call in our module*/
function fre_escrow_bid_child() {
   
    global $user_ID;
    $error = array(
        'success' => false,
        'msg' => __('Invalid bid', ET_DOMAIN)
    );
    if (!isset($_REQUEST['bid_id'])) {
        wp_send_json($error);
    }
    $bid_id = $_REQUEST['bid_id'];
    $bid = get_post($bid_id);


    // check bid is valid
    if (!$bid || is_wp_error($bid) || $bid->post_type != BID) {
        wp_send_json($error);
    }


    // currency settings
    $currency = ae_get_option('currency');
    $currency = $currency['code'];

    $bid_budget = get_post_meta($bid_id, 'bid_budget', true);

    // get commission settings
    $commission = ae_get_option('commission', 0);
    $commission_fee = $commission;

    // caculate commission fee by percent
    $commission_type = ae_get_option('commission_type');
    if ($commission_type != 'currency') {
        $commission_fee = ((float)($bid_budget * (float)$commission)) / 100;
    }
    $payer_of_commission = ae_get_option('payer_of_commission', 'project_owner');

    if ($payer_of_commission == 'project_owner') {
        $total = (float)$bid_budget + (float)$commission_fee;
    }
    else {
        $total = $bid_budget;
        $bid_budget = (float)$total - (float)$commission_fee;
    }

    // get URL Project
    $post_id     = get_post_field('post_parent', $bid_id);
    $post_url  = get_permalink( $post_id );
    $escrow_data = array(
        'total'=> $total,
        'currency'=>$currency,
        'bid_budget'=> $bid_budget,
        'commission_fee'=> $commission_fee,
        'payer_of_commission'=>$payer_of_commission,
        'bid_author'=> $bid->post_author,
        'bid_id' => $bid_id
        );

    do_action('ae_escrow_payment_gateway', $escrow_data);

    //  when using escrow, employer must setup an paypal account
    $paypal_account = get_user_meta($user_ID, 'paypal', true);
    if(!$paypal_account){
        wp_send_json(array(
            'success'   => false,
            'msg'       => __('You should enter your PayPal email in the account details tab to be received money in case of dispute!', ET_DOMAIN)
            ));
    }
    $receiver = get_user_meta($bid->post_author, 'paypal', true);



    // paypal adaptive process payment and send reponse to client
    $ppadaptive = AE_PPAdaptive::get_instance();
    // get paypal adaptive settings
    $ppadaptive_settings = ae_get_option('escrow_paypal');

    // the admin's paypal business account
    $primary = $ppadaptive_settings['business_mail'];

    // get from setting
    $feesPayer = $ppadaptive_settings['paypal_fee'];

    /**
     * paypal adaptive order data
    */
    $order_data = array(
        'actionType' => 'PAY_PRIMARY',
        'returnUrl' => et_get_page_link('process-payment', array(
            'paymentType' => 'paypaladaptive'
        )) ,
        'cancelUrl' => et_get_page_link('cancel-payment', array(
            'paymentType'   => 'paypaladaptive',
            'returnUrl'     => $post_url
        )) ,

        // 'maxAmountPerPayment' => '35.00',
        'currencyCode' => $currency,
        'feesPayer' => $feesPayer,
        'receiverList.receiver(0).amount' => $total,
        'receiverList.receiver(0).email' => $primary,
        'receiverList.receiver(0).primary' => true,
        // freelancer receiver
        'receiverList.receiver(1).amount' => $bid_budget,
        'receiverList.receiver(1).email' => $receiver,
        'receiverList.receiver(1).primary' => false,
        'requestEnvelope.errorLanguage' => 'en_US'
    );

    $response = $ppadaptive->Pay($order_data);

    if (is_array($response) && isset($response['success']) && !$response['success']) {
        wp_send_json(array(
            'success' => false,
            'msg' => $response['msg']
        ));
    }

    // create order
    $order_post = array(
        'post_type' => 'fre_order',
        'post_status' => 'pending',
        'post_parent' => $bid_id,
        'post_author' => $user_ID,
        'post_title' => 'Pay for accept bid',
        'post_content' => 'Pay for accept bid ' . $bid_id
    );

    if (strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
        do_action('fre_accept_bid', $bid_id);
        $order_id = wp_insert_post($order_post);
        update_post_meta($order_id, 'fre_paykey', $response->payKey);
        update_post_meta($order_id, 'gateway', 'PPadaptive');

        update_post_meta($bid_id, 'fre_bid_order', $order_id);
        update_post_meta($bid_id, 'fre_paykey', $response->payKey);

        et_write_session('payKey', $response->payKey);
        et_write_session('order_id', $order_id);
        et_write_session('bid_id', $bid_id);
        et_write_session('ad_id', $bid->post_parent);

        $response->redirect_url = $ppadaptive->paypal_url . $response->payKey;
        wp_send_json($response);
    }
    else {
        wp_send_json(array(
            'success' => false,
            'msg' => $response->error[0]->message
        ));
    }
}
add_action('wp_ajax_ae-escrow-bid', 'fre_escrow_bid_child');

/* modify accept informATION it is not working */
function remove_acceptBid_child() {
    remove_action('ae_escrow_payment_gateway', 'acceptBid', 3);
}
add_action('init','remove_acceptBid_child');

function acceptBid_child( $escrow_data ){
   
    if( is_use_credit_escrow() ){
        global $user_ID;
        $resp = array(
            'success' => false,
            'msg' => __('Please enter a valid secure code!', ET_DOMAIN)
        );

        if(ae_get_option('fre_credit_secure_code', true)){

            if( !isset($_REQUEST['data']) || empty($_REQUEST['data'] ) ){
                wp_send_json($resp);
            }
            $data = fre_parse_form_data($_REQUEST['data']);

            if(!isset($data['fre_credit_secure_code']) || empty($data['fre_credit_secure_code'])){
                wp_send_json($resp);
            }else{
                $flag = FRE_Credit_Users()->checkSecureCode($user_ID, $data['fre_credit_secure_code']);
                if( !$flag ){
                    wp_send_json($resp);
                }
            }
        }
        
        $bid_id = $escrow_data['bid_id'];
        $bid = get_post($bid_id);
        $post_id     = get_post_field('post_parent', $bid_id);
        $term_obj_list = wp_get_post_terms($post_id, 'project_type',  array("fields" => "all"));
       
        if(!empty($term_obj_list[0]->slug) && $term_obj_list[0]->slug == 'time-based') {
            $charge_obj = array(
                'amount' => (float)$escrow_data['total'],
                'currency' => fre_credit_get_payment_currency(),
                'customer' => $user_ID,
                'post_title'=> 'Paid',
                'project_accept' => $bid->post_parent,
                'check_project_type' => 'time-based',
                
            );

        } else {
            $charge_obj = array(
                'amount' => (float)$escrow_data['total'],
                'currency' => fre_credit_get_payment_currency(),
                'customer' => $user_ID,
                'post_title'=> 'Paid',
                'project_accept' => $bid->post_parent,
                'check_project_type' => 'regular',
                
            );
        }


        $post_id     = get_post_field('post_parent', $bid_id);
        $term_obj_list = wp_get_post_terms($post_id, 'project_type',  array("fields" => "all"));
        
        if(!empty($term_obj_list[0]->slug) && $term_obj_list[0]->slug == 'time-based') {
             
            $charge = FRE_Credit_Users()->charge($charge_obj);

            /*Here we have issue this is not calling*/
            $order_post = array(
                'post_type' => 'fre_order',
                'post_status' => 'pending',
                'post_parent' => $bid_id,
                'post_author' => $user_ID,
                'post_title' => 'Pay for accept bid',
                'post_content' => 'Pay for accept bid ' . $bid_id
            );
            $resp = $charge;

            if ( $charge['success'] && isset($charge['id'])) {
                do_action('fre_accept_bid', $bid_id);
                $order_id = wp_insert_post($order_post);
                update_post_meta($order_id, 'fre_paykey', $charge['id']);
                update_post_meta($order_id, 'gateway', '');

                update_post_meta($bid_id, 'fre_bid_order', $order_id);
                update_post_meta($bid_id, 'commission_fee', '0');
                update_post_meta($bid_id, 'payer_of_commission', '0');
                update_post_meta($bid_id, 'fre_paykey', $charge['id']);

                // insert transaction received pending for freelancer from ver 1.8.2
                $bid_budget = get_post_meta($bid_id, 'bid_budget', true);
                $args_received_pending = array(
                    'post_title' => 'Received',
                    'post_author' => $bid->post_author,
                    'history_type' => 'transfer',
                    'status' => 'pending',
                    'amount' => 0,
                    'commission_fee' => '0',
                    'payment' => $bid->post_parent,
                    'destination' => $bid->post_author,
                    'currency' => $escrow_data['currency'],
                );
                if($escrow_data['payer_of_commission'] =='project_owner'){
                    $args_received_pending['commission_fee'] = 0;
                }
                FRE_Credit_History()->saveHistory($args_received_pending);

                $admin_email = get_option('admin_email');
                $escrow_credit_settings = ae_get_option('escrow_credit_settings',false);
                $email_receive_commission  = !empty($escrow_credit_settings['email_receive_commission']) ? $escrow_credit_settings['email_receive_commission'] : $admin_email;
                $user_admin = get_user_by('email',$email_receive_commission);
                if(!empty($user_admin) && email_exists($email_receive_commission)){
                    // insert transaction commission fee for admin from ver 1.8.2
                    $args_commission = array(
                        'post_title' => 'Received',
                        'post_author' => $user_admin->data->ID,
                        'history_type' => 'transfer',
                        'status' => 'completed',
                        'amount' => 0,
                        'payment' => $bid->post_parent,
                        'destination' => $user_admin->data->ID,
                        'currency' => $escrow_data['currency'],
                        'is_commission' => 1,
                    );
                    FRE_Credit_History()->saveHistory($args_commission);

                    //update credit available + commission for admin from ver 1.8.2
                   
                }

                et_write_session('payKey', $charge['id']);
                et_write_session('order_id', $order_id);
                et_write_session('bid_id', $bid_id);
                et_write_session('ad_id', $bid->post_parent);
                $resp = array(
                    'success' => true,
                    'msg'=> 'Success!',
                    'redirect_url' => et_get_page_link('application-accepted').'/?paymentType=frecredit'
                );
            }
        }else{
             
            $charge = FRE_Credit_Users()->charge($charge_obj);

            $order_post = array(
                'post_type' => 'fre_order',
                'post_status' => 'pending',
                'post_parent' => $bid_id,
                'post_author' => $user_ID,
                'post_title' => 'Pay for accept bid',
                'post_content' => 'Pay for accept bid ' . $bid_id
            );
            $resp = $charge;

            if ( $charge['success'] && isset($charge['id'])) {
                do_action('fre_accept_bid', $bid_id);
                $order_id = wp_insert_post($order_post);
                update_post_meta($order_id, 'fre_paykey', $charge['id']);
                update_post_meta($order_id, 'gateway', 'stripe');

                update_post_meta($bid_id, 'fre_bid_order', $order_id);
                update_post_meta($bid_id, 'commission_fee', $escrow_data['commission_fee']);
                update_post_meta($bid_id, 'payer_of_commission', $escrow_data['payer_of_commission']);
                update_post_meta($bid_id, 'fre_paykey', $charge['id']);

                // insert transaction received pending for freelancer from ver 1.8.2
                $bid_budget = get_post_meta($bid_id, 'bid_budget', true);
                $args_received_pending = array(
                    'post_title' => 'Received',
                    'post_author' => $bid->post_author,
                    'history_type' => 'transfer',
                    'status' => 'pending',
                    'amount' => $bid_budget,
                    'commission_fee' => $escrow_data['commission_fee'],
                    'payment' => $bid->post_parent,
                    'destination' => $bid->post_author,
                    'currency' => $escrow_data['currency'],
                );
                if($escrow_data['payer_of_commission'] =='project_owner'){
                    $args_received_pending['commission_fee'] = 0;
                }
                FRE_Credit_History()->saveHistory($args_received_pending);

                $admin_email = get_option('admin_email');
                $escrow_credit_settings = ae_get_option('escrow_credit_settings',false);
                $email_receive_commission  = !empty($escrow_credit_settings['email_receive_commission']) ? $escrow_credit_settings['email_receive_commission'] : $admin_email;
                $user_admin = get_user_by('email',$email_receive_commission);
                if(!empty($user_admin) && email_exists($email_receive_commission)){
                    // insert transaction commission fee for admin from ver 1.8.2
                    $args_commission = array(
                        'post_title' => 'Received',
                        'post_author' => $user_admin->data->ID,
                        'history_type' => 'transfer',
                        'status' => 'completed',
                        'amount' => $escrow_data['commission_fee'],
                        'payment' => $bid->post_parent,
                        'destination' => $user_admin->data->ID,
                        'currency' => $escrow_data['currency'],
                        'is_commission' => 1,
                    );
                    FRE_Credit_History()->saveHistory($args_commission);

                    //update credit available + commission for admin from ver 1.8.2
                    $admin_available = FRE_Credit_Users()->getUserWallet($user_admin->data->ID);
                    if(!empty($admin_available->balance)){
                        $new_balance = intval($admin_available->balance) + intval($escrow_data['commission_fee']);
                    }else{
                        $new_balance = $escrow_data['commission_fee'];
                    }
                    FRE_Credit_Users()->updateUserBalance($user_admin->data->ID,$new_balance);
                }

                et_write_session('payKey', $charge['id']);
                et_write_session('order_id', $order_id);
                et_write_session('bid_id', $bid_id);
                et_write_session('ad_id', $bid->post_parent);
                $resp = array(
                    'success' => true,
                    'msg'=> 'Success!',
                    'redirect_url' => et_get_page_link('process-payment').'/?paymentType=frecredit'
                );
            }
        }

        wp_send_json($resp);
    }
}

add_action( 'ae_escrow_payment_gateway', 'acceptBid_child' );

/* End code */


add_action( 'user_register', function( $user_id ) {
    $user = get_user_by( 'ID', $user_id );
    if (in_array("author", $user->roles)) {
        $user->set_role('freelancer');
    }
});
