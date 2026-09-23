
<?php @session_start(); ?>
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
include("../phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());


 if($_GET['Corporator']!='') {

	$sql="select * from ward order by WardNo";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      $c_ward.="<select  name='WardId'>
                    <option value=''>Select Ward</option>";
      while($res=$db->fetch_array($row))
       {
         $WardNo = $res['WardNo'];
         $c_ward.="<option value='$WardNo'>$WardNo</option>";
       }
      $c_ward.="</select>";
	  }
}else{
$c_ward.="<select  name='WardId'>
                    <option value=''>Select Ward</option>";
      $c_ward.="</select>";
	  }
    
    echo $c_ward;
?>
