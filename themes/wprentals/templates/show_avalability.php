<?php
print '<h3 class="panel-title" id="listing_calendar">'.esc_html__( 'Availability', 'wpestate').'</h3>';
?>

 


<div class="all-front-calendars">
    <div class="all-front-calendars_headers"></div>
    
    <div id="calendar-next"><i class="fa fa-angle-right"></i></div> 
    <div id="calendar-prev"><i class="fa fa-angle-left"></i></div>

   

    
    <div class="separator"></div>

    <?php 
    $reservation_array  = get_post_meta($post->ID, 'booking_dates',true  ); 
        
    if(!is_array($reservation_array)){
        $reservation_array=array();
    }

    wpestate_get_calendar_custom_avalability ($reservation_array,true,true);
    ?>
    
    <div class="calendar-legend">
        <div class="calendar-legend-past"></div> <span> <?php esc_html_e('past','wpestate')?></span> 
        <div class="calendar-legend-today"></div> <span> <?php esc_html_e('today','wpestate')?></span> 
        <div class="calendar-legend-reserved"></div> <span> <?php esc_html_e('booked','wpestate')?></span> 
    </div>  

</div>














<?php

global $start_reservation;
global $end_reservation;
global $reservation_class;

$start_reservation  =   '' ;
$end_reservation    =   '';
$reservation_class  =   '';

function wpestate_get_calendar_custom_avalability($reservation_array,$initial = true, $echo = true) {
    global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
    $daywithpost =array();
    // week_begins = 0 stands for Sunday
	
        
    $time_now  = current_time('timestamp');
    $now=date('Y-m-d');
    $date = new DateTime();
    
    $thismonth = gmdate('m', $time_now);
    $thisyear  = gmdate('Y', $time_now);
                
    $unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
    $last_day = date('t', $unixmonth);

    $month_no=1;
        while ($month_no<13){
            
            wpestate_draw_month_front($month_no,$reservation_array, $unixmonth, $daywithpost,$thismonth,$thisyear,$last_day);
            
            $date->modify( 'first day of next month' );
            $thismonth=$date->format( 'm' );
            $thisyear  = $date->format( 'Y' );
            $unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
            $month_no++;
        }
       
	
}



function    wpestate_draw_month_front($month_no,$reservation_array, $unixmonth, $daywithpost,$thismonth,$thisyear,$last_day){
        global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
        global $start_reservation;
        global $end_reservation;
        global $reservation_class;

        $week_begins = intval(get_option('start_of_week'));
        
        
        $initial=true;
        $echo=true;
        
        $table_style='';
        if( $month_no>2 ){
               $table_style='style="display:none;"';
        }
        
        $calendar_output = '<div class="booking-calendar-wrapper" data-mno="'.$month_no.'" '.$table_style.'>
            <div class="month-title"> '. date_i18n("F", mktime(0, 0, 0, $thismonth, 10)).' '.$thisyear.' </div>
            <table class="wp-calendar booking-calendar">
	
	<thead>
	<tr>';

	$myweek = array();

	for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
		$myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
	}

	foreach ( $myweek as $wd ) {
		$day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
		$wd = esc_attr($wd);
		$calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
	}

	$calendar_output .= '
	</tr>
	</thead>
        <tbody>
	<tr>';

        
        
        

	
	// See how much we should pad in the beginning
	$pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
	if ( 0 != $pad )
		$calendar_output .= "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

	$daysinmonth = intval(date('t', $unixmonth));
        
        $reserverd_first    =   '';
        $reserved_last      =   '';
        
      
        
	for ( $day = 1; $day <= $daysinmonth; ++$day ) {
                $timestamp = strtotime( $day.'-'.$thismonth.'-'.$thisyear).' | ';
                $timestamp_java = strtotime( $day.'-'.$thismonth.'-'.$thisyear);
		if ( isset($newrow) && $newrow ){
                    $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
                }
                
		$newrow = false;
                $has_past_class='';
                if($timestamp_java < (time()-24*60*60)  ){
                    $has_past_class="has_past";
                }else{
                    $has_past_class="has_future";
                }
                $is_reserved=0;
                $reservation_class='';
                
		if ( $day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp')) ){
                    // if is today check for reservation
                    if(array_key_exists ($timestamp_java,$reservation_array) ){
                        $calendar_output .= '<td class="calendar-reserved calendar-today '.$has_past_class.' "     data-curent-date="'.$timestamp_java.'">';
                    }else{
                        $calendar_output .= '<td class="calendar-today '.$has_past_class.' "        data-curent-date="'.$timestamp_java.'">';
                    }
                    
                }
		else if(array_key_exists ($timestamp_java,$reservation_array) ){ // check for reservation
                    
                    $end_reservation=1;

                    if($start_reservation == 1){
                        $reservation_class  =   ' start_reservation';
                        $start_reservation  =   0;
                    }
                    
                 
     
                    $calendar_output .= '<td class="calendar-reserved '.$has_past_class.$reservation_class.' "     data-curent-date="'.$timestamp_java.'">';
                }
                else{// is not today and no resrvation
                    
                    $start_reservation=1;
        
                    if($end_reservation===1){
                        $reservation_class=' end_reservation ';
                        $end_reservation=0;
                    }
               
        
                    $calendar_output .= '<td class="calendar-free '.$has_past_class.$reservation_class.'"          data-curent-date="'.$timestamp_java.'">';
                }
                
               // print '</br> iteration from date ENDnd '.$end_reservation.' / Start  '.$start_reservation.' / '.$timestamp_java. ' / ' .date("Y-m-d", $timestamp_java);
                
                
                
                
		if ( in_array($day, $daywithpost) ) // any posts today?
				$calendar_output .= '<a href="' . get_day_link( $thisyear, $thismonth, $day ) . '" title="' . esc_attr( $ak_titles_for_day[ $day ] ) . "\">$day</a>";
		else
			$calendar_output .= $day;
		$calendar_output .= '</td>';

		if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
			$newrow = true;
	}

	$pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
	if ( $pad != 0 && $pad != 7 )
		$calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';

	$calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table></div>";

	

	if ( $echo ){
            echo apply_filters( 'get_calendar',  $calendar_output );
        }else{
            return apply_filters( 'get_calendar',  $calendar_output );
        }
}







?>