<?php

include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$id=$_GET['id'];
viewSmartCity($db,$db1,$id);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/smartcity_print.html");

ReplaceContent(Array( "PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();

function viewSmartCity($db,$db1,$id)
 {
   global $db,$db1,$name,$address,$mobile,$comment1,$comment2,$comment3,$comment4,$comment5,$comment6,$comment7,$comment8,$comment9,$comment10,$comment11,$comment12,$comment13,$comment14;
                                                           
   $query="select * from smartcity_comment where user_id=$id"; 
   $db->query($query);
		if($db->num_rows())
		{
			while($rows = $db->fetch_array())
			{
			   $comment1=$rows['comment1'];
         $comment2=$rows['comment2'];
         $comment3=$rows['comment3'];
         $comment4=$rows['comment4'];
         $comment5=$rows['comment5'];
         $comment6=$rows['comment6'];
         $comment7=$rows['comment7'];
         $comment8=$rows['comment8'];
         $comment9=$rows['comment9'];
         $comment10=$rows['comment10'];
         $comment11=$rows['comment11'];
         $comment12=$rows['comment12'];
         $comment13=$rows['comment13'];
         $comment14=$rows['comment14'];
		  }
   }
   
   $query1="select * from smartcity_reg where id=$id"; 
   $db1->query($query1);
		if($db1->num_rows())
		{
			while($rows1 = $db1->fetch_array())
			{
			  $id=$rows1['id'];
			  $name=$rows1['name'];
			  $address=$rows1['address'];
			  $mobile=$rows1['mobile'];
		  }
   }
} 


?>
