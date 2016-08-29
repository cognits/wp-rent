<?php
class paypal_recurring{


 var $environment;
 var $API_UserName;
 var $API_Password;
 var $API_Signature;
 var $API_Endpoint;
 var $paymentAmount;
 var $currencyID;
 var $paymentType;
 var $returnURL;
 var $cancelURL;
 var $startDate;
 var $productdesc;
 var $user_id;
 var $pack_id;
    
function setExpressCheckout()
{
    // Add request-specific fields to the request string.
    $nvpStr = "&PAYMENTREQUEST_0_AMT=$this->paymentAmount&ReturnUrl=$this->returnURL&CANCELURL=$this->cancelURL&PAYMENTACTION=$this->paymentType&CURRENCYCODE=$this->currencyID";

    // Execute the API operation; see the PPHttpPost function above.
    $httpParsedResponseAr = $this->fn_setExpressCheckout('SetExpressCheckout', $nvpStr);

    if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            // Redirect to paypal.com.
            $token = urldecode($httpParsedResponseAr["TOKEN"]);
            $payPalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token&useraction=commit";
            if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
                    $payPalURL = "https://www.".$this->environment.".paypal.com/webscr&cmd=_express-checkout&token=".$token."&useraction=commit";
            }
             print $payPalURL;
             exit;
    } else  {
            exit('SetExpressCheckout failed: ' . print_r($httpParsedResponseAr, true));
    }

}


function fn_setExpressCheckout($methodName_, $nvpStr_) {


        $API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
		 $API_Endpoint = "https://api-3t.".$this->environment.".paypal.com/nvp";
	}
	
        //print 'we have api'.$API_Endpoint.'enviroment '.$this->environment;
        $version = urlencode('51.0');
//exit('gata');
	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);


        // Set the API operation, version, and API signature in the request.
        $nvpreq = "L_BILLINGTYPE0=RecurringPayments&L_BILLINGAGREEMENTDESCRIPTION0=$this->productdesc&L_PAYMENTTYPE0=Any&L_CUSTOM0=&METHOD=$methodName_&VERSION=$version&PWD=$this->API_Password&USER=$this->API_UserName&SIGNATURE=$this->API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);
 
	if(!$httpResponse) {
            exit("$methodName_ failed x4: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}




///////////////////////////////////////////////////////////////////////////////////////////////////////
//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function getExpressCheckout($token)
{

    $token = urlencode(htmlspecialchars($token));

    // Add request-specific fields to the request string.
    $nvpStr = "&TOKEN=$token";

    // Execute the API operation; see the PPHttpPost function above.
    $httpParsedResponseAr = $this->fn_getExpressCheckout('GetExpressCheckoutDetails', $nvpStr);

    if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            // Extract the response details.
            $payerID='';
            if( isset(  $httpParsedResponseAr['PAYERID']) ){ 
                $payerID        = $httpParsedResponseAr['PAYERID'];
            }
            if(array_key_exists("SHIPTOSTREET", $httpParsedResponseAr)) {
                 $street1        = $httpParsedResponseAr["SHIPTOSTREET"];
            }
           
            if(array_key_exists("SHIPTOSTREET2", $httpParsedResponseAr)) {
                   $street2 = $httpParsedResponseAr["SHIPTOSTREET2"];
            }
            
            if(array_key_exists("SHIPTOCITY", $httpParsedResponseAr)) {
                $city_name      = $httpParsedResponseAr["SHIPTOCITY"];
            } 
            if(array_key_exists("SHIPTOSTATE", $httpParsedResponseAr)) {
                $state_province = $httpParsedResponseAr["SHIPTOSTATE"];
            }
            if(array_key_exists("SHIPTOZIP", $httpParsedResponseAr)) {
                $postal_code    = $httpParsedResponseAr["SHIPTOZIP"];
            }
            if(array_key_exists("SHIPTOCOUNTRYCODE", $httpParsedResponseAr)) {
                $country_code   = $httpParsedResponseAr["SHIPTOCOUNTRYCODE"];
            }
            if( $this->doExpressCheckout($payerID,$token) ){
                 return true;
            }
           
         //   exit('Get Express Checkout Details Completed Successfully: '.print_r($httpParsedResponseAr, true));
    } else  {
             exit('GetExpressCheckoutDetails failed: ' . print_r($httpParsedResponseAr, true));
    }

}






function fn_getExpressCheckout($methodName_, $nvpStr_)
{
        $API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
		$API_Endpoint = "https://api-3t.".$this->environment.".paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$this->API_Password&USER=$this->API_UserName&SIGNATURE=$this->API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit('$methodName_ failed x2: '.curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;

}




///////////////////////////////////////////////////////////////////////////////////////////////////////
//
///////////////////////////////////////////////////////////////////////////////////////////////////////



function doExpressCheckout($payerID,$token){
    // Add request-specific fields to the request string.
    $nvpStr = "&TOKEN=$token&PAYERID=$payerID&PAYMENTACTION=$this->paymentType&AMT=$this->paymentAmount&CURRENCYCODE=$this->currencyID";

    // Execute the API operation; see the PPHttpPost function above.
    $httpParsedResponseAr = $this->fn_doExpressCheckout('DoExpressCheckoutPayment', $nvpStr);

    if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            if ($this->createRecurringPaymentsProfile($token) ){
                return true;
            }
            //	exit('Express Checkout Payment Completed Successfully: '.print_r($httpParsedResponseAr, true));
    } else  {
             $dash_profile_link = wpestate_get_dashboard_profile_link();
             wp_redirect( $dash_profile_link ); 
             exit('DoExpressCheckoutPayment failed dd1: ' . print_r($httpParsedResponseAr, true));
    }
}

  


