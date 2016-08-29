<?php
// Single property
// Wp Estate Pack
get_header();

global $feature_list_array;
global $propid ;
global $post_attachments;
global $options;

global $where_currency;
global $property_description_text;     
global $property_details_text;
global $property_features_text;
global $property_adr_text;  
global $property_price_text;   
global $property_pictures_text;    
global $propid;
global $gmap_lat;  
global $gmap_long;
global $unit;
global $currency;
global $use_floor_plans;
        
$current_user = wp_get_current_user();
$propid                     =   $post->ID;
$options                    =   wpestate_page_details($post->ID);
$gmap_lat                   =   floatval( get_post_meta($post->ID, 'property_latitude', true));
$gmap_long                  =   floatval( get_post_meta($post->ID, 'property_longitude', true));
$unit                       =   esc_html( get_option('wp_estate_measure_sys', '') );
$currency                   =   esc_html( get_option('wp_estate_currency_symbol', '') );
$use_floor_plans            =   intval( get_post_meta($post->ID, 'use_floor_plans', true) );      


if (function_exists('icl_translate') ){
    $where_currency             =   icl_translate('wpestate','wp_estate_where_currency_symbol', esc_html( get_option('wp_estate_where_currency_symbol', '') ) );
    $property_description_text  =   icl_translate('wpestate','wp_estate_property_description_text', esc_html( get_option('wp_estate_property_description_text') ) );
    $property_details_text      =   icl_translate('wpestate','wp_estate_property_details_text', esc_html( get_option('wp_estate_property_details_text') ) );
    $property_features_text     =   icl_translate('wpestate','wp_estate_property_features_text', esc_html( get_option('wp_estate_property_features_text') ) );
    $property_adr_text          =   icl_translate('wpestate','wp_estate_property_adr_text', esc_html( get_option('wp_estate_property_adr_text') ) );  
    $property_price_text        =   icl_translate('wpestate','wp_estate_property_price_text', esc_html( get_option('wp_estate_property_price_text') ) ); 
    $property_pictures_text     =   icl_translate('wpestate','wp_estate_property_pictures_text', esc_html( get_option('wp_estate_property_pictures_text') ) );  
}else{
    $where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
    $property_description_text  =   esc_html( get_option('wp_estate_property_description_text') );
    $property_details_text      =   esc_html( get_option('wp_estate_property_details_text') );
    $property_features_text     =   esc_html( get_option('wp_estate_property_features_text') );
    $property_adr_text          =   stripslashes ( esc_html( get_option('wp_estate_property_adr_text') ) );
    $property_price_text        =   esc_html( get_option('wp_estate_property_price_text') );
    $property_pictures_text     =   esc_html( get_option('wp_estate_property_pictures_text') ); 
}


$agent_id                   =   '';
$content                    =   '';
$userID                     =   $current_user->ID;
$user_option                =   'favorites'.$userID;
$curent_fav                 =   get_option($user_option);
$favorite_class             =   'isnotfavorite'; 
$favorite_text              =   esc_html__( 'Add to Favorites','wpestate');
$feature_list               =   esc_html( get_option('wp_estate_feature_list') );
$feature_list_array         =   explode( ',',$feature_list);
$pinteres                   =   array();
$property_city              =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
$property_area              =   get_the_term_list($post->ID, 'property_area', '', ', ', '');



$property_category          =   get_the_term_list($post->ID, 'property_category', '', ', ', '') ;
$property_category_terms    =   get_the_terms( $post->ID, 'property_category' );

if(is_array($property_category_terms) ){
    $temp                       =   array_pop($property_category_terms);
    $property_category_terms_icon =   $temp->slug;
    $place_id                   =   $temp->term_id;
    $term_meta                  =   get_option( "taxonomy_$place_id");
    if( isset($term_meta['category_icon_image']) && $term_meta['category_icon_image']!='' ){
        $property_category_terms_icon=$term_meta['category_icon_image'];
    }else{
        $property_category_terms_icon =  get_template_directory_uri().'/img/'.$temp->slug.'-ico.png';
    }
}



$property_action            =   get_the_term_list($post->ID, 'property_action_category', '', ', ', '');   
$property_action_terms      =   get_the_terms( $post->ID, 'property_action_category' );

if(is_array($property_action_terms) ){
    $temp                       =   array_pop($property_action_terms);
    $place_id                   =   $temp->term_id;
    $term_meta                  =   get_option( "taxonomy_$place_id");
    if( isset($term_meta['category_icon_image']) && $term_meta['category_icon_image']!='' ){
        $property_action_terms_icon=$term_meta['category_icon_image'];
    }else{
        $property_action_terms_icon =  get_template_directory_uri().'/img/'.$temp->slug.'-ico.png';
    }
}

$slider_size                =   'small';
$guests                     =   floatval( get_post_meta($post->ID, 'guest_no', true));
$bedrooms                   =   floatval( get_post_meta($post->ID, 'property_bedrooms', true));
$bathrooms                  =   floatval( get_post_meta($post->ID, 'property_bathrooms', true));

$status = stripslashes( esc_html( get_post_meta($post->ID, 'property_status', true) ) );    
if (function_exists('icl_translate') ){
    $status     =   icl_translate('wpestate','wp_estate_property_status_'.$status, $status ) ;                                      
}

if($curent_fav){
    if ( in_array ($post->ID,$curent_fav) ){
        $favorite_class =   'isfavorite';     
        $favorite_text  =   esc_html__( 'Favorite','wpestate').'<i class="fa fa-heart"></i>';
    } 
}

if (has_post_thumbnail()){
    $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(),'wpestate_property_full_map');
}


if($options['content_class']=='col-md-12'){
    $slider_size='full';
}


 $listing_page_type    =   get_option('wp_estate_listing_page_type','');

 if($listing_page_type == 2){
    get_template_part('templates/listing_page_2');
 }else{
    get_template_part('templates/listing_page_1');
 }
 
?>



<?php


    if ( isset($_GET['check_in_prop']) && isset($_GET['check_out_prop'])  && isset($_GET['guest_no_prop'])   ){

        print '<script type="text/javascript">
                //<![CDATA[
                jQuery(document).ready(function(){
                    jQuery("#end_date,#start_date").parent().removeClass("calendar_icon");
                    jQuery("#end_date").trigger("change");
                });
                //]]>
        </script>';

    }
//if( is_user_logged_in() ){}
?>