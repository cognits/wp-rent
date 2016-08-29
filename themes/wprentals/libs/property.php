<?php
// register the custom post type
add_action('after_setup_theme', 'wpestate_create_property_type',0);

if( !function_exists('wpestate_create_property_type') ):
function wpestate_create_property_type() {
    register_post_type('estate_property', array(
        'labels' => array(
            'name'                  => esc_html__( 'Properties','wpestate'),
            'singular_name'         => esc_html__( 'Property','wpestate'),
            'add_new'               => esc_html__( 'Add New Property','wpestate'),
            'add_new_item'          => esc_html__( 'Add Property','wpestate'),
            'edit'                  => esc_html__( 'Edit','wpestate'),
            'edit_item'             => esc_html__( 'Edit Property','wpestate'),
            'new_item'              => esc_html__( 'New Property','wpestate'),
            'view'                  => esc_html__( 'View','wpestate'),
            'view_item'             => esc_html__( 'View Property','wpestate'),
            'search_items'          => esc_html__( 'Search Property','wpestate'),
            'not_found'             => esc_html__( 'No Properties found','wpestate'),
            'not_found_in_trash'    => esc_html__( 'No Properties found in Trash','wpestate'),
            'parent'                => esc_html__( 'Parent Property','wpestate')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'properties'),
        'supports' => array('title', 'editor', 'thumbnail', 'comments','excerpt'),
        'can_export' => true,
        'register_meta_box_cb' => 'wpestate_add_property_metaboxes',
        'menu_icon'=>get_template_directory_uri().'/img/properties.png'
         )
    );

    
    
////////////////////////////////////////////////////////////////////////////////////////////////
// Add custom taxomies
////////////////////////////////////////////////////////////////////////////////////////////////
    register_taxonomy('property_category', 'estate_property', array(
        'labels' => array(
            'name'              => esc_html__( 'Categories','wpestate'),
            'add_new_item'      => esc_html__( 'Add New Property Category','wpestate'),
            'new_item_name'     => esc_html__( 'New Property Category','wpestate')
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => 'listings' )
        )
    );


    // add custom taxonomy
    register_taxonomy('property_action_category', 'estate_property', array(
        'labels' => array(
            'name'              => esc_html__( 'What do you rent ?','wpestate'),
            'add_new_item'      => esc_html__( 'Add new option for "What do you rent" ','wpestate'),
            'new_item_name'     => esc_html__( 'Add new option for "What do you rent"','wpestate')
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => 'action' )
       )      
    );



    // add custom taxonomy
    register_taxonomy('property_city', 'estate_property', array(
        'labels' => array(
            'name'              => esc_html__( 'City','wpestate'),
            'add_new_item'      => esc_html__( 'Add New City','wpestate'),
            'new_item_name'     => esc_html__( 'New City','wpestate')
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => 'city' )
        )
    );




    // add custom taxonomy
    register_taxonomy('property_area', 'estate_property', array(
        'labels' => array(
            'name'              => esc_html__( 'Neighborhood / Area','wpestate'),
            'add_new_item'      => esc_html__( 'Add New Neighborhood / Area','wpestate'),
            'new_item_name'     => esc_html__( 'New Neighborhood / Area','wpestate')
        ),
        'hierarchical'  => true,
        'query_var'     => true,
        'rewrite'       => array( 'slug' => 'area' )

        )
    );

}// end create property type
endif; // end   wpestate_create_property_type      



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Add metaboxes for Property
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_add_property_metaboxes') ):
function wpestate_add_property_metaboxes() {
    add_meta_box('estate_property-sectionid',       esc_html__( 'Property Settings', 'wpestate'),      'estate_box', 'estate_property', 'normal', 'default');
    add_meta_box('estate_property-propdetails',     esc_html__( 'Property Details', 'wpestate'),       'details_estate_box', 'estate_property', 'normal', 'default');
    add_meta_box('estate_property-custom',          esc_html__( 'Property Custom', 'wpestate'),        'wpestate_custom_details_box', 'estate_property', 'normal', 'default');
    add_meta_box('estate_property-googlemap',       esc_html__( 'Place It On The Map', 'wpestate'),    'map_estate_box', 'estate_property', 'normal', 'default');
    add_meta_box('estate_property-features',        esc_html__( 'Amenities and Features', 'wpestate'), 'amenities_estate_box', 'estate_property', 'normal', 'default' );
    add_meta_box('estate_property-agent',           esc_html__( 'Owner', 'wpestate'),      'agentestate_box', 'estate_property', 'normal', 'default' );
    add_meta_box('wpestate-paid-submission',        esc_html__( 'Paid Submission',   'wpestate'),      'estate_paid_submission', 'estate_property', 'side', 'high' );  
    //add_meta_box('estate_property-user',            esc_html__( 'Assign property to user', 'wpestate'), 'userestate_box', 'estate_property', 'normal', 'default' );
   
}
endif; // end   wpestate_add_property_metaboxes  





///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Property Custom details  function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_custom_details_box') ):
function wpestate_custom_details_box(){
     global $post;
     $i=0;
     $custom_fields = get_option( 'wp_estate_custom_fields', true);    
     if( !empty($custom_fields)){  
        while($i< count($custom_fields) ){     
            $name =   $custom_fields[$i][0]; 
            $label =   $custom_fields[$i][1];
            $type =   $custom_fields[$i][2];
            // $slug =   sanitize_key ( str_replace(' ','_',$name));
            $slug         =   wpestate_limit45(sanitize_title( $name )); 
            $slug         =   sanitize_key($slug); 
        
             print '<div class="metacustom"> ';
             if ( $type =='long text' ){
                 print '<label for="'.$slug.'">'.$label.' (*text) </label>';
                 print '<textarea type="text" id="'.$slug.'"  size="0" name="'.$slug.'" rows="3" cols="42">' . esc_html(get_post_meta($post->ID, $slug, true)) . '</textarea>'; 
             }else if( $type =='short text' ){
                 print '<label for="'.$slug.'">'.$label.' (*text) </label>';
                 print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . esc_html(get_post_meta($post->ID,$slug, true)) . '">';
             }else if( $type =='numeric'  ){
                 print '<label for="'.$slug.'">'.$label.' (*numeric) </label>';
                 $numeric_value=get_post_meta($post->ID,$slug, true);
                 if($numeric_value!=''){
                     $numeric_value=  floatval($numeric_value);
                 }
                 print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . $numeric_value . '">';
             }else if( $type =='date' ){
                 print '<label for="'.$slug.'">'.$label.' (*date) </label>';
                 print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . esc_html(get_post_meta($post->ID,$slug, true)) . '">';
                 print '<script type="text/javascript">
                       //<![CDATA[
                       jQuery(document).ready(function(){
                            '.wpestate_date_picker_translation($slug).'
                       });
                       //]]>
                       </script>';

             }
             print '</div>';  
             $i++;        
       }
    }
    print '<div style="clear:both"></div>';
     
}
endif; // end     




