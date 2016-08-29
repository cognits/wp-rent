<?php
require_once get_template_directory().'/libs/css_js_include.php';
require_once get_template_directory().'/libs/metaboxes.php';
require_once get_template_directory().'/libs/plugins.php';
require_once get_template_directory().'/libs/help_functions.php';
require_once get_template_directory().'/libs/pin_management.php';
require_once get_template_directory().'/libs/ajax_functions.php';
require_once get_template_directory().'/libs/ajax_functions_edit.php';
require_once get_template_directory().'/libs/ajax_functions_booking.php';
require_once get_template_directory().'/libs/ajax_upload.php';
require_once get_template_directory().'/libs/3rdparty.php';
require_once get_template_directory().'/libs/theme-setup.php';
require_once get_template_directory().'/libs/general-settings.php';
require_once get_template_directory().'/libs/listing_functions.php';
require_once get_template_directory().'/libs/theme-slider.php';
require_once get_template_directory().'/libs/agents.php';
require_once get_template_directory().'/libs/invoices.php';
require_once get_template_directory().'/libs/searches.php';
require_once get_template_directory().'/libs/membership.php';
require_once get_template_directory().'/libs/property.php';
require_once get_template_directory().'/libs/booking.php';
require_once get_template_directory().'/libs/messages.php';
require_once get_template_directory().'/libs/shortcodes_install.php';
require_once get_template_directory().'/libs/shortcodes.php';
require_once get_template_directory().'/libs/widgets.php';
require_once get_template_directory().'/libs/events.php';
require_once get_template_directory().'/libs/icalendar.php';
require_once get_template_directory().'/libs/reviews.php';
require_once get_template_directory().'/libs/emailfunctions.php';

$facebook_status    =   esc_html( get_option('wp_estate_facebook_login','') );
if($facebook_status=='yes'){
    require_once get_template_directory().'/libs/resources/facebook_sdk5/Facebook/autoload.php';
}


load_theme_textdomain('wpestate', get_template_directory() . '/languages');

define('ULTIMATE_NO_EDIT_PAGE_NOTICE', true);
define('ULTIMATE_NO_PLUGIN_PAGE_NOTICE', true);
# Disable check updates - 
define('BSF_6892199_CHECK_UPDATES',false);

# Disable license registration nag -
define('BSF_6892199_NAG', false);


