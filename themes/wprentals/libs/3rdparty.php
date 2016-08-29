<?php
////////////////////////////////////////////////////////////////////////////////
/// Facebook  Login
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_facebook_login') ):

function estate_facebook_login($get_vars){
    //https://developers.facebook.com/docs/php/gettingstarted
    session_start();
    $facebook_api               =   esc_html ( get_option('wp_estate_facebook_api','') );
    $facebook_secret            =   esc_html ( get_option('wp_estate_facebook_secret','') );
 
    $fb = new Facebook\Facebook([
            'app_id'  => $facebook_api,
            'app_secret' => $facebook_secret,
            'default_graph_version' => 'v2.5',
        ]);
    $helper = $fb->getRedirectLoginHelper();
        

    $secret      =   $facebook_secret;
    try {
        $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
         // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
    exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    
    
    // Logged in
    // var_dump($accessToken->getValue());

    // The OAuth 2.0 client handler helps us manage access tokens
    $oAuth2Client = $fb->getOAuth2Client();

    // Get the access token metadata from /debug_token
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
    //echo '<h3>Metadata</h3>';
    //var_dump($tokenMetadata);

    // Validation (these will throw FacebookSDKException's when they fail)
    $tokenMetadata->validateAppId($facebook_api); 
    
    // If you know the user ID this access token belongs to, you can validate it here
    //$tokenMetadata->validateUserId('123');
    $tokenMetadata->validateExpiration();

    if (! $accessToken->isLongLived()) {
        // Exchanges a short-lived access token for a long-lived one
        try {
          $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
          echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
          exit;
        }

    // echo '<h3>Long-lived</h3>';
    //  var_dump($accessToken->getValue());
    }

    $_SESSION['fb_access_token'] = (string) $accessToken;
    
    try {
        // Returns a `Facebook\FacebookResponse` object
        $response = $fb->get('/me?fields=id,email,name,first_name,last_name', $accessToken);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    $user = $response->getGraphUser();
    //print_r($user);
    
    if(isset($user['name'])){
        $full_name=$user['name'];
    }
    if(isset($user['email'])){
        $email=$user['email'];
    }
    $identity_code=$secret.$user['id'];  
    wpestate_register_user_via_google($email,$full_name,$identity_code,$user['first_name'],$user['last_name']); 
    $info                   = array();
    $info['user_login']     = $full_name;
    $info['user_password']  = $identity_code;
    $info['remember']       = true;

    $user_signon            = wp_signon( $info, true );
        
        
    if ( is_wp_error($user_signon) ){ 
        wp_redirect( esc_url(home_url() ) ); exit(); 
    }else{
        wpestate_update_old_users($user_signon->ID);
        wp_redirect(wpestate_get_dashboard_profile_link());exit();
    }
               
    
    
    
  
}

/* deprecated
function estate_facebook_login($get_vars){
    require get_template_directory().'/libs/resources/facebook.php';
       
    $facebook_api               =   esc_html ( get_option('wp_estate_facebook_api','') );
    $facebook_secret            =   esc_html ( get_option('wp_estate_facebook_secret','') );
    $facebook = new Facebook(array(
        'appId'  => $facebook_api,
        'secret' => $facebook_secret,
        'cookie' => true
     ));
    $secret      =   $facebook_secret;
    $params = array(
        'redirect_uri' => wpestate_get_dashboard_profile_link(),
        'scope' => 'email',
        );
    
   
    $login_url   =   $facebook->getLoginUrl($params); 
   $user_id     =   $facebook->getUser();

  if($user_id==0){
      $login_url = $facebook->getLoginUrl($params); 
      wp_redirect($login_url);exit();
  }else{
        //   $user_profile = $facebook->api('/me','GET');
      
        $user_profile = $facebook->api('/me?fields=id,email,name,first_name,last_name','GET');
        
        
        if(isset($user_profile['name'])){
            $full_name=$user_profile['name'];
        }
        
        if(isset($user_profile['email'])){
            $email=$user_profile['email'];
        }else{
            $email=$full_name.'@facebook.com';
        }
        
        
        $identity_code=$secret.$user_profile['id'];  
        wpestate_register_user_via_google($email,$full_name,$identity_code,$user_profile['first_name'],$user_profile['last_name']); 
        $info                   = array();
        $info['user_login']     = $full_name;
        $info['user_password']  = $identity_code;
        $info['remember']       = true;
       
        $user_signon            = wp_signon( $info, true );
        
        
        if ( is_wp_error($user_signon) ){ 
            wp_redirect( esc_url(home_url() ) ); exit(); 
        }else{
            wpestate_update_old_users($user_signon->ID);
            wp_redirect(wpestate_get_dashboard_profile_link());exit();
        }
        
                
  }
}
*/
endif; // end   estate_facebook_login 






////////////////////////////////////////////////////////////////////////////////
/// estate_google_oauth_login  Login
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_google_oauth_login') ):

    function estate_google_oauth_login($get_vars){
       // set_include_path( get_include_path() . PATH_SEPARATOR . get_template_directory().'/libs/resources');
        $allowed_html   =   array();
        require_once  get_template_directory()."/libs/resources/base.php";
        require_once  get_template_directory()."/libs/resources/src2/Google/autoload.php";
        
        $google_client_id       =   esc_html ( get_option('wp_estate_google_oauth_api','') );
        $google_client_secret   =   esc_html ( get_option('wp_estate_google_oauth_client_secret','') );
        $google_redirect_url    =   wpestate_get_dashboard_profile_link();
        $google_developer_key   =   esc_html ( get_option('wp_estate_google_api_key','') );

        //$google_client_id = '789776065323-47hjb2931cl5ag9881gcpfcn0qq9e72n.apps.googleusercontent.com';
        //$google_client_secret = 'q9kSQCz2Pif1e1wLDeaYRoUl';
      
        
        
        $gClient = new Google_Client();
        $gClient->setApplicationName('Login to WpRentals');
        $gClient->setClientId($google_client_id);
        $gClient->setClientSecret($google_client_secret);
        $gClient->setRedirectUri($google_redirect_url);
        $gClient->setDeveloperKey($google_developer_key);
        $google_oauthV2 = new Google_Service_Oauth2($gClient);

        if (isset($_GET['code'])) { 
            $code= sanitize_text_field ( wp_kses($_GET['code'],$allowed_html) );
            $gClient->authenticate($code);
        }



        if ($gClient->getAccessToken()) 
        {    
            
            $allowed_html      =   array();
            $dashboard_url     =   wpestate_get_dashboard_profile_link();
            $user              =   $google_oauthV2->userinfo->get();
            $full_name         =   wp_kses($user['name'], $allowed_html);
            $email             =   wp_kses($user['email'], $allowed_html);
        
            $user_id           =   $user['id'];
            $full_name         =   wp_kses($user['name'], $allowed_html);
            $email             =   wp_kses($user['email'], $allowed_html);
            $full_name         =   str_replace(' ','.',$full_name);  
            
            $first_name=$last_name='';
            if(isset($user['familyName'])){
                $last_name=$user['familyName'];
            }  
            if(isset($user['givenName'])){
                $first_name=$user['givenName'];
            }
            
            wpestate_register_user_via_google($email,$full_name,$user_id,$first_name,$last_name); 
            $wordpress_user_id=username_exists($full_name);
            wp_set_password( $code, $wordpress_user_id ) ;

            $info                   = array();
            $info['user_login']     = $full_name;
            $info['user_password']  = $code;
            $info['remember']       = true;
            $user_signon            = wp_signon( $info, true );



            if ( is_wp_error($user_signon) ){ 
                wp_redirect( esc_url(home_url()) );  exit();
            }else{
                wpestate_update_old_users($user_signon->ID);
                wp_redirect($dashboard_url);exit();
            }
          
        }   
    }

endif; // end   estate_google_oauth_login 

////////////////////////////////////////////////////////////////////////////////
/// Open ID Login
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('estate_open_id_login') ):

function estate_open_id_login($get_vars){
    require get_template_directory().'/libs/resources/openid.php';  
    $openid         =   new LightOpenID( wpestate_get_domain_openid() );
    $allowed_html   =   array();
    if( $openid->validate() ){
        
        $dashboard_url          =   wpestate_get_dashboard_profile_link();
        $openid_identity        =   wp_kses( $get_vars['openid_identity'],$allowed_html);
        $openid_identity_check  =   wp_kses( $get_vars['openid_identity'],$allowed_html);
        
        
        if(strrpos  ($openid_identity_check,'google') ){
            $email                  =   wp_kses ( $get_vars['openid_ext1_value_contact_email'],$allowed_html );
            $last_name              =   wp_kses ( $get_vars['openid_ext1_value_namePerson_last'],$allowed_html );
            $first_name             =   wp_kses ( $get_vars['openid_ext1_value_namePerson_first'],$allowed_html );
            $full_name              =   $first_name.$last_name;
            $openid_identity_pos    =   strrpos  ($openid_identity,'id?id=');
            $openid_identity        =   str_split($openid_identity, $openid_identity_pos+6);
            $openid_identity_code   =   $openid_identity[1]; 
        }
        
        if(strrpos  ($openid_identity_check,'yahoo')){
          
            $email                  =   wp_kses ( $get_vars['openid_ax_value_email'] ,$allowed_html);
            $full_name              =   wp_kses ( str_replace(' ','.',$get_vars['openid_ax_value_fullname']) ,$allowed_html);            
            $openid_identity_pos    =   strrpos  ($openid_identity,'/a/.');
            $openid_identity        =   str_split($openid_identity, $openid_identity_pos+4);
            $openid_identity_code   =   $openid_identity[1]; 
        }
       
        wpestate_register_user_via_google($email,$full_name,$openid_identity_code); 
        $info                   = array();
        $info['user_login']     = $full_name;
        $info['user_password']  = $openid_identity_code;
        $info['remember']       = true;
        $user_signon            = wp_signon( $info, true );
        
 
        
        if ( is_wp_error($user_signon) ){ 
            wp_redirect( esc_url( home_url() ) );  exit();
        }else{
            wpestate_update_old_users($user_signon->ID);
            wp_redirect($dashboard_url);exit();
        }
           
        } 
    }// end  estate_open_id_login
endif; // end   estate_open_id_login  







////////////////////////////////////////////////////////////////////////////////
/// Twiter API v1.1 functions
////////////////////////////////////////////////////////////////////////////////
/*
 function getConnectionWithAccessToken($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret) {
    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
    return $connection;
} */

                
//convert links to clickable format
if( !function_exists('wpestate_convert_links') ):
    function wpestate_convert_links($status,$targetBlank=true,$linkMaxLen=250){
        // the target
        $target=$targetBlank ? " target=\"_blank\" " : "";

        // convert link to url
        $status = preg_replace("/((http:\/\/|https:\/\/)[^ )]+)/e", "'<a href=\"$1\" title=\"$1\" $target >'. ((strlen('$1')>=$linkMaxLen ? substr('$1',0,$linkMaxLen).'...':'$1')).'</a>'", $status);

   
        
        // convert @ to follow
        $status = preg_replace("/(@([_a-z0-9\-]+))/i","<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>",$status);

        // convert # to search
        $status = preg_replace("/(#([_a-z0-9\-]+))/i","<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>",$status);

        // return the status
        return $status;
    }
endif;
                

//convert dates to readable format	
if( !function_exists('wpestate_convert_links') ):
    function wpestate_convert_links($a) {
        //get current timestampt
    
        $b = strtotime("now"); 
        //get timestamp when tweet created
        $c = strtotime($a);
        //get difference
        $d = $b - $c;
        //calculate different time values
        $minute = 60;
        $hour = $minute * 60;
        $day = $hour * 24;
        $week = $day * 7;

        if(is_numeric($d) && $d > 0) {
                //if less then 3 seconds
                if($d < 3) return "right now";
                //if less then minute
                if($d < $minute) return floor($d) . " seconds ago";
                //if less then 2 minutes
                if($d < $minute * 2) return "about 1 minute ago";
                //if less then hour
                if($d < $hour) return floor($d / $minute) . " minutes ago";
                //if less then 2 hours
                if($d < $hour * 2) return "about 1 hour ago";
                //if less then day
                if($d < $day) return floor($d / $hour) . " hours ago";
                //if more then day, but less then 2 days
                if($d > $day && $d < $day * 2) return "yesterday";
                //if less then year
                if($d < $day * 365) return floor($d / $day) . " days ago";
                //else return more than a year
                return "over a year ago";
        }
    }
endif;
 

///////////////////////////////////////////////////////////////////////////////////////////
// register google user
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_register_user_via_google') ):
    
    function wpestate_register_user_via_google($email,$full_name,$openid_identity_code,$firsname='',$lastname=''){
        if ( email_exists( $email ) ){ 
            if(username_exists($full_name) ){
                return;
            }else{
                $user_id  = wp_create_user( $full_name, $openid_identity_code,' ' );  
                wpestate_update_profile($user_id); 
                wpestate_register_as_user($full_name,$user_id,$firsname,$lastname);
            }
        }else{
            if(username_exists($full_name) ){
                return;
            }else{
                $user_id  = wp_create_user( $full_name, $openid_identity_code, $email ); 
                wpestate_update_profile($user_id);
                wpestate_register_as_user($full_name,$user_id,$firsname,$lastname);
            }
        }
    }
endif; // end   wpestate_register_user_via_google 




///////////////////////////////////////////////////////////////////////////////////////////
// get domain open id
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_domain_openid') ):

    function wpestate_get_domain_openid(){
        $realm_url = esc_url(get_home_url());
        $realm_url= str_replace('http://','',$realm_url);
        $realm_url= str_replace('https://','',$realm_url);  
        return $realm_url;
    }

endif; // end   wpestate_get_domain_openid 





///////////////////////////////////////////////////////////////////////////////////////////
// paypal functions - get acces token
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_access_token') ):
    function wpestate_get_access_token($url, $postdata) {
            $clientId                       =   esc_html( get_option('wp_estate_paypal_client_id','') );
            $clientSecret                   =   esc_html( get_option('wp_estate_paypal_client_secret','') );
           /*'headers' => [
                    'Authorization' => "Basic $auth"
                ],
            $auth = base64_encode( $clientId . ':' . $clientSecret );
            print $url;
            $response_con = wp_remote_post( $url, array(
                
                'headers' => array(
                    'Authorization' => "Basic ".$auth
                ),
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,   
                'body' => $postdata,
                'cookies' => array()
                )
            );
           
            $response = wp_remote_retrieve_body( $response_con );
            
            if ( is_wp_error( $response ) ) {
               exit('conection error');
            } else {
            }

print 'response'.$response;
            // Convert the result from JSON format to a PHP array 
            $jsonResponse = json_decode( $response );
            print_r($jsonResponse);/*/
            
        $curl = curl_init($url); 
	curl_setopt($curl, CURLOPT_POST, true); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
	curl_setopt($curl, CURLOPT_HEADER, false); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); 
#	curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
	$response = curl_exec( $curl );
	if (empty($response)) {
	    // some kind of an error happened
	    die(curl_error($curl));
	    curl_close($curl); // close cURL handler
	} else {
	    $info = curl_getinfo($curl);
		//echo "Time took: " . $info['total_time']*1000 . "ms\n";
	    curl_close($curl); // close cURL handler
		if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
			echo "Received error: " . $info['http_code']. "\n";
			echo "Raw response:".$response."\n";
			die();
	    }
	}

	// Convert the result from JSON format to a PHP array 
	$jsonResponse = json_decode( $response );
	//print_r($jsonResponse);
        return $jsonResponse->access_token;
    }

