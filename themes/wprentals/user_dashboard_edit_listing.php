<?php
// Template Name: User Dashboard Edit
// Wp Estate Pack

if ( !is_user_logged_in() ) {   
     wp_redirect( home_url('') );exit();
} 
if ( !wpestate_check_user_level()){
   wp_redirect(  esc_html( home_url() ) );exit(); 
}


global $show_err;
global $edit_id;
$current_user = wp_get_current_user();
$userID                         =   $current_user->ID;
$user_pack                      =   get_the_author_meta( 'package_id' , $userID );
$status_values                  =   esc_html( get_option('wp_estate_status_list') );
$status_values_array            =   explode(",",$status_values);
$feature_list_array             =   array();
$feature_list                   =   esc_html( get_option('wp_estate_feature_list') );
$feature_list_array             =   explode( ',',$feature_list);
$allowed_html                   =   array();




if( isset( $_GET['listing_edit'] ) && is_numeric( $_GET['listing_edit'] ) ){
    ///////////////////////////////////////////////////////////////////////////////////////////
    /////// If we have edit load current values
    ///////////////////////////////////////////////////////////////////////////////////////////
    $edit_id                        =  intval ($_GET['listing_edit']);

    $the_post= get_post( $edit_id); 
    if( $current_user->ID != $the_post->post_author ) {
        exit('You don\'t have the rights to edit this');
    }
  
    $show_err                       =   '';
    $action                         =   'edit';
    $submit_title                   =   get_the_title($edit_id);
    $submit_description             =   get_post_field('post_content', $edit_id);
    
    $action_array=array("description","location","price","details","images","amenities","calendar");
   
    if ( isset( $_GET['action'] ) && in_array( $_GET['action'],$action_array) ){

        $action =sanitize_text_field(  wp_kses ( $_GET['action'],$allowed_html) );
        
        if ($action == 'description'){
            ///////////////////////////////////////////////////////////////////////////////////////
            // action description
            ///////////////////////////////////////////////////////////////////////////////////////
            $prop_category_array            =   get_the_terms($edit_id, 'property_category');
            if(isset($prop_category_array[0])){
                 $prop_category_selected   =   $prop_category_array[0]->term_id;
            }

            $prop_action_category_array     =   get_the_terms($edit_id, 'property_action_category');
            if(isset($prop_action_category_array[0])){
                $prop_action_category_selected           =   $prop_action_category_array[0]->term_id;
            }


            $property_city_array            =   get_the_terms($edit_id, 'property_city');

            if(isset($property_city_array [0])){
                  $property_city                  =   $property_city_array [0]->name;
            }
    
            $property_area_array            =   get_the_terms($edit_id, 'property_area');
            if(isset($property_area_array [0])){
                  $property_area                  =   $property_area_array [0]->name;
            }
            
            $guestnumber            =  get_post_meta($edit_id, 'guest_no', true);
            $property_country       =  esc_html   ( get_post_meta($edit_id, 'property_country', true) );
            $property_admin_area    =  esc_html   ( get_post_meta($edit_id, 'property_admin_area', true) );
            ///////////////////////////////////////////////////////////////////////////////////////
            // action description
            ///////////////////////////////////////////////////////////////////////////////////////
        }else if ($action =='location'){
             ///////////////////////////////////////////////////////////////////////////////////////
            // action location
            ///////////////////////////////////////////////////////////////////////////////////////
            $property_country       =  esc_html   ( get_post_meta($edit_id, 'property_country', true) );
            $property_latitude      =  floatval   ( get_post_meta($edit_id, 'property_latitude', true) );
            $property_longitude     =  floatval   ( get_post_meta($edit_id, 'property_longitude', true) );
            $google_camera_angle    =  floatval   ( get_post_meta($edit_id, 'google_camera_angle', true) );
            $property_address       =  esc_html   ( get_post_meta($edit_id, 'property_address', true) );
            $property_zip           =  esc_html   ( get_post_meta($edit_id, 'property_zip', true) );
            $property_state           =  esc_html   ( get_post_meta($edit_id, 'property_state', true) );
            $property_county          =  esc_html   ( get_post_meta($edit_id, 'property_county', true) );
           
            
            $property_city_array            =   get_the_terms($edit_id, 'property_city');
            if(isset($property_city_array [0])){
                  $property_city                  =   $property_city_array [0]->name;
            }
            ///////////////////////////////////////////////////////////////////////////////////////
            // action location
            ///////////////////////////////////////////////////////////////////////////////////////
        }else if ($action =='price'){
            
            //$mega=get_post_meta($edit_id, 'mega_details'.$edit_id,true );
            //print'test';  print_r($mega);
            /*  [1457308800] => Array
        (
            [period_min_days_booking] => 5
            [period_extra_price_per_guest] => 15
            [period_price_per_weekeend] => 25
            [period_checkin_change_over] => 1
            [period_checkin_checkout_change_over] => 1
        )*/
            ///////////////////////////////////////////////////////////////////////////////////////
            // action price
            ///////////////////////////////////////////////////////////////////////////////////////
            $property_price                 =   floatval   ( get_post_meta($edit_id, 'property_price', true) );
            $cleaning_fee                   =   floatval   ( get_post_meta($edit_id, 'cleaning_fee', true) );
            $city_fee                       =   floatval   ( get_post_meta($edit_id, 'city_fee', true) );
            $property_label                 =   esc_html ( get_post_meta($edit_id, 'property_label', true) );  
            $property_price_week            =   floatval   ( get_post_meta($edit_id, 'property_price_per_week', true) );
            $property_price_month           =   floatval   ( get_post_meta($edit_id, 'property_price_per_month', true) );
       
            $cleaning_fee_per_day           =   floatval  ( get_post_meta($edit_id,  'cleaning_fee_per_day', true) );
            $city_fee_per_day               =   floatval   ( get_post_meta($edit_id, 'city_fee_per_day', true) );
            $price_per_guest_from_one       =   floatval   ( get_post_meta($edit_id, 'price_per_guest_from_one', true) );
            $overload_guest                 =   floatval   ( get_post_meta($edit_id, 'overload_guest', true) );
            $checkin_change_over            =   floatval   ( get_post_meta($edit_id, 'checkin_change_over', true) );  
            $checkin_checkout_change_over   =   floatval   ( get_post_meta($edit_id, 'checkin_checkout_change_over', true) );  
            $min_days_booking               =   floatval   ( get_post_meta($edit_id, 'min_days_booking', true) );  
            $extra_price_per_guest          =   floatval   ( get_post_meta($edit_id, 'extra_price_per_guest', true) );  
            $price_per_weekeend             =   floatval   ( get_post_meta($edit_id, 'price_per_weekeend', true) );  
            
           if($cleaning_fee_per_day==1){
                $cleaning_fee_per_day = 'checked';
            }
          
            if($city_fee_per_day==1){
                $city_fee_per_day = 'checked';
            }
            
            if($price_per_guest_from_one==1){
                $price_per_guest_from_one = 'checked';
            }
            
            if($overload_guest==1){
                $overload_guest = 'checked';
            }
            
            if($property_price==0){
                $property_price='';
            }
            
            if($cleaning_fee==0){
                $cleaning_fee='';
            }
            
            if($city_fee==0){
                $city_fee='';
            }
            
            if($property_label==0){
                $property_label='';
            }
            
            if($property_price_week==0){
                $property_price_week='';
            }
            
            if($property_price_month==0){
                $property_price_month='';
            }
            
            
            
            if($min_days_booking==0){
                $min_days_booking='';
            }
            
            if($price_per_weekeend==0){
                $price_per_weekeend='';
            }
            
           
            
            ///////////////////////////////////////////////////////////////////////////////////////
            // action price
            ///////////////////////////////////////////////////////////////////////////////////////
            
        }else if ($action =='details'){
            ///////////////////////////////////////////////////////////////////////////////////////
            // action details
            ///////////////////////////////////////////////////////////////////////////////////////
            $property_size      =   floatval   ( get_post_meta($edit_id, 'property_size', true) );
            if($property_size==0){
                $property_size='';
            }
            $property_rooms     =   floatval   ( get_post_meta($edit_id, 'property_rooms', true) );
            if($property_rooms==0){
                $property_rooms='';
            }
            $property_bedrooms  =   floatval   ( get_post_meta($edit_id, 'property_bedrooms', true) );
            if($property_bedrooms==0){
                $property_bedrooms='';
            }
            $property_bathrooms =   floatval   ( get_post_meta($edit_id, 'property_bathrooms', true) );
            if($property_bathrooms==0){
                $property_bathrooms='';
            }
            
            $custom_fields = get_option( 'wp_estate_custom_fields', true);    

            $i=0;
            if( !empty($custom_fields)){  
                while($i< count($custom_fields) ){
                   $name    =   $custom_fields[$i][0];
                   $type    =   $custom_fields[$i][2];
                   $slug    =   wpestate_limit45(sanitize_title( $name ));
                   $slug    =   sanitize_key($slug);

                   $custom_fields_array[$slug]=esc_html(get_post_meta($edit_id, $slug, true));
                   $i++;
                }
            }
    
            ///////////////////////////////////////////////////////////////////////////////////////
            // action details
            ///////////////////////////////////////////////////////////////////////////////////////
            
        }else if ($action =='images'){
            ///////////////////////////////////////////////////////////////////////////////////////
            // action images
            ///////////////////////////////////////////////////////////////////////////////////////
            
            $embed_video_id     =   esc_html ( get_post_meta($edit_id, 'embed_video_id', true) ); 
            $option_video       =   '';
            $video_values       =   array('vimeo', 'youtube');
            $video_type         =   esc_html ( get_post_meta($edit_id, 'embed_video_type', true) ); 
            foreach ($video_values as $value) {
                $option_video.='<option value="' . $value . '"';
                if ($value == $video_type) {
                    $option_video.='selected="selected"';
                }
                $option_video.='>' . $value . '</option>';
            }
            ///////////////////////////////////////////////////////////////////////////////////////
            // action images
            ///////////////////////////////////////////////////////////////////////////////////////
            
        }else if ($action =='amenities'){
           
            $feature_list_array             =   array();
            $feature_list                   =   esc_html( get_option('wp_estate_feature_list') );
            $feature_list_array             =   explode( ',',$feature_list);

            foreach($feature_list_array as $key => $value){
                $post_var_name      =   str_replace(' ','_', trim($value) );
                $post_var_name      =   wpestate_limit45(sanitize_title( $post_var_name ));
                $post_var_name      =   sanitize_key($post_var_name);
                
                if(isset( $_POST[$post_var_name])){
                    $feature_value  =   wp_kses( $_POST[$post_var_name] ,$allowed_html);  
                    update_post_meta($edit_id, $post_var_name, $feature_value);
                    $moving_array[] =   $post_var_name;
                }
            }
   
            
        } else if ($action =='calendar'){
        
            $property_icalendar_import =   get_post_meta($edit_id, 'property_icalendar_import', true);
       
        }
        
    }else{
        exit();
    }
    
}

