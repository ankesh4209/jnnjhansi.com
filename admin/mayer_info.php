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

$mid = $_REQUEST['mid'];

if($_POST["SUBMIT_DELETE"])
{	
 if (is_array($mid))
	{
	 
		deletemayers($mid, $db);
	}
}

if($_POST["SUBMIT_CHANGE"])
{	
 if (is_array($mid))
	{
		ChangeStatus($mid, $db);
	}
}

viewpage($db);

$class1="leftab_off";
$class2="leftab_on";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/mayer_info.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$Mayer_Id,$Mayer_Desc,$Mayer_Name,$m_image;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/mayer_infoGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	 
	 $count="select count(Mayer_Id) as total from mayers";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from mayers order by Mayer_Id DESC limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $Mayer_Id=$rows['Mayer_Id'];
			  $description=$rows['Mayer_Desc'];
			  $Mayer_Desc=substr($description,0,30);
			  $Mayer_Name=$rows['Mayer_Name'];
			 	 
			  $status=$rows['Status'];
			  if($_SERVER['SERVER_NAME']=='localhost')
			  {
				  $m_image="<img src='/jnnweb/m_images/thumbs/".$rows['Mayer_Photo']."' width='150' height='150'>";
			  }
			  else
			  {
					$m_image="<img src='/m_images/thumbs/".$rows['Mayer_Photo']."' width='150' height='150'>";
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
				$PREV_PAGE_LINK="<<a href='mayer_info.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='mayer_info.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='mayer_info.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='5'>No mayer(s) found</td></tr>";
    }
   
  return 1;
 }
 
function deletemayers($mid, $db)
 {	
	global $PROMPT,$DOCUMENT_ROOT;

	foreach($mid as $value)
	{
      $query="select * from mayers where Mayer_Id='$value'";
	  $db->query($query);
	  $rows = $db->fetch_array();
	  $m_image=$rows['Mayer_Photo'];
	  if($_SERVER['SERVER_NAME']=='localhost')
		{
		  @unlink($DOCUMENT_ROOT.'jnnweb/m_images/thumb/'.$m_image);
	      @unlink($DOCUMENT_ROOT.'jnnweb/m_images/'.$m_image);
		}
		else
		{
			
			@unlink($DOCUMENT_ROOT.'/m_images/thumb/'.$m_image);
	        @unlink($DOCUMENT_ROOT.'/m_images/'.$m_image);
		}
	 
	}

	$mids= implode(",", $mid);

 	$delete = "delete from mayers where Mayer_Id in ($mids)";
	$db->query($delete);

	$total = $db->affected_rows();

	$PROMPT = "Total $total mayers's have been deleted.";
 }

function ChangeStatus($mid, $db)
{
  
   global $PROMPT;

	$mids = implode(",", $mid);
    $statusid=$_POST['status'];
 	$change = "update mayers set Status='$statusid' where Mayer_Id in ($mids)";
	$db->query($change);

	$total = $db->affected_rows();

	$PROMPT = "Total $total mayer's status have been changed.";

}
?>
