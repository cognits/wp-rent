<?php


////////////////////////////////////////////////////////////////////////////////
/// Ajax  add booking  function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_add_allinone_custom', 'wpestate_ajax_add_allinone_custom' );  
add_action( 'wp_ajax_wpestate_ajax_add_allinone_custom', 'wpestate_ajax_add_allinone_custom' );  
if( !function_exists('wpestate_ajax_add_allinone_custom') ):
    function wpestate_ajax_add_allinone_custom(){
  
      //  check_ajax_referer( 'booking_ajax_nonce','security');
        $current_user = wp_get_current_user();
        $allowded_html      =   array();
        $userID             =   $current_user->ID;
        $from               =   $current_user->user_login;
        
        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }
        
        $property_id        =   intval( $_POST['listing_id'] );
        $the_post= get_post( $property_id); 
 
        if( $current_user->ID != $the_post->post_author ) {
            exit('you don\'t have the right to see this');
        }

        
        $new_custom_price   =   '';
        if( isset($_POST['new_price']) ){
            $new_custom_price            = floatval ( $_POST['new_price'] ) ;
        }
     
        $fromdate           =   wp_kses ( $_POST['book_from'], $allowded_html );
        $to_date            =   wp_kses ( $_POST['book_to'], $allowded_html );
      
        ////////////////// 
        $period_min_days_booking                =   intval( $_POST['period_min_days_booking'] );
        $period_extra_price_per_guest           =   intval( $_POST['period_extra_price_per_guest'] );
        $period_price_per_weekeend              =   intval( $_POST['period_price_per_weekeend'] );
        $period_checkin_change_over             =   intval( $_POST['period_checkin_change_over'] );
        $period_checkin_checkout_change_over    =   intval( $_POST['period_checkin_checkout_change_over'] );
        
        
        if($new_custom_price==0 && $period_min_days_booking==1 && $period_extra_price_per_guest==0 && $period_price_per_weekeend==0 
            && $period_checkin_change_over ==0 && $period_checkin_checkout_change_over==0 ){
            print'blank';
            return;
        }
        
        
             
        $mega_details_temp_array=array();
        $mega_details_temp_array['period_min_days_booking']             =   $period_min_days_booking;
        $mega_details_temp_array['period_extra_price_per_guest']        =   $period_extra_price_per_guest;
        $mega_details_temp_array['period_price_per_weekeend']           =   $period_price_per_weekeend;
        $mega_details_temp_array['period_checkin_change_over']          =   $period_checkin_change_over;
        $mega_details_temp_array['period_checkin_checkout_change_over'] =   $period_checkin_checkout_change_over;
           
                
                
        // build the price array 
        //print 'mem1 '.memory_get_usage ();
      
        $price_array=  wpml_custom_price_adjust($property_id);
        if(empty($price_array)){
            $price_array=array();
        }
        
        
        $mega_details_array = wpml_mega_details_adjust($property_id);
        
        if( !is_array($mega_details_array)){
            $mega_details_array=array();
        }
        
     
        ///////////////////////////////////////////////////
        
        $from_date      =   new DateTime($fromdate);
        $from_date_unix =   $from_date->getTimestamp();
        $to_date        =   new DateTime($to_date);
        $to_date_unix   =   $to_date->getTimestamp();
        
        if($new_custom_price!=0 && $new_custom_price!=''){
            $price_array[$from_date_unix]           =   $new_custom_price;
        }
        
        $mega_details_array[$from_date_unix]    =   $mega_details_temp_array;
        
   
        
            $from_date->modify('tomorrow');
            $from_date_unix =   $from_date->getTimestamp();
                
            while ($from_date_unix <= $to_date_unix){
                if($new_custom_price!=0 && $new_custom_price!=''){
                    $price_array[$from_date_unix]           =   $new_custom_price;
                }
               
                $mega_details_array[$from_date_unix]    =   $mega_details_temp_array;
                //print 'memx '.memory_get_usage ().' </br>/';
                $from_date->modify('tomorrow');
                $from_date_unix =   $from_date->getTimestamp();
            }
        
        // clean price options from old data
        $now=time() - 30*24*60*60;
        foreach ($price_array as $key=>$value){
            if( $key < $now ){
                unset( $price_array[$key] );
                unset( $mega_details_array[$key] );
            } 
        }
        
        
        // end clean
        
        update_post_meta($property_id, 'custom_price',$price_array );
        wpml_custom_price_adjust_save($property_id,$price_array);
          
        update_post_meta($property_id, 'mega_details',$mega_details_array );
        wpml_mega_details_adjust_save($property_id,$mega_details_array);
         
        echo wpestate_show_price_custom($new_custom_price);
       
        die();
  } 
endif;