get_header();
$options=wpestate_page_details($post->ID);
///////////////////////////////////////////////////////////////////////////////////////////
/////// Html Form Code below
///////////////////////////////////////////////////////////////////////////////////////////
?> 

<div id="cover"></div>
<div class="row is_dashboard">  
    <?php
    if( wpestate_check_if_admin_page($post->ID) ){
        if ( is_user_logged_in() ) {   
            get_template_part('templates/user_menu'); 
        }  
    }
    ?> 

    <div class="dashboard-margin">
        <div class="dashboard-header">
            <?php get_template_part('templates/submission_guide');?>
        </div>   
        
     
        <?php while (have_posts()) : the_post(); ?>
            <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') { ?>
                <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php }
            endwhile; // end of the loop. ?>
            <div class="row">
            <?php
            
                if (isset($_GET['isnew']) && ($_GET['isnew']==1 ) ){
                    print ' <div class="col-md-12 new-listing-alert">'.esc_html__( 'Congratulations, you have just added a new listing! Now go and fill in the rest of the details.','wpestate').'</div>';
                }
            
                if ($action == 'description'){
                    //  get_template_part('templates/front_end_submission_step1'); 
                    get_template_part('templates/submit_templates/property_description');                    
                }else if ($action =='location'){
                    get_template_part('templates/submit_templates/property_location');
                }else if ($action =='price'){
                    get_template_part('templates/submit_templates/property_price');
                }else if ($action =='details'){
                    get_template_part('templates/submit_templates/property_details');  
                }else if ($action =='images'){
                    get_template_part('templates/submit_templates/property_images');
                }else if ($action =='amenities'){
                    get_template_part('templates/submit_templates/property_amenities');
                }else if ($action =='calendar'){
                    get_template_part('templates/submit_templates/property_calendar');
                }
            ?>                
            </div>
    </div>
</div>   
<?php get_footer();?>