///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Agent box function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('userestate_box') ):
function userestate_box($post) {
    global  $post;
    $mypost         =   $post->ID;
    $originalpost   =   $post;
    $blog_list      =   '';
    $original_user  =   wpsestate_get_author();


    
    $blogusers = get_users( 'blog_id=1&orderby=nicename&role=subscriber' );

    foreach ( $blogusers as $user ) {
 
        $the_id=$user->ID;
        $blog_list  .=  '<option value="' . $the_id . '"  ';
            if ($the_id == $original_user) {
                $blog_list.=' selected="selected" ';
            }
        $blog_list.= '>' .$user->user_login . '</option>';
    }


    

    print '
    <label for="property_user">'.esc_html__( 'Users: ','wpestate').'</label><br />
    <select id="property_user" style="width: 237px;" name="property_user">
          <option value="1">admin</option>
          <option value=""></option>
          '. $blog_list .'
    </select>';  

}
endif;


///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Property Pay Submission  function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_paid_submission') ):

function estate_paid_submission($post){
  global $post;
  $paid_submission_status= esc_html ( get_option('wp_estate_paid_submission','') );
  if($paid_submission_status=='no'){
     esc_html_e('Paid Submission is disabled','wpestate');  
  }
  
  if($paid_submission_status=='per listing'){
     esc_html_e('Pay Status: ','wpestate');
     $pay_status           = get_post_meta($post->ID, 'pay_status', true);
     if($pay_status=='paid'){
        esc_html_e('PAID','wpestate');
     }
     else{
        esc_html_e('Not Paid','wpestate');
     }
  }
    
}
endif; // end   estate_paid_submission  




///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Property details  function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('details_estate_box') ):

function details_estate_box($post) {
    global $post;
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    $week_days=array(
    '0'=>esc_html__('All','wpestate'),
    '1'=>esc_html__('Monday','wpestate'), 
    '2'=>esc_html__('Tuesday','wpestate'),
    '3'=>esc_html__('Wednesday','wpestate'),
    '4'=>esc_html__('Thursday','wpestate'),
    '5'=>esc_html__('Friday','wpestate'),
    '6'=>esc_html__('Saturday','wpestate'),
    '7'=>esc_html__('Sunday','wpestate')
 
    );
    $mypost             =   $post->ID;
    
    $checkin_change_over            =   floatval   ( get_post_meta($mypost, 'checkin_change_over', true) );  
    $checkin_checkout_change_over   =   floatval   ( get_post_meta($mypost, 'checkin_checkout_change_over', true) ); 
    
    print'            
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr >
    <td width="33%" valign="top" align="left">
        <p class="meta-options">
        <label for="property_price">'.esc_html__( 'Price: ','wpestate').'</label><br />
        <input type="text" id="property_price" size="40" name="property_price" value="' . intval(get_post_meta($mypost, 'property_price', true)) . '">
        </p>
    </td>';
    /*
    <td width="33%" valign="top" align="left">
        <p class="meta-options">
        <label for="property_label">'.esc_html__( 'After Price Label(*for example "per month"): ','wpestate').'</label><br />
        <input type="text" id="property_label" size="40" name="property_label" value="' . esc_html(get_post_meta($mypost, 'property_label', true)) . '">
        </p>
    </td>
    */
    print'
    </tr>
    
    <tr >
    <td width="33%" valign="top" align="left">
        <p class="meta-options">
            <label for="cleaning_fee">'.esc_html__( 'Cleaning Fee:','wpestate').'</label><br />
            <input type="text" id="cleaning_fee" size="40" name="cleaning_fee" value="' . floatval(get_post_meta($mypost, 'cleaning_fee', true)) . '">
        </p>
    </td>
    
    
    <td width="33%" valign="top" align="left">
       <p class="meta-options"> 
              <input type="hidden" name="cleaning_fee_per_day" value="0">
              <input type="checkbox"  id="cleaning_fee_per_day" name="cleaning_fee_per_day" value="1" ';
              if (intval(get_post_meta($mypost, 'cleaning_fee_per_day', true)) == 1) {
                  print'checked="checked"';
              }
              print' />
              <label for="cleaning_fee_per_day">'.esc_html__( 'Cleaning Fee applies per night:','wpestate').'</label>
          </p>
    </td>
    </tr>
    
    <tr>
  
        
    <tr >
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
                <label for="city_fee">'.esc_html__( 'City Fee:','wpestate').'</label><br />
                <input type="text" id="city_fee" size="40" name="city_fee" value="' . floatval(get_post_meta($mypost, 'city_fee', true)) . '">
            </p>
        </td>


        <td width="33%" valign="top" align="left">
            <p class="meta-options"> 
                <input type="hidden" name="city_fee_per_day" value="0">
                <input type="checkbox"  id="city_fee_per_day" name="city_fee_per_day" value="1" ';
                if (intval(get_post_meta($mypost, 'city_fee_per_day', true)) == 1) {
                    print'checked="checked"';
                }
                print' />
                <label for="city_fee_per_day">'.esc_html__( 'City Fee applies per night:','wpestate').'</label>
            </p>
        </td>
    </tr>


    <tr>

        <td width="33%" valign="top" align="left">
            <p class="meta-options">
                <label for="price_per_weekeend">'. esc_html__('Price per weekend (Saturday and Sundays)','wpestate').'</label><br />
                <input type="text" id="price_per_weekeend" size="40" name="price_per_weekeend" value="' . floatval(get_post_meta($mypost, 'price_per_weekeend', true)) . '">
            </p>
        </td>

        <td>
            <p class="meta-options">
            <label for="min_days_booking">'.esc_html__('Minimum days of booking (only numbers) ','wpestate').'</label></br>
            <input type="text" id="min_days_booking" class="form-control" size="40" name="min_days_booking" value="' . floatval(get_post_meta($mypost, 'min_days_booking', true)) . '">
            </p>
        </td>

    </tr>

    
    <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price">'.esc_html__( 'Price per night (7d+): ','wpestate').'</label><br />
            <input type="text" id="property_price_per_week" size="40" name="property_price_per_week" value="' . esc_html(get_post_meta($mypost, 'property_price_per_week', true)) . '">
            </p>
        </td>

        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_price">'.esc_html__( 'Price per night (30d+): ','wpestate').'</label><br />
            <input type="text" id="property_price_per_month" size="40" name="property_price_per_month" value="' . esc_html(get_post_meta($mypost, 'property_price_per_month', true)) . '">
            </p>
        </td>
    </tr>
    
    <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="extra_price_per_guest">'.esc_html__( 'Extra Price per guest per night','wpestate').'</label><br />
            <input type="text" id="extra_price_per_guest" size="40" name="extra_price_per_guest" value="' . esc_html(get_post_meta($mypost, 'extra_price_per_guest', true)) . '">
            </p>
        </td>

        <td width="33%" valign="top" align="left">
           <p class="meta-options"> 
                <input type="hidden" name="overload_guest" value="0">
                <input type="checkbox"  id="overload_guest" name="overload_guest" value="1" ';
                if (intval(get_post_meta($mypost, 'overload_guest', true)) == 1) {
                    print'checked="checked"';
                }
                print' />
                <label for="overload_guest">'.esc_html__( 'Allow guests above capacity?','wpestate').'</label>
            </p>
        </td>
    </tr>
 
    <tr>
        <td valign="top" align="left">
        '.esc_html__('The options below do not work together - choose only one and leave the other one on "All" ','wpestate').'
        </td>
    </tr>
      

      <tr>
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="checkin_change_over">'. esc_html__('Allow only bookings starting with the check in on:','wpestate').'</label></br>
            <select id="checkin_change_over" name="checkin_change_over" class="select-submit2">';
              
                foreach($week_days as $key=>$value){
                    print '   <option value="'.$key.'"';
                    if( $key==$checkin_change_over){
                        print ' selected="selected" ';
                    }
                    print '>'.$value.'</option>';
                }
            print'    
            </select>
            </p>
        </td>

        <td width="33%" valign="top" align="left">
            <p class="meta-options"> 
            <label for="checkin_checkout_change_over">'. esc_html__('Allow only bookings with the check in/check out on: ','wpestate').'</label></br>
            <select id="checkin_checkout_change_over" name="checkin_checkout_change_over" class="select-submit2">';
               
                foreach($week_days as $key=>$value){
                   print '   <option value="'.$key.'"';
                    if( $key==$checkin_checkout_change_over){
                        print ' selected="selected" ';
                    }
                    print '>'.$value.'</option>';
                }
              print'
            </p>
        </td>
    </tr>
    



    <tr>  
        <td width="33%" valign="top" align="left">
            <p class="meta-options">
            <label for="property_size">'.esc_html__( 'Size: ','wpestate').'</label><br />
            <input type="text" id="property_size" size="40" name="property_size" value="' . esc_html(get_post_meta($mypost, 'property_size', true)) . '">
            </p>
        </td>



        <td valign="top" align="left">
            <p class="meta-options">
            <label for="property_rooms">'.esc_html__( 'Rooms: ','wpestate').'</label><br />
            <input type="text" id="property_rooms" size="40" name="property_rooms" value="' . esc_html(get_post_meta($mypost, 'property_rooms', true)) . '">
            </p>
        </td>
    </tr>

    <tr>
        <td valign="top" align="left">
            <p class="meta-options">
            <label for="property_bedrooms">'.esc_html__( 'Bedrooms: ','wpestate').'</label><br />
            <input type="text" id="property_bedrooms" size="40" name="property_bedrooms" value="' . esc_html(get_post_meta($mypost, 'property_bedrooms', true)) . '">
            </p>
        </td>

        <td valign="top" align="left">  
            <p class="meta-options">
            <label for="property_bedrooms">'.esc_html__( 'Bathrooms: ','wpestate').'</label><br />
            <input type="text" id="property_bathrooms" size="40" name="property_bathrooms" value="' . esc_html(get_post_meta($mypost, 'property_bathrooms', true)) . '">
            </p>
        </td>
    </tr>
    
    <tr>
    <td valign="top" align="left">  
        <p class="meta-options">
        <label for="guest_no">'.esc_html__( 'Guests: ','wpestate').'</label><br />
        <input type="text" id="guest_no" size="40" name="guest_no" value="' . esc_html(get_post_meta($mypost, 'guest_no', true)) . '">
        </p>
    </td>
    
    </tr>
    <tr>';
     
     $option_video='';
     $video_values = array('vimeo', 'youtube');
     $video_type = get_post_meta($mypost, 'embed_video_type', true);

     foreach ($video_values as $value) {
         $option_video.='<option value="' . $value . '"';
         if ($value == $video_type) {
             $option_video.='selected="selected"';
         }
         $option_video.='>' . $value . '</option>';
     }
     
     
    print'
    <td valign="top" align="left">
        <p class="meta-options">
        <label for="embed_video_type">'.esc_html__( 'Video from ','wpestate').'</label><br />
        <select id="embed_video_type" name="embed_video_type" style="width: 237px;">
                ' . $option_video . '
        </select>       
        </p>
    </td>';

  
    print'
    <td valign="top" align="left">
      <p class="meta-options">     
      <label for="embed_video_id">'.esc_html__( 'Video id: ','wpestate').'</label> <br />
        <input type="text" id="embed_video_id" name="embed_video_id" size="40" value="'.esc_html( get_post_meta($mypost, 'embed_video_id', true) ).'">
      </p>
    </td>
    </tr>
    </table>';
}
endif; // end   details_estate_box  



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Google map function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('map_estate_box') ):
 
