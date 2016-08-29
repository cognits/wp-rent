<?php
/////////////////////////////////////////////////////////////////////////////////////////
///// strip words
/////////////////////////////////////////////////////////////////////////////////////////

if(!function_exists('wpestate_curency_submission_pick')):
function wpestate_curency_submission_pick(){
    $submission_curency = esc_html( get_option('wp_estate_submission_curency_custom', '') );
    if($submission_curency == ''){
        $submission_curency = esc_html( get_option('wp_estate_submission_curency', '') );
    }
    return $submission_curency;
    
}
endif;



function wpml_custom_price_adjust($post_id){
    $return =   get_post_meta($post_id, 'custom_price',true );
    //print_r($return);
    //print 'xxxxxxxxxxxx';
    if(!$return){
        $return=  get_post_meta($post_id, 'custom_price'.$post_id,true );
    }
    // print_r($return);
    return $return;
}


function wpml_mega_details_adjust($post_id){
    $return =  get_post_meta($post_id, 'mega_details',true );
    //print_r($return);
    //print 'xxxxxxxxxxxx';
    if(!$return){
        $return=   get_post_meta($post_id, 'mega_details'.$post_id,true );
    }
    // print_r($return);
    return $return;
}


function wpml_custom_price_adjust_save($post_id,$price_array){
    $old_custom =  get_post_meta($post_id, 'custom_price'.$post_id,true );
    if($old_custom!=''){
        update_post_meta($post_id, 'custom_price'.$post_id,$price_array );
    }
}

function wpml_mega_details_adjust_save($post_id,$price_array){
    $old_custom =  get_post_meta($post_id, 'mega_details'.$post_id,true );
    if($old_custom!=''){
        update_post_meta($post_id, 'mega_details'.$post_id,$price_array );
    }
}



if( !function_exists('wpestate_the_excerpt_max_charlength') ):
function wpestate_the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_content();
	$charlength++;
        $return='';
        
	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			$return= mb_substr( $subex, 0, $excut );
		} else {
			$return= $subex;
		}
		$return.= '[...]';
	} else {
		$return = $excerpt;
	}
        return $return;
}
endif;

if( !function_exists('wpestate_strip_words') ):
    function wpestate_strip_words($text, $words_no) {
        $temp = explode(' ', $text, ($words_no + 1));
        if (count($temp) > $words_no) {
            array_pop($temp);
        }
        return implode(' ', $temp);
    }
endif; // end   wpestate_strip_words 





if( !function_exists('wpestate_show_product_type')):
    function wpestate_show_product_type($item_id){
       return get_the_title($item_id);
        
    }
endif;    


if( !function_exists('wpestate_custom_vimdeo_video') ):
    function wpestate_custom_vimdeo_video($video_id) {
        $protocol = is_ssl() ? 'https' : 'http';
        return $return_string = '
            <div style="max-width:100%;" class="video">
               <iframe id="player_1" src="'.$protocol.'://player.vimeo.com/video/' . $video_id . '?api=1&amp;player_id=player_1"      allowFullScreen></iframe>
            </div>';

    }
endif; // end     


if( !function_exists('wpestate_custom_youtube_video') ):
function  wpestate_custom_youtube_video($video_id){
    $protocol = is_ssl() ? 'https' : 'http';
    return $return_string='
        <div style="max-width:100%;" class="video">
            <iframe id="player_2" title="YouTube video player" src="'.$protocol.'://www.youtube.com/embed/' . $video_id  . '?wmode=transparent&amp;rel=0"  ></iframe>
        </div>';

}
endif; // end     


if( !function_exists('get_video_thumb') ): 
    function get_video_thumb($post_id){
        $video_id    = esc_html( get_post_meta($post_id, 'embed_video_id', true) );
        $video_type = esc_html( get_post_meta($post_id, 'embed_video_type', true) );
        $protocol = is_ssl() ? 'https' : 'http';
        if($video_type=='vimeo'){
             $hash2 = ( wp_remote_get($protocol."://vimeo.com/api/v2/video/$video_id.php") );
             $pre_tumb=(unserialize ( $hash2['body']) );
             $video_thumb=$pre_tumb[0]['thumbnail_medium'];                                        
        }else{
            $video_thumb = $protocol.'://img.youtube.com/vi/' . $video_id . '/0.jpg';
        }
        return $video_thumb;
    }
endif;



if( !function_exists('wpestate_review_composer')):
    function wpestate_review_composer($agent_id){
        global $post;
  
        $owner_id       =   get_post_meta($agent_id, 'user_agent_id', true);
        if( $owner_id==0){
            $return_array['list_rating'] = 0;
            $return_array['coments_no'] = 0;
            $return_array['prop_selection']='';
            $return_array['templates'] ='';
            return $return_array;
        }
        $post_array     =   array();
        $post_array[]   =   0;
        $return_array   =   array();
        $args = array(
            'post_type'         =>  'estate_property',
            'author'            =>  $owner_id,
            'paged'             =>  1,
            'posts_per_page'    => -1,
            'post_status'       => 'publish'
        );

       
        $prop_selection =   new WP_Query($args);
        $return_array['prop_selection']=$prop_selection;
        if ( $prop_selection->have_posts() ) {
            while ($prop_selection->have_posts()): 
                $prop_selection->the_post();                     
        
                $post_array[]=$post->ID;
            endwhile;

            $args = array(
                'number' => '15',
                'post__in' => $post_array,
            );
            

            $comments   =   get_comments($args);
            $coments_no =   0;
            $stars_total=   0;
            $review_templates='';

            foreach($comments as $comment) :
                $coments_no++;
                $userId=$comment->user_id;
                $userid_agent=get_user_meta($userId, 'user_agent_id', true);
                $reviewer_name=get_the_title($userid_agent);

                $thumb_id           = get_post_thumbnail_id($userid_agent);
                $preview            = wp_get_attachment_image_src($thumb_id, 'thumbnail');
                $preview_img         = $preview[0];
                if($preview_img==''){
                    $preview_img    =   get_template_directory_uri().'/img/default_user_agent.gif';
                }

                $rating= get_comment_meta( $comment->comment_ID , 'review_stars', true );
                $stars_total+=$rating;
                $review_templates.='
                    <div class="listing-review">
                       
                        <div class="col-md-12 review-list-content norightpadding">
                            <div class="reviewer_image"  style="background-image: url('.$preview_img.');"></div>
                            <div class="reviwer-name">'.$reviewer_name.'</div>
                            <div class="property_ratings">';

                                $counter=0; 
                                    while($counter<5){
                                        $counter++;
                                        if( $counter<=$rating ){
                                            $review_templates.=' <i class="fa fa-star"></i>';
                                        }else{
                                           $review_templates.=' <i class="fa fa-star-o"></i>'; 
                                        }

                                    }
                            $review_templates.=' <span class="ratings-star">('. $rating.' ' .esc_html__( 'of','wpestate').' 5)</span> 
                            </div>


                            <div class="review-content">
                                '. $comment->comment_content .'

                                <div class="review-date">
                                '.esc_html__( 'Posted on ','wpestate' ). ' '. get_comment_date('j F Y',$comment->comment_ID).' 
                                </div>
                            </div>



                        </div>
                    </div>   ';

            endforeach;
            
            $return_array['templates'] = $review_templates;
            $list_rating=0;
            if($coments_no>0){
                $list_rating= ceil($stars_total/$coments_no);
            }

            
            $return_array['list_rating'] = $list_rating;
            $return_array['coments_no'] = $coments_no;
           
        }// if has listings

    
    
    
    
    return $return_array;
    }
endif;





/////////////////////////////////////////////////////////////////////////////////
// header type
///////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_show_media_header')):
    function wpestate_show_media_header($tip, $global_header_type,$header_type,$rev_slider,$custom_image){
        if( $tip=='global' ){
            switch ($global_header_type) {
                case 0://image
                    break;
                case 1://image
                    $global_header  =   get_option('wp_estate_global_header','');
                    print '<img src="'.$global_header.'"  class="img-responsive headerimg" alt="header_image"/>';
                    break;
                case 2://theme slider
                    wpestate_present_theme_slider();
                    break;
                case 3://revolutin slider
                     $global_revolution_slider   =  get_option('wp_estate_global_revolution_slider','');
                     putRevSlider($global_revolution_slider);
                    break;
                case 4://google maps
                    get_template_part('templates/google_maps_base'); 
                    break;
            } 
        }else{ // is local
            switch ($header_type) {
                case 1://none
                    break;
                case 2://image
                    print '<img src="'.$custom_image.'"  class="img-responsive" alt="header_image"/>';
                    break;
                case 3://theme slider
                    wpestate_present_theme_slider();
                    break;
                case 4://revolutin slider
                    putRevSlider($rev_slider);
                    break;
                case 5://google maps
                    get_template_part('templates/google_maps_base'); 
                    break;
              }  
        }
        
        
       
    }
endif;


