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
     $WardList2.="<a href='tenders.php?wardno=$k'>".$k."</a>&nbsp;";
   }else{
     $WardList1.="<a href='tenders.php?wardno=$k'>".$k."</a>&nbsp;";
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
	$PROMPT='Tender has been added successfullly.';
}
if(isset($_GET['msg']) && $_GET['msg']=='e_succ')
{
	$PROMPT='Tender has been edited successfullly.';
}
viewpage($db,$db1);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/tenders.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$cid;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$TotalCnt,$MinRate,$TotalAmount,$WorkName,$WardNo,$WorkOrderNo,$AddedDate,$amount,$ContractorName,$ContractorFirm;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/tendersDisplayGrid.html");

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
	    $sql.= " AND cw_e.WardNo=$w_no";
	 }
	 
	$count="select count(TenderId) as total from cw_tenders cw_t inner join cw_estimation cw_e on cw_t.EstmtId=cw_e.EstmtId where $sql";
	 
    $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
    $query="select * from cw_tenders cw_t inner join cw_estimation cw_e on cw_t.EstmtId=cw_e.EstmtId where $sql order by cw_t.TenderId DESC,cw_e.WardNo limit  $page, $MAX ";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  
              $sql="SELECT * FROM cw_estimation where EstmtId='".$rows['EstmtId']."'";
              $res1=$db1->query($sql);
			  $row1 = $db1->fetch_array();
//echo '<pre>';
//print_r($row1);

			  $sql4="SELECT * FROM contractors Where ContractorId='".$rows['ContractorId']."'";
              $res4=$db1->query($sql4);
			  $row4 = $db1->fetch_array();
	
			  $cid=$rows['TenderId'];
			  $WardNo=$row1['WardNo'];
              $WorkName=str_replace('\\','',stripslashes($row1['WorkName']));
              $MinRate=$rows['rate'];
              $ContractorName=str_replace('\\','',stripslashes($row4['ContractorName']));
              $AddedDate=$rows['AddedDate'];
              $AddedDate = explode('-',$AddedDate);
			  $AddedDate=$AddedDate[2]."/".$AddedDate[1]."/".$AddedDate[0]; 
			  $amount=$rows['amount'];
			  $Status=$rows['Status'];
			  
        
        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='tenders.php?page=$prevpage&max=$MAX&$next_links&wardno=".$_GET['wardno']."' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='tenders.php?page=$lastrow&max=$MAX&$next_links&wardno=".$_GET['wardno']."' >Next></a>";
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
          $PAGE_NAVS.=" <a href='tenders.php?page=$i&max=$MAX&left_id=1&$next_links&wardno=".$_GET['wardno']."' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='10'>No Records Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteaboutus($cid, $db)
 {	
	global $PROMPT;

   	$product = implode(",", $cid);
    $sel_reg_id="delete from cw_tenders where AmtId in ($product)"; 
    $db->query($sel_reg_id);
	$total = $db->affected_rows();
  	$PROMPT = "Total $total Records have been deleted.";
 }


?>

