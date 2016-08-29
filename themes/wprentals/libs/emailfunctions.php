<?php

if (!function_exists('wpestate_select_email_type')):
    function wpestate_select_email_type($user_email,$type,$arguments){
        $value          =   get_option('wp_estate_'.$type,'');
        $value_subject  =   get_option('wp_estate_subject_'.$type,'');  
            
        if (function_exists('icl_translate') ){
            $value          =  icl_translate('wpestate','wp_estate_email_'.$value, $value ) ;
            $value_subject  =  icl_translate('wpestate','wp_estate_email_subject_'.$value_subject, $value_subject ) ;
        }

        wpestate_emails_filter_replace($user_email,$value,$value_subject,$arguments);
    }
endif;


if( !function_exists('wpestate_emails_filter_replace')):
    function  wpestate_emails_filter_replace($user_email,$message,$subject,$arguments){
        $arguments ['website_url'] = get_option('siteurl');
        $arguments ['website_name'] = get_option('blogname');       
        $arguments ['user_email'] = $user_email;     
        $user= get_user_by('email',$user_email);
        $arguments ['username'] = $user-> user_login;
        
        foreach($arguments as $key_arg=>$arg_val){
            $subject = str_replace('%'.$key_arg, $arg_val, $subject);
            $message = str_replace('%'.$key_arg, $arg_val, $message);
        }
        
        wpestate_send_emails($user_email, $subject, $message );    
    }
endif;



if( !function_exists('wpestate_send_emails') ):
    function wpestate_send_emails($user_email, $subject, $message ){
        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        @wp_mail(
            $user_email,
            $subject,
            $message,
            $headers
            );            
    };
endif;




if( !function_exists('wpestate_email_management') ):
    function wpestate_email_management(){
        print '<div class="wpestate-tab-container">';
        print '<h1 class="wpestate-tabh1">'.__('Email Management','wpestate').'</h1>';
       

        $emails=array(
            'new_user'                  =>  __('New user  notification','wpestate'),
            'admin_new_user'            =>  __('New user admin notification','wpestate'),
           // 'purchase_activated'        =>  __('Purchase Activated','wpestate'),
            'password_reset_request'    =>  __('Password Reset Request','wpestate'),
            'password_reseted'          =>  __('Password Reseted','wpestate'),
           // 'purchase_activated'        =>  __('Purchase Activated','wpestate'),
            'approved_listing'          =>  __('Approved Listings','wpestate'),
            'admin_expired_listing'     =>  __('Admin - Expired Listing','wpestate'),
            'paid_submissions'          =>  __('Paid Submission','wpestate'),
            'featured_submission'       =>  __('Featured Submission','wpestate'),
            'account_downgraded'        =>  __('Account Downgraded','wpestate'),
            'membership_cancelled'      =>  __('Membership Cancelled','wpestate'),
            'free_listing_expired'      =>  __('Free Listing Expired','wpestate'),
            'new_listing_submission'    =>  __('New Listing Submission','wpestate'),
           // 'listing_edit'              =>  __('Listing Edit','wpestate'),
            'recurring_payment'         =>  __('Recurring Payment','wpestate'),
            'membership_activated'      =>  __('Membership Activated','wpestate'),
            'agent_update_profile'      =>  __('Update Profile','wpestate'),
            'bookingconfirmeduser'      =>  __('Booking Confirmed - User','wpestate'),
            'bookingconfirmed'          =>  __('Booking Confirmed','wpestate'),
            'bookingconfirmed_nodeposit'=>  __('Booking Confirmed - no deposit','wpestate'),
            'inbox'                     =>  __('Inbox- New Message','wpestate'),
            'newbook'                   =>  __('New Booking Request','wpestate'),
            'mynewbook'                 =>  __('User - New Booking Request','wpestate'),
            'newinvoice'                =>  __('Invoice generation','wpestate'),
            'deletebooking'             =>  __('Booking request rejected','wpestate'),
            'deletebookinguser'         =>  __('Booking Request Cancelled','wpestate'),
            'deletebookingconfirmed'    =>  __('Booking Period Cancelled ','wpestate'),
            'new_wire_transfer'         =>  __('New wire Transfer','wpestate'),
            'admin_new_wire_transfer'   =>  __('Admin - New wire Transfer','wpestate'),
        );
        
        
        print '<div class="email_row">'.__('Global variables: %website_url as website url,%website_name as website name, %user_email as user_email, %username as username','wpestate').'</div>';
        
        
        foreach ($emails as $key=>$label ){

            print '<div class="email_row">';
            $value          = stripslashes( get_option('wp_estate_'.$key,'') );
            $value_subject  = stripslashes( get_option('wp_estate_subject_'.$key,'') );

            print '<label for="subject_'.$key.'">'.__('Subject for','wpestate').' '.$label.'</label>';
            print '<input type="text" name="subject_'.$key.'" value="'.$value_subject.'" />';

            print '<label for="'.$key.'">'.__('Content for','wpestate').' '.$label.'</label>';
            print '<textarea rows="10" name="'.$key.'">'.$value.'</textarea>';
            print '<div class="extra_exp"> '.wpestate_emails_extra_details($key).'</div>';
            print '</div>';

        }

        print'<p class="submit">
               <input type="submit" name="submit" id="submit" class="button-primary"  value="'.__('Save Changes','wpestate').'" />
            </p>';

        print '</div>';   
    }
