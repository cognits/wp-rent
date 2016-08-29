<?php


////////////////////////////////////////////////////////////////////////////////////
/// featured palce
////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_featured_place') ):

function wpestate_featured_place($attributes, $content = null) {
    $place_id           =   '';
    $return_string      =   '';
    $extra_class_name   =   '';
    $type_class         =   '';
    $attributes = shortcode_atts( 
        array(
            'id'                       => 0,
            'type'                     => "type1",
        ), $attributes) ;
       
       
    if ( isset($attributes['id']) ){
        $place_id=$attributes['id'];
    }
    
    
    if ( isset($attributes['type']) && $attributes['type']=='type1' ){
        $type_class=' type_1_class ';
    }
    
    
    if( isset($attributes['extra_class_name'])){
        $extra_class_name=$attributes['extra_class_name'];
    }   
   
        $place_id=intval($place_id);
        $category_attach_id='';
        $category_tax='';
        $category_featured_image='';
        $category_name='';
        $category_featured_image_url='';
        $term_meta = get_option( "taxonomy_$place_id");
        $category_tagline='';
        
        if(isset($term_meta['category_featured_image'])){
            $category_featured_image=$term_meta['category_featured_image'];
        }
        
        if(isset($term_meta['category_attach_id'])){
            $category_attach_id=$term_meta['category_attach_id'];
            $category_featured_image= wp_get_attachment_image_src( $category_attach_id, 'wpestate_property_featured');
            $category_featured_image_url=$category_featured_image[0];
        }
        
        if(isset($term_meta['category_tax'])){
            $category_tax=$term_meta['category_tax'];
            $term= get_term( $place_id, $category_tax);
            $category_name=$term->name;
        }
       
         if(isset($term_meta['category_tagline'])){
            $category_tagline=$term_meta['category_tagline'];           
        }
     
        $term_link =  get_term_link( $place_id, $category_tax );
        if ( is_wp_error( $term_link ) ) {
            $term_link='';
        }
        
        $return_string .='<div class="places_wrapper '.$extra_class_name.' '.$type_class.' " data-link="'.$term_link.'"><div class="listing-hover-gradient"></div><div class="listing-hover"></div>';
        $return_string .= '<div class="places1 featuredplace" style="background-image:url('.$category_featured_image_url.')"></div>';
      
        $return_string .= '<div class="category_name"><a class="featured_listing_title" href="'.$term_link.'">'.stripslashes($category_name).'</a>';
        $return_string .= '<div class="category_tagline">'.stripslashes($category_tagline).'</div></div>';
  
        $return_string .= '';  
 
    $return_string .='</div>';
    
    return $return_string;
    
    
}

endif; // end   wpestate_featured_agent   



////////////////////////////////////////////////////////////////////////////////
// place list 
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_places_list_function') ):
function wpestate_places_list_function($attributes, $content = null) {
    $place_list='';
    $return_string ='';
    $extra_class_name='';
    
    $attributes = shortcode_atts( 
        array(
            'place_list'                       => '',
            'place_per_row'                    => 4,
            'extra_class_name'                 => '',
        ), $attributes) ;


    
    if ( isset($attributes['place_list']) ){
        $place_list=$attributes['place_list'];
    }
    if ( isset($attributes['place_per_row']) ){
        $place_per_row=$attributes['place_per_row'];
    }
    
    if($place_per_row>5){
        $place_per_row=5;
    }
    
    if( isset($attributes['extra_class_name'])){
        $extra_class_name=$attributes['extra_class_name'];
    }    
    
    //$return_string .=$place_list;
    
    $all_places_array=  explode(',', $place_list);
    
   // $return_string .='<div class="places_wrapper '.$extra_class_name.' ">';
    foreach($all_places_array as $place_id){
        
        $place_id=intval($place_id);
        $category_attach_id='';
        $category_tax='';
        $category_featured_image='';
        $category_name='';
        $category_featured_image_url='';
        $term_meta = get_option( "taxonomy_$place_id");
        $category_tagline='';
        
        if(isset($term_meta['category_featured_image'])){
            $category_featured_image=$term_meta['category_featured_image'];
        }
        
        if(isset($term_meta['category_attach_id'])){
            $category_attach_id=$term_meta['category_attach_id'];
            $category_featured_image= wp_get_attachment_image_src( $category_attach_id, 'wpestate_property_places');
            $category_featured_image_url=$category_featured_image[0];
        }
        
        if(isset($term_meta['category_tax'])){
            $category_tax=$term_meta['category_tax'];
            $term= get_term( $place_id, $category_tax);
            $category_name=$term->name;
        }
       
         if(isset($term_meta['category_tagline'])){
            $category_tagline=  stripslashes( $term_meta['category_tagline'] );           
        }
     
        $term_link =  get_term_link( $place_id, $category_tax );
        if ( is_wp_error( $term_link ) ) {
            $term_link='';
        }
        $return_string .= '<div class="places_wrapper places_wrapper'.$place_per_row.' '.$extra_class_name.'" data-link="'.$term_link.'"><div class="listing-hover-gradient"></div><div class="listing-hover"></div>';
        $return_string .= '<div class="places'.$place_per_row.'" style="background-image:url('.$category_featured_image_url.')"></div>';
       
        $return_string .= '<div class="category_name"><a class="featured_listing_title" href="'.$term_link.'">'.$category_name.'</a>';
        $return_string .= '<div class="category_tagline">'.$category_tagline.'</div></div>';
    
        $return_string .= '</div>';  
        
        /*
         *    $return_string .='<div class="places_wrapper '.$extra_class_name.' "><div class="listing-hover-gradient"></div><div class="listing-hover"></div>';
        $return_string .= '<div class="places1 featuredplace" style="background-image:url('.$category_featured_image_url.')"></div>';
      
        $return_string .= '<div class="category_name"><a class="featured_listing_title" href="'.$term_link.'">'.$category_name.'</a>';
        $return_string .= '<div class="category_tagline">'.$category_tagline.'</div></div>';
  
        $return_string .= '';  
        */
        
    }
   // $return_string .='</div>';
    
    return $return_string;
     
}
endif;

////////////////////////////////////////////////////////////////////////////////////////////
///  shortcode - recent post with picture
////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_slider_recent_posts_pictures') ):

