<?php
// Template Name: Advanced Search Results
// Wp Estate Pack

get_header();
$current_user = wp_get_current_user();
$options        =   wpestate_page_details($post->ID);
$show_compare   =   1;
$area_array     =   ''; 
$city_array     =   '';  
$action_array   =   '';
$categ_array    =   '';
$tax_query       =   '';
$allowed_html=array();
$compare_submit         =   wpestate_get_compare_link();
$currency               =   esc_html( get_option('wp_estate_currency_symbol', '') );
$where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
$prop_no                =   intval ( get_option('wp_estate_prop_no', '') );
$show_compare_link      =   'yes';
$userID                 =   $current_user->ID;
$user_option            =   'favorites'.$userID;
$curent_fav             =   get_option($user_option);
$custom_advanced_search =   get_option('wp_estate_custom_advanced_search','');
$meta_query             =   array();
           
$adv_search_what        =   '';
$adv_search_how         =   '';
$adv_search_label       =   '';             
$adv_search_type        =   '';   

$book_from      =   '';
$book_to        =   '';
$allowed_html   =   array();
if( isset($_GET['check_in'])){
    $book_from      =  sanitize_text_field( wp_kses ( $_GET['check_in'],$allowed_html) );
}
if( isset($_GET['check_out'])){
    $book_to        =  sanitize_text_field( wp_kses ( $_GET['check_out'],$allowed_html) );
}
                      
     