add_action( 'wp_ajax_nopriv_wpestate_ajax_delete_custom_period', 'wpestate_ajax_delete_custom_period' );  
add_action( 'wp_ajax_wpestate_ajax_delete_custom_period', 'wpestate_ajax_delete_custom_period' );  
if( !function_exists('wpestate_ajax_delete_custom_period') ):
    function wpestate_ajax_delete_custom_period(){ 
    
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;


        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

        
        $allowed_html=array();
        if( !isset($_POST['edit_id'])  || $_POST['edit_id']=='') {
            exit('1');
        }else{
            $edit_id = intval($_POST['edit_id']);
        }
      
        $to_date    =   wp_kses ( $_POST['to_date'],$allowed_html);
        $from_date  =   wp_kses ( $_POST['from_date'],$allowed_html);
        
        
        $the_post= get_post( $edit_id); 
        if( $userID!= $the_post->post_author ) {
            exit('you don\'t have the right to delete this');;
        }
         // build the price array 
    
      
        $price_array        =  wpml_custom_price_adjust($edit_id);
        $mega_details_array =  wpml_mega_details_adjust($edit_id);
        
        if( !is_array($mega_details_array)){
            $mega_details_array=array();
        }
        
     
        ///////////////////////////////////////////////////
        
        $from_date      =   new DateTime("@".$from_date);
        $from_date_unix =   $from_date->getTimestamp();
        $to_date        =   new DateTime("@".$to_date);
        $to_date_unix   =   $to_date->getTimestamp();
        
        unset($price_array[$from_date_unix]);
        unset($mega_details_array[$from_date_unix]);
        
        $from_date->modify('tomorrow');
        $from_date_unix =   $from_date->getTimestamp();

        while ($from_date_unix <= $to_date_unix){
            unset($price_array[$from_date_unix]);
            unset($mega_details_array[$from_date_unix]);
            $from_date->modify('tomorrow');
            $from_date_unix =   $from_date->getTimestamp();
        }
        
        update_post_meta($edit_id, 'custom_price',$price_array );
        wpml_custom_price_adjust_save($edit_id,$price_array);
        
        update_post_meta($edit_id, 'mega_details',$mega_details_array );
        wpml_mega_details_adjust_save($edit_id,$mega_details_array);
        
        
        
        print 'deleted';
        die();
    }
endif;    




add_action( 'wp_ajax_nopriv_wpestate_ajax_front_end_submit', 'wpestate_ajax_front_end_submit' );  
add_action( 'wp_ajax_wpestate_ajax_front_end_submit', 'wpestate_ajax_front_end_submit' );  
if( !function_exists('wpestate_ajax_front_end_submit') ):
    function wpestate_ajax_front_end_submit(){ 
        $allowed_html                   =   array();
        if( !isset($_POST['title'])  || $_POST['title']=='') {
            exit('1');
        }
    
        if( !isset($_POST['prop_category'])  || $_POST['prop_category']=='') {
            exit('2');
        }
    
        if( !isset($_POST['prop_action_category'])  || $_POST['prop_action_category']=='') {
            exit('3');
        }
        
        if( !isset($_POST['property_city'])  || $_POST['property_city']=='') {
            exit('4');
        }

        if( !isset($_POST['guest_no'])  || $_POST['guest_no']=='') {
            exit('5');
        }
    
        if ( !isset($_POST['new_estate']) || !wp_verify_nonce($_POST['new_estate'],'submit_new_estate') ){
            exit('6'); 
        }
   
    
    $paid_submission_status    = esc_html ( get_option('wp_estate_paid_submission','') );
    if ( $paid_submission_status!='membership' || ( $paid_submission_status== 'membership' || wpestate_get_current_user_listings($userID) > 0)  ){ // if user can submit        
        /*if ( !isset($_POST['new_estate']) || !wp_verify_nonce($_POST['new_estate'],'submit_new_estate') ){
           exit('Sorry, your not submiting from site'); 
        }*/
        
        if( !estate_verify_onetime_nonce_login($_POST['security'], 'submit_front_ajax_nonce') ){
            exit('Sorry, your not submiting from site or you have too many attempts'); 
        }
        
   
        if( !isset($_POST['prop_category']) ) {
            $prop_category  = 0;           
        }else{
            $prop_category  =   intval($_POST['prop_category']);
        }
  
        if( !isset($_POST['prop_action_category']) ) {
            $prop_action_category   =   0;           
        }else{
            $prop_action_category  =   wp_kses($_POST['prop_action_category'],$allowed_html);
        }
        
        if( !isset($_POST['property_city']) ) {
            $property_city  =   '';           
        }else{
            $property_city  =   wp_kses($_POST['property_city'],$allowed_html);
        }
        
        if( !isset($_POST['property_area_front']) ) {
            $property_area  =   '';           
        }else{
            $property_area  =   wp_kses($_POST['property_area_front'],$allowed_html);
        }
        
        
        if( !isset($_POST['property_country']) ) {
            $property_country   =   '';           
        }else{
            $property_country  =   wp_kses($_POST['property_country'],$allowed_html);
        }
        
        if( !isset($_POST['property_description']) ) {
            $property_description   =   '';           
        }else{
            $property_description  =   wp_kses($_POST['property_description'],$allowed_html);
        }
      
        $show_err                       =   '';
        $post_id                        =   '';
        $submit_title                   =   wp_kses( $_POST['title'],$allowed_html ); 
        $guest_no                       =   intval( $_POST['guest_no']);
        $has_errors                     =   false;
        $errors                         =   array();
        
        
        if($submit_title==''){
            $has_errors=true;
            $errors[]=esc_html__( 'Please submit a title for your listing','wpestate');
        }
        
        if($prop_category=='' || $prop_category=='-1'){
            $has_errors=true;
            $errors[]=esc_html__( 'Please submit a category for your property','wpestate');
        }
        
        
        if($prop_action_category=='' || $prop_action_category=='-1'){
            $has_errors=true;
            $errors[]=esc_html__( 'Please chose a room type for your listing','wpestate');
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
            
            
          
            $new_user_id=0;
           
          
            $post = array(
                'post_title'	=> $submit_title,
                'post_status'	=> $new_status, 
                'post_type'     => 'estate_property' ,
                'post_author'   => $new_user_id ,
                'post_content'  => $property_description
            );
            $post_id =  wp_insert_post($post );  
            
          
       
        }
        
        if($post_id) {
            $prop_category                  =   get_term( $prop_category, 'property_category');
            if(isset($prop_category->term_id)){
                $prop_category_selected         =   $prop_category->term_id;
            }

            $prop_action_category           =   get_term( $prop_action_category, 'property_action_category');  
            if(isset($prop_action_category->term_id)){
                 $prop_action_category_selected  =   $prop_action_category->term_id;
            }
        
            if( isset($prop_category->name) ){
                 wp_set_object_terms($post_id,$prop_category->name,'property_category'); 
            }  
            if ( isset ($prop_action_category->name) ){
                 wp_set_object_terms($post_id,$prop_action_category->name,'property_action_category'); 
            }  
            if( isset($property_city) && $property_city!='none' ){
                   wp_set_object_terms($post_id,$property_city,'property_city'); 
            }  
            
           
            if( isset($property_area) && $property_area!='none' ){
                $property_area= wpestate_double_tax_cover($property_area,$property_city,$post_id);
               // wp_set_object_terms($post_id,$property_area,'property_area'); 
            }  
  
            
            if( isset($property_area) && $property_area!='none' && $property_area!=''){
                $property_area_obj=   get_term_by('name', $property_area, 'property_area'); 
             
                    $t_id = $property_area_obj->term_id ;
                    $term_meta = get_option( "taxonomy_$t_id");
                    $allowed_html   =   array();
                    $term_meta['cityparent'] =  wp_kses( $property_city,$allowed_html);
                    $term_meta['pagetax'] = '';
                    $term_meta['category_featured_image '] = '';
                    $term_meta['category_tagline'] = '';
                    $term_meta['category_attach_id'] = '';

                    //save the option array
                     update_option( "taxonomy_$t_id", $term_meta );
               
            }
            
            
      
            update_post_meta($post_id, 'prop_featured', 0);
            update_post_meta($post_id, 'guest_no', $guest_no);
            update_post_meta($post_id, 'property_country', $property_country);            
            update_post_meta($post_id, 'pay_status', 'not paid');
            update_post_meta($post_id, 'page_custom_zoom', 16);
            $sidebar =  get_option( 'wp_estate_blog_sidebar', true); 
            update_post_meta($post_id, 'sidebar_option', $sidebar);
            $sidebar_name   = get_option( 'wp_estate_blog_sidebar_name', true); 
            update_post_meta($post_id, 'sidebar_select', $sidebar_name);
            
            // get user dashboard link
            $edit_link                       =   wpestate_get_dasboard_edit_listing();
            $edit_link_desc                  =   esc_url_raw ( add_query_arg( 'listing_edit', $post_id, $edit_link) ) ;
            $edit_link_desc                  =   esc_url_raw ( add_query_arg( 'action', 'description', $edit_link_desc) ) ;
            $edit_link_desc                  =   esc_url_raw ( add_query_arg( 'isnew', 1, $edit_link_desc) ) ;
            
            $arguments=array(
                'new_listing_url'   => get_permalink($post_id),
                'new_listing_title' => $submit_title
            );
            wpestate_select_email_type(get_option('admin_email'),'new_listing_submission',$arguments);          
            wp_reset_query();
            print $post_id;
            die();
        }else{
            print 'out';
        }
    }
}
endif;    
    




