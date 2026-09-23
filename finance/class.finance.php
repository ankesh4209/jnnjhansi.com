<?php
class finance
{
 private $dbhost='localhost';
 private $dbusername='jnnjhrqb_finance';
 private $dbpassword='finance123';

 private $dbname ='jnnjhrqb_finance';
 public $salt    ='1f2e9275f942e80f0950fb6811707fdt';
// public $salSalt ='324baed1941652c8dfa5de2af4c68f71';
	
 public $link;
public function __construct()
{
$link=mysql_connect($this->dbhost,$this->dbusername,$this->dbpassword) or die("unable to connect with server");
mysql_select_db($this->dbname,$link) or die(mysql_error());
$this->link=$link;
}
public function __destruct()
{
@mysql_close($this->link);
}
public function login($userName,$userPassword)
{
$sql="SELECT id,username,type,email,name as uname FROM users WHERE username='$userName' AND password='$userPassword' AND status =1";
$result=mysql_query($sql,$this->link) or die(mysql_error());
$n = mysql_num_rows($result);
$row = mysql_fetch_object($result);
$_SESSION['userid']=$row->id;
$_SESSION['username']=$row->username;
$_SESSION['useremail']=$row->email;
$_SESSION['usertype']=$row->type;
$_SESSION['uname']=$row->uname;
return $n;
}//session login
public function session_check($userid)
{
	if(empty($userid))
	{
	 header("location:index.php");
	}
	
} // end session_check
//function decode salary
function decodefield($field,$id)
{
$field = addslashes($field);
$sql="SELECT DECODE('$field','$this->salSalt') as defield FROM offer_data WHERE id='$id'";
$result=@mysql_query($sql,$this->link) or die(mysql_error($this->link));
$row = @mysql_fetch_object($result);
$defield = $row->defield;
return $defield;
}
function getDropDown($tablename,$selval=NULL)
{
$sql="SELECT id,name FROM $tablename where status=1 order by name";
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_error());
return $res;
} // end sbu


function number_range($selval)
{
$option='';
for($i=1;$i<=100;$i++)
{
$selected='';
if($i==$selval)
{
$selected='selected';
}
$option.="<option value='$i' $selected>$i</option>";
}//end foreach
return $option;	
}

function checkDuplicate($hiring_manager,$sbu,$sbuhead,$level)
{
$sql="SELECT hiring_manager,sbu,sbuhead FROM mrf_data where hiring_manager='$hiring_manager' AND sbu='$sbu' AND sbuhead='$sbuhead' AND level='$level'";
$res = mysql_query($sql,$this->link) or die(mysql_error());
$n = mysql_num_rows($res);
return $n;	
}
function getMaxId($table)
{
$sql="SELECT max(id) as maxid from $table";
$res = mysql_query($sql,$this->link) or die(mysql_error());
$row = @mysql_fetch_object($res);
$n = $row->maxid;
return $n+1;	
}
// *****************Save License Data*****************************
function saveMasterBankData($id=NULL)
{
$name=addslashes($_POST["name"]);
$branch=addslashes($_POST["branch"]);
$bank_acno=addslashes($_POST["bank_acno"]);
$working_proc=addslashes($_POST["working_proc"]); 

//$updated_date = date('Y-m-d');
//$user_id =  $_POST["user_id"] ;

if(!empty($id)){
 $sql="UPDATE bank SET name='$name',branch='$branch',bank_acno=$bank_acno,working_proc='$working_proc' WHERE id=$id";	
}
else
{
 //$license_no = date('Y|m|d')."|"."00".$maxid;
$sql="INSERT INTO bank(name ,branch, bank_acno, working_proc) VALUES('$name','$branch',$bank_acno,'$working_proc')";
}
//To set the char set
//mysql_set_charset('utf8',$this->link);
$res=mysql_query($sql,$this->link) or die(mysql_error($this->link));
return mysql_affected_rows($this->link);
}

