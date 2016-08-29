<?php

/////////////////////////////////////////////////////////////////////////////////////
// front end submission - add new property
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_add_new_property') ):

function estate_add_new_property(){
   
    global $_POST;    
    global $userID;
    global $user_pack;
    global $status_values;
    global $status_values_array;
    global $feature_list_array;
    global $feature_list;
    global $custom_fields;
    global $current_user ;
    $allowed_html   =   array();           
    $paid_submission_status    = esc_html ( get_option('wp_estate_paid_submission','') );
     
    if ( $paid_submission_status!='membership' || ( $paid_submission_status== 'membership' || wpestate_get_current_user_listings($userID) > 0)  ){ // if user can submit
        
        if ( !isset($_POST['new_estate']) || !wp_verify_nonce($_POST['new_estate'],'submit_new_estate') ){
           exit('Sorry, your are not submiting from site'); 
        }
   
        if( !isset($_POST['prop_category']) ) {
            $prop_category=0;           
        }else{
            $prop_category  =   intval($_POST['prop_category']);
        }
  
        if( !isset($_POST['prop_action_category']) ) {
            $prop_action_category=0;           
        }else{
            $prop_action_category  =   wp_kses($_POST['prop_action_category'],$allowed_html);
        }
        
        if( !isset($_POST['property_city']) ) {
            $property_city='';           
        }else{
            $property_city  =   wp_kses($_POST['property_city'],$allowed_html);
        }
        
        if( !isset($_POST['property_area']) ) {
            $property_area='';           
        }else{
            $property_area  =   wp_kses($_POST['property_area'],$allowed_html);
        }
       
        $show_err                       =   '';
        $post_id                        =   '';
        $submit_title                   =   wp_kses( $_POST['title'] ,$allowed_html); 
        $submit_description             =   wp_kses( $_POST['description'],$allowed_html);     
        $property_address               =   wp_kses( $_POST['property_address'],$allowed_html);
        $property_county                =   wp_kses( $_POST['property_county'],$allowed_html);
        $property_state                 =   wp_kses( $_POST['property_state'],$allowed_html);
        $property_zip                   =   wp_kses( $_POST['property_zip'],$allowed_html);
        $country_selected               =   wp_kses( $_POST['property_country'],$allowed_html);     
        $prop_stat                      =   wp_kses( $_POST['property_status'],$allowed_html);
        $property_status                =   '';
        
        foreach ($status_values_array as $key=>$value) {
            $value = trim($value);
            $property_status.='<option value="' . $value . '"';
            if ($value == $prop_stat) {
                $property_status.='selected="selected"';
            }
            $property_status.='>' . $value . '</option>';
        }

        $property_price                 =   wp_kses( $_POST['property_price'],$allowed_html);
        $property_label                 =   wp_kses( $_POST['property_label'],$allowed_html);    
        $property_size                  =   wp_kses( $_POST['property_size'],$allowed_html);  
        $property_lot_size              =   wp_kses( $_POST['property_lot_size'],$allowed_html); 
        $property_year                  =   wp_kses( $_POST['property_year'],$allowed_html); 
        $property_rooms                 =   wp_kses( $_POST['property_rooms'],$allowed_html); 
        $property_bedrooms              =   wp_kses( $_POST['property_bedrooms'],$allowed_html); 
        $property_bathrooms             =   wp_kses( $_POST['property_bathrooms'],$allowed_html); 
        $option_video                   =   '';
        $video_values                   =   array('vimeo', 'youtube');
        $video_type                     =   wp_kses( $_POST['embed_video_type'],$allowed_html); 
        $google_camera_angle            =   wp_kses( $_POST['google_camera_angle'],$allowed_html); 
        $has_errors                     =   false;
        $errors                         =   array();
        
        $moving_array=array();
        foreach($feature_list_array as $key => $value){
          $post_var_name    =   str_replace(' ','_', trim($value) );
          $feature_value    =   wp_kses( $_POST[$post_var_name],$allowed_html);
          
          if($feature_value==1){
               $moving_array[]=$post_var_name;
          }        
       }
        
      
        foreach ($video_values as $value) {
            $option_video.='<option value="' . $value . '"';
            if ($value == $video_type) {
                $option_video.='selected="selected"';
            }
            $option_video.='>' . $value . '</option>';
        }
        
        $option_slider='';
        $slider_values = array('full top slider', 'small slider'); 
        $slider_type = wp_kses( $_POST['prop_slider_type'],$allowed_html );

        foreach ($slider_values as $value) {
            $option_slider.='<option value="' . $value . '"';
            if ($value == $slider_type) {
                $option_slider.='selected="selected"';
            }
            $option_slider.='>' . $value . '</option>';
        }

        $embed_video_id                 =   wp_kses( $_POST['embed_video_id'],$allowed_html); 
        $property_latitude              =   floatval( $_POST['property_latitude']); 
        $property_longitude             =   floatval( $_POST['property_longitude']); 
      

        if($google_view==1){
            $google_view_check=' checked="checked" ';
        }else{
             $google_view_check=' ';
        }

        if(isset($_POST['prop_featured'])){
            $prop_featured                  =    wp_kses( $_POST['prop_featured'],$allowed_html); ;
            if($prop_featured==1){
                 $prop_featured_check    =' checked="checked" ';
            }else{
                 $prop_featured_check   =' ';
            }  
        }

       
        
        
        $google_camera_angle            =   intval( $_POST['google_camera_angle']); 
        $prop_category                  =   get_term( $prop_category, 'property_category');
        $prop_category_selected         =   $prop_category->term_id;
        $prop_action_category           =   get_term( $prop_action_category, 'property_action_category');     
        $prop_action_category_selected  =   $prop_action_category->term_id;
        
        // save custom fields
     
        $i=0;
        while($i< count($custom_fields) ){
           $name =   $custom_fields[$i][0];
           $type =   $custom_fields[$i][1];
           $slug =   str_replace(' ','_',$name);
           $custom_fields_array[$slug]= wp_kses( $_POST[$slug],$allowed_html);
           $i++;
        }
            
            
        if($submit_title==''){
            $has_errors=true;
            $errors[]=esc_html__( 'Please submit a title for your property','wpestate');
        }
        
        if($submit_description==''){
            $has_errors=true;
            $errors[]=esc_html__( '*Please submit a description for your property','wpestate');
        }
        
        if (  $_FILES['upload_attachment']['name'][0]=='') {
            $has_errors=true;
            $errors[]=esc_html__( '*Please submit an image for your property','wpestate');
        }
        
        if($property_address==''){
            $has_errors=true;
            $errors[]=esc_html__( '*Please submit an address for your property','wpestate');
        }
         
        if($property_price==''){
            $has_errors=true;
            $errors[]=esc_html__( '*Please submit the price','wpestate');
        }
        
        if($has_errors){
            foreach($errors as $key=>$value){
                $show_err.=$value.'</br>';
            }
            
        }else{
            $paid_submission_status = esc_html ( get_option('wp_estate_paid_submission','') );
            $new_status             = 'pending';
            
            $admin_submission_status= esc_html ( get_option('wp_estate_admin_submission','') );
            if($admin_submission_status=='no' && $paid_submission_status!='per listing'){
               $new_status='publish';  
            }
            
            $post = array(
                'post_title'	=> $submit_title,
                'post_content'	=> $submit_description,
                'post_status'	=> $new_status, 
                'post_type'     => 'estate_property' ,
                'post_author'   => $current_user->ID 
            );
            $post_id =  wp_insert_post($post );  
          
            if( $paid_submission_status == 'membership'){ // update pack status
                wpestate_update_listing_no($current_user->ID);                
                if($prop_featured==1){
                    wpestate_update_featured_listing_no($current_user->ID); 
                }
               
            }
           
        }
        
      

        
        
        ////////////////////////////////////////////////////////////////////////
        // Upload images
        ////////////////////////////////////////////////////////////////////////
        if($post_id) {
               if ( $_FILES ) {
                    $counter=0;
                    $files = array_reverse( $_FILES['upload_attachment']);
                    foreach ($files['name'] as $key => $value) {
                        if ($files['name'][$key]) {
                            $file = array(
                            'name' => $files['name'][$key],
                            'type' => $files['type'][$key],
                            'tmp_name' => $files['tmp_name'][$key],
                            'error' => $files['error'][$key],
                            'size' => $files['size'][$key]
                            );

                            $_FILES = array("upload_featured_attachment" => $file) ;
                         
                            foreach ($_FILES as $file => $array) {
                                $newupload = wpestate_insert_attachment($file,$post_id);
                            }
                             set_post_thumbnail( $post_id, $newupload );
                        }
                    }
                }// end id Files 
 
            if( isset($prop_category->name) ){
                 wp_set_object_terms($post_id,$prop_category->name,'property_category'); 
            }  
            if ( isset ($prop_action_category->name) ){
                 wp_set_object_terms($post_id,$prop_action_category->name,'property_action_category'); 
            }  
            if( isset($property_city) ){
                   wp_set_object_terms($post_id,$property_city,'property_city'); 
            }  
            if( isset($property_area) && $property_area!='none' ){
                $property_area= wpestate_double_tax_cover($property_area,$property_city,$post_id);
               // wp_set_object_terms($post_id,$property_area,'property_area'); 
            }  
  
   
      
            update_post_meta($post_id, 'property_address', $property_address);
            update_post_meta($post_id, 'property_area', $property_area);
            update_post_meta($post_id, 'property_county', $property_county);
            update_post_meta($post_id, 'property_state', $property_state);
            update_post_meta($post_id, 'property_zip', $property_zip);
            update_post_meta($post_id, 'property_country', $country_selected);
            update_post_meta($post_id, 'property_size', $property_size);
            update_post_meta($post_id, 'property_lot_size', $property_lot_size);  
            update_post_meta($post_id, 'property_rooms', $property_rooms);  
            update_post_meta($post_id, 'property_bedrooms', $property_bedrooms);
            update_post_meta($post_id, 'property_bathrooms', $property_bathrooms);
            update_post_meta($post_id, 'property_year', $property_year);
            update_post_meta($post_id, 'property_status', $prop_stat);
            update_post_meta($post_id, 'property_price', $property_price);
            update_post_meta($post_id, 'property_label', $property_label);
            update_post_meta($post_id, 'embed_video_type', $video_type);
            update_post_meta($post_id, 'prop_slider_type', $slider_type);
            update_post_meta($post_id, 'embed_video_id',  $embed_video_id );
            update_post_meta($post_id, 'property_latitude', $property_latitude);
            update_post_meta($post_id, 'property_longitude', $property_longitude);
            update_post_meta($post_id, 'property_prop_featured', $prop_featured);
          
            update_post_meta($post_id, 'google_camera_angle', $google_camera_angle);
            update_post_meta($post_id, 'pay_status', 'not paid');
              
           
            // save custom fields
            $custom_fields = get_option( 'wp_estate_custom_fields', true);  
     
            $i=0;
            while($i< count($custom_fields) ){
               $name =   $custom_fields[$i][0];
               $type =   $custom_fields[$i][2];
              // $slug =   str_replace(' ','_',$name);
               $slug =   wpestate_limit45(sanitize_title( $name ));
               $slug =   sanitize_key($name);
             
               
               if($type=='numeric'){
                   $value_custom    =   intval(wp_kses( $_POST[$slug] ,$allowed_html) );
                   update_post_meta($post_id, $slug, $value_custom);
               }else{
                   $value_custom    =   esc_html(wp_kses( $_POST[$slug],$allowed_html ) );
                   update_post_meta($post_id, $slug, $value_custom);
               }
               $custom_fields_array[$slug]= wp_kses( $_POST[$slug],$allowed_html); ;
               $i++;
            }
            
            
            
            
            foreach($feature_list_array as $key => $value){
                $post_var_name  =   str_replace(' ','_', trim($value) );
                $feature_value  =   wp_kses( $_POST[$post_var_name],$allowed_html );
                update_post_meta($post_id, $post_var_name, $feature_value);
                $moving_array[] =   $post_var_name;
            }
   
            // get user dashboard link
            $redirect = wpestate_get_dashboard_link();
  
            $arguments=array(
                'new_listing_url'   => get_permalink($post_id),
                'new_listing_title' => $submit_title
            );
            wpestate_select_email_type(get_option('admin_email'),'new_listing_submission',$arguments);
            
            wp_reset_query();
            wp_redirect( $redirect);
            exit;
        }
        
        }//end if user can submit  
    }
    
    
