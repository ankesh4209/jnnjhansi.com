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

$oid = $_REQUEST['oid'];

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_on";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

if($_POST["SUBMIT_DELETE"])
{	
 if (is_array($oid))
	{
	 
		deleteOfficers($oid, $db);
	}
}

if($_POST["SUBMIT_CHANGE"])
{	
 if (is_array($oid))
	{
		ChangeStatus($oid, $db);
	}
}

ViewOfficers($db);
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/officers_info.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function ViewOfficers($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$OfficerName,$OffNo,$RedNo,$Email,$Officer_Id,$Designation,$pages;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/officers_infoGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	 
	 $count="select count(Officer_Id) as total from officers";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from officers limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $Officer_Id=$rows['Officer_Id'];
			  
			  $OfficerName=$rows['FirstName']." ".$rows['LastName'];
			  $Email=$rows['Email'];
			  $Status=$rows['Status'];
			  $OffNo=$rows['OfficeNo'];
			  $RedNo=$rows['ResidenceNo'];
			  $Designation=$rows['Designation'];
			  
			  if($Status==1)
			  {  
				  $Status="Active";
			  }
			  else
			  {  
				  $Status="Inactive";
			  }
			  
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='officers_info.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='officers_info.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='officers_info.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		$pages="Pages:";
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='5'>No Officer(s) Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteOfficers($oid, $db)
 {	
	global $PROMPT,$DOCUMENT_ROOT;

	$oids= implode(",", $oid);

 	$delete = "delete from officers where Officer_Id in ($oids)";
	$db->query($delete);

	$total = $db->affected_rows();

	$PROMPT = "Total $total Officer's have been deleted.";
 }

function ChangeStatus($oid, $db)
{
  
   global $PROMPT;

	$oids = implode(",", $oid);
    $statusid=$_POST['Status'];
 	$change = "update officers set Status='$statusid' where Officer_Id in ($oids)";
	$db->query($change);

	$total = $db->affected_rows();

	$PROMPT = "Total $total Officer's status have been changed.";

}
?>
