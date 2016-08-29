<?php
function wp_get_attachment( $attachment_id ) {

	$attachment = get_post( $attachment_id );
	return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => esc_url ( get_permalink( $attachment->ID )),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
	);
}
///////////////////////////////////////////////////////////////////////////////////////////
// List features and ammenities
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('estate_listing_features') ):
    function estate_listing_features($post_id){
        $return_string='';    
        $counter            =   0;                          
        $feature_list_array =   array();
        $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
        $feature_list_array =   explode( ',',$feature_list);
        $total_features     =   round( count( $feature_list_array )/2 );


         $show_no_features= esc_html ( get_option('wp_estate_show_no_features','') );



            if($show_no_features!='no'){
                foreach($feature_list_array as $checker => $value){
                        $counter++;
                        $post_var_name  =   str_replace(' ','_', trim($value) );
                        $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
                        $input_name     =   sanitize_key($input_name);


                        if (function_exists('icl_translate') ){
                            $value     =   icl_translate('wpestate','wp_estate_property_custom_amm_'.$value, $value ) ;                                      
                        }
                        $value= stripslashes($value);

                        if (esc_html( get_post_meta($post_id, $input_name, true) ) == 1) {
                             $return_string .= '<div class="listing_detail col-md-6"><i class="fa fa-check checkon"></i>' . trim($value) . '</div>';
                        }else{
                            $return_string  .=  '<div class="listing_detail not_present col-md-6"><i class="fa fa-times"></i>' . trim($value). '</div>';
                        }
                  }
            }else{

                foreach($feature_list_array as $checker => $value){
                    $post_var_name  =  str_replace(' ','_', trim($value) );
                    $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
                    $input_name     =   sanitize_key($input_name);

                    if (function_exists('icl_translate') ){
                        $value     =   icl_translate('wpestate','wp_estate_property_custom_amm_'.$value, $value ) ;                                      
                    }

                    if (esc_html( get_post_meta($post_id, $input_name, true) ) == 1) {
                        $return_string .=  '<div class="listing_detail col-md-6"><i class="fa fa-check checkon"></i>' . trim($value) . '</div>';
                    }
                }

           }

        return $return_string;
    }
endif; // end   estate_listing_features  


