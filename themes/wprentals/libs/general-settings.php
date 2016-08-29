<?php

if(!function_exists('wpestate_price_default_convert') ):
function wpestate_price_default_convert($price){
    
    $custom_fields = get_option( 'wp_estate_multi_curr', true);
    if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
        $i=intval($_COOKIE['my_custom_curr_pos']);
        return $price* $custom_fields[$i][2];
    } else{
        return $price;
    }
}
endif;


if( !function_exists('wpestate_show_price_label_slider') ):
function wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$currency,$where_currency){
    $th_separator       =   get_option('wp_estate_prices_th_separator','');
    $custom_fields = get_option( 'wp_estate_multi_curr', true);
    //print_r($_COOKIE);
    if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
        $i=intval($_COOKIE['my_custom_curr_pos']);
        $min_price_slider       =   $min_price_slider * $custom_fields[$i][2];
        $max_price_slider       =   $max_price_slider * $custom_fields[$i][2];
        
        $currency               =   $custom_fields[$i][0];
        $min_price_slider   =   number_format($min_price_slider,0,'.',$th_separator);
        $max_price_slider   =   number_format($max_price_slider,0,'.',$th_separator);
        
        if ($custom_fields[$i][3] == 'before') {  
            $price_slider_label = $currency .' '. $min_price_slider.' '.esc_html__( 'to','wpestate').' '.$currency .' '. $max_price_slider;      
        } else {
            $price_slider_label =  $min_price_slider.' '.$currency.' '.esc_html__( 'to','wpestate').' '.$max_price_slider.' '.$currency;      
        }
        
    }else{
        $min_price_slider   =   number_format($min_price_slider,0,'.',$th_separator);
        $max_price_slider   =   number_format($max_price_slider,0,'.',$th_separator);
        
        if ($where_currency == 'before') {
            $price_slider_label = $currency .' '. ($min_price_slider).' '.esc_html__( 'to','wpestate').' '.$currency .' '. $max_price_slider;
        } else {
            $price_slider_label =  $min_price_slider.' '.$currency.' '.esc_html__( 'to','wpestate').' '.$max_price_slider.' '.$currency;
        }  
    }
    
    return $price_slider_label;
                            
    
}
endif;

///////////////////////////////////////////////////////////////////////////////////////////
/////// disable toolbar for subscribers
///////////////////////////////////////////////////////////////////////////////////////////

if (!current_user_can('manage_options') ) { show_admin_bar(false); }

///////////////////////////////////////////////////////////////////////////////////////////
/////// Define thumb sizes
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_image_size') ):
    function wpestate_image_size(){
        add_image_size('wpestate_blog_unit'           , 400 , 242, true); // 1.45 387 234 1.65
        add_image_size('wpestate_blog_unit2'           , 805 , 453, true); // 1.45 387 234 1.65       
        add_image_size('wpestate_slider_thumb'        , 143,  83, true); //
        add_image_size('wpestate_property_listings'   , 400, 314, true); // 1.6 590, 362, 1.27 386, 302
        add_image_size('wpestate_property_featured'   , 1170, 921, true); // 1.27
        add_image_size('wpestate_property_listings_page'   , 240, 160, true); // 1.6 590, 362, 1.27 386, 302
        add_image_size('wpestate_property_places'   , 600, 456, true);//1.315
        add_image_size('wpestate_property_full_map'   , 1920, 790, true);//2.4
        add_image_size('wpestate_user_thumb'          , 60, 60, true);
        set_post_thumbnail_size(  250, 220, true);
    }
endif;
///////////////////////////////////////////////////////////////////////////////////////////
/////// register sidebars
///////////////////////////////////////////////////////////////////////////////////////////



