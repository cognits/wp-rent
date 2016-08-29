<?php
// Template Name: User Dashboard Subscriptions
// Wp Estate Pack
if ( !is_user_logged_in() ) {   
    wp_redirect(  esc_html( home_url() ) );exit();
} 
if ( !wpestate_check_user_level()){
   wp_redirect(  esc_html( home_url() ) );exit(); 
}

global $current_user;
$current_user = wp_get_current_user();    
$paid_submission_status         =   esc_html ( get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( get_option('wp_estate_price_submission','') );
$submission_curency_status      =   wpestate_curency_submission_pick();
$userID                         =   $current_user->ID;
$user_option                    =   'favorites'.$userID;
$curent_fav                     =   get_option($user_option);
$show_remove_fav                =   1;   
$show_compare                   =   1;
$show_compare_only              =   'no';
$currency                       =   esc_html( get_option('wp_estate_currency_symbol', '') );
$where_currency                 =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
get_header();
$options                        =   wpestate_page_details($post->ID);
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
                <h1 class="entry-title entry-title-profile"><?php the_title(); ?></h1>
            <?php } ?>
        </div>
        
        <div class="row">    
        
        <div class="content-admin-dashboard">
            <?php the_content('Continue Reading'); ?>
        </div>    
            
        <?php
        $paid_submission_status = esc_html ( get_option('wp_estate_paid_submission','') );  
        if ($paid_submission_status == 'membership'){ 
        ?>
        
        <div class="col-md-12">
            <div class="user_dashboard_panel">
            <h4 class="user_dashboard_panel_title"><?php esc_html_e('Change your Package','wpestate');?></h4>
                <div class="col-md-4">
                    <?php wpestate_display_packages(); ?>
                    <input type="checkbox" name="pack_recuring" id="pack_recuring" value="1" /> 
                    <label for="pack_recurring"><?php esc_html_e('make payment recurring ','wpestate');?></label>                   
                </div>
             
                <?php
                    $enable_paypal_status   =   esc_html ( get_option('wp_estate_enable_paypal','') );
                    $enable_stripe_status   =   esc_html ( get_option('wp_estate_enable_stripe','') );
                    $enable_direct_pay      =   esc_html ( get_option('wp_estate_enable_direct_pay','') );
                     
                    print '<div class="col-md-8">';
                    if($enable_paypal_status==='yes'){
                        print '<div id="pick_pack">'.esc_html__( 'Pay with Paypal','wpestate').'</div>';
                    }
                    if($enable_stripe_status==='yes'){
                        wpestate_show_stripe_form_membership();
                    }
                    
                    if($enable_direct_pay==='yes'){
                        print '<div id="direct_pay">'.esc_html__( 'Pay via Wire transfer','wpestate').'</div>';
                    }
                    
                    print '</div>';
                ?>
             
            </div>
        </div>
            
            
       

             
        <?php
        $currency  =   wpestate_curency_submission_pick();
        $args = array(
            'post_type'         => 'membership_package',
            'posts_per_page'    => -1,
            'meta_query'        =>  array(
                                        array(
                                        'key' => 'pack_visible',
                                        'value' => 'yes',
                                        'compare' => '=',
                                    )
                                )
        );
        
        $pack_selection = new WP_Query($args);
           
            print '<div class="pack-wrapper">';
                while($pack_selection->have_posts() ){
                    $pack_selection->the_post();
                    get_template_part('templates/dashboard_pack_unit'); 
                }
            print '</div>';
        }
        ?>    
</div>   
<?php 
wp_reset_query();
get_footer(); ?>