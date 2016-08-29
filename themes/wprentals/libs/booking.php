<?php
// register the custom post type
add_action( 'after_setup_theme', 'wpestate_reate_booking_type' );

if( !function_exists('wpestate_reate_booking_type') ):

function wpestate_reate_booking_type() {
register_post_type( 'wpestate_booking',
		array(
			'labels' => array(
				'name'          => esc_html__(  'Bookings','wpestate'),
				'singular_name' => esc_html__(  'Booking','wpestate'),
				'add_new'       => esc_html__( 'Add New Booking','wpestate'),
                'add_new_item'          =>  esc_html__( 'Add booking','wpestate'),
                'edit'                  =>  esc_html__( 'Edit' ,'wpestate'),
                'edit_item'             =>  esc_html__( 'Edit booking','wpestate'),
                'new_item'              =>  esc_html__( 'New booking','wpestate'),
                'view'                  =>  esc_html__( 'View','wpestate'),
                'view_item'             =>  esc_html__( 'View booking','wpestate'),
                'search_items'          =>  esc_html__( 'Search booking','wpestate'),
                'not_found'             =>  esc_html__( 'No bookings found','wpestate'),
                'not_found_in_trash'    =>  esc_html__( 'No bookings found','wpestate'),
                'parent'                =>  esc_html__( 'Parent booking','wpestate')
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'bookings'),
		'supports' => array('title', 'editor'),
		'can_export' => true,
		'register_meta_box_cb' => 'wpestate_add_bookings_metaboxes',
                'menu_icon'=> get_template_directory_uri().'/img/book.png',
                'exclude_from_search'   => true
		)
	);
}
endif; // end   wpestate_reate_booking_type  


////////////////////////////////////////////////////////////////////////////////////////////////
// Add booking metaboxes
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_add_bookings_metaboxes') ):
function wpestate_add_bookings_metaboxes() {	
  add_meta_box(  'estate_booking-sectionid', esc_html__(  'Booking Details', 'wpestate' ), 'wpestate_booking_meta_function', 'wpestate_booking' ,'normal','default');
}
endif; // end   



////////////////////////////////////////////////////////////////////////////////////////////////
// booking details
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_booking_meta_function') ):
function wpestate_booking_meta_function( $post ) {
    wp_nonce_field( plugin_basename( __FILE__ ), 'estate_booking_noncename' );
    global $post;
    
    $option_status='';
    $status_values = array(
                        'confirmed',
                        'pending'
                        );
    
     $status_type = get_post_meta($post->ID, 'booking_status', true);

     foreach ($status_values as $value) {
         $option_status.='<option value="' . $value . '"';
         if ($value == $status_type) {
             $option_status.='selected="selected"';
         }
         $option_status.='>' . $value . '</option>';
     }
    // print   ' owner id '.get_post_meta($post->ID, 'owner_id', true);
    $property_id = esc_html(get_post_meta($post->ID, 'booking_id', true)); 
     
    print'
    <p class="meta-options">
        <label for="booking_listing_name">'.esc_html__( 'Booking Status:','wpestate').' </label>
        '.get_post_meta($post->ID, 'booking_status', true).'
    </p>
    
    <p class="meta-options">
        <label for="booking_listing_name">'.esc_html__( 'Booking Invoice:','wpestate').' </label>
        '.get_post_meta($post->ID, 'booking_invoice_no', true).'
    </p>
    
    <p class="meta-options">
        <label for="booking_from_date">'.esc_html__( 'Check In:','wpestate').' </label><br />
        <input type="text" id="booking_from_date" size="58" name="booking_from_date" value="'.  esc_html(get_post_meta($post->ID, 'booking_from_date', true)).'">
    </p>
    
    <p class="meta-options">
        <label for="booking_to_date">'.esc_html__( 'Check Out:','wpestate').' </label><br />
        <input type="text" id="booking_to_date" size="58" name="booking_to_date" value="'.  esc_html(get_post_meta($post->ID, 'booking_to_date', true)).'">
    </p>

    <p class="meta-options">
        <label for="booking_id">'.esc_html__( 'Property ID:','wpestate').' </label><br />
        <input type="text" id="booking_id" size="58" name="booking_id" value="'.  $property_id.'">
    </p>
   
    <p class="meta-options">
        <label for="booking_guests">'.esc_html__( 'Guests No:','wpestate').' </label><br />
        <input type="text" id="booking_guests" size="58" name="booking_guests" value="'.  esc_html(get_post_meta($post->ID, 'booking_guests', true)).'">
    </p>
    
    <p class="meta-options">
        <label for="booking_status">'.esc_html__( 'Property Name:','wpestate').' </label><br /> 
        <select id="booking_listing_name" name="booking_listing_name">
            '.wpestate_get_property_list($property_id).'
        </select>   
    </p>
    
    <p class="meta-options">
        <label for="booking_listing_name">'.esc_html__( 'Booking Status:','wpestate').' </label><br />
        <select id="booking_status" name="booking_status">
            '.$option_status.' 
        </select>   
    </p>
     
  
    
    ';     
  

    print '<script type="text/javascript">
                    //<![CDATA[
                    jQuery(document).ready(function(){
                        '.wpestate_date_picker_translation("#booking_from_date").'
                    });
                    //]]>
                    </script>';
    print '<script type="text/javascript">
                  //<![CDATA[
                  jQuery(document).ready(function(){
                        '.wpestate_date_picker_translation("#booking_to_date").'
                  });
                  //]]>
                  </script>';

}
endif; // end   estate_booking  