function wpestate_admin_notice() {
    global $pagenow;
    global $typenow;
    
    if (!empty($_GET['post'])) {
        $allowed_html   =   array();
        $post = get_post(wp_kses($_GET['post'],$allowed_html));
        $typenow = $post->post_type;
    }

    $api_key                        =   esc_html( get_option('wp_estate_api_key') );
    if($api_key===''){
        print '<div class="error">
            <p>'.esc_html__( 'Google Maps will NOT WORK without a correct Api Key. Get one from ','wpestate').'<a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">'.esc_html__('here','wpestate').'</a></p>
        </div>';
    }
    
    if ( WP_MEMORY_LIMIT < 96 ) { 
        print '<div class="error">
            <p>'.esc_html__( 'Wordpress Memory Limit is set to ', 'wpestate' ).' '.WP_MEMORY_LIMIT.' '.esc_html__( 'Recommended memory limit should be at least 96MB. Please refer to : ','wpestate').'<a href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">'.esc_html__('Increasing memory allocated to PHP','wpestate').'</a></p>
        </div>';
    }
    
    if (!defined('PHP_VERSION_ID')) {
        $version = explode('.', PHP_VERSION);
        define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
    }

    if(PHP_VERSION_ID<50600){
        $version = explode('.', PHP_VERSION);
        print '<div class="error">
            <p>'.__( 'Your PHP version is ', 'wpestate' ).' '.$version[0].'.'.$version[1].'.'.$version[2].'. We recommend upgrading the PHP version to at least 5.6.1. The upgrade should be done on your server by your hosting company. </p>
        </div>';
    }
    
    if ( !extension_loaded('mbstring')) { 
        print '<div class="error">
            <p>'.__( 'MbString extension not detected. Please contact your hosting provider in order to enable it.', 'wpestate' ).'</p>
        </div>';
    }
    
    //print  $pagenow.' / '.$typenow .' / '.basename( get_page_template($post) );
    
    if (is_admin() &&   $pagenow=='post.php' && $typenow=='page' && basename( get_page_template($post))=='property_list_half.php' ){
        $header_type    =   get_post_meta ( $post->ID, 'header_type', true);
      
        if ( $header_type != 5){
            print '<div class="error">
            <p>'.esc_html__( 'Half Map Template - make sure your page has the "media header type" set as google map ', 'wpestate' ).'</p>
            </div>';
        }
       
    }
    
    if (is_admin() &&   $pagenow=='edit-tags.php'  && $typenow=='estate_property') {
    
        print '<div class="error">
            <p>'.esc_html__( 'Please do not manually change the slugs when adding new terms. If you need to edit a term name copy the new name in the slug field also.', 'wpestate' ).'</p>
        </div>';
    }
    
    
    if (is_admin() &&  ( $pagenow=='post-new.php' || $pagenow=='post.php') && $typenow=='estate_property') {
    
        print '<div class="error">
            <p>'.esc_html__( 'Please add properties from front end interface using an user with subscriber level registered in front end', 'wpestate' ).'</p>
        </div>';
    }
  
    if(wpestate_get_ical_link()==home_url()){
        print '<div class="update-nag">
            <p>'.esc_html__( 'You need to create a page with the template ICAL FEED (if you want to use icalendar export/import feature)', 'wpestate' ).'</p>
        </div>';
    }

     if(wpestate_get_dashboard_allinone()==home_url()){
        print '<div class="update-nag">
            <p>'.esc_html__( 'You need to create a page with the template All in one calendar (if you want to use all in one calendar feature)', 'wpestate' ).'</p>
        </div>';
    }
    
    $current_tz= date_default_timezone_get();
    if( wpestate_isValidTimezoneId2($current_tz)!= 1 ){
         print '<div class="update-nag">
            <p>'.esc_html__( 'It looks like you may have a problem with the server date.timezone settings and may encounter errors like the one described here:', 'wpestate' ).'<a href="http://help.wprentals.org/2015/12/21/calendar-doesnt-work-calendar-issues/">http://help.wprentals.org/2015/12/21/calendar-doesnt-work-calendar-issues/</a> '.esc_html__('Please resolve these issues with your hosting provider.','wpestate').' </p>
        </div>';
    }
}
 
function wpestate_isValidTimezoneId2($tzid){
    $valid = array();
    $tza = timezone_abbreviations_list();
   
    foreach ($tza as $zone)
        
      foreach ($zone as $item)
        $valid[$item['timezone_id']] = true;
    unset($valid['']);
    return !!$valid[$tzid];
}

add_action( 'admin_notices', 'wpestate_admin_notice' );

add_action('after_setup_theme', 'wp_estate_init');
if (!function_exists('wp_estate_init')):

    function wp_estate_init() {

        global $content_width;
        if (!isset($content_width)) {
            $content_width = 1800;
        }

        load_theme_textdomain('wpestate', get_template_directory() . '/languages');
        set_post_thumbnail_size(940, 198, true);
        add_editor_style();
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('custom-background');
        add_theme_support("title-tag");
        wp_estate_setup();
        add_action('widgets_init', 'register_wpestate_widgets');
        add_action('init', 'wpestate_shortcodes');
        wp_oembed_add_provider('#https?://twitter.com/\#!/[a-z0-9_]{1,20}/status/\d+#i', 'https://api.twitter.com/1/statuses/oembed.json', true);
        wpestate_image_size();
        add_filter('excerpt_length', 'wp_estate_excerpt_length');
        add_filter('excerpt_more', 'wpestate_new_excerpt_more');
        add_action('tgmpa_register', 'wpestate_required_plugins');
        add_action('wp_enqueue_scripts', 'wpestate_scripts'); // function in css_js_include.php
        add_action('admin_enqueue_scripts', 'wpestate_admin'); // function in css_js_include.php
        update_option( 'image_default_link_type', 'file' );
    }