function wpestate_slider_recent_posts_pictures($attributes, $content = null) {
    global $options;
    global $align;
    global $align_class;
    global $post;
    global $currency;
    global $where_currency;
    global $is_shortcode;
    global $show_compare_only;
    global $row_number_col;
    global $curent_fav;
    global $current_user;
    global $listing_type;
    global $property_unit_slider;
    $property_unit_slider       =   esc_html ( get_option('wp_estate_prop_list_slider','') );

    $listing_type   =   get_option('wp_estate_listing_unit_type','');
    
    $options            =   wpestate_page_details($post->ID);
    $return_string      =   '';
    $pictures           =   '';
    $button             =   '';
    $class              =   '';
    $category=$action=$city=$area='';
    $title              =   '';
    $currency           =   esc_html( get_option('wp_estate_currency_symbol', '') );
    $where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
    $is_shortcode       =   1;
    $show_compare_only  =   'no';
    $row_number_col     =   '';
    $row_number         =   '';       
    $show_featured_only =   '';
    $autoscroll         =   '';
    

    $current_user = wp_get_current_user();
    $userID                         =   $current_user->ID;
    $user_option        =   'favorites'.$userID;
    $curent_fav         =   get_option($user_option);


    if ( isset($attributes['title']) ){
        $title=$attributes['title'];
    }
    
      $attributes = shortcode_atts( 
                array(
                    'title'                 =>  '',
                    'type'                  => 'properties',
                    'category_ids'          =>  '',
                    'action_ids'            =>  '',
                    'city_ids'              =>  '',
                    'area_ids'              =>  '',
                    'number'                =>  4,
                    'show_featured_only'    =>  'no',
                    'random_pick'           =>  'no',
                    'extra_class_name'      =>  '',
                    'autoscroll'            =>  0,
                ), $attributes) ;
    
    if ( isset($attributes['category_ids']) ){
        $category=$attributes['category_ids'];
    }
    
    
    if ( isset($attributes['category_ids']) ){
        $category=$attributes['category_ids'];
    }

    if ( isset($attributes['action_ids']) ){
        $action=$attributes['action_ids'];
    }

    if ( isset($attributes['city_ids']) ){
        $city=$attributes['city_ids'];
    }

    if ( isset($attributes['area_ids']) ){
        $area=$attributes['area_ids'];
    }
     
     if ( isset($attributes['show_featured_only']) ){
        $show_featured_only=$attributes['show_featured_only'];
    }
    if ( isset($attributes['autoscroll']) ){
        $autoscroll=intval ( $attributes['autoscroll'] );
    }    
    
    $post_number_total = $attributes['number'];
    if ( isset($attributes['rownumber']) ){
        $row_number        = $attributes['rownumber']; 
    }
   
    
    if( $row_number == 4 ){
        $row_number_col = 3; // col value is 3 
    }else if( $row_number==3 ){
        $row_number_col = 4; // col value is 4
    }else if ( $row_number==2 ) {
        $row_number_col =  6;// col value is 6
    }else if ($row_number==1) {
        $row_number_col =  12;// col value is 12
    }
    
    $align=''; 
    $align_class='';
    if(isset($attributes['align']) && $attributes['align']=='horizontal'){
        $align="col-md-12";
        $align_class='the_list_view';
        $row_number_col='12';
    }
    
    $attributes['type'] = 'properties';
    
    if ($attributes['type'] == 'properties') {
        $type = 'estate_property';
        
        $category_array =   '';
        $action_array   =   '';
        $city_array     =   '';
        $area_array     =   '';
        
        // build category array
        if($category!=''){
            $category_of_tax=array();
            $category_of_tax=  explode(',', $category);
            $category_array=array(     
                            'taxonomy'  => 'property_category',
                            'field'     => 'term_id',
                            'terms'     => $category_of_tax
                            );
        }
            
        
        // build action array
        if($action!=''){
            $action_of_tax=array();
            $action_of_tax=  explode(',', $action);
            $action_array=array(     
                            'taxonomy'  => 'property_action_category',
                            'field'     => 'term_id',
                            'terms'     => $action_of_tax
                            );
        }
        
        // build city array
        if($city!=''){
            $city_of_tax=array();
            $city_of_tax=  explode(',', $city);
            $city_array=array(     
                            'taxonomy'  => 'property_city',
                            'field'     => 'term_id',
                            'terms'     => $city_of_tax
                            );
        }
        
        // build city array
        if($area!=''){
            $area_of_tax=array();
            $area_of_tax=  explode(',', $area);
            $area_array=array(     
                            'taxonomy'  => 'property_area',
                            'field'     => 'term_id',
                            'terms'     => $area_of_tax
                            );
        }
        
        
           $meta_query=array(); 
        
            if($show_featured_only=='yes'){
                $compare_array=array();
                $compare_array['key']        = 'prop_featured';
                $compare_array['value']      = 1;
                $compare_array['type']       = 'numeric';
                $compare_array['compare']    = '=';
                $meta_query[]                = $compare_array;
            }
        
            $args = array(
                'post_type'         => $type,
                'post_status'       => 'publish',
                'paged'             => 0,
                'posts_per_page'    => $post_number_total,
                'meta_key'          => 'prop_featured',
                'orderby'           => 'ID',
                'order'             => 'DESC',
                'meta_query'        => $meta_query,
                'tax_query'         => array( 
                                        $category_array,
                                        $action_array,
                                        $city_array,
                                        $area_array
                                    )
              
            );
            if($show_featured_only=='yes'){
                $args['meta_query'] =$meta_query;
                $args['orderby']    ='meta_value';
            }
       
          
    } else {
        $type = 'post';
        $args = array(
            'post_type'      => $type,
            'post_status'    => 'publish',
            'paged'          => 0,
            'posts_per_page' => $post_number_total,
            'cat'            => $category
        );
    }


    if ( isset($attributes['link']) && $attributes['link'] != '') {
        if ($attributes['type'] == 'properties') {
            $button .= '<div class="listinglink-wrapper">
               <a href="' . $attributes['link'] . '"> <span class="wpb_button  wpb_btn-info wpb_btn-large vc_button">'.esc_html__( 'More Listings','wpestate').' </span></a> 
               </div>';
        } else {
            $button .= '<div class="listinglink-wrapper">
               <a href="' . $attributes['link'] . '"> <span class="wpb_button  wpb_btn-info wpb_btn-large vc_button">  '.esc_html__( 'More Articles','wpestate').' </span></a> 
               </div>';
        }
    } else {
        $class = "nobutton";
    }


    
  

    if ($attributes['type'] == 'properties') {
       /* if($show_featured_only=='yes'){
            add_filter( 'posts_orderby', 'wpestate_my_order' ); 
            $recent_posts = new WP_Query($args);
            remove_filter( 'posts_orderby', 'wpestate_my_order' ); 
        }else{ */
            add_filter( 'posts_orderby', 'wpestate_my_order' ); 
            $recent_posts = new WP_Query($args);
            remove_filter( 'posts_orderby', 'wpestate_my_order' ); 
        //}
        $count = 1;
        
    }else{
        $recent_posts = new WP_Query($args);
        $count = 1;
    }
   
    $return_string .= '<div class="article_container slider_container bottom-'.$type.' '.$class.'" >';
    
    $return_string .= '<div class="slider_control_left"><i class="fa fa-angle-left"></i></div>
                       <div class="slider_control_right"><i class="fa fa-angle-right"></i></div>';
    
    if($title!=''){
         $return_string .= '<h1 class="shortcode_title title_slider">'.$title.'</h1>';
    }
    
    $is_autoscroll='';
   
        $is_autoscroll=' data-auto="'.$autoscroll.'" ';
  
    
    $return_string .= '<div class="shortcode_slider_wrapper" '.$is_autoscroll.'><ul class="shortcode_slider_list">';
    
    
    ob_start();  
    while ($recent_posts->have_posts()): $recent_posts->the_post();
        print '<li>';
        if($type == 'estate_property'){
            get_template_part('templates/property_unit');
        } else {
            if(isset($attributes['align']) && $attributes['align']=='horizontal'){
                get_template_part('templates/blog_unit');
            }else{
                get_template_part('templates/blog_unit2');
            }
            
        }
        print '</li>';
    endwhile;

    $templates = ob_get_contents();
    ob_end_clean(); 
    $return_string .=$templates;
    $return_string .=$button;
    
    $return_string .= '</ul></div>';// end shrcode wrapper
    $return_string .= '</div>';
    wp_reset_query();
    
    wp_reset_postdata();
    $is_shortcode       =   0;
    
   
    
    return $return_string;
    
    
}
endif; // end   wpestate_recent_posts_pictures 













////////////////////////////////////////////////////////////////////////////////////
/// wpestate_icon_container_function
////////////////////////////////////////////////////////////////////////////////////