////////////////////////////////////////////////////////////////////////////////
/// Ajax  add booking  function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_add_custom_price', 'wpestate_ajax_add_custom_price' );  
add_action( 'wp_ajax_wpestate_ajax_add_custom_price', 'wpestate_ajax_add_custom_price' );  
if( !function_exists('wpestate_ajax_add_custom_price') ):
    function wpestate_ajax_add_custom_price(){
  
      //  check_ajax_referer( 'booking_ajax_nonce','security');
        $current_user = wp_get_current_user();
        $allowded_html      =   array();
        $userID             =   $current_user->ID;
        
        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

        
        $from               =   $current_user->user_login;
        $new_custom_price   =   '';
   
        
        if( isset($_POST['new_price']) ){
            $new_custom_price            = floatval ( $_POST['new_price'] ) ;
        }
     
     
        $property_id        =   intval( $_POST['listing_id'] );
        
        $the_post= get_post( $property_id); 
 
        if( $current_user->ID != $the_post->post_author ) {
            exit('you don\'t have the right to see this');
        }

        
        
        
        $fromdate           =   wp_kses ( $_POST['book_from'], $allowded_html );
        $to_date            =   wp_kses ( $_POST['book_to'], $allowded_html );
      
        ////////////////// 
        $period_min_days_booking                =   intval( $_POST['period_min_days_booking'] );
        $period_extra_price_per_guest           =   intval( $_POST['period_extra_price_per_guest'] );
        $period_price_per_weekeend              =   intval( $_POST['period_price_per_weekeend'] );
        $period_checkin_change_over             =   intval( $_POST['period_checkin_change_over'] );
        $period_checkin_checkout_change_over    =   intval( $_POST['period_checkin_checkout_change_over'] );
             
        $mega_details_temp_array=array();
        $mega_details_temp_array['period_min_days_booking']             =   $period_min_days_booking;
        $mega_details_temp_array['period_extra_price_per_guest']        =   $period_extra_price_per_guest;
        $mega_details_temp_array['period_price_per_weekeend']           =   $period_price_per_weekeend;
        $mega_details_temp_array['period_checkin_change_over']          =   $period_checkin_change_over;
        $mega_details_temp_array['period_checkin_checkout_change_over'] =   $period_checkin_checkout_change_over;
           
                
                
        // build the price array 
        //print 'mem1 '.memory_get_usage ();
       
        $price_array = wpml_custom_price_adjust($property_id);
        if(empty($price_array)){
            $price_array=array();
        }
        
        
        $mega_details_array = wpml_mega_details_adjust($property_id);
        if( !is_array($mega_details_array)){
            $mega_details_array=array();
        }
        
     
        ///////////////////////////////////////////////////
        
        $from_date      =   new DateTime($fromdate);
        $from_date_unix =   $from_date->getTimestamp();
        $to_date        =   new DateTime($to_date);
        $to_date_unix   =   $to_date->getTimestamp();
        
        if($new_custom_price!=0 && $new_custom_price!=''){
            $price_array[$from_date_unix]           =   $new_custom_price;
        }
        
        $mega_details_array[$from_date_unix]    =   $mega_details_temp_array;
        
   
        
            $from_date->modify('tomorrow');
            $from_date_unix =   $from_date->getTimestamp();
                
            while ($from_date_unix <= $to_date_unix){
                if($new_custom_price!=0 && $new_custom_price!=''){
                    $price_array[$from_date_unix]           =   $new_custom_price;
                }
               
                $mega_details_array[$from_date_unix]    =   $mega_details_temp_array;
                //print 'memx '.memory_get_usage ().' </br>/';
                $from_date->modify('tomorrow');
                $from_date_unix =   $from_date->getTimestamp();
            }
        
        // clean price options from old data
        $now=time() - 30*24*60*60;
        foreach ($price_array as $key=>$value){
            if( $key < $now ){
                unset( $price_array[$key] );
                unset( $mega_details_array[$key] );
            } 
        }
        
        
        // end clean
        
        update_post_meta($property_id, 'custom_price',$price_array );
        wpml_custom_price_adjust_save($property_id,$price_array);
        
        update_post_meta($property_id, 'mega_details',$mega_details_array );
        wpml_mega_details_adjust_save($property_id,$mega_details_array);
         
        echo wpestate_show_price_custom($new_custom_price);
       
        die();
  } 
