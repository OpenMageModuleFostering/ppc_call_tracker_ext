<?php 
class Apj_PpcCallTracker_Block_Ppc extends Mage_Core_Block_Template {
	
	 protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('apj/ppccalltracker/ppccalltracker.phtml');
    }

	public function getPPCNumber () {
		
		$ppc_cookie_name = 'ppc_ct';
		$ppc_cookie_type = 'ppc_ct_type';
		$ppc_ct_cookie =  Mage::getModel('core/cookie')->get($ppc_cookie_name);
		$ppc_ct_cookie_type = Mage::getModel('core/cookie')->get($ppc_cookie_type);
	   	$ppc_ct_parameter = '';
		$ppc_ct_refferrer_google = '~https?://(www.)google.*?gclid~i';
		$ppc_ct_refferrer_msn = '~https?://(www.|)bing.com~i';
		$ppc_ct_refferrer_yahoo = '~https?://(www.|)yahoo.com~i';				
		$http_referrer = Mage::app()->getRequest()->getServer('HTTP_REFERER');
		$gclid = Mage::app()->getRequest('gclid');
			if(preg_match($ppc_ct_refferrer_google, $http_referrer) || $gclid ) {
				$ppc_ct_parameter = 'google';		
			} elseif (preg_match($ppc_ct_refferrer_msn, $http_referrer)) {
				$ppc_ct_parameter = 'msn';		
			} elseif (preg_match($ppc_ct_refferrer_yahoo, $http_referrer)) {
				$ppc_ct_parameter = 'yahoo';		
			} else {
				$ppc_ct_parameter = '';
			}
			
		
		//Get configuration values		
		$non_adwards_phone_number = Mage::getStoreConfig('ppccalltracker_options/tabgeneral/regularphone');
		
		$google_adwards_phone_number = Mage::getStoreConfig('ppccalltracker_options/tabgeneral/googlephone');
		
		$msn_adwards_phone_number = Mage::getStoreConfig('ppccalltracker_options/tabgeneral/msnphone');
		
		$yahoo_adwards_phone_number = Mage::getStoreConfig('ppccalltracker_options/tabgeneral/yahoophone');
		
		$ppc_ct_cookie_seconds = Mage::getStoreConfig('ppccalltracker_options/tabgeneral/cookiesec');
		
		$ppc_ct_cookie_date = Mage::getStoreConfig('ppccalltracker_options/tabgeneral/cookietime');
		
		$ppc_cookie_expiration = '';
		//$cookie_path = "/";
		//$cookie_domain = Mage::getBaseUrl();
		
		/*====================================================================================*/
		// Setting up Cookies  //
		/*====================================================================================*/		
		
		if($ppc_ct_cookie_date == 'first') { $cookie_expiration = $ppc_ct_cookie + $ppc_ct_cookie_seconds;} 

		else { $ppc_cookie_expiration = $ppc_ct_cookie_seconds;}
		
		// if cookie not set
		
		if ($ppc_ct_cookie === false) {
			// if url parameter contains adwords data
			if (isset($ppc_ct_parameter) && $ppc_ct_parameter == 'google') { 
		
				// set cookie to expire in defined number of seconds
				Mage::getModel('core/cookie')->set($ppc_cookie_name, time(), $ppc_cookie_expiration);
				Mage::getModel('core/cookie')->set($ppc_cookie_type, 'google', $ppc_cookie_expiration);

		
			} elseif (isset($ppc_ct_parameter) && $ppc_ct_parameter == 'msn') {
				// set cookie for msn Adwords
				Mage::getModel('core/cookie')->set($ppc_cookie_name, time(), $ppc_cookie_expiration);
				Mage::getModel('core/cookie')->set($ppc_cookie_type, 'msn', $ppc_cookie_expiration);				

			} elseif (isset($ppc_ct_parameter) && $ppc_ct_parameter == 'yahoo') {
				// set cookie for Yahoo Adwords
				Mage::getModel('core/cookie')->set($ppc_cookie_name, time(), $ppc_cookie_expiration);
				Mage::getModel('core/cookie')->set($ppc_cookie_type, 'yahoo', $ppc_cookie_expiration);								

			}
		
		}
		
		// if custom adwords cookie IS set
		
		elseif (isset($ppc_ct_cookie)) {
			// update the value and the seconds
				Mage::getModel('core/cookie')->set($ppc_cookie_name, $ppc_ct_cookie, $ppc_cookie_expiration);
				Mage::getModel('core/cookie')->set($ppc_cookie_type, $ppc_ct_cookie_type, $ppc_cookie_expiration);		

			
		
		}
		
		// Out put phone number	
		$googleadwords = $google_adwards_phone_number;
		$msnadwords = $msn_adwards_phone_number;
		$yahooadwords = $yahoo_adwards_phone_number;
		$regular = $non_adwards_phone_number;
		$ppc_call_tracker_output = '';
		
		// if custom adwords cookie is set
		if (isset($ppc_ct_cookie) && isset($ppc_ct_cookie_type) ) {
			if ($ppc_ct_cookie_type == 'google') {
				$ppc_call_tracker_output = "{$googleadwords}";
			} else if ($ppc_ct_cookie_type == 'msn') {
				$ppc_call_tracker_output = "{$msnadwords}";
			}else if ($ppc_ct_cookie_type == 'yahoo') {
				$ppc_call_tracker_output = "{$yahooadwords}";
			} else {
				$ppc_call_tracker_output = "{$regular}";
			}
	
		}
	
		// if url parameter contains ppc data
	
		elseif (isset($ppc_ct_parameter) && $ppc_ct_parameter == 'google' ) {
	
			$ppc_call_tracker_output = "{$googleadwords}";
	
		}
		elseif (isset($ppc_ct_parameter) && $ppc_ct_parameter == 'msn') {
			$ppc_call_tracker_output = "{$msnadwords}";
				
		}
		elseif (isset($ppc_ct_parameter) && $ppc_ct_parameter == 'yahoo') {
			$ppc_call_tracker_output = "{$yahooadwords}";
		}
	
		else {
	
			$ppc_call_tracker_output = "{$regular}";
	
		}
	
	  //send back text to calling function
	
	  return $ppc_call_tracker_output;		
	
	}
	
	
	
}
?>