if( !function_exists('wpestate_widgets_init') ):
function wpestate_widgets_init() {
    register_nav_menu( 'primary', esc_html__(  'Primary Menu', 'wpestate' ) ); 
    register_nav_menu( 'mobile', esc_html__(  'Mobile Menu', 'wpestate' ) ); 
    register_nav_menu( 'footer_menu', esc_html__(  'Footer Menu', 'wpestate' ) ); 
    
    register_sidebar(array(
        'name' => esc_html__( 'Primary Widget Area', 'wpestate'),
        'id' => 'primary-widget-area',
        'description' => esc_html__( 'The primary widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-sidebar">',
        'after_title' => '</h3>',
    ));


    register_sidebar(array(
        'name' => esc_html__( 'Secondary Widget Area', 'wpestate'),
        'id' => 'secondary-widget-area',
        'description' => esc_html__( 'The secondary widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-sidebar">',
        'after_title' => '</h3>',
    ));


    register_sidebar(array(
        'name' => esc_html__( 'First Footer Widget Area', 'wpestate'),
        'id' => 'first-footer-widget-area',
        'description' => esc_html__( 'The first footer widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-footer">',
        'after_title' => '</h3>',
    ));


    register_sidebar(array(
        'name' => esc_html__( 'Second Footer Widget Area', 'wpestate'),
        'id' => 'second-footer-widget-area',
        'description' => esc_html__( 'The second footer widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-footer">',
        'after_title' => '</h3>',
    ));


    register_sidebar(array(
        'name' => esc_html__( 'Third Footer Widget Area', 'wpestate'),
        'id' => 'third-footer-widget-area',
        'description' => esc_html__( 'The third footer widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-footer">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__( 'Top Bar Left Widget Area', 'wpestate'),
        'id' => 'top-bar-left-widget-area',
        'description' => esc_html__( 'The top bar left widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-topbar">',
        'after_title' => '</h3>',
    ));
       
    register_sidebar(array(
        'name' => esc_html__( 'Top Bar Right Widget Area', 'wpestate'),
        'id' => 'top-bar-right-widget-area',
        'description' => esc_html__( 'The top bar right widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
       'before_title' => '<h3 class="widget-title-topbar">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => esc_html__( 'Owner Page', 'wpestate'),
        'id' => 'owner-page-widget-area',
        'description' => esc_html__( 'Owner page widget area', 'wpestate'),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title-sidebar">',
        'after_title' => '</h3>',
    ));
}
endif; // end   wpestate_widgets_init  


/////////////////////////////////////////////////////////////////////////////////////////
///// custom excerpt
/////////////////////////////////////////////////////////////////////////////////////////



if( !function_exists('wp_estate_excerpt_length') ):
    function wp_estate_excerpt_length($length) {
        return 20;
    }
endif; // end   wp_estate_excerpt_length  


/////////////////////////////////////////////////////////////////////////////////////////
///// custom excerpt more
/////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_new_excerpt_more') ):
    function wpestate_new_excerpt_more( $more ) {
            return ' ...';
    }
endif; // end   wpestate_new_excerpt_more  



/////////////////////////////////////////////////////////////////////////////////////////
///// strip words
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_strip_words') ):
    function wpestate_strip_words($text, $words_no) {
        $temp = explode(' ', $text, ($words_no + 1));
        if (count($temp) > $words_no) {
            array_pop($temp);
        }
        return implode(' ', $temp);
    }
endif; // end   wpestate_strip_words 

/////////////////////////////////////////////////////////////////////////////////////////
///// add extra div for wp embeds
/////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_embed_html') ):
    function wpestate_embed_html( $html ) {
        if (strpos($html,'twitter') !== false) {
            return '<div class="video-container-tw">' . $html . '</div>';
        }else{
            return '<div class="video-container">' . $html . '</div>';
        }
    }
endif;
add_filter( 'embed_oembed_html', 'wpestate_embed_html', 10, 3 );
add_filter( 'video_embed_html', 'wpestate_embed_html' ); // Jetpack

/////////////////////////////////////////////////////////////////////////////////////////
///// html in conmment
/////////////////////////////////////////////////////////////////////////////////////////
//add_action('init', 'wpestate_html_tags_code', 10);
if( !function_exists('wpestate_html_tags_code') ):
    function wpestate_html_tags_code() {
        global $allowedposttags, $allowedtags;
        $allowedposttags = array(
            'strong' => array(),
            'em' => array(),
            'pre' => array(),
            'code' => array(),
            'a' => array(
              'href' => array (),
              'title' => array ())
        );

        $allowedtags = array(
            'strong' => array(),
            'em' => array(),
            'pre' => array(),
            'code' => array(),
            'a' => array(
              'href' => array (),
              'title' => array ())
        );
    }
endif;
?>