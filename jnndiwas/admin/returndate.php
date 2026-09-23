
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

  
   $ccategory=$_GET['id'];
   
  if($ccategory=='A')
     {
       $tdate1=mktime(0,0,0,date('m'),date('d')+1,date('Y'));
       $tdate=date('d-m-Y',$tdate1);
     }
    elseif($ccategory=='B')
     {
       $tdate1=mktime(0,0,0,date('m'),date('d')+7,date('Y'));
       $tdate=date('d-m-Y',$tdate1);
     }
    elseif($ccategory=='C')
     {
       $tdate1=mktime(0,0,0,date('m'),date('d')+21,date('Y'));
       $tdate=date('d-m-Y',$tdate1);
     }
    else
     {
       $tdate1="";
       $tdate="";
     }
    
    echo $t_date="Target Date <br>(<span style=\"font-family:Kruti Dev 011;font-size:13px;\">visf{kr fuLrkj.k frfFk</span>):&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tdate";
?>
