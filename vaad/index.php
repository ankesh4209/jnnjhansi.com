<?php
ob_start();
session_start();
ini_set('display_errors', 'Off');
error_reporting(0);
$pageSuper=array('department','adddepartment','adduser','userlist','caselist','court','addcourt','addcase','bank','addbank','advocate','addadvocate','recruiters','payamtlist','addpayamt','report1','report2','report3','report4');
$pageHR=array('addpaymnt','madlist','caselist','addcase','addmaddata','interest','addinterest','mrf_closed');
if(($_SESSION['usertype']=='SUPER' or $_SESSION['usertype']=='ADMIN') and !in_array($_GET['page'],$pageSuper)) 
header("location:index.php?page=madlist");
if($_SESSION['usertype']=='USER' and !in_array($_GET['page'],$pageHR)) header("location:index.php?page=madlist");

if(empty($_GET['page']) or $_GET['page']=='main')
{
$bodyload='onLoad="document.login.username.focus();"';
}
//$dateTime = new DateTime("now");

$timezone = new DateTimeZone('Asia/Calcutta');
$dateTime = new DateTime("now", $timezone);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("utility.html"); ?>
<script type="text/javascript" id="sourcecode">
	$(function()
	{
		var api = $('.scroll-pane').jScrollPane(
			{
				showArrows:true,
				maintainPosition: true,
				autoReinitialise: true
			}
		);
		
	});
</script>
<script type="text/javascript">
var currenttime = '<?php print $dateTime->format("F d, Y H:i:s")?>' //PHP method of getting server date
var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var serverdate=new Date(currenttime)
var serverdate1=new Date(currenttime)
function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}
function showTime(){
serverdate1.setSeconds(serverdate1.getSeconds()+1)
var datestring=montharray[serverdate1.getMonth()]+" "+padlength(serverdate1.getDate())+", "+serverdate1.getFullYear()
var timestring=padlength(serverdate1.getHours())+":"+padlength(serverdate1.getMinutes())+":"+padlength(serverdate1.getSeconds())
document.getElementById("servertime").innerHTML=datestring+" "+timestring;

}
window.onload=function(){
setInterval("showTime()", 1000);
f1(displaytime())
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Online VAAD</title>
</head>
<body>
<!--main container end-->
<div id="container">
<!--main wrapper start-->
<div id="outerwrapper">
<div id="wrapper">
<!--Header Start-->
<div id="header_wrapper">
<div id="header">
<div class="logo"><img src="images/logo.png" width="109" height="61" /></div>
<?php if(!empty($_SESSION['username']) and $_GET['task']!='logout')
{ ?>
<div id="logged_in" class="white">
<span id="servertime"></span>
<a href="index.php?page=logout" class="logoutbt" title="Logout">Logout </a>
<div class="user_info">Welcome<strong><?=$_SESSION['username']?></strong> </div>
</div>
<?php } ?>
</div>
</div>
<!--Header End-->
<div id="main_nav">
<?php include_once("header.php"); ?>
</div>
<div class="horz_strip"></div>
<?php if(!empty($_GET['msg']))
{ ?>
<div style="background-color:#FF9900; color:#FF0000;" align="center"><?=$_GET['msg']?></div>
<?php } ?>
<div id="con_container">
<?php
if(file_exists($chkpage))
{
$obj->session_check($_SESSION['userid']);
include_once($chkpage);
}
else
{
include_once('main.php');
}
?>
</div>
<div id="footer" >
<?php include_once('footer.php'); ?>
</div>
</div>
</div>
<!--main wrapper end-->
</div>
<!--main container end-->
</body>
</html>