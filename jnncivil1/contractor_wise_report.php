<?php error_reporting(E_ALL);?>
<?php
include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to Jhansi Nagar Nigam";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$pid = $_REQUEST['pid'];
//echo '<pre>';
//print_r($_REQUEST);
if($_GET['Contractor']=='Single'){
	$radioContractor=$_GET['Contractor'];
	$radioSingle='checked';
	$ContractorId=$_GET['ContractorId'];
    GetContractorList($db,$db1,$ContractorId);
}else{
	$radioContractor=$_GET['Contractor'];
	$radioAll='checked';
}

$PhysicalProgress=$_GET['PhysicalProgress'];
if($_GET['PhysicalProgress']=='Completed'){
   $complete='checked';
}elseif($_GET['PhysicalProgress']=='In Progress'){
   $inprogress='checked';
}elseif($_GET['PhysicalProgress']=='Not Started'){
   $notstarted='checked';
}else{
   $all='checked';
}

if($_GET['Periods']=='SingleP'){
   $singlep='checked';
   $Periods=$_GET['Periods'];
   $FromDate=$_GET['FromDate']; 
   $ToDate=$_GET['ToDate'];

   $ShowFromDate="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;From&nbsp;<input type='text' name='FromDate' id='FromDate' value='".$FromDate."' size='10'  >";
   $ShowToDate="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To&nbsp;<input type='text' name='ToDate' id='ToDate' value='".$ToDate."' size='10' >";

   
   
   
}else{
	$allp='checked';
}
if(ISSET($_GET['showreport'])){
	$showreport=$_REQUEST['showreport'];
	viewreports($db,$db1,$radioContractor,$radioSingle,$radioAll,$ContractorId,$PhysicalProgress,$Periods,$FromDate,$ToDate,$showreport);
}
 
$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/contractor_wise_report.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

function viewreports($db,$db1,$radioContractor,$radioSingle,$radioAll,$ContractorId,$PhysicalProgress,$Periods,$FromDate,$ToDate,$showreport)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$db1,$db2,$strHead,$indicator;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$wardno,$workname,$WorkOrderNo,$estimateamt,$ContractorName,$appamt,$appdate,$workstartdate,$workenddate,$Payments,$CheckNos,$Comment,$MadName,$trclass,$ContractorSummary;
   
    
    $strHead="<thead><tr>
	   <th width='5%' style='font-family: kruti_dev_010regular;font-size:15PX;'><b>Ø0la0</b></th>
	   <th nowrap style='font-family: kruti_dev_010regular;font-size:15PX;'><b>Ekn</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>okMZ Uka0</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' nowrap><b>dk;Z dk uke</b></th>
	   
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>vkx.ku /kuakd</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>Bsdsnkj dk uke</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>fufonk Lohd`r /kuakd </b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>fufonk Lohd`r dk fnukad</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>dk;kZns”k dk fnuakd</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>dk;Z izkjEHk djus dh frfFk
¼dk;kZns”k ds vuqlkj ½
</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>dk;Z lekIr  djus dh frfFk
¼dk;kZns”k ds vuqlkj </b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>fu’ikfnr /kuakd</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>Hkqxrku dh fLFkfr</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>dk;Z lekIr djus dh frfFk
¼ lkbV ds vuqlkj ½
</b></th>
<th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>HkkSfrd izxfr</b></th>
<th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>vH;qfDr@
fo”ks’k
</b></th>
	  </thead>
    ";

    $indicator='<table width="1010px" align="center" border="0" ><tbody><tr >
 <td align="left"><div  class="reportheading">
 <span class="completerowindicator" style="display:inline-block;">&nbsp;</span><span style="font-family: kruti_dev_010regular;font-size:15PX;">&nbsp;dk;Z iw.kZ</span>
 <span class="incompleterowindicator" style="display:inline-block;">&nbsp;</span>&nbsp;<span style="font-family: kruti_dev_010regular;font-size:15PX;">Ikzxfr ij py jgs dk;Z</span> 
 <span class="notstartedrowindicator" style="display:inline-block;">&nbsp;</span>&nbsp;<span style="font-family: kruti_dev_010regular;font-size:15PX;">vukjEHk dk;Z </span>
 <span class="disputedrowindicator" style="display:inline-block;">&nbsp;</span>&nbsp;<span style="font-family: kruti_dev_010regular;font-size:15PX;">fookfnr dk;Z </span>
 <span class="cancelledrowindicator" style="display:inline-block;">&nbsp;</span>&nbsp;<span style="font-family: kruti_dev_010regular;font-size:15PX;">dsfUly dk;Z </span>