endif;


////////////////////////////////////////////////////////////////////////////////
/// Ajax  add booking  function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_add_booking', 'wpestate_ajax_add_booking' );  
add_action( 'wp_ajax_wpestate_ajax_add_booking', 'wpestate_ajax_add_booking' );  
if( !function_exists('wpestate_ajax_add_booking') ):
    function wpestate_ajax_add_booking(){
      
      //  check_ajax_referer( 'booking_ajax_nonce','security');
        $current_user = wp_get_current_user();
        $allowded_html      =   array();
        $userID             =   $current_user->ID;
        
        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

        
        $from               =   $current_user->user_login;
        $comment            =   '';
        $status             =   'pending';
        
        if( isset($_POST['comment']) ){
            $comment            =    wp_kses ( $_POST['comment'],$allowded_html ) ;
        }
        
        $booking_guest_no    =   0;
        if(isset($_POST['booking_guest_no'])){
            $booking_guest_no    =   intval($_POST['booking_guest_no']);
        }
        
        if ( isset ($_POST['confirmed']) ) {
            if (intval($_POST['confirmed'])==1 ){
                $status    =   'confirmed';
            }
        }
        
     
        
        $property_id        =   intval( $_POST['listing_edit'] );
        $owner_id           =   wpsestate_get_author($property_id);
        $fromdate           =   wp_kses ( $_POST['fromdate'], $allowded_html );
        $to_date            =   wp_kses ( $_POST['todate'], $allowded_html );
        $event_name         =   esc_html__( 'Booking Request','wpestate');
        
        $post = array(
            'post_title'	=> $event_name,
            'post_content'	=> $comment,
            'post_status'	=> 'publish', 
            'post_type'         => 'wpestate_booking' ,
            'post_author'       => $userID
        );
        $post_id =  wp_insert_post($post );  
        
        $post = array(
            'ID'                => $post_id,
            'post_title'	=> $event_name.' '.$post_id
        );
        wp_update_post( $post );
       
       
       
        
        update_post_meta($post_id, 'booking_status', $status);
        update_post_meta($post_id, 'booking_id', $property_id);
        update_post_meta($post_id, 'owner_id', $owner_id);
        update_post_meta($post_id, 'booking_from_date', $fromdate);
        update_post_meta($post_id, 'booking_to_date', $to_date);
        update_post_meta($post_id, 'booking_invoice_no', 0);
        update_post_meta($post_id, 'booking_pay_ammount', 0);
        update_post_meta($post_id, 'booking_guests', $booking_guest_no);

         // build the reservation array 
        $reservation_array = wpestate_get_booking_dates($property_id);      
        update_post_meta($property_id, 'booking_dates', $reservation_array); 
        
        
        if ( $owner_id == $userID ) {
            $subject    =   esc_html__( 'You reserved a period','wpestate');
            $description=   esc_html__( 'You have reserverd a period on your own listing','wpestate');

            $from               =   $current_user->user_login;
            $to                 =   $owner_id;

            $receiver          =   get_userdata($owner_id);
            $receiver_email    =   $receiver->user_email;


            wpestate_add_to_inbox($userID,$from,$to, $subject,$description);
            wpestate_send_booking_email('mynewbook',$receiver_email,$property_id);

            
        }else{
            
            $subject    =   esc_html__( 'New Booking Request from ','wpestate');
            $description=   esc_html__( 'You have received a new booking request','wpestate');
            
            $from               =   $current_user->ID;
            $to                 =   $owner_id;

            $receiver          =   get_userdata($owner_id);
            $receiver_email    =   $receiver->user_email;

            print " email to ".$receiver_email.' pr id '.$property_id.'/'.$from.'/'.$to;
            //print $userID." / ".$userID."/".$to;
            wpestate_add_to_inbox($userID,$userID,$to, $subject,$description,"external_book_req");
            wpestate_send_booking_email('newbook',$receiver_email,$property_id);

        }    
        
       

        die();
  } 
endif;

  
  