//*****************End Save License Data *************************
function showdata($tablename,$fieldname=NULL,$id = NULL,$status=NULL,$filter=NULL)
{
if(!empty($filter)) $condition=$filter;
if(!empty($id))
$sql ="SELECT * FROM $tablename WHERE id=$id";	
else
 $sql ="SELECT * FROM $tablename $condition ORDER BY $fieldname";	
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
return $res;
}
function reportData($query)
{
$sql ="$query";	
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
return $res;
}
function showMasterData($tablename,$fieldname=NULL)
{
$sql ="SELECT * FROM $tablename where status=1 ORDER BY $fieldname desc";
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
return $res;
}
function getacnoAjax($tablename,$colname,$id)
{
$sql ="SELECT $colname as getcol FROM $tablename where id=$id";
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
$row = @mysql_fetch_object($res);
return $row->getcol;
}
//get interest info
function getTotalAvaAmt($type,$id)
{
if($type=="MAD"){
$sql ="select sum(total_ava_amt) as totavaamt from addmaddata where madid=$id group by madid";
}
else
{
$sql ="select sum(total_ava_amt) as totavaamt from addmaddata where bank_name=$id group by bank_name";
}
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
$row = @mysql_fetch_object($res);
return $row->totavaamt;
}

function getTotalAvaMadamt($workdesc,$madid)
{

$sql ="select total_ava_amt from addmaddata WHERE workdesc='$workdesc' and madid='$madid'";

//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
$row = @mysql_fetch_object($res);
return $row->total_ava_amt;
}

function disableData($tablename,$id)
{
$sql="UPDATE $tablename SET status=0 WHERE id=$id";
$res=mysql_query($sql,$this->link);
return mysql_affected_rows($this->link);	
}
function delete($tablename,$id)
{
$sql="DELETE FROM $tablename WHERE id=$id";
$res=mysql_query($sql,$this->link);
return mysql_affected_rows($this->link);	
}

function saveUser($id=NULL)
{
$username=addslashes($_POST['username']);
$password=addslashes($_POST['password']);
$name=addslashes($_POST['name']);
$email=addslashes($_POST['email']);
//$emailchk=$_POST['emailchk'];
$type=trim($_POST['type']);
if(!empty($id)){
$sql="UPDATE users SET username='$username',password='$password',name='$name', email='$email',type='$type' WHERE id=$id";
}
else
{
$sql="INSERT INTO users(username,password,name,email,type)VALUES('$username','$password','$name','$email','$type')";	
}
$res = mysql_query($sql,$this->link) or die(mysql_error());
return mysql_affected_rows($this->link);
}
function decodepassword($id)
{
$sql="SELECT DECODE(password,'$this->salt') as pass FROM users WHERE id=$id";
$res = mysql_query($sql,$this->link) or die(mysql_error());
$row = mysql_fetch_object($res);
return $row->pass; 	
}

function getLevelID($table,$levelArr)
{
foreach($levelArr as $val)
{
$leveStr.=$leveSt."'".$val."',";	
}
$leveStr= substr($leveStr,0,strlen($leveStr)-1);
$sql ="SELECT id from $table WHERE name in($leveStr) AND status=1";
$res = mysql_query($sql,$this->link) or die(mysql_error());
while($row = mysql_fetch_object($res))
{
$arrID[] =$row->id;	
}
$leveid = implode(',',$arrID);
return $leveid;
}

function saveMaster($tablename,$id=NULL)
{
$name = addslashes($_POST['name']);
if(!empty($id))
{
	$sql="UPDATE `$tablename` set name='$name' WHERE id=$id";
}
else
{
	$sql="INSERT INTO `$tablename`(name)VALUES('$name')";
}
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
return $res;	
}

