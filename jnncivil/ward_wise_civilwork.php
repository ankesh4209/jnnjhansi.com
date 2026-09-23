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


//echo '<pre>';
//print_r($_REQUEST);
if($_GET['Ward']=='Single'){
	$radioWard=$_GET['Ward'];
	$radioSingle='checked';
	$WardNo=$_GET['WardNo'];
    GetWardList($db,$db1,$WardNo);
}else{
	$radioWard=$_GET['Ward'];
	$radioAll='checked';
}

$PhysicalProgress=$_GET['PhysicalProgress'];
if($_GET['PhysicalProgress']=='2'){
   $complete='checked';
}elseif($_GET['PhysicalProgress']=='1'){
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
	viewreports($db,$db1,$radioWard,$radioSingle,$radioAll,$WardNo,$PhysicalProgress,$Periods,$FromDate,$ToDate,$showreport);
}
 
$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/ward_wise_civilwork.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

function viewreports($db,$db1,$radioWard,$radioSingle,$radioAll,$WardNo,$PhysicalProgress,$Periods,$FromDate,$ToDate,$showreport)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$db1,$db2,$strHead,$indicator;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$wardno,$workname,$WorkOrderNo,$estimateamt,$ContractorName,$appamt,$appdate,$workstartdate,$workenddate,$Payments,$CheckNos,$Comment,$MadName,$trclass,$WardWorkSummary, $completedphoto,  $startphoto, $inprogressphoto;
   
    
    $strHead="<thead><tr>
	   <th width='5%' style='font-family: kruti_dev_010regular;font-size:15PX;'><b>Ø0la0</b></th>
	   <th nowrap style='font-family: kruti_dev_010regular;font-size:15PX;'><b>okMZ Uka0</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>dk;Z dk uke</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' nowrap><b>dk;Z izkjfEHkd dh QksVks</b></th>
	   
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>dk;Z dh izxfr'khy QksVks</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>dk;Z lekfIr dh QksVks </b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>fu'ikfnr /kukad </b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>QksVks viyksM fnukad </b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' ><b>HkkSfrd izxfr</b></th> 
	  </thead>
    ";

      $indicator='<table width="1010px" align="center" border="0" ><tbody><tr >
 <td align="left"><div  class="reportheading">
 <span class="completerowindicator" style="display:inline-block;">&nbsp;</span><span style="font-family: kruti_dev_010regular;font-size:15PX;">dk;Z iw.kZ</span>
 <span class="notstartedrowindicator" style="display:inline-block;">&nbsp;</span>&nbsp;<span style="font-family: kruti_dev_010regular;font-size:15PX;">Ikzxfr ij py jgs dk;Z</span> 
<!--  <span class="notstartedrowindicator" style="display:inline-block;">&nbsp;</span>&nbsp;<span style="font-family: kruti_dev_010regular;font-size:15PX;">okMZ Uka0</span>
 <span class="disputedrowindicator" style="display:inline-block;">&nbsp;</span>&nbsp;<span style="font-family: kruti_dev_010regular;font-size:15PX;">fookfnr dk;Z </span>
 <span class="cancelledrowindicator" style="display:inline-block;">&nbsp;</span>&nbsp;<span style="font-family: kruti_dev_010regular;font-size:15PX;">dsfUly dk;Z </span> -->