function map_estate_box($post) {
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    global $post;
    
    $mypost                 =   $post->ID;
    $gmap_lat               =   floatval(get_post_meta($mypost, 'property_latitude', true));
    $gmap_long              =   floatval(get_post_meta($mypost, 'property_longitude', true));
    $google_camera_angle    =   intval( esc_html(get_post_meta($mypost, 'google_camera_angle', true)) );
    $cache_array            =   array('yes','no');
    $keep_min_symbol        =   '';
    $keep_min_status        =   esc_html ( get_post_meta($post->ID, 'keep_min', true) );

    foreach($cache_array as $value){
            $keep_min_symbol.='<option value="'.$value.'"';
            if ($keep_min_status==$value){
                    $keep_min_symbol.=' selected="selected" ';
            }
            $keep_min_symbol.='>'.$value.'</option>';
    }
    
    print '<script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready(function(){
        '.wpestate_date_picker_translation("property_date").'
    });
    //]]>
    </script>
    <p class="meta-options"> 
    <div id="googleMap" style="width:100%;height:380px;margin-bottom:30px;"></div>    
    <p class="meta-options"> 
        <a class="button" href="#" id="admin_place_pin">'.esc_html__( 'Place Pin with Property Address','wpestate').'</a>
    </p>
    '.esc_html__( 'Latitude:','wpestate').'  <input type="text" id="property_latitude" style="margin-right:20px;" size="40" name="property_latitude" value="' . $gmap_lat . '">
    '.esc_html__( 'Longitude:','wpestate').' <input type="text" id="property_longitude" style="margin-right:20px;" size="40" name="property_longitude" value="' . $gmap_long . '">
    <p>
    <p class="meta-options"> 
    <label for="google_camera_angle" >'.esc_html__( 'Google View Camera Angle','wpestate').'</label>
    <input type="text" id="google_camera_angle" style="margin-right:0px;" size="5" name="google_camera_angle" value="'.$google_camera_angle.'">
    
    </p>';
        
    $page_custom_zoom  = get_post_meta($mypost, 'page_custom_zoom', true);
    if ($page_custom_zoom==''){
        $page_custom_zoom=16;
    }
    
    print '
     <p class="meta-options">
       <label for="page_custom_zoom">'.esc_html__( 'Zoom Level for map (1-20)','wpestate').'</label><br />
       <select name="page_custom_zoom" id="page_custom_zoom">';
      
      for ($i=1;$i<21;$i++){
           print '<option value="'.$i.'"';
           if($page_custom_zoom==$i){
               print ' selected="selected" ';
           }
           print '>'.$i.'</option>';
       }
        
     print'
       </select>
    ';     
}
endif; // end   map_estate_box 