function saveContractorMaster($id=NULL)
{
$name = addslashes($_POST['name']);
$firm_name = addslashes($_POST['firm_name']);
$contact_no = addslashes($_POST['contact_no']);
$bank_accountno = addslashes($_POST['bank_accountno']);
$bank_name = addslashes($_POST['bank_name']);
$bank_branch = addslashes($_POST['bank_branch']);
$ifsc_code = addslashes($_POST['ifsc_code']);
$pancard   = trim($_POST['pancard']);
if(!empty($id))
{
	$sql="UPDATE contractor set name='$name', firm_name='$firm_name', contact_no='$contact_no', bank_accountno='$bank_accountno', bank_name='$bank_name', bank_branch='$bank_branch', ifsc_code='$ifsc_code',pancard='$pancard' WHERE id=$id";
}
else
{
	echo $sql="INSERT INTO contractor(name, firm_name, contact_no, bank_accountno, bank_name, bank_branch, ifsc_code, pancard) VALUES('$name', '$firm_name', '$contact_no', '$bank_accountno', '$bank_name', '$bank_branch', '$ifsc_code','$pancard')";
}
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
return $res;	
}
//Save mad data
function saveMadData($id=NULL)
{
$madid			= addslashes($_POST['madid']);
$ref_letno		= addslashes($_POST['ref_letno']);
$mad_amount		= addslashes($_POST['mad_amount']);
$bank_name		= addslashes($_POST['bank_name']);
$bank_acno		= addslashes($_POST['bank_acno']);
$receive_date	= addslashes($_POST['receive_date']);
$comments		= addslashes($_POST['comments']);
$total_ava_amt	=  addslashes($_POST['total_ava_amt']);
$mad_interest	=  addslashes($_POST['mad_interest']);
$workdesc	    =  addslashes($_POST['workdesc']);

$updated_date	= date('Y-m-d');
$userid			= ($_POST['userid']);

if(!empty($id))
{
	 $sql="UPDATE addmaddata set madid='$madid', ref_letno='$ref_letno', mad_amount='$mad_amount', bank_name='$bank_name', bank_acno='$bank_acno', receive_date='$receive_date', comments='$comments',total_ava_amt='$total_ava_amt',mad_interest='$mad_interest',workdesc='$workdesc' WHERE id=$id";
	 
}
else
{
	   $sql="INSERT INTO addmaddata(madid, ref_letno, mad_amount,workdesc,mad_interest, bank_name, bank_acno, receive_date, comments,total_ava_amt,updated_date,userid) VALUES('$madid', '$ref_letno', '$mad_amount','$workdesc','$mad_interest', '$bank_name', '$bank_acno', '$receive_date', '$comments','$mad_amount','$updated_date','$userid')";
}
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
return $res;	
}
// Save Payment to contractor data
function savePayData($id=NULL)
{
$madid				= addslashes($_POST['madid']);
$contractorid		= addslashes($_POST['contractorid']);
$bankid				= addslashes($_POST['bankid']);
$checkno			= addslashes($_POST['checkno']);
$paidamt			= addslashes($_POST['paidamt']);
$payment_date		= addslashes($_POST['payment_date']);
$workdesc		    = addslashes($_POST['workdesc']);

$updated_date		= date('Y-m-d');
$userid				= ($_POST['userid']);

$tot_ava_amt = $this->getTotAvaamt($workdesc,$madid);  // get tot amount with interest
$tot_ava_amt_updated = $tot_ava_amt - $paidamt;
$this->updateTotAvaamt($workdesc,$madid,$tot_ava_amt_updated);
if(!empty($id))
{
	$sql="UPDATE payamount set madid=$madid, contractorid=$contractorid,workdesc='$workdesc', bankid=$bankid, checkno='$checkno', paidamt='$paidamt', payment_date='$payment_date' WHERE id=$id";
}
else
{
	  $sql="INSERT INTO payamount(madid, contractorid,workdesc, bankid, checkno, paidamt, payment_date, updated_date,userid) VALUES($madid, $contractorid,'$workdesc', $bankid, '$checkno', '$paidamt', '$payment_date','$updated_date',$userid)";
}
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
return $res;	
}
// Save Interest Data 
function saveInterestData($id=NULL)
{
$bankid				= addslashes($_POST['bankid']);
$accountno			= addslashes($_POST['accountno']);
$interest_amt		= addslashes($_POST['interest_amt']);
$totavaamt			= addslashes($_POST['avaamt']);
$interest_date		= addslashes($_POST['interest_date']);

$updated_date		= date('Y-m-d');
$userid				= ($_POST['userid']);

if(!empty($id))
{
	$sql="UPDATE interest set bankid=$bankid, accountno=$accountno, interest_amt=$interest_amt, totavaamt=$totavaamt, interest_date='$interest_date' WHERE id=$id";
}
else
{
	  echo $sql="INSERT INTO interest(bankid, accountno, interest_amt, totavaamt, interest_date, updated_date,userid) VALUES($bankid, $accountno, $interest_amt, $totavaamt, '$interest_date','$updated_date',$userid)";
}
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
return $res;	
}


