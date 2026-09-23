<?php session_start(); ?>
<link href="../css/style1.css" rel="stylesheet" type="text/css" />
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


 if($_GET['ItemType']!='') {
    $sql="select * from epr_items where ItemType='".$_GET['ItemType']."' order by ItemId";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      $c_items.="<select  name='ItemId'  >";
      $c_items.="<option value=''>Choose</option>";
	  while($res=$db->fetch_array($row))
       {
         $item_id = $res['ItemId'];
         $ItemName = $res['ItemName'];
         $c_items.="<option value='$item_id' >$ItemName</option>";
       }
      $c_items.="</select>";
    }

	
}else{
$c_items.="<select  name='ItemId'>
                    <option value='' >lkexzh pqu</option>";
      $c_corporator.="</select>";
	 
}
    
    echo $c_items;
	
   
?>