///////////////////////////////////////////////////////////////////////////////////////////
// dashboard price
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('estate_listing_price') ):
    function estate_listing_price($post_id){
        $return_string                  =   '';
        $property_price                 =   floatval(get_post_meta($post_id, 'property_price', true) );
        $property_price_per_week        =   floatval(get_post_meta($post_id, 'property_price_per_week', true) );
        $property_price_per_month       =   floatval(get_post_meta($post_id, 'property_price_per_month', true) );
        $cleaning_fee                   =   floatval(get_post_meta($post_id, 'cleaning_fee', true) );
        $city_fee                       =   floatval(get_post_meta($post_id, 'city_fee', true) );
        $cleaning_fee_per_day           =   floatval  ( get_post_meta($post_id,  'cleaning_fee_per_day', true) );
        $city_fee_per_day               =   floatval   ( get_post_meta($post_id, 'city_fee_per_day', true) );
        $price_per_guest_from_one       =   floatval   ( get_post_meta($post_id, 'price_per_guest_from_one', true) );
        $overload_guest                 =   floatval   ( get_post_meta($post_id, 'overload_guest', true) );
        $checkin_change_over            =   floatval   ( get_post_meta($post_id, 'checkin_change_over', true) );  
        $checkin_checkout_change_over   =   floatval   ( get_post_meta($post_id, 'checkin_checkout_change_over', true) );  
        $min_days_booking               =   floatval   ( get_post_meta($post_id, 'min_days_booking', true) );  
        $extra_price_per_guest          =   floatval   ( get_post_meta($post_id, 'extra_price_per_guest', true) );  
        $price_per_weekeend             =   floatval   ( get_post_meta($post_id, 'price_per_weekeend', true) );  
        
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
        
        $currency                       = esc_html( get_option('wp_estate_currency_symbol', '') );
        $where_currency                 = esc_html( get_option('wp_estate_where_currency_symbol', '') );

        $th_separator   =   get_option('wp_estate_prices_th_separator','');
        $custom_fields  =   get_option( 'wp_estate_multi_curr', true);
        
        $property_price_show                 =  wpestate_show_price_booking($property_price,$currency,$where_currency,1);         
        $property_price_per_week_show        =  wpestate_show_price_booking($property_price_per_week,$currency,$where_currency,1);
        $property_price_per_month_show       =  wpestate_show_price_booking($property_price_per_month,$currency,$where_currency,1);
        $cleaning_fee_show                   =  wpestate_show_price_booking($cleaning_fee,$currency,$where_currency,1);
        $city_fee_show                       =  wpestate_show_price_booking($city_fee,$currency,$where_currency,1);
        
        $price_per_weekeend_show             =  wpestate_show_price_booking($price_per_weekeend,$currency,$where_currency,1);
        $extra_price_per_guest_show          =  wpestate_show_price_booking($extra_price_per_guest,$currency,$where_currency,1);
        $extra_price_per_guest_show          =  wpestate_show_price_booking($extra_price_per_guest,$currency,$where_currency,1);
      
        $setup_weekend_status= esc_html ( get_option('wp_estate_setup_weekend','') );
        $weekedn = array( 
            0 => __("Sunday and Saturday","wpestate"),
            1 => __("Friday and Saturday","wpestate"),
            2 => __("Friday, Saturday and Sunday","wpestate")
        );


        if($price_per_guest_from_one!=1){
        
            if ($property_price != 0){
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night col-md-6"><span class="item_head">'.esc_html__( 'Price per night','wpestate').':</span> ' . $property_price_show . '</div>'; 
            }

            if ($property_price_per_week != 0){
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_7d col-md-6"><span class="item_head">'.esc_html__( 'Price per night (7d+)','wpestate').':</span> ' . $property_price_per_week_show . '</div>'; 
            }

            if ($property_price_per_month != 0){
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_30d col-md-6"><span class="item_head">'.esc_html__( 'Price per night (30d+)','wpestate').':</span> ' . $property_price_per_month_show . '</div>'; 
            }

            if ($price_per_weekeend!=0){
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_weekend col-md-6"><span class="item_head">'.esc_html__( 'Price per weekend ','wpestate').'('.$weekedn[$setup_weekend_status].') '.':</span> ' . $price_per_weekeend_show . '</div>'; 
            }
            
            if ($extra_price_per_guest!=0){
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_extra_guest col-md-6"><span class="item_head">'.esc_html__( 'Extra Price per guest','wpestate').':</span> ' . $extra_price_per_guest_show . '</div>'; 
            }
        }else{
            if ($extra_price_per_guest!=0){
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_extra_guest_price col-md-6"><span class="item_head">'.esc_html__( 'Price per guest','wpestate').':</span> ' . $extra_price_per_guest_show . '</div>'; 
            }
        }
        
        if ($cleaning_fee != 0){
            $return_string.='<div class="listing_detail list_detail_prop_price_cleaning_fee col-md-6"><span class="item_head">'.esc_html__( 'Cleaning Fee','wpestate').':</span> ' . $cleaning_fee_show ;
            if($cleaning_fee_per_day==1){
                $return_string .= ' '.esc_html__('per night','wpestate');
            }
            $return_string.='</div>'; 
        }

        if ($city_fee != 0){
            $return_string.='<div class="listing_detail list_detail_prop_price_tax_fee col-md-6"><span class="item_head">'.esc_html__( 'City Tax Fee','wpestate').':</span> ' . $city_fee_show; 
              if($city_fee_per_day==1){
                $return_string .= ' '.esc_html__('per night','wpestate');
            }
            $return_string.='</div>'; 
            
        }
        
        if ($min_days_booking!=0){
            $return_string.='<div class="listing_detail list_detail_prop_price_min_nights col-md-6"><span class="item_head">'.esc_html__( 'Minimum no of nights','wpestate').':</span> ' . $min_days_booking . '</div>'; 
        }
        
        if($overload_guest!=0){
            $return_string.='<div class="listing_detail list_detail_prop_price_overload_guest col-md-6"><span class="item_head">'.esc_html__( 'Allow more guests than the capacity: yes','wpestate').'</span></div>'; 
        }
        
       
       
        if ($checkin_change_over!=0){
            $return_string.='<div class="listing_detail list_detail_prop_book_starts col-md-6"><span class="item_head">'.esc_html__( 'Booking starts only on','wpestate').':</span> ' . $week_days[$checkin_change_over ]. '</div>'; 
        }
        
        if ($checkin_checkout_change_over!=0){
            $return_string.='<div class="listing_detail list_detail_prop_book_starts_end col-md-6"><span class="item_head">'.esc_html__( 'Booking starts/ends only on','wpestate').':</span> ' .$week_days[$checkin_checkout_change_over] . '</div>'; 
        }
        
        
        return $return_string;

    }