endif; // end   wpestate_get_access_token 


///////////////////////////////////////////////////////////////////////////////////////////
// paypal functions - make post call
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_make_post_call') ):


    function wpestate_make_post_call($url, $postdata,$token) {
       /*
        * 
        $headers = array(
        'Authorization'  => 'Bearer ' .$token,
        'Accept'       => 'application/json',
        'Content-Type'   => 'application/json',
       );

        
        
        $response_con = wp_remote_post( $url, array(
                'headers'       =>  $headers,
                'method'        =>  'POST',
                'timeout'       =>  45,
                'redirection'   => 5,
                'httpversion'   => '1.0',
                'blocking'      => true,   
                'body'          => $postdata,
                'cookies'       => array()
                )
            );
        $response = wp_remote_retrieve_body( $response_con );
            
        if ( is_wp_error( $response ) ) {
               exit('conection error');
        } 

        // Convert the result from JSON format to a PHP array 
        $jsonResponse = json_decode($response, TRUE);
        return $jsonResponse;
        */
    //global $token;
	$curl = curl_init($url); 
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer '.$token,
				'Accept: application/json',
				'Content-Type: application/json'
				));
	
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); 
	#curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
	$response = curl_exec( $curl );
	if (empty($response)) {
	    // some kind of an error happened
	    die(curl_error($curl));
	    curl_close($curl); // close cURL handler
	} else {
	    $info = curl_getinfo($curl);
		//echo "Time took: " . $info['total_time']*1000 . "ms\n";
	    curl_close($curl); // close cURL handler
		if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
			echo "Received error: " . $info['http_code']. "\n";
			echo "Raw response:".$response."\n";
			die();
	    }
	}

	// Convert the result from JSON format to a PHP array 
	$jsonResponse = json_decode($response, TRUE);
	return $jsonResponse;
    }

 

endif; // end   wpestate_make_post_call 
?>
