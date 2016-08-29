<?php
global $post;
global $where_currency;
global $currency;
global $userID;
global $user_login;

$link               =   esc_url (get_permalink());
$booking_status     =   get_post_meta($post->ID, 'booking_status', true);
$booking_id         =   get_post_meta($post->ID, 'booking_id', true);
$booking_from_date  =   get_post_meta($post->ID, 'booking_from_date', true);
$booking_to_date    =   get_post_meta($post->ID, 'booking_to_date', true);
$booking_guests     =   get_post_meta($post->ID, 'booking_guests', true);
$preview            =   wp_get_attachment_image_src(get_post_thumbnail_id($booking_id), 'wpestate_blog_unit');
$author             =   get_the_author();

$invoice_no         =   get_post_meta($post->ID, 'booking_invoice_no', true);
$booking_pay        =   get_post_meta($post->ID, 'booking_pay_ammount', true);
$booking_company    =   get_post_meta($post->ID, 'booking_company', true);
    

$no_of_days         =   ( strtotime($booking_to_date)-strtotime($booking_from_date) ) / (60*60*24);
$property_price     =   get_post_meta($booking_id, 'property_price', true);
$price_per_option   =   intval(get_post_meta($booking_id, 'price_per', true));
if ($price_per_option!=0){
    $property_price     =   round ( $property_price/$price_per_option,2);
}
$price_per_booking  =   $no_of_days *$property_price;
$event_description  =   get_the_content();   




if($invoice_no== 0){
    $invoice_no='-';
}else{
    $price_per_booking=get_post_meta($invoice_no, 'item_price', true);
}
$price_per_booking=floatval($price_per_booking);
$price_per_booking = number_format($price_per_booking,2,'.',',');

if ($where_currency == 'before') {
    $price_per_booking = $currency . ' ' . $price_per_booking;
} else {
    $price_per_booking = $price_per_booking . ' ' . $currency;
}


?>

<div class="col-md-12 ">
    <div class="dasboard-prop-listing ">
        <div class="blog_listing_image book_image">
           <a href="<?php print esc_url ( get_permalink($booking_id) );?>"> 
            <?php if (has_post_thumbnail($booking_id)){?>
            <img  src="<?php  print $preview[0]; ?>"  class="img-responsive" alt="slider-thumb" />
            <?php 
            
            }else{
                $thumb_prop_default =  get_template_directory_uri().'/img/defaultimage_prop.jpg';
                ?>
           
                <img  src="<?php  print $thumb_prop_default; ?>"  class="img-responsive" alt="slider-thumb" />
            <?php }?>
            </a>
        </div>


        <div class="prop-info">
            <h4 class="listing_title_book">
                <?php
                the_title();  
                print ' <strong>'. esc_html__( 'for','wpestate').'</strong> <a href="'.esc_url ( get_permalink($booking_id)).'">'.get_the_title($booking_id).'</a>'; 
                ?>      
            </h4>




            <div class="user_dashboard_listed">
                <strong><?php esc_html_e('Period: ','wpestate');?>   </strong>  <?php print $booking_from_date.' <strong>'.esc_html__( 'to','wpestate').'</strong> '.$booking_to_date; ?>
            </div>

            <div class="user_dashboard_listed">
                <strong><?php esc_html_e('Invoice No: ','wpestate');?></strong> <span class="invoice_list_id"><?php print $invoice_no;?></span>   
            </div>

            <div class="user_dashboard_listed">
                <strong><?php esc_html_e('Guests: ','wpestate');?> </strong> <?php print $booking_guests; ?>  
            </div>    

            <?php 
            if($event_description!=''){
                print ' <div class="user_dashboard_listed event_desc"><strong>'.esc_html__( 'Comment: ','wpestate').'</strong>'.$event_description.'</div>';
            }
            ?>
        </div>


        <div class="info-container_booking">
            <?php 
            if ($booking_status=='confirmed'){
                print '<span class="tag-published">'.esc_html__( 'Confirmed','wpestate').'</span>';
                print '<span class="tag-published confirmed_booking" data-invoice-confirmed="'.$invoice_no.'" data-booking-confirmed="'.$post->ID.'">'.esc_html__( 'View Details','wpestate').'</span>';       
                
                
                if(strtotime($booking_to_date) < time() ){
                    if ( get_post_meta($booking_id,'review_by_'.$userID,true) != 'has' ){
                        print '<span class="tag-post-review post_review" data-bookid="'.$post->ID.'" data-listing-review="'.$booking_id.'">'.esc_html__( 'Post Review','wpestate').'</span>';
                    }else{
                        print '<span class="tag-published">'.esc_html__( 'You already reviewed this property!','wpestate').'</span>';
                    }
                }else{
                    print '<span class="tag-published">'.esc_html__( 'You can post the review after the trip!','wpestate').'</span>'; 
                }
                
              //   print '<span class="cancel_user_booking user_cancelation" data-listing-id="'.$booking_id.'"  data-booking-confirmed="'.$post->ID.'">'.esc_html__( 'Cancel booking','wpestate').'</span>';
            
                  
            }else if( $booking_status=='waiting'){
                print '<span class="proceed-payment" data-invoiceid="'.$invoice_no.'" data-bookid="'.$post->ID.'">'.esc_html__( 'Invoice Created - Check & Pay','wpestate').'</span>';                  
                print '<span class="delete_booking usercancel" data-bookid="'.$post->ID.'">'.esc_html__( 'Cancel Booking Request','wpestate').'</span>';              
            }else{
                print '<span class="waiting_payment_user" data-bookid="'.$post->ID.'">'.esc_html__( 'Request Pending','wpestate').'</span>';            
                print '<span class="delete_booking usercancel" data-bookid="'.$post->ID.'">'.esc_html__( 'Cancel Booking Request','wpestate').'</span>';  

            } 

            ?>

        </div>

    </div>
</div>