///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Agent box function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('agentestate_box') ):
function agentestate_box($post) {
    global $post;
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
   
    $mypost         =   $post->ID;
    $originalpost   =   $post;
    $agent_list     =   '';
    $picked_agent   =   wpsestate_get_author($mypost);
    $blogusers = get_users( 'blog_id=1&orderby=nicename' );
  
    foreach ( $blogusers as $user ) {     
        $the_id       =  $user->ID;
        $agent_list  .=  '<option value="' . $the_id . '"  ';
        if ($the_id == $picked_agent) {
           $agent_list.=' selected="selected" ';
        }
        $user_info = get_userdata($the_id);
        $username = $user_info->user_login;
        $first_name = $user_info->first_name;
        $last_name = $user_info->last_name;
        $agent_list.= '>' .  $user->user_login .' - '.$first_name.' '.$last_name.'</option>';
    }

  
    
    wp_reset_postdata();
    $post = $originalpost;
    $originalAuthor = get_post_meta($mypost, 'original_author',true );
    //print ($originalAuthor);
    print '
    <label for="property_zip">'.esc_html__( 'Property Owner: ','wpestate').'</label><br />
    <select id="property_agent" style="width: 237px;" name="property_agent">
        <option value="">none</option>
        <option value=""></option>
        '. $agent_list .'
    </select>';  
}
endif; // end   agentestate_box  





///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Features And Amenties function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('amenities_estate_box') ):
function amenities_estate_box($post) {
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    global $post;
    $mypost             =   $post->ID;
    $feature_list_array =   array();
    $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
    $feature_list_array =   explode( ',',$feature_list);
    $counter            =   0;
    
    print ' <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
    foreach($feature_list_array as $key => $value){
        $counter++;
        $post_var_name=  str_replace(' ','_', trim($value) );
      
        if( ($counter-1) % 3 == 0){
            print'<tr>';
        }
        $input_name =   wpestate_limit45(sanitize_title( $post_var_name ));
        $input_name =   sanitize_key($input_name);
      
        if (function_exists('icl_translate') ){
            $value     =   icl_translate('wpestate','wp_estate_property_custom_amm_'.$value, $value ) ;                                      
        }
        print '     
        <td width="33%" valign="top" align="left">
            <p class="meta-options"> 
            <input type="hidden"    name="'.$input_name.'" value="">
            <input type="checkbox"  name="'.$input_name.'" value="1" ';
        
        if (esc_html(get_post_meta($mypost, $input_name, true)) == 1) {
            print' checked="checked" ';
        }
        print' />
            <label for="'.$input_name.'">'.$value.'</label>
            </p>
        </td>';
        if($counter % 3 == 0){
            print'</tr>';
        }
    }
    
    print '</table>';
}
endif; // end   amenities_estate_box  





///////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Property custom fields
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_box') ): 
function estate_box($post) {
    global $post;
    wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    $mypost = $post->ID;
    
    print' 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr>
      <td width="33%" align="left" valign="top">
          <p class="meta-options">
          <label for="property_address">'.esc_html__( 'Address: ','wpestate').'</label><br />
          <textarea type="text" id="property_address"  size="40" name="property_address" rows="3" cols="42">' . esc_html(get_post_meta($mypost, 'property_address', true)) . '</textarea>
          </p>
      </td>
      
      <td width="33%" align="left" valign="top">
          <p class="meta-options">
          <label for="property_county">'.esc_html__( 'County: ','wpestate').'</label><br />
          <input type="text" id="property_county"  size="40" name="property_county" value="' . esc_html(get_post_meta($mypost, 'property_county', true)) . '">
          </p>
      </td>
      
      <td width="33%" align="left" valign="top">
           <p class="meta-options">
          <label for="property_state">'.esc_html__( 'State: ','wpestate').'</label><br />
          <input type="text" id="property_state" size="40" name="property_state" value="' . esc_html(get_post_meta($mypost, 'property_state', true)) . '">
          </p>
      </td>
    </tr>

    <tr>
      <td align="left" valign="top">   
          <p class="meta-options">
          <label for="property_zip">'.esc_html__( 'Zip: ','wpestate').'</label><br />
          <input type="text" id="property_zip" size="40" name="property_zip" value="' . esc_html(get_post_meta($mypost, 'property_zip', true)) . '">
          </p>
      </td>

      <td align="left" valign="top">
          <p class="meta-options">
          <label for="property_country">'.esc_html__( 'Country: ','wpestate').'</label><br />

          ';
      print wpestate_country_list(esc_html(get_post_meta($mypost, 'property_country', true)));
      print '     
          </p>
      </td>

    
    </tr>

    <tr>';
     
    $status_values          =   esc_html( get_option('wp_estate_status_list') );
    $status_values_array    =   explode(",",$status_values);
    $prop_stat              =   stripslashes( get_post_meta($mypost, 'property_status', true) );
    $property_status        =   '';

    foreach ($status_values_array as $key=>$value) {
        if (function_exists('icl_translate') ){
          $value     =   icl_translate('wpestate','wp_estate_property_status_'.$value, stripslashes($value) ) ;                                      
        }

        $value = stripslashes(trim($value));
        $property_status.='<option value="' . $value . '"';
        if ($value == $prop_stat) {
            $property_status.='selected="selected"';
        }
        $property_status.='>' . $value . '</option>';
    }


    print'
    <td align="left" valign="top">
        <p class="meta-options">
           <label for="property_status">'.esc_html__( 'Property Status:','wpestate').'</label><br />
           <select id="property_status" style="width: 237px;" name="property_status">
           <option value="normal">normal</option>
           ' . $property_status . '
           </select>
       </p>
    </td>';
 
      print '
      <td align="left" valign="top">  
           <p class="meta-options"> 
              <input type="hidden" name="prop_featured" value="0">
              <input type="checkbox"  id="prop_featured" name="prop_featured" value="1" ';
              if (intval(get_post_meta($mypost, 'prop_featured', true)) == 1) {
                  print'checked="checked"';
              }
              print' />
              <label for="prop_featured">'.esc_html__( 'Make it Featured Property','wpestate').'</label>
          </p>
     </td>

      <td align="left" valign="top">          
      </td>
    </tr>
    </table> 

    ';
}
endif; // end   estate_box 








