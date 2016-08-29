<?php
global $feature_list_array;
global $edit_id;
global $moving_array;
global $property_icalendar_import;
?>




<div class="col-md-12">
    <div class="user_dashboard_panel">
    <h4 class="user_dashboard_panel_title"><?php  esc_html_e('When is your listing available?','wpestate');?></h4>
    <div class="price_explaning"> <?php   esc_html_e('*(Click to select the period you wish to mark as booked for visitors.','wpestate');?></div>
    <div class="col-md-12" id="profile_message"></div>
   
   
    
    <div class="booking-calendar-wrapper-in-wrapper booking-calendar-set">
        
        <?php 
        
           // print_r( wpestate_get_booking_dates($edit_id));
        
            $reservation_array  = get_post_meta($edit_id, 'booking_dates',true  ); 
           // print_r($reservation_array);
            if(!is_array($reservation_array)){
                $reservation_array=array();
            }
         
            wpestate_get_calendar_custom2 ($reservation_array,true,true);
        ?>
        
        
        <div id="calendar-prev-internal-set" class="internal-calendar-left"><i class="fa fa-angle-left"></i></div>
        <div id="calendar-next-internal-set" class="internal-calendar-right"><i class="fa fa-angle-right"></i></div>
        <div style="clear: both;"></div>
    </div>

    <div class="col-md-12 calendar-actions">
        <div class="calendar-legend-today"></div><span><?php  esc_html_e('Today','wpestate');?></span>
        <div class="calendar-legend-reserved"></div><span><?php esc_html_e('Dates Booked','wpestate');?></span>
        
    </div>  
  
    <h4 class="user_dashboard_panel_title"><?php esc_html_e('Import/Export iCalendar feeds','wpestate'); ?> </h4>

    <div class="export_ical">
    <strong> <?php esc_html_e('This property iCalendar feed','wpestate'); ?> </strong>
    
    <?php 
    $unique_code_ical = get_post_meta($edit_id, 'unique_code_ica',true  );
    if($unique_code_ical==''){
        $unique_code_ical= md5(uniqid(mt_rand(), true));
        update_post_meta($edit_id, 'unique_code_ica', $unique_code_ical);
    }
    
    $icalendar_feed=wpestate_icalendar_feed();
    $icalendar_feed =  esc_url_raw ( add_query_arg( 'ical', $unique_code_ical, $icalendar_feed) ) ;
    print ': '. $icalendar_feed;
    
    ?>
    </div>
 
    <div class="import_ical">
         <div  id="profile_message2"></div>
    <p>
            <label for="property_icalendar_import"><?php esc_html_e('iCalendar import feed(feed will be read every 3 hours and when you hit save)','wpestate');?></label>
            <input type="text" id="property_icalendar_import" class="form-control" size="40" width="200" name="property_icalendar_import" value="<?php echo $property_icalendar_import;?>">
            <a href="" id="delete_imported_dates" data-edit-id="<?php echo $edit_id;?>"><?php esc_html_e('delete imported dates','wpestate');?></a>
    </p>
    <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_calendar" value="<?php esc_html_e('Save', 'wpestate') ?>">
    </div>
    
    

    <div class="col-md-12" style="display: inline-block;">  
        <input type="hidden" name="" id="listing_edit" value="<?php echo $edit_id;?>">
    </div>
</div>

    
    
 <!-- Modal -->
<div class="modal fade" id="owner_reservation_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header"> 
              <button type="button" id="close_reservation_internal" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h2 class="modal-title_big"><?php esc_html_e('Reserve a period','wpestate');?></h2>
              <h4 class="modal-title" id="myModalLabel"><?php esc_html_e('Mark dates as booked.','wpestate');?></h4>
            </div>

            <div class="modal-body">
             
                <div id="booking_form_request_mess_modal"></div>    
                
                <label for="start_date_owner_book"><?php esc_html_e('Check In','wpestate');?></label>
                <input type="text" id="start_date_owner_book" size="40" name="booking_from_date" class="form-control" value="" readonly>



                <label for="end_date_owner_book"><?php  esc_html_e('Check Out','wpestate');?></label>
                <input type="text" id="end_date_owner_book" size="40" name="booking_to_date" class="form-control" value="" readonly>
            

              
                <input type="hidden" id="property_id" name="property_id" value="" />
                <input name="prop_id" type="hidden"  id="agent_property_id" value="">
               

                <p class="full_form">
                    <label for="coment"><?php esc_html_e('Your notes','wpestate');?></label>
                    <textarea id="book_notes" name="booking_mes_mess" cols="50" rows="6" class="form-control"></textarea>
                </p>
                <button type="submit" id="book_dates" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button"><?php esc_html_e('Book Period','wpestate');?></button>

            </div><!-- /.modal-body -->

        
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    
    
    
    