endif; // end   wp_estate_init  



///////////////////////////////////////////////////////////////////////////////////////////
/////// If admin create the menu
///////////////////////////////////////////////////////////////////////////////////////////
if (is_admin()) {
    add_action('admin_menu', 'wpestate_manage_admin_menu');
}

if (!function_exists('wpestate_manage_admin_menu')):
    function wpestate_manage_admin_menu() {
        global $theme_name;
        add_theme_page(esc_html__('WpRentals Options','wpestate'),esc_html__('WpRentals Options','wpestate'), 'administrator', 'libs/theme-admin.php', 'wpestate_new_general_set');
        require_once get_template_directory().'/libs/property-admin.php';
        require_once get_template_directory().'/libs/pin-admin.php';
        require_once get_template_directory().'/libs/theme-admin.php';
    }
endif; // end   wpestate_manage_admin_menu 

//////////////////////////////////////////////////////////////////////////////////////////////
// page details : setting sidebar position etc...
//////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_page_details')):

    function wpestate_page_details($post_id) {
        $return_array = array();

        
        if ($post_id != '' && !is_home() && !is_tax() && !is_search()) {
            $sidebar_name   = esc_html(get_post_meta($post_id, 'sidebar_select', true));
            $sidebar_status = esc_html(get_post_meta($post_id, 'sidebar_option', true));
        } else {
            $sidebar_name   = esc_html(get_option('wp_estate_blog_sidebar_name', ''));
            $sidebar_status = esc_html(get_option('wp_estate_blog_sidebar', ''));
        }

        if ('' == $sidebar_name) {
            $sidebar_name = 'primary-widget-area';
        }
        if ('' == $sidebar_status) {
            $sidebar_status = 'right';
        }


        if ('left' == $sidebar_status) {
            $return_array['content_class'] = 'col-md-8 col-md-push-4 ';
            $return_array['sidebar_class'] = 'col-md-4 col-md-pull-8 ';
        } else if ($sidebar_status == 'right') {
            $return_array['content_class'] = 'col-md-8 ';
            $return_array['sidebar_class'] = 'col-md-4 ';
        } else {
            $return_array['content_class'] = 'col-md-12';
            $return_array['sidebar_class'] = 'none';
        }

        $return_array['sidebar_name'] = $sidebar_name;

        return $return_array;
    }

endif; // end   wpestate_page_details 



///////////////////////////////////////////////////////////////////////////////////////////
/////// generate custom css
///////////////////////////////////////////////////////////////////////////////////////////

add_action('wp_head', 'wpestate_generate_options_css');
if (!function_exists('wpestate_generate_options_css')):

    function wpestate_generate_options_css() {
        $general_font   = esc_html(get_option('wp_estate_general_font', ''));
        $custom_css     = stripslashes(get_option('wp_estate_custom_css'));
        $color_scheme   = esc_html(get_option('wp_estate_color_scheme', ''));
        $on_child_theme= esc_html ( get_option('wp_estate_on_child_theme','') );
        if ($general_font != '' || $color_scheme == 'yes' || $custom_css != '') {
            echo "<style type='text/css'>";
            if ($general_font != '' && $on_child_theme!=1) {
                require_once get_template_directory().'/libs/custom_general_font.php';
            }


            if ($color_scheme == 'yes' && $on_child_theme!=1) {
                require_once get_template_directory().'/libs/customcss.php';
            }
            print $custom_css;
            echo "</style>";
        }
    }

