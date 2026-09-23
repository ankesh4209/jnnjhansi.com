<?php session_start(); ?>
<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

if($_GET['delete']=="other")
 {
   $rid=$_GET['aid'];
   $delete="delete from tbl_otherobj where pro_id='$rid'";
   $db->query($delete);
 }

if($_POST['objection_add']!='')
{
  $pid=$_REQUEST['aid'];
  $mast_objection=$_POST['masterobjection'];
  $objections=implode(",",$mast_objection);
  $obj_date=mktime(0,0,0,date('m'),date('d'),date('Y'));
  
  if($_POST['oth_objection']!="")
   {
     $otherobjection=$_POST['oth_objection'];
     $insert1="insert into tbl_otherobj (pro_id,oobj_name) values('$pid','$otherobjection')";
     $db->query($insert1);
   }
  
   $sql_1="select * from tbl_objection where pro_id='$pid'";
   $res_1=$db->query($sql_1);
   if($db->num_rows())
   {
     $update="update tbl_objection set obj_name='$objections',obj_date='$obj_date' where pro_id='$pid'";
     $db->query($update);  
   }
   else
   {
    $insert="insert into tbl_objection (pro_id,obj_name,obj_date) values('$pid','$objections','$obj_date')";
    $db->query($insert);
   }
   
  $sql="select a.a_rno,a.a_contactno,a.a_name,b.z_jecontact,b.z_jename from tbl_automation as a, tbl_zone as b where a.a_id='$pid' and a.a_zone=b.z_id";
  $db1->query($sql);
  $rows = $db1->fetch_array();
  $je_name=$rows['z_jename'];
  $je_name=str_replace(" ","%20",$je_name);
  $je_contact=$rows['z_jecontact'];
  $user_contact=$rows['a_contactno'];
  $uname=$rows['a_name'];
  $uname=str_replace(" ","%20",$uname);
  $mapno=$rows['a_rno'];
  $objdate=date('d-m-Y');
  
   $sql_2="select * from tbl_objection where pro_id='$pid'";
   $res_2=$db2->query($sql_2);
   $rows_2 = $db2->fetch_array();
   if($rows_2['obj_name']!="")
    {
      $message="Objection%20No.%20$objections%20are%20raised%20on%20your%20Map%20No.%20$mapno%20on%20$objdate/Contact%20JDA%20JE%20$je_name-$je_contact/You%20must%20remove%20the%20Objections%20within%2015%20Days";
      $message1="Objection%20$objections%20assigned%20by%20you%20on%20Map%20No.%20$mapno/Date%20of%20Objection%20$objdate/Conatct%20Applicant%20$uname-$user_contact";
      
      $url1="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jdajhansi@gmail.com:jhansi0510&senderID=JDA-JHS&receipientno=$user_contact&msgtxt=$message";
      $file1=fopen("$url1","r");
      fclose("$file1");
      
      $url2="http://api.mVaayoo.com/mvaayooapi/MessageCompose?user=jdajhansi@gmail.com:jhansi0510&senderID=JDA-JHS&receipientno=$je_contact&msgtxt=$message1";
      $file2=fopen("$url2","r");
      fclose("$file2");
    }
    
  $update="update tbl_automation set app_status='2',mod_date='$obj_date' where a_id='$pid'";
  $db->query($update);
}


$rno=$_REQUEST['rno'];
Registerationdetails($db,$db1,$db2,$rno);
if($rno!="")
 {
  $PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/objection.html");
 }
else
 {
  $PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/objectionnew.html");
 }

$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();



function  Registerationdetails($db,$db1,$db2,$rno)
{
   global $aid,$rno,$rfno,$pno,$block,$area,$apbuilding,$scheme,$pbusage,$cno,$aname,$fname,$address,
          $a_district,$s_date,$description,$obj_grid,$master_objection,$other_objection,$zone;
  
    $sql="select a.*,b.a_name as name,c.z_name,d.pb_name,d.pb_days from tbl_automation as a,tbl_area as b,tbl_zone as c,
          tbl_pbusage as d where a.a_rno='$rno' and a.a_zone=c.z_id and a.a_area=b.a_id and a.a_pbusage=d.pb_id";
    $db->query($sql);
    $rows = $db->fetch_array();
    $aid=$rows['a_id'];   
        
    $rno=stripslashes($rows['a_rno']);
    $rfno=stripslashes($rows['a_rfno']);
    $r_date=$rows['a_rdate'];
    $pno=stripslashes($rows['a_pno']);
    $block=stripslashes($rows['a_block']);
    $area=stripslashes($rows['name']);
    $apbuilding=stripslashes($rows['a_apbuilding']);
    $zone=stripslashes($rows['z_name']);
    $pbusage=stripslashes($rows['pb_name']);
    $pbusage_days=$rows['pb_days'];
    $cno=stripslashes($rows['a_contactno']);
    $aname=ucwords(stripslashes($rows['a_name']));
    $fname=ucwords(stripslashes($rows['a_fname']));
    $address=stripslashes($rows['a_address']);
    $a_district=stripslashes($rows['a_district']);
    $description=stripslashes($rows['a_decription']);  
    
    $s_date=$r_date+($pbusage_days*24*60*60);
    $s_date=date("d/m/Y", $s_date);
    
    //---------------Get Objection array ------------------------------
    
    $sql3="select obj_name from tbl_objection where pro_id='$aid'";
    $res3=$db2->query($sql3);
    if($db2->num_rows())
    {
     $rows3=$db2->fetch_array($res3);
     $select_obj_array=$rows3['obj_name'];
     $select_obj_array=explode(",",$select_obj_array);
    }
    else
    {
      $select_obj_array=array();
    }
    
    $sql1="select * from tbl_masterobj"; 
    $res1=$db1->query($sql1);
    $i=1;
    if($db1->num_rows())
    {
      while($rows1=$db1->fetch_array($res1))
       {
         $objno=$rows1['mobj_id'];
         if(in_array($objno,$select_obj_array))
          $master_objection.=$objno."<input type='checkbox' name='masterobjection[]' value='$objno' checked>"."&nbsp;&nbsp;&nbsp;";
         else
          $master_objection.=$objno."<input type='checkbox' name='masterobjection[]' value='$objno'>"."&nbsp;&nbsp;&nbsp;";
       }
    }
    
    //------------------- Get the other objecton ---------------------------------------
    $sql2="select oobj_name from tbl_otherobj where pro_id='$aid'";
    $res2=$db2->query($sql2);
    if($db2->num_rows())
    {
     $rows2=$db2->fetch_array($res2);
     $oobj_name=$rows2['oobj_name'];
     $other_objection="<tr>
                       <td colspan='2'><b>Other Objection :</b>&nbsp;&nbsp;&nbsp; <span style='font-family:Kruti Dev 011;font-size:15px;'>$oobj_name</span><br><a href='objection.php?rno=$rno&aid=$aid&delete=other'>Delete</a></td>
                      </tr>";
    }
}

?>