endif;

///////////////////////////////////////////////////////////////////////////////////////////
// custom details
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_show_custom_details') ):
    function wpestate_show_custom_details($edit_id,$is_dash=0){
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
        $price_per_guest_from_one       =   floatval   ( get_post_meta($edit_id, 'price_per_guest_from_one', true) );
     
        $currency                       = esc_html( get_option('wp_estate_currency_symbol', '') );
        $where_currency                 = esc_html( get_option('wp_estate_where_currency_symbol', '') );
        
        $mega           =   wpml_mega_details_adjust($edit_id);
        $price_array    =   wpml_custom_price_adjust($edit_id);
        
        
        if (empty($mega) && empty($price_array)){
            return;
        }
        
        
        if(is_array($mega)){
            // sort arry by key
            ksort($mega);
        

            $flag=0;
            $flag_price         ='';
            $flag_min_days      ='';
            $flag_guest         ='';
            $flag_price_week    ='';
            $flag_change_over   ='';
            $flag_checkout_over ='';

            print '<div class="custom_day_wrapper';
            if($is_dash==1){
                print ' custom_day_wrapper_dash ';
            }
            print '">';

            print '
            <div class="custom_day custom_day_header"> 
                <div class="custom_day_from_to">'.esc_html__('Period','wpestate').'</div>';
                
                if($price_per_guest_from_one!=1){
                    print'
                    <div class="custom_price_per_day">'.esc_html__('Price per night','wpestate').'</div>    
                    <div class="custom_day_min_days">'.esc_html__('Minimum Booking days','wpestate').'</div>   
                    <div class="custom_day_name_price_per_guest">'.esc_html__('Extra price per guest','wpestate').'</div>
                    <div class="custom_day_name_price_per_weekedn">'.esc_html__('Price per night in weekends','wpestate').'</div>';
                }else{
                    print '<div class="custom_day_name_price_per_guest">'.esc_html__('Price per guest','wpestate').'</div>';
                }
            
             
                print'
                <div class="custom_day_name_change_over">'.esc_html__('Booking starts only on','wpestate').'</div>
                <div class="custom_day_name_checkout_change_over">'.esc_html__('Booking starts/ends only on','wpestate').'</div>';
                
                if($is_dash==1){
                    print '<div class="delete delete_custom_period"></div>';
                }
                
            print'</div>';  
            
          //  print_r($mega);
            foreach ($mega as $day=>$data_day){          
                $checker            =   0;
                $from_date          =   new DateTime("@".$day);
                $to_date            =   new DateTime("@".$day);
                $tomorrrow_date     =   new DateTime("@".$day);
                
                $tomorrrow_date->modify('tomorrow');
                $tomorrrow_date     =   $tomorrrow_date->getTimestamp();
               
                //we set the flags
                //////////////////////////////////////////////////////////////////////////////////////////////
                if ($flag==0){
                    $flag=1;
                    if(isset($price_array[$day])){
                        $flag_price         =   $price_array[$day];
                    }
                    $flag_min_days      =   $data_day['period_min_days_booking'];
                    $flag_guest         =   $data_day['period_extra_price_per_guest'];
                    $flag_price_week    =   $data_day['period_price_per_weekeend'];
                    $flag_change_over   =   $data_day['period_checkin_change_over'];
                    $flag_checkout_over =   $data_day['period_checkin_checkout_change_over'];
                    $from_date_unix     =   $from_date->getTimestamp();
                    print' <div class="custom_day">';
                    print' <div class="custom_day_from_to"> '.esc_html__('From','wpestate').' '. $from_date->format('Y-m-d');
                }

                
                
    
                //we check period chane
                //////////////////////////////////////////////////////////////////////////////////////////////
                if ( !array_key_exists ($tomorrrow_date,$mega) ){ // non consecutive days
                    $checker = 1; 
                 
                }else {
                    if( isset($price_array[$tomorrrow_date]) && $flag_price!=$price_array[$tomorrrow_date] ){
                        // IF PRICE DIFFRES FROM DAY TO DAY
                        $checker = 1;     
                    }
                    if( $mega[$tomorrrow_date]['period_min_days_booking']                !=  $flag_min_days || 
                        $mega[$tomorrrow_date]['period_extra_price_per_guest']           !=  $flag_guest || 
                        $mega[$tomorrrow_date]['period_price_per_weekeend']              !=  $flag_price_week || 
                        $mega[$tomorrrow_date]['period_checkin_change_over']             !=  $flag_change_over ||  
                        $mega[$tomorrrow_date]['period_checkin_checkout_change_over']    !=  $flag_checkout_over){
                            // IF SOME DATA DIFFRES FROM DAY TO DAY
                       
                            $checker = 1;
                        } 

                }
/*
   print 'xxxx '.$from_date->format('Y-m-d').' / '.$tomorrrow_date.' - > '.$checker;
   print '%%%%'.  $data_day['period_extra_price_per_guest'].'/'.$flag_guest.'%%%%%';
  print    '</br>flag '.$flag.' --- '. $flag_price .' --- '.$flag_min_days .'---'.$flag_guest.'</br>';   
*/
                if (  $checker == 0 ){
                    // we have consecutive days, data stays the sa,e- do not print 
                } else{
                    // no consecutive days - we CONSIDER print


                        if($flag==1){
                           
                         //   $to_date->modify('yesterday');
                            $to_date_unix     =   $from_date->getTimestamp();
                            print ' '.esc_html__('To','wpestate').' '. $from_date->format('Y-m-d').'</div>';
                           
                            if($price_per_guest_from_one!=1){
                                print'
                                <div class="custom_price_per_day">';
                                if( isset($price_array[$day]) ){
                                    echo   wpestate_show_price_booking($price_array[$day],$currency,$where_currency,1);
                                }else{
                                    echo '-';
                                }
                                print'</div>
                                <div class="custom_day_min_days">';
                                if( $flag_min_days!=0 ){
                                    echo $flag_min_days;
                                }else{
                                    echo '-';
                                }
                                print '</div>   
                                <div class="custom_day_name_price_per_guest">';
                                if($flag_guest!=0){
                                    echo wpestate_show_price_booking($flag_guest,$currency,$where_currency,1);
                                }else{
                                    echo '-';
                                }
                                print '</div>
                                <div class="custom_day_name_price_per_weekedn">';
                                if( $flag_price_week!=0 ){
                                    echo   wpestate_show_price_booking($flag_price_week,$currency,$where_currency,1);
                                }else{
                                    echo '-';
                                }
                                print '</div>';
                            }else{
                                print '<div class="custom_day_name_price_per_guest">'.wpestate_show_price_booking($flag_guest,$currency,$where_currency,1).'</div>';
                            }
                            
                            print'
                            <div class="custom_day_name_change_over">';
                            if( intval( $flag_change_over ) !=0 ){
                                echo $week_days[ $flag_change_over ];
                            }else{
                                esc_html_e('All','wpestate');
                            }
                            
                            print '</div>
                            <div class="custom_day_name_checkout_change_over">';
                            if( intval ( $flag_checkout_over ) !=0 ) {
                                echo $week_days[ $flag_checkout_over ];
                            }else{
                                esc_html_e('All','wpestate');
                            }
                            
                            print '</div>';
                            
                            if($is_dash==1){
                                print '<div class="delete delete_custom_period" data-editid="'.$edit_id.'" data-fromdate="'.$from_date_unix.'" data-todate="'.$to_date_unix.'"><a href="#"> '.esc_html__('delete period','wpestate').'</a></div>';
                            }
                            
                            print '</div>'; 
                        }
                        $flag=0;
                        if( isset( $price_array[$day])){
                            $flag_price         =   $price_array[$day];
                        }
                        $flag_min_days      =   $data_day['period_min_days_booking'];
                        $flag_guest         =   $data_day['period_extra_price_per_guest'];
                        $flag_price_week    =   $data_day['period_price_per_weekeend'];
                        $flag_change_over   =   $data_day['period_checkin_change_over'];
                        $flag_checkout_over =   $data_day['period_checkin_change_over'];
                }
            }
            print '</div>';
        }
    }
