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

$aid = $_REQUEST['aid'];

if($_POST["SUBMIT_DELETE"])
{	
 if (is_array($aid))
	{
	 
		deleteaboutus($aid, $db);
	}
}

if($_POST["SUBMIT_CHANGE"])
{	
 if (is_array($aid))
	{
		ChangeStatus($aid, $db);
	}
}

if($_POST['submit']!="")
 $rid=$_POST['r_no'];
else
 $rid="";

viewpage($db,$rid);
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/automation.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$rid)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$aid,$rno,$rfno,$cno,$per_msg,$reg_status,$address,$name,$description;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/automationDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	 if($rid!="")
	  $count="select count(a_id) as total from tbl_automation where a_rno='$rid' and app_status='1'";
	 else
	  $count="select count(a_id) as total from tbl_automation where app_status='1'";
	 
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   if($rid!="")
   $query="select * from tbl_automation where a_rno='$rid' and app_status='1' order by a_id desc limit  $page, $MAX ";
   else
   $query="select * from tbl_automation where app_status='1' order by a_id desc limit  $page, $MAX ";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $status="";
			  $per_msg="";
			  $aid=$rows['a_id'];
        $rno=stripslashes($rows['a_rno']);
        $name=$rows['a_name'];
        $address=$rows['a_address'];
        $cno=$rows['a_contactno'];
        $status=$rows['app_status'];
        
        if($status==1)
         $reg_status="Pending";
        elseif($status==2)
         $reg_status="Complete";
          	  
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='automation.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='automation.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='automation.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='6'>No Records Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteaboutus($aid, $db)
 {	
	global $PROMPT;

	$product = implode(",", $aid);

 	$delete = "delete from tbl_automation where a_id in ($product)";
	$db->query($delete);

	$total = $db->affected_rows();

	$PROMPT = "Total $total Records have been deleted.";
 }


?>

