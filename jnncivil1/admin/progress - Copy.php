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
include("../phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$cid = $_REQUEST['cid'];

if($_POST["SUBMIT_DELETE"])
{	
 if (is_array($cid))
	{
	 
		deleteaboutus($cid, $db);
	}
}

if($_POST["SUBMIT_CHANGE"])
{	
 if (is_array($cid))
	{
		ChangeStatus($cid, $db);
	}
}


if(isset($_GET['msg']) && $_GET['msg']=='succ')
{
	$PROMPT='Estimate has been added successfullly.';
}
if(isset($_GET['msg']) && $_GET['msg']=='e_succ')
{
	$PROMPT='Estimate has been edited successfullly.';
}
viewpage($db,$db1);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/progress.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$cid;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$wardno,$workname,$WorkOrderNo,$estimateamt,$workername,$appamt,$appdate,$workstartdate,$workenddate,$Payments,$CheckNos,$Comment;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/progressDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;
	 
	  $count="select count(ProgId) as total from cw_progress";
	 
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from cw_progress order by ProgId DESC limit  $page, $MAX ";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $sql="SELECT * FROM cw_payments where WorkOrderNo='".$rows['WorkOrderNo']."'";
              $res1=$db1->query($sql);
			  
			  
			  while($row1 = $db1->fetch_array())
			  {
				  $PayDate=$row1['PayDate'];
				  $PayDate = explode('-',$PayDate);
			      $PayDate=$PayDate[2]."/".$PayDate[1]."/".$PayDate[0];

				  $CheckDate=$row1['CheckDate'];
				  $CheckDate = explode('-',$CheckDate);
			      $CheckDate=$CheckDate[2]."/".$CheckDate[1]."/".$CheckDate[0];

				  $Payments.="<div>".$row1['Installment'].'<br>'.$PayDate."</div><br>";
				  $CheckNos.="<div>No. ".$row1['CheckNo'].'<br>'.$CheckDate."</div><br>";
				  $Comment.="<div>".$row1['Comments']."<br>";
			  }
			  
              $wardno=$rows['wardno'];
              $workname=$rows['workname'];
              $WorkOrderNo=$rows['WorkOrderNo'];
              $estimateamt=$rows['estimateamt'];
              $workername=$rows['workername'];
              $appdate=$rows['appdate'];
              $Status=$rows['Status'];
              $appdate = explode('-',$appdate);
			  $appdate=$appdate[2]."/".$appdate[1]."/".$appdate[0];
			  
			  $workstartdate=$rows['workstartdate'];
              $workstartdate = explode('-',$workstartdate);
			  $workstartdate=$workstartdate[2]."/".$workstartdate[1]."/".$workstartdate[0];

			  $workenddate=$rows['workenddate'];
              $workenddate = explode('-',$workenddate);
			  $workenddate=$workenddate[2]."/".$workenddate[1]."/".$workenddate[0];

			  $appamt=$rows['appamt'];
			  $Status=$rows['Status'];
			  
        
        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='progress.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='progress.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='progress.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='13'>No Records Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteaboutus($cid, $db)
 {	
	global $PROMPT;

   	$product = implode(",", $cid);
    $sel_reg_id="delete from cw_madamount where AmtId in ($product)"; 
    $db->query($sel_reg_id);
	$total = $db->affected_rows();
  	$PROMPT = "Total $total Records have been deleted.";
 }

function GetMad($db1,$MadId)
 {

	$sql="select * from cw_mads where MadId='$MadId'";
    $row=$db1->query($sql);
      $res=$db1->fetch_array($row);
      $MadName = $res['MadName'];
      return $MadName;  
    
 }
?>

