<?php

class recruitment {

  //Live Server
  
    public $dbhost = 'localhost';
    public $dbusername = 'jnnjhrqb_licens1';
    public $dbpassword = 'licens1#@!';
    public $dbname = 'jnnjhrqb_license';
  

   //Dev Server
   /* public $dbhost = 'localhost';
    public $dbusername = 'cropswdq_license';
    public $dbpassword = 'licens1#@!';
    public $dbname = 'cropswdq_license';
 */
    public $salt = '1f2e9275f942e80f0950fb6811707fd7';
// public $salSalt ='324baed1941652c8dfa5de2af4c68f71';

    public $link;

    public function __construct() {
        $link = mysql_connect($this->dbhost, $this->dbusername, $this->dbpassword) or die("unable to connect with server");
        mysql_select_db($this->dbname, $link) or die(mysql_error());
        $this->link = $link;
    }

    public function __destruct() {
        @mysql_close($this->link);
    }

    public function login($userName, $userPassword) {
        $sql = "SELECT id,username,type,email,name as uname FROM users WHERE username='$userName' AND password='$userPassword' AND status =1";
        $result = mysql_query($sql, $this->link) or die(mysql_error());
        $n = mysql_num_rows($result);
        $row = mysql_fetch_object($result);
        $_SESSION['userid'] = $row->id;
        $_SESSION['username'] = $row->username;
        $_SESSION['useremail'] = $row->email;
        $_SESSION['usertype'] = $row->type;
        $_SESSION['uname'] = $row->uname;
        return $n;
    }

//session login

    public function session_check($userid) {
        if (empty($userid)) {
            header("location:index.php");
        }
    }

// end session_check
//function decode salary

    function decodefield($field, $id) {
        $field = addslashes($field);
        $sql = "SELECT DECODE('$field','$this->salSalt') as defield FROM offer_data WHERE id='$id'";
        $result = @mysql_query($sql, $this->link) or die(mysql_error($this->link));
        $row = @mysql_fetch_object($result);
        $defield = $row->defield;
        return $defield;
    }

    function getDropDown($tablename, $selval = NULL) {
        $sql = "SELECT id,name FROM $tablename where status=1 order by name";
//mysql_set_charset('utf8',$this->link);
        $res = mysql_query($sql, $this->link) or die(mysql_error());
        return $res;
    }

// end sbu

    function number_range($selval) {
        $option = '';
        for ($i = 1; $i <= 100; $i++) {
            $selected = '';
            if ($i == $selval) {
                $selected = 'selected';
            }
            $option.="<option value='$i' $selected>$i</option>";
        }//end foreach
        return $option;
    }

    function checkDuplicate($hiring_manager, $sbu, $sbuhead, $level) {
        $sql = "SELECT hiring_manager,sbu,sbuhead FROM mrf_data where hiring_manager='$hiring_manager' AND sbu='$sbu' AND sbuhead='$sbuhead' AND level='$level'";
        $res = mysql_query($sql, $this->link) or die(mysql_error());
        $n = mysql_num_rows($res);
        return $n;
    }

    function getMaxId($table) {
        $sql = "SELECT max(id) as maxid from $table";
        $res = mysql_query($sql, $this->link) or die(mysql_error());
        $row = @mysql_fetch_object($res);
        $n = $row->maxid;
        return $n + 1;
    }
    
