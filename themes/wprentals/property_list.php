<?php
// Template Name: Properties list
// Wp Estate Pack 
get_header();
$options        =   wpestate_page_details($post->ID);
$filtred        =   0;
$compare_submit =   wpestate_get_compare_link();


// get curency , currency position and no of items per page
$current_user = wp_get_current_user();
$currency                   =   esc_html( get_option('wp_estate_currency_symbol', '') );
$where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$prop_no                    =   intval( get_option('wp_estate_prop_no', '') );
$userID                     =   $current_user->ID;
$user_option                =   'favorites'.$userID;
$curent_fav                 =   get_option($user_option);
$icons                      =   array();
$taxonomy                   =   'property_action_category';
$tax_terms                  =   get_terms($taxonomy);
$taxonomy_cat               =   'property_category';
$categories                 =   get_terms($taxonomy_cat);
$show_compare=1;


$current_adv_filter_search_action       = get_post_meta ( $post->ID, 'adv_filter_search_action', true);
$current_adv_filter_search_category     = get_post_meta ( $post->ID, 'adv_filter_search_category', true);
$current_adv_filter_area                = get_post_meta ( $post->ID, 'current_adv_filter_area', true);
$current_adv_filter_city                = get_post_meta ( $post->ID, 'current_adv_filter_city', true);

$show_featured_only                     = get_post_meta($post->ID, 'show_featured_only', true);
$show_filter_area                       = get_post_meta($post->ID, 'show_filter_area', true);

$area_array     =   '';  
$city_array     =   '';  
$action_array   =   ''; 
$categ_array    =   '';

/////////////////////////////////////////////////////////////////////////action
if (!empty($current_adv_filter_search_action) && $current_adv_filter_search_action[0]!='all'){
    $taxcateg_include   =   array();

    foreach($current_adv_filter_search_action as $key=>$value){
        $taxcateg_include[]=sanitize_title($value);
    }

    $categ_array=array(
        'taxonomy'  => 'property_action_category',
        'field'     => 'slug',
        'terms'     => $taxcateg_include
    );
    $current_adv_filter_search_label= $current_adv_filter_search_action[0];
}else{
    $current_adv_filter_search_label=esc_html__( 'All Sizes','wpestate');
}
      


/////////////////////////////////////////////////////////////////////////category
if ( !empty($current_adv_filter_search_category) && $current_adv_filter_search_category[0]!='all' ){
    $taxaction_include   =   array();   

    foreach( $current_adv_filter_search_category as $key=>$value){
        $taxaction_include[]=sanitize_title($value);
    }

    $action_array=array(
        'taxonomy' => 'property_category',
        'field' => 'slug',
        'terms' => $taxaction_include
    );
    $current_adv_filter_category_label=$current_adv_filter_search_category[0];
}else{
    $current_adv_filter_category_label=esc_html__( 'All Types','wpestate');
}

/////////////////////////////////////////////////////////////////////////////

if ( !empty( $current_adv_filter_city ) && $current_adv_filter_city[0]!='all' ) {
    $taxaction_include   =   array();   

    foreach( $current_adv_filter_city as $key=>$value){
        $taxaction_include[]=sanitize_title($value);
    }
    
    $city_array = array(
        'taxonomy' => 'property_city',
        'field' => 'slug',
        'terms' => $taxaction_include
    );
    
    $current_adv_filter_city_label=$current_adv_filter_city[0];
}else{
    $current_adv_filter_city_label=esc_html__( 'All Cities','wpestate');
}


/////////////////////////////////////////////////////////////////////////////

if ( !empty( $current_adv_filter_area ) && $current_adv_filter_area[0]!='all' ) {
    $taxaction_include   =   array();   

    foreach( $current_adv_filter_area as $key=>$value){
        $taxaction_include[]=sanitize_title($value);
    }
    
    $area_array = array(
        'taxonomy' => 'property_area',
        'field' => 'slug',
        'terms' => $taxaction_include
    );
    
    $current_adv_filter_area_label=$current_adv_filter_area[0];
}else{
    $current_adv_filter_area_label=esc_html__( 'All Areas','wpestate');
}
  
 

/////////////////////////////////////////////////////////////////////////////

$meta_query=array();                
if($show_featured_only=='yes'){
    $compare_array=array();
    $compare_array['key']        = 'prop_featured';
    $compare_array['value']      = 1;
    $compare_array['type']       = 'numeric';
    $compare_array['compare']    = '=';
    $meta_query[]                = $compare_array;
}
     
$meta_directions    =   'DESC';
$meta_order         =   'prop_featured';
$order              =   get_post_meta($post->ID, 'listing_filter',true );
switch ($order){
    case 1:
        $meta_order='property_price';
        $meta_directions='DESC';
        break;
    case 2:
        $meta_order='property_price';
        $meta_directions='ASC';
        break;
    case 3:
        $meta_order='property_size';
        $meta_directions='DESC';
        break;
    case 4:
        $meta_order='property_size';
        $meta_directions='ASC';
        break;
    case 5:
        $meta_order='property_bedrooms';
        $meta_directions='DESC';
        break;
    case 6:
        $meta_order='property_bedrooms';
        $meta_directions='ASC';
        break;
}

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if( is_front_page() ){
     global $paged;
    $paged= (get_query_var('page')) ? get_query_var('page') : 1;
}
  wp_reset_query();          

        
$args = array(
      'post_type'         => 'estate_property',
      'post_status'       => 'publish',
      'paged'             => $paged,
      'posts_per_page'    => $prop_no,
      'orderby'           => 'meta_value_num',
      'meta_key'          => $meta_order,
      'order'             => $meta_directions,
      'meta_query'        => $meta_query,
      'tax_query'         => array(
                                'relation' => 'AND',
                                $categ_array,
                                $action_array,
                                $city_array,
                                $area_array
                            )
);



if( $order==0 ){
    add_filter( 'posts_orderby', 'wpestate_my_order' );
    $prop_selection = new WP_Query($args);
    remove_filter( 'posts_orderby', 'wpestate_my_order' );
}else{
    $prop_selection = new WP_Query($args);
}
   
get_template_part('templates/normal_map_core');
get_footer(); 
?>