if ( !function_exists("wpestate_icon_container_function") ):    
function wpestate_icon_container_function($attributes, $content = null) {
    $return_string  =   '';
    $link           =   '';
    $title          =   ''; 
    $image          =   ''; 
    $content_box    =   '';
    $icon_type      =   '';
    $icon_class     =   '';
    $font_size      =   '';
    $font_size_style=   '';
    
    if(isset($attributes['title'])){
        $title=$attributes['title'] ;
    }
    
    $attributes = shortcode_atts( 
        array(
            'title'                       => 'title',
            'image'                       => '',
            'content_box'                 => 'Content of the box goes here',
            'image_effect'                =>  'yes',  
            'link'                        =>  '',
            'icon_type'                   =>  'left',
            'title_font_size'             =>    24,
        ), $attributes) ;

    
    if(isset($attributes['image'])){
        $image=$attributes['image'] ;
    }
    if(isset($attributes['content_box'])){
        $content_box=$attributes['content_box'] ;
    }
    
    if(isset($attributes['link'])){
        $link=$attributes['link'] ;
    }
    
    if(isset($attributes['icon_type'])){
        $icon_type=$attributes['icon_type'] ;
    }
    
    if(isset($attributes['title_font_size'])){
        $font_size=$attributes['title_font_size'] ;
    }
    
    
    $return_string .= '<div class="iconcol">';
    
    if($icon_type=='central'){
        $icon_class=" icon_central ";
    }
    
    if($font_size!=24){
        $font_size_style=' style="font-size:'.$font_size.'px; "';
    }
    
    
    

    if($image!=''){
        $return_string .= '<div class="icon_img '.$icon_class.'">';
        $return_string .= ' <img src="' .$image . '"  class="img-responsive" alt="thumb"/ >';
    }
   
    $return_string .= '<h3><a href="' . $link . '" '.$font_size_style.'>' . $title . '</a></h3></div>';
    $return_string .= '<p>' . do_shortcode($content_box) . '</p>';
    $return_string .= '</div>';

    return $return_string;
}
endif;












////////////////////////////////////////////////////////////////////////////////////
/// spacer
////////////////////////////////////////////////////////////////////////////////////

if ( !function_exists("wpestate_spacer_shortcode_function") ):    
function wpestate_spacer_shortcode_function($attributes, $content = null) {
    $height =   '';
    $type   =   1;
    
    $attributes = shortcode_atts( 
         array(
             'type'            => '1',
             'height'          => '40',                    
         ), $attributes) ;

    if(isset($attributes['type'])){
        $type=$attributes['type'] ;
    }
    
    if(isset($attributes['height'])){
        $height=$attributes['height'] ;
    }
    
    $return_string='';
    $return_string.= '<div class="spacer" style="height:' .$height. 'px;">';
    if($type==2){
         $return_string.='<span class="spacer_line"></span>';
    }
    $return_string.= '</div>';
    return $return_string;
}
endif;











///////////////////////////////////////////////////////////////////////////////////////////
// font awesome function
///////////////////////////////////////////////////////////////////////////////////////////
if ( !function_exists("wpestate_font_awesome_function") ): 
function wpestate_font_awesome_function($attributes, $content = null){
        $icon = $attributes['icon'];
        $size = $attributes['size'];
        $return_string ='<i class="'.$icon.'" style="'.$size.'"></i>';
        return $return_string;
}
endif;