///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Country list function
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_country_list') ): 
function wpestate_country_list($selected,$class='') {
    $countries = array(esc_html__('Afghanistan','wpestate'),esc_html__('Albania','wpestate'),esc_html__('Algeria','wpestate'),esc_html__('American Samoa','wpestate'),esc_html__('Andorra','wpestate'),esc_html__('Angola','wpestate'),esc_html__('Anguilla','wpestate'),esc_html__('Antarctica','wpestate'),esc_html__('Antigua and Barbuda','wpestate'),esc_html__('Argentina','wpestate'),esc_html__('Armenia','wpestate'),esc_html__('Aruba','wpestate'),esc_html__('Australia','wpestate'),esc_html__('Austria','wpestate'),esc_html__('Azerbaijan','wpestate'),esc_html__('Bahamas','wpestate'),esc_html__('Bahrain','wpestate'),esc_html__('Bangladesh','wpestate'),esc_html__('Barbados','wpestate'),esc_html__('Belarus','wpestate'),esc_html__('Belgium','wpestate'),esc_html__('Belize','wpestate'),esc_html__('Benin','wpestate'),esc_html__('Bermuda','wpestate'),esc_html__('Bhutan','wpestate'),esc_html__('Bolivia','wpestate'),esc_html__('Bosnia and Herzegowina','wpestate'),esc_html__('Botswana','wpestate'),esc_html__('Bouvet Island','wpestate'),esc_html__('Brazil','wpestate'),esc_html__('British Indian Ocean Territory','wpestate'),esc_html__('Brunei Darussalam','wpestate'),esc_html__('Bulgaria','wpestate'),esc_html__('Burkina Faso','wpestate'),esc_html__('Burundi','wpestate'),esc_html__('Cambodia','wpestate'),esc_html__('Cameroon','wpestate'),esc_html__('Canada','wpestate'),esc_html__('Cape Verde','wpestate'),esc_html__('Cayman Islands','wpestate'),esc_html__('Central African Republic','wpestate'),esc_html__('Chad','wpestate'),esc_html__('Chile','wpestate'),esc_html__('China','wpestate'),esc_html__('Christmas Island','wpestate'),esc_html__('Cocos (Keeling) Islands','wpestate'),esc_html__('Colombia','wpestate'),esc_html__('Comoros','wpestate'),esc_html__('Congo','wpestate'),esc_html__('Congo, the Democratic Republic of the','wpestate'),esc_html__('Cook Islands','wpestate'),esc_html__('Costa Rica','wpestate'),esc_html__('Cote dIvoire','wpestate'),esc_html__('Croatia (Hrvatska)','wpestate'),esc_html__('Cuba','wpestate'),esc_html__('Curacao','wpestate'),esc_html__('Cyprus','wpestate'),esc_html__('Czech Republic','wpestate'),esc_html__('Denmark','wpestate'),esc_html__('Djibouti','wpestate'),esc_html__('Dominica','wpestate'),esc_html__('Dominican Republic','wpestate'),esc_html__('East Timor','wpestate'),esc_html__('Ecuador','wpestate'),esc_html__('Egypt','wpestate'),esc_html__('El Salvador','wpestate'),esc_html__('Equatorial Guinea','wpestate'),esc_html__('Eritrea','wpestate'),esc_html__('Estonia','wpestate'),esc_html__('Ethiopia','wpestate'),esc_html__('Falkland Islands (Malvinas)','wpestate'),esc_html__('Faroe Islands','wpestate'),esc_html__('Fiji','wpestate'),esc_html__('Finland','wpestate'),esc_html__('France','wpestate'),esc_html__('France Metropolitan','wpestate'),esc_html__('French Guiana','wpestate'),esc_html__('French Polynesia','wpestate'),esc_html__('French Southern Territories','wpestate'),esc_html__('Gabon','wpestate'),esc_html__('Gambia','wpestate'),esc_html__('Georgia','wpestate'),esc_html__('Germany','wpestate'),esc_html__('Ghana','wpestate'),esc_html__('Gibraltar','wpestate'),esc_html__('Greece','wpestate'),esc_html__('Greenland','wpestate'),esc_html__('Grenada','wpestate'),esc_html__('Guadeloupe','wpestate'),esc_html__('Guam','wpestate'),esc_html__('Guatemala','wpestate'),esc_html__('Guinea','wpestate'),esc_html__('Guinea-Bissau','wpestate'),esc_html__('Guyana','wpestate'),esc_html__('Haiti','wpestate'),esc_html__('Heard and Mc Donald Islands','wpestate'),esc_html__('Holy See (Vatican City State)','wpestate'),esc_html__('Honduras','wpestate'),esc_html__('Hong Kong','wpestate'),esc_html__('Hungary','wpestate'),esc_html__('Iceland','wpestate'),esc_html__('India','wpestate'),esc_html__('Indonesia','wpestate'),esc_html__('Iran (Islamic Republic of)','wpestate'),esc_html__('Iraq','wpestate'),esc_html__('Ireland','wpestate'),esc_html__('Israel','wpestate'),esc_html__('Italy','wpestate'),esc_html__('Jamaica','wpestate'),esc_html__('Japan','wpestate'),esc_html__('Jordan','wpestate'),esc_html__('Kazakhstan','wpestate'),esc_html__('Kenya','wpestate'),esc_html__('Kiribati','wpestate'),esc_html__('Korea, Democratic People Republic of','wpestate'),esc_html__('Korea, Republic of','wpestate'),esc_html__('Kuwait','wpestate'),esc_html__('Kyrgyzstan','wpestate'),esc_html__('Lao, People Democratic Republic','wpestate'),esc_html__('Latvia','wpestate'),esc_html__('Lebanon','wpestate'),esc_html__('Lesotho','wpestate'),esc_html__('Liberia','wpestate'),esc_html__('Libyan Arab Jamahiriya','wpestate'),esc_html__('Liechtenstein','wpestate'),esc_html__('Lithuania','wpestate'),esc_html__('Luxembourg','wpestate'),esc_html__('Macau','wpestate'),esc_html__('Macedonia, The Former Yugoslav Republic of','wpestate'),esc_html__('Madagascar','wpestate'),esc_html__('Malawi','wpestate'),esc_html__('Malaysia','wpestate'),esc_html__('Maldives','wpestate'),esc_html__('Mali','wpestate'),esc_html__('Malta','wpestate'),esc_html__('Marshall Islands','wpestate'),esc_html__('Martinique','wpestate'),esc_html__('Mauritania','wpestate'),esc_html__('Mauritius','wpestate'),esc_html__('Mayotte','wpestate'),esc_html__('Mexico','wpestate'),esc_html__('Micronesia, Federated States of','wpestate'),esc_html__('Moldova, Republic of','wpestate'),esc_html__('Monaco','wpestate'),esc_html__('Mongolia','wpestate'),esc_html__('Montserrat','wpestate'),esc_html__('Morocco','wpestate'),esc_html__('Mozambique','wpestate'),esc_html__('Montenegro','wpestate'),esc_html__('Myanmar','wpestate'),esc_html__('Namibia','wpestate'),esc_html__('Nauru','wpestate'),esc_html__('Nepal','wpestate'),esc_html__('Netherlands','wpestate'),esc_html__('Netherlands Antilles','wpestate'),esc_html__('New Caledonia','wpestate'),esc_html__('New Zealand','wpestate'),esc_html__('Nicaragua','wpestate'),esc_html__('Niger','wpestate'),esc_html__('Nigeria','wpestate'),esc_html__('Niue','wpestate'),esc_html__('Norfolk Island','wpestate'),esc_html__('Northern Mariana Islands','wpestate'),esc_html__('Norway','wpestate'),esc_html__('Oman','wpestate'),esc_html__('Pakistan','wpestate'),esc_html__('Palau','wpestate'),esc_html__('Panama','wpestate'),esc_html__('Papua New Guinea','wpestate'),esc_html__('Paraguay','wpestate'),esc_html__('Peru','wpestate'),esc_html__('Philippines','wpestate'),esc_html__('Pitcairn','wpestate'),esc_html__('Poland','wpestate'),esc_html__('Portugal','wpestate'),esc_html__('Puerto Rico','wpestate'),esc_html__('Qatar','wpestate'),esc_html__('Reunion','wpestate'),esc_html__('Romania','wpestate'),esc_html__('Russian Federation','wpestate'),esc_html__('Rwanda','wpestate'),esc_html__('Saint Kitts and Nevis','wpestate'),esc_html__('Saint Lucia','wpestate'),esc_html__('Saint Vincent and the Grenadines','wpestate'),esc_html__('Samoa','wpestate'),esc_html__('San Marino','wpestate'),esc_html__('Sao Tome and Principe','wpestate'),esc_html__('Saudi Arabia','wpestate'),esc_html__('Serbia','wpestate'),esc_html__('Senegal','wpestate'),esc_html__('Seychelles','wpestate'),esc_html__('Sierra Leone','wpestate'),esc_html__('Singapore','wpestate'),esc_html__('Slovakia (Slovak Republic)','wpestate'),esc_html__('Slovenia','wpestate'),esc_html__('Solomon Islands','wpestate'),esc_html__('Somalia','wpestate'),esc_html__('South Africa','wpestate'),esc_html__('South Georgia and the South Sandwich Islands','wpestate'),esc_html__('Spain','wpestate'),esc_html__('Sri Lanka','wpestate'),esc_html__('St. Helena','wpestate'),esc_html__('St. Pierre and Miquelon','wpestate'),esc_html__('Sudan','wpestate'),esc_html__('Suriname','wpestate'),esc_html__('Svalbard and Jan Mayen Islands','wpestate'),esc_html__('Swaziland','wpestate'),esc_html__('Sweden','wpestate'),esc_html__('Switzerland','wpestate'),esc_html__('Syrian Arab Republic','wpestate'),esc_html__('Taiwan, Province of China','wpestate'),esc_html__('Tajikistan','wpestate'),esc_html__('Tanzania, United Republic of','wpestate'),esc_html__('Thailand','wpestate'),esc_html__('Togo','wpestate'),esc_html__('Tokelau','wpestate'),esc_html__('Tonga','wpestate'),esc_html__('Trinidad and Tobago','wpestate'),esc_html__('Tunisia','wpestate'),esc_html__('Turkey','wpestate'),esc_html__('Turkmenistan','wpestate'),esc_html__('Turks and Caicos Islands','wpestate'),esc_html__('Tuvalu','wpestate'),esc_html__('Uganda','wpestate'),esc_html__('Ukraine','wpestate'),esc_html__('United Arab Emirates','wpestate'),esc_html__('United Kingdom','wpestate'),esc_html__('United States','wpestate'),esc_html__('United States Minor Outlying Islands','wpestate'),esc_html__('Uruguay','wpestate'),esc_html__('Uzbekistan','wpestate'),esc_html__('Vanuatu','wpestate'),esc_html__('Venezuela','wpestate'),esc_html__('Vietnam','wpestate'),esc_html__('Virgin Islands (British)','wpestate'),esc_html__('Virgin Islands (U.S.)','wpestate'),esc_html__('Wallis and Futuna Islands','wpestate'),esc_html__('Western Sahara','wpestate'),esc_html__('Yemen','wpestate'),esc_html__('Yugoslavia','wpestate'),esc_html__('Zambia','wpestate'),esc_html__('Zimbabwe','wpestate'));

    if ($selected == '') {
        $selected = get_option('wp_estate_general_country');
    }
    
    $country_select = '<select id="property_country"  name="property_country" class="'.$class.'">';

   
    foreach ($countries as $country) {
        $country_select.='<option value="' . $country . '"';
        if (strtolower($selected) == strtolower ($country) ) {
            $country_select.='selected="selected"';
        }
        $country_select.='>' . $country . '</option>';
    }

    $country_select.='</select>';
    return $country_select;
}
endif; // end   wpestate_country_list 



