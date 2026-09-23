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
     $WardList2.="<a href='payments.php?wardno=$k'>".$k."</a>&nbsp;";
   }else{
     $WardList1.="<a href='payments.php?wardno=$k'>".$k."</a>&nbsp;";
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
	$PROMPT='Payment has been added successfullly.';
}
if(isset($_GET['msg']) && $_GET['msg']=='e_succ')
{
	$PROMPT='Payment has been edited successfullly.';
}
viewpage($db,$db1,$rid);

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/payments.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db,$db1,$rid)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$cid;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$PayId,$WorkOrderNo,$Installment,$PayDate,$Amount,$CheckNo,$CheckDate,$WorkName,$WardNo,$ContractorName,$WorkStartDate,$WorkEndDate,$appdate;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/paymentDisplayGrid.html");

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
	    $sql.= " AND estm.WardNo=$w_no";
	 }
	 
	 
	  $count="select count(PayId) as total from cw_payments as pay inner join cw_progress as prog ON pay.WorkOrderNo=prog.WorkOrderNo inner join cw_estimation as estm ON estm.EstmtId=prog.EstmtId where $sql";
	 
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select pay.*,prog.TenderId,prog.EstmtId,prog.WorkStartDate,prog.WorkEndDate,estm.WorkName,estm.WardNo from cw_payments as pay inner join cw_progress as prog ON pay.WorkOrderNo=prog.WorkOrderNo inner join cw_estimation as estm ON estm.EstmtId=prog.EstmtId where $sql order by pay.PayId DESC,estm.WardNo ASC limit  $page, $MAX ";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  
              $WorkOrderNo=$rows['WorkOrderNo'];
              $Installment=$rows['Installment'];
              $Amount=$rows['Amount'];
			  $PayDate=$rows['PayDate'];
			  $PayDate = explode('-',$PayDate);
	          $PayDate=$PayDate[2]."/".$PayDate[1]."/".$PayDate[0]; 

			  $CheckNo=$rows['CheckNo'];
			  $CheckDate=$rows['CheckDate'];
			  $CheckDate = explode('-',$CheckDate);
	          $CheckDate=$CheckDate[2]."/".$CheckDate[1]."/".$CheckDate[0]; 

			  if($CheckDate=='00/00/0000'){
			     $CheckDate=''; 
			  }

			  if($PayDate=='00/00/0000'){
			     $PayDate=''; 
			  }

			  $WorkStartDate=$rows['WorkStartDate'];
              $WorkStartDate = explode('-',$WorkStartDate);
			  $WorkStartDate=$WorkStartDate[2]."/".$WorkStartDate[1]."/".$WorkStartDate[0];

			  $WorkEndDate=$rows['WorkEndDate'];
              $WorkEndDate = explode('-',$WorkEndDate);
			  $WorkEndDate=$WorkEndDate[2]."/".$WorkEndDate[1]."/".$WorkEndDate[0];

				if($WorkStartDate=='00/00/0000'){
			     $WorkStartDate=''; 
			  }
			  if($WorkEndDate=='00/00/0000'){
			     $WorkEndDate=''; 
			  }
              $cid=$rows['PayId'];
              $WardNo=$rows['WardNo'];
              $WorkName=str_replace('\\','',stripslashes($rows['WorkName']));
              
			  $query1="select ContractorId,appdate from cw_tenders Where TenderId='".$rows['TenderId']."'";
			  $res1=$db1->query($query1);
			  $row1 = $db1->fetch_array($res1);
			  $appdate=$row1['appdate'];
			  $appdate = explode('-',$appdate);
			  $appdate=$appdate[2]."/".$appdate[1]."/".$appdate[0];
if($appdate=='00/00/0000'){
			     $appdate=''; 
			  }
			  
			  $sql2="SELECT ContractorName FROM contractors Where ContractorId='".$row1['ContractorId']."'";
              $res2=$db1->query($sql2);
			  $row2 = $db1->fetch_array();
	          $ContractorName=str_replace('\\','',stripslashes($row2['ContractorName']));  
			  
        
        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='payments.php?page=$prevpage&max=$MAX&$next_links&wardno=".$_GET['wardno']."' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='payments.php?page=$lastrow&max=$MAX&$next_links&wardno=".$_GET['wardno']."' >Next></a>";
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
          $PAGE_NAVS.=" <a href='payments.php?page=$i&max=$MAX&left_id=1&$next_links&wardno=".$_GET['wardno']."' >$toPrint</a> |";
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
    $sel_reg_id="delete from cw_progress where PayId in ($product)"; 
    $db->query($sel_reg_id);
	$total = $db->affected_rows();
  	$PROMPT = "Total $total Records have been deleted.";
 }


?>