    function getMaxLicId($table) {
        $sql = "SELECT license_no from $table order by id desc limit 0,1";
        $res = mysql_query($sql, $this->link) or die(mysql_error());
        $row = @mysql_fetch_object($res);
        //$n = $row->maxid;
        $license_no=explode('|',$row->license_no);
        return (int)$license_no[3] + 1;
    }

// *****************Save License Data*****************************
    function saveLicenseData($id = NULL) {
        $fine_amt = 0;
        $dis_amt = 0;
        $license_type = addslashes($_POST["license_type"]);
        $category = addslashes($_POST["category"]);
        $location = addslashes($_POST["location"]);
        $situate = addslashes($_POST["situate"]);
//$billno=addslashes($_POST["billno"]);
        $amount = addslashes($_POST["amount"]);
        $amount_in_words = addslashes($_POST["amount_in_words"]);
        $name = addslashes($_POST["name"]);
        $father_name = addslashes($_POST["father_name"]);
        $address = addslashes($_POST["address"]);
        $btype = addslashes($_POST["btype"]);
        $status = addslashes($_POST["status"]);
        $final_submit = addslashes($_POST["final_submit"]);
        $reg_date = trim($_POST["reg_date"]);
        $exp_date = trim($_POST["exp_date"]);
        $updated_date = date('Y-m-d');
        $user_id = $_POST["user_id"];
		$renew = $_POST["renew"];

//*****Start land or vech calculation
        $vehicle_type = $_POST["vehicle_type"];
        $vehicle_area = $_POST["vehicle_area"];
        $vehicle_no = $_POST["vehicle_no"];
        $vehicle_reg = $_POST["vehicle_reg"];
        if ($license_type == "LND") {
            $vehicle_type = '';
            $vehicle_area = '';
            $vehicle_no = '';
            $vehicle_reg = '';
        }
        if ($license_type == "VEH") {
            $btype = '';
        }
//********End land or veh calculation
        $dis_type = $_POST["dis_type"];
        $fine_amt = $_POST["fine_amt"];
        $dis_amt = $_POST["dis_amt"];
        if ($dis_type == 'FINE') {
            $dis_amt = "";
        }
        if ($dis_type == 'DIS') {
            $fine_amt = "";
        }

//****** Start payment type calculation
        $pay_type = $_POST["pay_type"];
        $chk_no = $_POST["chk_no"];
        $dd_no = $_POST["dd_no"];
        if ($pay_type == 'CASH') {
            $dd_no = '';
            $chk_no = '';
        }
        if ($pay_type == 'CHECK') {
            $dd_no = '';
        }
        if ($pay_type == 'DD') {
            $chk_no = '';
        }
//******End payment type calculations

        if (!empty($id)) {
            $sql = "UPDATE license_data SET license_type='$license_type',category='$category',location='$location',situate='$situate',amount=$amount ,amount_in_words='$amount_in_words',dis_type='$dis_type',fine_amt='$fine_amt',dis_amt='$dis_amt',name='$name',father_name='$father_name',address='$address',btype='$btype', user_id='$user_id',reg_date='$reg_date',final_submit='$final_submit',exp_date='$exp_date',vehicle_type='$vehicle_type',vehicle_area='$vehicle_area',vehicle_no='$vehicle_no',vehicle_reg='$vehicle_reg',pay_type='$pay_type',chk_no='$chk_no',dd_no='$dd_no',updated_date='$updated_date',renew='$renew' WHERE id=$id";
        } else {
            $maxid= $this->getMaxId('license_data');
            $temp=1873;
  
	  if($maxid==1873){
	    $maxid=1;
	  }else{
		  $maxid=$maxid-$temp;
	  }
            $license_no = date('Y|m|d') . "|" . $maxid;
            $sql = "INSERT INTO license_data(license_no ,license_type, category, location, situate, amount, amount_in_words,dis_type,fine_amt,dis_amt, name , father_name, address , reg_date , exp_date , updated_date,vehicle_type,vehicle_area,vehicle_no,vehicle_reg ,btype,pay_type,chk_no,dd_no,final_submit, user_id,first_reg_date) VALUES('$license_no','$license_type',$category, $location, '$situate', $amount, '$amount_in_words','$dis_type','$fine_amt','$dis_amt','$name' , '$father_name', '$address' , '$reg_date' , '$exp_date' , '$updated_date','$vehicle_type','$vehicle_area','$vehicle_no','$vehicle_reg', '$btype','$pay_type','$chk_no','$dd_no','$final_submit', $user_id,'$updated_date')";
        }
//To set the char set
//mysql_set_charset('utf8',$this->link);
        $res = mysql_query($sql, $this->link) or die(mysql_error($this->link));
        return mysql_affected_rows($this->link);
    }

//*****************End Save License Data *************************
    function showdata($tablename, $fieldname = NULL, $id = NULL, $status = NULL, $filter = NULL) {

        if (!empty($filter))
            $condition = $filter;
        if (!empty($id))
            $sql = "SELECT * FROM $tablename WHERE id=$id";
        else
            $sql = "SELECT * FROM $tablename $condition ORDER BY $fieldname";
            //echo $sql;
        $res = mysql_query($sql, $this->link) or die(mysql_error($this->link));
        return $res;
    }