/////////////////////////////////////////////////////////////////////////////////
// datepcker_translate
///////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_booking_price')):
    function wpestate_booking_price($curent_guest_no,$invoice_id, $property_id, $from_date, $to_date){
    
        $price_array                =   wpml_custom_price_adjust($property_id);    
        $mega                       =   wpml_mega_details_adjust($property_id);
        $cleaning_fee_per_day       =   floatval   ( get_post_meta($property_id,  'cleaning_fee_per_day', true) );
        $city_fee_per_day           =   floatval   ( get_post_meta($property_id, 'city_fee_per_day', true) );
        $price_per_weekeend         =   floatval   ( get_post_meta($property_id, 'price_per_weekeend', true) );  
        $setup_weekend_status       =   esc_html ( get_option('wp_estate_setup_weekend','') );
        $booking_from_date  =  $from_date;
        $booking_to_date    =  $to_date;
       
        $numberDays=1;
        if( $invoice_id == 0){
            $price_per_day      =   floatval(get_post_meta($property_id, 'property_price', true));
            $week_price         =   floatval(get_post_meta($property_id, 'property_price_per_week', true));
            $month_price        =   floatval(get_post_meta($property_id, 'property_price_per_month', true));
            $cleaning_fee       =   floatval(get_post_meta($property_id, 'cleaning_fee', true));
            $city_fee           =   floatval(get_post_meta($property_id, 'city_fee', true));
            
        }else{
            $price_per_day      =   floatval(get_post_meta($invoice_id, 'default_price', true));
            $week_price         =   floatval(get_post_meta($invoice_id, 'week_price', true));
            $month_price        =   floatval(get_post_meta($invoice_id, 'month_price', true));
            $cleaning_fee       =   floatval(get_post_meta($invoice_id, 'cleaning_fee', true));
            $city_fee           =   floatval(get_post_meta($invoice_id, 'city_fee', true));
        }
        
   
      
        
        $from_date      =   new DateTime($booking_from_date);
        $from_date_unix =   $from_date->getTimestamp();
        $to_date        =   new DateTime($booking_to_date);
        $to_date_unix   =   $to_date->getTimestamp();
        $total_price    =   0;
        $inter_price    =   0;
        $has_custom     =   0;
        $usable_price   =   0;
        $has_wkend_price=   0;
        $cover_weekend  =   0;
        $custom_period_quest = 0;
        
        $timeDiff           =   abs( strtotime($booking_to_date) - strtotime($booking_from_date) );
        $count_days         =   $timeDiff/86400;  // 86400 seconds in one day
        $count_days         =   intval($count_days);
        
        //check extra price per guest
        ///////////////////////////////////////////////////////////////////////////
        $extra_price_per_guest          =   floatval   ( get_post_meta($property_id, 'extra_price_per_guest', true) );  
        $price_per_guest_from_one       =   floatval   ( get_post_meta($property_id, 'price_per_guest_from_one', true) );
        $overload_guest                 =   floatval   ( get_post_meta($property_id, 'overload_guest', true) );
        $guestnumber                    =   floatval   ( get_post_meta($property_id, 'guest_no', true) );
        $has_guest_overload             =   0;
        $total_extra_price_per_guest    =   0;
        $extra_guests                   =   0;
      
        
        
        
        //cleaning or city fee per day
        ///////////////////////////////////////////////////////////////////////////
        if($cleaning_fee_per_day==1){
            $cleaning_fee = $cleaning_fee * $count_days;
        }
        
        if($city_fee_per_day==1){
            $city_fee   =   $city_fee   *  $count_days;
        }
        
        
        
        if($price_per_guest_from_one == 0 ) {
            ///////////////////////////////////////////////////////////////
            //  per day math
            ////////////////////////////////////////////////////////////////
                
                //discoutn prices for month and week
                ///////////////////////////////////////////////////////////////////////////
                if( $count_days > 7 && $week_price!=0){ // if more than 7 days booked
                    $price_per_day = $week_price;
                }

                if( $count_days > 30 && $month_price!=0 ) {
                    $price_per_day = $month_price;
                }





                //custom prices - check the first day
                ///////////////////////////////////////////////////////////////////////////
                if( isset( $price_array[$from_date_unix] ) ) {
                    $has_custom      =   1;
                }

                if( isset($mega[$from_date_unix]) && isset( $mega[$from_date_unix]['period_price_per_weekeend'] ) &&  $mega[$from_date_unix]['period_price_per_weekeend']!=0 ){
                    $has_wkend_price = 1;
                }
                  
                if ($overload_guest==1){  // if we allow overload
                    if($curent_guest_no > $guestnumber){
                        $has_guest_overload   = 1;
                        $extra_guests         = $curent_guest_no-$guestnumber;
                        if( isset($mega[$from_date_unix]) && isset( $mega[$from_date_unix]['period_price_per_weekeend'] ) ){
                            $total_extra_price_per_guest = $total_extra_price_per_guest + $extra_guests * $mega[$from_date_unix]['period_extra_price_per_guest'] ;
                            $custom_period_quest=1;
                        }else{
                            $total_extra_price_per_guest = $total_extra_price_per_guest + $extra_guests * $extra_price_per_guest;
                        
                        }
                    }

                }

                if($price_per_weekeend!=0){
                    $has_wkend_price = 1;
                }

                $usable_price   =    wpestate_return_custom_price($from_date_unix,$mega,$price_per_weekeend,$price_array,$price_per_day);
                $total_price     =   $total_price + $usable_price;
                $inter_price     =   $inter_price + $usable_price;

                $from_date->modify('tomorrow');
                $from_date_unix =   $from_date->getTimestamp();

                $weekday = date('N', $from_date_unix); // 1-7
                if( ($weekday ==6 || $weekday==7) && $has_wkend_price==1){
                    $cover_weekend=1;
                }

                // loop trough the dates
                //////////////////////////////////////////////////////////////////////////
                while ($from_date_unix < $to_date_unix){
                    $numberDays++;


                    if( isset( $price_array[$from_date_unix] ) ) {
                        $has_custom      =   1;
                    }

                    if( isset($mega[$from_date_unix]) && isset( $mega[$from_date_unix]['period_price_per_weekeend'] ) &&  $mega[$from_date_unix]['period_price_per_weekeend']!=0 ){
                        $has_wkend_price = 1;
                    }
                    
                    if ($overload_guest==1){  // if we allow overload
                        if($curent_guest_no > $guestnumber){
                            $has_guest_overload   = 1;
                            $extra_guests         = $curent_guest_no-$guestnumber;
                            if( isset($mega[$from_date_unix]) && isset( $mega[$from_date_unix]['period_price_per_weekeend'] ) ){
                                $total_extra_price_per_guest = $total_extra_price_per_guest + $extra_guests * $mega[$from_date_unix]['period_extra_price_per_guest'] ;
                                $custom_period_quest=1;
                            }else{
                                $total_extra_price_per_guest = $total_extra_price_per_guest + $extra_guests * $extra_price_per_guest;

                            }
                        }
                    }

                    if($price_per_weekeend!=0){
                        $has_wkend_price = 1;
                    }

                    
                    $weekday = date('N', $from_date_unix); // 1-7
                    if( ($weekday ==6 || $weekday==7) && $has_wkend_price==1){
                        $cover_weekend=1;
                    }
                    
                    $usable_price   =    wpestate_return_custom_price($from_date_unix,$mega,$price_per_weekeend,$price_array,$price_per_day);
                    $total_price     =   $total_price + $usable_price;
                    $inter_price     =   $inter_price + $usable_price;

                    $from_date->modify('tomorrow');
                    $from_date_unix =   $from_date->getTimestamp();

                }

        }else{
                $custom_period_quest=0;
                ///////////////////////////////////////////////////////////////
                //  per guest math
                ////////////////////////////////////////////////////////////////
              
                if(isset($mega[$from_date_unix]['period_extra_price_per_guest']) ){
                    $total_price        =   $curent_guest_no* $mega[$from_date_unix]['period_extra_price_per_guest'];
                    $inter_price        =    $curent_guest_no*$mega[$from_date_unix]['period_extra_price_per_guest'];
                    $custom_period_quest=   1;
                }else{
                    $total_price     =   $curent_guest_no* $extra_price_per_guest;
                    $inter_price     =   $curent_guest_no* $extra_price_per_guest;
                }
                
           
                
                $from_date->modify('tomorrow');
                $from_date_unix =   $from_date->getTimestamp();
                
                
                
                  while ($from_date_unix < $to_date_unix){
                    $numberDays++;
   
          
                        
                    if( isset($mega[$from_date_unix]['period_extra_price_per_guest']) ) {
                        $total_price    =   $total_price+  $curent_guest_no* $mega[$from_date_unix]['period_extra_price_per_guest'];
                        $inter_price    =   $inter_price+  $curent_guest_no* $mega[$from_date_unix]['period_extra_price_per_guest'];
                        $custom_period_quest=   1;
                    }else{
                        $total_price    =   $total_price+ $curent_guest_no * $extra_price_per_guest;
                        $inter_price    =   $inter_price+ $curent_guest_no * $extra_price_per_guest;
                    }



                    $from_date->modify('tomorrow');
                    $from_date_unix =   $from_date->getTimestamp();

                }
                
        
         
        }// end per guest math
        
        
        $wp_estate_book_down              =   floatval ( get_option('wp_estate_book_down', '') );
        $wp_estate_book_down_fixed_fee    =   floatval ( get_option('wp_estate_book_down_fixed_fee', '') );
        
        $deposit = wpestate_calculate_deposit($wp_estate_book_down,$wp_estate_book_down_fixed_fee,$total_price);

        
        
        
        if($has_guest_overload==1 && $total_extra_price_per_guest>0){
            $total_price=$total_price + $total_extra_price_per_guest;
        }
      
        if($cleaning_fee!=0 && $cleaning_fee!=''){
            $total_price=$total_price+$cleaning_fee;
        }

        if($city_fee!=0 && $city_fee!=''){
            $total_price=$total_price+$city_fee;
        }

     
        
        
        
        $balance                                        =   $total_price - $deposit;
        $return_array=array();
        $return_array['default_price']                  =   $price_per_day;
        $return_array['week_price']                     =   $week_price;
        $return_array['month_price']                    =   $month_price;
        $return_array['total_price']                    =   $total_price;
        $return_array['inter_price']                    =   $inter_price;
        $return_array['balance']                        =   $balance;
        $return_array['deposit']                        =   $deposit;
        $return_array['from_date']                      =   $from_date;
        $return_array['to_date']                        =   $to_date;
        $return_array['cleaning_fee']                   =   $cleaning_fee;
        $return_array['city_fee']                       =   $city_fee;
        $return_array['has_custom']                     =   $has_custom;
        $return_array['numberDays']                     =   $numberDays;
        $return_array['count_days']                     =   $count_days;
        $return_array['has_wkend_price']                =   $has_wkend_price;
        $return_array['has_guest_overload']             =   $has_guest_overload;
        $return_array['total_extra_price_per_guest']    =   $total_extra_price_per_guest;
        $return_array['extra_guests']                   =   $extra_guests;
        $return_array['extra_price_per_guest']          =   $extra_price_per_guest;
        $return_array['price_per_guest_from_one']       =   $price_per_guest_from_one;
        $return_array['curent_guest_no']                =   $curent_guest_no;
        $return_array['cover_weekend']                  =   $cover_weekend;
        $return_array['custom_period_quest']            =   $custom_period_quest;
        
       
        return $return_array;

    }
endif;


if(!function_exists('wpestate_calculate_deposit')):
    function  wpestate_calculate_deposit($wp_estate_book_down,$wp_estate_book_down_fixed_fee,$total_price){
    
        if ( $wp_estate_book_down_fixed_fee == 0) {
          
            if($wp_estate_book_down =='' || $wp_estate_book_down == 0){
                $deposit                =   0;
            }else{
           
                $deposit                =   round($wp_estate_book_down*$total_price/100,2);
            }
        }else{
            $deposit = $wp_estate_book_down_fixed_fee;
        }
        return $deposit;
        
    }
endif;



function wpestate_calculate_weekedn_price($mega,$from_date_unix,$price_per_weekeend,$price_per_day,$price_array){
    $new_price='';
    if( isset($mega[$from_date_unix]) && isset( $mega[$from_date_unix]['period_price_per_weekeend'] ) &&  $mega[$from_date_unix]['period_price_per_weekeend']!=0 ){
        $new_price =$mega[$from_date_unix]['period_price_per_weekeend'];
    }else if($price_per_weekeend!=0){      
        $new_price =$price_per_weekeend;
    }else{
       $new_price = wpestate_classic_price_return($price_per_day,$price_array, $from_date_unix);
    }
    return $new_price;
}




if( !function_exists('wpestate_return_custom_price') ):
function wpestate_return_custom_price($from_date_unix,$mega,$price_per_weekeend,$price_array,$price_per_day){
    $weekday = date('N', $from_date_unix);
    $setup_weekend_status= esc_html ( get_option('wp_estate_setup_weekend','') );

    if( $setup_weekend_status ==0 && ( $weekday ==6 || $weekday==7) ){
       $new_price=wpestate_calculate_weekedn_price($mega,$from_date_unix,$price_per_weekeend,$price_per_day,$price_array);
    }else if( $setup_weekend_status ==1 && ( $weekday ==5 || $weekday==6) ){
       $new_price=wpestate_calculate_weekedn_price($mega,$from_date_unix,$price_per_weekeend,$price_per_day,$price_array);    
    }else if( $setup_weekend_status ==2 && ( $weekday ==5 || $weekday ==6 || $weekday==7) ){
       $new_price=wpestate_calculate_weekedn_price($mega,$from_date_unix,$price_per_weekeend,$price_per_day,$price_array);    
    }else{
       $new_price = wpestate_classic_price_return($price_per_day,$price_array, $from_date_unix);
    }
    return $new_price;
            
}
endif;



if( !function_exists('wpestate_classic_price_return') ):
    function wpestate_classic_price_return($price_per_day,$price_array, $from_date_unix){
        if( isset( $price_array[$from_date_unix] ) ) {
            return $price_array[$from_date_unix];
        }else{
            return $price_per_day;
        }  
    }
endif;