/////////////////////////////////////////////////////////////////////////////////////////////////////////
// property list function
/////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_property_list') ):
function wpestate_get_property_list($selected) {
    global $post;
  
    $return_string='';
    $args=array(
        'post_type'        => 'estate_property',
        'post_status'      => 'any',
         'posts_per_page'   => -1,
        );
    
    $prop_selection = new WP_Query($args);
     
    if ($prop_selection->have_posts()){    
        while ($prop_selection->have_posts()): $prop_selection->the_post();
            $return_string.='<option value="'.$post->ID.'"';
            if($selected==$post->ID){
                $return_string.=' selected="selected" ';
            }
            $return_string.='>'.get_the_title().'</option>';
        endwhile;
    }
    
    wp_reset_query();
    
    return $return_string;
}
endif;

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// property list function
/////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_property_list_from_user') ):
function wpestate_get_property_list_from_user($user_id) {
    global $post;
    $selected=  get_post_meta($post->ID,'booking_listing_name',true);
    $return_string='';
    $args=array(
        'post_type'        => 'estate_property',
        'post_status'      => 'publish',
        'posts_per_page'   => -1,
        'author'      => $user_id
        );
    
    $prop_selection = new WP_Query($args);
     
    if ($prop_selection->have_posts()){    
        while ($prop_selection->have_posts()): $prop_selection->the_post();
            $return_string.='<option value="'.$post->ID.'"';
            if($selected==$post->ID){
                $return_string.=' selected="selected" ';
            }
            $return_string.='>'.get_the_title().'</option>';
        endwhile;
    }
    
    wp_reset_query();
    
    return $return_string;
}
endif;

////////////////////////////////////////////////////////////////////////////////
// custom action on save
////////////////////////////////////////////////////////////////////////////////


add_action('save_post', 'estate_save_booking_postdata', 99);
if( !function_exists('estate_save_booking_postdata') ):
    function estate_save_booking_postdata($post_id) {
        global $post;   
        if(!is_object($post) || !isset($post->post_type)) {
            return;
        }

        if($post->post_type!='wpestate_booking'){
            return;    
        }

        $curent_listng_id= get_post_meta($post_id, 'booking_id',true);
  
        if($curent_listng_id ==''){
            $selected= $curent_listng_id= get_post_meta($post->ID,'booking_listing_name',true);
            update_post_meta($post_id, 'booking_id', $selected  ); 
        } 
        
        // save booking dates;
        $reservation_array = wpestate_get_booking_dates($curent_listng_id);
        update_post_meta($curent_listng_id, 'booking_dates', $reservation_array); 
        
        
        
    }
endif;


