<?php
// Template Name: User Dashboard Invoices
// Wp Estate Pack

if ( !is_user_logged_in() ) {   
     wp_redirect(  esc_html( home_url() ) );exit();
} 

if ( !wpestate_check_user_level()){
   wp_redirect(  esc_html( home_url() ) );exit(); 
}


$current_user = wp_get_current_user();    
$paid_submission_status         =   esc_html ( get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( get_option('wp_estate_price_submission','') );
$submission_curency_status      =   wpestate_curency_submission_pick();;
$userID                         =   $current_user->ID;

$show_remove_fav                =   1;   
$show_compare                   =   1;
$show_compare_only              =   'no';
$where_currency                =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
get_header();
$options=wpestate_page_details($post->ID);
?> 


<div class="row is_dashboard">
  
    <?php
    if( wpestate_check_if_admin_page($post->ID) ){
        if ( is_user_logged_in() ) {   
            get_template_part('templates/user_menu'); 
        }  
    }
    ?> 
    
    <div class=" dashboard-margin">
        
        <div class="dashboard-header">
            <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') { ?>
                <h1 class="entry-title listings-title-dash"><?php the_title(); ?></h1>
            <?php } ?>
        </div>
            

        
        <div class="row admin-list-wrapper invoices-wrapper">    
        <?php
        
            $args = array(
                'post_type'        => 'wpestate_invoice',
                'post_status'      => 'publish',
                'posts_per_page'   => -1 ,
                'author'           => $userID,
                'meta_query'       => array(
                        array(
                            'key'       => 'invoice_type',
                            'value'     => 'Reservation Fee',
                            'type'      =>  'char',
                            'compare'   => 'LIKE'
                            )
                ),
            );
          


            $prop_selection = new WP_Query($args);
            $counter                =   0;
            $options['related_no']  =   4;
            $total_confirmed        =   0;
            $total_issued           =   0;
            $templates              =   esc_html__( 'No invoices','wpestate');
            
            if( $prop_selection->have_posts() ){
                ob_start(); 
                while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                    get_template_part('templates/invoice_listing_unit'); 
                    $status = esc_html(get_post_meta($post->ID, 'invoice_status', true));
                    $type   = esc_html(get_post_meta($post->ID, 'invoice_type', true));
                    $price  = esc_html(get_post_meta($post->ID, 'item_price', true));
                    
                    if( trim($type) == 'Reservation fee'){
                        if($status == 'confirmed' ){
                            $total_confirmed = $total_confirmed + $price;
                        }
                        if($status == 'issued' ){
                            $total_issued = $total_issued + $price;
                        }
                    }else{
                        $total_issued='-';
                        $total_confirmed = $total_confirmed + $price;
                    }
                    
                  
                 
                endwhile;
                $templates = ob_get_contents();
                ob_end_clean(); 
            }
               
                print '<div class="col-md-12 invoice_filters">
                    <div class="col-md-3">
                        <input type="text" id="invoice_start_date" class="form-control" name="invoice_start_date" placeholder="'.esc_html__( 'from date','wpestate').'"> 
                    </div>
                    
                    <div class="col-md-3">
                        <input type="text" id="invoice_end_date" class="form-control"  name="invoice_end_date" placeholder="'.esc_html__( 'start date','wpestate').'"> 
                    </div>
                    

                    <div class="col-md-3">
                        <select id="invoice_type" name="invoice_type" class="form-control">
                            <option value="Upgrade to Featured">'.esc_html__( 'Upgrade to Featured','wpestate').'</option>   
                            <option value="Publish Listing with Featured">'.esc_html__( 'Publish Listing with Featured','wpestate').'</option>
                            <option value="Package">'.esc_html__( 'Package','wpestate').'</option>
                            <option value="Listing">'.esc_html__( 'Listing','wpestate').'</option>
                            <option value="Reservation fee" selected="selected">'.esc_html__( 'Reservation fee','wpestate').'</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <select id="invoice_status" name="invoice_status" class="form-control">
                            <option value="">'.esc_html__( 'Any','wpestate').'</option>
                            <option value="confirmed">'.esc_html__( 'confirmed','wpestate').'</option>
                            <option value="issued">'.esc_html__( 'issued','wpestate').'</option>   
                        </select>
                    
                    </div>

                </div>
                    
                <div class="invoices_explanation">'.esc_html__( 'Reservation fees filter applies only to the invoices issued by you!','wpestate').'</div>

                <div class="col-md-12 invoice_totals">
                <strong>'.esc_html__( 'Total Invoices Confirmed: ','wpestate').'</strong><span id="invoice_confirmed">'.wpestate_show_price_custom_invoice($total_confirmed).'</span>
                <strong>'.esc_html__( 'Total Invoices Issued: ','wpestate').'</strong><span id="invoice_issued">'.wpestate_show_price_custom_invoice($total_issued).'</span>
                </div>
                ';
                
                
                print '<div class="col-md-12 invoice_unit_title">
                    <div class="col-md-2">
                        <strong> '.esc_html__( 'Title','wpestate').'</strong> 
                    </div>

                    <div class="col-md-2">
                         <strong> '.esc_html__( 'Date','wpestate').'</strong> 
                    </div>

                    <div class="col-md-2">
                         <strong> '.esc_html__( 'Invoice Type','wpestate').'</strong> 
                    </div>

                    <div class="col-md-2">
                        <strong> '.esc_html__( 'Billing Type','wpestate').'</strong> 
                    </div>

                    <div class="col-md-2">
                        <strong> '.esc_html__( 'Status','wpestate').'</strong> 
                    </div>

                    <div class="col-md-2">
                         <strong> '.esc_html__( 'Price','wpestate').'</strong> 
                    </div>
                </div>
                ';
                
                print '<div id="container-invoices">';
                print $templates;
                print '</div>';
            
        
        ?>    
        </div>          
    </div>
</div>   

<?php 
wp_reset_query();
get_footer(); 
?>