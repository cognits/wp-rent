<?php
global $post;
global $where_currency;
global $currency;
global $user_login;

$link               =   esc_url(get_permalink());
$booking_status     =   get_post_meta($post->ID, 'booking_status', true);
$booking_id         =   get_post_meta($post->ID, 'booking_id', true);
$booking_from_date  =   get_post_meta($post->ID, 'booking_from_date', true);
$booking_to_date    =   get_post_meta($post->ID, 'booking_to_date', true);
$booking_guests     =   get_post_meta($post->ID, 'booking_guests', true);
$preview            =   wp_get_attachment_image_src(get_post_thumbnail_id($booking_id), 'wpestate_blog_unit');
$author             =   get_the_author();


//$author_id          =   get_the_author_id();

$author_id          =   get_the_author_meta('ID');
$userid_agent       =   get_user_meta($author_id, 'user_agent_id', true);
$invoice_no         =   get_post_meta($post->ID, 'booking_invoice_no', true);


$booking_array      =   wpestate_booking_price($booking_guests,$invoice_no,$booking_id, $booking_from_date, $booking_to_date);
        
$invoice_no         =   get_post_meta($post->ID, 'booking_invoice_no', true);
$booking_pay        =   $booking_array['total_price'];
$booking_company    =   get_post_meta($post->ID, 'booking_company', true);
    

$no_of_days         =   $booking_array['numberDays'];
$property_price     =   $booking_array['default_price'];
$event_description  =   get_the_content();   




if($invoice_no== 0){
    $invoice_no='-';
}
$price_per_booking         =   wpestate_show_price_booking($booking_array['total_price'],$currency,$where_currency,1);            

?>


<div class="col-md-12 ">
    <div class="dasboard-prop-listing">
    
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
            print ' <strong>'. esc_html__( 'for','wpestate').'</strong> <a href="'.esc_url (get_permalink($booking_id) ).'">'.get_the_title($booking_id).'</a>'; 
            ?>      
        </h4>
        
        
        
        <div class="user_dashboard_listed">
            <span class="booking_details_title">  <?php esc_html_e('Request by ','wpestate');?></span><a href="<?php echo get_permalink($userid_agent);?>" target="_blank"><?php print $author; ?></a>
        </div>
        
        <div class="user_dashboard_listed">
            <span class="booking_details_title"><?php esc_html_e('Period: ','wpestate');?>   </span>  <?php print $booking_from_date.' <strong>'.esc_html__( 'to','wpestate').'</strong> '.$booking_to_date; ?>
        </div>
        
        <?php if( $author!= $user_login ) { ?>
            <div class="user_dashboard_listed">
                <span class="booking_details_title"><?php esc_html_e('Invoice No: ','wpestate');?></span> <span class="invoice_list_id"><?php print $invoice_no;?></span>   
            </div>


            <div class="user_dashboard_listed">
                <span class="booking_details_title"><?php esc_html_e('Pay Amount: ','wpestate');?> </span> <?php print wpestate_show_price_booking ( floatval( get_post_meta($invoice_no, 'item_price', true)) ,$currency,$where_currency,1); ?>  
                <span class="booking_details_title guest_details"><?php esc_html_e('Guests: ','wpestate');?> </span> <?php print $booking_guests; ?>  
            </div>

            <div class="user_dashboard_listed">
                
            </div>  
        <?php } 
        
        if($event_description!=''){
            print ' <div class="user_dashboard_listed event_desc"> <span class="booking_details_title">'.esc_html__( 'Reservation made by owner','wpestate').'</span></div>';
            print ' <div class="user_dashboard_listed event_desc"> <span class="booking_details_title">'.esc_html__( 'Comments: ','wpestate').'</span>'.$event_description.'</div>';
        }
        ?>                
    </div>

    
    <div class="info-container_booking">
        <?php //print $booking_status;
        if ($booking_status=='confirmed'){
            print '<span class="tag-published">'.esc_html__( 'Confirmed','wpestate').'</span>';
            if( $author!= $user_login ){
                print '<span class="tag-published confirmed_booking" data-invoice-confirmed="'.$invoice_no.'" data-booking-confirmed="'.$post->ID.'">'.esc_html__( 'View Details','wpestate').'</span>';
                print '<span class="cancel_user_booking" data-listing-id="'.$booking_id.'"  data-booking-confirmed="'.$post->ID.'">'.esc_html__( 'Cancel booking','wpestate').'</span>';
            
                
            }else{
                print '<span class="cancel_own_booking" data-listing-id="'.$booking_id.'"  data-booking-confirmed="'.$post->ID.'">'.esc_html__( 'Cancel my own booking','wpestate').'</span>';
            
            }
      
        }else if( $booking_status=='waiting'){
            print '<span class="waiting_payment" data-bookid="'.$post->ID.'">'.esc_html__( 'Invoice Issued ','wpestate').'</span>';             
            print '<span class="delete_invoice" data-invoiceid="'.$invoice_no.'" data-bookid="'.$post->ID.'">'.esc_html__( 'Delete Invoice','wpestate').'</span>';
            print '<span class="delete_booking" data-bookid="'.$post->ID.'">'.esc_html__( 'Reject Booking Request','wpestate').'</span>';    
        }else{
            print '<span class="generate_invoice" data-bookid="'.$post->ID.'">'.esc_html__( 'Issue invoice','wpestate').'</span>';  
            print '<span class="delete_booking" data-bookid="'.$post->ID.'">'.esc_html__( 'Reject Booking Request','wpestate').'</span>';    
        } 
       
        ?>
        
    </div>
      
   </div> 
 </div>