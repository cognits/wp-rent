<?php

///////////////////////////////////////////////////////////////////////////////////////////
/////// Js & Css include on front site 
///////////////////////////////////////////////////////////////////////////////////////////



if( !function_exists('wpestate_scripts') ):
function wpestate_scripts() {   
    global $post;
    $custom_image               =   '';
    $use_idx_plugins            =   0;
    $header_type                =   '';
  
    $adv_search_type_status     =   intval   ( get_option('wp_estate_adv_search_type',''));
    $home_small_map_status      =   esc_html ( get_option('wp_estate_home_small_map','') );
        
  
   
    if( isset($post->ID) ) {
        $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
    }
   
    $global_header_type         =   get_option('wp_estate_header_type','');
    if(is_singular('estate_agent')){
        $global_header_type         =   get_option('wp_estate_user_header_type','');
    }

    $listing_map                =   'internal';
    
    if( $header_type==5 || $global_header_type==4 ){
        $listing_map            =   'top';        
    }
    
    
    $slugs=array();
    $hows=array();
    $show_price_slider          =   'no';
    $slider_price_position      =   0;
            
    $custom_advanced_search= get_option('wp_estate_custom_advanced_search','');
    if ( $custom_advanced_search == 'yes'){
            $adv_search_what        =   get_option('wp_estate_adv_search_what','');
            $adv_search_label       =   get_option('wp_estate_adv_search_label','');
            $adv_search_how         =   get_option('wp_estate_adv_search_how','');
            $show_price_slider       =   get_option('wp_estate_show_slider_price','');
            $slider_price_position  =   0;
            $counter                =   0;
            foreach($adv_search_what as $key=>$search_field){
                $counter++;
                if($search_field=='types'){  
                    $slugs[]='adv_actions';
                }
                else if($search_field=='categories'){
                    $slugs[]='adv_categ';
                }  
                else if($search_field=='cities'){
                    $slugs[]='advanced_city';
                } 
                else if($search_field=='areas'){
                    $slugs[]='advanced_area';
                } else if (  $search_field=='property price' && $show_price_slider=='yes' ){
                    $slugs[]='property_price';
                    $slugs[]='property_price';
                    $slider_price_position=$counter ;
                    
                }
                else {                   
                    $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
                    $slug         =   sanitize_key($string);
                    $slugs[]=$slug;
                 }
            }
          
            foreach($adv_search_how as $key=>$search_field){
                $hows[]= $adv_search_how[$key];
                
            }
    }
  
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // load the css files
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    wp_enqueue_style('wpestate_bootstrap',get_template_directory_uri().'/css/bootstrap.css', array(), '1.0', 'all');
    wp_enqueue_style('wpestate_bootstrap-theme',get_template_directory_uri().'/css/bootstrap-theme.css', array(), '1.0', 'all');
    wp_enqueue_style('wpestate_style',get_stylesheet_uri(), array(), '1.0', 'all');  
    wp_enqueue_style('wpestate_media',get_template_directory_uri().'/css/my_media.css', array(), '1.0', 'all'); 
   
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    // load the general js files
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    wp_enqueue_script("jquery");
    wp_enqueue_script("jquery-ui-slider");
    wp_enqueue_script("jquery-ui-datepicker");
    wp_enqueue_script("jquery-ui-autocomplete");
    if( is_page_template('user-dashboard-profile.php')  ){
        //   wp_enqueue_script('plupload-handlers');
    }
    wp_enqueue_script('wpestate_bootstrap', trailingslashit( get_template_directory_uri() ).'js/bootstrap.min.js',array(), '1.0', false);
    wp_enqueue_script('wpestate_viewport', trailingslashit( get_template_directory_uri() ).'js/jquery.viewport.mini.js',array(), '1.0', false);
    wp_enqueue_script('wpestate_modernizr', trailingslashit( get_template_directory_uri() ).'js/modernizr.custom.62456.js',array(), '1.0', false);     
    wp_enqueue_script('wpestate_jquery.fancybox.pack', trailingslashit( get_template_directory_uri() ).'js/jquery.fancybox.pack.js',array('jquery'), '1.0', true); 
    wp_enqueue_script('wpestate_jquery.fancybox-thumbs', trailingslashit( get_template_directory_uri() ).'js/jquery.fancybox-thumbs.js',array('jquery'), '1.0', true); 
    wp_enqueue_script('wpestate_jquery.placeholders', trailingslashit( get_template_directory_uri() ).'js/placeholders.min.js',array('jquery'), '1.0', true);
    wp_enqueue_script('wpestate_dense', trailingslashit( get_template_directory_uri() ).'js/dense.js',array('jquery'), '1.0', true);
    wp_enqueue_script('wpestate_touch-punch', trailingslashit( get_template_directory_uri() ).'js/jquery.ui.touch-punch.min.js',array('jquery'), '1.0', true); 
    wp_enqueue_script('wpestate_jquery.lazyloadxt.min', trailingslashit( get_template_directory_uri() ).'js/jquery.lazyload.min.js',array('jquery'), '1.0', true);
    wp_enqueue_style('wpestate_jquery.ui.theme', trailingslashit( get_template_directory_uri() ) . 'css/jquery-ui.min.css');
    wp_enqueue_script('latinise.min', get_template_directory_uri().'/js/latinise.min_.js',array('jquery'), '1.0', true);
   
   
    if( !is_tax() && get_post_type() === 'estate_property' ) {
        wp_enqueue_script('wpestate_jquery.fancybox.pack', trailingslashit( get_template_directory_uri() ).'js/jquery.fancybox.pack.js',array('jquery'), '1.0', true); 
        wp_enqueue_script('wpestate_jquery.fancybox-thumbs', trailingslashit( get_template_directory_uri() ).'js/jquery.fancybox-thumbs.js',array('jquery'), '1.0', true); 
        wp_enqueue_style('wpestate_fancybox', trailingslashit( get_template_directory_uri() ).'css/jquery.fancybox.css', array(), '1.0', 'all'); 
    }
    
    $date_lang_status= esc_html ( get_option('wp_estate_date_lang','') );
    
    if($date_lang_status!='xx'){
        $handle="datepicker-".$date_lang_status;
        $name="datepicker-".$date_lang_status.".js";
        wp_enqueue_script($handle, trailingslashit( get_template_directory_uri() ).'js/i18n/'.$name,array('jquery'), '1.0', true);
    }
   
    
    if ( is_page_template('user_dashboard_edit_listing.php') ){
        wp_enqueue_script("jquery-ui-draggable");
        wp_enqueue_script("jquery-ui-sortable");              
    }
    
    $use_generated_pins =   0;
    $load_extra         =   0;
    $post_type          =   get_post_type();

    if( is_page_template('advanced_search_results.php') || is_page_template('property_list_half.php')  || is_tax() || $post_type=='estate_agent' ){    // search results -> pins are added  from template   
        $use_generated_pins=1;
        $json_string=array();
        $json_string=json_encode($json_string);
    }else{
        // google maps pins
        if ( get_option('wp_estate_readsys','') =='yes' ){
            $path= wpestate_get_pin_file_path_read();
            $request = wp_remote_get($path);
            $json_string = wp_remote_retrieve_body( $request );
        }else{
            $json_string= wpestate_listing_pins();
        }
    }

    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // load the Google Maps js files
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    $show_g_search_status= esc_html ( get_option('wp_estate_show_g_search','') );
        
    if (esc_html ( get_option('wp_estate_ssl_map','') ) =='yes'){
        wp_enqueue_script('wpestate_googlemap', 'https://maps-api-ssl.google.com/maps/api/js?libraries=places&amp;language=en&amp;key='.esc_html(get_option('wp_estate_api_key', '') ),array('jquery'), '1.0', false);        
    }else{
        wp_enqueue_script('wpestate_googlemap', 'http://maps.googleapis.com/maps/api/js?libraries=places&amp;language=en&amp;key='.esc_html(get_option('wp_estate_api_key', '') ),array('jquery'), '1.0', false);        
    }
    wp_enqueue_script('wpestate_infobox',  trailingslashit( get_template_directory_uri() ) .'js/infobox.js',array('jquery'), '1.0', true); 
    
   
    $pin_images=wpestate_pin_images();
    $geolocation_radius =   esc_html ( get_option('wp_estate_geolocation_radius','') );
    if ($geolocation_radius==''){
          $geolocation_radius =1000;
    }
    $pin_cluster_status =   esc_html ( get_option('wp_estate_pin_cluster','') );
    $zoom_cluster       =   esc_html ( get_option('wp_estate_zoom_cluster ','') );
    $show_adv_search    =   esc_html ( get_option('wp_estate_show_adv_search_map_close','') );
    
    if( isset($post->ID) ){
        $page_lat           =   wpestate_get_page_lat($post->ID);
        $page_long          =   wpestate_get_page_long($post->ID);  
        $page_custom_zoom   =   wpestate_get_page_zoom($post->ID); 
        $page_custom_zoom_prop   =   get_post_meta($post->ID,'page_custom_zoom',true);
        $closed_height      =   wpestate_get_current_map_height($post->ID);
        $open_height        =   wpestate_get_map_open_height($post->ID);
        $open_close_status  =   wpestate_get_map_open_close_status($post->ID);  
    }else{
        $page_lat           =   esc_html( get_option('wp_estate_general_latitude','') );
        $page_long          =   esc_html( get_option('wp_estate_general_longitude','') );
        $page_custom_zoom   =   esc_html( get_option('wp_estate_default_map_zoom','') ); 
        $page_custom_zoom_prop  =   15;
        $closed_height      =   intval (get_option('wp_estate_min_height',''));
        $open_height        =   get_option('wp_estate_max_height','');
        $open_close_status  =   esc_html( get_option('wp_estate_keep_min','' ) ); 
    }
   
    
    if( get_post_type() === 'estate_property' && !is_tax() && !is_search() && !is_tag() ){
        $load_extra =   1;
        $google_camera_angle    =   intval( esc_html(get_post_meta($post->ID, 'google_camera_angle', true)) );
        $header_type                =   get_post_meta ( $post->ID, 'header_type', true);
        $global_header_type         =   get_option('wp_estate_header_type','');
        $small_map=0;
        if ( $header_type == 0 ){ // global
            if ($global_header_type != 4){
                $small_map=1;
            }
        }else{
            if($header_type!=5){
                $small_map=1;
            }
        }
        
        $single_json_string= wpestate_single_listing_pins($post->ID);
        
        wp_enqueue_script('wpestate_googlecode_property',trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_code_listing.js',array('jquery'), '1.0', true); 
        wp_localize_script('wpestate_googlecode_property', 'googlecode_property_vars', 
              array(  'general_latitude'  =>  esc_html( get_option('wp_estate_general_latitude','') ),
                      'general_longitude' =>  esc_html( get_option('wp_estate_general_longitude','') ),
                      'path'              =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                      'markers'           =>  $json_string,
                      'single_marker'     =>  $single_json_string,
                      'single_marker_id'  =>  $post->ID,
                      'camera_angle'      =>  $google_camera_angle,
                      'idx_status'        =>  $use_idx_plugins,
                      'page_custom_zoom'  =>  $page_custom_zoom_prop,
                      'current_id'        =>  $post->ID,
                      'generated_pins'    =>  0,
                      'small_map'          => $small_map
                   )
          );
        
      
   
  
    }else if( is_page_template('contact_page.php')  ){
        $load_extra =   1;
        if($custom_image    ==  ''){  
            wp_enqueue_script('wpestate_googlecode_contact', trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_code_contact.js',array('jquery'), '1.0', true);        
            $hq_latitude =  esc_html( get_option('wp_estate_hq_latitude','') );
            $hq_longitude=  esc_html( get_option('wp_estate_hq_longitude','') );

            if($hq_latitude==''){
                $hq_latitude='40.781711';
            }

            if($hq_longitude==''){
                $hq_longitude='-73.955927';
            }
            $json_string=wpestate_contact_pin(); 

        wp_localize_script('wpestate_googlecode_contact', 'googlecode_contact_vars', 
            array(  'hq_latitude'       =>  $hq_latitude,
                    'hq_longitude'      =>  $hq_longitude,
                    'path'              =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                    'markers'           =>  $json_string,
                    'page_custom_zoom'  =>  $page_custom_zoom,
                    'address'           =>  esc_html( get_option('wp_estate_co_address', '') )                     
                   )
          );
        }
       
    }else {
            if($header_type==5 || $global_header_type==4){           
                $load_extra =   1;
                 
                wp_enqueue_script('wpestate_googlecode_regular', trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_code.js',array('jquery'), '1.0', true);        
                wp_localize_script('wpestate_googlecode_regular', 'googlecode_regular_vars', 
                    array(  'general_latitude'  =>  $page_lat,
                            'general_longitude' =>  $page_long,
                            'path'              =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                            'markers'           =>  $json_string,
                            'idx_status'        =>  $use_idx_plugins,
                            'page_custom_zoom'  =>  $page_custom_zoom,
                            'generated_pins'    =>  $use_generated_pins,
                            'page_custom_zoom'  =>  $page_custom_zoom,
                            'on_demand_pins'    =>   esc_html ( get_option('wp_estate_ondemandmap','') )
                         )
                );

            }
    }         
    
    $custom_advanced_search  = get_option('wp_estate_custom_advanced_search','');
    $measure_sys             = get_option('wp_estate_measure_sys','');
    
    $is_tax=0;  
    if( is_tax() ){
        $is_tax=1;  
    }
    
    
    $is_property_list=0;
    if ( is_page_template('property_list.php') || is_page_template('property_list_half.php') || is_page_template('advanced_search_results.php') ){
        $is_property_list=1;  
    }
    
    
    
    if ( is_page_template('user_dashboard_edit_listing.php') ){
        $load_extra =   1; 
    }
    //var_dump($load_extra);
    if($load_extra ==   1){
        wp_enqueue_script('wpestate_oms.min',trailingslashit( get_template_directory_uri() ).'js/google_js/oms.min.js',array('jquery'), '1.0', true);   
        wp_enqueue_script('wpestate_mapfunctions', trailingslashit( get_template_directory_uri() ).'js/google_js/mapfunctions.js',array('jquery'), '1.0', true);   
        wp_localize_script('wpestate_mapfunctions', 'mapfunctions_vars', 
            array(  'path'                 =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                    'pin_images'           =>  $pin_images ,
                    'geolocation_radius'   =>  $geolocation_radius,
                    'adv_search'           =>  $adv_search_type_status,
                    'in_text'              =>  esc_html__( ' in ','wpestate'),
                    'zoom_cluster'         =>  intval($zoom_cluster),
                    'user_cluster'         =>  $pin_cluster_status,
                    'open_close_status'    =>  $open_close_status,
                    'open_height'          =>  $open_height,
                    'closed_height'        =>  $closed_height,     
                    'generated_pins'       =>  $use_generated_pins,
                    'geo_no_pos'           =>  esc_html__( 'The browser couldn\'t detect your position!','wpestate'),
                    'geo_no_brow'          =>  esc_html__( 'Geolocation is not supported by this browser.','wpestate'),
                    'geo_message'          =>  esc_html__( 'm radius','wpestate'),
                    'show_adv_search'      =>  $show_adv_search,
                    'custom_search'        =>  $custom_advanced_search,
                    'listing_map'          =>  $listing_map,
                    'slugs'                =>  $slugs,
                    'hows'                 =>  $hows,
                    'measure_sys'          =>  $measure_sys,
                    'close_map'            =>  esc_html__( 'close map','wpestate'),
                    'show_g_search_status' =>  $show_g_search_status,
                    'slider_price'         =>  $show_price_slider,
                    'slider_price_position'=>  $slider_price_position,
                    'map_style'            =>  stripslashes (  get_option('wp_estate_map_style','') ),
                    'is_tax'               =>  $is_tax, 
                    'is_property_list'     =>  $is_property_list
                    )
            );   
        wp_enqueue_script('wpestate_markerclusterer', trailingslashit( get_template_directory_uri() ).'js/google_js/markerclusterer.js',array('jquery'), '1.0', true);  
    } // end load extra
    
  
    
         

    $login_redirect =   wpestate_get_dashboard_profile_link();
    $show_adv_search_map_close          =   esc_html ( get_option('wp_estate_show_adv_search_map_close','') ); 
    $max_file_size  = 100 * 1000 * 1000;
    $current_user = wp_get_current_user();
    $userID                     =   $current_user->ID; 
      
    
    $booking_array                  =   array();
    $custom_price                   =   '';
    $default_price                  =   '';
    $cleaning_fee_per_day           =   '';
    $city_fee_per_day               =   '';
    $price_per_guest_from_one       =   '';
    $checkin_change_over            =   '';
    $checkin_checkout_change_over   =   '';
    $min_days_booking               =   '';
    $extra_price_per_guest          =   '';
    $price_per_weekeend             =   '';
    $mega_details                   =   '';
    
    if(isset($post->ID)){
        $custom_price    =  json_encode(  wpml_custom_price_adjust($post->ID));
        
        
        $booking_array   =   json_encode(get_post_meta($post->ID, 'booking_dates',true  ));
        $default_price   =   get_post_meta($post->ID,'property_price',true);
        
        $cleaning_fee_per_day           =   intval  ( get_post_meta($post->ID,  'cleaning_fee_per_day', true) );
        $city_fee_per_day               =   intval   ( get_post_meta($post->ID, 'city_fee_per_day', true) );
        $price_per_guest_from_one       =   intval   ( get_post_meta($post->ID, 'price_per_guest_from_one', true) );
        $checkin_change_over            =   intval   ( get_post_meta($post->ID, 'checkin_change_over', true) );  
        $checkin_checkout_change_over   =   intval   ( get_post_meta($post->ID, 'checkin_checkout_change_over', true) );  
        $min_days_booking               =   intval   ( get_post_meta($post->ID, 'min_days_booking', true) );  
        $extra_price_per_guest          =   intval   ( get_post_meta($post->ID, 'extra_price_per_guest', true) );  
        $price_per_weekeend             =   intval   ( get_post_meta($post->ID, 'price_per_weekeend', true) );
        $mega_details                   =   json_encode( wpml_mega_details_adjust($post->ID));
    }
    
    $week_days_control=array(
        '0'=>esc_html__('None','wpestate'),
        '1'=>esc_html__('Monday','wpestate'), 
        '2'=>esc_html__('Tuesday','wpestate'),
        '3'=>esc_html__('Wednesday','wpestate'),
        '4'=>esc_html__('Thursday','wpestate'),
        '5'=>esc_html__('Friday','wpestate'),
        '6'=>esc_html__('Saturday','wpestate'),
        '7'=>esc_html__('Sunday','wpestate')
    );
       
    $submission_curency = wpestate_curency_submission_pick();
  
    
    //$direct_payment_details         =   wp_kses( get_option('wp_estate_direct_payment_details','') ,$argsx);
    if (function_exists('icl_translate') ){
        $mes =  stripslashes ( esc_html( get_option('wp_estate_direct_payment_details','') ) );
        $direct_payment_details      =   icl_translate('wpestate','wp_estate_property_direct_payment_text', $mes );
    }else{
        $direct_payment_details = stripslashes ( esc_html( get_option('wp_estate_direct_payment_details','') ) );
    }
    
    wp_enqueue_script('wpestate_control', trailingslashit( get_template_directory_uri() ).'js/control.js',array('jquery'), '1.0', true);   
    wp_localize_script('wpestate_control', 'control_vars', 
            array(  'searchtext'            =>   esc_html__( 'SEARCH','wpestate'),
                    'searchtext2'           =>   esc_html__( 'Search here...','wpestate'),
                    'path'                  =>   get_template_directory_uri(),
                    'search_room'           =>  esc_html__( 'Type Bedrooms No.','wpestate'),
                    'search_bath'           =>  esc_html__( 'Type Bathrooms No.','wpestate'),
                    'search_min_price'      =>  esc_html__( 'Type Min. Price','wpestate'),
                    'search_max_price'      =>  esc_html__( 'Type Max. Price','wpestate'),
                    'contact_name'          =>  esc_html__( 'Your Name','wpestate'),
                    'contact_email'         =>  esc_html__( 'Your Email','wpestate'),
                    'contact_phone'         =>  esc_html__( 'Your Phone','wpestate'),
                    'contact_comment'       =>  esc_html__( 'Your Message','wpestate'),
                    'zillow_addres'         =>  esc_html__( 'Your Address','wpestate'),
                    'zillow_city'           =>  esc_html__( 'Your City','wpestate'),
                    'zillow_state'          =>  esc_html__( 'Your State Code (ex CA)','wpestate'),
                    'adv_contact_name'      =>  esc_html__( 'Your Name','wpestate'),
                    'adv_email'             =>  esc_html__( 'Your Email','wpestate'),
                    'adv_phone'             =>  esc_html__( 'Your Phone','wpestate'),
                    'adv_comment'           =>  esc_html__( 'Your Message','wpestate'),
                    'adv_search'            =>  esc_html__( 'Send Message','wpestate'),
                    'admin_url'             =>  get_admin_url(),
                    'login_redirect'        =>  $login_redirect,
                    'login_loading'         =>  esc_html__( 'Sending user info, please wait...','wpestate'), 
                    'street_view_on'        =>  esc_html__( 'Street View','wpestate'),
                    'street_view_off'       =>  esc_html__( 'Close Street View','wpestate'),
                    'userid'                =>  $userID,
                    'show_adv_search_map_close'=>$show_adv_search_map_close,
                    'close_map'             =>  esc_html__( 'close map','wpestate'),
                    'open_map'              =>  esc_html__( 'open map','wpestate'),
                    'fullscreen'            =>  esc_html__( 'Fullscreen','wpestate'),
                    'default'               =>  esc_html__( 'Default','wpestate'),
                    'addprop'               =>  esc_html__( 'Please wait while we are processing your submission!','wpestate'),
                    'deleteconfirm'         =>  esc_html__( 'Are you sure you wish to delete?','wpestate'),
                    'terms_cond'            =>  esc_html__( 'You must to agree with terms and conditions!','wpestate'),
                    'slider_min'            =>  floatval(get_option('wp_estate_show_slider_min_price','')),
                    'slider_max'            =>  floatval(get_option('wp_estate_show_slider_max_price','')),
                    'bookconfirmed'         =>  esc_html__( 'Booking request sent. Please wait for owner\'s confirmation!','wpestate'),
                    'bookdenied'            =>  esc_html__( 'The selected period is already booked. Please choose a new one!','wpestate'),
                    'to'                    =>  esc_html__( 'to','wpestate'),
                    'curency'               =>  esc_html( get_option('wp_estate_currency_symbol', '') ),
                    'where_curency'         =>  esc_html( get_option('wp_estate_where_currency_symbol', '') ),
                    'price_separator'       =>  esc_html( get_option('wp_estate_prices_th_separator', '') ),
                    'datepick_lang'         =>  esc_html ( get_option('wp_estate_date_lang','') ),
                    'custom_price'          =>  $custom_price,
                    'booking_array'         =>  $booking_array,
                    'default_price'         =>  $default_price ,
                    'transparent_logo'      =>  get_option('wp_estate_transparent_logo_image', ''),
                    'normal_logo'           =>  get_option('wp_estate_logo_image', ''),
                    'cleaning_fee_per_day'           =>   $cleaning_fee_per_day,         
                    'city_fee_per_day'               =>   $city_fee_per_day,
                    'price_per_guest_from_one'       =>   $price_per_guest_from_one,
                    'checkin_change_over'            =>   $checkin_change_over,
                    'checkin_checkout_change_over'   =>   $checkin_checkout_change_over,
                    'min_days_booking'               =>   $min_days_booking,
                    'extra_price_per_guest'          =>   $extra_price_per_guest,
                    'price_per_weekeend'             =>   $price_per_weekeend,
                    'setup_weekend_status'           =>   esc_html ( get_option('wp_estate_setup_weekend','') ),
                    'mega_details'                   =>   $mega_details,
                    'mindays'                        =>   esc_html__( 'The selected period is shorter than the minimum required period!','wpestate'),
                    'weekdays'                       =>   json_encode($week_days_control),
                    'stopcheckin'                    =>   esc_html__( 'Check in date is not correct','wpestate'),
                    'stopcheckinout'                 =>   esc_html__( 'Check in/Check out dates are not correct','wpestate'),  
                    'from'                           =>   esc_html__('from','wpestate'),
                    'separate_users'                 =>   esc_html ( get_option('wp_estate_separate_users','') )  ,
                    'captchakey'                     =>   get_option('wp_estate_recaptha_sitekey',''),
                    'usecaptcha'                     =>   get_option('wp_estate_use_captcha',''),
                    'unavailable_check'              =>   esc_html__('Unavailable/Only Check Out','wpestate'),
                    'unavailable'                    =>   esc_html__('Unavailable','wpestate'),
                    'submission_curency'             =>   $submission_curency,
                    'direct_price'                   =>   esc_html__('To be paid','wpestate'),
                    'send_invoice'                   =>   esc_html__('Send me the invoice','wpestate'),
                    'direct_pay'                     =>   $direct_payment_details,
                    'direct_title'                   =>   esc_html__('Direct payment instructions','wpestate'),
                    'direct_thx'                     =>  esc_html__('Thank you. Please check your email for payment instructions.','wpestate'),
                )
     );
    
   
    
    
    wp_enqueue_script('wpestate_ajaxcalls', trailingslashit( get_template_directory_uri() ).'js/ajaxcalls.js',array('jquery'), '1.0', true);   
    wp_localize_script('wpestate_ajaxcalls', 'ajaxcalls_vars', 
            array(  'contact_name'          =>  esc_html__( 'Your Name','wpestate'),
                    'contact_email'         =>  esc_html__( 'Your Email','wpestate'),
                    'contact_phone'         =>  esc_html__( 'Your Phone','wpestate'),
                    'contact_comment'       =>  esc_html__( 'Your Message','wpestate'),
                    'adv_contact_name'      =>  esc_html__( 'Your Name','wpestate'),
                    'adv_email'             =>  esc_html__( 'Your Email','wpestate'),
                    'adv_phone'             =>  esc_html__( 'Your Phone','wpestate'),
                    'adv_comment'           =>  esc_html__( 'Your Message','wpestate'),
                    'adv_search'            =>  esc_html__( 'Send Message','wpestate'),
                    'admin_url'             =>  get_admin_url(),
                    'login_redirect'        =>  $login_redirect,
                    'login_loading'         =>  esc_html__( 'Sending user info, please wait...','wpestate'), 
                    'userid'                =>  $userID,
                    'prop_featured'         =>  esc_html__( 'Property is featured','wpestate'),
                    'no_prop_featured'      =>  esc_html__( 'You have used all the "Featured" listings in your package.','wpestate'),
                    'favorite'              =>  esc_html__( 'Favorite','wpestate').'<i class="fa fa-heart"></i>',
                    'add_favorite'          =>  esc_html__( 'Add to Favorites','wpestate'),
                    'remove_favorite'       =>  esc_html__( 'remove from favorites','wpestate'),
                    'add_favorite_unit'     =>  esc_html__( 'add to favorites','wpestate'),
                    'saving'                =>  esc_html__( 'saving..','wpestate'),
                    'sending'               =>  esc_html__( 'sending message..','wpestate'),
                    'reserve'               =>  esc_html__( 'Reserve Period','wpestate'),
                    'paypal'                =>  esc_html__( 'Connecting to Paypal! Please wait...','wpestate'),
                    'stripecancel'          =>  esc_html__( 'subscription will be cancelled at the end of the current period','wpestate'),
                )
     );
    
  
      
    if(is_page_template('user_dashboard_edit_listing.php') || is_page_template('user_dashboard_add_step1.php')   ){

        $page_lat   = esc_html( get_option('wp_estate_general_latitude','') );
        $page_long  = esc_html( get_option('wp_estate_general_longitude','') );
        wp_enqueue_script('wpestate_google_map_submit', trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_submit.js',array('jquery'), '1.0', true);  
        wp_localize_script('wpestate_google_map_submit', 'google_map_submit_vars', 
            array(  'general_latitude'  =>  $page_lat,
                    'general_longitude' =>  $page_long,    
                    'geo_fails'        =>  esc_html__( 'Geolocation was not successful for the following reason:','wpestate') 
                 )
        ); 
    }
      
      
    if(is_page_template('user_dashboard_allinone.php') || is_page_template('user_dashboard_edit_listing.php') || is_page_template('user_dashboard_add_step1.php') ||  ( 'estate_property' == get_post_type() )  ){
   
        $custom_fields          =   get_option( 'wp_estate_custom_fields', true);  
        $tranport_custom_array  =   array();
        $i=0;
        if( !empty($custom_fields)){  
            while($i< count($custom_fields) ){
                $name  =   $custom_fields[$i][0];
                $label =   $custom_fields[$i][1];
                $type  =   $custom_fields[$i][2];
                $slug  =   str_replace(' ','_',$name);

                $slug         =   wpestate_limit45(sanitize_title( $name ));
                $slug         =   sanitize_key($slug);
                $i++;
                $tranport_custom_array[]=$slug;
           }
        }
        
        $feature_list_array             =   array();
        $feature_list                   =   esc_html( get_option('wp_estate_feature_list') );
        $feature_list_array             =   explode( ',',$feature_list);
        $moving_array_amm               =   array();
        foreach($feature_list_array as $key => $value){
            $post_var_name      =   str_replace(' ','_', trim($value) );
            $post_var_name      =   wpestate_limit45(sanitize_title( $post_var_name ));
            $post_var_name      =   sanitize_key($post_var_name);
            $moving_array_amm[] =   $post_var_name;           
        }
            
            
        wp_enqueue_script('wpestate_ajaxcalls_add', trailingslashit( get_template_directory_uri() ).'js/ajaxcalls_add.js',array('jquery'), '1.0', true);   
        wp_localize_script('wpestate_ajaxcalls_add', 'ajaxcalls_add_vars', 
            array(  'admin_url'                 =>  get_admin_url(),
                    'tranport_custom_array'     =>  json_encode($tranport_custom_array),  
                    'transport_custom_array_amm'=>  json_encode($moving_array_amm)
            )
        );
    
    }

    if ( is_user_logged_in() ) {
        $logged_in="yes";
    } else {
         $logged_in="no";
    }
    if( 'estate_property' == get_post_type() ||  'estate_agent' == get_post_type() ){
        wp_enqueue_script('wpestate_property', trailingslashit( get_template_directory_uri() ).'js/property.js',array('jquery'), '1.0', true);   
        wp_localize_script('wpestate_property', 'property_vars', 
            array(  'plsfill'                 =>    esc_html__( 'Please fill all the forms:','wpestate'),
                    'sending'                 =>    esc_html__( 'Sending Request...','wpestate'),
                    'logged_in'               =>    $logged_in,
                    'notlog'                  =>    esc_html__( 'You need to log in order to book a listing!','wpestate'),
                    'viewless'                =>    esc_html__( 'View less','wpestate'),
                    'viewmore'                =>    esc_html__( 'View more','wpestate'),
                    'nostart'                 =>    esc_html__( 'Check in date cannot be bigger than Check out date','wpestate'),
                    'noguest'                 =>    esc_html__('Please select the number of guests','wpestate'),
                    'guestoverload'           =>    esc_html__('The number of guest is bigger than the property capacity - ','wpestate'),
                    'guests'                  =>    esc_html__('guests','wpestate'),
   
               )
        );
                
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////file upload ajax - profile and user dashboard
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    if( is_page_template('user_dashboard_profile.php') || is_page_template('user_dashboard_edit_listing.php')   ){
        $plup_url = add_query_arg( array(
            'action' => 'wpestate_me_upload',
            'nonce' => wp_create_nonce('aaiu_allow'),
        ), admin_url('admin-ajax.php') );
                
                
        wp_enqueue_script('ajax-upload', trailingslashit( get_template_directory_uri() ).'js/ajax-upload.js',array('jquery','plupload-handlers'), '1.0', true);  
        wp_localize_script('ajax-upload', 'ajax_vars', 
            array(  'ajaxurl'           => admin_url('admin-ajax.php'),
                    'nonce'             => wp_create_nonce('aaiu_upload'),
                    'remove'            => wp_create_nonce('aaiu_remove'),
                    'number'            => 1,
                    'upload_enabled'    => true,
                    'path'              =>  trailingslashit( get_template_directory_uri() ),
                    'confirmMsg'        => esc_html__( 'Are you sure you want to delete this?','wpestate'),
                    'plupload'         => array(
                                            'runtimes'          => 'html5,flash,html4',
                                            'browse_button'     => 'aaiu-uploader',
                                            'container'         => 'aaiu-upload-container',
                                            'file_data_name'    => 'aaiu_upload_file',
                                            'max_file_size'     => $max_file_size . 'b',
                                            'url'               => $plup_url,
                                            'flash_swf_url'     => includes_url('js/plupload/plupload.flash.swf'),
                                            'filters'           => array(array('title' => esc_html__( 'Allowed Files','wpestate'), 'extensions' => "jpeg,jpg,gif,png,pdf")),
                                            'multipart'         => true,
                                            'urlstream_upload'  => true,
                                            )
                
                )
                );
    }
     

     
     
    if ( is_singular() && get_option( 'thread_comments' ) ){
        wp_enqueue_script( 'comment-reply' );
    }
    
    
    if( get_post_type() === 'estate_property' && !is_tax() ){
        wp_enqueue_script('wpestate_property',trailingslashit( get_template_directory_uri() ).'js/property.js',array('jquery'), '1.0', true); 
    }
   
    $protocol = is_ssl() ? 'https' : 'http';
    $general_font = esc_html( get_option('wp_estate_general_font', '') );
    
    $headings_font_subset   =   esc_html ( get_option('wp_estate_headings_font_subset','') );
    if($headings_font_subset!=''){
        $headings_font_subset='&amp;subset='.$headings_font_subset;
    }
   
    // embed custom fonts from admin
    if($general_font && $general_font!='x'){
        $general_font =  str_replace(' ', '+', $general_font);
        wp_enqueue_style( 'wpestate-custom-font',"$protocol://fonts.googleapis.com/css?family=$general_font:300,400,700,900$headings_font_subset");  
    }else{      
        wp_enqueue_style( 'wpestate-railway', "$protocol://fonts.googleapis.com/css?family=Raleway:500,600,400,700,800&amp;subset=latin,latin-ext" );
        wp_enqueue_style( 'wpestate-opensans', "$protocol://fonts.googleapis.com/css?family=Open+Sans:400,600,300&amp;subset=latin,latin-ext" );
   
        
    }

   
    $headings_font = esc_html( get_option('wp_estate_headings_font', '') );
    if($headings_font && $headings_font!='x'){
       $headings_font =  str_replace(' ', '+', $headings_font);
       wp_enqueue_style( 'wpestate-custom-secondary-font', "$protocol://fonts.googleapis.com/css?family=$headings_font:400,500,300" );  
    }
    
    
    
    wp_enqueue_style( 'wpestate_font-awesome.min',  trailingslashit( get_template_directory_uri() ) . 'css/fontawesome/css/font-awesome.min.css' );  
    if(!is_search() && !is_404() && !is_tax() && !is_category() && !is_tag()){
        if( wpestate_check_if_admin_page($post->ID) ){

                $wp_estate_book_down=get_option('wp_estate_book_down', '');
                if($wp_estate_book_down==''){
                    $wp_estate_book_down=10;
                }
                $book_down_fixed_fee            =   floatval( get_option('wp_estate_book_down_fixed_fee','') );
             
                
                wp_enqueue_script('wpestate_dashboard-control', trailingslashit( get_template_directory_uri() ).'js/dashboard-control.js',array('jquery'), '1.0', true);   
                wp_localize_script('wpestate_dashboard-control', 'dashboard_vars', 
                    array(  'deleting'                  =>  esc_html__( 'deleting...','wpestate'),
                            'searchtext2'               =>  esc_html__( 'Search here...','wpestate'),
                            'currency_symbol'           =>  wpestate_curency_submission_pick(),
                            'where_currency_symbol'     =>  esc_html( get_option('wp_estate_where_currency_symbol', '') ),
                            'book_down'                 =>  $wp_estate_book_down,
                            'book_down_fixed_fee'       =>  $book_down_fixed_fee,
                            'discount'                  =>  esc_html__( 'Discount','wpestate'),
                            'delete_inv'                =>  esc_html__( 'Delete Invoice','wpestate'),
                            'issue_inv'                 =>  esc_html__( 'Invoice Issued','wpestate'),
                            'confirmed'                 =>  esc_html__( 'Confirmed','wpestate'),
                            'issue_inv1'                =>  esc_html__( 'Issue invoice','wpestate'),
                            'sending'                   =>  esc_html__( 'sending message...','wpestate'),
                            'plsfill'                   =>  esc_html__( 'Please fill in all the fields','wpestate'),
                            'datesb'                    =>  esc_html__( 'Dates are already booked. Please check the calendar for free days!','wpestate'),
                            'datepast'                  =>  esc_html__( 'You cannot select a date in the past! ','wpestate'),
                            'bookingstart'              =>  esc_html__( 'Start date cannot be greater than end date !','wpestate'),
                            'selectprop'                =>  esc_html__( 'Please select a property !','wpestate'),
                            'err_title'                 =>  esc_html__( 'Please submit a title !','wpestate'),
                            'err_category'              =>  esc_html__( 'Please pick a category !','wpestate'),
                            'err_type'                  =>  esc_html__( 'Please pick a typr !','wpestate'),
                            'err_guest'                 =>  esc_html__( 'Please select the guest no !','wpestate'),
                            'err_city'                  =>  esc_html__( 'Please pick a city !','wpestate'),
                            'sending'                   =>  esc_html__( 'sending...','wpestate'),
                            'doublebook'                =>  esc_html__( 'This period is already booked','wpestate'),
                          
                    )       
                );

        }
    }   
    if(get_option('wp_estate_use_captcha','')=='yes'){
        wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js?onload=wpestate_onloadCallback&render=explicit&hl=iw" async defer',array('jquery'), '1.0', true);        
    }

}
endif; // end   wpestate_scripts  







///////////////////////////////////////////////////////////////////////////////////////////
/////// Js & Css include on admin site 
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_admin') ):

function wpestate_admin($hook_suffix) {	
    global $post;            
    global $pagenow;
    global $typenow;
    
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_script('my-upload'); 
    wp_enqueue_style('thickbox');
    wp_enqueue_script("jquery-ui-autocomplete");
    wp_enqueue_style('wpestate_adminstyle', trailingslashit( get_template_directory_uri() ) . '/css/admin.css');
    wp_enqueue_script('wpestate_admin-control', trailingslashit( get_template_directory_uri() ).'/js/admin-control.js',array('jquery'), '1.0', true);     
    wp_localize_script('wpestate_admin-control', 'admin_control_vars', 
        array( 'ajaxurl'            => admin_url('admin-ajax.php'))
    );
    
    if($hook_suffix=='post-new.php' || $hook_suffix=='post.php'){
        wp_enqueue_script("jquery-ui-datepicker");
        wp_enqueue_style( 'font-awesome.min',  trailingslashit( get_template_directory_uri() ) . '/css/fontawesome/css/font-awesome.min.css' );  
        wp_enqueue_style('jquery.ui.theme', trailingslashit( get_template_directory_uri() ). '/css/jquery-ui.min.css');
    }

    if (empty($typenow) && !empty($_GET['post'])) {
        $allowed_html   =   array();
        $post = get_post(wp_kses($_GET['post'],$allowed_html));
        $typenow = $post->post_type;
    }

    
    if (is_admin() &&  ( $pagenow=='post-new.php' || $pagenow=='post.php') && $typenow=='estate_property') {
        if (esc_html ( get_option('wp_estate_ssl_map','') ) =='yes'){
            wp_enqueue_script('wpestate_googlemap',      'https://maps-api-ssl.google.com/maps/api/js?key='.esc_html(get_option('wp_estate_api_key', '') ).'&amp;sensor=true',array('jquery'), '1.0', false);
        }else{
            wp_enqueue_script('wpestate_googlemap',      'http://maps.googleapis.com/maps/api/js?key='.esc_html(get_option('wp_estate_api_key', '') ).'&amp;sensor=true',array('jquery'), '1.0', false);
        }  
        wp_enqueue_script('wpestate_admin_google',   trailingslashit( get_template_directory_uri() ).'js/google_js/admin_google.js',array('jquery'), '1.0', true); 
           
                     
        $wp_estate_general_latitude  = floatval(get_post_meta($post->ID, 'property_latitude', true));
        $wp_estate_general_longitude = floatval(get_post_meta($post->ID, 'property_longitude', true));

        if ($wp_estate_general_latitude=='' || $wp_estate_general_longitude=='' ){
            $wp_estate_general_latitude    = esc_html( get_option('wp_estate_general_latitude','') ) ;
            $wp_estate_general_longitude   = esc_html( get_option('wp_estate_general_longitude','') );

            if($wp_estate_general_latitude==''){
               $wp_estate_general_latitude ='40.781711';
            }

            if($wp_estate_general_longitude==''){ 
               $wp_estate_general_longitude='-73.955927';  
            }
        }
        
        wp_localize_script('wpestate_admin_google', 'admin_google_vars', 
        array(  'general_latitude'  =>  $wp_estate_general_latitude,
                'general_longitude' =>  $wp_estate_general_longitude,
                'postId'=>$post->ID,
                'geo_fails'        =>  esc_html__( 'Geolocation was not successful for the following reason:','wpestate') 
              )
        );
     }

    $admin_pages = array('appearance_page_libs/theme-admin');
 
    if(in_array($hook_suffix, $admin_pages)) {
        wp_enqueue_script('wpestate_admin', trailingslashit( get_template_directory_uri() ).'/js/admin.js',array('jquery'), '1.0', true); 
        wp_enqueue_style ('wpestate_colorpicker_css', trailingslashit( get_template_directory_uri() ).'/css/colorpicker.css', false, '1.0', 'all');
        wp_enqueue_script('wpestate_admin_colorpicker', trailingslashit( get_template_directory_uri() ).'/js/admin_colorpicker.js',array('jquery'), '1.0', true);
        wp_enqueue_script('wpestate_config-property', trailingslashit( get_template_directory_uri() ).'/js/config-property.js',array('jquery'), '1.0', true);          
    }
   
}

endif; // end   wpestate_admin  
?>