///////////////////////////////////////////////////////////////////////////////////////////
// advanced search function
///////////////////////////////////////////////////////////////////////////////////////////
if ( !function_exists("wpestate_advanced_search_function") ): 
function wpestate_advanced_search_function($attributes, $content = null){
        $return_string          =   '';
        $random_id              =   '';
        $custom_advanced_search =   get_option('wp_estate_custom_advanced_search','');       
        $actions_select         =   '';
        $categ_select           =   '';
        $title                  =   '';
       
        if ( isset($attributes['title']) ){
            $title=$attributes['title'];    
        }
    
        $args = wpestate_get_select_arguments();
        $action_select_list =   wpestate_get_action_select_list($args);
        $categ_select_list  =   wpestate_get_category_select_list($args);
        $select_city_list   =   wpestate_get_city_select_list($args); 
        $select_area_list   =   wpestate_get_area_select_list($args);


        $adv_submit=wpestate_get_adv_search_link();
     
        if($title!=''){
            
        }
        
        $return_string .= '<h1 class="shortcode_title_adv">'.$title.'</h1>';
        $return_string .= '<div class="advanced_search_shortcode" id="advanced_search_shortcode">
        <form role="search" method="get"   action="'.$adv_submit.'" >';
        if (function_exists('icl_translate') ){
            $return_string .=  do_action( 'wpml_add_language_form_field' );
        }
        
        
        if($custom_advanced_search=='yes'){
                $adv_search_what        =   get_option('wp_estate_adv_search_what','');
                $adv_search_label       =   get_option('wp_estate_adv_search_label','');
                $adv_search_how         =   get_option('wp_estate_adv_search_how','');
                $count=0;
       
                foreach($adv_search_what as $key=>$search_field){
                    $count++;
                    $margin_style='';
                    if($count%4==0){
                        $margin_style=' adv_seach_code_right ';
                    }
                    
                    if($search_field=='none'){
                        $return_string.=''; 
                    }
                    else if($search_field=='types'){
                            $return_string.='           
                                <div class="dropdown listing_filter_select form-control" >
                                <div data-toggle="dropdown" id="adv_actions_shortcode" class="filter_menu_trigger" data-value="all">'.esc_html__( 'All Sizes','wpestate').'<span class="caret caret_filter"></span> </div>           
                                    <input type="hidden" name="filter_search_action[]" value="">
                                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions_shortcode">
                                        '.$action_select_list.'
                                    </ul>        
                                </div>';
                   }else if($search_field=='categories'){
                            $return_string.='
                             <div class="dropdown listing_filter_select form-control" >
                                <div data-toggle="dropdown" id="adv_categ_shortcode" class="filter_menu_trigger" data-value="all">'.esc_html__( 'All Types','wpestate').' <span class="caret caret_filter"></span> </div>           
                                  <input type="hidden" name="filter_search_type[]" value="">

                                  <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ_shortcode">
                                    '.$categ_select_list.'
                                  </ul>        
                            </div>';
                    }  else if($search_field=='cities'){
                            $return_string.='
                            <div class="dropdown listing_filter_select form-control" >
                                <div data-toggle="dropdown" id="advanced_city_shortcode" class="filter_menu_trigger" data-value="all">'. esc_html__( 'All Cities','wpestate').' <span class="caret caret_filter"></span> </div>           
                                <input type="hidden" name="advanced_city" value="">
                                <ul class="dropdown-menu filter_menu" role="menu" id="adv_short_select_city" aria-labelledby="advanced_city_shortcode">
                                    '.$select_city_list.'
                                </ul>        
                            </div>';
                   }   else if($search_field=='areas'){
                            $return_string.='
                            <div class="dropdown listing_filter_select  form-control" >
                               <div data-toggle="dropdown" id="advanced_area_shortcode" class="filter_menu_trigger" data-value="all">'.esc_html__( 'All Areas','wpestate').'<span class="caret caret_filter"></span> </div>           
                               <input type="hidden" name="advanced_area" value="">
                               <ul class="dropdown-menu filter_menu" role="menu" id="adv_short_select_area" aria-labelledby="advanced_area_shortcode">
                                   '.$select_area_list.'
                               </ul>        
                            </div>';
                    }    else {
                            $slug=str_replace(' ','_',$search_field);
                            $random_id=rand(1,999);
                            $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
                            $slug         =   sanitize_key($string);
            
                            $label=$adv_search_label[$key];
                            if (function_exists('icl_translate') ){
                                $label     =   icl_translate('wpestate','wp_estate_custom_search_'.$label, $label ) ;
                            }
                        
                            
                            if ( $adv_search_what[$key]=='property price'){
                                $show_slider_price  =   get_option('wp_estate_show_slider_price','');
                                if ($show_slider_price == 'yes'){
                                        $where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
                                        $currency               =   esc_html( get_option('wp_estate_currency_symbol', '') );
                                        $min_price_slider       =   ( floatval(get_option('wp_estate_show_slider_min_price','')) );
                                        $max_price_slider       =   ( floatval(get_option('wp_estate_show_slider_max_price','')) );

                                        if ($where_currency == 'before') {
                                             $price_slider_label = $currency . number_format($min_price_slider).' '.esc_html__( 'to','wpestate').' '.$currency . number_format($max_price_slider);
                                        } else {
                                             $price_slider_label =  number_format($min_price_slider).$currency.' '.esc_html__( 'to','wpestate').' '.number_format($max_price_slider).$currency;
                                        }    

         
                                        $return_string.='
                                        <div class="adv_search_sh">
                                            <p>
                                                <label>'.esc_html__( 'Price range:','wpestate').'</label>
                                                <span id="amount_sh"  style="border:0; font-weight:bold;">'.wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$currency,$where_currency).'</span>
                                            </p>
                                            <div id="slider_price_sh"></div>
                                            <input type="hidden" id="price_low_sh"  name="price_low"  value="'.$min_price_slider.'" />
                                            <input type="hidden" id="price_max_sh"  name="price_max"  value="'.$max_price_slider.'" />
                                        </div>';
                                }else{
                                    $return_string.='
                                    <div class="adv_shortcode_input '.$margin_style.'" >
                                       <input type="text" id="'.$slug.$random_id.'"  name="'.$slug.'" placeholder="'.$label.'" value=""  class="advanced_select form-control">
                                    </div>';
                                }
                              // if is property price    
                            }else{ 
                                $return_string.='
                                <div class="adv_shortcode_input '.$margin_style.'" >
                                   <input type="text" id="'.$slug.$random_id.'"  name="'.$slug.'" placeholder="'.$label.'" value=""  class="advanced_select form-control">
                                </div>';
                            }

                                            
                            
                         
                              
                                if ( $adv_search_how[$key]=='date bigger' || $adv_search_how[$key]=='date smaller'){
                                    $return_string .= '<script type="text/javascript">
                                          //<![CDATA[
                                          jQuery(document).ready(function(){
                                                '.wpestate_date_picker_translation($slug.$random_id).'
                                          });
                                          //]]>
                                          </script>';
                                }


                        } //end else


                
                } // end foreach
        }else{
             $return_string.='
                 
                    <div class="col-md-3 map_icon "> <!-- map_icon -->';
                        $show_adv_search_general            =   get_option('wp_estate_wpestate_autocomplete','');
                        $wpestate_internal_search           =   '';
                        if($show_adv_search_general=='no'){
                            $wpestate_internal_search='_autointernal';
                            $return_string.='<input type="hidden" class="stype" id="stype" name="stype" value="tax">';
                        }
           
                    $return_string.='
                    <input type="text" id="search_location_filter_shortcode'.$wpestate_internal_search.'" class="form-control search_location_city" name="search_location" placeholder="'.esc_html__( 'Type location','wpestate').'" value="" autocomplete="off">
                    <input type="hidden" id="advanced_city_shortcode"   class="form-control" name="advanced_city" data-value=""   value="" >              
                    <input type="hidden" id="advanced_area_shortcode"   class="form-control" name="advanced_area"   data-value="" value="" >              
                    <input type="hidden" id="advanced_country_shortcode"   class="form-control" name="advanced_country"   data-value="" value="" >              
                    <input type="hidden" id="property_admin_area_shortcode" name="property_admin_area" value="">
           
                </div>
                
                <div class="col-md-3 has_calendar calendar_icon ">  <!-- calendar_icon -->
                    <input type="text" id="checkinshortcode" class="form-control " name="check_in" placeholder="'.esc_html__( 'Check in','wpestate').'">       
                </div>
                
                <div class="col-md-3 has_calendar calendar_icon checkout_sh ">  <!-- calendar_icon -->
                    <input type="text" id="checkoutshortcode" disabled class="form-control " name="check_out" placeholder="'.esc_html__( 'Check Out','wpestate').'">
                </div>

                <div class="col-md-3 dropdown guest_form_sh_wr">
                <div class=" form-control guest_form">
                    <div data-toggle="dropdown" id="guest_no_shortcode" class="filter_menu_trigger" data-value="all"> '.esc_html__( 'Guests','wpestate').' <span class="caret caret_filter"></span> </div>           
                    <input type="hidden" name="guest_no" id="guest_no_input_sh" value="">
                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="guest_no_input_sh">'. wpestate_get_guest_dropdown().'
                    </ul>
                </div>    
                </div>';
                get_template_part('libs/internal_autocomplete_wpestate');
                $show_slider_price  =   get_option('wp_estate_show_slider_price','');
                            
        
                $where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
                $currency               =   esc_html( get_option('wp_estate_currency_symbol', '') );

                $min_price_slider= ( floatval(get_option('wp_estate_show_slider_min_price','')) );
                $max_price_slider= ( floatval(get_option('wp_estate_show_slider_max_price','')) );

                if ($where_currency == 'before') {
                     $price_slider_label = $currency . number_format($min_price_slider).' '.esc_html__( 'to','wpestate').' '.$currency . number_format($max_price_slider);
                } else {
                     $price_slider_label =  number_format($min_price_slider).$currency.' '.esc_html__( 'to','wpestate').' '.number_format($max_price_slider).$currency;
                } 
                $return_string.='
                    <div class="col-md-9 adv_search_sh">
                        <p>
                            <label>'.esc_html__( 'Price range:','wpestate').'</label>
                            <span id="amount_sh"  style="border:0; font-weight:bold;">'.wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$currency,$where_currency).'</span>
                        </p>
                        <div id="slider_price_sh"></div>
                        <input type="hidden" id="price_low_sh"  name="price_low"  value="'.$min_price_slider.'" />
                        <input type="hidden" id="price_max_sh"  name="price_max"  value="'.$max_price_slider.'" />
                    </div>';

                

                
                
             
             
                 
        }
        $extended_search= get_option('wp_estate_show_adv_search_extended','');
        if($extended_search=='yes'){
            ob_start();
            wpestate_show_extended_search('short');           
            $templates = ob_get_contents();
            ob_end_clean(); 
            $return_string=$return_string.$templates;
        }

          $return_string.='<div class="col-md-3"></div><div class="col-md-3 adv_sh_but"><button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="advanced_submit_shorcode">'.esc_html__( 'Search','wpestate').'</button> </div>             

    </form>   
</div>';

 return $return_string;
          
}

endif;




///////////////////////////////////////////////////////////////////////////////////////////
// list items by ids function
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_list_items_by_id_function') ):