///////////////////////////////////////////////////////////////////////////
//edit property location
////////////////////////////////////////////////////////////////////////////   
add_action( 'wp_ajax_nopriv_wpestate_ajax_update_listing_ammenities', 'wpestate_ajax_update_listing_ammenities' );  
add_action( 'wp_ajax_wpestate_ajax_update_listing_ammenities', 'wpestate_ajax_update_listing_ammenities' );  
if( !function_exists('wpestate_ajax_update_listing_ammenities') ):
    function wpestate_ajax_update_listing_ammenities(){ 
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;
        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }



        if( isset( $_POST['listing_edit'] ) ) {
            if( !is_numeric($_POST['listing_edit'] ) ){
                exit('you don\'t have the right to edit this');
            }else{
                $edit_id    =   intval($_POST['listing_edit'] );
                $the_post   =   get_post( $edit_id); 

                if( $current_user->ID != $the_post->post_author ) {
                    esc_html_e("you don't have the right to edit this","wpestate");
                    die();
                }else{
                    $allowed_html           =   array();
                    $i=0;

                    $custom_values_amm = explode('~',wp_kses($_POST['custom_fields_amm'], $allowed_html));
                    $feature_list_array             =   array();
                    $feature_list                   =   esc_html( get_option('wp_estate_feature_list') );
                    $feature_list_array             =   explode( ',',$feature_list);

                    foreach($feature_list_array as $key => $value){

                        $post_var_name      =   str_replace(' ','_', trim($value) );
                        $post_var_name      =   wpestate_limit45(sanitize_title( $post_var_name ));
                        $post_var_name      =   sanitize_key($post_var_name);

                        $feature_value  =   wp_kses( $custom_values_amm[$i+1] ,$allowed_html);
                        update_post_meta($edit_id, $post_var_name, $feature_value);
                        $moving_array[] =   $post_var_name;
                        $i++;
                    }
                    echo json_encode(array('edited'=>true, 'response'=>esc_html__( 'Changes are saved!','wpestate')));
                    die();

                }
            }
        }
    }
endif;

///////////////////////////////////////////////////////////////////////////
//edit property location
////////////////////////////////////////////////////////////////////////////   
add_action( 'wp_ajax_nopriv_wpestate_ajax_update_listing_location', 'wpestate_ajax_update_listing_location' );  
add_action( 'wp_ajax_wpestate_ajax_update_listing_location', 'wpestate_ajax_update_listing_location' );  
if( !function_exists('wpestate_ajax_update_listing_location') ):
    function wpestate_ajax_update_listing_location(){ 
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;

        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

        if( isset( $_POST['listing_edit'] ) ) {
            if( !is_numeric($_POST['listing_edit'] ) ){
                exit('you don\'t have the right to edit this');
            }else{
                $edit_id    =   intval($_POST['listing_edit'] );
                $the_post   =   get_post( $edit_id); 

                if( $current_user->ID != $the_post->post_author ) {
                    esc_html_e("you don't have the right to edit this","wpestate");
                    die();
                }else{
                    $allowed_html           =   array();

                    $property_latitude      = floatval($_POST['property_latitude']);
                    $property_longitude     = floatval($_POST['property_longitude']);
                    $google_camera_angle    = floatval($_POST['google_camera_angle']);
                    $property_address       = wp_kses($_POST['property_address'],$allowed_html);
                    $property_zip           = wp_kses($_POST['property_zip'],$allowed_html);
                    $property_county        = wp_kses($_POST['property_county'],$allowed_html);
                    $property_state         = wp_kses($_POST['property_state'],$allowed_html);

                    update_post_meta($edit_id, 'property_latitude', $property_latitude);
                    update_post_meta($edit_id, 'property_longitude', $property_longitude);
                    update_post_meta($edit_id, 'google_camera_angle', $google_camera_angle);
                    update_post_meta($edit_id, 'property_address', $property_address);
                    update_post_meta($edit_id, 'property_zip', $property_zip);
                    update_post_meta($edit_id, 'property_state', $property_state);
                    update_post_meta($edit_id, 'property_county', $property_county);

                    echo json_encode(array('edited'=>true, 'response'=>esc_html__( 'Changes are saved!','wpestate')));
                    die();

                }
            }   
        }
    }    
endif;


///////////////////////////////////////////////////////////////////////////
//edit property location
////////////////////////////////////////////////////////////////////////////   
add_action( 'wp_ajax_nopriv_wpestate_ajax_update_ical_feed', 'wpestate_ajax_update_ical_feed' );  
add_action( 'wp_ajax_wpestate_ajax_update_ical_feed', 'wpestate_ajax_update_ical_feed' );  
if( !function_exists('wpestate_ajax_update_ical_feed') ):
    function wpestate_ajax_update_ical_feed(){ 
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;

        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }



        if( isset( $_POST['listing_edit'] ) ) {
            if( !is_numeric($_POST['listing_edit'] ) ){
                exit('you don\'t have the right to edit this');
            }else{
                $edit_id    =   intval($_POST['listing_edit'] );
                $the_post   =   get_post( $edit_id); 

                if( $current_user->ID != $the_post->post_author ) {
                    esc_html_e("you don't have the right to edit this","wpestate");
                    die();
                }else{
              

                    $property_icalendar_import      =esc_url_raw($_POST['property_icalendar_import']);
                   

                    update_post_meta($edit_id, 'property_icalendar_import', $property_icalendar_import);
                    
                    if ($property_icalendar_import!=''){
                        wpestate_import_calendar_feed_listing($edit_id);
                    }
                    
                    echo json_encode(array('edited'=>true, 'response'=>esc_html__( 'Changes are saved!','wpestate')));
                    die();

                }
            }   
        }
    }    
endif;