/////////////////////////////////////////////////////////////////////////////////
// datepcker_translate
///////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_date_picker_translation') ):
function wpestate_date_picker_translation($selector){
    $date_lang_status= esc_html ( get_option('wp_estate_date_lang','') );
     print '<script type="text/javascript">
                //<![CDATA[
                jQuery(document).ready(function(){
                        jQuery("#'.$selector.'").datepicker({
                                dateFormat : "yy-mm-dd"
                        },jQuery.datepicker.regional["'.$date_lang_status.'"]).datepicker("widget").wrap(\'<div class="ll-skin-melon"/>\');
                });
                //]]>
            </script>';
}
endif;

/////////////////////////////////////////////////////////////////////////////////
// show price
///////////////////////////////////////////////////////////////////////////////////
if( !function_exists('westate_display_corection') ):
function westate_display_corection($price){
    $whole = floor($price);      // 1
    $fraction = $price - $whole;

    if($fraction==0){
        $price=floatval($price);
    }
    return $price;
}
endif;

if( !function_exists('wpestate_show_price') ):
function wpestate_show_price($post_id,$currency,$where_currency,$return=0){
      
    $price_label    = '<span class="price_label">'.esc_html ( get_post_meta($post_id, 'property_label', true) ).'</span>';
    $price_label='';
    $price          = intval( get_post_meta($post_id, 'property_price', true) );
    
    $th_separator   =get_option('wp_estate_prices_th_separator','');
    $custom_fields = get_option( 'wp_estate_multi_curr', true);
    //print_r($_COOKIE);
    if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
        $i=intval($_COOKIE['my_custom_curr_pos']);
        $custom_fields = get_option( 'wp_estate_multi_curr', true);
        if ($price != 0) {
            $price      = $price * $custom_fields[$i][2];
            $price      = westate_display_corection($price);
            $price      = number_format($price,2,'.',$th_separator);
          
          

            
            $currency   = $custom_fields[$i][0];
            
            if ($custom_fields[$i][3] == 'before') {
                $price = $currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $currency;
            }
            
        }else{
            $price='';
        }
    }else{
        if ($price != 0) {
            $price      = westate_display_corection($price);
            $price = number_format($price,2,'.',$th_separator);
            
            if ($where_currency == 'before') {
                $price = $currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $currency;
            }
            
        }else{
            $price='';
        }
    }
    
       $price = TrimTrailingZeroes($price);
    if($return==0){
        print $price.' '.$price_label;
    }else{
         return $price.' '.$price_label;
    }
}
endif;

/////////////////////////////////////////////////////////////////////////////////
// show price custom
///////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_show_price_custom') ):
    function wpestate_show_price_custom($price){
        $price_label    =   '';
        $currency       =   esc_html( get_option('wp_estate_currency_symbol', '') );
        $where_currency =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        $th_separator   =   get_option('wp_estate_prices_th_separator','');
        $custom_fields  =   get_option( 'wp_estate_multi_curr', true);

        if ($price != 0) {
            $price  = westate_display_corection($price);
            $price  = number_format($price,2,'.',$th_separator);
    
            if ($where_currency == 'before') {
                $price = $currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $currency;
            }

        }else{
            $price='';
        }

         $price = TrimTrailingZeroes($price);
        return $price.' '.$price_label;
       
    }
endif;

if( !function_exists('wpestate_show_price_custom_invoice') ):
    function wpestate_show_price_custom_invoice($price){
        $price_label    =   '';
        $currency       =   wpestate_curency_submission_pick();
        $where_currency =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        $th_separator   =   get_option('wp_estate_prices_th_separator','');
        $custom_fields  =   get_option( 'wp_estate_multi_curr', true);

        if ($price != 0) {
            $price  = westate_display_corection($price);
            $price  = number_format($price,2,'.',$th_separator);
    
            if ($where_currency == 'before') {
                $price = $currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $currency;
            }

        }else{
            $price='';
        }

        $price = TrimTrailingZeroes($price);
        return $price.' '.$price_label;
       
    }
endif;

/////////////////////////////////////////////////////////////////////////////////
// show price booking
///////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_show_price_booking') ):
function wpestate_show_price_booking($price,$currency,$where_currency,$return=0){
      
   
        
    $price_label='';
 
    
    $th_separator   =get_option('wp_estate_prices_th_separator','');
    $custom_fields = get_option( 'wp_estate_multi_curr', true);

    if( !empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1){
        $i=intval($_COOKIE['my_custom_curr_pos']);
        $custom_fields = get_option( 'wp_estate_multi_curr', true);
        if ($price != 0) {
            $price      = $price * $custom_fields[$i][2];
            $price      = westate_display_corection($price);
            $price      = number_format($price,2,'.',$th_separator);
        
            $currency   = $custom_fields[$i][0];
            
            if ($custom_fields[$i][3] == 'before') {
                $price = $currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $currency;
            }
            
        }else{
            $price='';
        }
    }else{
        if ($price != 0) {
            $price      = westate_display_corection($price);
            $price      = ( number_format($price,2,'.',$th_separator) );
        
            if ($where_currency == 'before') {
                $price = $currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $currency;
            }
            
        }else{
            $price='';
        }
    }
    
  
    $price = TrimTrailingZeroes($price);
    if($return==0){
        print  $price.' '.$price_label;
    }else{
         return $price.' '.$price_label;
    }
}
endif;


function TrimTrailingZeroes($nbr) {
    return strpos($nbr,'.')!==false ? rtrim(rtrim($nbr,'0'),'.') : $nbr;
}


//////////////////////////////////////////////////////////////////////////////////////
// show price bookign for invoice - 1 currency only
///////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_price_booking_for_invoice') ):
function wpestate_show_price_booking_for_invoice($price,$currency,$where_currency,$has_data=0,$return=0){
      
        
    $price_label='';
    $th_separator   =get_option('wp_estate_prices_th_separator','');
    $custom_fields = get_option( 'wp_estate_multi_curr', true);

    
    if (floatval($price) != 0) {
        $price=$clear_price=floatval($price);
        $price      = westate_display_corection($price);
        $price      = number_format(($price),2,'.',$th_separator);
     
 
    if($has_data==1){
            $price = '<span class="inv_data_value" data-clearprice="'.$clear_price.'"> '.$price.'</span>';
        }
       
        if ($where_currency == 'before') {
            $price = $currency . ' ' . $price;
        } else {
            $price = $price . ' ' . $currency;
        }

    }else{
        $price=0;
        if ($where_currency == 'before') {
            $price = $currency . ' ' . $price;
        } else {
            $price = $price . ' ' . $currency;
        }
    }

    $price = TrimTrailingZeroes($price);
    
    if($return==0){
        print $price.' '.$price_label;
    }else{
        return $price.' '.$price_label;
    }
}
endif;

/////////////////////////////////////////////////////////////////////////////////
// show top bar
///////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_show_top_bar') ):
    function  wpestate_show_top_bar(){
        global $post;
        $is_top_bar= get_option('wp_estate_show_top_bar_user_menu','');
       
        if( $is_top_bar =="yes" ){
            if(!is_tax() && !is_category() && !is_archive() && !is_404() && !is_tag() ){
                
                if ( !wpestate_check_if_admin_page($post->ID ) ){
                    return true;
                }else{
                    return false;
                }
            }
             return true;
        }else{
            return false;
        }
        
    }
endif;



/////////////////////////////////////////////////////////////////////////////////
// show create_booking_type
///////////////////////////////////////////////////////////////////////////////////




if( !function_exists('wpestate_check_if_admin_page') ):
    function  wpestate_check_if_admin_page($page_id){

        if( basename(get_page_template($page_id)) == 'user_dashboard.php' || 
                basename(get_page_template($page_id)) == 'user_dashboard_add_step1.php' || 
                basename(get_page_template($page_id)) == 'user_dashboard_edit_listing.php' || 
                basename(get_page_template($page_id)) == 'user_dashboard_favorite.php' || 
                basename(get_page_template($page_id)) == 'user_dashboard_profile.php'  || 
                basename(get_page_template($page_id)) == 'user_dashboard_my_bookings.php'  || 
                basename(get_page_template($page_id)) == 'user_dashboard_my_reservations.php'  || 
                basename(get_page_template($page_id)) == 'user_dashboard_favorite'  || 
                basename(get_page_template($page_id)) == 'user_dashboard_inbox.php'  || 
                basename(get_page_template($page_id)) == 'user_dashboard_invoices.php'  ||
                basename(get_page_template($page_id)) == 'user_dashboard_packs.php'  || 
                basename(get_page_template($page_id)) == 'user_dashboard_searches.php' ||
                basename(get_page_template($page_id)) == 'user_dashboard_allinone.php'  )    {
            return true;
        }else{
            return false;
        }   
        
    }
endif;






if( !function_exists('wpestate_new_list_to_user') ):
    function  wpestate_new_list_to_user($newlist, $userid){    
        if( wpsestate_get_author($newlist)==0 ){
            $user_pack              =   get_the_author_meta( 'package_id' , $userid );
            $remaining_listings     =   wpestate_get_remain_listing_user($userid,$user_pack);
           
            if($remaining_listings  === -1){
               $remaining_listings=11;
            }
            $paid_submission_status= esc_html ( get_option('wp_estate_paid_submission','') );
          
    
            if( $paid_submission_status == 'membership' && $remaining_listings != -1 && $remaining_listings < 1 ) {
                wp_delete_post($newlist);
                return wpestate_get_dasboard_add_listing();  
            }else{
                $new_post = array(
                    'ID'            => $newlist,
                    'post_author'   => $userid,
                );
                wp_update_post( $new_post );
                $paid_submission_status = esc_html ( get_option('wp_estate_paid_submission','') );
                if( $paid_submission_status == 'membership'){ // update pack status
                    wpestate_update_listing_no($userid);                
                }

                $edit_link                       =   wpestate_get_dasboard_edit_listing();
                $edit_link_desc                  =   esc_url_raw ( add_query_arg( 'listing_edit', $newlist , $edit_link) ) ;
                $edit_link_desc                  =   esc_url_raw ( add_query_arg( 'action', 'description', $edit_link_desc) ) ;
                $edit_link_desc                  =   esc_url_raw ( add_query_arg( 'isnew', 1, $edit_link_desc) ) ;
                return $edit_link_desc;   
            }
           
        }
    }
endif;



if( !function_exists('wpestate_email_to_admin') ):
    function wpestate_email_to_admin($onlyfeatured){  
        $arguments=array();
        if($onlyfeatured==1){
            $arguments=array();
            wpestate_select_email_type(get_option('admin_email'),'featured_submission',$arguments); 
        }else{
            $arguments=array();
            wpestate_select_email_type(get_option('admin_email'),'paid_submissions',$arguments); 
        }

     

    }
endif;




if( !function_exists('wpestate_show_stripe_form_upgrade') ):
    function    wpestate_show_stripe_form_upgrade($stripe_class,$post_id,$price_submission,$price_featured_submission){
        require_once(get_template_directory().'/libs/stripe/lib/Stripe.php');
        $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
        $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );

        $stripe = array(
          "secret_key"      => $stripe_secret_key,
          "publishable_key" => $stripe_publishable_key
        );

        Stripe::setApiKey($stripe['secret_key']);
        $processor_link=wpestate_get_stripe_link();
        $current_user = wp_get_current_user();
        $userID                 =   $current_user->ID ;
        $user_email             =   $current_user->user_email ;

        $submission_curency_status  =   esc_html( get_option('wp_estate_submission_curency','') );
        $price_featured_submission  =   $price_featured_submission*100;

        print ' 
        <div class="stripe_upgrade">    
        <form action="'.$processor_link.'" method="post" >
        <div class="stripe_simple upgrade_stripe">
            <script src="https://checkout.stripe.com/checkout.js" 
            class="stripe-button"
            data-key="'. $stripe_publishable_key.'"
            data-amount="'.$price_featured_submission.'" 
            data-zip-code="true"
            data-email="'.$user_email.'"
            data-currency="'.$submission_curency_status.'"
            data-panel-label="'.esc_html__( 'Set as Featured','wpestate').'"
            data-label="'.esc_html__( 'Set as Featured','wpestate').'"
            data-description="'.esc_html__( ' Featured Payment','wpestate').'">

            </script>
        </div>
        <input type="hidden" id="propid" name="propid" value="'.$post_id.'">
        <input type="hidden" id="submission_pay" name="submission_pay" value="1">
        <input type="hidden" id="is_upgrade" name="is_upgrade" value="1">
        <input type="hidden" name="userID" value="'.$userID.'">
        <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_featured_submission.'">
        </form>
        </div>';
    }
