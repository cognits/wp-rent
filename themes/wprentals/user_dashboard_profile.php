<?php
// Template Name: User Dashboard Profile Page
// Wp Estate Pack

$current_user = wp_get_current_user();
$dash_profile_link = wpestate_get_dashboard_profile_link();
 
//////////////////////////////////////////////////////////////////////////////////////////
// Paypal payments for membeship packages
//////////////////////////////////////////////////////////////////////////////////////////
if (isset($_GET['token']) ){
    $allowed_html       =   array();
    $token              =   sanitize_text_field ( wp_kses ( esc_html($_GET['token']) ,$allowed_html)  );
    $token_recursive    =   sanitize_text_field ( wp_kses ( esc_html($_GET['token']) ,$allowed_html ) );
       
    // get transfer data
    $save_data              =   get_option('paypal_pack_transfer');
    $payment_execute_url    =   $save_data[$current_user->ID ]['paypal_execute'];
    $token                  =   $save_data[$current_user->ID ]['paypal_token'];
    $pack_id                =   $save_data[$current_user->ID ]['pack_id'];
    $recursive              =   0;
  
    if (isset ( $save_data[$current_user->ID ]['recursive']) ){
        $recursive  =   $save_data[$current_user->ID ]['recursive']; 
    }

    if($recursive!=1){
        if( isset($_GET['PayerID']) ){
            $payerId             =   wp_kses ( esc_html($_GET['PayerID']),$allowed_html );  

            $payment_execute = array(
                           'payer_id' => $payerId
                          );
            $json = json_encode($payment_execute);
            $json_resp = wpestate_make_post_call($payment_execute_url, $json,$token);

            $save_data[$current_user->ID ]=array();
            update_option ('paypal_pack_transfer',$save_data); 

            if($json_resp['state']=='approved' ){
                if( wpestate_check_downgrade_situation($current_user->ID,$pack_id) ){
                    wpestate_downgrade_to_pack( $current_user->ID, $pack_id );
                    wpestate_upgrade_user_membership($current_user->ID,$pack_id,1,'');
                }else{
                    wpestate_upgrade_user_membership($current_user->ID,$pack_id,1,'');
                }
                wp_redirect( $dash_profile_link ); exit();
            }
        } //end if Get
    }else{
         
        require get_template_directory().'/libs/resources/paypalfunctions.php';   
        $billing_period                 =   get_post_meta($pack_id, 'biling_period', true);
        $billing_freq                   =   intval(get_post_meta($pack_id, 'billing_freq', true));        
        $obj=new paypal_recurring;
        $obj->environment       =   esc_html( get_option('wp_estate_paypal_api','') );
        $obj->paymentType       =   urlencode('Sale');          // or 'Sale' or 'Order'
        $paypal_api_username    =   esc_html( get_option('wp_estate_paypal_api_username','') );
        $paypal_api_password    =   esc_html( get_option('wp_estate_paypal_api_password','') );
        $paypal_api_signature   =   esc_html( get_option('wp_estate_paypal_api_signature','') );    
        $obj->API_UserName      =   urlencode( $paypal_api_username );
        $obj->API_Password      =   urlencode( $paypal_api_password );
        $obj->API_Signature     =   urlencode( $paypal_api_signature );
        $obj->API_Endpoint      =   "https://api-3t.paypal.com/nvp";
        $obj->paymentType       =   urlencode('Sale');  
        $obj->returnURL         =   urlencode($dash_profile_link);
        $obj->cancelURL         =   urlencode($dash_profile_link);
        $obj->paymentAmount     =   get_post_meta($pack_id, 'pack_price', true);
        $obj->currencyID        =   get_option('wp_estate_submission_curency','');
        $date                   =   $save_data[$current_user->ID ]['date'];
        $obj->startDate         =   urlencode($date);
        $obj->billingPeriod     =   urlencode($billing_period);         
        $obj->billingFreq       =   urlencode($billing_freq); 
        $pack_name              =   get_the_title($pack_id);
        $obj->productdesc       =   urlencode($pack_name.esc_html__( ' package on ','wpestate').get_bloginfo('name') );
        $obj->user_id           =   $current_user->ID;
        $obj->pack_id           =   $pack_id;
        
       if ( $obj->getExpressCheckout($token_recursive) ){           
            if( wpestate_check_downgrade_situation($current_user->ID,$pack_id) ){
                wpestate_downgrade_to_pack( $current_user->ID, $pack_id );
                wpestate_upgrade_user_membership($current_user->ID,$pack_id,2,'');
            }else{
                wpestate_upgrade_user_membership($current_user->ID,$pack_id,2,'');
            }      
            wp_redirect( $dash_profile_link );  exit();
        }
    }                    
}


//////////////////////////////////////////////////////////////////////////////////////////
// 3rd party login code
//////////////////////////////////////////////////////////////////////////////////////////

if( ( isset($_GET['code']) && isset($_GET['state']) ) ){
    estate_facebook_login($_GET);
}else if(isset($_GET['openid_mode']) && $_GET['openid_mode']=='id_res' ){   
    estate_open_id_login($_GET);
}else if (isset($_GET['code'])){
    estate_google_oauth_login($_GET);
}else{
    if ( !is_user_logged_in() ) {   
        wp_redirect(  esc_html( home_url() ) );exit();
    }
  
}
   
$paid_submission_status         =   esc_html ( get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( get_option('wp_estate_price_submission','') );
$submission_curency_status      =   esc_html( get_option('wp_estate_submission_curency','') );
$edit_link                      =   wpestate_get_dasboard_add_listing();
$processor_link                 =   wpestate_get_procesor_link();
$options                        =   wpestate_page_details($post->ID);
get_header();
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
        
        <?php while (have_posts()) : the_post(); ?>
        
            <div class="dashboard-header">
                <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') { ?>
                    <h1 class="entry-title entry-title-profile"><?php the_title(); ?></h1>
                <?php } ?>
            </div>
        
            <div class="single-content"><?php the_content();?></div><!-- single content-->
           <?php endwhile; // end of the loop. ?>
           <?php    get_template_part('templates/user_profile'); ?>
    </div>
</div>   
<?php get_footer(); ?>