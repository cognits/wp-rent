<?php
// Template Name:Stripe Charge Page
// Wp Estate Pack
require_once get_template_directory().'/libs/stripe/lib/Stripe.php';
$allowed_html=array();
$stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
$stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );
$stripe = array(
    "secret_key"      => $stripe_secret_key,
    "publishable_key" => $stripe_publishable_key
);

Stripe::setApiKey($stripe['secret_key']);     
//////////////////////////////////////////////////////////////////////////////////////////////////
//////////webhook part
//////////////////////////////////////////////////////////////////////////////////////////////////



// Retrieve the request's body and parse it as JSON

$input = wp_remote_get("php://input");
//$event_json = wp_remote_retrieve_body( $request );

/*

if($event_json!=''){
    $event_json = json_decode($input);
   
    $array=get_object_vars($event_json->data);
    foreach($array as $key=>$value){
        $customer_stripe_id= $value->customer;
    }

  
    if($event_json->type=='charge.failed'){
        $args   =   array(  'meta_key'      => 'stripe', 
                            'meta_value'    => $customer_stripe_id
                        );
        
        $customers  =   get_users( $args ); 
        foreach ( $customers as $user ) {
            update_user_meta( $user->ID, 'stripe', '' );
            downgrade_to_free($user->ID);
        }        
    }  
    
    
    // recurring charge
    if($event_json->type=='charge.succeeded'){
            $args=array('meta_key'      => 'stripe', 
                        'meta_value'    => $customer_stripe_id
            );
            
            $update_user_id =   0;
            $customers=get_users( $args ); 
            foreach ( $customers as $user ) {
                $update_user_id = $user->ID;
            } 
            $pack_id = intval (get_user_meta($update_user_id, 'package_id',true));
           
            if($update_user_id!=0 && $pack_id!=0){
                if( wpestate_check_downgrade_situation($update_user_id,$pack_id) ){
                    wpestate_downgrade_to_pack( $update_user_id, $pack_id );
                    wpestate_upgrade_user_membership($update_user_id,$pack_id,2,'');
                }else{
                    wpestate_upgrade_user_membership($update_user_id,$pack_id,2,'');
                }     
            
            }else{
               // echo 'no user';           
            }    
    }
    
    http_response_code(200); 
    exit();
}
*/
//////////////////////end webhook - start processing

if( is_email($_POST['stripeEmail']) ){
    $stripeEmail= wp_kses($_POST['stripeEmail'],$allowed_html)  ;
}else{
    exit('none mail');
}

if( isset($_POST['userID']) && !is_numeric( $_POST['userID'] ) ){
    exit();
}

if( isset($_POST['invoice_id']) && !is_numeric( $_POST['invoice_id'] ) ){
    exit();
}

if( isset($_POST['booking_id']) && !is_numeric( $_POST['booking_id'] ) ){
    exit();
}

if( isset($_POST['depozit']) && !is_numeric( $_POST['depozit'] ) ){
    exit();
}

if( isset($_POST['pack_id']) && !is_numeric( $_POST['pack_id'] ) ){
    exit();
}

if( isset($_POST['pay_ammout']) && !is_numeric( $_POST['pay_ammout'] ) ){
    exit();
}

if( isset($_POST['stripe_recuring']) && !is_numeric( $_POST['stripe_recuring'] ) ){
    exit();
}

if( isset($_POST['submission_pay']) && !is_numeric( $_POST['submission_pay'] ) ){
    exit();
}

if( isset($_POST['propid']) && !is_numeric( $_POST['propid'] ) ){
    exit();
}

if( isset($_POST['featured_pay']) && !is_numeric( $_POST['featured_pay'] ) ){
    exit();
}

if( isset($_POST['is_upgrade']) && !is_numeric( $_POST['is_upgrade'] ) ){
    exit();
}



$current_user = wp_get_current_user();
$userID         =   $current_user->ID;
$user_email     =   $current_user->user_email;
$username       =   $current_user->user_login;
$submission_curency_status = esc_html( get_option('wp_estate_submission_curency','') );

