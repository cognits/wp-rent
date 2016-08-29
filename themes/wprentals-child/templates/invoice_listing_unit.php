<?php
global $post;
global $userID;
global $where_currency;
global $current_user;
?>

<div class="col-md-12 invoice_unit " data-booking-confirmed="<?php echo esc_html(get_post_meta($post->ID, 'item_id', true));?>" data-invoice-confirmed="<?php echo $post->ID; ?>">
    <div class="col-md-2">
         <?php echo get_the_title(); ?> 
    </div>
    
    <div class="col-md-2">
        <?php echo get_the_date(); ?> 
    </div>
    
    <div class="col-md-2">
        <?php  echo esc_html(get_post_meta($post->ID, 'invoice_type', true)); ?>
    </div>
    
    <div class="col-md-2">
        <?php echo esc_html(get_post_meta($post->ID, 'biling_type', true));?>
    </div>
    
    <div class="col-md-2">
           <?php echo esc_html(get_post_meta($post->ID, 'invoice_status', true));?>      
    </div>
    
    <div class="col-md-2">
        <?php 
        $price = get_post_meta($post->ID, 'item_price', true);
        $currency                   =   esc_html( get_post_meta($post->ID, 'invoice_currency',true) );
       
      
       echo wpestate_show_price_booking_for_invoice($price,$currency,$where_currency,0,1) ?>
    </div>
</div>
