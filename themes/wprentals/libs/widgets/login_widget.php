<?php
class Wpestate_Login_widget extends WP_Widget {
	
//	function Wpestate_Login_widget(){
        function __construct(){
		$widget_ops = array('classname' => 'loginwd_sidebar', 'description' => 'Put the login & register form on sidebar');
		$control_ops = array('id_base' => 'wpestate_login_widget');
                parent::__construct('wpestate_login_widget', 'Wp Estate: Login & Register', $widget_ops, $control_ops);
	}
	
	function form($instance){
		$defaults = array();
		$instance = wp_parse_args((array) $instance, $defaults);
		$display='';
		print $display;
	}


	function update($new_instance, $old_instance){
		$instance = $old_instance;
		return $instance;
	}



	function widget($args, $instance){
		extract($args);
                $display='';
		global $post;
              
		print $before_widget;
                $facebook_status    =   esc_html( get_option('wp_estate_facebook_login','') );
                $google_status      =   esc_html( get_option('wp_estate_google_login','') );
                $yahoo_status       =   esc_html( get_option('wp_estate_yahoo_login','') );
		$mess='';
		$display.='
                <div class="login_sidebar">
                    <h3 class="widget-title-sidebar"  id="login-div-title">'.esc_html__( 'Login','wpestate').'</h3>
                    <div class="login_form" id="login-div">
                        <div class="loginalert" id="login_message_area_wd" >'.$mess.'</div>
                            
                        <input type="text" class="form-control" name="log" id="login_user_wd" placeholder="'.esc_html__( 'Username','wpestate').'"/>
                        <input type="password" class="form-control" name="pwd" id="login_pwd_wd" placeholder="'.esc_html__( 'Password','wpestate').'"/>                       
                        <input type="hidden" name="loginpop" id="loginpop_wd" value="0">
                      

                        <input type="hidden" id="security-login-wd" name="security-login-wd" value="'. estate_create_onetime_nonce( 'login_ajax_nonce_wd' ).'">
                        <button class="wpb_button  wpb_btn-info  wpb_regularsize   wpestate_vc_button  vc_button" id="wp-login-but-wd" >'.esc_html__( 'Login','wpestate').'</button>
                        
                        <div class="navigation_links">
                            <a href="#" id="widget_register_sw">'.esc_html__( "Don't have an account?",'wpestate').'</a> | 
                            <a href="#" id="forgot_pass_widget">'.esc_html__( 'Forgot Password?','wpestate').'</a>
                        </div>
                        
                        <div class="login-links">
                         ';
                
                        if($facebook_status=='yes'){
                            $display.='<div id="facebooklogin_wd" data-social="facebook"><i class="fa fa-facebook"></i>'.esc_html__( 'Login with Facebook','wpestate').'</div>';
                        }
                        if($google_status=='yes'){
                            $display.='<div id="googlelogin_wd" data-social="google"><i class="fa fa-google"></i>'.esc_html__( 'Login with Google','wpestate').'</div>';
                        }
                        if($yahoo_status=='yes'){
                            $display.='<div id="yahoologin_wd" data-social="yahoo"><i class="fa fa-yahoo"></i>'.esc_html__( 'Login with Yahoo','wpestate').'</div>';
                        }
                
                   
                    $display.='</div>
                          
                    </div>
                
              <h3 class="widget-title-sidebar"  id="register-div-title">'.esc_html__( 'Register','wpestate').'</h3>
                <div class="login_form" id="register-div">
                    <div class="loginalert" id="register_message_area_wd" ></div>
                    <input type="text" name="user_login_register" id="user_login_register_wd" class="form-control" placeholder="'.esc_html__( 'Username','wpestate').'"/>
                    <input type="text" name="user_email_register" id="user_email_register_wd" class="form-control" placeholder="'.esc_html__( 'Email','wpestate').'"  />';
                     
                    $enable_user_pass_status= esc_html ( get_option('wp_estate_enable_user_pass','') );
                    if($enable_user_pass_status == 'yes'){
                        $display.='<input type="password" name="user_password" id="user_password_wd" class="form-control" placeholder="'.esc_html__( 'Password','wpestate').'" size="20" />';
                        $display.='<input type="password" name="user_password_retype" id="user_password_retype_wd" class="form-control" placeholder="'.esc_html__( 'Retype Password','wpestate').'" size="20" />';

                    }
                    $separate_users_status= esc_html ( get_option('wp_estate_separate_users','') );            
    
                    if($separate_users_status=='yes'){
                        $display.='
                        <div class="acc_radio">
                        <input type="radio" name="acc_type" id="acctype0" value="1" checked required> 
                        <div class="radiolabel" for="acctype0">'.esc_html__('I only want to book','wpestate').'</div><br>
                        <input type="radio" name="acc_type" id="acctype1" value="0" required>
                        <div class="radiolabel" for="acctype1">'.esc_html__('I want to rent my property','wpestate').'</div></div> ';
                    }

                    $display.='<input type="checkbox" name="terms" id="user_terms_register_wd"><label id="user_terms_register_wd_label" for="user_terms_register_wd">'.esc_html__( 'I agree with ','wpestate').'<a href="'.wpestate_get_terms_links().'" target="_blank" id="user_terms_register_topbar_link">'.esc_html__( 'terms & conditions','wpestate').'</a> </label>';
                    if($enable_user_pass_status == 'yes'){
                        $display.='<p id="reg_passmail">'.esc_html__( 'A password will be e-mailed to you','wpestate').'</p>';
                    }
                    
                    $display.='<input type="hidden" id="security-register-wd" name="security-register-wd" value="'. estate_create_onetime_nonce( 'register_ajax_nonce_wd' ).'">';
       
                    
                    if( esc_html ( get_option('wp_estate_use_captcha','') )=='yes'){
                        $display.='<div id="widget_register_menu" style="float:left;margin-bottom:10px;transform:scale(1);-webkit-transform:scale(1);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>';
                    }  
     
                    $display.='<button class="wpb_button  wpb_btn-info  wpb_regularsize  wpestate_vc_button  vc_button" id="wp-submit-register_wd">'.esc_html__( 'Register','wpestate').'</button>';
     
                                   
                    $display.='
                    <div class="login-links">
                        <a href="#" id="widget_login_sw">'.esc_html__( 'Back to Login','wpestate').'</a>                       
                    </div>';

                    $social_register_on  =   esc_html( get_option('wp_estate_social_register_on','') );
                    if($social_register_on=='yes'){
                  

                        $facebook_status    =   esc_html( get_option('wp_estate_facebook_login','') );
                        $google_status      =   esc_html( get_option('wp_estate_google_login','') );
                        $yahoo_status       =   esc_html( get_option('wp_estate_yahoo_login','') );

                   
                        if($facebook_status=='yes'){
                            $display.='<div id="facebooklogin_wd_reg" data-social="facebook"><i class="fa fa-facebook"></i>'.esc_html__( 'Login with Facebook','wpestate').'</div>';
                         }
                        if($google_status=='yes'){
                            $display.='<div id="googlelogin_wd_reg" data-social="google"><i class="fa fa-google"></i>'.esc_html__( 'Login with Google','wpestate').'</div>';
                        }
                        if($yahoo_status=='yes'){
                            $display.='<div id="yahoologin_wd_reg" data-social="yahoo"><i class="fa fa-yahoo"></i>'.esc_html__( 'Login with Yahoo','wpestate').'</div>';
                        }
             
                    }
                 $display.='   
                 </div>
                </div>
                <h3 class="widget-title-sidebar"  id="forgot-div-title_shortcode">'. esc_html__( 'Reset Password','wpestate').'</h3>
                <div class="login_form" id="forgot-pass-div_shortcode">
                    <div class="loginalert" id="forgot_pass_area_shortcode_wd"></div>
                    <div class="loginrow">
                            <input type="text" class="form-control" name="forgot_email" id="forgot_email_shortcode" placeholder="'.esc_html__( 'Enter Your Email Address','wpestate').'" size="20" />
                    </div>
                    '. wp_nonce_field( 'login_ajax_nonce_forgot_wd', 'security-login-forgot_wd',true).'  
                    <input type="hidden" id="postid" value="0">    
                    <button class="wpb_btn-info wpb_regularsize wpestate_vc_button  vc_button" id="wp-forgot-but_shortcode" name="forgot" >'.esc_html__( 'Reset Password','wpestate').'</button>
                    <div class="login-links shortlog">
                    <a href="#" id="return_login_shortcode">'.esc_html__( 'Return to Login','wpestate').'</a>
                    </div>
                </div>
            ';
                
                
                $current_user = wp_get_current_user(); 
                $userID                 =   $current_user->ID;
                $user_login             =   $current_user->user_login;
                $user_email             =   get_the_author_meta( 'user_email' , $userID );
                
                $activeprofile= $activedash = $activeadd = $activefav ='';
                
                $add_link               =   wpestate_get_dasboard_add_listing();
                $dash_profile           =   wpestate_get_dashboard_profile_link(); 
                $dash_link              =   wpestate_get_dashboard_link();
                $dash_favorite          =   wpestate_get_dashboard_favorites();
                $dash_searches          =   wpestate_get_searches_link();
                $dash_reservation       =   wpestate_get_my_reservation_link();
                $dash_bookings          =   wpestate_get_my_bookings_link();
                $dash_inbox             =   get_inbox_wpestate_booking();
                $dash_invoices          =   get_invoices_wpestate();
                $home_url               =   esc_html( home_url() );
                $logged_display='
                    <h3 class="widget-title-sidebar" >'.esc_html__( 'Hello ','wpestate'). ' '. $user_login .'  </h3>
                    
                    <ul class="wd_user_menu">';
                    if($home_url!=$dash_profile){
                        $logged_display.='<li> <a href="'.$dash_profile.'"  class="'.$activeprofile.'"><i class="fa fa-cogs"></i>  '.esc_html__( 'My Profile','wpestate').'</a> </li>';
                    }
                    if($home_url!=$dash_link && wpestate_check_user_level()){
                        $logged_display.=' <li> <a href="'.$dash_link.'"     class="'.$activedash.'"><i class="fa fa-map-marker"></i>'.esc_html__( 'My Properties','wpestate').'</a> </li>';
                    }
                    if($home_url!=$add_link && wpestate_check_user_level()){
                        $logged_display.=' <li> <a href="'.$add_link.'"      class="'.$activeadd.'"><i class="fa fa-plus"></i>'. esc_html__( 'Add New Property','wpestate').'</a> </li>';
                    }
                    if($home_url!=$dash_favorite){
                        $logged_display.=' <li> <a href="'.$dash_favorite.'" class="'.$activefav.'"><i class="fa fa-heart"></i>'.esc_html__( 'Favorites','wpestate').'</a> </li>';
                    }
                    
                    if($home_url!=$dash_reservation){
                        $logged_display.=' <li> <a href="'.$dash_reservation.'" class="'.$activefav.'"><i class="fa fa-folder-open"></i>'.esc_html__( 'Reservations','wpestate').'</a> </li>';
                    }
                    
                    if($home_url!=$dash_bookings && wpestate_check_user_level() ){
                        $logged_display.=' <li> <a href="'.$dash_bookings.'" class="'.$activefav.'"><i class="fa fa-folder-open-o"></i>'.esc_html__( 'Bookings','wpestate').'</a> </li>';
                    }
                    
                    if($home_url!=$dash_inbox){
                        $logged_display.=' <li> <a href="'.$dash_inbox.'" class="'.$activefav.'"><i class="fa fa-inbox"></i>'.esc_html__( 'Inbox','wpestate').'</a> </li>';
                    }
                    
                    if($home_url!=$dash_favorite){
                        $logged_display.=' <li> <a href="'.$dash_invoices.'" class="'.$activefav.'"><i class="fa  fa-file-o"></i>'.esc_html__( 'Invoices','wpestate').'</a> </li>';
                    }
                    
                    
                   
                       
                    $logged_display.=' <li> <a href="'.wp_logout_url().'" title="Logout"><i class="fa fa-power-off"></i>'.esc_html__( 'Log Out','wpestate').'</a> </li>   
                    </ul>
                ';
                
               if ( is_user_logged_in() ) {                   
                  print $logged_display;
               }else{
                  print $display; 
               }
               print $after_widget;
	}

}

?>