endif;




////////////////////////////////////////////////////////////////////////////////
/// show stripe form per listing
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_stripe_form_per_listing') ):
    function wpestate_show_stripe_form_per_listing($stripe_class,$post_id,$price_submission,$price_featured_submission){
        require_once(get_template_directory().'/libs/stripe/lib/Stripe.php');
        $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
        $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );

        $stripe = array(
          "secret_key"      => $stripe_secret_key,
          "publishable_key" => $stripe_publishable_key
        );

        Stripe::setApiKey($stripe['secret_key']);
        $processor_link=wpestate_get_stripe_link();
        $submission_curency_status = esc_html( get_option('wp_estate_submission_curency','') );
        $current_user = wp_get_current_user();
        $userID                 =   $current_user->ID ;
        $user_email             =   $current_user->user_email ;

        $price_submission_total =   $price_submission+$price_featured_submission;
        $price_submission_total =   $price_submission_total*100;
        $price_submission       =   $price_submission*100;
        print ' 
        <div class="stripe-wrapper '.$stripe_class.'">    
        <form action="'.$processor_link.'" method="post" id="stripe_form_simple">
            <div class="stripe_simple">
                <script src="https://checkout.stripe.com/checkout.js" 
                class="stripe-button"
                data-key="'. $stripe_publishable_key.'"
                data-amount="'.$price_submission.'" 
                data-zip-code="true"
                data-zip-code="true"
                data-email="'.$user_email.'"
                data-currency="'.$submission_curency_status.'"
                data-label="'.esc_html__( 'Pay with Credit Card','wpestate').'"
                data-description="'.esc_html__( 'Submission Payment','wpestate').'">
                </script>
            </div>
            <input type="hidden" id="propid" name="propid" value="'.$post_id.'">
            <input type="hidden" id="submission_pay" name="submission_pay" value="1">
            <input type="hidden" name="userID" value="'.$userID.'">
            <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_submission.'">
        </form>

        <form action="'.$processor_link.'" method="post" id="stripe_form_featured">
            <div class="stripe_simple">
                <script src="https://checkout.stripe.com/checkout.js" 
                class="stripe-button"
                data-key="'. $stripe_publishable_key.'"
                data-amount="'.$price_submission_total.'" 
                data-email="'.$user_email.'"
                data-currency="'.$submission_curency_status.'"
                data-label="'.esc_html__( 'Pay with Credit Card','wpestate').'"
                data-description="'.esc_html__( 'Submission & Featured Payment','wpestate').'">
                </script>
            </div>
            <input type="hidden" id="propid" name="propid" value="'.$post_id.'">
            <input type="hidden" id="submission_pay" name="submission_pay" value="1">
            <input type="hidden" id="featured_pay" name="featured_pay" value="1">
            <input type="hidden" name="userID" value="'.$userID.'">
            <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_submission_total.'">
        </form>
        </div>';
    }
endif;




////////////////////////////////////////////////////////////////////////////////
/// show stripe form membership
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_show_stripe_form_membership') ):
    function wpestate_show_stripe_form_membership(){
        require_once(get_template_directory().'/libs/stripe/lib/Stripe.php');

        $current_user = wp_get_current_user();
        $userID                 =   $current_user->ID;
        $user_login             =   $current_user->user_login;
        $user_email             =   get_the_author_meta( 'user_email' , $userID );

        $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
        $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );

        $stripe = array(
          "secret_key"      => $stripe_secret_key,
          "publishable_key" => $stripe_publishable_key
        );
        
        $pay_ammout='0';
        $pack_id='0';
        
        Stripe::setApiKey($stripe['secret_key']);
        $processor_link             =   wpestate_get_stripe_link();
        $submission_curency_status  =   esc_html( get_option('wp_estate_submission_curency','') );


        print ' 
        <form action="'.$processor_link.'" method="post" id="stripe_form">
            '.wpestate_get_stripe_buttons($stripe['publishable_key'],$user_email,$submission_curency_status).'

            <input type="hidden" id="pack_id" name="pack_id" value="'.$pack_id.'">
            <input type="hidden" name="userID" value="'.$userID.'">
            <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$pay_ammout.'">
        </form>';
    }
endif;





if( !function_exists('wpestate_get_stripe_buttons') ):
    function wpestate_get_stripe_buttons($stripe_pub_key,$user_email,$submission_curency_status){
        wp_reset_query();
        $buttons='';
        $args = array(
            'post_type' => 'membership_package',
            'meta_query' => array(
                                 array(
                                     'key' => 'pack_visible',
                                     'value' => 'yes',
                                     'compare' => '=',
                                 )
                              )
            );
            $pack_selection = new WP_Query($args);
            $i=0;        
            while($pack_selection->have_posts() ){
                 $pack_selection->the_post();
                        $postid             = get_the_ID();

                        $pack_price         = get_post_meta($postid, 'pack_price', true)*100;
                        $title=get_the_title();
                        if($i==0){
                            $visible_stripe=" visible_stripe ";
                        }else{
                            $visible_stripe ='';
                        }
                        $i++;
                        $buttons.='
                        <div class="stripe_buttons '.$visible_stripe.' stripe_member" id="'.  sanitize_title($title).'">
                            <script src="https://checkout.stripe.com/checkout.js" id="stripe_script"
                            class="stripe-button"
                            data-key="'. $stripe_pub_key.'"
                            data-amount="'.$pack_price.'"
                            data-zip-code="true"
                            data-email="'.$user_email.'"
                            data-currency="'.$submission_curency_status.'"
                            data-label="'.esc_html__( 'Pay with Credit Card','wpestate').'"
                            data-description="'.$title.' '.esc_html__( 'Package Payment','wpestate').'">
                            </script>
                        </div>';         
            }
            wp_reset_query();
        return $buttons;
    }
endif;



/////////////////////////////////////////////////////////////////////////////////
/// get the associated user for certain agent
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_user_for_agent') ):
    function wpestate_user_for_agent($agent_id){
        $args = array(
            'fields' =>'ID',
            'meta_query' => array(

                    0 => array(
                            'key'     => 'user_agent_id',
                            'value'   => $agent_id,
                            'compare' => '='
                    ),

            )
        );
        $user_query = new WP_User_Query( $args );
        if(isset($user_query->results[0])){
            $user_agent_id=$user_query->results[0]; 
        }else{
            $user_agent_id=1;
        }
       
        return $user_agent_id;
    }
endif;



/////////////////////////////////////////////////////////////////////////////////
/// check user vs agent id 
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_user_booked_from_agent') ):
    function wpestate_user_booked_from_agent($userid,$agent_id){
        $all_my_post=array();
        $args = array(
              'post_type'           => 'wpestate_booking',
              'post_status'         => 'publish',
              'posts_per_page'      => -1,
              'author'              =>  $userid ,
              'meta_query' => array(
                    array(
                          'key'     => 'booking_status',
                          'value'   => 'confirmed',
                          'compare' => '='
                    )
          )


          );
       
          $prop_selection = new WP_Query($args);

          if ($prop_selection->have_posts()){
                while ($prop_selection->have_posts()): $prop_selection->the_post(); 

                    $prop_id = intval  ( get_post_meta(get_the_ID(), 'booking_id', true) );
                    //print 'check for '.get_the_ID().' '.wpsestate_get_author ($prop_id).' vs '.$agent_id.'</br>';
                    if( intval(wpsestate_get_author ($prop_id)) === intval($agent_id ) ){
                        return 1;
                    }

                endwhile; // end of the loop.    
                return 0;
          }else{
              return 0;
          }

    }
endif;

///////////////////////////////////////////////////////////////////////////////////////////
// stripe link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_stripe_link') ):
    function wpestate_get_stripe_link(){
        $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'stripecharge.php'
                ));

        if( $pages ){
            $stripe_link = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $stripe_link='';
        }

        return $stripe_link;
    }
endif;



/////////////////////////////////////////////////////////////////////////////////
/// check user vs agent id 
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_send_booking_email') ):
    function wpestate_send_booking_email($email_type,$receiver_email,$content=''){
        $user_email = $receiver_email;
        if ($email_type == 'bookingconfirmeduser'){
            $arguments=array();
            wpestate_select_email_type($user_email,'bookingconfirmeduser',$arguments);
        }if ($email_type == 'bookingconfirmed'){
            $arguments=array();
            wpestate_select_email_type($user_email,'bookingconfirmed',$arguments);
        }else if ($email_type == 'bookingconfirmed_nodeposit'){
            $arguments=array();
            wpestate_select_email_type($user_email,'bookingconfirmed_nodeposit',$arguments);
        }else if ($email_type == 'inbox'){
            $arguments=array('content'=>$content);
            wpestate_select_email_type($user_email,'inbox',$arguments);
        }else if ($email_type == 'newbook'){
            $property_id= intval($content);
            $arguments= array(  
                'booking_property_link'=>get_permalink($property_id)
            );
            wpestate_select_email_type($user_email,'newbook',$arguments);
        }else if ($email_type == 'mynewbook'){
            $property_id= intval($content);
            $arguments= array(  
                'booking_property_link'=>get_permalink($property_id)
            );
            wpestate_select_email_type($user_email,'mynewbook',$arguments);
        }else if ($email_type == 'newinvoice'){
            $arguments=array();
            wpestate_select_email_type($user_email,'newinvoice',$arguments);
        }else if ($email_type == 'deletebooking'){
            $arguments=array();
            wpestate_select_email_type($user_email,'deletebooking',$arguments);
        }else if ($email_type == 'deletebookinguser'){
            $arguments=array();
            wpestate_select_email_type($user_email,'deletebookinguser',$arguments);
        }else if ($email_type == 'deletebookingconfirmed'){
            $arguments=array();
            wpestate_select_email_type($user_email,'deletebookingconfirmed',$arguments);
        }





        /*
        $email_headers = "From: <noreply@".$_SERVER['HTTP_HOST']."> \r\n Reply-To:<noreply@".$_SERVER['HTTP_HOST'].">";      
        $headers = 'From: noreply  <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n".
                        'Reply-To: <noreply@'.$_SERVER['HTTP_HOST'].'>\r\n" '.
                        'X-Mailer: PHP/' . phpversion();

        $mail = wp_mail($receiver_email, $subject, $message, $headers);
        */
    }
endif;






////////////////////////////////////////////////////////////////////////////////
/// show hieracy area
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_guest_dropdown') ):
    function wpestate_get_guest_dropdown($with_any='',$selected=''){
        $select_area_list='';
        if($with_any==''){
            $select_area_list.='<li role="presentation" data-value="0">'.esc_html__( 'any','wpestate').'</li>';
        }
        
        $select_area_list .=   '<li role="presentation" data-value="1"'; 
        if($selected==1){
            $select_area_list .=' selected="selected" ';
        }
        $select_area_list .= '>1 '.esc_html__( 'guest','wpestate').'</li>';
        
        
        for($i=2;$i<15;$i++){
            $select_area_list .=   '<li role="presentation" data-value="'. $i.'"';
            if($selected!='' && $selected==$i){
                $select_area_list .=' selected="selected" ';
            }
            $select_area_list .= '>'. $i.' '.esc_html__( 'guests','wpestate').'</li>';
        }

        return $select_area_list;
    }
endif;


if( !function_exists('wpestate_get_rooms_dropdown') ):
    function wpestate_get_rooms_dropdown(){
        $select_area_list='<li role="presentation" data-value="0">'.esc_html__( 'any','wpestate').'</li>';
        $select_area_list .=   '<li role="presentation" data-value="1">1 '.esc_html__( 'room','wpestate').'</li>';
        for($i=2;$i<15;$i++){
            $select_area_list .=   '<li role="presentation" data-value="'. $i.'">'. $i.' '.esc_html__( 'rooms','wpestate').'</li>';
        }

        return $select_area_list;
    }
endif;

