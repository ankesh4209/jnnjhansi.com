<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
  {	
    header ("Location: index.php"); 				
	  exit;
  }
?>

<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$strmadinfo='';
 if($_GET['value']!='') {
	$mid=$_GET['value'];
    $sql="select * from cw_madamount where MadId='".$mid."'";
    $row=$db->query($sql);
     if($db->num_rows())
     {
      $strmadinfo.="<select  name='MadAmtId'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      $strmadinfo.="<option value=''>en dk fooj.k pqus</option>";
	   while($res=$db->fetch_array($row))
       {
         $MadInfo = stripslashes($res['MadInfo']);
         $AmtId = $res['AmtId'];
         $strmadinfo.="<option value='$AmtId' style='font-family: kruti_dev_010regular;font-size:15px;'>$MadInfo</option>";
       }
       $strmadinfo.="</select>";
     }else{
		 $strmadinfo.="<select  name='MadAmtId'  style='font-family: kruti_dev_010regular;font-size:15px;'>
                    <option value=''>en dk fooj.k pqus</option>";
      $strmadinfo.="</select>";

	 }
	
	
}else{
$strmadinfo.="<select  name='MadAmtId'  style='font-family: kruti_dev_010regular;font-size:15px;'>
                    <option value=''>en dk fooj.k pqus</option>";
      $strmadinfo.="</select>";
	 
}
    
    echo $strmadinfo;
	
   
?>
