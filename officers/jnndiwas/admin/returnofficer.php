
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

  $id=$_GET['id'];

  $sql="select * from con_officer where s_id='$id'";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      $c_officer.="<select  name='c_officer'>
                    <option>Select Concerning Officer</option>";
      while($res=$db->fetch_array($row))
       {
         $c_id = $res['c_id'];
         $officer = $res['off_desi'];
         $c_officer.="<option value='$c_id'>$officer</option>";
       }
      $c_officer.="</select>";
    }
    
    echo $c_officer;
?>