endif; // end   estate_add_new_property  





/////////////////////////////////////////////////////////////////////////////////////
// front end submission - add new property
/////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('estate_edit_property') ):
    
function estate_edit_property(){
    global $_POST;    
    global $userID;
    global $user_pack;
    global $status_values;
    global $status_values_array;
    global $feature_list_array;
    global $feature_list;
    global $custom_fields;
    global $current_user ;
    $allowed_html   =   array();
        if ( !isset($_POST['new_estate']) || !wp_verify_nonce($_POST['new_estate'],'submit_new_estate') ){
            exit('Sorry, your not submiting from site');
        }     
        $has_errors                     =   false;
        $show_err                       =   '';
        $edited                         =   0;
        $edit_id                        =   intval( $_POST['edit_id'] );
        $post                           =   get_post( $edit_id ); 
        $author_id                      =   $post->post_author ;
        if($current_user->ID !=  $author_id){
            exit('you don\'t have the rights to edit');
        }
        
        $images_todelete                =   wp_kses( $_POST['images_todelete'],$allowed_html );
        $images_delete_arr              =   explode(',',$images_todelete);
        foreach ($images_delete_arr as $key=>$value){
             $img                       =   get_post( $value ); 
             $author_id                 =   $img->post_author ;
             if($current_user->ID !=  $author_id){
                exit('you don\'t have the rights to delete images');
             }else{
                  wp_delete_post( $value );   
             }
                      
        }
        
        if( !isset($_POST['prop_category']) ) {
            $prop_category=0;           
        }else{
            $prop_category  =   intval($_POST['prop_category']);
        }
    
        if( !isset($_POST['prop_action_category']) ) {
            $prop_action_category=0;           
        }else{
            $prop_action_category  =   wp_kses($_POST['prop_action_category'],$allowed_html);
        }
        
        if( !isset($_POST['property_city']) ) {
            $property_city=0;           
        }else{
            $property_city  =   wp_kses($_POST['property_city'],$allowed_html);
        }
        
        if( !isset($_POST['property_area']) ) {
            $property_area=0;           
        }else{
            $property_area  =   wp_kses($_POST['property_area'],$allowed_html);
        }
       
        
        
        $submit_title                   =   wp_kses( $_POST['title'] ,$allowed_html); 
        $submit_description             =   wp_kses( $_POST['description'],$allowed_html);
        $property_address               =   wp_kses( $_POST['property_address'],$allowed_html);
        $property_county                =   wp_kses( $_POST['property_county'],$allowed_html);
        $property_state                 =   wp_kses( $_POST['property_state'],$allowed_html);
        $property_zip                   =   wp_kses( $_POST['property_zip'],$allowed_html);
        $country_selected               =   wp_kses( $_POST['property_country'],$allowed_html);     
        $prop_stat                      =   wp_kses( $_POST['property_status'],$allowed_html);
        $property_status                =   '';
        
        foreach ($status_values_array as $key=>$value) {
            $value = trim($value);
            $property_status.='<option value="' . $value . '"';
            if ($value == $prop_stat) {
                $property_status.='selected="selected"';
            }
            $property_status.='>' . $value . '</option>';
        }

        $property_price                 =   wp_kses( $_POST['property_price'],$allowed_html);
        $property_label                 =   wp_kses( $_POST['property_label'],$allowed_html);    
        $property_size                  =   wp_kses( $_POST['property_size'],$allowed_html);  
        $property_lot_size              =   wp_kses( $_POST['property_lot_size'],$allowed_html); 
        $property_year                  =   wp_kses( $_POST['property_year'],$allowed_html); 
        $property_rooms                 =   wp_kses( $_POST['property_rooms'],$allowed_html); 
        $property_bedrooms              =   wp_kses( $_POST['property_bedrooms'],$allowed_html); 
        $property_bathrooms             =   wp_kses( $_POST['property_bathrooms'],$allowed_html); 
        $option_video                   =   '';
        $video_values                   =   array('vimeo', 'youtube');
        $video_type                     =   wp_kses( $_POST['embed_video_type'],$allowed_html); 
        $google_camera_angle            =   wp_kses( $_POST['google_camera_angle'],$allowed_html); 

        foreach ($video_values as $value) {
            $option_video.='<option value="' . $value . '"';
            if ($value == $video_type) {
                $option_video.='selected="selected"';
            }
            $option_video.='>' . $value . '</option>';
        }
        
        $option_slider='';
        $slider_values  = array('full top slider', 'small slider');
        $slider_type    = wp_kses( $_POST['prop_slider_type'],$allowed_html );

        foreach ($slider_values as $value) {
            $option_slider.='<option value="' . $value . '"';
            if ($value == $slider_type) {
                $option_slider.='selected="selected"';
            }
            $option_slider.='>' . $value . '</option>';
        }
     

        $embed_video_id                 =   wp_kses( $_POST['embed_video_id'],$allowed_html); 
        $property_latitude              =   floatval( $_POST['property_latitude']); 
        $property_longitude             =   floatval( $_POST['property_longitude']); 
     

        if($google_view==1){
            $google_view_check=' checked="checked" ';
        }else{
             $google_view_check=' ';
        }
        
        $prop_featured                  =   intval( get_post_meta($edit_id, 'prop_featured', true) );
        if($prop_featured==1){
           $prop_featured_check    =' checked="checked" ';
        }else{
            $prop_featured_check   =' ';
        }

        $google_camera_angle            =   intval( $_POST['google_camera_angle']); 
        $prop_category                  =   get_term( $prop_category, 'property_category');
        $prop_action_category           =   get_term( $prop_action_category, 'property_action_category');     

      
     
        
        if($submit_title==''){
            $has_errors=true;
            $errors[]=esc_html__( 'Please submit a title for your property','wpestate');
        }
        
        if($submit_description==''){
            $has_errors=true;
            $errors[]=esc_html__( '*Please submit a description for your property','wpestate');
        }
        
        if (  $_FILES['upload_attachment']['name'][0]=='') {
          //  $has_errors=true;
           // $errors[]=esc_html__( '*Please submit an image for your property','wpestate');
        }
        
        if($property_address==''){
            $has_errors=true;
            $errors[]=esc_html__( '*Please submit an address for your property','wpestate');
        }
         
        if($property_address==''){
            $has_errors=true;
            $errors[]=esc_html__( '*Please submit the price','wpestate');
        }
        
           if($has_errors){
            foreach($errors as $key=>$value){
                $show_err.=$value.'</br>';
            }
            
        }else{
            $new_status='pending';
            $admin_submission_status = esc_html ( get_option('wp_estate_admin_submission','') );
            $paid_submission_status  = esc_html ( get_option('wp_estate_paid_submission','') );
              
            if($admin_submission_status=='no'  && $paid_submission_status!='per listing' ){
               $new_status='publish';  
            }
            
            $post = array(
                    'ID'            => $edit_id,
                    'post_title'    => $submit_title,
                    'post_content'  => $submit_description,
                    'post_type'     => 'estate_property',
                    'post_status'   => $new_status
            );

            $post_id =  wp_update_post($post );  
            $edited=1;
        }
        
        
     

        if( $edited==1) {
            if ( $_FILES ) {
                    $counter=0;
                    $files = array_reverse( $_FILES['upload_attachment']);
                    foreach ($files['name'] as $key => $value) {
                        if ($files['name'][$key]) {
                            $file = array(
                            'name' => $files['name'][$key],
                            'type' => $files['type'][$key],
                            'tmp_name' => $files['tmp_name'][$key],
                            'error' => $files['error'][$key],
                            'size' => $files['size'][$key]
                            );

                            $_FILES = array("upload_featured_attachment" => $file) ;
                         
                            foreach ($_FILES as $file => $array) {
                                $newupload = wpestate_insert_attachment($file,$post_id);
                            }
                             set_post_thumbnail( $post_id, $newupload );
                        }
                    }
            }  // end if files
            
            if( isset($prop_category->name) ){
                 wp_set_object_terms($post_id,$prop_category->name,'property_category'); 
            }  
            if ( isset ($prop_action_category->name) ){
                 wp_set_object_terms($post_id,$prop_action_category->name,'property_action_category'); 
            }  
            if( isset($property_city) ){
                 wp_set_object_terms($post_id,$property_city,'property_city'); 
            }  
            if( isset($property_area) && $property_area!='none' ){
                $property_area= wpestate_double_tax_cover($property_area,$property_city,$post_id);
               // wp_set_object_terms($post_id,$property_area,'property_area'); 
            }  
  
            
            
      
            update_post_meta($post_id, 'property_address', $property_address);
            update_post_meta($post_id, 'property_area', $property_area);
            update_post_meta($post_id, 'property_county', $property_county);
            update_post_meta($post_id, 'property_state', $property_state);
            update_post_meta($post_id, 'property_zip', $property_zip);
            update_post_meta($post_id, 'property_country', $country_selected);
            update_post_meta($post_id, 'property_size', $property_size);
            update_post_meta($post_id, 'property_lot_size', $property_lot_size);  
            update_post_meta($post_id, 'property_rooms', $property_rooms);  
            update_post_meta($post_id, 'property_bedrooms', $property_bedrooms);
            update_post_meta($post_id, 'property_bathrooms', $property_bathrooms);
            update_post_meta($post_id, 'property_year', $property_year);
            update_post_meta($post_id, 'property_status', $prop_stat);
            update_post_meta($post_id, 'property_price', $property_price);
            update_post_meta($post_id, 'property_label', $property_label);      
            update_post_meta($post_id, 'embed_video_type', $video_type);
            update_post_meta($post_id, 'embed_video_id', $embed_video_id);
            update_post_meta($post_id, 'prop_slider_type', $slider_type);
            update_post_meta($post_id, 'property_latitude', $property_latitude);
            update_post_meta($post_id, 'property_longitude', $property_longitude);
    
            update_post_meta($post_id, 'prop_featured', $prop_featured);
            update_post_meta($post_id, 'google_camera_angle', $google_camera_angle);
         
            foreach($feature_list_array as $key => $value){
                $post_var_name  =   str_replace(' ','_', trim($value) );
                $feature_value  =   wp_kses( $_POST[$post_var_name],$allowed_html );
                update_post_meta($post_id, $post_var_name, $feature_value);
            }
        
    
            // save custom fields
            $i=0;
            while($i< count($custom_fields) ){
               $name =   $custom_fields[$i][0];
               $type =   $custom_fields[$i][1];
               $slug =   str_replace(' ','_',$name);
               
               if($type=='numeric'){
                   $value_custom    =   intval(wp_kses( $_POST[$slug],$allowed_html ) );
                   update_post_meta($post_id, $slug, $value_custom);
               }else{
                   $value_custom    =   esc_html(wp_kses( $_POST[$slug] ,$allowed_html) );
                   update_post_meta($post_id, $slug, $value_custom);
               }
                   $custom_fields_array[$slug]= wp_kses( $_POST[$slug],$allowed_html); ;
               $i++;
            }
        
            // get user dashboard link
            $redirect = wpestate_get_dashboard_link();
            wp_reset_query();
            $arguments=array(
                'editing_listing_url'   => get_permalink($post_id),
                'editing_listing_title' => $submit_title
            );
            wpestate_select_email_type(get_option('admin_email'),'listing_edit',$arguments);
     
            
            wp_redirect( $redirect);
            exit;
        }// end if edited

    
}    
    
endif; // end   estate_edit_property








/////////////////////////////////////////////////////////////////////////////////////
// insert attachement on upload
/////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_insert_attachment') ):


function wpestate_insert_attachment($file_handler,$post_id,$setthumb='false') {

    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload( $file_handler, $post_id );

    if ($setthumb) {
        update_post_meta($post_id,'_thumbnail_id',$attach_id);
    }
    return $attach_id;
} 

endif; // end   wpestate_insert_attachment  

?>