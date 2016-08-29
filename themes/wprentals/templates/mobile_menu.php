<div class="mobilewrapper">
    <div class="snap-drawers">
        <!-- Left Sidebar-->
        <div class="snap-drawer snap-drawer-left">
    
            <div class="mobilemenu-close"><i class="fa fa-times"></i></div>
            
            <?php  wp_nav_menu( array( 
                    'theme_location' => 'mobile',
                    'container' => false,
                    'menu_class'      => 'mobilex-menu',
                ) );?>
           
        </div>  
  </div>
</div>  


<div class="mobilewrapper-user">
    <div class="snap-drawers">
   
    <!-- Right Sidebar-->
        <div class="snap-drawer snap-drawer-right">
    
        <div class="mobilemenu-close-user"><i class="fa fa-times"></i></div>
        <?php
        $current_user               =   wp_get_current_user();
     
        if ( 0 != $current_user->ID  && is_user_logged_in() ) {
            $username               =   $current_user->user_login ;
            $add_link               =   wpestate_get_dasboard_add_listing();
            $dash_profile           =   wpestate_get_dashboard_profile_link();
            $dash_favorite          =   wpestate_get_dashboard_favorites();
            $dash_link              =   wpestate_get_dashboard_link();
            $dash_searches          =   wpestate_get_searches_link(); 
            $dash_reservation       =   wpestate_get_my_reservation_link();
            $dash_bookings          =   wpestate_get_my_bookings_link();
            $dash_inbox             =   get_inbox_wpestate_booking();
            $dash_invoices          =   get_invoices_wpestate();
            $logout_url             =   wp_logout_url();      
            $home_url               =   esc_html( home_url() );

            ?> 
            <ul class="user_mobile_menu_list">
                <li><a href="<?php print $dash_profile;?>" ><i class="fa fa-cog"></i><?php esc_html_e('My Profile','wpestate');?></a></li>   
                <?php if( wpestate_check_user_level() ) { ?>
                    <li><a href="<?php print $dash_link;?>" ><i class="fa fa-map-marker"></i><?php esc_html_e('My Properties','wpestate');?></a></li>
                    <li><a href="<?php print $add_link;?>" ><i class="fa fa-plus"></i><?php esc_html_e('Add New Property','wpestate');?></a></li> 
                <?php } ?>
                
                      
                <li><a href="<?php print $dash_favorite;?>" class="active_fav"><i class="fa fa-heart"></i><?php esc_html_e('Favorites','wpestate');?></a></li>
                <li><a href="<?php print $dash_reservation;?>" class="active_fav"><i class="fa fa-folder-open"></i><?php esc_html_e('Reservations','wpestate');?></a></li>
                
                <?php if( wpestate_check_user_level() ) { ?>
                    <li><a href="<?php print $dash_bookings;?>" class="active_fav"><i class="fa fa-folder-open-o"></i><?php esc_html_e('Bookings','wpestate');?></a></li>
                <?php } ?>
                
                <li><a href="<?php print $dash_inbox;?>" class="active_fav"><i class="fa fa-inbox"></i><?php esc_html_e('Inbox','wpestate');?></a></li>
                
                <?php if( wpestate_check_user_level() ) { ?>
                    <li><a href="<?php print $dash_invoices;?>" class="active_fav"><i class="fa fa-file-o"></i><?php esc_html_e('Invoices','wpestate');?></a></li>
                <?php } ?>

                <li><a href="<?php echo wp_logout_url();?>" title="Logout" class="menulogout"><i class="fa fa-power-off"></i><?php esc_html_e('Log Out','wpestate');?></a></li>
            
            </ul>        
    
    <?php }else{
        $facebook_status    =   esc_html( get_option('wp_estate_facebook_login','') );
        $google_status      =   esc_html( get_option('wp_estate_google_login','') );
        $yahoo_status       =   esc_html( get_option('wp_estate_yahoo_login','') );
        $mess='';
        
        print '
        <div class="login_sidebar_mobile">
            <h3 class="widget-title-sidebar"  id="login-div-title-mobile">'.esc_html__( 'Login','wpestate').'</h3>
            <div class="login_form" id="login-div-mobile">
                <div class="loginalert" id="login_message_area_wd_mobile" >'.$mess.'</div>

                <input type="text" class="form-control" name="log" id="login_user_wd_mobile" placeholder="'.esc_html__( 'Username','wpestate').'"/>
                <input type="password" class="form-control" name="pwd" id="login_pwd_wd_mobile" placeholder="'.esc_html__( 'Password','wpestate').'"/>                       
                <input type="hidden" name="loginpop" id="loginpop_mobile" value="0">
            

                <input type="hidden" id="security-login-mobile" name="security-login-mobile" value="'. estate_create_onetime_nonce( 'login_ajax_nonce_mobile' ).'">
       
                <button class="wpb_button  wpb_btn-info  wpb_regularsize   wpestate_vc_button  vc_button" id="wp-login-but-wd-mobile">'.esc_html__( 'Login','wpestate').'</button>

                <div class="login-links">
                    <a href="#" id="widget_register_mobile">'.esc_html__( 'Need an account? Register here!','wpestate').'</a>
                    <a href="#" id="forgot_pass_widget_mobile">'.esc_html__( 'Forgot Password?','wpestate').'</a>
                </div> ';

                if($facebook_status=='yes'){
                    print '<div id="facebooklogin_mb" data-social="facebook"><i class="fa fa-facebook"></i>'.esc_html__( 'Login with Facebook','wpestate').'</div>';
                }
                if($google_status=='yes'){
                    print '<div id="googlelogin_mb" data-social="google"><i class="fa fa-google"></i>'.esc_html__( 'Login with Google','wpestate').'</div>';
                }
                if($yahoo_status=='yes'){
                    print '<div id="yahoologin_mb" data-social="yahoo"><i class="fa fa-yahoo"></i>'.esc_html__( 'Login with Yahoo','wpestate').'</div>';
                }


            print '

            </div>

              <h3 class="widget-title-sidebar"  id="register-div-title-mobile">'.esc_html__( 'Register','wpestate').'</h3>
                <div class="login_form" id="register-div-mobile">
                    <div class="loginalert" id="register_message_area_wd_mobile" ></div>
                    <input type="text" name="user_login_register" id="user_login_register_wd_mobile" class="form-control" placeholder="'.esc_html__( 'Username','wpestate').'"/>';
                    
            $enable_user_pass_status= esc_html ( get_option('wp_estate_enable_user_pass','') );
            if($enable_user_pass_status == 'yes'){
                print   '<input type="text" name="user_email_register" id="user_email_register_wd_mobile" class="form-control" placeholder="'.esc_html__( 'Email','wpestate').'"  />';
                print   '<input type="password" name="user_password" id="user_password_wd_mobile" class="form-control" placeholder="'.esc_html__( 'Password','wpestate').'" size="20" />';
                print   '<input type="password" name="user_password_retype" id="user_password_retype_wd_mobile" class="form-control" placeholder="'.esc_html__( 'Retype Password','wpestate').'" size="20" />';
            }else{
                print'        <input type="text" name="user_email_register" id="user_email_register_wd_mobile" class="form-control" placeholder="'.esc_html__( 'Email','wpestate').'"  />';
            }
            
                    $separate_users_status= esc_html ( get_option('wp_estate_separate_users','') );   
                    if($separate_users_status=='yes'){
                        print'
                        <div class="acc_radio">
                        <input type="radio" name="acc_type" id="acctype0" value="1" checked required> 
                        <div class="radiolabel" for="acctype0">'.esc_html__('I only want to book','wpestate').'</div><br>
                        <input type="radio" name="acc_type" id="acctype1" value="0" required>
                        <div class="radiolabel" for="acctype1">'.esc_html__('I want to rent my property','wpestate').'</div></div> ';
                    }
        
                    print'<input type="checkbox" name="terms" id="user_terms_register_wd_mobile"><label id="user_terms_register_wd_label_mobile" for="user_terms_register_wd_mobile">'.esc_html__( 'I agree with ','wpestate').'<a href="'.wpestate_get_terms_links().'" target="_blank" id="user_terms_register_topbar_link">'.esc_html__( 'terms & conditions','wpestate').'</a> </label>';
                    if($separate_users_status!=='yes'){
                        print '<p id="reg_passmail_mobile">'.esc_html__( 'A password will be e-mailed to you','wpestate').'</p>';
                    }
                    
                    print'        
                    <input type="hidden" id="security-register-mobile" name="security-register-mobile" value="'. estate_create_onetime_nonce( 'register_ajax_nonce_mobile' ).'">';
                    
                    if( esc_html ( get_option('wp_estate_use_captcha','') )=='yes'){
                        print'<div id="mobile_register_menu" style="float:left;transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;margin-top:10px;"></div>';
                    }     
                    
                    print'<button class="wpb_button  wpb_btn-info  wpb_regularsize  wpestate_vc_button  vc_button" id="wp-submit-register_wd_mobile">'.esc_html__( 'Register','wpestate').'</button>';

                    print'
                    <div class="login-links">
                        <a href="#" id="widget_login_sw_mobile">'.esc_html__( 'Back to Login','wpestate').'</a>                       
                    </div>';
                    $social_register_on  =   esc_html( get_option('wp_estate_social_register_on','') );
                    if($social_register_on=='yes'){
                        print'
                        <div class="login-links" >';


                            $facebook_status    =   esc_html( get_option('wp_estate_facebook_login','') );
                            $google_status      =   esc_html( get_option('wp_estate_google_login','') );
                            $yahoo_status       =   esc_html( get_option('wp_estate_yahoo_login','') );


                            if($facebook_status=='yes'){
                                print '<div id="facebooklogin_mb" data-social="facebook"><i class="fa fa-facebook"></i> '.esc_html__( 'Login with Facebook','wpestate').'</div>';
                            }
                            if($google_status=='yes'){
                                print '<div id="googlelogin_mb" data-social="google"><i class="fa fa-google"></i>'.esc_html__( 'Login with Google','wpestate').'</div>';
                            }
                            if($yahoo_status=='yes'){
                                print '<div id="yahoologin_mb" data-social="yahoo"><i class="fa fa-yahoo"></i>'.esc_html__( 'Login with Yahoo','wpestate').'</div>';
                            }


                        print'
                        </div> <!-- end login links--> ';
                    }
                 print'
                 </div>
                </div>
                
            <div id="mobile_forgot_wrapper">    
                <h3 class="widget-title-sidebar"  id="forgot-div-title_mobile">'. esc_html__( 'Reset Password','wpestate').'</h3>
                <div class="login_form" id="forgot-pass-div_mobile">
                    <div class="loginalert" id="forgot_pass_area_shortcode_wd_mobile"></div>
                    <div class="loginrow">
                            <input type="text" class="form-control" name="forgot_email" id="forgot_email_mobile" placeholder="'.esc_html__( 'Enter Your Email Address','wpestate').'" size="20" />
                    </div>';
                    wp_nonce_field( 'login_ajax_nonce_forgot_mobile', 'security-login-forgot_wd_mobile',true);
                    print'<input type="hidden" id="postid" value="0">    
                    <button class="wpb_btn-info wpb_regularsize wpestate_vc_button  vc_button" id="wp-forgot-but_mobile" name="forgot" >'.esc_html__( 'Reset Password','wpestate').'</button>
                    <div class="login-links shortlog">
                    <a href="#" id="return_login_shortcode_mobile">'.esc_html__( 'Return to Login','wpestate').'</a>
                    </div>
                </div>
            </div>';
    
    } ?>
        
           
          
           
        </div>  
        
        </div>              
    </div>
      