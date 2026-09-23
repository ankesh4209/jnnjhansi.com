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

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());


 if($_GET['ItemType']!='') {
    $sql="SELECT i.ItemId as ItemId,i.ItemName as ItemName,sum(s.Qty) as Qty FROM epr_items as i INNER JOIN epr_stocks as s ON i.ItemId=s.ItemId where i.ItemType='".$_GET['ItemType']."' group by s.ItemId";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      $c_items.="<select  name='ItemId'  >";
      $c_items.="<option value=''>Choose Item</option>";
	  /*while($res=$db->fetch_array($row))
       {
         $item_id = $res['ItemId'];
         $ItemName = $res['ItemName'];
         
		 $c_items.="<option value='$item_id' style='font-family: kruti_dev_010regular;font-size:15px;'>$ItemName</option>";
       }*/
	   while($res=$db->fetch_array($row))
       {
         $item_id = $res['ItemId'];
         $ItemName = $res['ItemName'];
         $Qty = $res['Qty'];

		 $sql1="SELECT sum(DeliverQty) as DeliverQty FROM epr_distributions Where ItemId='".$item_id."'";
		 $row1=$db1->query($sql1);
		 $res1=$db1->fetch_array($row1);
		 $RemainQty=$Qty-$res1['DeliverQty'];
         	$c_items.="<option value='$item_id' >$ItemName $RemainQty</option>";
		 
       }
      $c_items.="</select>";
    }

	
}else{
$c_items.="<select  name='ItemId'>
                    <option value='' >Choose</option>";
      $c_corporator.="</select>";
	 
}
    
    echo $c_items;
	
   
?>