if( !function_exists('wpestate_agent_list') ):
    function wpestate_agent_list($mypost) {
        return $agent_list;
    }
endif; // end   wpestate_agent_list



///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Manage property lists
///////////////////////////////////////////////////////////////////////////////////////////////////////////
add_filter( 'manage_edit-estate_property_columns', 'wpestate_my_columns' );

if( !function_exists('wpestate_my_columns') ):
    function wpestate_my_columns( $columns ) {
        $slice=array_slice($columns,2,2);
        unset( $columns['comments'] );
        unset( $slice['comments'] );
        $splice=array_splice($columns, 2);   
        $columns['estate_action']   = esc_html__( 'Action','wpestate');
        $columns['estate_category'] = esc_html__( 'Category','wpestate');
        $columns['estate_autor']    = esc_html__( 'User','wpestate');
        $columns['estate_status']   = esc_html__( 'Status','wpestate');
        $columns['estate_price']    = esc_html__( 'Price per night','wpestate');
        return  array_merge($columns,array_reverse($slice));
    }
endif; // end   wpestate_my_columns  


add_action( 'manage_posts_custom_column', 'wpestate_populate_columns' );
if( !function_exists('wpestate_populate_columns') ):
    function wpestate_populate_columns( $column ) {

         if ( 'estate_status' == $column ) {
            $estate_status = get_post_status(get_the_ID()); 
            if($estate_status=='publish'){
                echo esc_html__( 'published','wpestate');
            }else{
                echo $estate_status;
            }

            $pay_status    = get_post_meta(get_the_ID(), 'pay_status', true);
            if($pay_status!=''){
                echo " | ".$pay_status;
            }

        } 

        if ( 'estate_autor' == $column ) {
            $user_id=wpsestate_get_author(get_the_ID());
            $estate_autor = get_the_author_meta('display_name');; 
            echo '<a href="'.get_edit_user_link($user_id).'" >'. $estate_autor.'</a>';
        } 

        if ( 'estate_action' == $column ) {
            $estate_action = get_the_term_list( get_the_ID(), 'property_action_category', '', ', ', '');
            echo $estate_action;
        }
        elseif ( 'estate_category' == $column ) {
            $estate_category = get_the_term_list( get_the_ID(), 'property_category', '', ', ', '');
            echo $estate_category ;
        }
        
        if ( 'estate_price' == $column ) {
            $currency                   =   esc_html( get_option('wp_estate_currency_symbol', '') );
            $where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
            wpestate_show_price(get_the_ID(),$currency,$where_currency,0);
        }
    }
endif; // end   wpestate_populate_columns 






add_filter( 'manage_edit-estate_property_sortable_columns', 'wpestate_sort_me' );
if( !function_exists('wpestate_sort_me') ):
    function wpestate_sort_me( $columns ) {
      
        $columns['estate_autor'] = 'estate_autor';
        $columns['estate_price'] = 'estate_price';
        return $columns;
    }
endif; // end   wpestate_sort_me 


add_filter( 'request', 'bs_event_date_column_orderby' );
function bs_event_date_column_orderby( $vars ) {
  
    if ( isset( $vars['orderby'] ) && 'estate_price' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'property_price',
            'orderby' => 'meta_value_num'
        ) );
    }
    
    
      if ( isset( $vars['orderby'] ) && 'estate_autor' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'orderby' => 'author'
        ) );
    }
    
   

    return $vars;
}


add_action( 'property_city_edit_form_fields',   'wpestate_property_city_callback_function', 10, 2);
add_action( 'property_city_add_form_fields',    'wpestate_property_city_callback_add_function', 10, 2 );  
add_action( 'created_property_city',            'wpestate_property_city_save_extra_fields_callback', 10, 2);
add_action( 'edited_property_city',             'wpestate_property_city_save_extra_fields_callback', 10, 2);

