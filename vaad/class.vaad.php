<?php
class vaad
{
//Live Server

public $dbhost='localhost';
 public $dbusername='jnnjhrqb_vaad';
 public $dbpassword='vaad_!@#$';
 public $dbname ='jnnjhrqb_vaad';
 
 
 //Dev Server
 /*public $dbhost='localhost';
 public $dbusername='cropswdq_vaad123';
 public $dbpassword='vaad@123';
 public $dbname ='cropswdq_vaad';
 */
/*
 //Local Server
 public $dbhost='localhost';
 public $dbusername='root';
 public $dbpassword='';
 public $dbname ='cropswdq_vaad';
 public $salt    ='1f2e9275f942e80f0950fb6811707fd7';
// public $salSalt ='324baed1941652c8dfa5de2af4c68f71';
*/	
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
 $sql="UPDATE bank SET bank_name='$bank_name',branch='$branch',bank_acno=$bank_acno,working_proc='$working_proc' WHERE id=$id";	
}
else
{
 //$license_no = date('Y|m|d')."|"."00".$maxid;
$sql="INSERT INTO bank(bank_name ,branch, bank_acno, working_proc) VALUES('$bank_name','$branch',$bank_acno,'$working_proc')";
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
if(!empty($id)) {
 $sql ="SELECT * FROM $tablename WHERE id=$id";	
} 
else {
 $sql ="SELECT * FROM $tablename $condition ORDER BY $fieldname";;
}	
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
return $res;
}

function reportData($query)
{
//mysql_query("SET NAMES utf8",$this->link); //the main trick
$sql ="$query";	
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
return $res;
}
function showMasterData($tablename,$fieldname=NULL)
{
$sql ="SELECT * FROM $tablename where status=1 ORDER BY $fieldname";
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
return $res;
}
function getacnoAjax($tablename,$colname,$id)
{
$sql ="SELECT $colname as getcol FROM $tablename where id=$id";
mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
$row = @mysql_fetch_object($res);
return $row->getcol;
}
//get interest info
function getTotalAvaAmt($type,$id)
{
$sql ="select sum(total_ava_amt) as totavaamt from addmaddata where bank_name=$id group by bank_name";
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
$row = @mysql_fetch_object($res);
return $row->totavaamt;
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
$sbu=addslashes($_POST['sbu']);
$emailchk=$_POST['emailchk'];
$type=trim($_POST['type']);
if(!empty($id))
$sql="UPDATE users SET username='$username',password='$password',name='$name', email='$email',emailchk='$emailchk',type='$type' WHERE id=$id";
else
$sql="INSERT INTO users(username,password,name,email,emailchk,type)VALUES('$username','$password','$name','$email','$emailchk','$type')";	
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

function saveAdvocateMaster($id=NULL)
{
$name = addslashes($_POST['name']);
$address = addslashes($_POST['address']);
$licence_no = addslashes($_POST['licence_no']);
$contact_no = addslashes($_POST['contact_no']);
$app_date = addslashes($_POST['app_date']);

if(!empty($id))
{
	$sql="UPDATE advocate set address='$address', licence_no='$licence_no', contact_no='$contact_no', app_date='$app_date' WHERE id=$id";
}
else
{
	 $sql="INSERT INTO advocate(name, address, licence_no, contact_no, app_date) VALUES('$name', '$address', '$licence_no', '$contact_no', '$app_date')";
}
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
return $res;	
}
//Save mad data
function saveCaseData($id=NULL)
{
$orderno			= addslashes($_POST['orderno']);
$activity_no		= addslashes($_POST['activity_no']);
$party				= addslashes($_POST['party']);
$property_desc		= addslashes($_POST['property_desc']);
$department			= addslashes($_POST['department']);
$court				= addslashes($_POST['court']);
$dhara				= addslashes($_POST['dhara']);
$schedule_date		= addslashes($_POST['schedule_date']);
$result_date		= addslashes($_POST['result_date']);
$ref_lawer			= addslashes($_POST['ref_lawer']);
$description		= addslashes($_POST['description']);
$case_status		= addslashes($_POST['case_status']);
$reason_status		= addslashes($_POST['reason_status']);
$last_date			= addslashes($_POST['last_date']);
$cur_discuss_date	= addslashes($_POST['cur_discuss_date']);
$comments			= addslashes($_POST['comments']);
$next_discss_date	= addslashes($_POST['next_discss_date']);

$updated_date		= date('Y-m-d');
$userid				= ($_POST['userid']);

if(!empty($id))
{
	$sql="UPDATE addcase set orderno='$orderno',activity_no='$activity_no', party='$party', property_desc='$property_desc', department='$department', court='$court', dhara='$dhara', schedule_date='$schedule_date',result_date='$result_date',ref_lawer='$ref_lawer',description='$description',case_status='$case_status',reason_status='$reason_status',last_date='$last_date',cur_discuss_date='$cur_discuss_date',comments='$comments',next_discss_date='$next_discss_date' WHERE id=$id";
}
else
{
	 $sql="INSERT INTO addcase(orderno,activity_no, party, property_desc, department, court, dhara, schedule_date,result_date,ref_lawer,description,updated_date,userid) VALUES('$orderno','$activity_no', '$party', '$property_desc', '$department', '$court', '$dhara', '$schedule_date','$result_date','$ref_lawer','$description','$updated_date','$userid')";
}
//mysql_set_charset('utf8',$this->link);
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
return $res;	
}
//save case info
//Save mad data
function saveCaseDetailsData($id=NULL)
{
$court				= addslashes($_POST['court']);
$schedule_date		= addslashes($_POST['schedule_date']);
$result_date		= addslashes($_POST['result_date']);
$ref_lawer			= addslashes($_POST['ref_lawer']);
$comments			= addslashes($_POST['comments']);
$updatedate		    = date('Y-m-d');
$userid				= ($_POST['userid']);
$caseid				= ($_POST['caseid']);


if(!empty($id))
{
	$sql="UPDATE addcaseinfo set court='$court',schedule_date='$schedule_date',result_date='$result_date',ref_lawer='$ref_lawer',comments='$comments',updatedate='$updatedate' WHERE id=$id";
}
else
{
	  $sql="INSERT INTO addcaseinfo(court,schedule_date,result_date,ref_lawer,comments,updatedate,userid,caseid) VALUES( '$court',  '$schedule_date','$result_date','$ref_lawer','$comments','$updatedate','$userid','$caseid')";
	// exit;
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
//mysql_query("SET NAMES utf8",$this->link); //the main trick
$res = mysql_query($sql,$this->link) or die(mysql_errno($this->link));
$row = mysql_fetch_object($res);
return $row->name;	
}

function getName($tablename,$id)
{
$sql="SELECT name FROM $tablename WHERE id=$id";
//mysql_query("SET NAMES utf8",$this->link); //the main trick
$res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
$rown = mysql_fetch_object($res);
return $rown->name;	
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
//
function getDropDownPageWisedetails($tableval,$selval)
{
$res = $this->getDropDown($tableval,$selval);
while($row = mysql_fetch_object($res))
{
$ListArray[$row->id]= $row->name;
}
$option='';                   
foreach($ListArray as $key=>$val)
{
$val= str_replace("\\","",stripslashes($val));
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
} // end class
?>