endif;    


///////////////////////////////////////////////////////////////////////////////////////////
// dashboard favorite listings
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('estate_listing_address') ):
    function estate_listing_address($post_id){

        $property_address   = esc_html( get_post_meta($post_id, 'property_address', true) );
        $property_city      = get_the_term_list($post_id, 'property_city', '', ', ', '');
        $property_area      = get_the_term_list($post_id, 'property_area', '', ', ', '');
        $property_county    = esc_html( get_post_meta($post_id, 'property_county', true) );
        $property_state     = esc_html(get_post_meta($post_id, 'property_state', true) );
        $property_zip       = esc_html(get_post_meta($post_id, 'property_zip', true) );
        $property_country   = esc_html(get_post_meta($post_id, 'property_country', true) );

        $return_string='';

        if ($property_address != ''){
            $return_string.='<div class="listing_detail list_detail_prop_address col-md-6"><span class="item_head">'.esc_html__( 'Address','wpestate').':</span> ' . $property_address . '</div>'; 
        }
        if ($property_city != ''){
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__( 'City','wpestate').':</span> ' .$property_city. '</div>';  
        }  
        if ($property_area != ''){
            $return_string.= '<div class="listing_detail list_detail_prop_area col-md-6"><span class="item_head">'.esc_html__( 'Area','wpestate').':</span> ' .$property_area. '</div>';
        }    
        if ($property_county != ''){
            $return_string.= '<div class="listing_detail list_detail_prop_county col-md-6"><span class="item_head">'.esc_html__( 'County','wpestate').':</span> ' . $property_county . '</div>'; 
        }
        if ($property_state != ''){
            $return_string.= '<div class="listing_detail list_detail_prop_state col-md-6"><span class="item_head">'.esc_html__( 'State','wpestate').':</span> ' . $property_state . '</div>'; 
        }
        if ($property_zip != ''){
            $return_string.= '<div class="listing_detail list_detail_prop_zip col-md-6"><span class="item_head">'.esc_html__( 'Zip','wpestate').':</span> ' . $property_zip . '</div>';
        }  
        if ($property_country != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_contry col-md-6"><span class="item_head">'.esc_html__( 'Country','wpestate').':</span> ' . $property_country . '</div>'; 
        } 

        return  $return_string;
    }
