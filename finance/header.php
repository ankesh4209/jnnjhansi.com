<?php
ob_start();
include_once('class.finance.php');
$page = $_GET['page'];
$chkpage=$page.'.php';
$page=$_GET['page'];
$obj = new finance();
function getDropDownPageWise($tableval,$selval)
{
$obj = new finance();
$res = $obj->getDropDown($tableval,$selval);
while($row = mysql_fetch_object($res))
{
$ListArray[$row->id]= $row->name;
}
$option='';
foreach($ListArray as $key=>$val)
{
$selected='';
if($key==$selval)
{
$selected='selected';
}
//$option.="<option value='$key' $selected>$val</option>";
$option.="<option style=\"font-family: 'kruti_dev_010regular';font-size:15px;width:130px;\" value='$key' $selected>$val</option>";
}
return $option;
}

switch($page)
{

case 'addmaddata':
$active2='active';
break;
case 'payamtlist':
$active3='active';
break;
case 'adduser':
$active4='active';
break;
case 'userlist':
$active5='active';
break;
case 'interest':
$active6='active';
break;
case 'report1':
case 'report2':
case 'report3':
case 'report4':
case 'report5':
case 'mrf_closed':
case 'chanel_closed':
case 'recoffered':
case 'sbu_open_closed':
$active00='active';
break;
case 'mad':
case 'addmad':
case 'workdesc':
case 'addworkdesc':
case 'contractor':
case 'addcontractor':
case 'bank':
case 'addbank':
$active_master='active';
break;
}
//
if(!empty($_POST['loginbtn']))
{
$username=$_POST['username'];
$password=$_POST['password'];	
if($obj->login($username,$password)>0)
{
 header("location:index.php?page=madlist");	
}
else
{
header("location:index.php?msg=Please enter valid username and password");
}
}
if(empty($page) or $page=='main')
{
?>
<div class="menu_login">
<div id="user_login">
<form name="login" action="#" method="POST">
<label>Username:</label>
<input name="username" type="text" value="" maxlength="5" onkeyup="javascript:{this.value = this.value.toUpperCase();}"/>
&nbsp;<label>Password:</label>
<input name="password" type="password" value="" />
<input type="submit" name="loginbtn" value="Login" class="btn"/>
&nbsp;&nbsp;<span style="font-size:11px; display:none;" class="forgot_ps"><a href="forgotpassword.html?keepThis=true&TB_iframe=true&height=120&width=350" title="Forgot Your Password" class="thickbox">Forgot Password</a></span>
</form>
<SCRIPT language="JavaScript">
 var frmvalidator  = new Validator("login");
frmvalidator.addValidation("username","req","Username can not be blank!");
frmvalidator.addValidation("username","maxlen=5","Please enter valid username");
frmvalidator.addValidation("password","req","Password can not be blank!"); 
  </SCRIPT>
</div>
</div>
<?php } elseif($_SESSION['usertype']=='SUPER')
{
?>   <div class="menu">
 			<ul>
			<li class="<?=$active_master?> first"><a href="index.php?page=mad">Masters</a></li>
			 <li class="<?=$active2?>"><a href="index.php?page=madlist">MAD</a></li>
			 <li class="<?=$active3?>"><a href="index.php?page=payamtlist">Pay Amount</a></li>
			 <li class="<?=$active6?>"><a href="index.php?page=interest">Interest</a></li>
			 <li class="<?=$active5?>"><a href="index.php?page=userlist">View User</a></li>
			 <li class="<?=$active4?>"><a href="index.php?page=adduser">Add User</a></li>
			 <li class="<?=$active00?>"><a href="index.php?page=report1">Reports</a></li>
			</ul>
</div>
<?php } elseif($_SESSION['usertype']=='USER'){ ?>
 <div class="menu">
 			<ul>
			
            <li class="<?=$active3?>"><a href="index.php?page=madlist">View Mad</a></li>			
			</ul>
</div>
<?php } else { ?>
 	 
        <div class="menu">
			<ul>
			
            <li class="<?=$active3?>"><a href="index.php?page=madlist">View Mad</a></li>
			<li class="<?=$active2?>"><a href="index.php?page=addmaddata">New Mad</a></li>
			
			</ul>
			 </div>			
<?php  }?>
                                                                                                               