</div></td>
 </tr><tr><td>&nbsp;</td></tr>
  </tbody></table>';
   //$JEEmployeeCode,$PhysicalProgress,$Periods,$FromDate,$ToDate
   $strSql='';
   if($WardNo!=''){
	   $strSql.=" and wardno='$WardNo'";
	   $strSql2.=" and  wardno='$WardNo'";
	   
   }
   if($PhysicalProgress!='All'){
	   $strSql.=" and status='$PhysicalProgress'";
	   $strSql1.=" and status='$PhysicalProgress'";
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
	   /*$Tquery="select count(*) as  Total from cw_progress cw_prg inner join cw_estimation cw_e on cw_prg.EstmtId=cw_e.EstmtId where cw_prg.Status!='Cancelled' and 1 $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $Total = $Trows['Total'];*/

	   $Tquery="select count(*) as  TotalComplete from cw_photo where 1 and status=2 $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalComplete = $Trows['TotalComplete'];

	    $Tquery22="select count(*) as  TotalProgress from cw_photo where 1 and status=1 $strSql";
	    $Tres=$db->query($Tquery22); 
	    $Trows=$db->fetch_assoc($Tres); 
	    $TotalProgress = $Trows['TotalProgress'];

	   $Tquery="select count(*) as  TotalPending from cw_progress cw_prg inner join cw_estimation cw_e on cw_prg.EstmtId=cw_e.EstmtId where 1 and (cw_prg.Status='Not Started' || cw_prg.Status='Disputed') $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPending = $Trows['TotalPending'];
	   if($TotalPending=='')$TotalPending=0;

	   $Tquery="select sum(cw_t.amount) as TotalAmt from cw_progress cw_prg INNER JOIN cw_tenders cw_t ON cw_prg.TenderId=cw_t.TenderId INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId and cw_t.Status='Won' $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalAmt = $Trows['TotalAmt'];

	   $Tquery="select sum(cw_p.amount) as TotalPaidAmt from cw_payments cw_p INNER JOIN cw_tenders cw_t ON cw_p.WorkOrderNo=cw_t.WorkOrderNo INNER JOIN cw_estimation cw_e ON cw_t.EstmtId=cw_e.estmtId $strSql1 $strSql2";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPaidAmt = $Trows['TotalPaidAmt'];

	   $Total= $TotalComplete+$TotalProgress;

	   $FinacialProgress=round(($TotalPaidAmt*100)/$TotalAmt,2);
   }
   elseif($PhysicalProgress=='2'){	   
	   $Tquery="select count(*) as  TotalComplete from cw_photo where 1 $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalComplete = $Trows['TotalComplete'];
	   $Total=$TotalComplete;
	   
	   $Tquery="select sum(cw_t.amount) as TotalAmt from cw_progress cw_prg INNER JOIN cw_tenders cw_t ON cw_prg.TenderId=cw_t.TenderId INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId and cw_t.Status='Won' $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalAmt = $Trows['TotalAmt'];

	   $Tquery="select sum(cw_p.amount) as TotalPaidAmt from cw_payments cw_p INNER JOIN cw_progress cw_prg ON cw_p.WorkOrderNo=cw_prg.WorkOrderNo INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPaidAmt = $Trows['TotalPaidAmt'];

	   $FinacialProgress=round(($TotalPaidAmt*100)/$TotalAmt,2);
   }elseif($PhysicalProgress=='1'){
		
		$Tquery="select count(*) as  TotalProgress from cw_photo where 1 $strSql";
	    $Tres=$db->query($Tquery);
	    $Trows=$db->fetch_assoc($Tres);
	    $TotalProgress = $Trows['TotalProgress'];	
		$Total=$TotalProgress;

		$Tquery="select sum(cw_t.amount) as TotalAmt from cw_progress cw_prg INNER JOIN cw_tenders cw_t ON cw_prg.TenderId=cw_t.TenderId INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId and cw_t.Status='Won' $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalAmt = $Trows['TotalAmt'];

	   $Tquery="select sum(cw_p.amount) as TotalPaidAmt from cw_payments cw_p INNER JOIN cw_progress cw_prg ON cw_p.WorkOrderNo=cw_prg.WorkOrderNo INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPaidAmt = $Trows['TotalPaidAmt'];

	   $FinacialProgress=round(($TotalPaidAmt*100)/$TotalAmt,2);

   }
   elseif(
   $PhysicalProgress=='Not Started' || $PhysicalProgress=='Disputed'){
       
	   $Tquery="select count(*) as  TotalPending from cw_progress cw_prg INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId where 1  and (cw_prg.Status='Not Started' || cw_prg.Status='Disputed') $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPending = $Trows['TotalPending'];
	   if($TotalPending=='')$TotalPending=0;
	   $Total=$TotalPending;

	   $Tquery="select sum(cw_t.amount) as TotalAmt from cw_progress cw_prg INNER JOIN cw_tenders cw_t ON cw_prg.TenderId=cw_t.TenderId INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId and cw_t.Status='Won' and (cw_prg.Status='Not Started' || cw_prg.Status='Disputed') $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalAmt = $Trows['TotalAmt'];
	   if($TotalAmt=='')$TotalAmt=0;

	   $Tquery="select sum(cw_p.amount) as TotalPaidAmt from cw_payments cw_p INNER JOIN cw_progress cw_prg ON cw_p.WorkOrderNo=cw_prg.WorkOrderNo INNER JOIN cw_estimation cw_e ON cw_prg.EstmtId=cw_e.estmtId and (cw_prg.Status='Not Started' || cw_prg.Status='Disputed') $strSql";
	   $Tres=$db->query($Tquery);
	   $Trows=$db->fetch_assoc($Tres);
	   $TotalPaidAmt = $Trows['TotalPaidAmt'];
	   if($TotalPaidAmt=='')$TotalPaidAmt=0; 	
	   $FinacialProgress=round(($TotalPaidAmt*100)/$TotalAmt,2);

   }

   if($WardNo!=''){
	   $ward_sql="SELECT WardNo,WardName,CorporatorName FROM corporators WHERE WardNo='$WardNo'";
	   $ward_res=$db->query($ward_sql);
	   $ward_rows=$db->fetch_array($ward_res);
	   $WardName=$ward_rows['WardName'];
	   $CorporatorName=$ward_rows['CorporatorName'];
	   $WardInfo="Uka0 $WardNo-<span style='padding-right:10px;font-family: kruti_dev_010regular;font-size:15px;'>$WardName</span>-<span style='padding-right:10px;font-family: kruti_dev_010regular;font-size:15px;'>$CorporatorName ds</span>";
	   
   }else{
   $x='ds';
   }
 
      $WardWorkSummary="<div class='pis_list'><table width='1010' border='1' cellspacing='0' cellpadding='5' align='center' ><tbody>
	  <tr style='background:#000;color:#fff;font-weight:bold;font-family: kruti_dev_010regular;font-size:15px;'><td colspan='7'>okMZ $x $WardInfo dk;ksZa dk lkjka”k</td></tr>
	  <tr style='background:#000;color:#fff;'><td style='font-family: kruti_dev_010regular;font-size:15px;'>dqy iw.kZ dk;Z</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>Ikzxfr ij py jgs dk;Z</td><td style='font-family: kruti_dev_010regular;font-size:15px;'>dqy dk;Z</td>

      </tr>
	  <tr><td>$TotalComplete</td><td>$TotalProgress</td><td>$Total</td></tr>
	  </tbody></table></div>