endif; // end   estate_listing_address  



if( !function_exists('estate_listing_address_print') ):
    function estate_listing_address_print($post_id){

        $property_address   = esc_html( get_post_meta($post_id, 'property_address', true) );
        $property_city      = strip_tags (  get_the_term_list($post_id, 'property_city', '', ', ', '') );
        $property_area      = strip_tags ( get_the_term_list($post_id, 'property_area', '', ', ', '') );
        $property_county    = esc_html( get_post_meta($post_id, 'property_county', true) );
        $property_state     = esc_html(get_post_meta($post_id, 'property_state', true) );
        $property_zip       = esc_html(get_post_meta($post_id, 'property_zip', true) );
        $property_country   = esc_html(get_post_meta($post_id, 'property_country', true) );

        $return_string='';

        if ($property_address != ''){
            $return_string.='<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__( 'Address','wpestate').':</span> ' . $property_address . '</div>'; 
        }
        if ($property_city != ''){
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__( 'City','wpestate').':</span> ' .$property_city. '</div>';  
        }  
        if ($property_area != ''){
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__( 'Area','wpestate').':</span> ' .$property_area. '</div>';
        }    
        if ($property_county != ''){
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__( 'County','wpestate').':</span> ' . $property_county . '</div>'; 
        }
        if ($property_state != ''){
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__( 'State','wpestate').':</span> ' . $property_state . '</div>'; 
        }
        if ($property_zip != ''){
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__( 'Zip','wpestate').':</span> ' . $property_zip . '</div>';
        }  
        if ($property_country != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__( 'Country','wpestate').':</span> ' . $property_country . '</div>'; 
        } 

        return  $return_string;
    }
endif; // end   estate_listing_address  



///////////////////////////////////////////////////////////////////////////////////////////
// dashboard favorite listings
///////////////////////////////////////////////////////////////////////////////////////////




