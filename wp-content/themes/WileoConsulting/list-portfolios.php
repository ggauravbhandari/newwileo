<?php
/**
 * Use for page author.php and page-profile.php
 */
global $wp_query, $ae_post_factory, $post, $profile_id;
$wp_query->query = array_merge(  $wp_query->query ,array('posts_per_page' => 6)) ;

$post_object = $ae_post_factory->get( 'portfolio' );
$is_edit = false;
if(is_author()){
    $author_id        = get_query_var( 'author' );
}else{
    $author_id        = get_current_user_id();
    $is_edit = true;
}

$query_args =  array(
    // 'post_parent' => $convert->ID,
    'posts_per_page' => 6,
    'post_status' => 'publish',
    'post_type' => PORTFOLIO,
    'author' => $author_id,
    'is_edit' =>$is_edit
);

query_posts($query_args);

if(have_posts() or $is_edit) {
    ?>
    <div class="fre-profile-box porfolio-profile-box">
        <div class="portfolio-container">
            <div class="profile-freelance-portfolio">
                <div class="row">

                    <div class="<?php echo $is_edit ? 'col-sm-6' :'' ?> col-xs-12">
                        <h2 class="freelance-portfolio-title"><?php _e('Portfolio',ET_DOMAIN) ?></h2>
                    </div>
                    <?php if($is_edit){ ?>
                        <div class="col-sm-6 col-xs-12" id="fre-empty-portfolio">
                            <div class="freelance-portfolio-add">
                                <a href="#"
                                <?php echo !empty($profile_id) ? 'class="portfolio-add-btn add-portfolio"' : '' ?> 
                                data-ctn_edit="portfolio-add-form" data-ctn_hide="fre-empty-portfolio">
                                    <i class="fa fa-plus" <?php echo empty($profile_id) ? 'style="color: gray;"' : '' ?>
                                    <?php 
                                    if ( empty($profile_id) ) {
                                    ?> title="<?php _e('Please update your profile first.') ?>"
                                    <?php }?>
                                    ></i>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="portfolio-add-form" id="modal_add_portfolio" style="display: none;">
                    <form role="form" id="create_portfolio" class="fre-modal-form auth-form create_portfolio">
                        <div class="fre-input-field">
                            <label class="fre-field-title"><?php _e('Portfolio Title', ET_DOMAIN) ?></label>
                            <input type="text" name="post_title"  />
                        </div>
                        <div class="fre-input-field">
                            <label class="fre-field-title"><?php _e('Portfolio Description', ET_DOMAIN) ?></label>
                            <textarea name="post_content" cols="30" rows="10"></textarea>
                        </div>

                        <div class="fre-input-field box_upload_img">
                            <div id="portfolio_img_thumbnail" style="display: none"></div>
                            <ul class="portfolio-thumbs-list row image ctn_portfolio_img">

                            </ul>

                            <div id="portfolio_img_container">
                                <span class="et_ajaxnonce hidden" data-id="<?php echo wp_create_nonce( 'portfolio_img_et_uploader' ); ?>"></span>
                                <label class="fre-upload-file" for="portfolio_img_browse_button">
                			<input type="file" name="post_thumbnail" id="portfolio_img_browse_button" value="<?php _e('Browse', ET_DOMAIN); ?>" />
                			<?php _e('Upload Files', ET_DOMAIN) ?>
                		    </label>
                                <!--<a class="fre-upload-file" href="#" id="portfolio_img_browse_button" style="display: block;">
                                    <?php /*_e( 'Upload Files', ET_DOMAIN ) */?>
                                </a>-->
                            </div>
                            <p class="fre-allow-upload"><?php _e('(Maximum upload file size is limited to 10MB, allowed file types in the png, jpg, and gif.)', ET_DOMAIN) ?></p>
                        </div>

                        <div class="fre-input-field no-margin-bottom">
                            <label class="fre-field-title"><?php _e('Skills (optional)', ET_DOMAIN); ?></label>

                            <select  class="fre-chosen-multi" name="skill" multiple data-placeholder="<?php _e('Select an option', ET_DOMAIN); ?>">
                                <?php
                                if($profile_id) {
                                    $skills = wp_get_object_terms( $profile_id, 'skill' );
                                } else {
                                    $skills = get_terms( 'skill', array('hide_empty' => false) );
                                }
                                if(!empty($skills)){
                                    // $value = 'slug';
                                    $value = 'term_id';
                                    foreach ($skills as $skill) {
                                        echo '<option value="'.$skill->$value.'">'.$skill->name.'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="fre-form-btn">
                            <button type="submit" class="fre-normal-btn fre-submit-portfolio save-portfolio">
                                <?php _e('Save', ET_DOMAIN) ?>
                            </button>
                            <span onclick=" document.getElementById('modal_add_portfolio').style.display = 'none';"><?php _e('Cancel', ET_DOMAIN) ?></span>
                        </div>
                    </form>
                </div>
                <?php if(!have_posts() and $is_edit){ ?>
                    <p class="fre-empty-optional-profile"><?php _e('Add portfolio to your profile. (optional)',ET_DOMAIN) ?></p>
                <?php }else { ?>
                    <ul class="freelance-portfolio-list row">
                        <?php
                        $postdata = array();
                        while ( have_posts() ) {
                            the_post();
                            $convert    = $post_object->convert( $post, 'thumbnail' );
                            $postdata[] = $convert;
                            get_template_part( 'template/portfolio', 'item' );
                        }
                        ?>
                    </ul>
                <?php } ?>

                <?php
                if ( ! empty( $postdata ) && $wp_query->max_num_pages > 1 ) {
                    /**
                     * render post data for js
                     */
                    echo '<script type="data/json" class="postdata portfolios-data" >' . json_encode( $postdata ) . '</script>';

                    echo '<div class="freelance-portfolio-loadmore">';
                    ae_pagination( $wp_query, get_query_var( 'paged' ), 'load_more','View more' );
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
<?php }  ?>