///////////////////////////////////////////////////////////////////////////
//edit property location
////////////////////////////////////////////////////////////////////////////   
add_action( 'wp_ajax_nopriv_wpestate_ajax_delete_imported_dates', 'wpestate_ajax_delete_imported_dates' );  
add_action( 'wp_ajax_wpestate_ajax_delete_imported_dates', 'wpestate_ajax_delete_imported_dates' );  
if( !function_exists('wpestate_ajax_delete_imported_dates') ):
    function wpestate_ajax_delete_imported_dates(){ 
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;
        
        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

        $edit_id=intval( $_POST['edit_id']);
        $the_post= get_post( $edit_id); 
        if( $userID!= $the_post->post_author ) {
            exit('you don\'t have the right to delete this');;
        }else{
            $reservation_array  = get_post_meta($edit_id, 'booking_dates',true  ); 
            foreach($reservation_array as $key=>$value){
                if( !is_numeric($value)){
                    unset($reservation_array[$key]);
                }
            }
            update_post_meta($edit_id, 'booking_dates',$reservation_array);
            print'done';
        }
        die();
        
    }
endif;




////////////////////////////////////////////////////////////////////////////
//edit property images
////////////////////////////////////////////////////////////////////////////   
add_action( 'wp_ajax_nopriv_wpestate_ajax_update_listing_details', 'wpestate_ajax_update_listing_details' );  
add_action( 'wp_ajax_wpestate_ajax_update_listing_details', 'wpestate_ajax_update_listing_details' );  
if( !function_exists('wpestate_ajax_update_listing_details') ):
    function wpestate_ajax_update_listing_details(){ 
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;

        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

        if( isset( $_POST['listing_edit'] ) ) {
            if( !is_numeric($_POST['listing_edit'] ) ){
                exit('you don\'t have the right to edit this');
            }else{
                $edit_id    =   intval($_POST['listing_edit'] );
                $the_post   =   get_post( $edit_id); 

                if( $current_user->ID != $the_post->post_author ) {
                    esc_html_e("you don't have the right to edit this","wpestate");
                    die();
                }else{
                    $allowed_html           =   array();
                    $property_size          =   floatval($_POST['property_size']);
                    $property_rooms         =   floatval($_POST['property_rooms']);
                    $property_bedrooms      =   floatval($_POST['property_bedrooms']);
                    $property_bathrooms     =   floatval($_POST['property_bathrooms']);

                    update_post_meta($edit_id, 'property_size', $property_size);
                    update_post_meta($edit_id, 'property_rooms', $property_rooms);
                    update_post_meta($edit_id, 'property_bedrooms', $property_bedrooms);
                    update_post_meta($edit_id, 'property_bathrooms', $property_bathrooms);

                    $custom_values = explode('~',wp_kses($_POST['custom_fields_val'], $allowed_html));

                    // save custom fields
                    $i=0;
                    $custom_fields = get_option( 'wp_estate_custom_fields', true);  
                    if( !empty($custom_fields)){  
                        while($i< count($custom_fields) ){
                            $name =   $custom_fields[$i][0];
                            $type =   $custom_fields[$i][1];
                            $slug =   str_replace(' ','_',$name);
                            $slug =   wpestate_limit45(sanitize_title( $name ));
                            $slug =   sanitize_key($slug);

                            if($type=='numeric'){
                                $value_custom    =   intval(wp_kses( $custom_values[$i+1],$allowed_html ) );
                                update_post_meta($edit_id, $slug, $value_custom);
                            }else{
                                $value_custom    =   esc_html(wp_kses( $custom_values[$i+1],$allowed_html ) );
                                update_post_meta($edit_id, $slug, $value_custom);
                            }                       

                            $i++;
                        }
                    }

                    echo json_encode(array('edited'=>true, 'response'=>esc_html__( 'Changes are saved!','wpestate')));
                    die();
                }
            }
        }    
    }
endif;

////////////////////////////////////////////////////////////////////////////
//edit property images
////////////////////////////////////////////////////////////////////////////   
add_action( 'wp_ajax_nopriv_wpestate_ajax_update_listing_images', 'wpestate_ajax_update_listing_images' );  
add_action( 'wp_ajax_wpestate_ajax_update_listing_images', 'wpestate_ajax_update_listing_images' );  
if( !function_exists('wpestate_ajax_update_listing_images') ):
    function wpestate_ajax_update_listing_images(){ 
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;


        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

        if( isset( $_POST['listing_edit'] ) ) {
            if( !is_numeric($_POST['listing_edit'] ) ){
                exit('you don\'t have the right to edit this');
            }else{
                $edit_id    =   intval($_POST['listing_edit'] );
                $the_post   =   get_post( $edit_id); 

                if( $current_user->ID != $the_post->post_author ) {
                    esc_html_e("you don't have the right to edit this","wpestate");
                    die();
                }else{
                    $allowed_html   =   array();

                    $video_type     =   wp_kses($_POST['video_type'],$allowed_html);
                    $video_id       =   wp_kses($_POST['video_id'],$allowed_html);
                    $attachthumb    =   intval($_POST['attachthumb']);
                    $attachid       =   wp_kses($_POST['attachid'],$allowed_html);

                    $attach_array   =   explode(',',$attachid);
                    $last_id        =   '';

                    // check for deleted images
                    $arguments = array(
                                'numberposts'   => -1,
                                'post_type'     => 'attachment',
                                'post_parent'   => $edit_id,
                                'post_status'   => null,
                                'orderby'       => 'menu_order',
                                'order'         => 'ASC'
                    );
                    $post_attachments = get_posts($arguments);

                    $new_thumb=0;
                    $curent_thumb=get_post_thumbnail_id($edit_id);
                    foreach ($post_attachments as $attachment){
                        if ( !in_array ($attachment->ID,$attach_array) ){
                            wp_delete_post($attachment->ID);
                            if( $curent_thumb == $attachment->ID ){
                                $new_thumb=1;
                            }
                        }
                    }

                    // check for deleted images

                    $order=0;
                    foreach($attach_array as $att_id){
                        if( !is_numeric($att_id) ){

                        }else{
                            if($last_id==''){
                                $last_id=  $att_id;  
                            }
                            $order++;
                            wp_update_post( array(
                                        'ID' => $att_id,
                                        'post_parent' => $edit_id,
                                        'menu_order'=>$order
                                    ));


                        }
                    }

                    if( $attachthumb !=''  ){
                        set_post_thumbnail( $edit_id, $attachthumb ); 
                    } 

                    if($new_thumb==1 || !has_post_thumbnail($edit_id) || $attachthumb==''){
                        set_post_thumbnail( $edit_id, $last_id );
                    }

                    update_post_meta($edit_id, 'embed_video_type', $video_type);
                    update_post_meta($edit_id, 'embed_video_id', $video_id);

                    echo json_encode(array('edited'=>true, 'response'=>esc_html__( 'Changes are saved!','wpestate')));
                    die();
                }
            }
        }   
    }
