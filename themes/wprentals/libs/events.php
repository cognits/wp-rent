<?php
/////////////////////////////////////////////////////////////////////////////////////////////////
//// add weekly interval
/////////////////////////////////////////////////////////////////////////////////////////////////
add_filter( 'cron_schedules', 'wpestate_add_weekly_cron_schedule',1 );

if( !function_exists('wpestate_add_weekly_cron_schedule') ): 
    function wpestate_add_weekly_cron_schedule( $schedules ) {
        $schedules['weekly'] = array(
            'interval' => 604800, // 1 week in seconds
            'display'  => esc_html__(  'Once Weekly','wpestate'),
        );

        $schedules['hourlythree'] = array(
            'interval' => 10800, // 3 hours
            'display'  => esc_html__(  'Every 3 hours','wpestate'),
        );

      
	return $schedules;
    }
endif;






if(!function_exists('wpestate_create_auto_data')):
function wpestate_create_auto_data(){

    if ( !wp_next_scheduled( 'event_wp_estate_create_auto' ) ) {
        wp_schedule_event( time(), 'daily', 'event_wp_estate_create_auto');
    }
}
endif;


add_action( 'event_wp_estate_create_auto', 'event_wp_estate_create_auto_function' );


if( !function_exists('event_wp_estate_create_auto_function') ): 
function event_wp_estate_create_auto_function(){
    $show_adv_search_general            =   get_option('wp_estate_wpestate_autocomplete','');
    if($show_adv_search_general=='no'){
        $availableTags='';
        $show_empty_city_status= esc_html ( get_option('wp_estate_show_empty_city','') );
        
        if ( $show_empty_city_status=='no' ){
            $args = array(
                'orderby' => 'count',
                'hide_empty' => 1,
            ); 
        }else{
            $args = array(
                'orderby' => 'count',
                'hide_empty' => 0,
            ); 
        }
   

        $terms = get_terms( 'property_city', $args );
        foreach ( $terms as $term ) {
            $availableTags.= ' { label: "'.$term->name.'", category: "tax" },';
        }

        $terms = get_terms( 'property_area', $args );
        foreach ( $terms as $term ) {
          $availableTags.= ' { label: "'.$term->name.'", category: "tax" },';
        }

        $country    = get_meta_values('property_country');
        foreach ( $country as $term ) {
          $availableTags.= ' { label: "'.$term.'", category: "meta" },';
        }

        $state      = get_meta_values('property_state');
        foreach ( $state as $term ) {
          $availableTags.= ' { label: "'.$term.'", category: "meta" },';
        }

        $conty      = get_meta_values('property_county');
        foreach ( $conty as $term ) {
          $availableTags.= ' { label: "'.$term.'", category: "meta" },';
        }
    }
    
    update_option('wpestate_autocomplete_data',$availableTags);
    
}
endif;