endif; // end   generate_options_css 
///////////////////////////////////////////////////////////////////////////////////////////
///////  Display navigation to next/previous pages when applicable
///////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wp_estate_content_nav')) :
    function wp_estate_content_nav($html_id) {
        global $wp_query;

        if ($wp_query->max_num_pages > 1) :
            ?>
            <nav id="<?php echo esc_attr($html_id); ?>">
                <h3 class="assistive-text"><?php esc_html_e('Post navigation', 'wpestate'); ?></h3>
                <div class="nav-previous"><?php next_posts_link(esc_html__( '<span class="meta-nav">&larr;</span> Older posts', 'wpestate')); ?></div>
                <div class="nav-next"><?php previous_posts_link(esc_html__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'wpestate')); ?></div>
            </nav><!-- #nav-above -->
            <?php
        endif;
    }

endif; // wpestate_content_nav

///////////////////////////////////////////////////////////////////////////////////////////
///////  Comments
///////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_comment')) :

    function wpestate_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                ?>
                <li class="post pingback">
                    <p><?php esc_html_e('Pingback:', 'wpestate'); ?> <?php comment_author_link(); ?><?php edit_comment_link(esc_html__( 'Edit', 'wpestate'), '<span class="edit-link">', '</span>'); ?></p>
                <?php
                break;
            default :
                ?>




        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

            <?php
                $avatar =esc_url( wpestate_get_avatar_url(get_avatar($comment, 55)));
                print '<div class="blog_author_image singlepage" style="background-image: url(' . esc_url($avatar) . ');">';
                print '</div>';
                ?>

                <div id="comment-<?php comment_ID(); ?>" class="comment">     
                <?php edit_comment_link(esc_html__( 'Edit', 'wpestate'), '<span class="edit-link">', '</span>'); ?>
                    <div class="comment-meta">
                        <div class="comment-author vcard">
                        <?php
                        print '<div class="comment_name">' . get_comment_author_link() . '</div>';
                        print '<span class="comment_date">' . esc_html__( ' on ', 'wpestate') . ' ' . get_comment_date() . '</span>';
                        ?>
                        </div><!-- .comment-author .vcard -->

                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'wpestate'); ?></em>
                    <br />
                <?php endif; ?>

                </div>

                <div class="comment-content">
                <?php comment_text(); ?>

                <?php comment_reply_link(array_merge($args, array('reply_text' => '<i class="fa fa-reply"></i> ' . esc_html__( 'Reply', 'wpestate'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                </div>

            </div><!-- #comment-## -->
            <?php
            break;
        endswitch;
    }

endif; // ends check for  wpestate_comment 
////////////////////////////////////////////////////////////////////////////////
/// Add new profile fields
////////////////////////////////////////////////////////////////////////////////

add_filter('user_contactmethods', 'wpestate_modify_contact_methods');
if (!function_exists('wpestate_modify_contact_methods')):

    function wpestate_modify_contact_methods($profile_fields) {

        // Add new fields
        $profile_fields['facebook']     = esc_html__('Facebook','wpestate');
        $profile_fields['twitter']      = esc_html__('Twitter','wpestate');
        $profile_fields['linkedin']     = esc_html__('Linkedin','wpestate');
        $profile_fields['pinterest']    = esc_html__('Pinterest','wpestate');
        $profile_fields['phone']        = esc_html__('Phone','wpestate');
        $profile_fields['mobile']       = esc_html__('Mobile','wpestate');
        $profile_fields['skype']        = esc_html__('Skype','wpestate');
        $profile_fields['title']        = esc_html__('Title/Position','wpestate');
        $profile_fields['custom_picture']       = esc_html__('Picture Url','wpestate');
        $profile_fields['small_custom_picture'] = esc_html__('Small Picture Url','wpestate');
        $profile_fields['package_id']           = esc_html__('Package Id','wpestate');
        $profile_fields['package_activation']   = esc_html__('Package Activation','wpestate');
        $profile_fields['package_listings']     = esc_html__('Listings available','wpestate');
        $profile_fields['package_featured_listings']    = esc_html__('Featured Listings available','wpestate');
        $profile_fields['profile_id']                   = esc_html__('Paypal Recuring Profile','wpestate');
        $profile_fields['user_agent_id']                = esc_html__('User Owner Id','wpestate');
        $profile_fields['stripe']       = esc_html__( 'Stripe Consumer Profile','wpestate');
        $profile_fields['i_speak']      = esc_html__('I Speak','wpestate');
        $profile_fields['live_in']      = esc_html__('Live In','wpestate');
        $profile_fields['user_type']    = esc_html__('User Type(0-can rent and book / 1 can only book)','wpestate');
        return $profile_fields;
    }

endif; // end   wpestate_modify_contact_methods 




if (!current_user_can('activate_plugins')) {
    
    if (!function_exists('wpestate_admin_bar_render')):
        function wpestate_admin_bar_render() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('edit-profile', 'user-actions');
        }
    endif;

    add_action('wp_before_admin_bar_render', 'wpestate_admin_bar_render');

    add_action('admin_init', 'wpestate_stop_access_profile');
    if (!function_exists('wpestate_stop_access_profile')):
        function wpestate_stop_access_profile() {
            global $pagenow;

            if (defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE === true) {
                wp_die(esc_html__( 'Please edit your profile page from site interface.', 'wpestate'));
            }

            if ($pagenow == 'user-edit.php') {
                wp_die(esc_html__( 'Please edit your profile page from site interface.', 'wpestate'));
            }
        }
    endif; // end   wpestate_stop_access_profile 
}// end user can activate_plugins


///////////////////////////////////////////////////////////////////////////////////////////
// get attachment info
///////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_attachment')):
    function wpestate_get_attachment($attachment_id) {

        $attachment = get_post($attachment_id);
        return array(
            'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'href' => esc_url( get_permalink($attachment->ID) ),
            'src' => $attachment->guid,
            'title' => $attachment->post_title
        );
    }
endif;


add_action('get_header', 'wpestate_my_filter_head');
if (!function_exists('wpestate_my_filter_head')):
    function wpestate_my_filter_head() {
        remove_action('wp_head', '_admin_bar_bump_cb');
    }
endif;

///////////////////////////////////////////////////////////////////////////////////////////
// loosing session fix
///////////////////////////////////////////////////////////////////////////////////////////
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

///////////////////////////////////////////////////////////////////////////////////////////
// forgot pass action
///////////////////////////////////////////////////////////////////////////////////////////

add_action('wp_head', 'wpestate_hook_javascript');
if (!function_exists('wpestate_hook_javascript')):
    function wpestate_hook_javascript() {
        global $wpdb;
        $allowed_html = array();
        if (isset($_GET['key']) && $_GET['action'] == "reset_pwd") {
            $reset_key  =   sanitize_text_field ( wp_kses($_GET['key'], $allowed_html) );
            $user_login =   sanitize_text_field( wp_kses($_GET['login'], $allowed_html) );
            $user_data  =   $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users 
    WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));


            if (!empty($user_data)) {
                $user_login = $user_data->user_login;
                $user_email = $user_data->user_email;

                if (!empty($reset_key) && !empty($user_data)) {
                    $new_password = wp_generate_password(7, false);
                    wp_set_password($new_password, $user_data->ID);
                    
                    $arguments=array(
                        'user_pass'        =>  $new_password,
                    );
                    wpestate_select_email_type($user_email,'password_reseted',$arguments);
                    $mess = '<div class="login-alert">' . esc_html__( 'A new password was sent via email!', 'wpestate') . '</div>';
                    
                } else {
                    exit('Not a Valid Key.');
                }
            }// end if empty
            print '<div class="login_alert_full" id="forgot_notice">' . esc_html__( 'We have just sent you a new password. Please check your email!', 'wpestate') . '</div>';
        }
    }
