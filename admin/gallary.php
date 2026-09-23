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
$class4="leftab_off";
$class5="leftab_on";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/gallary.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$pid,$photo,$description,$DOCUMENT_ROOT;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/gallaryDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	 
	 $count="select count(g_id) as total from tbl_gallary";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from tbl_gallary limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $pid=$rows['g_id'];
			    if($_SERVER['SERVER_NAME']=='localhost')
				{
			        $photo="<img src='/jnnweb/pic/thumb/".$rows['photo_name']."' >";
				}
				else
				{
					$photo="<img src='/pic/thumb/".$rows['photo_name']."' >";
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
				$PREV_PAGE_LINK="<<a href='gallary.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='gallary.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='gallary.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='5'>No Image Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteaboutus($pid, $db)
 {	
	global $PROMPT,$DOCUMENT_ROOT;

	foreach($pid as $value)
	{
      $query="select * from tbl_gallary where g_id='$value'";
	  $db->query($query);
	  $rows = $db->fetch_array();
	  $photo=$rows['photo_name'];
	  
	  if($_SERVER['SERVER_NAME']=='localhost')
		{
		  @unlink($DOCUMENT_ROOT.'jnnweb/pic/thumb/'.$photo);
		  @unlink($DOCUMENT_ROOT.'jnnweb/pic/thumb/thumb_'.$photo);
	      @unlink($DOCUMENT_ROOT.'jnnweb/pic/'.$photo);
		}
		else
		{
			
			@unlink($DOCUMENT_ROOT.'/pic/thumb/'.$photo);
			@unlink($DOCUMENT_ROOT.'/pic/thumb/thumb_'.$photo);
	        @unlink($DOCUMENT_ROOT.'/pic/'.$photo);
		}
	 
	}
	
	$product = implode(",", $pid);

 	$delete = "delete from tbl_gallary where g_id in ($product)";
	$db->query($delete);

	$total = $db->affected_rows();

	$PROMPT = "Total $total Image have been deleted.";
 }

function ChangeStatus($pid, $db)
{
  
   global $PROMPT;

	$product = implode(",", $pid);
    $statusid=$_POST['status'];
 	$change = "update tbl_gallary set status='$statusid' where g_id in ($product)";
	$db->query($change);

	$total = $db->affected_rows();

	$PROMPT = "Total $total Image's status have been changed.";

}
?>
