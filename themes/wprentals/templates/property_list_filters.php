<?php
global $current_adv_filter_search_label;
global $current_adv_filter_category_label;
global $current_adv_filter_city_label;
global $current_adv_filter_area_label;
$allowed_html      =    array();

$allowed_html_list =    array(  'li' => array(
                                        'data-value'        =>array(),
                                        'role'              => array(),
                                        'data-parentcity'   =>array(),
                                        'data-value2'       =>array(),
                                    ),
                              
                            );

$current_name      =   '';
$current_slug      =   '';
$listings_list     =   '';
$show_filter_area  =   '';

if( isset($post->ID) ){
    $show_filter_area  =   get_post_meta($post->ID, 'show_filter_area', true);
}


if( is_tax() ){
    $show_filter_area = 'yes';
    $current_adv_filter_search_label    =esc_html__( 'All Sizes','wpestate');
    $current_adv_filter_category_label  =esc_html__( 'All Types','wpestate');
    $current_adv_filter_city_label      =esc_html__( 'All Cities','wpestate');
    $current_adv_filter_area_label      =esc_html__( 'All Areas','wpestate');
    $taxonmy                            = get_query_var('taxonomy');
//  $term                               = get_query_var( 'name' );
    $term                               = single_cat_title('',false);
    
    if ($taxonmy == 'property_city'){
        $current_adv_filter_city_label = ucwords( str_replace('-',' ',$term) );
    }
    if ($taxonmy == 'property_area'){
        $current_adv_filter_area_label = ucwords( str_replace('-',' ',$term) );
    }
    if ($taxonmy == 'property_category'){
        $current_adv_filter_category_label = ucwords( str_replace('-',' ',$term) );
    }
    if ($taxonmy == 'property_action_category'){
        $current_adv_filter_search_label = ucwords( str_replace('-',' ',$term) );
    }
    
}

$listing_filter         =   '';
$selected_order         = esc_html__( 'Sort by','wpestate');
if( isset($post->ID) ){
    $listing_filter         = get_post_meta($post->ID, 'listing_filter',true );
}
$listing_filter_array   = array(
                            "1"=>esc_html__( 'Price High to Low','wpestate'),
                            "2"=>esc_html__( 'Price Low to High','wpestate'),
                            "0"=>esc_html__( 'Default','wpestate')
                        );
    

// show or not empty taxonomies
$args = wpestate_get_select_arguments();


foreach($listing_filter_array as $key=>$value){
    $listings_list.= '<li role="presentation" data-value="'.$key.'">'.$value.'</li>';

    if($key==$listing_filter){
        $selected_order=$value;
    }
}   
      

$order_class='';
if( $show_filter_area != 'yes' ){
    $order_class=' order_filter_single ';  
}


        
if( $show_filter_area=='yes' ){

        if ( is_tax() ){
            $curent_term    =   get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            $current_slug   =   $curent_term->slug;
            $current_name   =   $curent_term->name;
            $current_tax    =   $curent_term->taxonomy; 
        }


    $action_select_list =   wpestate_get_action_select_list($args);
    $categ_select_list  =   wpestate_get_category_select_list($args);
    $select_city_list   =   wpestate_get_city_select_list($args); 
  
    if(is_tax() && $taxonmy=='property_city' ){
           $select_area_list   =   wpestate_get_area_select_list($args);
//        $select_area_list   =   wpestate_get_area_select_list_area_tax($args,$term);
    }else{
        $select_area_list   =   wpestate_get_area_select_list($args);
    }
        
}// end if show filter

?>

    <?php if( $show_filter_area=='yes' ){?>
    <div class="listing_filters_head row"> 
        <input type="hidden" id="page_idx" value="
            <?php 
            if(!is_tax() ) {
                print $post->ID;
            }
            ?>">
        
            <div class="col-md-2">
                <div class="dropdown form_control listing_filter_select" >
                    <div data-toggle="dropdown" id="a_filter_action" class="filter_menu_trigger" data-value="<?php print wp_kses($current_adv_filter_search_label,$allowed_html);?>"> <?php print wp_kses($current_adv_filter_search_label,$allowed_html);?> <span class="caret caret_filter"></span> </div>           
                    <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="a_filter_action">
                        <?php print  wp_kses($action_select_list,$allowed_html_list); ?>
                    </ul>        
                </div>
            </div>
        
            <div class="col-md-2">
                <div class="dropdown form_control listing_filter_select" >
                    <div data-toggle="dropdown" id="a_filter_categ" class="filter_menu_trigger" data-value="<?php print wp_kses($current_adv_filter_category_label,$allowed_html); ?> "> <?php  print wp_kses($current_adv_filter_category_label,$allowed_html); ?> <span class="caret caret_filter"></span> </div>           
                    <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="a_filter_categ">
                        <?php print  wp_kses($categ_select_list,$allowed_html_list);?>
                    </ul>        
                </div>      
            </div>
       
            <div class="col-md-2">
                <div class="dropdown form_control listing_filter_select" >
                    <div data-toggle="dropdown" id="a_filter_cities" class="filter_menu_trigger" data-value="<?php print wp_kses($current_adv_filter_city_label,$allowed_html); ?>"> <?php print wp_kses($current_adv_filter_city_label,$allowed_html);  ?> <span class="caret caret_filter"></span> </div>           
                    <ul id="filter_city" class="dropdown-menu filter_menu" role="menu" aria-labelledby="a_filter_cities">
                        <?php  print $select_city_list//wp_kses( ,$allowed_html_list); ?>
                    </ul>        
                </div>
            </div>
        
            <div class="col-md-2">
                <div class="dropdown form_control listing_filter_select" >
                    <div data-toggle="dropdown" id="a_filter_areas" class="filter_menu_trigger" data-value="<?php  print wp_kses($current_adv_filter_area_label,$allowed_html); ?>"> <?php print wp_kses($current_adv_filter_area_label,$allowed_html); ?> <span class="caret caret_filter"></span> </div>           
                    <ul id="filter_area" class="dropdown-menu filter_menu" role="menu" aria-labelledby="a_filter_areas">
                        <?php   print  wp_kses($select_area_list,$allowed_html_list); ?>
                    </ul>        
                </div>
            </div>
        
        
            <div class="col-md-2 order_filter">
        
                <div class="dropdown  listing_filter_select " >
                    <div data-toggle="dropdown" id="a_filter_order" class="filter_menu_trigger" data-value="0"> <?php print wp_kses($selected_order,$allowed_html); ?> <span class="caret caret_filter"></span> </div>           

                    <ul id="filter_order" class="dropdown-menu filter_menu" role="menu" aria-labelledby="a_filter_order">
                        <?php  print  wp_kses($listings_list,$allowed_html_list);?>
                    </ul>        
                </div>

            </div>
        
    </div> 
    <?php } ?>      