endif;

if ( !function_exists('wpestate_get_pin_file_path_read')):
    
    function wpestate_get_pin_file_path_read(){
        if (function_exists('icl_translate') ) {
            $path=trailingslashit( get_template_directory_uri() ).'/pins-'.apply_filters( 'wpml_current_language', 'en' ).'.txt';
        }else{
            $path=trailingslashit( get_template_directory_uri() ).'/pins.txt';
        }
   
        return $path;
    }

endif;

if ( !function_exists('wpestate_get_pin_file_path_write')):
    
    function wpestate_get_pin_file_path_write(){
        if (function_exists('icl_translate') ) {
            $path=get_template_directory().'/pins-'.apply_filters( 'wpml_current_language', 'en' ).'.txt';
        }else{
            $path=get_template_directory().'/pins.txt';
        }
 
        return $path;
    }

endif;


add_filter( 'redirect_canonical','wpestate_disable_redirect_canonical',10,2 ); 
function wpestate_disable_redirect_canonical( $redirect_url ,$requested_url){
    //print '$redirect_url'.$redirect_url;
    //print '$requested_url'.$requested_url;
    if ( is_page_template('property_list.php') || is_page_template('property_list_half.php') ){
      //  print 'bag false';
        $redirect_url = false;
    }
    
   
    return $redirect_url;
}