////////////////////////////////////////////////////////////////////////////////
///////////////// payment for booking 
////////////////////////////////////////////////////////////////////////////////
if ( isset($_POST['booking_id']) ){
    try {    
        $token  = wp_kses($_POST['stripeToken'],$allowed_html);
        $customer = Stripe_Customer::create(array(
            'email' => $stripeEmail,
            'card'  => $token
        ));

        $userId     =   intval($_POST['userID']);
        $invoice_id =   intval($_POST['invoice_id']);
        $booking_id =   intval($_POST['booking_id']);
        $depozit    =   intval($_POST['depozit']);
        $charge = Stripe_Charge::create(array(
            'customer' => $customer->id,
            'amount'   => $depozit,
            'currency' => $submission_curency_status
        ));


        // confirm booking
        update_post_meta($booking_id, 'booking_status', 'confirmed');

        $curent_listng_id   =   get_post_meta($booking_id,'booking_id',true);
        $reservation_array  =   wpestate_get_booking_dates($curent_listng_id);


        update_post_meta($curent_listng_id, 'booking_dates', $reservation_array); 

        // set invoice to paid
        update_post_meta($invoice_id, 'invoice_status', 'confirmed');
        update_post_meta($invoice_id, 'depozit_paid', ($depozit/100) );

        /////////////////////////////////////////////////////////////////////////////
        // send confirmation emails
        /////////////////////////////////////////////////////////////////////////////   
        wpestate_send_booking_email("bookingconfirmeduser",$user_email);

        $receiver_id    =   wpsestate_get_author($invoice_id);
        $receiver_email =   get_the_author_meta('user_email', $receiver_id); 
        $receiver_name  =   get_the_author_meta('user_login', $receiver_id); 
        wpestate_send_booking_email("bookingconfirmed",$receiver_email);


        // add messages to inbox
        $subject=esc_html__( 'Booking Confirmation','wpestate');
        $description=esc_html__( 'A booking was confirmed','wpestate');
        wpestate_add_to_inbox($userID,$userID,$receiver_id,$subject,$description,1);

        ////redirect catre bookng list
        $redirect=wpestate_my_reservations_link();
         wp_redirect($redirect);
        exit();
    }
    catch (Exception $e) {
        $error =    '<div class="alert alert-danger">
                        <strong>Error!</strong> '.$e->getMessage().'
                    </div>';
        print $error;
    }

    
}else if ( isset ($_POST['submission_pay'])  && $_POST['submission_pay']==1  ){
    ////////////////////////////////////////////////////////////////////////////////
    ////////////////// payment for submission
    ////////////////////////////////////////////////////////////////////////////////    
    try {
        $token  = wp_kses($_POST['stripeToken'],$allowed_html);
        $customer = Stripe_Customer::create(array(
            'email' => $stripeEmail,
            'card'  => $token
        ));

        $userId         =   intval($_POST['userID']);
        $listing_id     =   intval($_POST['propid']);
        $pay_ammout     =   intval($_POST['pay_ammout']);
        $is_featured    =   0;
        $is_upgrade     =   0;
        
        if ( isset($_POST['featured_pay']) && $_POST['featured_pay']==1 ){
            $is_featured    =   intval($_POST['featured_pay']);
        }

        if ( isset($_POST['is_upgrade']) && $_POST['is_upgrade']==1 ){
            $is_upgrade    =   intval($_POST['is_upgrade']);
        }
        
        $charge = Stripe_Charge::create(array(
            'customer' => $customer->id,
            'amount'   => $pay_ammout,
            'currency' => $submission_curency_status
        ));
        
      
        $time = time(); 
        $date = date('Y-m-d H:i:s',$time);
    
        if($is_upgrade==1){
            update_post_meta($listing_id, 'prop_featured', 1);
            $invoice_id = wpestate_insert_invoice('Upgrade to Featured','One Time',$listing_id,$date,$current_user->ID,0,1,'' );
            update_post_meta($invoice_id, 'invoice_status', 'confirmed');
            wpestate_email_to_admin(1);
        }else{
            update_post_meta($listing_id, 'pay_status', 'paid');
            $admin_submission_status = esc_html ( get_option('wp_estate_admin_submission','') );
            $paid_submission_status  = esc_html ( get_option('wp_estate_paid_submission','') );
 
            if($admin_submission_status=='no'  && $paid_submission_status=='per listing' ){
                $post = array(
                    'ID'            => $listing_id,
                    'post_status'   => 'publish'
                    );
                $post_id =  wp_update_post($post ); 
            }
            // end make post publish

        if($is_featured==1){
            update_post_meta($listing_id, 'prop_featured', 1);
            $invoice_id = wpestate_insert_invoice('Publish Listing with Featured','One Time',$listing_id,$date,$current_user->ID,1,0,'' );
            update_post_meta($invoice_id, 'invoice_status', 'confirmed');
        }else{
            $invoice_id = wpestate_insert_invoice('Listing','One Time',$listing_id,$date,$current_user->ID,0,0,'' );
            update_post_meta($invoice_id, 'invoice_status', 'confirmed');
        }
        wpestate_email_to_admin(0);
        }
        
        $redirect = wpestate_get_dashboard_link();
        wp_redirect($redirect);exit();
        
    }
    catch (Exception $e) {
    $error =    '<div class="alert alert-danger">
                    <strong>Error!</strong> '.$e->getMessage().'
                </div>';
    print $error;
    }
    
}else if ( isset ($_POST['stripe_recuring'] ) && $_POST['stripe_recuring'] ==1 ) { 
////////////////////////////////////////////////////////////////////////////////
////////////////// payment for pack recuring
////////////////////////////////////////////////////////////////////////////////    
    try {
        $dash_profile_link = wpestate_get_dashboard_profile_link();
        $token          =   wp_kses($_POST['stripeToken'],$allowed_html);
        $pack_id        =   intval($_POST['pack_id']);
        $stripe_plan    =   esc_html(get_post_meta($pack_id, 'pack_stripe_id', true));

        $customer = Stripe_Customer::create(array(
            "card" =>  $token,
            "plan" =>  $stripe_plan,
            "email" => $stripeEmail
          ));
       
        
        $stripe_customer_id=$customer->id;
        $subscription_id = $customer->subscriptions['data'][0]['id'];
 
        if( wpestate_check_downgrade_situation($current_user->ID,$pack_id) ){
            wpestate_downgrade_to_pack( $current_user->ID, $pack_id );
            wpestate_upgrade_user_membership($current_user->ID,$pack_id,2,'');
        }else{
            wpestate_upgrade_user_membership($current_user->ID,$pack_id,2,'');
        }      
        update_user_meta( $current_user->ID, 'stripe', $stripe_customer_id );
        update_user_meta( $current_user->ID, 'stripe_subscription_id', $subscription_id );
        wp_redirect( $dash_profile_link ); exit();
 
    }
    catch (Exception $e) {
        $error = '  <div class="alert alert-danger">
                        <strong>Error!</strong> '.$e->getMessage().'
                    </div>';
        print $error;
    }
 
    
}else{

////////////////////////////////////////////////////////////////////////////////
////////////////// payment for pack
////////////////////////////////////////////////////////////////////////////////  
    try {
        $dash_profile_link = wpestate_get_dashboard_profile_link();

        $token  =  wp_kses($_POST['stripeToken'],$allowed_html);
        $customer = Stripe_Customer::create(array(
            'email' => $stripeEmail,
            'card'  => $token
        ));

        $userId     =   intval($_POST['userID']);
        $pay_ammout =   intval($_POST['pay_ammout']);
        $pack_id    =   intval($_POST['pack_id']);
        $charge     =   Stripe_Charge::create(array(
                        'customer' => $customer->id,
                        'amount'   => $pay_ammout,
                        'currency' => $submission_curency_status
        ));

        if( wpestate_check_downgrade_situation($current_user->ID,$pack_id) ){
            wpestate_downgrade_to_pack( $current_user->ID, $pack_id );
            wpestate_upgrade_user_membership($current_user->ID,$pack_id,1,'');
        }else{
            wpestate_upgrade_user_membership($current_user->ID,$pack_id,1,'');
        }      
        wp_redirect( $dash_profile_link );   exit();   
    }
    catch (Exception $e) {
        $error = '<div class="alert alert-danger">
                    <strong>Error!</strong> '.$e->getMessage().'
                </div>';
        print $error;
    }
    
}

?>