if( !function_exists('wpestate_property_city_callback_function') ):
    function wpestate_property_city_callback_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_tagline           =   stripslashes($category_tagline);
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';
        }

        print'
        <table class="form-table">
        <tbody>    
            <tr class="form-field">
                <th scope="row" valign="top"><label for="term_meta[pagetax]">'.esc_html__( 'Page id for this term','wpestate').'</label></th>
                <td> 
                    <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
                    <p class="description">'.esc_html__( 'Page id for this term','wpestate').'</p>
                </td>

                <tr valign="top">
                    <th scope="row"><label for="category_featured_image">'.esc_html__( 'Featured Image','wpestate').'</label></th>
                    <td>
                        <input id="category_featured_image" type="text" class="postform" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                        <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wpestate').'" />
                        <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                    </td>
                </tr> 

                <tr valign="top">
                    <th scope="row"><label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wpestate').'</label></th>
                    <td>
                        <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
                    </td>
                </tr> 



                <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_city" />


            </tr>
        </tbody>
        </table>';
    }
endif;



if( !function_exists('wpestate_property_city_callback_add_function') ):
    function wpestate_property_city_callback_add_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';

        }

        print'
        <div class="form-field">
        <label for="term_meta[pagetax]">'. esc_html__( 'Page id for this term','wpestate').'</label>
            <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
        </div>

        <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Featured Image','wpestate').'</label>
            <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
            <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wpestate').'" />
           <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />

        </div>     

        <div class="form-field">
        <label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wpestate').'</label>
            <input id="category_tagline" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
        </div> 
        <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_city" />
        ';
    }
endif;

if( !function_exists('wpestate_property_city_save_extra_fields_callback') ):
    function wpestate_property_city_save_extra_fields_callback($term_id ){
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
             update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif;
///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Tie area with city
///////////////////////////////////////////////////////////////////////////////////////////////////////////
add_action( 'property_area_edit_form_fields',   'wpestate_property_area_callback_function', 10, 2);
add_action( 'property_area_add_form_fields',    'wpestate_property_area_callback_add_function', 10, 2 );  
add_action( 'created_property_area',            'wpestate_property_area_save_extra_fields_callback', 10, 2);
add_action( 'edited_property_area',             'wpestate_property_area_save_extra_fields_callback', 10, 2);
add_filter('manage_edit-property_area_columns', 'ST4_columns_head');  
add_filter('manage_property_area_custom_column','ST4_columns_content_taxonomy', 10, 3); 


if( !function_exists('ST4_columns_head') ):
    function ST4_columns_head($new_columns) {   
        $new_columns = array(
            'cb'            => '<input type="checkbox" />',
            'name'          => esc_html__( 'Name','wpestate'),
            'city'          => esc_html__( 'City','wpestate'),
            'header_icon'   => '',
            'slug'          => esc_html__( 'Slug','wpestate'),
            'posts'         => esc_html__( 'Posts','wpestate')
            );
        return $new_columns;
    } 
endif; // end   ST4_columns_head  


if( !function_exists('ST4_columns_content_taxonomy') ):
    function ST4_columns_content_taxonomy($out, $column_name, $term_id) {  
        if ($column_name == 'city') {    
            $term_meta= get_option( "taxonomy_$term_id");
            print $term_meta['cityparent'] ;
        }  
    }  
endif; // end   ST4_columns_content_taxonomy  




if( !function_exists('wpestate_property_area_callback_add_function') ):
    function wpestate_property_area_callback_add_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $cityparent                 =   $term_meta['cityparent'] ? $term_meta['cityparent'] : ''; 
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $cityparent                 =   wpestate_get_all_cities();
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';
        }

        print'
            <div class="form-field">
            <label for="term_meta[cityparent]">'. esc_html__( 'Which city has this area','wpestate').'</label>
                <select name="term_meta[cityparent]" class="postform">  
                    '.$cityparent.'
                </select>
            </div>
            ';

         print'
            <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Page id for this term','wpestate').'</label>
                <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
            </div>

            <div class="form-field">
            <label for="term_meta[pagetax]">'. esc_html__( 'Featured Image','wpestate').'</label>
                <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wpestate').'" />
                <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />

            </div> 


            <div class="form-field">
            <label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wpestate').'</label>
                <input id="category_featured_image" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
            </div>  
            <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_area" />
            ';
    }
endif; // end     




if( !function_exists('wpestate_property_area_callback_function') ):
    function wpestate_property_area_callback_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $cityparent                 =   $term_meta['cityparent'] ? $term_meta['cityparent'] : ''; 
            $pagetax                    =   $term_meta['pagetax'] ? $term_meta['pagetax'] : '';
            $category_featured_image    =   $term_meta['category_featured_image'] ? $term_meta['category_featured_image'] : '';
            $category_tagline           =   $term_meta['category_tagline'] ? $term_meta['category_tagline'] : '';
            $category_tagline           =   stripslashes($category_tagline);
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';

            $cityparent =   wpestate_get_all_cities($cityparent);
        }else{
            $cityparent                 =   wpestate_get_all_cities();
            $pagetax                    =   '';
            $category_featured_image    =   '';
            $category_tagline           =   '';
            $category_attach_id         =   '';

        }

        print'
            <table class="form-table">
            <tbody>
                    <tr class="form-field">
                            <th scope="row" valign="top"><label for="term_meta[cityparent]">'. esc_html__( 'Which city has this area','wpestate').'</label></th>
                            <td> 
                                <select name="term_meta[cityparent]" class="postform">  
                                 '.$cityparent.'
                                    </select>
                                <p class="description">'.esc_html__( 'City that has this area','wpestate').'</p>
                            </td>
                    </tr>

                   <tr class="form-field">
                            <th scope="row" valign="top"><label for="term_meta[pagetax]">'.esc_html__( 'Page id for this term','wpestate').'</label></th>
                            <td> 
                                <input type="text" name="term_meta[pagetax]" class="postform" value="'.$pagetax.'">  
                                <p class="description">'.esc_html__( 'Page id for this term','wpestate').'</p>
                            </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="logo_image">'.esc_html__( 'Featured Image','wpestate').'</label></th>
                        <td>
                            <input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="'.$category_featured_image.'" />
                            <input id="category_featured_image_button" type="button"  class="upload_button button category_featured_image_button" value="'.esc_html__( 'Upload Image','wpestate').'" />
                            <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                        </td>
                    </tr> 

                    <tr valign="top">
                        <th scope="row"><label for="term_meta[category_tagline]">'. esc_html__( 'Category Tagline','wpestate').'</label></th>
                        <td>
                          <input id="category_featured_image" type="text" size="36" name="term_meta[category_tagline]" value="'.$category_tagline.'" />
                        </td>
                    </tr> 


                    <input id="category_tax" type="hidden" size="36" name="term_meta[category_tax]" value="property_area" />




              </tbody>
             </table>';
    }
endif; // end     



if( !function_exists('wpestate_get_all_cities') ): 
    function wpestate_get_all_cities($selected=''){
        $taxonomy       =   'property_city';
        $args = array(
            'hide_empty'    => false
        );
        $tax_terms      =   get_terms($taxonomy,$args);
        $select_city    =   '';

        foreach ($tax_terms as $tax_term) {             
            $select_city.= '<option value="' . $tax_term->name.'" ';
            if($tax_term->name == $selected){
                $select_city.= ' selected="selected" ';
            }
            $select_city.= ' >' . $tax_term->name . '</option>'; 
        }
        return $select_city;
    }