////////////////////////////////////////////////////////////////////////////////
// save array with bookng dates
////////////////////////////////////////////////////////////////////////////////
if (!function_exists("wpestate_get_booking_dates")):
function wpestate_get_booking_dates($listing_id){
    $args=array(
        'post_type'        => 'wpestate_booking',
        'post_status'      => 'any',
        'posts_per_page'   => -1,
        'meta_query' => array(
                            array(
                                'key'       => 'booking_id',
                                'value'     => $listing_id,
                                'type'      => 'NUMERIC',
                                'compare'   => '='
                            ),
                            array(
                                'key'       =>  'booking_status',
                                'value'     =>  'confirmed',
                                'compare'   =>  '='
                            )
                        )
        );
    
    $reservation_array = get_post_meta($listing_id, 'booking_dates',true);
    
    if( !is_array($reservation_array) || $reservation_array=='' ){
        $reservation_array  =   array();
    }
    
    $booking_selection  =   new WP_Query($args);
    $now=time();
    $daysago = $now-3*24*60*60;
  
    if ($booking_selection->have_posts()){    

        while ($booking_selection->have_posts()): $booking_selection->the_post();
            $pid            =   get_the_ID();
            
            $fromd          =   esc_html(get_post_meta($pid, 'booking_from_date', true));
            $tod            =   esc_html(get_post_meta($pid, 'booking_to_date', true));
            $unix_time_start = strtotime ($fromd);
            
            if ($unix_time_start > $daysago){ // add booking from 3 days ago 
                $from_date      =   new DateTime($fromd);
                $from_date_unix =   $from_date->getTimestamp();
                $to_date        =   new DateTime($tod);
                $to_date_unix   =   $to_date->getTimestamp();

                //$reservation_array[]=$from_date_unix;
                $reservation_array[$from_date_unix]=$pid;

                // $from_date->modify('tomorrow');
                $from_date_unix =   $from_date->getTimestamp();

               //print ' from date'.$from_date_unix.'  ---  to date.'.$to_date_unix.' - '.date("Y-m-d", $from_date_unix).' --- '.date("Y-m-d", $to_date_unix).'</br>';

                while ($from_date_unix < $to_date_unix){
                //  print '</br> iteration from date'.$from_date_unix. ' / ' .date("Y-m-d", $from_date_unix);
                //  $reservation_array[]=$from_date_unix;
                    $reservation_array[$from_date_unix]=$pid;

                    $from_date->modify('tomorrow');
                    $from_date_unix =   $from_date->getTimestamp();
                }          
            }
        endwhile;
          // print_r($reservation_array);
        wp_reset_query();
    }        
  
    return $reservation_array;
    
}

endif;




add_filter( 'manage_edit-wpestate_booking_columns', 'wpestate_my_booking_columns' );

if( !function_exists('wpestate_my_booking_columns') ):
    function wpestate_my_booking_columns( $columns ) {
        $slice=array_slice($columns,2,2);
        unset( $columns['comments'] );
        unset( $slice['comments'] );
        $splice=array_splice($columns, 2);   
        $columns['booking_estate_period']   = esc_html__( 'Period','wpestate');
        $columns['booking_estate_listing']  = esc_html__( 'Listing','wpestate');
        $columns['booking_estate_owner']    = esc_html__( 'Owner','wpestate');
        $columns['booking_estate_renter']   = esc_html__( 'Renter','wpestate');
        return  array_merge($columns,array_reverse($slice));
    }
endif; // end   wpestate_my_columns  


add_action( 'manage_posts_custom_column', 'wpestate_populate_booking_columns' );
if( !function_exists('wpestate_populate_booking_columns') ):
    function wpestate_populate_booking_columns( $column ) {
        $the_id=get_the_ID();
        if(  'booking_estate_period' == $column){
            echo esc_html__( 'from','wpestate').' '.esc_html(get_post_meta($the_id, 'booking_from_date', true)).' '.esc_html__( 'to','wpestate').' '. esc_html(get_post_meta($the_id, 'booking_to_date', true));
        }
        
        if(  'booking_estate_listing' == $column){
            $curent_listng_id= get_post_meta($the_id, 'booking_id',true);
            echo get_the_title($curent_listng_id);
        }
        
        if(  'booking_estate_owner' == $column){
            $owner_id = get_post_meta($the_id, 'owner_id', true);
            $user = get_user_by( 'id', $owner_id );
            echo $user->user_login;
        }
        
        if(  'booking_estate_renter' == $column){
            echo $author             =   get_the_author();
        }
    }
endif;



add_action(  'wp_trash_post', 'wpestate_delete_booking_from_admin',10 );
function wpestate_delete_booking_from_admin( $postid ){

    global $post_type;   
    if ( $post_type == 'wpestate_booking' ) {
       
        if( !is_admin() ){
            exit('ko');
        }
        
        $bookid         =   $postid;  
        $listing_id     =   get_post_meta($postid, 'booking_id', true);    
        $invoice_id     =   get_post_meta($bookid, 'booking_invoice_no', 'true');
        
        if($listing_id==0 || $bookid==0 ){
            exit('buh');
        }
        
        $the_post= get_post( $listing_id); 
       
            
        $user_id           =   wpse119881_get_author($bookid);
        $receiver          =   get_userdata($user_id);
        $receiver_email    =   $receiver->user_email;
        $receiver_name     =   $receiver->user_login;
         
        $reservation_array      =   wpestate_get_booking_dates($listing_id);
        
        foreach($reservation_array as $key=>$value){
            if ($value == $bookid){
               unset($reservation_array[$key]);
            }
        }
        
        update_post_meta($listing_id, 'booking_dates', $reservation_array); 
  
        if($invoice_id!=''){
            wp_delete_post($invoice_id);
        }

         
    }
        


}
?>