endif;


if( !function_exists('wpestate_emails_extra_details') ):
    function wpestate_emails_extra_details($type){
        $return_string='';
        switch ($type) {
              
            case "agent_update_profile":
                    $return_string=__('%user_login as  username, %user_email as user email, %user_id as user_id' ,'wpestate');
                    break;
                
            case "new_user":
                    $return_string=__('%user_login_register as new username, %user_pass_register as user password, %user_email_register as new user email' ,'wpestate');
                    break;
                
            case "admin_new_user":
                    $return_string=__('%user_login_register as new username and %user_email_register as new user email' ,'wpestate');
                    break;
                
            case "password_reset_request":
                    $return_string=__('%reset_link as reset link','wpestate');
                    break;
                
            case "password_reseted":
                    $return_string=__('%user_pass as user password','wpestate');
                    break;
                
            case "purchase_activated":
                    $return_string='';
                    break;
                
            case "approved_listing":
                    $return_string=__('* you can use %post_id as listing id, %property_url as property url and %property_title as property name','wpestate');
                    break;

            case "new_wire_transfer":
                    $return_string=  __('* you can use %invoice_no as invoice number, %total_price as $totalprice and %payment_details as  $payment_details','wpestate');
                    break;
            
            case "admin_new_wire_transfer":
                    $return_string=  __('* you can use %invoice_no as invoice number, %total_price as $totalprice and %payment_details as  $payment_details','wpestate');
                    break;    
                
            case "admin_expired_listing":
                    $return_string=  __('* you can use %submission_title as property title number, %submission_url as property submission url','wpestate');
                    break;  
                
            case "matching_submissions":
                    $return_string=  __('* you can use %matching_submissions as matching submissions list','wpestate');
                    break;
                
            case "paid_submissions":  
                    $return_string= '';
                    break;
                
            case  "featured_submission":
                    $return_string=  '';
                    break;

            case "account_downgraded":   
                    $return_string=  '';
                    break;
                
            case "free_listing_expired":
                    $return_string=  __('* you can use %expired_listing_url as expired listing url and %expired_listing_name as expired listing name','wpestate');
                    break;
                
            case "new_listing_submission":
                    $return_string=  __('* you can use %new_listing_title as new listing title and %new_listing_url as new listing url','wpestate');
                    break;
                
        /*   case "listing_edit":
                    $return_string=  __('* you can use %editing_listing_title as editing listing title and %editing_listing_url as editing listing url','wpestate');
                    break;
          */      
            case "recurring_payment":  
                    $return_string=  __('* you can use %recurring_pack_name as recurring packacge name and %merchant as merchant name','wpestate');
                    break;
                
            case "membership_activated":  
                    $return_string=  '';
                    break;    
            case "bookingconfirmeduser":
                    $return_String='';
                    break;
            case "bookingconfirmed":
                    $return_String='';
                    break;
            case "inbox":
                    $return_string=  __('* you can use %content as message content','wpestate');
                    break;
            case "newbook":
                    $return_string=  __('* you can use %booking_property_link as property url','wpestate');
                    break;  
            case "mynewbook":
                    $return_string=  __('* you can use %booking_property_link as property url','wpestate');
                    break; 
            case "newinvoice":
                    $return_String='';
                    break;
            case "deletebooking":
                    $return_String='';
                    break;
            case "deletebookinguser":
                    $return_String='';
                    break;
            case "deletebookingconfirmed":
                    $return_String='';
                    break;
            case "new_wire_transfer":
                    $return_string=  __('* you can use %invoice_no as invoice number, %total_price as $totalprice and %payment_details as  $payment_details','wpestate');
                    break;
            
            case "admin_new_wire_transfer":
                    $return_string=  __('* you can use %invoice_no as invoice number, %total_price as $totalprice and %payment_details as  $payment_details','wpestate');
                    break;  
        }
        return $return_string;
    }
endif;