if( !function_exists('wpestate_get_bedrooms_dropdown') ):
    function wpestate_get_bedrooms_dropdown(){
        $select_area_list='<li role="presentation" data-value="0">'.esc_html__( 'any','wpestate').'</li>';
        $select_area_list .=   '<li role="presentation" data-value="1">1 '.esc_html__( 'bedroom','wpestate').'</li>';
        for($i=2;$i<15;$i++){
            $select_area_list .=   '<li role="presentation" data-value="'. $i.'">'. $i.' '.esc_html__( 'bedrooms','wpestate').'</li>';
        }

        return $select_area_list;
    }
endif;

if( !function_exists('wpestate_get_baths_dropdown') ):
    function wpestate_get_baths_dropdown(){
        $select_area_list='<li role="presentation" data-value="0">'.esc_html__( 'any','wpestate').'</li>';
        $select_area_list .=   '<li role="presentation" data-value="1">1 '.esc_html__( 'bath','wpestate').'</li>';
        for($i=2;$i<15;$i++){
            $select_area_list .=   '<li role="presentation" data-value="'. $i.'">'. $i.' '.esc_html__( 'baths','wpestate').'</li>';
        }

        return $select_area_list;
    }
endif;



if( !function_exists('wpestate_insert_attachment') ):
    function wpestate_insert_attachment($file_handler,$post_id,$setthumb='false') {

        // check to make sure its a successful upload
        if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

        $attach_id = media_handle_upload( $file_handler, $post_id );

        if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
        return $attach_id;
    } 
endif;




/////////////////////////////////////////////////////////////////////////////////
// order by filter featured
///////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_measure_unit') ):
    function wpestate_get_measure_unit() {
        $measure_sys    =   esc_html ( get_option('wp_estate_measure_sys','') ); 

        if($measure_sys=='feet'){
            return 'ft<sup>2</sup>';
        }else{ 
            return 'm<sup>2</sup>';
        }              
    }
endif;
/////////////////////////////////////////////////////////////////////////////////
// order by filter featured
///////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_my_order') ):
    function wpestate_my_order($orderby) { 
        global $wpdb; 
        global $table_prefix;
        $orderby = $table_prefix.'postmeta.meta_value DESC, '.$table_prefix.'posts.ID DESC';
        return $orderby;
    }    
endif; // end   wpestate_my_order  


////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('kriesi_pagination') ):
    function kriesi_pagination($pages = '', $range = 2){  

        $showitems = ($range * 2)+1;  
        global $paged;
        if(empty($paged)) $paged = 1;


        if($pages == '')
        {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if(!$pages)
            {
                $pages = 1;
            }
        }   

        if(1 != $pages)
        {
            echo '<ul class="pagination pagination_nojax">';
            echo "<li class=\"roundleft\"><a href='".get_pagenum_link($paged - 1)."'><i class=\"fa fa-angle-left\"></i></a></li>";

            for ($i=1; $i <= $pages; $i++)
            {
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                {
                    if ($paged == $i){
                       print '<li class="active"><a href="'.get_pagenum_link($i).'" >'.$i.'</a><li>';
                    }else{
                       print '<li><a href="'.get_pagenum_link($i).'" >'.$i.'</a><li>';
                    }
                }
            }

            $prev_page= get_pagenum_link($paged + 1);
            if ( ($paged +1) > $pages){
               $prev_page= get_pagenum_link($paged );
            }else{
                $prev_page= get_pagenum_link($paged + 1);
            }


            echo "<li class=\"roundright\"><a href='".$prev_page."'><i class=\"fa fa-angle-right\"></i></a><li></ul>";
        }
    }
endif; // end   kriesi_pagination  



////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination Ajax
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('kriesi_pagination_agent') ):

    function kriesi_pagination_agent($pages = '', $range = 2){  
        $showitems = ($range * 2)+1;  
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        if(empty($paged)) $paged = 1;

        if(1 != $pages)
        { 
            $prev_pagex=  str_replace('page/','',get_pagenum_link($paged - 1) );
            echo '<ul class="pagination pagination_nojax">';
            echo "<li class=\"roundleft\"><a href='".$prev_pagex."'><i class=\"fa fa-angle-left\"></i></a></li>";

            for ($i=1; $i <= $pages; $i++)
            {
                  $cur_page=str_replace('page/','',get_pagenum_link($i) );
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                {
                    if ($paged == $i){
                       print '<li class="active"><a href="'.$cur_page.'" >'.$i.'</a><li>';
                    }else{
                       print '<li><a href="'.$cur_page.'" >'.$i.'</a><li>';
                    }
                }
            }

           $prev_page= str_replace('page/','',get_pagenum_link($paged + 1) );
           if ( ($paged +1) > $pages){
              $prev_page= str_replace('page/','',get_pagenum_link($paged ) );
           }else{
              $prev_page= str_replace('page/','', get_pagenum_link($paged + 1) );
           }


            echo "<li class=\"roundright\"><a href='".$prev_page."'><i class=\"fa fa-angle-right\"></i></a><li></ul>";
        }
    }
endif; // end   kriesi_pagination  

////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination Custom
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('kriesi_pagination_ajax') ):

function kriesi_pagination_ajax($pages = '', $range = 2,$paged,$where){  
    $showitems = ($range * 2)+1;  

    if(1 != $pages)
    {
        echo '<ul class="pagination '.$where.'">';
        if($paged!=1){
            $prev_page=$paged-1;
        }else{
            $prev_page=1;
        }
        echo "<li class=\"roundleft\"><a href='".get_pagenum_link($paged - 1)."' data-future='".$prev_page."'><i class=\"fa fa-angle-left\"></i></a></li>";

        for ($i=1; $i <= $pages; $i++)
        {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
            {
                if ($paged == $i){
                   print '<li class="active"><a href="'.get_pagenum_link($i).'" data-future="'.$i.'">'.$i.'</a><li>';
                }else{
                   print '<li><a href="'.get_pagenum_link($i).'" data-future="'.$i.'">'.$i.'</a><li>';
                }
            }
        }

        $prev_page= get_pagenum_link($paged + 1);
        if ( ($paged +1) > $pages){
           $prev_page= get_pagenum_link($paged );
            echo "<li class=\"roundright\"><a href='".$prev_page."' data-future='".$paged."'><i class=\"fa fa-angle-right\"></i></a><li>"; 
        }else{
            $prev_page= get_pagenum_link($paged + 1);
            echo "<li class=\"roundright\"><a href='".$prev_page."' data-future='".($paged+1)."'><i class=\"fa fa-angle-right\"></i></a><li>"; 
        }

        echo "</ul>\n";
    }
}
endif; // end   kriesi_pagination  



////////////////////////////////////////////////////////////////////////////////
/// force html5 validation -remove category list rel atttribute
////////////////////////////////////////////////////////////////////////////////    

add_filter( 'wp_list_categories', 'wpestate_remove_category_list_rel' );
add_filter( 'the_category', 'wpestate_remove_category_list_rel' );

if( !function_exists('wpestate_remove_category_list_rel') ):
    function wpestate_remove_category_list_rel( $output ) {
        // Remove rel attribute from the category list
        return str_replace( ' rel="category tag"', '', $output );
    }
endif; // end   wpestate_remove_category_list_rel  



////////////////////////////////////////////////////////////////////////////////
/// avatar url
////////////////////////////////////////////////////////////////////////////////    

if( !function_exists('wpestate_get_avatar_url') ):
    function wpestate_get_avatar_url($get_avatar) {
        preg_match("/src='(.*?)'/i", $get_avatar, $matches);
        return $matches[1];
    }
endif; // end   wpestate_get_avatar_url  



////////////////////////////////////////////////////////////////////////////////
///  get current map height
////////////////////////////////////////////////////////////////////////////////   

if( !function_exists('wpestate_get_current_map_height') ):
    function wpestate_get_current_map_height($post_id){

       if ( $post_id == '' || is_home() ) {
            $min_height =   intval ( get_option('wp_estate_min_height','') );
       } else{
            $min_height =   intval ( (get_post_meta($post_id, 'min_height', true)) );
            if($min_height==0){
                  $min_height =   intval ( get_option('wp_estate_min_height','') );
            }
       }    
       return $min_height;
    }
endif; // end     



////////////////////////////////////////////////////////////////////////////////
///  get  map open height
////////////////////////////////////////////////////////////////////////////////   

if( !function_exists('wpestate_get_map_open_height') ):
    function wpestate_get_map_open_height($post_id){

       if ( $post_id == '' || is_home() ) {
            $max_height =   intval ( get_option('wp_estate_max_height','') );
       } else{
            $max_height =   intval ( (get_post_meta($post_id, 'max_height', true)) );
            if($max_height==0){
                $max_height =   intval ( get_option('wp_estate_max_height','') );
            }
       }

       return $max_height;
    }
endif; // end     





////////////////////////////////////////////////////////////////////////////////
///  get  map open/close status 
////////////////////////////////////////////////////////////////////////////////   

if( !function_exists('wpestate_get_map_open_close_status') ):
    function wpestate_get_map_open_close_status($post_id){    
       if ( $post_id == '' || is_home() ) {
            $keep_min =  esc_html( get_option('wp_estate_keep_min','' ) ) ;
       } else{
            $keep_min =  esc_html ( (get_post_meta($post_id, 'keep_min', true)) );
       }

       if ($keep_min == 'yes'){
           $keep_min=1; // map is forced at closed
       }else{
           $keep_min=0; // map is free for resize
       }

       return $keep_min;
    }
endif; // end     




////////////////////////////////////////////////////////////////////////////////
///  get  map  longitude
////////////////////////////////////////////////////////////////////////////////   
if( !function_exists('wpestate_get_page_long') ):
    function wpestate_get_page_long($post_id){
          $header_type  =   get_post_meta ( $post_id ,'header_type', true);
          if( $header_type==5 ){
            $page_long  = esc_html( get_post_meta($post_id, 'page_custom_long', true) );          
          }
          else{
            $page_long  = esc_html( get_option('wp_estate_general_longitude','') );
          }
          return $page_long;   
    }  
endif; // end     




////////////////////////////////////////////////////////////////////////////////
///  get  map  lattitudine
////////////////////////////////////////////////////////////////////////////////  

if( !function_exists('wpestate_get_page_lat') ):
    function wpestate_get_page_lat($post_id){
          $header_type  =   get_post_meta ( $post_id ,'header_type', true);
          if( $header_type==5 ){
            $page_lat  = esc_html( get_post_meta($post_id, 'page_custom_lat', true) );
          }
          else{
            $page_lat = esc_html( get_option('wp_estate_general_latitude','') );
          }
          return $page_lat;


}  
endif; // end     

////////////////////////////////////////////////////////////////////////////////
///  get  map  zoom
////////////////////////////////////////////////////////////////////////////////  

if( !function_exists('wpestate_get_page_zoom') ):
    function wpestate_get_page_zoom($post_id){
          $header_type  =   get_post_meta ( $post_id ,'header_type', true);
          if( $header_type==5 ){
            $page_zoom  =  get_post_meta($post_id, 'page_custom_zoom', true);
          }
          else{
            $page_zoom = esc_html( get_option('wp_estate_default_map_zoom','') );
          }
          return $page_zoom;


    }  
endif; // end     


///////////////////////////////////////////////////////////////////////////////////////////
// advanced search link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_adv_search_link') ):
    function wpestate_get_adv_search_link(){   
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'advanced_search_results.php'
            ));

        if( $pages ){
            $adv_submit = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $adv_submit='';
        }

        return $adv_submit;
    }
endif; // end     




///////////////////////////////////////////////////////////////////////////////////////////
// compare link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_compare_link') ):
    function wpestate_get_compare_link(){
       $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'compare_listings.php'
            ));

        if( $pages ){
            $compare_submit = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $compare_submit='';
        }

        return $compare_submit;
    }
endif; // end     