function wpestate_list_items_by_id_function($attributes, $content = null) {
    global $post;
    global $align;
    global $show_compare_only;
    global $currency;
    global $where_currency;
    global $col_class;
    global $is_shortcode;
    global $row_number_col;
    global $listing_type;
    global $property_unit_slider;
    $property_unit_slider       =   esc_html ( get_option('wp_estate_prop_list_slider','') );
    $listing_type   =   get_option('wp_estate_listing_unit_type','');
    $currency           =   esc_html( get_option('wp_estate_currency_symbol', '') );
    $where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
    $show_compare_only  =   'no';
    $return_string      =   '';
    $pictures           =   '';
    $button             =   '';
    $class              =   '';
    $rows               =   1;
    $ids                =   '';
    $ids_array          =   array();
    $post_number        =   1;
    $title              =   '';
    $is_shortcode       =   1;
    $row_number         =   '';
    
    
    $title              =   '';
    if ( isset($attributes['title']) ){
        $title=$attributes['title'];
    }
    
    $attributes = shortcode_atts( 
        array(
            'title'                 => '',
            'type'                  => 'properties',
            'ids'                   =>  '',
            'number'                =>  3,
            'rownumber'             =>  4,
            'align'                 =>  'vertical',
            'link'                  =>  '#',
            'extra_class_name'      =>  ''
        ), $attributes) ;
    
    if ( isset($attributes['ids']) ){
        $ids=$attributes['ids'];
        $ids_array=explode(',',$ids);
    }
    
    if ( isset($attributes['title']) ){
        $title=$attributes['title'];    
    }

    $post_number_total = $attributes['number'];

    
    if ( isset($attributes['rownumber']) ){
        $row_number        = $attributes['rownumber']; 
    }
    
    // max 4 per row
    if($row_number>4){
        $row_number=4;
    }
    
    if( $row_number == 4 ){
        $row_number_col = 3; // col value is 3 
    }else if( $row_number==3 ){
        $row_number_col = 4; // col value is 4
    }else if ( $row_number==2 ) {
        $row_number_col =  6;// col value is 6
    }else if ($row_number==1) {
        $row_number_col =  12;// col value is 12
    }
    
    
    $align=''; 
    if(isset($attributes['align']) && $attributes['align']=='horizontal'){
        $align="col-md-12";
    }
    
    
    
    if ($attributes['type'] == 'properties') {
       $type = 'estate_property';
    } else {
       $type = 'post';
    }

    if ($attributes['link'] != '') {
        if ($attributes['type'] == 'properties') {
            $button .= '<div class="listinglink-wrapper">
                           <a href="' . $attributes['link'] . '"> <span class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button more_list">'.esc_html__( ' More Listings','wpestate').' </span></a>
                       </div>';
        } else {
            $button .= '<div class="listinglink-wrapper">
                           <a href="' . $attributes['link'] . '"> <span class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button more_list">'.esc_html__( ' More Articles','wpestate').'</span></a>
                        </div>';
        }
    } else {
        $class = "nobutton";
    }

    
 
    
   
   $args = array(
        'post_type'         => $type,
        'post_status'       => 'publish',
        'paged'             => 0,
        'posts_per_page'    => $post_number_total, 
        'post__in'          => $ids_array,
        'orderby '          => 'none'
    );
 
    $recent_posts = new WP_Query($args);
   

    $return_string .= '<div class="article_container items_shortcode_wrapper">';
    if($title!=''){
        $return_string .= '<h1 class="shortcode_title">'.$title.'</h1>';
    }
     
    ob_start();  
    while ($recent_posts->have_posts()): $recent_posts->the_post();
        if($type == 'estate_property'){
            if(isset($attributes['align']) && $attributes['align']=='horizontal'){
               $col_class='col-md-12';
            }
            get_template_part('templates/property_unit');
           
        } else {
            if(isset($attributes['align']) && $attributes['align']=='horizontal'){
                get_template_part('templates/blog_unit');
            }else{
                get_template_part('templates/blog_unit2');
            }
            
        }
    endwhile;

    $templates = ob_get_contents();
    ob_end_clean(); 
    $return_string .=$templates;
    $return_string .=$button;
    $return_string .= '</div>';
    wp_reset_query();
    $is_shortcode       =   0;
    return $return_string;
}
endif; // end   wpestate_list_items_by_id_function 






///////////////////////////////////////////////////////////////////////////////////////////
// login form  function
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_login_form_function') ):
  
function wpestate_login_form_function($attributes, $content = null) {
     // get user dashboard link
        global $wpdb;
        $redirect='';
        $mess='';
        $allowed_html   =   array();
        $attributes = shortcode_atts( 
            array(
                'register_label'                  => '',
                'register_url'                =>  '',

            ), $attributes) ;  
        
       if(isset($_GET['key']) && $_GET['action'] == "reset_pwd") {
            $reset_key  = sanitize_text_field ( wp_kses($_GET['key'],$allowed_html) );
            $user_login = sanitize_text_field ( wp_kses($_GET['login'],$allowed_html) );
            $user_data  = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users 
                    WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));
            
            
            if(!empty($user_data)){
                    $user_login = $user_data->user_login;
                    $user_email = $user_data->user_email;

                    if(!empty($reset_key) && !empty($user_data)) {
                            $new_password = wp_generate_password(7, false); 
                            wp_set_password( $new_password, $user_data->ID );
                            //mailing the reset details to the user
                            $message = esc_html__( 'Your new password for the account at:','wpestate') . "\r\n\r\n";
                            $message .= get_bloginfo('name') . "\r\n\r\n";
                            $message .= sprintf(esc_html__( 'Username: %s','wpestate'), $user_login) . "\r\n\r\n";
                            $message .= sprintf(esc_html__( 'Password: %s','wpestate'), $new_password) . "\r\n\r\n";
                            $message .= esc_html__( 'You can now login with your new password at: ','wpestate') . get_option('siteurl')."/" . "\r\n\r\n";
                            
                            $arguments=array(
                                'user_pass'        =>  $new_password,
                            );
                            wpestate_select_email_type($user_email,'password_reseted',$arguments);
                            
                            $mess= '<div class="login-alert">'.esc_html__( 'A new password was sent via email!','wpestate').'</div>';
                            
                    }
                    else {
                        exit('Not a Valid Key.');
                    }
            }// end if empty
        } 
  
    $post_id=get_the_ID();
    //$login_nonce=wp_nonce_field( 'login_ajax_nonce', 'security-login',true,false );
    $security_nonce=wp_nonce_field( 'forgot_ajax_nonce', 'security-forgot',true,false );
    $return_string='<div class="login_form shortcode-login" id="login-div">
        <div class="loginalert" id="login_message_area_sh" >'.$mess.'</div>
        
                <div class="loginrow">
                    <input type="text" class="form-control" name="log" id="login_user_sh" placeholder="'.esc_html__( 'Username','wpestate').'" size="20" />
                </div>
                
                <div class="loginrow">
                    <input type="password" class="form-control" name="pwd" id="login_pwd_sh"  placeholder="'.esc_html__( 'Password','wpestate').'" size="20" />
                </div>
                
                <input type="hidden" name="loginpop" id="loginpop" value="0">
                <input type="hidden" id="security-login_sh" name="security-login" value="'. estate_create_onetime_nonce( 'login_ajax_nonce' ).'">
                      
                
                <button id="wp-login-but_sh" class="wpb_button  wpb_btn-info  wpb_btn-small   wpestate_vc_button  vc_button">'.esc_html__( 'Login','wpestate').'</button>
                <div class="login-links shortlog">';
    
          
                if(isset($attributes['register_label']) && $attributes['register_label']!=''){
                     $return_string.='<a href="'.$attributes['register_url'].'">'.$attributes['register_label'].'</a> | ';
                }         
                $return_string.='<a href="#" id="forgot_pass">'.esc_html__( 'Forgot Password?','wpestate').'</a>
                </div>';
                $facebook_status    =   esc_html( get_option('wp_estate_facebook_login','') );
                $google_status      =   esc_html( get_option('wp_estate_google_login','') );
                $yahoo_status       =   esc_html( get_option('wp_estate_yahoo_login','') );
               
                
                if($facebook_status=='yes'){
                    $return_string.='<div id="facebooklogin_sh" data-social="facebook"><i class="fa fa-facebook"></i>'.esc_html__( 'Login with Facebook','wpestate').'</div>';
                }
                if($google_status=='yes'){
                    $return_string.='<div id="googlelogin_sh" data-social="google"><i class="fa fa-google"></i>'.esc_html__( 'Login with Google','wpestate').'</div>';
                }
                if($yahoo_status=='yes'){
                    $return_string.='<div id="yahoologin_sh" data-social="yahoo"><i class="fa fa-yahoo"></i>'.esc_html__( 'Login with Yahoo','wpestate').'</div>';
                }
                   
        $return_string.='                 
        </div>
        <div class="login_form  shortcode-login" id="forgot-pass-div">
            <div class="loginalert" id="forgot_pass_area"></div>
            <div class="loginrow">
                    <input type="text" class="form-control" name="forgot_email" id="forgot_email" placeholder="'.esc_html__( 'Enter Your Email Address','wpestate').'" size="20" />
            </div>
            '. $security_nonce.'  
            <input type="hidden" id="postid" value="'.$post_id.'">    
            <button class="wpb_button  wpb_btn-info  wpb_btn-small   wpestate_vc_button  vc_button" id="wp-forgot-but" name="forgot" >'.esc_html__( 'Reset Password','wpestate').'</button>
            <div class="login-links shortlog">
            <a href="#" id="return_login">'.esc_html__( 'Return to Login','wpestate').'</a>
            </div>
        </div>';
    return  $return_string;
}
endif; // end   wpestate_login_form_function 



