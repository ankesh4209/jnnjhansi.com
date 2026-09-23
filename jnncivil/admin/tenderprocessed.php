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

for($k=1;$k<=60;$k++){
   
   if($k>32){
     $WardList2.="<a href='tenderprocessed.php?wardno=$k'>".$k."</a>&nbsp;";
   }else{
     $WardList1.="<a href='tenderprocessed.php?wardno=$k'>".$k."</a>&nbsp;";
   }
}


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
	$PROMPT='Tender has been sanctioned.';
}
if(isset($_GET['msg']) && $_GET['msg']=='e_succ')
{
	$PROMPT='Estimate has been edited successfullly.';
}
viewpage($db,$db1);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/tenderprocessed.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$cid,$status;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$TotalCnt,$MinRate,$TotalAmount,$WorkName,$WardNo,$ContractorName,$AddedDate,$EstimatedAmount;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/tenderprocessedDisplayGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	$MAX=10;
	 $lastrow=$MAX+$page;

	 $sql='1'; 
	 if($_GET['wardno']!=''){
		 $w_no=$_GET['wardno'];
	    $sql.= " AND WardNo=$w_no";
	 }
	 
	 $count="select * from cw_tenders cw_t inner join cw_estimation cw_e on cw_t.EstmtId=cw_e.EstmtId where $sql group by cw_e.EstmtId";
	 
     $re=$db->query($count);
	 $TOTAL_RECORDSET= $db->num_rows($re);
	 //$row = $db->fetch_assoc();
   //$TOTAL_RECORDSET = $rowtotal'total'];
  
    $query="select * from cw_tenders cw_t inner join cw_estimation cw_e on cw_t.EstmtId=cw_e.EstmtId where $sql group by cw_e.EstmtId order by cw_t.TenderId DESC,cw_e.WardNo ASC limit  $page, $MAX ";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  
              $sql="SELECT * FROM cw_tenders where EstmtId='".$rows['EstmtId']."' order by rate ASC";
              $res1=$db1->query($sql);
			  $row1 = $db1->fetch_array();
//echo '<pre>';
//print_r($row1);
			  $sql2="SELECT count(*) as TotalCnt FROM cw_tenders Where EstmtId='".$row1['EstmtId']."'";
              $res2=$db1->query($sql2);
			  $row2 = $db1->fetch_array();

			  $sql3="SELECT * FROM cw_estimation Where EstmtId='".$rows['EstmtId']."'";
              $res3=$db1->query($sql3);
			  $row3 = $db1->fetch_array();

			  $sql4="SELECT * FROM contractors Where ContractorId='".$row1['ContractorId']."'";
              $res4=$db1->query($sql4);
			  $row4 = $db1->fetch_array();
			
			  $cid=$row1['TenderId'];
			  $WardNo=$row3['WardNo'];
              $WorkName=str_replace('\\','',stripslashes($row3['WorkName']));
              $EstimatedAmount=$row3['TotalAmount'];
              $ContractorName=str_replace('\\','',stripslashes($row4['ContractorName']));
              $MinRate=$row1['rate'];
              $TotalCnt=$row2['TotalCnt'];
              $AddedDate=$row1['appdate'];
              $AddedDate = explode('-',$AddedDate);
			  $AddedDate=$AddedDate[2]."/".$AddedDate[1]."/".$AddedDate[0]; 
			  $TotalAmount=$row1['amount'];
              $Status=$row1['Status'];
			  if($Status=='Pending'){
			     $status='<a href="approve.php?cid='.$cid.'" >Approve</a>';
			  }else{
				$status='Approved';
			  }
        
        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='tenderprocessed.php?page=$prevpage&max=$MAX&$next_links&wardno=".$_GET['wardno']."' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='tenderprocessed.php?page=$lastrow&max=$MAX&$next_links&wardno=".$_GET['wardno']."' >Next></a>";
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
          $PAGE_NAVS.=" <a href='tenderprocessed.php?page=$i&max=$MAX&left_id=1&$next_links&wardno=".$_GET['wardno']."' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='9'>No Records Found</td></tr>";
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