if ( !function_exists('wpestate_check_user_level')):
    function wpestate_check_user_level(){
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;
        $user_login                     =   $current_user->user_login;
        $separate_users_status          =   esc_html ( get_option('wp_estate_separate_users','') );   
        $publish_only                   =   esc_html ( get_option('wp_estate_publish_only','') );   
        
      
        if (trim($publish_only) != '' ){
            $user_array=explode(',',$publish_only);
          
            if ( in_array ($user_login,$user_array)){
                return true;
            }else{
                return false;
            }
            
        }
        
        
        if($separate_users_status=='no'){
            return true;
        }else{
            $user_level = intval( get_user_meta($userID,'user_type',true));
        
            if($user_level==0){ // user can book and rent
                return true;
            }else{// user can only book
                if( basename(get_page_template()) == 'user_dashboard.php' || 
                basename(get_page_template()) == 'user_dashboard_add_step1.php' || 
                basename(get_page_template()) == 'user_dashboard_edit_listing.php' || 
                basename(get_page_template()) == 'user_dashboard_my_bookings.php'  || 
                basename(get_page_template()) == 'user_dashboard_packs.php'  || 
                basename(get_page_template()) == 'user_dashboard_searches.php' ||
                basename(get_page_template()) == 'user_dashboard_allinone.php'  )    {
                   
                    return false;
                }
                
            }
            
        }
        
    }
endif;


function estate_create_onetime_nonce($action = -1) {
    $time = time();
  // print $time.$action;
   $nonce = wp_create_nonce($time.$action);
    return $nonce . '-' . $time;
}
//1455041901register_ajax_nonce_topbar

function estate_verify_onetime_nonce( $_nonce, $action = -1) {
    $parts  =   explode( '-', $_nonce );
    $nonce  =   $toadd_nonce    = $parts[0]; 
    $generated = $parts[1];

    $nonce_life = 60*60;
    $expires    = (int) $generated + $nonce_life;
    $time       = time();

    if( ! wp_verify_nonce( $nonce, $generated.$action ) || $time > $expires ){
        return false;
    }
    
    $used_nonces = get_option('_sh_used_nonces');

    if( isset( $used_nonces[$nonce] ) ) {
        return false;
    }

    if(is_array($used_nonces)){
        foreach ($used_nonces as $nonce=> $timestamp){
            if( $timestamp > $time ){
                break;
            }
            unset( $used_nonces[$nonce] );
        }
    }

    $used_nonces[$toadd_nonce] = $expires;
    asort( $used_nonces );
    update_option( '_sh_used_nonces',$used_nonces );
    return true;
}




