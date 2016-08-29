<?php
// Template Name: ICAL FEED
// Wp Estate Pack

if( !isset($_GET['ical'])){
    exit('ouch');
}
$allowed_html=array();
$unique_ical_id=sanitize_text_field ( wp_kses($_GET['ical'],$allowed_html)  );

$post_id = wpestate_get_id_for_ical($unique_ical_id);
$ical = "BEGIN:VCALENDAR\n
PRODID;X-RICAL-TZSOURCE=TZINFO:-//WpRentals Hosting Calendar//EN\n
CALSCALE:GREGORIAN\n
VERSION:2.0 \n";
$ical.=wpestate_ical_get_booking_dates($post_id);
$ical.="
END:VCALENDAR";

header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=calendar.ics');
echo $ical;
exit;

function wpestate_get_id_for_ical($unique_ical_id){
    $args=array(
        'post_type'     => 'estate_property',
        'post_status'   => 'publish',
     
	'meta_query'    => array(
                        array(
                            'key'     => 'unique_code_ica',
                            'value'   => $unique_ical_id,
                            'compare' => '=',
                        )
                        ),
        );
    $prop_selection  =   new WP_Query($args);

    if ($prop_selection->have_posts()){    
        while ($prop_selection->have_posts()): $prop_selection->the_post();
            $pid            =   get_the_ID();
        endwhile;
    }else{
        exit();
    }

    wp_reset_query();
    wp_reset_postdata();
    return $pid;    
}



function wpestate_ical_get_booking_dates($listing_id){
    $ical_feed='';
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
    

    $booking_selection  =   new WP_Query($args);

    if ($booking_selection->have_posts()){    
        $ical_feed='';
        while ($booking_selection->have_posts()): $booking_selection->the_post();
            $pid            =   get_the_ID();
            $fromd          =   esc_html(get_post_meta($pid, 'booking_from_date', true));
            $tod            =   esc_html(get_post_meta($pid, 'booking_to_date', true));

            $from_date      =   new DateTime($fromd);
            $from_date_unix =   $from_date->getTimestamp();
            $to_date        =   new DateTime($tod);
            $to_date_unix   =   $to_date->getTimestamp();
            
            
           
            
            $ical_feed=$ical_feed.wpestate_ical_unit($from_date_unix,$to_date_unix,$pid);
            
        endwhile;
         
        wp_reset_query();
    }        
  
    return $ical_feed;
    
}
function dateToCal($timestamp) {
  return date('Ymd\THis\Z', $timestamp);
}
function escapeString($string) {
  return preg_replace('/([\,;])/','\\\$1', $string);
}

function wpestate_ical_unit($from_date,$to_date,$pid){
$name =$_SERVER['HTTP_HOST']." booking no ".$pid;

$ical_unit="BEGIN:VEVENT\n
DTEND:".dateToCal($to_date)."  \n   
UID:" . md5(uniqid(mt_rand(), true)) . "@".$_SERVER['HTTP_HOST']."\n
DTSTAMP:" .dateToCal(time())."\n
SUMMARY:".escapeString($name)."\n
DTSTART:".dateToCal($from_date)."\n
END:VEVENT\n";
return $ical_unit;

}
?>