endif;







////////////////////////////////////////////////////////////////////////////
//edit property price
////////////////////////////////////////////////////////////////////////////   
add_action( 'wp_ajax_nopriv_wpestate_ajax_update_listing_price', 'wpestate_ajax_update_listing_price' );  
add_action( 'wp_ajax_wpestate_ajax_update_listing_price', 'wpestate_ajax_update_listing_price' );  
if( !function_exists('wpestate_ajax_update_listing_price') ):
    function wpestate_ajax_update_listing_price(){ 
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;

        if ( !is_user_logged_in() ) {   
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }


        if( isset( $_POST['listing_edit'] ) ) {
            if( !is_numeric($_POST['listing_edit'] ) ){
                exit('you don\'t have the right to edit this');
            }else{
                $edit_id    =   intval($_POST['listing_edit'] );
                $the_post   =   get_post( $edit_id); 

                if( $current_user->ID != $the_post->post_author ) {
                    esc_html_e("you don't have the right to edit this","wpestate");
                    die();
                }else{
                    $allowed_html    =  array();
                    $cleaning_fee    =  floatval( $_POST['cleaning_fee']);
                    $city_fee        =  floatval( $_POST['city_fee']);
                    $price           =  floatval( $_POST['price']);
                    $price_week      =  floatval( $_POST['price_week']);
                    $price_month     =  floatval( $_POST['price_month']);

                    $cleaning_fee_per_day           =   floatval( $_POST['cleaning_fee_per_day']);
                    $city_fee_per_day               =   floatval( $_POST['city_fee_per_day']);
                    $min_days_booking               =   floatval( $_POST['min_days_booking']);
                    $price_per_guest_from_one       =   floatval( $_POST['price_per_guest_from_one']);
                    $price_per_weekeend             =   floatval( $_POST['price_per_weekeend']);
                    $checkin_change_over            =   floatval( $_POST['checkin_change_over']);
                    $checkin_checkout_change_over   =   floatval( $_POST['checkin_checkout_change_over']);
                    $extra_price_per_guest          =   floatval( $_POST['extra_price_per_guest']);
                    $overload_guest                 =   floatval( $_POST['overload_guest']);
                    
                    

                    update_post_meta($edit_id, 'property_price', $price);
                    update_post_meta($edit_id, 'cleaning_fee', $cleaning_fee);
                    update_post_meta($edit_id, 'city_fee', $city_fee);
                    //update_post_meta($edit_id, 'property_label', $property_label);
                    update_post_meta($edit_id, 'property_price_per_week', $price_week);
                    update_post_meta($edit_id, 'property_price_per_month', $price_month);
                    
                    
                    update_post_meta($edit_id, 'cleaning_fee_per_day', $cleaning_fee_per_day);
                    update_post_meta($edit_id, 'city_fee_per_day', $city_fee_per_day);
                    update_post_meta($edit_id, 'price_per_guest_from_one', $price_per_guest_from_one);
                    update_post_meta($edit_id, 'price_per_weekeend', $price_per_weekeend);
                    update_post_meta($edit_id, 'checkin_change_over', $checkin_change_over);
                    update_post_meta($edit_id, 'checkin_checkout_change_over', $checkin_checkout_change_over);
                    update_post_meta($edit_id, 'min_days_booking', $min_days_booking);
                    update_post_meta($edit_id, 'extra_price_per_guest', $extra_price_per_guest);
                    update_post_meta($edit_id, 'overload_guest', $overload_guest);
                    
                    echo json_encode(array('edited'=>true, 'response'=>esc_html__( 'Changes are saved!','wpestate')));
                    die();

                }   
            }
        }
    }
endif;