///////////////////////////////////////////////////////////////////////////////////////////
// my reservation link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_my_reservation_link') ):
    function wpestate_get_my_reservation_link(){
        $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard_my_reservations.php'
            ));

        if( $pages ){
            $dash_link = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $dash_link=esc_html( home_url() );
        }  

        return $dash_link;
    }
endif; // end     

///////////////////////////////////////////////////////////////////////////////////////////
// my bookings link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_my_bookings_link') ):
    function wpestate_get_my_bookings_link(){
        $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard_my_bookings.php'
            ));

        if( $pages ){
            $dash_link = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $dash_link=esc_html( home_url() );
        }  

        return $dash_link;
    }
endif; // end     


///////////////////////////////////////////////////////////////////////////////////////////
// dasboaord link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_searches_link') ):
    function wpestate_get_searches_link(){
        $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard_searches.php'
            ));

        if( $pages ){
            $dash_link =esc_url( get_permalink( $pages[0]->ID) );
        }else{
            $dash_link=esc_html( home_url() );
        }  

        return $dash_link;
    }
endif; // end     



///////////////////////////////////////////////////////////////////////////////////////////
// dasboaord link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_dashboard_link') ):
    function wpestate_get_dashboard_link(){
        $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard.php'
            ));

        if( $pages ){
            $dash_link = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $dash_link=esc_html( home_url() );
        }  

        return $dash_link;
    }
endif; // end     




///////////////////////////////////////////////////////////////////////////////////////////
// procesor link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_procesor_link') ):
    function wpestate_get_procesor_link(){
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'processor.php'
                ));

        if( $pages ){
            $processor_link = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $processor_link=esc_html( home_url() );
        }

        return $processor_link;
    }
endif; // end     
///////////////////////////////////////////////////////////////////////////////////////////
// dashboard profile packages
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('get_wpestate_packages_link') ):
    function get_wpestate_packages_link(){
         $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard_packs.php'
                    ));

        if( $pages ){
            $dash_inbox =esc_url( get_permalink( $pages[0]->ID) );
        }else{
            $dash_inbox=esc_html( home_url() );
        }    
        return $dash_inbox;
    }
endif;


///////////////////////////////////////////////////////////////////////////////////////////
// dashboard profile inbox
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('get_inbox_wpestate_booking') ):
    function get_inbox_wpestate_booking(){
         $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard_inbox.php'
                    ));

        if( $pages ){
            $dash_inbox =esc_url( get_permalink( $pages[0]->ID) );
        }else{
            $dash_inbox=esc_html( home_url() );
        }    
        return $dash_inbox;
    }
endif;



///////////////////////////////////////////////////////////////////////////////////////////
// dashboard profile invoices
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('get_invoices_wpestate') ):
    function get_invoices_wpestate(){
         $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard_invoices.php'
                    ));

        if( $pages ){
            $dash_inbox = esc_url (get_permalink( $pages[0]->ID) );
        }else{
            $dash_inbox=esc_html( home_url() );
        }    
        return $dash_inbox;
    }
endif;



///////////////////////////////////////////////////////////////////////////////////////////
// dashboard profile my bookins
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_my_booking_link') ):
    function wpestate_my_booking_link(){
         $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard_my_bookings.php'
                ));

        if( $pages ){
            $dash_book = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $dash_book=esc_html( home_url() );
        }    
        return $dash_book;
    }
endif;

///////////////////////////////////////////////////////////////////////////////////////////
// dashboard profile my bookins
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_my_reservations_link') ):
    function wpestate_my_reservations_link(){
         $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard_my_reservations.php'
                ));

        if( $pages ){
            $dash_book = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $dash_book=esc_html( home_url() );
        }    
        return $dash_book;
    }
endif;

///////////////////////////////////////////////////////////////////////////////////////////
// dashboard profile link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_dashboard_profile_link') ):
    function wpestate_get_dashboard_profile_link(){
        $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard_profile.php'
            ));

        if( $pages ){
            $dash_link = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $dash_link=esc_html( home_url() );
        }  

        return $dash_link;
    }
endif; // end   wpestate_get_dashboard_profile_link  




///////////////////////////////////////////////////////////////////////////////////////////
// terms and conditions
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_get_terms_links') ):
    function wpestate_get_terms_links(){
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'terms_conditions.php'
                ));

        if( $pages ){
            $add_link = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $add_link=esc_html( home_url() );
        }
        return $add_link;
    }
endif; // end   gterms and conditions






///////////////////////////////////////////////////////////////////////////////////////////
// dashboard add listing
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_get_dasboard_add_listing') ):
    function wpestate_get_dasboard_add_listing(){
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'user_dashboard_add_step1.php'
                ));

        if( $pages ){
            $add_link =esc_url( get_permalink( $pages[0]->ID) );
        }else{
            $add_link=esc_html( home_url() );
        }
        return $add_link;
    }
endif; // end     


///////////////////////////////////////////////////////////////////////////////////////////
// dashboard edit listing
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_get_dasboard_edit_listing') ):
    function wpestate_get_dasboard_edit_listing(){
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'user_dashboard_edit_listing.php'
                ));

        if( $pages ){
            $add_link = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $add_link=esc_html( home_url() );
        }
        return $add_link;
    }
endif; // end     

///////////////////////////////////////////////////////////////////////////////////////////
// dashboard favorite listings
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_dashboard_allinone') ):

    function wpestate_get_dashboard_allinone(){
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'user_dashboard_allinone.php'
                ));

        if( $pages ){
            $dash_favorite = esc_url( get_permalink( $pages[0]->ID) );
        }else{
            $dash_favorite=esc_html( home_url() );
        }    
        return $dash_favorite;
    }
endif; // end     

///////////////////////////////////////////////////////////////////////////////////////////
// dashboard favorite listings
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_ical_link') ):

    function wpestate_get_ical_link(){
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'ical.php'
                ));

        if( $pages ){
            $dash_favorite = esc_url( get_permalink( $pages[0]->ID) );
        }else{
            $dash_favorite=esc_html( home_url() );
        }    
        return $dash_favorite;
    }
endif; // end  
///////////////////////////////////////////////////////////////////////////////////////////
// dashboard favorite listings
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_dashboard_favorites') ):

    function wpestate_get_dashboard_favorites(){
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'user_dashboard_favorite.php'
                ));

        if( $pages ){
            $dash_favorite = esc_url( get_permalink( $pages[0]->ID) );
        }else{
            $dash_favorite=esc_html( home_url() );
        }    
        return $dash_favorite;
    }
endif; // end     

///////////////////////////////////////////////////////////////////////////////////////////
//ical feed
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_icalendar_feed') ):
    function wpestate_icalendar_feed(){
        $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'ical.php'
            ));

        if( $pages ){
            $dash_link = esc_url ( get_permalink( $pages[0]->ID) );
        }else{
            $dash_link=esc_html( home_url() );
        }  

        return $dash_link;
    }
endif; // end     



///////////////////////////////////////////////////////////////////////////////////////////
// return video divs for sliders
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_custom_vimdeo_video') ):
    function wpestate_custom_vimdeo_video($video_id) {
        $protocol = is_ssl() ? 'https' : 'http';
        return $return_string = '
            <div style="max-width:100%;" class="video">
               <iframe id="player_1" src='.$protocol.'"://player.vimeo.com/video/' . $video_id . '?api=1&amp;player_id=player_1"      allowFullScreen></iframe>
            </div>';

    }
endif; // end     


if( !function_exists('wpestate_custom_youtube_video') ):
    function  wpestate_custom_youtube_video($video_id){
        $protocol = is_ssl() ? 'https' : 'http';
        return $return_string='
            <div style="max-width:100%;" class="video">
                <iframe id="player_2" title="YouTube video player" src="'.$protocol.'://www.youtube.com/embed/' . $video_id  . '?wmode=transparent&amp;rel=0"  ></iframe>
            </div>';

    }
endif; // end     


if( !function_exists('wpestate_get_video_thumb') ):
    function wpestate_get_video_thumb($post_id){
        $video_id    = esc_html( get_post_meta($post_id, 'embed_video_id', true) );
        $video_type = esc_html( get_post_meta($post_id, 'embed_video_type', true) );
        $protocol = is_ssl() ? 'https' : 'http';
        if($video_type=='vimeo'){
             $hash2 = ( wp_remote_get($protocol."://vimeo.com/api/v2/video/$video_id.php") );
             $pre_tumb=(unserialize ( $hash2['body']) );
             $video_thumb=$pre_tumb[0]['thumbnail_medium'];                                        
        }else{
            $video_thumb = $protocol.'://img.youtube.com/vi/' . $video_id . '/0.jpg';
        }
        return $video_thumb;

    }
endif;



if( !function_exists('wpestate_generateRandomString') ):
function wpestate_generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
endif;
///////////////////////////////////////////////////////////////////////////////////////////
/////// Show advanced search fields mobile
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_show_search_field_mobile') ):
         
 function  wpestate_show_search_field_mobile($search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key){
            $adv_search_what        =   get_option('wp_estate_adv_search_what','');
            $adv_search_label       =   get_option('wp_estate_adv_search_label','');
            $adv_search_how         =   get_option('wp_estate_adv_search_how','');

            $return_string='';
            if($search_field=='none'){
                $return_string=''; 
            }
            else if($search_field=='types'){
                  $return_string='
                  <div class="dropdown form-control">
                  <div data-toggle="dropdown" id="adv_actions_mobile" class="filter_menu_trigger" data-value="all">'.esc_html__( 'All Sizes','wpestate').'<span class="caret caret_filter"></span> </div>           
                     <input type="hidden" name="filter_search_action[]" value="">
                                                          
                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions_mobile">
                      '.$action_select_list.'
                    </ul>        
                  </div>';
                    
            }else if($search_field=='categories'){
                    
                  $return_string='
                  <div class="dropdown form-control">
                  <div data-toggle="dropdown" id="adv_categ_mobile" class="filter_menu_trigger" data-value="all">'.esc_html__( 'All Types','wpestate').' <span class="caret caret_filter"></span> </div>           
                    <input type="hidden" name="filter_search_type[]" value="">
                                                              
                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ_mobile">
                      '.$categ_select_list.'
                    </ul>        
                  </div>';

            }  else if($search_field=='cities'){
                    
                    $return_string='
                    <div class="dropdown form-control">
                        <div data-toggle="dropdown" id="advanced_city_mobile" class="filter_menu_trigger" data-value="all">'. esc_html__( 'All Cities','wpestate').' <span class="caret caret_filter"></span> </div>           
                        <input type="hidden" name="advanced_city" value="">
                        <ul id="mobile-adv-city" class="dropdown-menu filter_menu" role="menu" aria-labelledby="advanced_city_mobile">
                            '.$select_city_list.'
                        </ul>        
                    </div> ';
                
           }   else if($search_field=='areas'){

                    $return_string='
                    <div class="dropdown form-control">
                        <div data-toggle="dropdown" id="advanced_area_mobile" class="filter_menu_trigger" data-value="all">'.esc_html__( 'All Areas','wpestate').'<span class="caret caret_filter"></span> </div>           
                        <input type="hidden" name="advanced_area" value="">
                        <ul id="mobile-adv-area"  class="dropdown-menu filter_menu" role="menu" aria-labelledby="advanced_area_mobile">
                            '.$select_area_list.'
                        </ul>        
                   </div>  ';
            }      else {
                 // $slug=str_replace(' ','_',$search_field);
                    $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
                    $slug         =   sanitize_key($string);
                    
                    $label=$adv_search_label[$key];
                    if (function_exists('icl_translate') ){
                        $label     =   icl_translate('wpestate','wp_estate_custom_search_'.$label, $label ) ;
                    }
                    
                    $random_id=rand(1,999);
                    
                    
               
                    
                    if ( $adv_search_what[$key]=='property price'){
                        $show_slider_price            =   get_option('wp_estate_show_slider_price','');
                        if ($show_slider_price==='yes'){
                            $where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
                            $currency               =   esc_html( get_option('wp_estate_currency_symbol', '') );
         
                            $min_price_slider= ( floatval(get_option('wp_estate_show_slider_min_price','')) );
                            $max_price_slider= ( floatval(get_option('wp_estate_show_slider_max_price','')) );

                            $price_slider_label = wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$currency,$where_currency);
                          
                               
                            $return_string='<div class="adv_search_slider slide_mobile">
                                <p>
                                    <label>'.esc_html__( 'Price range:','wpestate').'</label>
                                    <span id="amount_mobile"  style="border:0; color:#f6931f; font-weight:bold;">'.$price_slider_label.'</span>
                                </p>
                                <div id="slider_price_mobile"></div>
                                <input type="hidden" id="price_low_mobile"  name="price_low"  value="'.$min_price_slider.'"/>
                                <input type="hidden" id="price_max_mobile"  name="price_max"  value="'.$max_price_slider.'"/>
                            </div>';
                        }else{
                            $return_string='<input type="text" id="'.$slug.$random_id.'" name="'.$slug.'" placeholder="'.$label.'" value=""  class="advanced_select form-control">';
                        }
                    }else{
                        $return_string='<input type="text" id="'.$slug.$random_id.'" name="'.$slug.'" placeholder="'.$label.'" value=""  class="advanced_select form-control">';
                        
                    }
                    
                    if ( $adv_search_how[$key]=='date bigger' || $adv_search_how[$key]=='date smaller'){
                        /*print '<script type="text/javascript">
                            //<![CDATA[
                            jQuery(document).ready(function(){
                                    jQuery("#'.$slug.$random_id.'").datepicker({
                                            dateFormat : "yy-mm-dd"
                                    });
                            });
                            //]]>
                            </script>';
                        */
                        wpestate_date_picker_translation($slug.$random_id);
                    }
           

            } 
            print $return_string;
         }
