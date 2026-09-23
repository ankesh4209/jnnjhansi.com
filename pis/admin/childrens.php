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

$class1="";
$class2="current";
$class3="";


if($_REQUEST["SUBMIT_DELETE"])
{	
 if (is_array($pid))
	{
	 
		deleteEmployees($pid, $db);
	}
}

if($_REQUEST["SUBMIT_CHANGE"])
{	
 if (is_array($pid))
	{
		ChangeStatus($pid, $db);
	}
}

viewcontents($db);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/childrens.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR","BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewcontents($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$status;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$pid,$emp_name,$ecode,$emp_code,$EmployeeCode,$ChildName,$ChildSex,$ChildDob;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/childrenDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	
	$sql='';  
	
	if($_GET['EmployeeCode']) {
		$sql.=" and EmployeeCode='".addslashes($_GET['EmployeeCode'])."'";
		$EmployeeCode=$_GET['EmployeeCode'];
	}
	 $count="select count(ChildId) as total from childrens where 1".$sql;
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
  $query="select * from childrens where 1".$sql." order by EmployeeCode ASC limit  $page, $MAX ";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $pid=$rows['ChildId'];
			  $ChildName=stripslashes($rows['ChildName']);
			  $ChildSex=stripslashes($rows['ChildSex']);
			  $ChildDob=stripslashes($rows['ChildDob']);
			  $EmployeeCode=stripslashes($rows['EmployeeCode']);
			  
			  $status=$rows['Status'];
			  if($status==1)
			    $status="Active";
			  else
				$status="Inactive";
			  
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<a href='childrens.php?page=$prevpage&max=$MAX' ><< Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='childrens.php?page=$lastrow&max=$MAX' >Next>></a>";
			}
							
			$PAGE_NAVS="";
			for($i=0,$toPrint=1;$i<	$TOTAL_RECORDSET;$i+=$MAX,$toPrint++)
			{	
				if ($lastrow-$i==$MAX)
				{	
					$PAGE_NAVS.="<span class='current'>".$toPrint."</span>";
					$CURRENT_PAGE_NO = $toPrint;
				}
				else
				{	
					$PAGE_NAVS.=" <a href='childrens.php?page=$i&max=$MAX' >$toPrint</a>";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
			$PRODUCT_LIST="<tr><td colspan='9'>No Child Found.</td></tr>";
        }
   
  return 1;
 }
 
function deleteEmployees($pid, $db)
 {	
	global $PROMPT,$DOCUMENT_ROOT;

	$product = implode(",", $pid);

 	$delete = "delete from childrens where ChildId in ($product)";
	$db->query($delete);

	$total = $db->affected_rows();

	$PROMPT = "Total $total children(s) have been deleted.";
 }



function ChangeStatus($pid, $db)
{
  
   global $PROMPT;

	$product = implode(",", $pid);
    $statusid=$_REQUEST['Status'];
 	$change = "update childrens set Status='$statusid' where ChildId in ($product)";
	$db->query($change);

	$total = $db->affected_rows();

	$PROMPT = "Total $total Child's status have been changed.";

}
?>