endif; // end   wpestate_get_all_cities 




if( !function_exists('wpestate_property_area_save_extra_fields_callback') ):
    function wpestate_property_area_save_extra_fields_callback($term_id ){
          if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
            update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif; // end     


add_action( 'init', 'wpestate_my_custom_post_status' );
if( !function_exists('wpestate_my_custom_post_status') ):
    function wpestate_my_custom_post_status(){
        register_post_status( 'expired', array(
                'label'                     => esc_html__(  'expired', 'wpestate' ),
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Membership Expired <span class="count">(%s)</span>', 'Membership Expired <span class="count">(%s)</span>','wpestate' ),
        ) );
        
        register_post_status( 'disabled', array(
                    'label'                     => esc_html__(  'disabled', 'wpestate' ),
                    'public'                    => false,
                    'exclude_from_search'       => false,
                    'show_in_admin_all_list'    => true,
                    'show_in_admin_status_list' => true,
                    'label_count'               => _n_noop( 'Disabled by user <span class="count">(%s)</span>', 'Disabled by user <span class="count">(%s)</span>','wpestate' ),
            ) );
        
    }
endif; // end   wpestate_my_custom_post_status  







///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Tie area with city
// property_category
//property_action_category
///////////////////////////////////////////////////////////////////////////////////////////////////////////
add_action('property_category_edit_form_fields',   'wpestate_property_category_callback_function', 10, 2);
add_action('property_category_add_form_fields',    'wpestate_property_category_callback_add_function', 10, 2 );  
add_action('created_property_category',            'wpestate_property_category_save_extra_fields_callback', 10, 2);
add_action('edited_property_category',             'wpestate_property_category_save_extra_fields_callback', 10, 2);



if( !function_exists('wpestate_property_category_callback_function') ):
    function wpestate_property_category_callback_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $category_icon_image        =   $term_meta['category_icon_image'] ? $term_meta['category_icon_image'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $category_icon_image    =   '';
            $category_attach_id     =   '';
        }

       /* print'
            <table class="form-table">
            <tbody>
                    

                    <tr valign="top">
                        <th scope="row"><label for="logo_image">'.esc_html__( 'Icon Image','wpestate').'</label></th>
                        <td>
                            <input id="category_icon_image" type="text" size="36" name="term_meta[category_icon_image]" value="'.$category_icon_image.'" />
                            <input id="category_icon_image_button" type="button"  class="upload_button button category_icon_image_button" value="'.esc_html__( 'Upload Icon','wpestate').'" />
                            <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                        </td>
                    </tr> 

                    
              </tbody>
             </table>';*/
    }
endif; // end     


if( !function_exists('wpestate_property_category_callback_add_function') ):
    function wpestate_property_category_callback_add_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $category_icon_image        =   $term_meta['category_icon_image'] ? $term_meta['category_icon_image'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $category_icon_image    =   '';
            $category_attach_id     =   '';
        }

      /* print'
        <table class="form-table">
        <tbody>


                <tr valign="top">
                    <th scope="row"><label for="logo_image">'.esc_html__( 'Icon Image','wpestate').'</label></th>
                    <td>
                        <input id="category_icon_image" type="text" size="36" name="term_meta[category_icon_image]" value="'.$category_icon_image.'" />
                        <input id="category_icon_image_button" type="button"  class="upload_button button category_icon_image_button" value="'.esc_html__( 'Upload Icon','wpestate').'" />
                        <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                    </td>
                </tr> 


          </tbody>
         </table>';
       *
       */
    }
endif; // end     


if( !function_exists('wpestate_property_category_save_extra_fields_callback') ):
    function wpestate_property_category_save_extra_fields_callback($term_id ){
          if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
            update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif; // end     


add_action( 'property_action_category_edit_form_fields',   'wpestate_property_action_category_callback_function', 10, 2);
add_action( 'property_action_category_add_form_fields',    'wpestate_property_action_category_callback_add_function', 10, 2 );  
add_action( 'created_property_action_category',            'wpestate_property_action_category_save_extra_fields_callback', 10, 2);
add_action( 'edited_property_action_category',             'wpestate_property_action_category_save_extra_fields_callback', 10, 2);



if( !function_exists('wpestate_property_action_category_callback_function') ):
    function wpestate_property_action_category_callback_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $category_icon_image        =   $term_meta['category_icon_image'] ? $term_meta['category_icon_image'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $category_icon_image    =   '';
            $category_attach_id     =   '';
        }

      /*  print'
            <table class="form-table">
            <tbody>
                    

                    <tr valign="top">
                        <th scope="row"><label for="logo_image">'.esc_html__( 'Icon Image','wpestate').'</label></th>
                        <td>
                            <input id="category_icon_image" type="text" size="36" name="term_meta[category_icon_image]" value="'.$category_icon_image.'" />
                            <input id="category_icon_image_button" type="button"  class="upload_button button category_icon_image_button" value="'.esc_html__( 'Upload Icon','wpestate').'" />
                            <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                        </td>
                    </tr> 

                    
              </tbody>
             </table>';*/
    }
endif; // end     


if( !function_exists('wpestate_property_action_category_callback_add_function') ):
    function wpestate_property_action_category_callback_add_function($tag){
        if(is_object ($tag)){
            $t_id                       =   $tag->term_id;
            $term_meta                  =   get_option( "taxonomy_$t_id");
            $category_icon_image        =   $term_meta['category_icon_image'] ? $term_meta['category_icon_image'] : '';
            $category_attach_id         =   $term_meta['category_attach_id'] ? $term_meta['category_attach_id'] : '';
        }else{
            $category_icon_image    =   '';
            $category_attach_id     =   '';
        }

      /* print'
        <table class="form-table">
        <tbody>


                <tr valign="top">
                    <th scope="row"><label for="logo_image">'.esc_html__( 'Icon Image','wpestate').'</label></th>
                    <td>
                        <input id="category_icon_image" type="text" size="36" name="term_meta[category_icon_image]" value="'.$category_icon_image.'" />
                        <input id="category_icon_image_button" type="button"  class="upload_button button category_icon_image_button" value="'.esc_html__( 'Upload Icon','wpestate').'" />
                        <input id="category_attach_id" type="hidden" size="36" name="term_meta[category_attach_id]" value="'.$category_attach_id.'" />
                    </td>
                </tr> 


          </tbody>
         </table>';*/
    }
endif; // end     


if( !function_exists('wpestate_property_action_category_save_extra_fields_callback') ):
    function wpestate_property_action_category_save_extra_fields_callback($term_id ){
          if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id");
            $cat_keys = array_keys($_POST['term_meta']);
            $allowed_html   =   array();
                foreach ($cat_keys as $key){
                    $key=sanitize_key($key);
                    if (isset($_POST['term_meta'][$key])){
                        $term_meta[$key] =  wp_kses( $_POST['term_meta'][$key],$allowed_html);
                    }
                }
            //save the option array
            update_option( "taxonomy_$t_id", $term_meta );
        }
    }
endif; // end     





?>