function estate_verify_onetime_nonce_login( $_nonce, $action = -1) {
    $parts = explode( '-', $_nonce );
    $nonce =$toadd_nonce= $parts[0];
    $generated = $parts[1];

    $nonce_life = 60*60;
    $expires    = (int) $generated + $nonce_life;
    $expires2   = (int) $generated + 120;
    $time       = time();

    if( ! wp_verify_nonce( $nonce, $generated.$action ) || $time > $expires ){
        return false;
    }
    
    //Get used nonces
    $used_nonces = get_option('_sh_used_nonces');

    if( isset( $used_nonces[$nonce] ) ) {
        return false;
    }

    if(is_array($used_nonces)){
        foreach ($used_nonces as $nonce=> $timestamp){
            if( $timestamp > $time ){
                break;
            }
            unset( $used_nonces[$nonce] );
        }
    }

    //Add nonce in the stack after 2min
    if($time > $expires2){
        $used_nonces[$toadd_nonce] = $expires;
        asort( $used_nonces );
        update_option( '_sh_used_nonces',$used_nonces );
    }
    return true;
}




///////////////////////////////////////////////////////////////////////////////////////////
// prevent changing the author id when admin hit publish
///////////////////////////////////////////////////////////////////////////////////////////

add_action( 'transition_post_status', 'wpestate_correct_post_data',10,3 );

if( !function_exists('wpestate_correct_post_data') ):
    
function wpestate_correct_post_data( $strNewStatus,$strOldStatus,$post) {
    /* Only pay attention to posts (i.e. ignore links, attachments, etc. ) */
    if( $post->post_type !== 'estate_property' )
        return;

    if( $strOldStatus === 'new' ) {
        update_post_meta( $post->ID, 'original_author', $post->post_author );
    }

       
    
    /* If this post is being published, try to restore the original author */
    if( $strNewStatus === 'publish' ) {
    
         
            $originalAuthor_id =$post->post_author;
            $user = get_user_by('id',$originalAuthor_id); 
            if(!$user){
                return;
            }
            $user_email=$user->user_email;
            
      
            
            
            if( $user->roles[0]=='subscriber'){
                $arguments=array(
                    'post_id'           =>  $post->ID,
                    'property_url'      =>  get_permalink($post->ID),
                    'property_title'    =>  get_the_title($post->ID)
                );
                
                if($strOldStatus=='pending'){
                      
                    if( $user->roles[0]=='subscriber'){
                        $arguments=array(
                            'post_id'           =>  $post->ID,
                            'property_url'      =>  get_permalink($post->ID),
                            'property_title'    =>  get_the_title($post->ID)
                        );



                        wpestate_select_email_type($user_email,'approved_listing',$arguments);    

                    }
                   
                }
            }
    }
}
endif; // end   wpestate_correct_post_data 



function wpestate_double_tax_cover($property_area,$property_city,$post_id){
        $prop_city_selected                  =   get_term_by('name', $property_city, 'property_city');
        $prop_area_selected                  =   get_term_by('name', $property_area, 'property_area');
        if(isset($prop_area_selected->term_id)){ // we have this tax
            //print  $prop_area_selected->term_id.' / '.$prop_area_selected->name;
            //print  $prop_city_selected->term_id.' / '.$prop_city_selected->name;
            $term_meta = get_option( "taxonomy_$prop_area_selected->term_id");
            
            if( $term_meta['cityparent'] !=  $property_city){
                $new_property_area=$property_area.', '.$property_city;
            }else{
                  $new_property_area=$property_area;
            }
            wp_set_object_terms($post_id,$new_property_area,'property_area'); 
            return $new_property_area;
        }else{
            wp_set_object_terms($post_id,$property_area,'property_area'); 
            return $property_area;
        }
                   
}

function wpestate_search_by_title_only( $search, $wp_query ) {
    if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
        global $wpdb;

        $q = $wp_query->query_vars;
        $n = ! empty( $q['exact'] ) ? '' : '%';

        $search = array();

        foreach ( ( array ) $q['search_terms'] as $term )
            $search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );

        if ( ! is_user_logged_in() )
            $search[] = "$wpdb->posts.post_password = ''";

        $search = ' AND ' . implode( ' AND ', $search );
    }

    return $search;
}


?>