    function reportData($query) {
        $sql = "$query";
        $res = mysql_query($sql,$this->link) or die(mysql_error($this->link));
        return $res;
    }
    
    function reportData1($query) {
        echo $sql = "$query";
        $res = mysql_query($query,$this->link);
        print_r($res);
        if (!$res) {
         echo "DB Error, could not query the database\n";
         echo 'MySQL Error: ' . mysql_error();
         exit;
       }
        return $res;
    }

	

    function showMasterData($tablename, $fieldname = NULL) {
        $sql = "SELECT * FROM $tablename where status=1 ORDER BY $fieldname";
//mysql_set_charset('utf8',$this->link);
        $res = mysql_query($sql, $this->link) or die(mysql_error($this->link));
        return $res;
    }

    function disableData($tablename, $id) {
        $sql = "UPDATE $tablename SET status=0 WHERE id=$id";
        $res = mysql_query($sql, $this->link);
        return mysql_affected_rows($this->link);
    }

    function delete($tablename, $id) {
        $sql = "DELETE FROM $tablename WHERE id=$id";
        $res = mysql_query($sql, $this->link);
        return mysql_affected_rows($this->link);
    }

    function saveUser($id = NULL) {
        $username = addslashes($_POST['username']);
        $password = addslashes($_POST['password']);
        $name = addslashes($_POST['name']);
        $email = addslashes($_POST['email']);
        $sbu = addslashes($_POST['sbu']);
        $emailchk = $_POST['emailchk'];
        $type = trim($_POST['type']);
        if (!empty($id))
            $sql = "UPDATE users SET username='$username',password='$password',name='$name', email='$email',emailchk='$emailchk',type='$type' WHERE id=$id";
        else
            $sql = "INSERT INTO users(username,password,name,email,emailchk,type)VALUES('$username','$password','$name','$email','$emailchk','$type')";
        $res = mysql_query($sql, $this->link) or die(mysql_error());
        return mysql_affected_rows($this->link);
    }

    function decodepassword($id) {
        $sql = "SELECT DECODE(password,'$this->salt') as pass FROM users WHERE id=$id";
        $res = mysql_query($sql, $this->link) or die(mysql_error());
        $row = mysql_fetch_object($res);
        return $row->pass;
    }

    function getLevelID($table, $levelArr) {
        foreach ($levelArr as $val) {
            $leveStr.=$leveSt . "'" . $val . "',";
        }
        $leveStr = substr($leveStr, 0, strlen($leveStr) - 1);
        $sql = "SELECT id from $table WHERE name in($leveStr) AND status=1";
        $res = mysql_query($sql, $this->link) or die(mysql_error());
        while ($row = mysql_fetch_object($res)) {
            $arrID[] = $row->id;
        }
        $leveid = implode(',', $arrID);
        return $leveid;
    }

    function saveMaster($tablename, $id = NULL) {
        $name = addslashes($_POST['name']);
        if (!empty($id)) {
            $sql = "UPDATE `$tablename` set name='$name' WHERE id=$id";
        } else {
            $sql = "INSERT INTO `$tablename`(name)VALUES('$name')";
        }
//mysql_set_charset('utf8',$this->link);
        $res = mysql_query($sql, $this->link) or die(mysql_errno($this->link));
        return $res;
    }

    function print_doc($id) {
        $sql = "SELECT * FROM license_data WHERE id=$id";
        $res = mysql_query($sql, $this->link) or die(mysql_error($this->link));
        return $res;
    }