</div></td>
 </tr><tr><td>&nbsp;</td></tr>
  </tbody></table>';
   //$JEEmployeeCode,$PhysicalProgress,$Periods,$FromDate,$ToDate
   $strSql='';
   if($ContractorId!=''){
	   $strSql.=" and cw_t.ContractorId='$ContractorId'";
	   $strSql2.=" and cw_t.ContractorId='$ContractorId'";
	   
   }
   if($PhysicalProgress!='All'){
	   $strSql.=" and cw_prg.Status='$PhysicalProgress'";
	   $strSql1.=" and cw_pr.Status='$PhysicalProgress'";
   }

   if($Periods=='SingleP'){
		if($FromDate!=''){
			$FromDate = explode('/',$FromDate);
			$FromDate=$FromDate[2]."-".$FromDate[1]."-".$FromDate[0]; 
		}
   
		if($ToDate!=''){
			$ToDate = explode('/',$ToDate);
			$ToDate=$ToDate[2]."-".$ToDate[1]."-".$ToDate[0]; 
		}

	   if($FromDate!='' && $ToDate!=''){
	     $strSql.=" and cw_prg.WorkStartDate>='".$FromDate."' and cw_prg.WorkEndDate<='".$ToDate."'";
	     $strSql1.=" and cw_t.WorkStartDate>='".$FromDate."' and cw_t.WorkEndDate<='".$ToDate."'";
	   }elseif($FromDate!='' && $ToDate==''){
		   $strSql.=" and cw_prg.WorkStartDate>='".$FromDate."'";
		   $strSql1.=" and cw_t.WorkStartDate>='".$FromDate."'";
	   }elseif($FromDate=='' && $ToDate!=''){
		   $strSql.=" and cw_prg.WorkEndDate<='".$ToDate."'";
		   $strSql1.=" and cw_t.WorkEndDate<='".$ToDate."'";
	   }

   }
   //echo $strSql;
   $Total=0;$TotalComplete=0;$TotalProgress=0;$TotalPending=0;$TotalAmt=0;$TotalPaidAmt=0;

   if($PhysicalProgress=='All'){
	   $Tquery="select count(*) as  Total from cw_progress cw_prg inner join cw_estimation cw_e on cw_prg.EstmtId=cw_e.EstmtId INNER JOIN cw_tenders cw_t ON cw_t.EstmtId=cw_prg.EstmtId where cw_t.Status='Won' and 1 $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $Total = $Trows['Total'];

	   $Tquery="select count(*) as  TotalComplete from cw_progress cw_prg inner join cw_estimation cw_e on cw_prg.EstmtId=cw_e.EstmtId INNER JOIN cw_tenders cw_t ON cw_t.EstmtId=cw_prg.EstmtId where cw_t.Status='Won' and 1 and cw_prg.Status='Completed' $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalComplete = $Trows['TotalComplete'];

	   $Tquery="select count(*) as  TotalProgress from cw_progress cw_prg inner join cw_estimation cw_e on cw_prg.EstmtId=cw_e.EstmtId INNER JOIN cw_tenders cw_t ON cw_t.EstmtId=cw_prg.EstmtId where cw_t.Status='Won' and 1 and cw_prg.Status='In Progress' $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalProgress = $Trows['TotalProgress'];

	   $Tquery="select count(*) as  TotalPending from cw_progress cw_prg inner join cw_estimation cw_e on cw_prg.EstmtId=cw_e.EstmtId INNER JOIN cw_tenders cw_t ON cw_t.EstmtId=cw_prg.EstmtId where cw_t.Status='Won' and 1 and (cw_prg.Status='Not Started' || cw_prg.Status='Disputed') $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPending = $Trows['TotalPending'];
	   if($TotalPending=='')$TotalPending=0;

	   $Tquery="select sum(cw_t.amount) as TotalAmt from cw_progress cw_prg INNER JOIN cw_tenders cw_t ON cw_prg.TenderId=cw_t.TenderId INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId and cw_t.Status='Won' where 1 $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalAmt = $Trows['TotalAmt'];

	   $Tquery="select sum(cw_p.amount) as TotalPaidAmt from cw_payments cw_p INNER JOIN cw_tenders cw_t ON cw_p.WorkOrderNo=cw_t.WorkOrderNo INNER JOIN cw_estimation cw_e ON cw_t.EstmtId=cw_e.estmtId where 1 $strSql1 $strSql2";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPaidAmt = $Trows['TotalPaidAmt'];

	   $FinacialProgress=round(($TotalPaidAmt*100)/$TotalAmt,2);
   }elseif($PhysicalProgress=='Completed'){
	   
	   $Tquery="select count(*) as  TotalComplete from cw_progress cw_prg INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId INNER JOIN cw_tenders cw_t ON cw_t.EstmtId=cw_prg.EstmtId where cw_t.Status='Won' and 1 $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalComplete = $Trows['TotalComplete'];
	   $Total=$TotalComplete;
	   
	   $Tquery="select sum(cw_t.amount) as TotalAmt from cw_progress cw_prg INNER JOIN cw_tenders cw_t ON cw_prg.TenderId=cw_t.TenderId INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId where cw_t.Status='Won' $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalAmt = $Trows['TotalAmt'];

	   $Tquery="select sum(cw_p.amount) as TotalPaidAmt from cw_payments cw_p INNER JOIN cw_progress cw_prg ON cw_p.WorkOrderNo=cw_prg.WorkOrderNo INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId INNER JOIN cw_tenders cw_t ON cw_t.EstmtId=cw_prg.EstmtId where cw_t.Status='Won' $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPaidAmt = $Trows['TotalPaidAmt'];

	   $FinacialProgress=round(($TotalPaidAmt*100)/$TotalAmt,2);
   }elseif($PhysicalProgress=='In Progress'){
		
		$Tquery="select count(*) as  TotalProgress from cw_progress cw_prg INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId INNER JOIN cw_tenders cw_t ON cw_t.EstmtId=cw_prg.EstmtId where 1 $strSql";
	    $Tres=$db->query($Tquery);
	    $Trows=$db->fetch_assoc($Tres);
	    $TotalProgress = $Trows['TotalProgress'];
		$Total=$TotalProgress;

		$Tquery="select sum(cw_t.amount) as TotalAmt from cw_progress cw_prg INNER JOIN cw_tenders cw_t ON cw_prg.TenderId=cw_t.TenderId INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId and cw_t.Status='Won' $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalAmt = $Trows['TotalAmt'];

	   $Tquery="select sum(cw_p.amount) as TotalPaidAmt from cw_payments cw_p INNER JOIN cw_progress cw_prg ON cw_p.WorkOrderNo=cw_prg.WorkOrderNo INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId INNER JOIN cw_tenders cw_t ON cw_t.EstmtId=cw_prg.EstmtId $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPaidAmt = $Trows['TotalPaidAmt'];

	   $FinacialProgress=round(($TotalPaidAmt*100)/$TotalAmt,2);

   }elseif($PhysicalProgress=='Not Started' || $PhysicalProgress=='Disputed'){
       
	   $Tquery="select count(*) as  TotalPending from cw_progress cw_prg INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId INNER JOIN cw_tenders cw_t ON cw_t.EstmtId=cw_prg.EstmtId where cw_t.Status='Won' and (cw_prg.Status='Not Started' || cw_prg.Status='Disputed') $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPending = $Trows['TotalPending'];
	   if($TotalPending=='')$TotalPending=0;
	   $Total=$TotalPending;

	   $Tquery="select sum(cw_t.amount) as TotalAmt from cw_progress cw_prg INNER JOIN cw_tenders cw_t ON cw_prg.TenderId=cw_t.TenderId INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId where cw_t.Status='Won' and (cw_prg.Status='Not Started' || cw_prg.Status='Disputed') $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalAmt = $Trows['TotalAmt'];
	   if($TotalAmt=='')$TotalAmt=0;

	   $Tquery="select sum(cw_p.amount) as TotalPaidAmt from cw_payments cw_p INNER JOIN cw_progress cw_prg ON cw_p.WorkOrderNo=cw_prg.WorkOrderNo INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId INNER JOIN cw_tenders cw_t ON cw_t.EstmtId=cw_prg.EstmtId where cw_t.Status='Won' and (cw_prg.Status='Not Started' || cw_prg.Status='Disputed') $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPaidAmt = $Trows['TotalPaidAmt'];
	   if($TotalPaidAmt=='')$TotalPaidAmt=0; 	
	   $FinacialProgress=round(($TotalPaidAmt*100)/$TotalAmt,2);

   }

   if($ContractorId!=''){
	   $Contractor_sql="SELECT ContractorName,ContractorContact FROM contractors WHERE ContractorId='$ContractorId'";
	   $Contractor_res=$db->query($Contractor_sql);
	   $Contractor_rows=$db->fetch_array($Contractor_res);
	   
	   $ContractorName=$Contractor_rows['ContractorName'];
	   $ContractorContact=$Contractor_rows['ContractorContact'];
	   
	   $ContractorInfo="<span style='padding-right:10px;font-family: kruti_dev_010regular;font-size:15px;'>$ContractorName ds</span>";
   }else{
   $x='ds';
   }

   $ContractorSummary="<div class='pis_list'><table width='1010' border='1' cellspacing='0' cellpadding='5' align='center' ><tbody>
	  <tr style='background:#000;color:#fff;font-weight:bold;'><td colspan='7'><span style='padding-right:10px;font-family: kruti_dev_010regular;font-size:15px;'>Bsdsnkj $x $ContractorInfo $x dk;ksZa dk lkjka”k</span></td></tr>
	  <tr style='background:#000;color:#fff;'><td style='font-family: kruti_dev_010regular;font-size:15px;'>dqy iw.kZ dk;Z</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>Ikzxfr ij py jgs dk;Z</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>vukjEHk dk;Z</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>dqy dk;Z</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>dqy dk;ksZa dk /kuakd </td><td style='font-family: kruti_dev_010regular;font-size:15px;'>dqy fu’ikfnr  dk;Z dk /kuakd </td><td ><span style='font-family: kruti_dev_010regular;font-size:15px;'>foRRkh; izxfr dk izfr”kr</span> <span>(%)</span></td></tr>
	  <tr><td>$TotalComplete</td><td>$TotalProgress</td><td>$TotalPending</td><td>$Total</td><td>$TotalAmt</td><td>$TotalPaidAmt</td><td>$FinacialProgress%</td></tr>
	  </tbody></table></div>"; 

   $S1	= $S2 = ReadTemplate("$TEMPLATE_DIR/contractor_wiseDisplayGrid.html");

	 
	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	 $MAX=20;
	 $lastrow=$MAX+$page;
	 
	$count="select count(*) as total from cw_progress cw_prg INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.EstmtId INNER JOIN cw_tenders cw_t ON cw_prg.EstmtId=cw_t.EstmtId where cw_t.Status='Won' $strSql";
	 
    $db->query($count);
    $row = $db->fetch_assoc();
    $TOTAL_RECORDSET = $row['total'];
  
    $query="select cw_prg.* from cw_progress cw_prg INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.EstmtId INNER JOIN cw_tenders cw_t ON cw_prg.EstmtId=cw_t.EstmtId where cw_t.Status='Won' $strSql order by cw_e.WardNo ASC limit  $page, $MAX ";
    
    $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			
			while($rows = $db->fetch_array())
			{
			  $sql="SELECT * FROM cw_payments where WorkOrderNo='".$rows['WorkOrderNo']."'";
              $res1=$db1->query($sql);
			  
			  $WorkOrderNo=$rows['WorkOrderNo'];
			  $workstartdate=$rows['WorkStartDate'];
              $workstartdate = explode('-',$workstartdate);
			  $workstartdate=$workstartdate[2]."/".$workstartdate[1]."/".$workstartdate[0];

			  $workenddate=$rows['WorkEndDate'];
              $workenddate = explode('-',$workenddate);
			  $workenddate=$workenddate[2]."/".$workenddate[1]."/".$workenddate[0];

			  $Status=$rows['Status'];

			  if($Status=='In Progress'){
			    $trclass='incompleterowindicator';
			  }elseif($Status=='Completed'){
			    $trclass='completerowindicator';
			  }elseif($Status=='Not Started' || $Status=='Pending'){
			    $trclass='notstartedrowindicator';
			  }elseif($Status=='Cancelled'){
			    $trclass='cancelledrowindicator';
			  }elseif($Status=='Disputed'){
			    $trclass='disputedrowindicator';
			  }

			  $Payments='';
			  $CheckNos='';
			  $Comment='';
			  while($row1 = $db1->fetch_array())
			  {
				  if($PayDate!='00/00/0000'){
					  $PayDate=$row1['PayDate'];
					  $PayDate = explode('-',$PayDate);
					  $PayDate=$PayDate[2]."/".$PayDate[1]."/".$PayDate[0];
				  }else{
					  $PayDate='';
				  }

				  if(isset($row1['Amount'])){
					  $PayAmount=$row1['Amount'];
				  }
				  if($CheckDate!='00/00/0000'){
					  $CheckDate=$row1['CheckDate'];
					  $CheckDate = explode('-',$CheckDate);
					  $CheckDate=$CheckDate[2]."/".$CheckDate[1]."/".$CheckDate[0];
				  }else{
					  $CheckDate='';
				  }

				  $Payments.="<div ><span >".$row1['Installment']."</span><br>";
				  $CheckNos.="<div>".$row1['Amount']."<br></div><br>";
				  //$CheckNos.="<div>No. ".$row1['CheckNo'].'<br>'.$CheckDate."</div><br>";
				  $Comment.="<div >".$row1['Comments']."<br>";
			  }

			  $sql1="SELECT * FROM cw_tenders where TenderId='".$rows['TenderId']."'";
              $res11=$db1->query($sql1);
			  $rows2 = $db1->fetch_array();
			  $appamt=$rows2['amount'];
			  
			  $sql4="SELECT * FROM contractors Where ContractorId='".$rows2['ContractorId']."'";
              $res4=$db1->query($sql4);
			  $row4 = $db1->fetch_array();

              $ContractorName=$row4['ContractorName'];
			  $appdate=$rows2['appdate'];
			  $appdate = explode('-',$appdate);
			  $appdate=$appdate[2]."/".$appdate[1]."/".$appdate[0];
              
			  $sql2="SELECT * FROM cw_estimation where EstmtId='".$rows['EstmtId']."'";
              $res12=$db1->query($sql2);
			  $rows3 = $db1->fetch_array();

			  $workname=$rows3['WorkName'];
              $estimateamt=$rows3['TotalAmount'];
              $wardno=$rows3['WardNo'];
              $MadId=$rows3['MadId'];
			  if($wardno==0){
				$wardno='<span style="font-family: kruti_dev_010regular;font-size:15px;">vU; dk;Z </span>';
			  }

			  $sql3="SELECT * FROM cw_mads where MadId='".$MadId."'";
              $res13=$db1->query($sql3);
			  $rows4 = $db1->fetch_array();
			  $MadName=$rows4['MadName'];


                      
        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='contractor_wise_report.php?page=$prevpage&max=$MAX&Ward=$radioWard&WardNo=$WardNo&PhysicalProgress=$PhysicalProgress&Periods=$Periods&FromDate=$FromDate&ToDate=$ToDate&showreport=$showreport'>Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='contractor_wise_report.php?page=$lastrow&max=$MAX&Ward=$radioWard&WardNo=$WardNo&PhysicalProgress=$PhysicalProgress&Periods=$Periods&FromDate=$FromDate&ToDate=$ToDate&showreport=$showreport' >Next></a>";
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
          $PAGE_NAVS.=" <a href='contractor_wise_report.php?page=$i&max=$MAX&left_id=1&&Ward=$radioWard&WardNo=$WardNo&PhysicalProgress=$PhysicalProgress&Periods=$Periods&FromDate=$FromDate&ToDate=$ToDate&showreport=$showreport' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='16'>No Records Found</td></tr>";
    }
   
  return 1;
 }
 
function GetContractorList($db,$db1,$pContractorId='')
 {	
	global $ContractorList;
	$sql="select ContractorId,ContractorName,ContractorContact from contractors order by ContractorId ASC";
    $row=$db->query($sql);
      $ContractorList.="<select  name='ContractorId'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      //$JEofficers.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
            
			$ContractorId = $res['ContractorId'];
			$ContractorName = $res['ContractorName'];
			$ContractorContact = $res['ContractorContact'];

				if($ContractorId==$pContractorId){
					$ContractorList.="<option value='$ContractorId' selected>$ContractorName - $ContractorContact </option>";
				}else{
					$ContractorList.="<option value='$ContractorId' >$ContractorName - $ContractorContact</option>";
				}
			
		 
       }
      $ContractorList.="</select>";
 }
?>