<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
  {	
    header ("Location: ../login.php"); 				
	  exit;
  }
?>

<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/thumbclass.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

//echo '<pre>';
//print_r($_POST);






$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/bidded_tender_details.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


function GetItemsAmont($db,$arrRateIds)
{
	global $RateList,$SubTotal,$TotalAmount;
	
	$i=1;
	$TotalAmount=0;
	$arrRateList=array(); 
	foreach($arrRateIds as $Rids)
	{
	  
	  $query="select * from cw_ratelist WHERE RateId='".$Rids."'";
      $db->query($query);
	  $rows = $db->fetch_array();
	  $qty_index="qty-".$Rids;
	  $SubTotal=$rows['Rate']*$_POST[$qty_index]; 
	  $Mask=$rows['Rate']."&nbsp;X&nbsp;". $_POST[$qty_index];
	  $TotalAmount=$TotalAmount+$SubTotal;

	  $RateList.="<tr><td>".$i."</td><td>".$rows['RateId']."</td><td>".$rows['ItemName']."</td><td>".$rows['Unit']."</td><td align='right'>".$rows['Rate']."</td><td align='right'>".$Mask."</td><td align='right'>".number_format($SubTotal,2)."</td></tr>";
	  $i++;

	}
	$RateList.="<tr><td colspan='7' align='right'><b>Total Estimated Amount&nbsp;:&nbsp;&nbsp;".number_format($TotalAmount,2)."</b></td></tr>";


}

?>