///////////////////////////////////////////////////////////////////////////////////////////
// register form  function
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_register_form_function') ):

function wpestate_register_form_function($attributes, $content = null) {
 
    $attributes = shortcode_atts( 
    array(
        'type'                 => '_sh',
    ), $attributes) ;
    
    if ( isset($attributes['type']) ){
        $type=$attributes['type'];
    }
    
    
    $register_nonce=wp_nonce_field( 'register_ajax_nonce', 'security-register',true,false );
    $return_string='
        <div class="login_form shortcode-login">
                <div class="loginalert" id="register_message_area'.$type.'" ></div>
               
                <div class="loginrow">
                    <input type="text" name="user_login_register" id="user_login_register'.$type.'" class="form-control" placeholder="'.esc_html__( 'Username','wpestate').'" size="20" />
                </div>';
 
        $enable_user_pass_status= esc_html ( get_option('wp_estate_enable_user_pass','') );
        if($enable_user_pass_status == 'yes'){
            $return_string.='
            <div class="loginrow">
                <input type="text" name="user_email_register" id="user_email_register'.$type.'" class="form-control" placeholder="'.esc_html__( 'Email','wpestate').'" size="20" />
            </div>
            
            <div class="loginrow">
                <input type="password" name="user_password" id="user_password'.$type.'" class="form-control" placeholder="'.esc_html__( 'Password','wpestate').'" size="20" />
            </div>';
            
            $return_string.='
            <div class="loginrow">
                <input type="password" name="user_password_retype" id="user_password_retype'.$type.'" class="form-control" placeholder="'.esc_html__( 'Retype Password','wpestate').'" size="20" />
            </div>';
             
        }else{
            $return_string.='
            <div class="loginrow">
                <input type="text" name="user_email_register" id="user_email_register'.$type.'" class="form-control" placeholder="'.esc_html__( 'Email','wpestate').'" size="20" />
            </div>'; 
        }
    
               
    
    $separate_users_status= esc_html ( get_option('wp_estate_separate_users','') );            
    
    if($separate_users_status=='yes'){
        $return_string.='
            <div class="acc_radio">
            <input type="radio" name="acc_type'.$type.'" id="acctype0" value="1" checked required> 
            <div class="radiolabel" for="acctype0">'.esc_html__('I only want to book','wpestate').'</div><br>
            <input type="radio" name="acc_type'.$type.'" id="acctype1" value="0" required>
            <div class="radiolabel" for="acctype1">'.esc_html__('I want to rent my property','wpestate').'</div></div> ';
        }

    $return_string.=' 
        <input type="checkbox" name="terms" id="user_terms_register_sh'.$type.'">
        <label id="user_terms_register_sh_label" for="user_terms_register_sh">'.esc_html__( 'I agree with ','wpestate').'<a href="'.wpestate_get_terms_links().'" target="_blank" id="user_terms_register_topbar_link">'.esc_html__( 'terms & conditions','wpestate').'</a> </label>';
        
        if( esc_html ( get_option('wp_estate_use_captcha','') )=='yes'){
            if($type=='_sh'){
                $return_string.='<div id="capthca_register'.$type.'" style="margin:10px 0px;float:left;transform:scale(1.02);-webkit-transform:scale(1.02);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>';
            }else{
                $return_string.='<div id="capthca_register'.$type.'" style="float:left;transform:scale(1.02);-webkit-transform:scale(1.02);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>';
            }
        }      
        
        $return_string.='<button id="wp-submit-register'.$type.'"  style="margin-top:10px;" class="wpb_button  wpb_btn-info  wpb_btn-small wpestate_vc_button  vc_button">'.esc_html__( 'Create an account','wpestate').'</button>';
    
        
        if($enable_user_pass_status != 'yes'){
            $return_string.='<p id="reg_passmail">'.esc_html__( '*A password will be e-mailed to you','wpestate').'</p>';
        }
   
        $return_string.=' 
        <input type="hidden" id="security-register'.$type.'" name="security-register" value="'. estate_create_onetime_nonce( 'register_ajax_nonce' ).'">';

        $social_register_on  =   esc_html( get_option('wp_estate_social_register_on','') );
        if($social_register_on=='yes'){

            $facebook_status    =   esc_html( get_option('wp_estate_facebook_login','') );
            $google_status      =   esc_html( get_option('wp_estate_google_login','') );
            $yahoo_status       =   esc_html( get_option('wp_estate_yahoo_login','') );

            $return_string.='<div class="register_separator"></div>';
            if($facebook_status=='yes'){
                $return_string.='<div id="facebooklogin_sh_reg" data-social="facebook"><i class="fa fa-facebook"></i>'.esc_html__( 'Login with Facebook','wpestate').'</div>';
            }
            if($google_status=='yes'){
                $return_string.='<div id="googlelogin_sh_reg" data-social="google"><i class="fa fa-google"></i>'.esc_html__( 'Login with Google','wpestate').'</div>';
            }
            if($yahoo_status=='yes'){
                $return_string.='<div id="yahoologin_sh_reg" data-social="yahoo"><i class="fa fa-yahoo"></i>'.esc_html__( 'Login with Yahoo','wpestate').'</div>';
            }

       }
               
    $return_string.='</div>';
    return  $return_string;
}
endif; // end   wpestate_register_form_function   



///////////////////////////////////////////////////////////////////////////////////////////
/// featured article
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_featured_article') ):


