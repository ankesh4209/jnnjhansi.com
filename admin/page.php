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

$pid = $_REQUEST['pid'];

if($_POST["SUBMIT_DELETE"])
{	
 if (is_array($pid))
	{
	 
		deleteaboutus($pid, $db);
	}
}

if($_POST["SUBMIT_CHANGE"])
{	
 if (is_array($pid))
	{
		ChangeStatus($pid, $db);
	}
}

viewpage($db);

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_on";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/page.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$pid,$pname,$description;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/pageDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	 
	 $count="select count(p_id) as total from page";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from page limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $pid=$rows['p_id'];
        $pname=stripslashes($rows['p_name']);
        if($pid=="3" || $pid=="4" || $pid=="5")
		{
		  $description="&nbsp;";
		}
		else
		{
		  $description=substr($rows['p_text'],0,30);
		}
			  $status=$rows['status'];
			  if($status==1)
				{ $status="Live";}
			  else
				{$status="Draft";}
			  
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='page.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='page.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
			}
							
			$PAGE_NAVS="";
			for($i=0,$toPrint=1;$i<	$TOTAL_RECORDSET;$i+=$MAX,$toPrint++)
			{	
       if ($lastrow-$i==$MAX)
				{	
          $PAGE_NAVS.=" <B>".$toPrint."</b> | ";
					$CURRENT_PAGE_NO = $toPrint;
				}
				else
				{	
          $PAGE_NAVS.=" <a href='page.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='5'>No Pages Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteaboutus($pid, $db)
 {	
	global $PROMPT;

	$product = implode(",", $pid);

 	$delete = "delete from page where p_id in ($product)";
	$db->query($delete);

	$total = $db->affected_rows();

	$PROMPT = "Total $total page have been deleted.";
 }

function ChangeStatus($pid, $db)
{
  
   global $PROMPT;

	$product = implode(",", $pid);
    $statusid=$_POST['status'];
 	$change = "update page set status='$statusid' where p_id in ($product)";
	$db->query($change);

	$total = $db->affected_rows();

	$PROMPT = "Total $total Page's status have been changed.";

}
?>