endif; //

///////////////////////////////////////////////////////////////////////////////////////////
/////// Show advanced search fields
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_search_field') ):

    function  wpestate_show_search_field($search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key){
                $adv_search_what        =   get_option('wp_estate_adv_search_what','');
                $adv_search_label       =   get_option('wp_estate_adv_search_label','');
                $adv_search_how         =   get_option('wp_estate_adv_search_how','');


                $return_string='';
                if($search_field=='none'){
                    $return_string=''; 
                }
                else if($search_field=='types'){
                    $return_string='
                    <div class="dropdown form-control">
                        <div data-toggle="dropdown" id="adv_actions" class="filter_menu_trigger" data-value="all">'
                            .esc_html__( 'All Sizes','wpestate').'<span class="caret caret_filter"></span>
                        </div>           
                        <input type="hidden" name="filter_search_action[]" value="">

                        <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                            '.$action_select_list.'
                        </ul>        
                    </div>';

                }else if($search_field=='categories'){

                    $return_string='
                    <div class="dropdown  form-control">
                        <div data-toggle="dropdown" id="adv_categ" class="filter_menu_trigger" data-value="all">'
                        .esc_html__( 'All Types','wpestate').' <span class="caret caret_filter"></span>
                        </div>           
                        <input type="hidden" name="filter_search_type[]" value="">

                        <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ">
                          '.$categ_select_list.'
                        </ul>        
                    </div>';

                }  else if($search_field=='cities'){

                    $return_string='
                    <div class="dropdown  form-control">
                        <div data-toggle="dropdown" id="advanced_city" class="filter_menu_trigger" data-value="all">'
                            . esc_html__( 'All Cities','wpestate').' <span class="caret caret_filter"></span>
                        </div>           
                        <input type="hidden" name="advanced_city" value="">
                        <ul  id="adv-search-city" class="dropdown-menu filter_menu" role="menu" aria-labelledby="advanced_city">
                            '.$select_city_list.'
                        </ul>        
                    </div> ';

               }   else if($search_field=='areas'){

                    $return_string='
                    <div class="dropdown  form-control">
                        <div data-toggle="dropdown" id="advanced_area" class="filter_menu_trigger" data-value="all">'
                            .esc_html__( 'All Areas','wpestate').'<span class="caret caret_filter"></span>
                        </div>           
                        <input type="hidden" name="advanced_area" value="">
                        <ul id="adv-search-area" class="dropdown-menu filter_menu" role="menu" aria-labelledby="advanced_area">
                            '.$select_area_list.'
                        </ul>        
                    </div>';

                }   else {

                    //$slug       =   wpestate_limit45 ( sanitize_title ( $search_field )); 
                    //$slug       =   sanitize_key($slug);            
                    $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
                    $slug         =   sanitize_key($string);

                    $label=$adv_search_label[$key];
                    if (function_exists('icl_translate') ){
                        $label     =   icl_translate('wpestate','wp_estate_custom_search_'.$label, $label ) ;
                    }

                 //   $return_string='<input type="text" id="'.$slug.'"  name="'.$slug.'" placeholder="'.$label.'" value=""  class="advanced_select  form-control" />';

                    if ( $adv_search_what[$key]=='property price'){
                        $show_slider_price            =   get_option('wp_estate_show_slider_price','');
                        if ($show_slider_price==='yes'){
                                $min_price_slider       = ( floatval(get_option('wp_estate_show_slider_min_price','')) );
                                $max_price_slider       = ( floatval(get_option('wp_estate_show_slider_max_price','')) );
                                $where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
                                $currency               =   esc_html( get_option('wp_estate_currency_symbol', '') );

                                if ($where_currency == 'before') {
                                    $price_slider_label = $currency . number_format($min_price_slider).' '.esc_html__( 'to','wpestate').' '.$currency . number_format($max_price_slider);
                                } else {
                                    $price_slider_label =  number_format($min_price_slider).$currency.' '.esc_html__( 'to','wpestate').' '.number_format($max_price_slider).$currency;
                                } 

                                $return_string=' <div class="adv_search_slider">
                                    <p>
                                        <label for="amount">'. esc_html__( 'Price range:','wpestate').'</label>
                                        <span id="amount"  style="border:0; color:#f6931f; font-weight:bold;">'.$price_slider_label.'</span>
                                    </p>
                                    <div id="slider_price"></div>
                                    <input type="hidden" id="price_low"  name="price_low"  value="'.$min_price_slider.'"/>
                                    <input type="hidden" id="price_max"  name="price_max"  value="'.$max_price_slider.'"/>
                                </div>';
                        }else{
                             $return_string='<input type="text" id="'.$slug.'"  name="'.$slug.'" placeholder="'.$label.'" value=""  class="advanced_select  form-control" />';
                        }
                     // if is property price    
                    }else{ 
                         $return_string='<input type="text" id="'.$slug.'"  name="'.$slug.'" placeholder="'.$label.'" value=""  class="advanced_select  form-control" />';
                    }

                    if ( $adv_search_how[$key]=='date bigger' || $adv_search_how[$key]=='date smaller'){
                        print '<script type="text/javascript">
                              //<![CDATA[
                              jQuery(document).ready(function(){
                                    '.wpestate_date_picker_translation($slug).'
                              });
                              //]]>
                              </script>';
                    }


                } 
                print $return_string;
    }
endif; // 



if( !function_exists('wpestate_show_extended_search') ):
    function wpestate_show_extended_search($tip){
        print '<div class="extended_search_check_wrapper" id="extended_search_check_filter">';
        
        print ' 
        <div class="secondrow">
            
            
        </div>';
        print '<span id="adv_extended_close_adv"><i class="fa fa-times"></i></span>';

               $advanced_exteded   =   get_option( 'wp_estate_advanced_exteded', true); 

               foreach($advanced_exteded as $checker => $value){
                   $post_var_name  =   str_replace(' ','_', trim($value) );
                   $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
                   $input_name     =   sanitize_key($input_name);

                   if (function_exists('icl_translate') ){
                       $value     =   icl_translate('wpestate','wp_estate_property_custom_amm_'.$value, $value ) ;                                      
                   }

                  $value= str_replace('_',' ', trim($value) );
                  if($value!='none'){
                    print '<div class="extended_search_checker"><input type="checkbox" id="'.$input_name.$tip.'" name="'.$input_name.'" value="1" ><label for="'.$input_name.$tip.'">'.$value. '</label></div>';
                  }
               }

        print '</div>';    
    }
endif;






////////////////////////////////////////////////////////////////////////////////
/// show hieracy categeg
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_hierarchical_category_childen') ):
    /*function wpestate_hierarchical_category_childen($taxonomy, $cat,$args ) {

        $args['parent']             =   $cat;
        $children                   =   get_terms($taxonomy,$args);


        $children_categ_select_list =   '';
        foreach ($children as $categ) {
            $area_addon =   '';
            $city_addon =   '';

            if($taxonomy=='property_city'){
                $string       =     wpestate_limit45 ( sanitize_title ( $categ->slug ) );              
                $slug         =     sanitize_key($string);
                $city_addon   =     ' data-value2="'.$slug.'" ';
            }

            if($taxonomy=='property_area'){
                $term_meta    =   get_option( "taxonomy_$categ->term_id");
                $string       =   wpestate_limit45 ( sanitize_title ( $term_meta['cityparent'] ) );              
                $slug         =   sanitize_key($string);
                $area_addon   =   ' data-parentcity="' . $slug . '" ';

            }

            $children_categ_select_list     .=   '<li role="presentation" data-value="'.$categ->slug.'" '.$city_addon.' '.$area_addon.' > - '. ucwords ( urldecode( $categ->name ) ).' ('.$categ->count.')'.'</li>';
        }
        return $children_categ_select_list;
    }*/
     function wpestate_hierarchical_category_childen($taxonomy, $cat,$args,$base=1,$level=1  ) {
        $level++;
        $args['parent']             =   $cat;
        $children                   =   get_terms($taxonomy,$args);
        $return_array=array();
        $total_main[$level]=0;
        $children_categ_select_list =   '';
        foreach ($children as $categ) {
            
            $area_addon =   '';
            $city_addon =   '';

            if($taxonomy=='property_city'){
                $string       =     wpestate_limit45 ( sanitize_title ( $categ->slug ) );              
                $slug         =     sanitize_key($string);
                $city_addon   =     ' data-value2="'.$slug.'" ';
            }

            if($taxonomy=='property_area'){
                $term_meta    =   get_option( "taxonomy_$categ->term_id");
                $string       =   wpestate_limit45 ( sanitize_title ( $term_meta['cityparent'] ) );              
                $slug         =   sanitize_key($string);
                $area_addon   =   ' data-parentcity="' . $slug . '" ';

            }
            
            $hold_base=  $base;
            $base_string='';
            $base++;
            $hold_base=  $base;
            
            if($level==2){
                $base_string='-';
            }else{
                $i=2;
                $base_string='';
                while( $i <= $level ){
                    $base_string.='-';
                    $i++;
                }
              
            }
    
            
            if($categ->parent!=0){
                $received =wpestate_hierarchical_category_childen( $taxonomy, $categ->term_id,$args,$base,$level ); 
            }
            
            
            $counter = $categ->count;
            if(isset($received['count'])){
                $counter = $counter+$received['count'];
            }
            
            $children_categ_select_list     .=   '<li role="presentation" data-value="'.$categ->slug.'" '.$city_addon.' '.$area_addon.' > '.$base_string.' '. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
           
            if(isset($received['html'])){
                $children_categ_select_list     .=   $received['html'];  
            }
          
            $total_main[$level]=$total_main[$level]+$counter;
            
            $return_array['count']=$counter;
            $return_array['html']=$children_categ_select_list;
            
            
        }
      //  return $children_categ_select_list;
 
        $return_array['count']=$total_main[$level];
    
     
        return $return_array;
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// get select arguments
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_select_arguments') ):
    function wpestate_get_select_arguments(){
        $args = array(
                'hide_empty'    => true  ,
                'hierarchical'  => false,
                'pad_counts '   => true,
                'parent'        => 0
                ); 

        $show_empty_city_status = esc_html ( get_option('wp_estate_show_empty_city','') );
        if ($show_empty_city_status=='yes'){
            $args = array(
                'hide_empty'    => false  ,
                'hierarchical'  => false,
                'pad_counts '   => true,
                'parent'        => 0
                ); 
        }
        return $args;
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// show hieracy action
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_action_select_list') ):
    /*function wpestate_get_action_select_list($args){
        $taxonomy           =   'property_action_category';
        $tax_terms          =   get_terms($taxonomy,$args);
        $action_select_list =   ' <li role="presentation" data-value="all">'. esc_html__( 'All Sizes','wpestate').'</li>';

        foreach ($tax_terms as $tax_term) {
            $action_select_list     .=  '<li role="presentation" data-value="'.$tax_term->slug.'">'. ucwords ( urldecode($tax_term->name ) ).' ('.$tax_term->count.')'.'</li>';
            $action_select_list     .=   wpestate_hierarchical_category_childen($taxonomy, $tax_term->term_id,$args );       
        }
        return $action_select_list;
    }
    */
      function wpestate_get_action_select_list($args){
        $taxonomy           =   'property_action_category';
        $categories          =   get_terms($taxonomy,$args);
       
        $categ_select_list =   ' <li role="presentation" data-value="all">'. __('All Sizes','wpestate').'</li>';
       
        foreach ($categories as $categ) {
            $received = wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args ); 
            $counter = $categ->count;
            if(isset($received['count'])){
                $counter = $counter+$received['count'];
            }
            
            $categ_select_list     .=   '<li role="presentation" data-value="'.$categ->slug.'">'. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
            if(isset($received['html'])){
                $categ_select_list     .=   $received['html'];  
            }
            
        }
        return $categ_select_list;
    }
endif;


////////////////////////////////////////////////////////////////////////////////
/// show hieracy categ
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_category_select_list') ):
    /*function wpestate_get_category_select_list($args){
        $taxonomy           =   'property_category';
        $categories         =   get_terms($taxonomy,$args);
        $categ_select_list  =  '<li role="presentation" data-value="all">'. esc_html__( 'All Types','wpestate').'</li>'; 

        foreach ($categories as $categ) {
            $categ_select_list     .=   '<li role="presentation" data-value="'.$categ->slug.'">'. ucwords ( urldecode( $categ->name ) ).' ('.$categ->count.')'.'</li>';
            $categ_select_list     .=   wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args );    
        }
        return $categ_select_list;
    }*/
     function wpestate_get_category_select_list($args){
        $taxonomy           =   'property_category';
        $categories         =   get_terms($taxonomy,$args);
      
        $categ_select_list  =  '<li role="presentation" data-value="all">'. __('All Types','wpestate').'</li>'; 

        foreach ($categories as $categ) {
            $counter = $categ->count;
            $received = wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args ); 
         
            // print 'xxxxrece : '.$categ->name .'/ '. $received['count'].'</br>';
              
            if(isset($received['count'])){
                $counter = $counter+$received['count'];
            }
            
            $categ_select_list     .=   '<li role="presentation" data-value="'.$categ->slug.'">'. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
            if(isset($received['html'])){
                $categ_select_list     .=   $received['html'];  
            }
            
        }
        return $categ_select_list;
    }