<?php
global $start_reservation;
global $end_reservation;
global $reservation_class;

$start_reservation  =   '' ;
$end_reservation    =   '';
$reservation_class  =   '';

    function wpestate_get_calendar_custom2($reservation_array,$initial = true, $echo = true) {
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
            while ($month_no<12){

                wpestate_draw_month($month_no,$reservation_array, $unixmonth, $daywithpost,$thismonth,$thisyear,$last_day);

                $date->modify( 'first day of next month' );
                $thismonth=$date->format( 'm' );
                $thisyear  = $date->format( 'Y' );
                $unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
                $month_no++;
            }

    }



    function    wpestate_draw_month($month_no,$reservation_array, $unixmonth, $daywithpost,$thismonth,$thisyear,$last_day){
            global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
            global $start_reservation;
            global $end_reservation;
            global $reservation_class;
            $week_begins = intval(get_option('start_of_week'));


            $initial=true;
            $echo=true;

            $table_style='';
            if( $month_no>1 ){
                   $table_style='style="display:none;"';
            }

     /*       $calendar_output = '<div class="col-md-4 booking-calendar-wrapper-in internal-calendar" data-mno="'.$month_no.'" '.$table_style.'><table class="wp-calendar booking-calendar">
            <caption> '.$thismonth.'/'.$thisyear.' </caption>
            <thead>
            <tr>';
    */
            $calendar_output = '<div class="booking-calendar-wrapper-in col-md-12" data-mno="'.$month_no.'" '.$table_style.'>
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

            <tfoot>
            <tr>';

            $calendar_output .= '
            </tr>
            </tfoot>
            <tbody>
            <tr>';






            // See how much we should pad in the beginning
            $pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
            if ( 0 != $pad )
                    $calendar_output .= "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

            $daysinmonth = intval(date('t', $unixmonth));
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
                            $calendar_output .= '<td class="calendar-reserved '.$has_past_class.' "     data-curent-date="'.$timestamp_java.'">'. wpestate_draw_reservation($reservation_array[$timestamp_java]);
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
                    
                    
                        $calendar_output .= '<td class="calendar-reserved '.$has_past_class.$reservation_class.' "     data-curent-date="'.$timestamp_java.'">'. wpestate_draw_reservation($reservation_array[$timestamp_java]);
                    }
                    else{// is not today and no resrvation
                         
                        $start_reservation=1;

                        if($end_reservation===1){
                            $reservation_class=' end_reservation ';
                            $end_reservation=0;
                        }

                    
                        $calendar_output .= '<td class="calendar-free '.$has_past_class.$reservation_class.'"          data-curent-date="'.$timestamp_java.'">';
                    }






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

// wpestate_draw_reservation($reservation_array[$timestamp_java])

function wpestate_draw_reservation($reservation_note){
    //$reservation_array[$timestamp_java]

    if ( is_numeric($reservation_note)!=0){
        return '<div class="rentals_reservation" >'.esc_html__('Booking id','wpestate').': '.$reservation_note.'</div>';
    }else{
      
        if (strpos($reservation_note,'@') !== false) {
            $reservation_array=  explode('@', $reservation_note);
            return '<div class="rentals_reservation external_reservation">'.$reservation_array[1].'</div>';
        }else{
            return '<div class="rentals_reservation external_reservation">'.esc_html__('External Booking','wpestate').'</div>';
        }
     
       
    }
    
}






?>