function fn_doExpressCheckout($methodName_, $nvpStr_){

        $API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
		$API_Endpoint = "https://api-3t.".$this->environment.".paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Set the curl parameters.
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$this->API_Password&USER=$this->API_UserName&SIGNATURE=$this->API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit('$methodName_ failed x3: '.curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}







///////////////////////////////////////////////////////////////////////////////////////////////////////
//
///////////////////////////////////////////////////////////////////////////////////////////////////////




function createRecurringPaymentsProfile($token){

    $nvpStr="&TOKEN=$token&AMT=$this->paymentAmount&CURRENCYCODE=$this->currencyID&PROFILESTARTDATE=$this->startDate";
    $nvpStr .= "&BILLINGPERIOD=$this->billingPeriod&BILLINGFREQUENCY=$this->billingFreq";

    $httpParsedResponseAr = $this->fn_createRecurringPaymentsProfile('CreateRecurringPaymentsProfile', $nvpStr);

    if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
       
       $profile_id=$httpParsedResponseAr["PROFILEID"];
       wpestate_update_user_recuring_profile( $profile_id,$this->user_id ); 
       return true;
    } else  {
        exit('CreateRecurringPaymentsProfile failed: ' . print_r($httpParsedResponseAr, true));
    }

}  

function fn_createRecurringPaymentsProfile($methodName_, $nvpStr_){
        $API_Endpoint = "https://api-3t.paypal.com/nvp";
        if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
		$API_Endpoint = "https://api-3t.".$this->environment.".paypal.com/nvp";
	}
	$version = urlencode('51.0');
        
	// setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// turning off the server and peer verification(TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// NVPRequest for submitting to server
	 $nvpreq="DESC=$this->productdesc&MAXFAILEDPAYMENTS=1&AUTOBILLAMT=AddToNextBilling&SUBSCRIBERNAME=WpEstate&PROFILEREFERENCE=1277&"; 
        $nvpreq .= "METHOD=$methodName_&VERSION=$version&PWD=$this->API_Password&USER=$this->API_UserName&SIGNATURE=$this->API_Signature$nvpStr_";

     
	// setting the nvpreq as POST FIELD to curl
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// getting response from server
	$httpResponse = curl_exec($ch);
	if(!$httpResponse) {
		exit($methodName_." failed x1: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the RefundTransaction response details
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}

}//end class

?>