function wpestate_featured_article($attributes, $content = null) {
    global $design_class;
    $return_string  =   '';
    $article        =   0;
    $second_line    =   '';
    $design_class   =   '';
    $type           =   '';
      
    $attributes = shortcode_atts( 
        array(
            'id'     => 0,
            'type'  => "type1",
        ), $attributes) ;

    
     
    if(isset($attributes['id'])){
        $article = intval($attributes['id']);
    }
    
    if(isset($attributes['type'])){
       
         $type = ($attributes['type']);
    }
    
    if( isset($attributes['second_line'] )){
        $second_line = $attributes['second_line']; 
    }
    
    $args = array(  
            'post_type' => 'post',
            'p'         => $article
        );


    if($type=='type1'){
        $design_class = 'type_1_class';
    }
    
    
    $my_query = new WP_Query($args);
    ob_start(); 
    while ($my_query->have_posts() ): $my_query->the_post();
        get_template_part('templates/blog_unit_featured');
    endwhile;
    $return_string .= ob_get_contents();
    ob_end_clean(); 
       

    wp_reset_query();
    return $return_string;
}
endif; // end   featured_article   


if( !function_exists('wpestate_get_avatar_url') ):

function wpestate_get_avatar_url($get_avatar) {
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}
endif; // end   wpestate_get_avatar_url   




////////////////////////////////////////////////////////////////////////////////////
/// featured property
////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_featured_property') ):
   
function wpestate_featured_property($attributes, $content = null) {
    global $property_unit_slider;

    $property_unit_slider       =   esc_html ( get_option('wp_estate_prop_list_slider','') ); 
    $return_string  =   '';
    $prop_id        =   ''; 
    $sale_line      =   '';
    $desgin_class   =   '';
    $type           =   '';
    
    $attributes = shortcode_atts( 
        array(
            'id'                  => '',
            'type'                     => "type1",
        ), $attributes) ;

    if( isset($attributes['id'])){
        $prop_id=$attributes['id'];
    }
    
    if( isset($attributes['type'])){
        $type=$attributes['type'];
    }
    
    if ( isset($attributes['sale_line'])){
        $sale_line =  $attributes['sale_line'];
    }
    
    $args = array('post_type'   => 'estate_property',
                  'post_status' => 'publish',
                  'p'           => $prop_id
                );

   
    if( $type =='type1' ){
        $desgin_class ='type_1_class';
    }
    
    $return_string= '<div class="featured_property '.$desgin_class.'">';
    $my_query = new WP_Query($args);
    ob_start(); 
    while ($my_query->have_posts() ): $my_query->the_post();
         get_template_part('templates/property_unit_featured'); 
    endwhile;
    $return_string .= ob_get_contents();
    ob_end_clean();  
    wp_reset_query();
    $return_string.='</div>'; 
    return $return_string;
}
endif; // end   wpestate_featured_property



////////////////////////////////////////////////////////////////////////////////////
/// featured agent
////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_featured_agent') ):

function wpestate_featured_agent($attributes, $content = null) {
    global $notes;
    $return_string='';
    $notes  =   '';
    $agent_id   =   $attributes['id'];
    
    $attributes = shortcode_atts( 
        array(
            'id'                  => 0,
            'notes'                =>  '',
        ), $attributes) ;

    if ( isset($attributes['notes']) ){
        $notes=$attributes['notes'];    
    }
    
    $args = array(
        'post_type' => 'estate_agent',
        'p' => $agent_id
        );
 
    
    
  
    $my_query = new WP_Query($args);
    ob_start(); 
    while ($my_query->have_posts() ): $my_query->the_post();
        get_template_part('templates/agent_unit_featured'); 
    endwhile;
    $return_string = ob_get_contents();
    ob_end_clean();  
    wp_reset_query();
    return $return_string;
}

endif; // end   wpestate_featured_agent   










////////////////////////////////////////////////////////////////////////////////////////////
///  shortcode - recent post with picture
////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_recent_posts_pictures') ):

