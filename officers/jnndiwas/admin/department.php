
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

 $did = $_REQUEST['did'];

if($_POST["SUBMIT_DELETE"])
{	
 if (is_array($did))
	{
		deletedepartment($did, $db);
	}
}

viewdepartment($db);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/department.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();

function viewdepartment($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$title,$nid,$cdate,$TEMPLATE_DIR,$NEWS_LIST;
   global $did,$dname,$TOTAL_RECORDSET,$PAGE_NAVS,$status,$fid,$fname,$sfdesign,$sfpay,$sfadsalary,$faddress,$fcontact,$row_color;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/departmentGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	 $MAX=6;
	 $lastrow=$MAX+$page;
	  $color1="#6495ED";
	  $color2="#F0E68C";
	 
	 
	 $count="select count(d_id) as total from department";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from department limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $row_color = ($slno % 2) ? $color1 : $color2;
		   	$did=$rows['d_id'];
			  $dname=stripslashes($rows['d_name']);
			 
	
			  
			  ReplaceContent(Array("S1"));
	   	 $NEWS_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='department.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='department.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='department.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $NEWS_LIST="<tr><td colspan='5'>No Department Records Found</td></tr>";
    }
   
  return 1;
 }
 
function deletedepartment($did, $db)
 {	
	global $PROMPT;
 	$department = implode("," ,$did);
 	$delete = "delete from department where d_id in ($department)";
	$db->query($delete);
	$total = $db->affected_rows();
	$PROMPT = "Total $total Department records have been deleted.";
 }


?>