</body>"; 

   $S1	= $S2 = ReadTemplate("$TEMPLATE_DIR/civilwork_wiseDisplayGrid.html");

	 
	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	 $MAX=20;
	 $lastrow=$MAX+$page;
	 
	  $count="select count(id) as total from cw_photo $strSql";
	 
    $db->query($count);
    $row = $db->fetch_assoc();
    $TOTAL_RECORDSET = $row['total'];
  
     $query="select  * from cw_photo where 1  $strSql order by id DESC limit  $page, $MAX ";
    
    $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			
			while($rows = $db->fetch_array())
			{
			   
			  $wardno=$rows['wardno'];
		      $workname=$rows['name_work'];
			  
			  
			  $workstartdate=$rows['amount'];
            
			  $workenddate=$rows['date2'];
              $workenddate = explode('-',$workenddate);
			  $workenddate=$workenddate[2]."/".$workenddate[1]."/".$workenddate[0];


                $startphoto= '';
			  if($rows['start_photo']!=""){
			    $start_photo= $rows['start_photo']; 
			    $startphoto= '<a href="payment/'.$start_photo.'" target="_blank"><img src="payment/'.$start_photo.'"  width="50" height="50" ></a>';  
			  }
			 
			  $inprogressphoto= '';
			  if($rows['inprogess_photo']!=""){
			    $inprogess_photo= $rows['inprogess_photo']; 
			    $inprogressphoto= '<a href="payment/'.$inprogess_photo.'" target="_blank"><img src="payment/'.$inprogess_photo.'"  width="50" height="50" ></a>';  
			  }
			   
			   $completedphoto= '';
			  if($rows['complete_photo']!=""){
			    $complete_photo= $rows['complete_photo']; 
			    $completedphoto= '<a href="payment/'.$complete_photo.'" target="_blank"><img src="payment/'.$complete_photo.'"  width="50" height="50" ></a>';  
			  }
			  
			  
			  
			  $Status=$rows['status'];

			  if($Status=='2'){
			    $trclass='completerowindicator';
				$Status='dk;Z iw.kZ';
			  }elseif($Status=='1' || $Status=='1'){
			    $trclass='notstartedrowindicator';
			    $Status='dk;Z  Ikzxfr ij';
			  }elseif($Status=='Cancelled'){
			    $trclass='cancelledrowindicator';
			  }elseif($Status=='Disputed'){
			    $trclass='disputedrowindicator';
			  }

			  $Payments='';
			  $CheckNos='';
			  $Comment='';
			  
        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='ward_wise_report.php?page=$prevpage&max=$MAX&Ward=$radioWard&WardNo=$WardNo&PhysicalProgress=$PhysicalProgress&Periods=$Periods&FromDate=$FromDate&ToDate=$ToDate&showreport=$showreport'>Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='ward_wise_report.php?page=$lastrow&max=$MAX&Ward=$radioWard&WardNo=$WardNo&PhysicalProgress=$PhysicalProgress&Periods=$Periods&FromDate=$FromDate&ToDate=$ToDate&showreport=$showreport' >Next></a>";
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
          $PAGE_NAVS.=" <a href='ward_wise_report.php?page=$i&max=$MAX&Ward=$radioWard&WardNo=$WardNo&PhysicalProgress=$PhysicalProgress&Periods=$Periods&FromDate=$FromDate&ToDate=$ToDate&showreport=$showreport' >$toPrint</a> |";
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
 
function GetWardList($db,$db1,$pWardNo='')
 {	
	global $WardList;
	$sql="select WardNo,WardName from corporators order by WardNo ASC";
    $row=$db->query($sql);
      $WardList.="<select  name='WardNo'  style='font-family: kruti_dev_010regular;font-size:15px;'>";
      //$JEofficers.="<option value=''>pqus</option>";
	  while($res=$db->fetch_array($row))
       {
            
			$WardNo = $res['WardNo'];
			$WardName = str_replace('\\','',stripslashes($res['WardName']));
			if($WardNo!=0){
				if($WardNo==$pWardNo){
					$WardList.="<option value='$WardNo' selected>okMZ Uka0 $WardNo - $WardName </option>";
				}else{
					$WardList.="<option value='$WardNo' >okMZ Uka0 $WardNo - $WardName </option>";
				}
			}
		 
       }
      $WardList.="</select>";
 }
?>