function getmailInfo($id)
{
$sql ="SELECT * FROM offer_data WHERE id=$id";	
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
return $res;
}
function getMasterName($tablename,$colname,$id)
{
$sql="SELECT $colname as name FROM $tablename WHERE id=$id";
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
$row = mysql_fetch_object($res);
return $row->name;	
}

function getName($tablename,$id)
{
$sql="SELECT name FROM $tablename WHERE id=$id";
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
$row = mysql_fetch_object($res);
return $row->name;	
}
//check emp code duplicate

function checkDuplicateCode($code)
{
$sql="SELECT code FROM offer_data where code='$code'";
$res = mysql_query($sql,$this->link) or die(mysql_error());
$n = mysql_num_rows($res);
return $n;	
}



//***************end send mail after MTYP Approve******
// send mail when add user
function SendingMail($UserName,$UserEmail,$Name)  
	{    
		$to  =$UserEmail;  
		$from= 'LMS';            
		$subject= "LMS Login Credentials";                  
		$headers.="From:$from";  
		$headers.="\nMIME-version: 1.0rn\n"."Content-type: text/html; charset=iso-8859-1rn";  /* protocol */
		$message= ""; 
		echo "<br>";
$message.="<html><strong>Please use below credentials to LMS login:</strong><p>
		 <table align=\"left\" width=\"60%\" hieght=\"50%\" border=\"0\" align=\"left\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FFFFFF\"
		  style=\"border-top: 1px solid #40E0D0; border-bottom: 1px solid #40E0D0;border-left: 1px solid #40E0D0; border-right: 1px solid #40E0D0\" >
";
$message.="<td colspan=\"2\">
					<table width=\"100%\" border=\"0\" align=\"center\" bordercolor=\"#000000\">";
				    $obj=new recruitment(); // create object of the class recruitment //
					$sql="select id from users where username='$UserName'";
					$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
					$row = mysql_fetch_object($res); 
					$decodedpass=$obj->decodepassword($row->id);
$message.="<tr bgcolor=\"#E8F6FF\">
			             <td height=\"21\" width=\"50%\" ><strong>Username :</strong></td> 
						 <td class=\"txtarea_id\" mrfcode\" height=\"21\"width=\"50%\" >".$UserName."</td>
						 </tr>";
$message.="<tr bgcolor=\"#E8F6FF\">
			             <td height=\"21\" width=\"50%\" ><strong>Password :</strong></td> 
						 <td class=\"txtarea_id\" mrfcode\" height=\"21\"width=\"50%\" >". $decodedpass."</td>
						 </tr>";
 $message.="<tr bgcolor=\"#E8F6FF\">
			             <td height=\"21\" width=\"50%\" ><strong>RMS Site:</strong></td> 
						 <td class=\"txtarea_id\" mrfcode\" height=\"21\"width=\"50%\" ><strong><a href=\" http://localhost/license\">LMS SITE</a></strong></td>
						 </tr>
		 </table>
	  </td>
	</table></html>";
 mail($to,$subject,$message,$headers); 
	}
//************* End send mail when add user*****************
function checkDupUser($email=NULL,$username)
{
$sql="SELECT * FROM users where username='$username'";
$res = mysql_query($sql,$this->link) or die(mysql_error());
$n = mysql_num_rows($res);
return $n;	
}	
function getTotAvaamt($workdesc,$madid)
{
$sql="SELECT (total_ava_amt + mad_interest) as total_ava_amt  FROM addmaddata WHERE workdesc='$workdesc' and madid='$madid'";
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
$row = mysql_fetch_object($res);
return $row->total_ava_amt;		
}

function updateTotAvaamt($workdesc,$madid,$amt)
{
$sql="UPDATE addmaddata SET total_ava_amt='$amt', mad_interest='0',mad_amount='$amt' WHERE workdesc='$workdesc' and madid='$madid'";
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
$row = mysql_fetch_object($res);
}
//
} // end class
?>