if( !function_exists('estate_listing_details') ):
    function estate_listing_details($post_id){

        $currency       =   esc_html( get_option('wp_estate_currency_symbol', '') );
        $where_currency =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
        $measure_sys    =   esc_html ( get_option('wp_estate_measure_sys','') ); 
        $property_size  =   intval( get_post_meta($post_id, 'property_size', true) );

        if ($property_size  != '') {
            $property_size  = number_format($property_size) . ' '.$measure_sys.'<sup>2</sup>';
        }

        $property_lot_size = intval( get_post_meta($post_id, 'property_lot_size', true) );

        if ($property_lot_size != '') {
            $property_lot_size = number_format($property_lot_size) . ' '.$measure_sys.'<sup>2</sup>';
        }

        $property_rooms     = floatval ( get_post_meta($post_id, 'property_rooms', true) );
        $property_bedrooms  = floatval ( get_post_meta($post_id, 'property_bedrooms', true) );
        $property_bathrooms = floatval ( get_post_meta($post_id, 'property_bathrooms', true) );     
        $property_status= stripslashes ( esc_html(get_post_meta($post_id, 'property_status', true) ) );

        $return_string='';


        if ($property_status != ''){
            $return_string.= '<div class="listing_detail list_detail_prop_status col-md-6"><span class="item_head">'.esc_html__( 'Property Status','wpestate').':</span> ' . $property_status . '</div>';
        }   
        
        $return_string.= '<div class="listing_detail list_detail_prop_id col-md-6"><span class="item_head">'.esc_html__( 'Property ID','wpestate').':</span> ' . $post_id . '</div>';
      
        
        if ($property_size != ''){
            $return_string.= '<div class="listing_detail list_detail_prop_size col-md-6"><span class="item_head">'.esc_html__( 'Property Size','wpestate').':</span> ' . $property_size . '</div>';
        }               
        if ($property_lot_size != ''){
            $return_string.= '<div class="listing_detail list_detail_prop_lot_size  col-md-6"><span class="item_head">'.esc_html__( 'Property Lot Size','wpestate').':</span> ' . $property_lot_size . '</div>';
        }      
        if ($property_rooms != ''){
            $return_string.= '<div class="listing_detail list_detail_prop_rooms col-md-6"><span class="item_head">'.esc_html__( 'Rooms','wpestate').':</span> ' . $property_rooms . '</div>'; 
        }      
        if ($property_bedrooms != ''){
            $return_string.= '<div class="listing_detail list_detail_prop_bedrooms col-md-6"><span class="item_head">'.esc_html__( 'Bedrooms','wpestate').':</span> ' . $property_bedrooms . '</div>'; 
        }     
        if ($property_bathrooms != '')    {
            $return_string.= '<div class="listing_detail list_detail_prop_bathrooms col-md-6"><span class="item_head">'.esc_html__( 'Bathrooms','wpestate').':</span> ' . $property_bathrooms . '</div>'; 
        }      


        // Custom Fields 


        $i=0;
        $custom_fields = get_option( 'wp_estate_custom_fields', true); 
        if( !empty($custom_fields)){  
            while($i< count($custom_fields) ){
               $name =   $custom_fields[$i][0];
               $label=   $custom_fields[$i][1];
               $type =   $custom_fields[$i][2];
           //    $slug =   sanitize_key ( str_replace(' ','_',$name) );
               $slug         =   wpestate_limit45(sanitize_title( $name ));
               $slug         =   sanitize_key($slug);

               $value=esc_html(get_post_meta($post_id, $slug, true));
               if (function_exists('icl_translate') ){
                    $label     =   icl_translate('wpestate','wp_estate_property_custom_'.$label, $label ) ;
                    $value     =   icl_translate('wpestate','wp_estate_property_custom_'.$value, $value ) ;                                      
               }
               
               $label = stripslashes ($label);
               
               if($value!=''){
                   $return_string.= '<div class="listing_detail list_detail_prop_'.( strtolower( str_replace(' ','_',$label) ) ).' col-md-6"><span class="item_head">'.ucwords($label).':</span> ' .$value. '</div>'; 
               }
               $i++;       
            }
        }

         //END Custom Fields 



        return $return_string;
    }
endif; // end   estate_listing_details  
?>