function wpestate_recent_posts_pictures($attributes, $content = null) {
    global $options;
    global $align;
    global $align_class;
    global $post;
    global $currency;
    global $where_currency;
    global $is_shortcode;
    global $show_compare_only;
    global $row_number_col;
    global $row_number;
    global $curent_fav;
    global $current_user;
    global $listing_type;
    global $property_unit_slider;
    $property_unit_slider       =   esc_html ( get_option('wp_estate_prop_list_slider','') ); 
    $listing_type   =   get_option('wp_estate_listing_unit_type','');
    $current_user = wp_get_current_user();
    $userID             =   $current_user->ID;
    $user_option        =   'favorites'.$userID;
    $curent_fav         =   get_option($user_option);

    
    $options            =   wpestate_page_details($post->ID);
    $return_string      =   '';
    $pictures           =   '';
    $button             =   '';
    $class              =   '';
    $category=$action=$city=$area='';
    $title              =   '';
    $currency           =   esc_html( get_option('wp_estate_currency_symbol', '') );
    $where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
    $is_shortcode       =   1;
    $show_compare_only  =   'no';
    $row_number_col     =   '';
    $row_number         =   '';       
    $show_featured_only =   '';
    $full_row           =   '';
    $extra_class_name   =   '';
    $random_pick        =   '';
    $orderby            =   'meta_value';
  
    if ( isset($attributes['title']) ){
        $title=$attributes['title'];
    }
    
    $attributes = shortcode_atts( 
        array(
            'full_row'              =>  'yes',
            'title'                 =>  '',
            'type'                  => 'properties',
            'category_ids'          =>  '',
            'action_ids'            =>  '',
            'city_ids'              =>  '',
            'area_ids'              =>  '',
            'number'                =>  4,
            'rownumber'             =>  4,
            'align'                 =>  'vertical',
            'link'                  =>  '',
            'show_featured_only'    =>  'no',
            'random_pick'           =>  'no',
            'extra_class_name'      =>  '',
    ), $attributes) ;

    
    if ( isset($attributes['category_ids']) ){
        $category=$attributes['category_ids'];
    }
    
    if ( isset($attributes['category_ids']) ){
        $category=$attributes['category_ids'];
    }

    if ( isset($attributes['action_ids']) ){
        $action=$attributes['action_ids'];
    }

    if ( isset($attributes['city_ids']) ){
        $city=$attributes['city_ids'];
    }

    if ( isset($attributes['area_ids']) ){
        $area=$attributes['area_ids'];
    }
    
    if ( isset($attributes['show_featured_only']) ){
        $show_featured_only=$attributes['show_featured_only'];
    }

    if( isset($attributes['full_row'])){
        $full_row=$attributes['full_row'];
    }     
    
    if (isset($attributes['random_pick'])){
        $random_pick=   $attributes['random_pick'];
        if($random_pick==='yes'){
            $orderby    =   'rand';
        }
    }
    
    if( isset($attributes['extra_class_name'])){
        $extra_class_name=$attributes['extra_class_name'];
    }        
    
    
    $post_number_total = $attributes['number'];
    if ( isset($attributes['rownumber']) ){
        $row_number        = $attributes['rownumber']; 
    }
    
    // max 4 per row
    if($row_number>5){
        $row_number=5;
    }
    
    if( $row_number == 4 ||  $row_number == 5 ){
        $row_number_col = 3; // col value is 3 
    }else if( $row_number==3 ){
        $row_number_col = 4; // col value is 4
    }else if ( $row_number==2 ) {
        $row_number_col =  6;// col value is 6
    }else if ($row_number==1) {
        $row_number_col =  12;// col value is 12
    }
    
    $align=''; 
    $align_class='';
    if(isset($attributes['align']) && $attributes['align']=='horizontal'){
        $align="col-md-12";
        $align_class='the_list_view';
        $row_number_col='12';
    }
    
  
    if ($attributes['type'] == 'properties') {
        $type = 'estate_property';
        
        $category_array =   '';
        $action_array   =   '';
        $city_array     =   '';
        $area_array     =   '';
        
        // build category array
        if($category!=''){
            $category_of_tax=array();
            $category_of_tax=  explode(',', $category);
            $category_array=array(     
                            'taxonomy'  => 'property_category',
                            'field'     => 'term_id',
                            'terms'     => $category_of_tax
                            );
        }
            
        
        // build action array
        if($action!=''){
            $action_of_tax=array();
            $action_of_tax=  explode(',', $action);
            $action_array=array(     
                            'taxonomy'  => 'property_action_category',
                            'field'     => 'term_id',
                            'terms'     => $action_of_tax
                            );
        }
        
        // build city array
        if($city!=''){
            $city_of_tax=array();
            $city_of_tax=  explode(',', $city);
            $city_array=array(     
                            'taxonomy'  => 'property_city',
                            'field'     => 'term_id',
                            'terms'     => $city_of_tax
                            );
        }
        
        // build city array
        if($area!=''){
            $area_of_tax=array();
            $area_of_tax=  explode(',', $area);
            $area_array=array(     
                            'taxonomy'  => 'property_area',
                            'field'     => 'term_id',
                            'terms'     => $area_of_tax
                            );
        }
        
        
            $meta_query=array();                
            if($show_featured_only=='yes'){
                $compare_array=array();
                $compare_array['key']        = 'prop_featured';
                $compare_array['value']      = 1;
                $compare_array['type']       = 'numeric';
                $compare_array['compare']    = '=';
                $meta_query[]                = $compare_array;
            }

        
            $args = array(
                'post_type'         => $type,
                'post_status'       => 'publish',
                'paged'             => 0,
                'posts_per_page'    => $post_number_total,
                'meta_key'          => 'prop_featured',
                'orderby'           => $orderby,
                'order'             => 'DESC',
                'meta_query'        => $meta_query,
                'tax_query'         => array( 
                                        $category_array,
                                        $action_array,
                                        $city_array,
                                        $area_array
                                    )
              
            );
        

          
    } else {
        $type = 'post';
        $args = array(
            'post_type'         =>  'post',
            'status'            =>  'published',
            'paged'             =>  0,
            'posts_per_page'    =>  $post_number_total,
            'cat'               =>  $category
        );
    }


    if ( isset($attributes['link']) && $attributes['link'] != '') {
        if ($attributes['type'] == 'properties') {
            $button .= '<div class="listinglink-wrapper">
               <a href="' . $attributes['link'] . '"> <span class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button more_list">'.esc_html__( 'More Listings','wpestate').' </span></a> 
               </div>';
        } else {
            $button .= '<div class="listinglink-wrapper">
               <a href="' . $attributes['link'] . '"> <span class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button more_list">  '.esc_html__( 'More Articles','wpestate').' </span></a> 
               </div>';
        }
    } else {
        $class = "nobutton";
    }


    if ($attributes['type'] == 'properties') {
        if($random_pick !=='yes'){
            add_filter( 'posts_orderby', 'wpestate_my_order' ); 
            $recent_posts = new WP_Query($args);
            $count = 1;
            remove_filter( 'posts_orderby', 'wpestate_my_order' ); 
        }else{
            $recent_posts = new WP_Query($args); 
            $count = 1;
        }
   
    }else{
        $recent_posts = new WP_Query($args);
        $count = 1;
    }
    
    
    if($full_row==='yes'){
        $return_string .= '<div class="  '.$extra_class_name.' " >';
    }else{
        $return_string .= '<div class=" bottom-'.$type.' '.$class.' '.$extra_class_name.'" >';
        if($title!=''){
             $return_string .= '<h1 class="shortcode_title">'.$title.'</h1>';
        }
    }
   
   
    ob_start();  
 
    print '<div class="items_shortcode_wrapper';
      if($full_row==='yes'){
          print ' items_shortcode_wrapper_full ';
      }
    print'  ">';
    while ($recent_posts->have_posts()): $recent_posts->the_post();
        if($type == 'estate_property'){
            if($full_row==='yes'){
                get_template_part('templates/property_unit_full_row');
            }else{
                get_template_part('templates/property_unit');
            }
            
            
            
        } else {
            if($full_row==='yes'){
                get_template_part('templates/blog_unit_full_row');
            }else{
                get_template_part('templates/blog_unit');
            }
        }
    endwhile;
    print '</div>';
    $templates = ob_get_contents();
    ob_end_clean(); 
    $return_string .=$templates;
    if($full_row !='yes'){
        $return_string .=$button;
    }
   
    $return_string .= '</div>';
    wp_reset_query();
    $is_shortcode       =   0;
    return $return_string;   
}
endif; // end   wpestate_recent_posts_pictures 



if( !function_exists('wpestate_limit_words') ):

function wpestate_limit_words($string, $max_no) {
    $words_no = explode(' ', $string, ($max_no + 1));

    if (count($words_no) > $max_no) {
        array_pop($words_no);
    }

    return implode(' ', $words_no);
}
endif; // end   wpestate_limit_words  







////////////////////////////////////////////////////////////////////////////////////////////////////////////////..
///  shortcode - testimonials
////////////////////////////////////////////////////////////////////////////////////////////////////////////////..


if( !function_exists('wpestate_testimonial_function') ):
function wpestate_testimonial_function($attributes, $content = null) {
    $return_string='';
    $title_client='';
    $client_name='';
    $imagelinks='';
    $testimonial_text='';
    $attributes = shortcode_atts( 
            array(
                'client_name'                  => 'Name Here',
                'title_client'                 => "happy client",
                'imagelinks'                   => '',
                'testimonial_text'             => '',
                'extra_class_name'             => '',

            ), $attributes) ;

        
    if ( $attributes['client_name'] ){
     $client_name   =   $attributes['client_name'];
    }
    
    if( $attributes['title_client'] ){
        $title_client   =   $attributes['title_client'] ;
    }
    
    if( $attributes['imagelinks'] ){
        $imagelinks   =   $attributes['imagelinks']  ;
    }
    
    if( $attributes['testimonial_text'] ){
        $testimonial_text   =   $attributes['testimonial_text']  ;
    }
    
    $return_string .= ' <div class="testimonial-container">';
    $return_string .= '     <div class="testimonial-text">'.$testimonial_text.'</div>';    

    $return_string .= '     <div class="testimonial-image" style="background-image:url(' .$imagelinks . ')"></div>';
        $return_string .= '     <div class="testimonial-author-line"><span class="testimonial-author">' . $client_name .'</span> '.$title_client.' </div>';
    $return_string .= ' </div>';

    return $return_string;
}
endif; // end   wpestate_testimonial_function 



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  shortcode - reccent post function
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_recent_posts_function') ):


function wpestate_recent_posts_function($attributes, $heading = null) {
    $return_string='';
    extract(shortcode_atts(array(
        'posts' => 1,
                    ), $attributes));

    query_posts(array('orderby' => 'date', 'order' => 'DESC', 'showposts' => $posts));
    $return_string = '<div id="recent_posts"><ul><h3>' . $heading . '</h3>';
    if (have_posts()) :
        while (have_posts()) : the_post();
            $return_string .= '<li><a href="' . esc_url( get_permalink() ). '">' . get_the_title() . '</a></li>';
        endwhile;
    endif;

    $return_string.='</div></ul>';
    wp_reset_query();

    return $return_string;
}
endif; // end   wpestate_recent_posts_function   
?>