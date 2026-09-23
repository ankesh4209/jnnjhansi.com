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

$catposts='';
 if($_GET['value']!='') {
	$cls='Js.kh&'.$_GET['value'];
    $sql="select * from posts where class='".$cls."'";
    $row=$db->query($sql);
     if($db->num_rows())
     {
      $catposts.="<span style='font-family: kruti_dev_010regular;font-size:20px;' >fu;qfDr  in</span><span style='padding-left:150px;'><select  name='JoinningPost'  style='font-family: kruti_dev_010regular;font-size:20px;'>";
      $catposts.="<option value=''>inuke pqus</option>";
	   while($res=$db->fetch_array($row))
       {
         $PostId = $res['PostId'];
         $Post = $res['Post'];
         $catposts.="<option value='$PostId' style='font-family: kruti_dev_010regular;font-size:20px;'>$Post</option>";
       }
       $catposts.="</select></span>";
     }

	
}else{
$catposts.="<select  name='JoinningPost'  style='font-family: kruti_dev_010regular;font-size:20px;'>
                    <option value=''>inuke pqus</option>";
      $catposts.="</select>";
	 
}
    
    echo $catposts;
	
   
?>
