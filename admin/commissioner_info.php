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

$cid = $_REQUEST['cid'];

if($_POST["SUBMIT_DELETE"])
{	
 if (is_array($cid))
	{
	 
		deletecommissioner($cid, $db);
	}
}

if($_POST["SUBMIT_CHANGE"])
{	
 if (is_array($cid))
	{
		ChangeStatus($cid, $db);
	}
}

viewcommissioner($db);

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_on";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/commissioner_info.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewcommissioner($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$Comm_Id,$Comm_Desc,$Comm_Name,$c_image;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/commissioner_infoGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	 
	 $count="select count(Comm_Id) as total from municipal_comm";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from municipal_comm order by Comm_Id DESC limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $Comm_Id=$rows['Comm_Id'];
			  $description=$rows['Comm_Desc'];
			  $Comm_Desc=substr($description,0,30);
			  $Comm_Name=$rows['Comm_Name'];
			 	 
			  $status=$rows['Status'];
			  if($_SERVER['SERVER_NAME']=='localhost')
			  {
			     $c_image="<img src='/jnnweb/c_images/thumbs/".$rows['Comm_Photo']."' width='150' height='150'>";
			  }
			  else
			  {
				$c_image="<img src='/c_images/thumbs/".$rows['Comm_Photo']."' width='150' height='150'>";
			  }
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
				$PREV_PAGE_LINK="<<a href='commissioner_info.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='commissioner_info.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='commissioner_info.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='5'>No commissioner(s) found</td></tr>";
    }
   
  return 1;
 }
 
function deletecommissioner($cid, $db)
 {	
	global $PROMPT,$DOCUMENT_ROOT;

	foreach($cid as $value)
	{
      $query="select * from municipal_comm where Comm_Id='$value'";
	  $db->query($query);
	  $rows = $db->fetch_array();
	  $m_image=$rows['Comm_Photo'];
	  if($_SERVER['SERVER_NAME']=='localhost')
		{
		  @unlink($DOCUMENT_ROOT.'jnnweb/c_images/thumbs/'.$m_image);
	      @unlink($DOCUMENT_ROOT.'jnnweb/c_images/'.$m_image);
		}
		else
		{
			
			@unlink($DOCUMENT_ROOT.'/c_images/thumbs/'.$m_image);
	        @unlink($DOCUMENT_ROOT.'/c_images/'.$m_image);
		}
	 
	}

	$cids= implode(",", $cid);

 	$delete = "delete from municipal_comm where Comm_Id in ($cids)";
	$db->query($delete);

	$total = $db->affected_rows();

	$PROMPT = "Total $total commissioner's have been deleted.";
 }

function ChangeStatus($cid, $db)
{
  
   global $PROMPT;

	$cids = implode(",", $cid);
    $statusid=$_POST['status'];
 	$change = "update municipal_comm set Status='$statusid' where Comm_Id in ($cids)";
	$db->query($change);

	$total = $db->affected_rows();

	$PROMPT = "Total $total commissioner's status have been changed.";

}
?>
