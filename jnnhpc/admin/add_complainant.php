<?php session_start(); ?>
<?php 
 if ($_SESSION['username']=='')
  {	
    header ("Location: index.php"); 				
	  exit;
  }
?>
<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

if($_POST['submit']!="")
 {

	$cname=addslashes($_POST['cname']);
    $caddress=addslashes($_POST['address']);
    
    $c_officer=$_POST['c_officer'];
    $ccontact=addslashes($_POST['contact']);
    $department=addslashes($_POST['department']);
    if($cmode=='parsad'){
		$cmode=addslashes('Ikk”kZnx.k');
	}else{
	  $cmode=addslashes($_POST['cmode']);
    }
	$CorporatorId=addslashes($_POST['CorporatorId']);
    $WardId=addslashes($_POST['WardId']);
    $summary=addslashes($_POST['summary']);
    $oldcomplain=$_POST['oldcno'];
    $comtime=$_POST['ctime'];
    $ccategory=$_POST['category'];
    $fax=$_POST['fax'];
    $newspapername=$_POST['newspaper'];
    $newspaperdate=$_POST['news_date'];
    $television_name=$_POST['television'];
    $television_date=$_POST['tel_date'];
    $add_phone=$_POST['add_phone'];
    $tehsildivas=$_POST['tehsil'];
    $lokvani=$_POST['lokvani'];
    $C_Type=$_POST['C_Type'];
    //---------------------------- Get Nature -----------------------------------------------
    
      $nature_id=$_POST['cnature'];
      $sql="select nature_name from nature where nature_id='$nature_id'";
      $resn=$db1->query($sql);
      $rowsn=$db1->fetch_array($resn);
      $nature_name=$rowsn['nature_name'];
      $nature1=str_replace(" ","%20",$nature_name);
      
    
    
     $tdate2=$_POST['tdate'];
     $tdate=$_POST['tdate'];
     $tdate=explode('/',$tdate);
     $tdate1=mktime(0,0,0,$tdate[1],$tdate[0],$tdate[2]);
     $cid=$_POST['compno'];
     $cdate2=$_POST['cdate'];
     $cdate=$_POST['cdate'];
     $cdate=explode('/',$cdate);
     $cdate1=mktime(0,0,0,$cdate[1],$cdate[0],$cdate[2]);
    
    //$tdate=$_POST['tdate'];
    
    $select="select * from complainant where c_id='$cid'";
    $db->query($select);
		if($db->num_rows())
		{
      echo"<script type='text/javascript'>
        <!-- 
         window.location = 'add_complainant.php?msg=1'
        //-->
        </script>"; 
    }
    else
    {
    
     $insert="insert into complainant(c_id,c_name,c_add,c_city,c_contno,c_nature,con_officer,c_detail,department,c_date,c_newspapername,fax,c_televisionname,
                                     add_phone,c_tdate,c_category,oldc_no,c_time,c_ndate,c_mode,CorporatorId,WardId,tdate,tehsil,lokvani,ComplainType) 
             values('$cid','$cname','$caddress','$city','$ccontact','$nature_id','$c_officer','$summary','$department','$cdate1','$newspapername','$fax','$television_name',
                    '$add_phone','$television_date','$ccategory','$oldcomplain','$comtime','$ndate','$cmode','$CorporatorId','$WardId','$tdate1','$tehsildivas','$lokvani','$C_Type')";

    $db->query($insert);   
    $regno=$cid;
    
    $select2="select * from con_officer where c_id='$c_officer'";
    $res2=$db1->query($select2);
    $rows2=$db1->fetch_array($res2);
    $con_contact = $rows2['officer_contno'];
    $conoff_name = str_replace(" ","%20",$rows2['off_desi']);
    $conoff_name1 = $rows2['off_desi'];
    
    
     //------------------------------------------- mode ----------------------------------- 
    
    $message1="Comp%20No.%20$regno/Date%20of%20complaint%20$cdate2/Target%20Date%20$tdate2/to%20get%20more%20details%20pls%20pls%20visit%20www.jnnjhansi.com%20or%20call%2018001805140";
    $message2="Comp%20No.%20$regno/Date%20of%20complaint%20$cdate2/Target%20Date%20$tdate2/Mobile%20no%20of%20Complainant%20$add_phone/to%20get%20more%20details%20pls%20visit%20www.jnnjhansi.com%20or%20call%2018001805140";
    
   if($add_phone!='') {
    /*$xml_data ="<smslist><sms><user>JNNJHS</user><password>JNNJHS</password><message>$message1</message><mobiles>$add_phone</mobiles><senderid>JNNJHS</senderid><cdmasenderid>00201009546310</cdmasenderid><accountusagetypeid>1</accountusagetypeid></sms></smslist>";  
    $URL = "http://trans.cropsoft.co.in/reseller/sendsms.jsp?"; 
    $ch = curl_init($URL);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);*/
   //http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=CROPSOFT&password=D@ve23&mobiles=7710083173&sms=xxxxxx&senderid=JNNJHS
		//$url1="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=JNNJHS&password=JNNJHS&mobiles=$add_phone&sms=$message1&senderid=JNNJHS";
    //$file1=fopen("$url1","r");
    //fclose("$file1");
    /*$curl_handle1=curl_init();
    curl_setopt($curl_handle1,CURLOPT_URL,$url1);       
    curl_setopt($curl_handle1,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle1, CURLOPT_NOBODY, TRUE); // remove body 
    curl_setopt($curl_handle1, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_exec($curl_handle1);
    curl_close($curl_handle1);     */
  }
     //$url2="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=JNNJHS&password=JNNJHS&mobiles=$con_contact&sms=$message2&senderid=JNNJHS";
    //$file1=fopen("$url2","r");
    //fclose("$file2");
  // $url2="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=CROPSOFT&password=D@ve23&senderid=JNNJHS&mobiles=$con_contact&sms=$message2";
   //$curl_handle2=curl_init();
    //curl_setopt($curl_handle2,CURLOPT_URL,$url2);       
    //curl_setopt($curl_handle2,CURLOPT_CONNECTTIMEOUT,2);
    //curl_exec($curl_handle2);
    //curl_close($curl_handle2);
    


     echo"<script type='text/javascript'>
        <!-- 
         window.location = 'complainant.php'
        //-->
        </script>"; 
    }
 
 }

if(ISSET($_GET['msg']) && $_GET['msg']==1)
{
	$message='Complaint no already exists.';
}
GetNature($db);
GetComplaint($db);

$currentdate=date('d/m/Y');
$currenthours=date('h');
$currentmin=date('i');

if($_GET['msg']!="")
 {
   $error_message="Complain Already Added";
 }

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_complainant.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();
  
function GetNature($db)
 {	
	global $nature;
  
    $sql="select * from nature order by nature_name asc";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      while($res=$db->fetch_array($row))
       {
         $nid= $res['nature_id'];
         $n_name = $res['nature_name'];
         $nature.="<option value='$nid'>$n_name</option>";
       }
    }
 }
  
function GetComplaint($db)
 {
   global $complain_no,$comp_date;
   $sql="select c_id from complainant order by c_id desc limit 0,1";
   $res=$db->query($sql);
   $rows=$db->fetch_array($res);
   $comp_no=$rows['c_id'];
   
   $complain_no=$comp_no+1;
   $cdate2=mktime(0,0,0,date('m'),date('d'),date('Y'));
   $comp_date=date('d-m-Y',$cdate2);
 
 }
?>                                                                  