////////////////////////////////////////////////////////////////////////////
//edit property description
////////////////////////////////////////////////////////////////////////////   
add_action( 'wp_ajax_nopriv_wpestate_ajax_update_listing_description', 'wpestate_ajax_update_listing_description' );  
add_action( 'wp_ajax_wpestate_ajax_update_listing_description', 'wpestate_ajax_update_listing_description' );  
if( !function_exists('wpestate_ajax_update_listing_description') ):
function wpestate_ajax_update_listing_description(){ 
    
    $current_user = wp_get_current_user();
    $userID                         =   $current_user->ID;
    
   
    if ( !is_user_logged_in() ) {   
        exit('ko');
    }
    if($userID === 0 ){
        exit('out pls');
    }


    if( isset( $_POST['listing_edit'] ) ) {
        if( !is_numeric($_POST['listing_edit'] ) ){
            exit('you don\'t have the right to edit this');
        }else{
            $edit_id    =   intval($_POST['listing_edit'] );
            $the_post   =   get_post( $edit_id); 
            
            if( $current_user->ID != $the_post->post_author ) {
                esc_html_e("you don't have the right to edit this","wpestate");
                die();
            }else{
            ////////////////////////////////////////////////////////////////////    
            // start the edit    
            ////////////////////////////////////////////////////////////////////    
                $allowed_html                   =   array();
                $has_errors                     =   false;
                $show_err                       =   '';
                $submit_title                   =   wp_kses( $_POST['title'] ,$allowed_html); 
                $submit_desc                    =   wp_kses( $_POST['prop_desc'] ,$allowed_html); 
                $guest_no                       =   intval( $_POST['guests']);
            
                //category
                if( !isset($_POST['category']) ) {
                    $prop_category=0;           
                }else{
                    $prop_category  =   intval($_POST['category']);
                }

                if($prop_category==-1){
                    wp_delete_object_term_relationships($edit_id,'property_category'); 
                }
                
                //action category
                if( !isset($_POST['action_category']) ) {
                    $prop_action_category=0;           
                }else{
                    $prop_action_category  =   wp_kses($_POST['action_category'],$allowed_html);
                }

                if($prop_action_category==-1){
                    wp_delete_object_term_relationships($edit_id,'property_action_category'); 
                }
                
                $prop_category                  =   get_term( $prop_category, 'property_category');
                if(isset($prop_category->term_id)){
                    $prop_category_selected         =   $prop_category->term_id;
                }

                $prop_action_category           =   get_term( $prop_action_category, 'property_action_category');  
                if(isset($prop_action_category->term_id)){
                    $prop_action_category_selected  =   $prop_action_category->term_id;
                }
                
                // city
                if( !isset($_POST['city']) ) {
                    $property_city=0;           
                }else{
                    $property_city  =   wp_kses($_POST['city'],$allowed_html);
                }
                
                if( !isset($_POST['country']) ) {
                    $property_country=0;           
                }else{
                    $property_country  =   wp_kses($_POST['country'],$allowed_html);
                }
                
                 if( !isset($_POST['area']) ) {
                    $property_area=0;           
                }else{
                    $property_area  =   wp_kses($_POST['area'],$allowed_html);
                }
               
                if( !isset($_POST['property_admin_area']) ) {
                    $property_admin_area='';           
                }else{
                    $property_admin_area  =   wp_kses($_POST['property_admin_area'],$allowed_html);
                }
                
                
                
                
                //////////////////////////////////////// the updated 
                
                if($submit_title==''){
                    $has_errors=true;
                    $errors[]=esc_html__( 'Please submit a title for your listing','wpestate');
                }

                if($prop_category=='' || $prop_category=='-1'){
                    $has_errors=true;
                    $errors[]=esc_html__( 'Please submit a category for your property','wpestate');
                }

                if($prop_action_category=='' || $prop_action_category=='-1'){
                    $has_errors=true;
                    $errors[]=esc_html__( 'Please chose a room type for your listing','wpestate');
                }
                
                if($property_city==''){
                    $has_errors=true;
                    $errors[]=esc_html__( 'Please submit a city for your listing','wpestate');
                }

                
                
                if($has_errors){
                    foreach($errors as $key=>$value){
                       $show_err.=$value.'</br>';
                    }
                    echo json_encode(array('edited'=>false, 'response'=>$show_err));
                }else{
                    $post = array(
                        'ID'            => $edit_id,
                        'post_title'    => $submit_title,
                        'post_type'     => 'estate_property',
                        'post_content'  =>  $submit_desc
                    );

                    $post_id =  wp_update_post($post );  
                    $prop_category                  =   get_term( $prop_category, 'property_category');
                    $prop_action_category           =   get_term( $prop_action_category, 'property_action_category');     

                    
                    if( isset($property_city) && $property_city!='none' && $property_city!='' ){
                        wp_set_object_terms($post_id,$property_city,'property_city'); 
                    } 
                    
                    if( isset($property_area) && $property_area!='none' ){
                        $property_area= wpestate_double_tax_cover($property_area,$property_city,$post_id);
                       // wp_set_object_terms($post_id,$property_area,'property_area'); 
                    }  
  
                    
                    if ( isset ($prop_action_category->name) ){
                        wp_set_object_terms($post_id,$prop_action_category->name,'property_action_category'); 
                    } 
                
                    if( isset($prop_category->name) ){
                        wp_set_object_terms($post_id,$prop_category->name,'property_category'); 
                    } 
                    
                    
                    if( isset($property_area) && $property_area!='none' && $property_area!=''){
                        $property_area_obj=   get_term_by('name', $property_area, 'property_area'); 
             
                        $t_id = $property_area_obj->term_id ;
                        $term_meta = get_option( "taxonomy_$t_id");
                        $allowed_html   =   array();
                        $term_meta['cityparent'] =  wp_kses( $property_city,$allowed_html);
                        $term_meta['pagetax'] = '';
                        $term_meta['category_featured_image '] = '';
                        $term_meta['category_tagline'] = '';
                        $term_meta['category_attach_id'] = '';

                        //save the option array
                         update_option( "taxonomy_$t_id", $term_meta );
               
                    }
                    
                    update_post_meta($post_id, 'guest_no', $guest_no);
                    update_post_meta($post_id, 'property_country', strtolower($property_country));
                    $property_admin_area                     =   str_replace(" ", "-", $property_admin_area);
                    $property_admin_area                     =   str_replace("\'", "", $property_admin_area);
                    update_post_meta($post_id, 'property_admin_area',strtolower( $property_admin_area) );
                    echo json_encode(array('edited'=>true, 'response'=>esc_html__( 'Changes are saved!','wpestate')));
                }
                die();
            }  
        }
    }
}
endif;