function get_meta_values( $key = '', $type = 'estate_property', $status = 'publish' ) {
    global $wpdb;

    if( empty( $key ) )
        return;

    $r = $wpdb->get_col( $wpdb->prepare( "
        SELECT DISTINCT  pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '%s' 
        AND p.post_status = '%s' 
        AND p.post_type = '%s'
    ", $key, $status, $type ) );
    return $r;
}













//wp_clear_scheduled_hook('event_wp_estate_sync_ical');
if( !function_exists('setup_event_wp_estate_sync_ical') ): 
    function setup_event_wp_estate_sync_ical() {
            if ( ! wp_next_scheduled( 'event_wp_estate_sync_ical' ) ) {
                    wp_schedule_event( time(), 'hourlythree', 'event_wp_estate_sync_ical');
            }
    }
endif;
setup_event_wp_estate_sync_ical();
add_action( 'event_wp_estate_sync_ical', 'wp_estate_sync_ical' );



if( !function_exists('wp_estate_sync_ical') ): 
function wp_estate_sync_ical(){
    $args = array(
            'post_type'         =>  'estate_property',
            'post_status'       =>  'published',
            'posts_per_page'    =>  -1,
            'meta_query' => array(
                            array(
                                'key'       => 'property_icalendar_import',
                                'value'     => '',
                                'compare'   => '!='
                            )
                           
                        )
        );

        
        $prop_selection =   new WP_Query($args);

        if ($prop_selection->have_posts()){  
            while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                $post_id=get_the_id();
                print '</br>SYNC FOR '.$post_id.' '.get_the_title() ;
                  wpestate_import_calendar_feed_listing($post_id);
            endwhile;
        }
}

endif;






if( !function_exists('setup_wp_estate_delete_orphan_lists') ): 
    function setup_wp_estate_delete_orphan_lists() {
            if ( ! wp_next_scheduled( 'prefix_wp_estate_delete_orphan_lists' ) ) {
                    wp_schedule_event( time(), 'daily', 'prefix_wp_estate_delete_orphan_lists');
            }
    }
endif;
//setup_wp_estate_delete_orphan_lists();
add_action( 'prefix_wp_estate_delete_orphan_lists', 'wp_estate_delete_orphan_lists' );





if( !function_exists('wp_estate_delete_orphan_lists') ): 
function wp_estate_delete_orphan_lists(){
    $args = array(
            'post_type'         => 'estate_property',
            'post_status'       =>'any',
            'orderby'           => 'ID',
            'order'             => 'DESC',
             'author__in' => array( 0 ) 
            

        );
        $prop_selection =   new WP_Query($args);

        if ($prop_selection->have_posts()){  
            while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                $post_id=get_the_id();
                $author_id=wpsestate_get_author($post_id);
                //print '</br>'.$post_id .' /'.$author_id.'/ '.get_the_title();
                if ( $author_id==0 ){
                     // print '</br> DELETE'.$post_id .' /';
                     wp_delete_post($post_id);
                }
            endwhile;
        }
}

endif;





/////////////////////////////////////////////////////////////////////////////////////////////////
//// schedule user_checks
/////////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wp_estate_schedule_user_check') ): 
    function wp_estate_schedule_user_check(){
        $paid_submission_status    = esc_html ( get_option('wp_estate_paid_submission','') );
        if($paid_submission_status == 'membership' ){
            //  wpestate_check_user_membership_status_function();
            wp_clear_scheduled_hook('wpestate_check_for_users_event');
            wpestate_setup_daily_user_schedule();  
        }
    }
endif;

/////////////////////////////////////////////////////////////////////////////////////////////////
//// schedule daily USER check
/////////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_setup_daily_user_schedule') ): 
    function  wpestate_setup_daily_user_schedule(){
        if ( ! wp_next_scheduled( 'wpestate_check_for_users_event' ) ) {
            //daily
            wp_schedule_event( time(), 'twicedaily', 'wpestate_check_for_users_event');
        }
    }
endif;
add_action( 'wpestate_check_for_users_event', 'wpestate_check_user_membership_status_function' );




/////////////////////////////////////////////////////////////////////////////////////////////////
//// schedule daily pin generation
/////////////////////////////////////////////////////////////////////////////////////////////////

//add_action( 'wp', 'setup_wpestate_cron_generate_pins_daily' );

if( !function_exists('setup_wpestate_cron_generate_pins_daily') ): 
    function setup_wpestate_cron_generate_pins_daily() {
            if ( ! wp_next_scheduled( 'prefix_wpestate_cron_generate_pins_daily' ) ) {
                    wp_schedule_event( time(), 'daily', 'prefix_wpestate_cron_generate_pins_daily');
            }
    }
endif;
setup_wpestate_cron_generate_pins_daily();
add_action( 'prefix_wpestate_cron_generate_pins_daily', 'wpestate_cron_generate_pins' );



if( !function_exists('wpestate_cron_generate_pins') ): 
    function wpestate_cron_generate_pins(){
        if ( get_option('wp_estate_readsys','') =='yes' ){

            $path=wpestate_get_pin_file_path_write();
            if ( file_exists ($path) && is_writable ($path) ){
                wpestate_listing_pins();
            }

        }
    }
endif;







/////////////////////////////////////////////////////////////////////////////////////////////////
//// schedule daily event
/////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_setup_daily_schedule') ): 
    function  wpestate_setup_daily_schedule(){
        $schedule =   get_option('wpestate_cron_saved_search',true);
        if ( ! wp_next_scheduled( 'wpestate_check_for_new_listings' ) && $schedule!='daily'  ) {
            //daily
            wp_clear_scheduled_hook('wpestate_check_for_new_listings_event');
            wp_schedule_event( time(), 'daily', 'wpestate_check_for_new_listings_event');
            update_option('wpestate_cron_saved_search','daily');
        }
    }
endif;







/////////////////////////////////////////////////////////////////////////////////
// convert object to array
/////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_objectToArray') ): 
    function wpestate_objectToArray ($object) {
        if(!is_object($object) && !is_array($object))
            return $object;

        return array_map('objectToArray', (array) $object);
    }
endif;





function wp_estate_enable_load_exchange(){
     if ( ! wp_next_scheduled( 'wpestate_load_exchange_action' ) ) {
        //daily
        wp_schedule_event( time(), 'daily', 'wpestate_load_exchange_action');
        }
}
add_action( 'wpestate_load_exchange_action', 'estate_parse_curency' );




function estate_parse_curency(){
   $currency_symbol                =   esc_html( get_option('wp_estate_currency_symbol') );
   
   print $curency_list=estate_get_currency_values();
    $xml = simplexml_load_file($curency_list) or die("Exchange feed not loading!"); //<-- Load the XML file into PHP variable.
    $no_curr = count($xml->results->rate);
   
    $exchange = array(); 
    for($i=0; $i<$no_curr; $i++): 
        $name = (string)$xml->results->rate[$i]->Name; 
        $name=  str_replace($currency_symbol.'/','',$name);
        $rate = (string)$xml->results->rate[$i]->Rate; 
        $exchange[$name] = $rate; 
    endfor; 
    
  
    
    $custom_fields = get_option( 'wp_estate_multi_curr', true);    
      

    
    $i=0;
    if( !empty($custom_fields)){    
        while($i< count($custom_fields) ){
            $symbol=$custom_fields[$i][0];
            if ( isset($exchange[$symbol]) ){
              $custom_fields[$i][2]=  $exchange[$symbol];
            }  
            $i++;
        }
    }
    
 
    update_option( 'wp_estate_multi_curr', $custom_fields ); 
}

function estate_get_currency_values(){
    $custom_fields = get_option( 'wp_estate_multi_curr', true);    
    $i=0;
    $currency_list='(';    
    $currency_symbol                =   esc_html( get_option('wp_estate_currency_symbol') );
   
    if( !empty($custom_fields)){    
        while($i< count($custom_fields) ){
            $currency_list.= '"'.$currency_symbol.$custom_fields[$i][0].'",';
             
            $i++;
        }
    }
    $currency_list= rtrim($currency_list, ",");
    $currency_list.=")";
 
    
  
    $link='http://query.yahooapis.com/v1/public/yql?q=select * from yahoo.finance.xchange where pair in '.$currency_list;
    $link.='&env=store://datatables.org/alltableswithkeys';
    return $link;
}

?>