    function getmailInfo($id) {
        $sql = "SELECT * FROM offer_data WHERE id=$id";
        $res = mysql_query($sql, $this->link) or die(mysql_error($this->link));
        return $res;
    }

    function getMasterName($tablename, $colname, $id) {
        $sql = "SELECT $colname as name FROM $tablename WHERE id=$id";
        $res = mysql_query($sql, $this->link) or die(mysql_error($this->link));
        $row = mysql_fetch_object($res);
        return $row->name;
    }
    
    function getLocationName($id) {
        $sql = "SELECT * FROM location WHERE id=$id";
        $res = mysql_query($sql, $this->link) or die(mysql_error($this->link));
        $row = mysql_fetch_object($res);
        return $row->name;
    }

    function getName($tablename, $id) {
        $sql = "SELECT name FROM $tablename WHERE id=$id";
        $result = mysql_query($sql, $this->link) or die(mysql_error($this->link));
        $row = mysql_fetch_object($result);
        return $row->name;
    }

//check emp code duplicate

    function checkDuplicateCode($code) {
        $sql = "SELECT code FROM offer_data where code='$code'";
        $res = mysql_query($sql, $this->link) or die(mysql_error());
        $n = mysql_num_rows($res);
        return $n;
    }

//***************end send mail after MTYP Approve******
// send mail when add user
    function SendingMail($UserName, $UserEmail, $Name) {
        $to = $UserEmail;
        $from = 'LMS';
        $subject = "LMS Login Credentials";
        $headers.="From:$from";
        $headers.="\nMIME-version: 1.0rn\n" . "Content-type: text/html; charset=iso-8859-1rn";  /* protocol */
        $message = "";
        echo "<br>";
        $message.="<html><strong>Please use below credentials to LMS login:</strong><p>
		 <table align=\"left\" width=\"60%\" hieght=\"50%\" border=\"0\" align=\"left\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FFFFFF\"
		  style=\"border-top: 1px solid #40E0D0; border-bottom: 1px solid #40E0D0;border-left: 1px solid #40E0D0; border-right: 1px solid #40E0D0\" >
";
        $message.="<td colspan=\"2\">
					<table width=\"100%\" border=\"0\" align=\"center\" bordercolor=\"#000000\">";
        $obj = new recruitment(); // create object of the class recruitment //
        $sql = "select id from users where username='$UserName'";
        $res = mysql_query($sql, $this->link) or die(mysql_error($this->link));
        $row = mysql_fetch_object($res);
        $decodedpass = $obj->decodepassword($row->id);
        $message.="<tr bgcolor=\"#E8F6FF\">
			             <td height=\"21\" width=\"50%\" ><strong>Username :</strong></td> 
						 <td class=\"txtarea_id\" mrfcode\" height=\"21\"width=\"50%\" >" . $UserName . "</td>
						 </tr>";
        $message.="<tr bgcolor=\"#E8F6FF\">
			             <td height=\"21\" width=\"50%\" ><strong>Password :</strong></td> 
						 <td class=\"txtarea_id\" mrfcode\" height=\"21\"width=\"50%\" >" . $decodedpass . "</td>
						 </tr>";
        $message.="<tr bgcolor=\"#E8F6FF\">
			             <td height=\"21\" width=\"50%\" ><strong>RMS Site:</strong></td> 
						 <td class=\"txtarea_id\" mrfcode\" height=\"21\"width=\"50%\" ><strong><a href=\" http://localhost/license\">LMS SITE</a></strong></td>
						 </tr>
		 </table>
	  </td>
	</table></html>";
        mail($to, $subject, $message, $headers);
    }

//************* End send mail when add user*****************
    function checkDupUser($email = NULL, $username) {
        $sql = "SELECT * FROM users where username='$username'";
        $res = mysql_query($sql, $this->link) or die(mysql_error());
        $n = mysql_num_rows($res);
        return $n;
    }

//
}

// end class
?>