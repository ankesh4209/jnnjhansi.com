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
	viewreports($db,$db1,$radioWard,$radioSingle,$radioAll,$WardNo,$PhysicalProgress,$Periods,$FromDate,$ToDate,$showreport);
}
 
$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/proposedwork_report.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");

ReplaceContent(Array("TOPBAR", "PAGE_CONTENTS", "BOTTOMBAR", "TEMPLATE",));
print $TEMPLATE;
flush();

function viewreports($db,$db1,$radioWard,$radioSingle,$radioAll,$WardNo,$PhysicalProgress,$Periods,$FromDate,$ToDate,$showreport)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST,$db1,$db2,$strHead,$indicator;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$WardNo,$WorkName,$EstimatedAmount,$CorporatorName,$ProposerName,$JEInfo,$AEInfo,$EXnInfo,$trclass,$WardName;
   
    
    $strHead="<thead><tr>
	   <th width='5%' style='font-family: kruti_dev_010regular;font-size:15PX;'><b>Ø0la0</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>okMZ Uka0</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>okMZ dk uke</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>Ikk’kZn dk uke</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>IkzLrkod</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>voj vfHk;Urk dk uke</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;'><b>dk;Z dk uke</b></th>
	   <th style='font-family: kruti_dev_010regular;font-size:15PX;' nowrap><b>vkx.ku /kuakd</b></th>
	   
	   
	  </thead>
    ";

     //$JEEmployeeCode,$PhysicalProgress,$Periods,$FromDate,$ToDate
   $strSql='';
   if($WardNo!=''){
	   $strSql.=" and WardNo='$WardNo'";
	   $strSql2.=" and WardNo='$WardNo'";
	   
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
	     $strSql.=" and ProposedWorkDate>='".$FromDate."' and ProposedWorkDate<='".$ToDate."'";
	     $strSql1.=" and ProposedWorkDate>='".$FromDate."' and ProposedWorkDate<='".$ToDate."'";
	   }elseif($FromDate!='' && $ToDate==''){
		   $strSql.=" and ProposedWorkDate>='".$FromDate."'";
		   $strSql1.=" and ProposedWorkDate>='".$FromDate."'";
	   }elseif($FromDate=='' && $ToDate!=''){
		   $strSql.=" and ProposedWorkDate<='".$ToDate."'";
		   $strSql1.=" and ProposedWorkDate<='".$ToDate."'";
	   }

   }
   //echo $strSql;
  
   
   $S1	= $S2 = ReadTemplate("$TEMPLATE_DIR/proposedworkDisplayGrid.html");

	 
	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	 $MAX=20;
	 $lastrow=$MAX+$page;
	 
	$count="select count(EstmtId) as total from cw_estimation $strSql";
	 
    $db->query($count);
    $row = $db->fetch_assoc();
    $TOTAL_RECORDSET = $row['total'];
  
    $query="select * from cw_estimation where 1 $strSql order by WardNo ASC limit  $page, $MAX ";
    
    $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			
			while($rows = $db->fetch_array())
			{
			  
			  $WardNo=$rows['WardNo'];
			  $ProposerName=$rows['ProposerName'];
			  $JEEmployeeCode=$rows['JEEmployeeCode'];
			  $AEEmployeeCode=$rows['AEEmployeeCode'];
			  $EXnEmployeeCode=$rows['EXnEmployeeCode'];
			  $WorkName=str_replace('\\','',stripslashes($rows['WorkName']));
			  $EstimatedAmount=$rows['TotalAmount'];
			  
			  $sql1="SELECT * FROM corporators where WardNo='".$rows['WardNo']."'";
              $res11=$db1->query($sql1);
			  $rows1 = $db1->fetch_array();
			  $WardName=$rows1['WardName'];
			  $CorporatorName=$rows1['CorporatorName'];

			  $JEInfo=GetOfficerInfo($db1,$JEEmployeeCode);
			  $AEInfo=GetOfficerInfo($db1,$AEEmployeeCode);
	          $EXnInfo=GetOfficerInfo($db1,$EXnEmployeeCode);
		
                      
        	  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='proposedwork_report.php?page=$prevpage&max=$MAX&Ward=$radioWard&WardNo=$WardNo&Periods=$Periods&FromDate=$FromDate&ToDate=$ToDate&showreport=$showreport'>Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='proposedwork_report.php?page=$lastrow&max=$MAX&Ward=$radioWard&WardNo=$WardNo&Periods=$Periods&FromDate=$FromDate&ToDate=$ToDate&showreport=$showreport' >Next></a>";
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
          $PAGE_NAVS.=" <a href='proposedwork_report.php?page=$i&max=$MAX&Ward=$radioWard&WardNo=$WardNo&Periods=$Periods&FromDate=$FromDate&ToDate=$ToDate&showreport=$showreport' >$toPrint</a> |";
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

 function GetOfficerInfo($db1,$EmployeeCode)
 {
	global $officerdetail;
	$sql="select EmployeeName from employeeinfo where EmployeeCode='".$EmployeeCode."'";
    $res=$db1->query($sql);
    $rows = $db1->fetch_array(); 
	$officerdetail = $rows['EmployeeName'];
    return $officerdetail;   
      
 }
?>