$show_adv_search_general            =   get_option('wp_estate_wpestate_autocomplete','');
if($show_adv_search_general=='no'){
    
        if( esc_html($_GET['stype'])=='tax' ){
            $stype='tax';
                //////////////////////////////////////////////////////////////////////////////////////
                ///// city filters 
                //////////////////////////////////////////////////////////////////////////////////////
                if (isset($_GET['search_location']) and $_GET['search_location'] != 'all' && $_GET['search_location'] != '') {
                    $taxcity[] = sanitize_title ( wp_kses($_GET['search_location'],$allowed_html)  );
                    $city_array = array(
                        'taxonomy'     => 'property_city',
                        'field'        => 'slug',
                        'terms'        => $taxcity
                    );
                }
                //////////////////////////////////////////////////////////////////////////////////////
                ///// area filters 
                //////////////////////////////////////////////////////////////////////////////////////
                if (isset($_GET['search_location']) and $_GET['search_location'] != 'all' && $_GET['search_location'] != '') {
                    $taxarea[] = sanitize_title (   wp_kses($_GET['search_location'],$allowed_html) );
                    $area_array = array(
                        'taxonomy'     => 'property_area',
                        'field'        => 'slug',
                        'terms'        => $taxarea
                    );
                }

                $tax_query = array(
                    'relation' => 'OR',
                    $city_array,
                    $area_array
                    );

        }else{
                $stype='meta';
                $meta_query_part=array();
                $meta_query['relation']     =   'AND';
                if( isset($_GET['search_location'])  && $_GET['search_location']!='' ){
                    $search_string=sanitize_text_field ( wp_kses ($_GET['search_location'],$allowed_html) );
                    $search_string                     =   str_replace('-', ' ', $search_string);
                    $meta_query_part['relation']     =   'OR';
                    $country_array =array();
                    $country_array['key']        =   'property_country';
                    $country_array['value']      =   $search_string;
                    $country_array['type']       =   'CHAR';
                    $country_array['compare']    =   'LIKE'; 
                    $meta_query_part[]                =   $country_array;

                    $country_array =array();
                    $country_array['key']        =   'property_county';
                    $country_array['value']      =   $search_string;
                    $country_array['type']       =   'CHAR';
                    $country_array['compare']    =   'LIKE'; 
                    $meta_query_part[]           =   $country_array;

                    $country_array =array();
                    $country_array['key']        =   'property_state';
                    $country_array['value']      =   $search_string;
                    $country_array['type']       =   'CHAR';
                    $country_array['compare']    =   'LIKE'; 
                    $meta_query_part[]           =   $country_array;
                    $meta_query[]=$meta_query_part;
                    var_dump($meta_query_part);
                }
        }
    
        //////////////////////////////////////////////////////////////////////////////////////
        ///// guest meta
        //////////////////////////////////////////////////////////////////////////////////////
        $guest_array=array();
        if( isset($_GET['guest_no'])  && is_numeric($_GET['guest_no']) ){
            $guest_no       = intval($_GET['guest_no']);
            $guest_array['key']      = 'guest_no';
            $guest_array['value']    = $guest_no;
            $guest_array['type']     = 'numeric';
            $guest_array['compare']  = '>='; 
            $meta_query[]            = $guest_array;
        }
        //////////////////////////////////////////////////////////////////////////////////////
        ///// Rooms meta
        //////////////////////////////////////////////////////////////////////////////////////
        $room_array=array();
        if( isset($_GET['rooms_no'])  && is_numeric($_GET['rooms_no']) ){

            $rooms_no       = intval($_GET['rooms_no']);
            //var_dump($rooms_no);
            $room_array['key']      = 'property_rooms';
            $room_array['value']    = $rooms_no;
            $room_array['type']     = 'numeric';
            $room_array['compare']  = '=='; 
            $meta_query[]            = $room_array;
        }
        //////////////////////////////////////////////////////////////////////////////////////
        ///// Bathroom meta
        //////////////////////////////////////////////////////////////////////////////////////
        $bath_array=array();
        if( isset($_GET['baths_no'])  && is_numeric($_GET['baths_no']) ){

            $baths_no       = intval($_GET['baths_no']);
            //var_dump($baths_no);
            $bath_array['key']      = 'property_bathrooms';
            $bath_array['value']    = $rooms_no;
            $bath_array['type']     = 'numeric';
            $bath_array['compare']  = '=='; 
            $meta_query[]            = $bath_array;
        }

        //////////////////////////////////////////////////////////////////////////////////////
        ///// Plazo arrendamiento meta
        //////////////////////////////////////////////////////////////////////////////////////
        $plazo_arrendamiento_array=array();

        if( isset($_GET['plazo_arrendamiento']) ) {

            $plazo_arrendamiento       = intval($_GET['plazo_arrendamiento']);
            //var_dump($plazo_arriendamiento);
            //var_dump($baths_no);
            $plazo_arrendamiento_array['key']      = 'plazo-arrendamiento';
            $plazo_arrendamiento_array['value']    = $plazo_arrendamiento;
            $plazo_arrendamiento_array['type']     = 'numeric';
            $plazo_arrendamiento_array['compare']  = '=='; 
            $meta_query[]            = $plazo_arrendamiento_array;
        }
 
    //////////////////////////////////////////////////////////////////////////////////////
    ///// price filters 
    //////////////////////////////////////////////////////////////////////////////////////
    $price_low ='';
    $custom_fields = get_option( 'wp_estate_multi_curr', true);
    if( isset($_GET['price_low'])){
        $price_low         = intval($_GET['price_low']);
        $price['key']      = 'property_price';
        $price['value']    = $price_low;
         

        if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
            $i=intval($_COOKIE['my_custom_curr_pos']);
            if ($price_low != 0) {
                $price_low      = $price_low / $custom_fields[$i][2];
            }
        }
            
        $price['type']     = 'numeric';
        $price['compare']  = '>='; 
        $meta_query[]     = $price;
    }

    $price_max='';
    if( isset($_GET['price_max'])  && is_numeric($_GET['price_max']) ){
        $price_max         = intval($_GET['price_max']);
        $price['key']      = 'property_price';
        
         
        if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
            $i=intval($_COOKIE['my_custom_curr_pos']);
            
            if ($price_max != 0) {
                $price_max      = $price_max / $custom_fields[$i][2];
            }
        }
        
        $price['value']    = $price_max;
        $price['type']     = 'numeric';
        $price['compare']  = '<='; 
        $meta_query[] = $price;
    }
    
        if($paged>1){
            $meta_query= get_option('wpestate_pagination_meta_query','');
            $categ_array= get_option('wpestate_pagination_categ_query','');
            $action_array= get_option('wpestate_pagination_action_query','');
            $city_array= get_option('wpestate_pagination_city_query','');
            $area_array=get_option('wpestate_pagination_area_query','');
        }else{
            update_option('wpestate_pagination_meta_query',$meta_query);
            update_option('wpestate_pagination_categ_query',$categ_array);
            update_option('wpestate_pagination_action_query',$action_array);
            update_option('wpestate_pagination_city_query',$city_array);
            update_option('wpestate_pagination_area_query',$area_array);
        }

        
        
        if ( $book_from!='' && $book_from!='' ){          
            $args = array(
                'cache_results'           =>    false,
                'update_post_meta_cache'  =>    false,
                'update_post_term_cache'  =>    false,
                'post_type'               =>    'estate_property',
                'post_status'             =>    'publish',
                'posts_per_page'          =>    -1,
                'meta_key'                =>    'prop_featured',
                'orderby'                 =>    'meta_value',
                'order'                   =>    'DESC',
                'meta_query'              =>    $meta_query,
                'tax_query'               =>    $tax_query
                );
        }else{
            $args = array(
                'cache_results'           =>    false,
                'update_post_meta_cache'  =>    false,
                'update_post_term_cache'  =>    false,
                'post_type'               =>    'estate_property',
                'post_status'             =>    'publish',
                'paged'                   =>    $paged,
                'posts_per_page'          =>    $prop_no,
                'meta_key'                =>    'prop_featured',
                'orderby'                 =>    'meta_value',
                'order'                   =>    'DESC',
                'meta_query'              =>    $meta_query,
                'tax_query'               =>    $tax_query
                );
        }
    
    
    
  
    
}else{
    //////////////////////////////////////////////////////////////////////////////////////
    ///// city filters 
    //////////////////////////////////////////////////////////////////////////////////////

    if (isset($_GET['advanced_city']) and $_GET['advanced_city'] != 'all' && $_GET['advanced_city'] != '') {
        $taxcity[] = sanitize_title (    wp_kses($_GET['advanced_city'],$allowed_html) );
        $city_array = array(
            'taxonomy'     => 'property_city',
            'field'        => 'slug',
            'terms'        => $taxcity
        );
    }

    

    //////////////////////////////////////////////////////////////////////////////////////
    ///// area filters 
    //////////////////////////////////////////////////////////////////////////////////////

    if (isset($_GET['advanced_area']) and $_GET['advanced_area'] != 'all' && $_GET['advanced_area'] != '') {
        $taxarea[] = sanitize_title (  wp_kses($_GET['advanced_area'],$allowed_html) );
        $area_array = array(
            'taxonomy'     => 'property_area',
            'field'        => 'slug',
            'terms'        => $taxarea
        );
    }
    
    
    
    
    
    //////////////////////////////////////////////////////////////////////////////////////
    ///// price filters 
    //////////////////////////////////////////////////////////////////////////////////////
    $price_low ='';
    $custom_fields = get_option( 'wp_estate_multi_curr', true);
    if( isset($_GET['price_low'])){
        $price_low         = intval($_GET['price_low']);
        $price['key']      = 'property_price';
        $price['value']    = $price_low;
         

        if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
            $i=intval($_COOKIE['my_custom_curr_pos']);
            if ($price_low != 0) {
                $price_low      = $price_low / $custom_fields[$i][2];
            }
        }
            
        $price['type']     = 'numeric';
        $price['compare']  = '>='; 
        $meta_query[]     = $price;
    }

    $price_max='';
    if( isset($_GET['price_max'])  && is_numeric($_GET['price_max']) ){
        $price_max         = intval($_GET['price_max']);
        $price['key']      = 'property_price';
        
         
        if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
            $i=intval($_COOKIE['my_custom_curr_pos']);
            
            if ($price_max != 0) {
                $price_max      = $price_max / $custom_fields[$i][2];
            }
        }
        
        $price['value']    = $price_max;
        $price['type']     = 'numeric';
        $price['compare']  = '<='; 
        $meta_query[] = $price;
    }

    //////////////////////////////////////////////////////////////////////////////////////
    ///// guest meta
    //////////////////////////////////////////////////////////////////////////////////////

    $guest_array=array();
     if( isset($_GET['guest_no'])  && is_numeric($_GET['guest_no']) ){
            $guest_no       = intval($_GET['guest_no']);
            $guest_array['key']      = 'guest_no';
            $guest_array['value']    = $guest_no;
            $guest_array['type']     = 'numeric';
            $guest_array['compare']  = '>='; 
            $meta_query[]            = $guest_array;
        }


    $country_array=array();
    if( isset($_GET['advanced_country'])  && $_GET['advanced_country']!='' ){
        $country                     =   sanitize_text_field ( wp_kses ($_GET['advanced_country'],$allowed_html) );
        $country                     =   str_replace('-', ' ', $country);
        $country_array['key']        =   'property_country';
        $country_array['value']      =   $country;
        $country_array['type']       =   'CHAR';
        $country_array['compare']    =   'LIKE'; 
        $meta_query[]                =   $country_array;
    }

    if( isset($_GET['advanced_city']) && $_GET['advanced_city']=='' && isset($_GET['property_admin_area']) && $_GET['property_admin_area']!=''   ){
        $admin_area_array=array();
        $admin_area                     =   sanitize_text_field ( wp_kses ($_GET['property_admin_area'],$allowed_html) );
        $admin_area                     =   str_replace(" ", "-", $admin_area);
        $admin_area                     =   str_replace("\'", "", $admin_area);
        $admin_area_array['key']        =   'property_admin_area';
        $admin_area_array['value']      =   $admin_area;
        $admin_area_array['type']       =   'CHAR';
        $admin_area_array['compare']    =   'LIKE'; 
        $meta_query[]                   =   $admin_area_array;

    }

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
   
    if($paged>1){

       $meta_query= get_option('wpestate_pagination_meta_query','');
       $categ_array= get_option('wpestate_pagination_categ_query','');
       $action_array= get_option('wpestate_pagination_action_query','');
       $city_array= get_option('wpestate_pagination_city_query','');
       $area_array=get_option('wpestate_pagination_area_query','');
    }else{
        update_option('wpestate_pagination_meta_query',$meta_query);
        update_option('wpestate_pagination_categ_query',$categ_array);
        update_option('wpestate_pagination_action_query',$action_array);
        update_option('wpestate_pagination_city_query',$city_array);
        update_option('wpestate_pagination_area_query',$area_array);

    }
    
    if ( $book_from!='' && $book_from!='' ){          
        $args = array(
            'cache_results'           =>    false,
            'update_post_meta_cache'  =>    false,
            'update_post_term_cache'  =>    false,
            'post_type'               =>    'estate_property',
            'post_status'             =>    'publish',
            'posts_per_page'          =>    -1,
            'meta_key'                =>    'prop_featured',
            'orderby'                 =>    'meta_value',
            'order'                   =>    'DESC',
            'meta_query'              =>    $meta_query,
            'tax_query'               =>    array(
                                                'relation' => 'AND',
                                               
                                                $city_array,
                                                $area_array
                                            )
            );
    }else{
            $args = array(
            'cache_results'           =>    false,
            'update_post_meta_cache'  =>    false,
            'update_post_term_cache'  =>    false,
            'post_type'               =>    'estate_property',
            'post_status'             =>    'publish',
            'paged'                   =>    $paged,
            'posts_per_page'          =>    $prop_no,
            'meta_key'                =>    'prop_featured',
            'orderby'                 =>    'meta_value',
            'order'                   =>    'DESC',
            'meta_query'              =>    $meta_query,
            'tax_query'               =>    array(
                                                'relation' => 'AND',
                                               
                                                $city_array,
                                                $area_array
                                              )
            );
    }
}



 
                


                            
//////////////////////////////////////////////////////////////////////////////////////
///// compose query 
//////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////
    // if we have check in and check out dates we need to double loop
    ////////////////////////////////////////////////////////////////////////////    
    if ( $book_from!='' && $book_from!='' ){          
     
        $custom_fields = get_option( 'wp_estate_custom_fields', true); 
     //   add_filter( 'posts_orderby', 'wpestate_my_order' );
        $prop_selection =   new WP_Query($args);
       // remove_filter( 'posts_orderby', 'wpestate_my_order' );
        $num = $prop_selection->found_posts;
      
        $right_array=array();
        $right_array[]=0;
        while ($prop_selection->have_posts()): $prop_selection->the_post(); 
            //print '</br>we check '.$post->ID.'</br>';
        
            if( wpestate_check_booking_valability($book_from,$book_to,$post->ID) ){
                $right_array[]=$post->ID;
            }
        endwhile;
    
        
        wp_reset_postdata();
        $args = array(
            'cache_results'           =>    false,
            'update_post_meta_cache'  =>    false,
            'update_post_term_cache'  =>    false,
            'meta_key'                =>    'prop_featured',
            'orderby'                 =>    'meta_value',
            'post_type'               =>    'estate_property',
            'post_status'             =>    'publish',
            'paged'                   =>    $paged,
            'posts_per_page'          =>    $prop_no,
            'post__in'                =>    $right_array
        );
        add_filter( 'posts_orderby', 'wpestate_my_order' );
        $prop_selection =   new WP_Query($args);
        remove_filter( 'posts_orderby', 'wpestate_my_order' );
    }else{
        //var_dump($args);
        add_filter( 'posts_orderby', 'wpestate_my_order' );
        $prop_selection =   new WP_Query($args);
        //var_dump($prop_selection);
        remove_filter( 'posts_orderby', 'wpestate_my_order' );
    }
    
  
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    /// map pins
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    if ( is_page_template('advanced_search_results.php')   && $book_from!='' && $book_from!='' ){  
        //check reservation
        $selected_pins  = wpestate_listing_pins_with_reservation($args,1,$book_from,$book_to);//call the new pins  
    }else{
        $selected_pins  = wpestate_listing_pins($args,1,1);//call the new pins  
    }
  

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    /// get template and display results
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    
    $property_list_type_status =    esc_html(get_option('wp_estate_property_list_type_adv',''));
    if ( $property_list_type_status == 2 ){
        get_template_part('templates/half_map_core');
    }else{
        get_template_part('templates/normal_map_core');
    }


wp_localize_script('wpestate_googlecode_regular', 'googlecode_regular_vars2', 
    array(  
        'markers2'           =>  $selected_pins,
    )
);
get_footer(); 
?>