endif;




////////////////////////////////////////////////////////////////////////////////
/// show hieracy city
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_city_select_list') ):
   /* function wpestate_get_city_select_list($args){
        $select_city_list   =    '<li role="presentation" data-value="all" data-value2="all">'. esc_html__( 'All Cities','wpestate').'</li>';
        $taxonomy           =   'property_city';
        $tax_terms_city     =   get_terms($taxonomy,$args);

        foreach ($tax_terms_city as $tax_term) {
            $string       =   wpestate_limit45 ( sanitize_title ( $tax_term->slug ) );              
            $slug         =   sanitize_key($string);
            $select_city_list     .=   '<li role="presentation" data-value="'.$tax_term->slug.'" data-value2="'.$slug.'">'. ucwords ( urldecode( $tax_term->name) ).' ('.$tax_term->count.')'.'</li>';
            $select_city_list     .=   wpestate_hierarchical_category_childen($taxonomy, $tax_term->term_id,$args );    
        }
        return $select_city_list;
    }*/
     function wpestate_get_city_select_list($args){
        $categ_select_list   =    '<li role="presentation" data-value="all" data-value2="all">'. __('All Cities','wpestate').'</li>';
        $taxonomy           =   'property_city';
        $categories     =   get_terms($taxonomy,$args);
       
        foreach ($categories as $categ) {
            $string     =   wpestate_limit45 ( sanitize_title ( $categ->slug ) );              
            $slug       =   sanitize_key($string);
            $received   =   wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args ); 
            $counter    =   $categ->count;
            if( isset($received['count'])   ){
                $counter = $counter+$received['count'];
            }
            
            $categ_select_list  .=  '<li role="presentation" data-value="'.$categ->slug.'" data-value2="'.$slug.'">'. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
            if(isset($received['html'])){
                $categ_select_list     .=   $received['html'];  
            }
            
        }
        return $categ_select_list;
    }
endif;


////////////////////////////////////////////////////////////////////////////////
/// show hieracy area
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_area_select_list') ):
   /* function wpestate_get_area_select_list($args){
        $select_area_list   =   '<li role="presentation" data-value="all">'.esc_html__( 'All Areas','wpestate').'</li>';
        $taxonomy           =   'property_area';
        $tax_terms_area     =   get_terms($taxonomy,$args);

        foreach ($tax_terms_area as $tax_term) {
            $term_meta    =   get_option( "taxonomy_$tax_term->term_id");
            $string       =   wpestate_limit45 ( sanitize_title ( $term_meta['cityparent'] ) );              
            $slug         =   sanitize_key($string);

            $select_area_list .=   '<li role="presentation" data-value="'.$tax_term->slug.'" data-parentcity="' . $slug . '">'. ucwords  (urldecode( $tax_term->name ) ).' ('.$tax_term->count.')'.'</li>';
            $select_area_list .=   wpestate_hierarchical_category_childen( $taxonomy, $tax_term->term_id,$args ); 
        }  
        return $select_area_list;
    }*/
    
    function wpestate_get_area_select_list($args){
    $categ_select_list  =   '<li role="presentation" data-value="all">'.__('All Areas','wpestate').'</li>';
    $taxonomy           =   'property_area';
    $categories         =   get_terms($taxonomy,$args);
  
    foreach ($categories as $categ) {
        $term_meta      =   get_option( "taxonomy_$categ->term_id");
        $string         =   wpestate_limit45 ( sanitize_title ( $term_meta['cityparent'] ) );              
        $slug           =   sanitize_key($string);
        $received       =   wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args ); 
        $counter        =   $categ->count;
        if( isset($received['count'])   ){
            $counter = $counter+$received['count'];
        }

        $categ_select_list  .=  '<li role="presentation" data-value="'.$categ->slug.'" data-parentcity="' . $slug . '">'. ucwords ( urldecode( $categ->name ) ).' ('.$counter.')'.'</li>';
        if(isset($received['html'])){
            $categ_select_list     .=   $received['html'];  
        }

    }
    return $categ_select_list;
}
endif;

if( !function_exists('wpestate_get_area_select_list_area_tax') ):
    function wpestate_get_area_select_list_area_tax($args, $parentcity=''){
        $select_area_list   =   '<li role="presentation" data-value="all">'.esc_html__( 'All Areas','wpestate').'</li>';
        $taxonomy           =   'property_area';
        $tax_terms_area     =   get_terms($taxonomy,$args);

        foreach ($tax_terms_area as $tax_term) {
        
            $term_meta    =   get_option( "taxonomy_$tax_term->term_id");
            $string       =   wpestate_limit45 ( sanitize_title ( $term_meta['cityparent'] ) );              
            $parentcity   =   wpestate_limit45 ( sanitize_title ( $parentcity ) );   
            $slug         =   sanitize_key($string);

            if($parentcity!='' && $parentcity == $string){
                $select_area_list .=   '<li style="display:none;" role="presentation" data-value="'.$tax_term->slug.'" data-parentcity="' . $slug . '">'. ucwords  (urldecode( $tax_term->name ) ).' ('.$tax_term->count.')'.'</li>';
                $select_area_list .=   wpestate_hierarchical_category_childen( $taxonomy, $tax_term->term_id,$args );  
            }else{
                $select_area_list .=   '<li role="presentation" data-value="'.$tax_term->slug.'" data-parentcity="' . $slug . '">'. ucwords  (urldecode( $tax_term->name ) ).' ('.$tax_term->count.')'.'</li>';
                $select_area_list .=   wpestate_hierarchical_category_childen( $taxonomy, $tax_term->term_id,$args );  
         
            }
            
         
        }  
        return $select_area_list;
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// show name on saved searches
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_custom_field_name') ):
    function wpestate_get_custom_field_name($query_name,$adv_search_what,$adv_search_label){
        $i=0;

        foreach($adv_search_what as $key=>$term){    
                $term         =   str_replace(' ', '_', $term);
                $slug         =   wpestate_limit45(sanitize_title( $term )); 
                $slug         =   sanitize_key($slug); 

                if($slug==$query_name){
                    return  $adv_search_label[$key];
                }
                $i++;
        }

        return $query_name;
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// get author
////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpsestate_get_author') ):
    function wpsestate_get_author( $post_id = 0 ){
        $post = get_post( $post_id );
        
        if( isset($post->post_author)   ) {
            return $post->post_author;
        }
    }
endif;


////////////////////////////////////////////////////////////////////////////////
/// check avalability
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_check_booking_valability') ):
    function wpestate_check_booking_valability($book_from,$book_to,$listing_id){

        $reservation_array  =   wpestate_get_booking_dates_advanced_search($listing_id);
        $from_date          =   new DateTime($book_from);
        //$from_date          =   DateTime::createFromFormat('M-d-Y', $book_from);
        $from_date_unix     =   $from_date->getTimestamp();
        $to_date            =   new DateTime($book_to);
        //$to_date            =    DateTime::createFromFormat('M-d-Y',$book_to);
        $to_date->modify('yesterday');
          
        $to_date_unix       =   $to_date->getTimestamp();
        
       
        if($from_date_unix===$to_date_unix){
            if( array_key_exists($from_date_unix,$reservation_array ) ){
                return false;
            }
        }
          
          
        while ($from_date_unix < $to_date_unix){
            $from_date_unix =   $from_date->getTimestamp();
            if( array_key_exists($from_date_unix,$reservation_array ) ){
                return false;
            }
            $from_date->modify('tomorrow');
        }
        return true;
    }
endif;



if( !function_exists('wpestate_get_booking_dates_advanced_search') ):
    function wpestate_get_booking_dates_advanced_search($listing_id){
     
    $reservation_array = get_post_meta($listing_id, 'booking_dates',true);
    if( !is_array($reservation_array) || $reservation_array=='' ){
        $reservation_array  =   array();
   
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
        foreach ( $booking_selection->posts as $post ) {
            $pid            =   get_the_ID();
            $fromd          =   esc_html(get_post_meta($post->ID, 'booking_from_date', true));
            $tod            =   esc_html(get_post_meta($post->ID, 'booking_to_date', true));

            $from_date      =   new DateTime($fromd);
            $from_date_unix =   $from_date->getTimestamp();
            $to_date        =   new DateTime($tod);
            $to_date_unix   =   $to_date->getTimestamp();
           // $reservation_array[]=$from_date_unix;
            $reservation_array[$from_date_unix]=$pid;

            while ($from_date_unix < $to_date_unix){
                $from_date->modify('tomorrow');
                $from_date_unix =   $from_date->getTimestamp();
                //$reservation_array[]=$from_date_unix;
                $reservation_array[$from_date_unix]=$pid;
            }
        }
 
       
        }
        
       // print_r($reservation_array);
        return $reservation_array;
    }
endif;
?>