<?php 
session_start();
$filter=$_SESSION['filter'];
include "class.hrrecruitment.php";
$obj= new recruitment();
$obj->session_check($_SESSION['userid']);
if($_SESSION['usertype']=='SUPER')
{
$r =$obj->HRPayroll('','','ORDER BY o.id',$filter);	
}
else
{
$r =$obj->HRPayroll('',$_SESSION['userid'],'ORDER BY o.id DESC',$filter);	
}
function xlsBOF() { 
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);  
    return; 
} 

function xlsEOF() { 
    echo pack("ss", 0x0A, 0x00); 
    return; 
} 

function xlsWriteNumber($Row, $Col, $Value) { 
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0); 
    echo pack("d", $Value); 
    return; 
} 

function xlsWriteLabel($Row, $Col, $Value ) { 
    $L = strlen($Value); 
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L); 
    echo $Value; 
return; 
} 
$now_date = date('d-m-Y');

    // Send Header
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment;filename=HRPayrollReport$now_date.xls "); // ?????????????????
    header("Content-Transfer-Encoding: binary ");

    // XLS Data Cell
				xlsBOF(); 
               	xlsWriteLabel(0,0,"Code");
				xlsWriteLabel(0,1,"LOCATION");
				xlsWriteLabel(0,2,"NAME OF THE EMPLOYEE");
				xlsWriteLabel(0,3,"FATHER'S/ HUSBAND'S NAME");
				xlsWriteLabel(0,4,"DOB");
				xlsWriteLabel(0,5,"LEVEL");
				xlsWriteLabel(0,6,"DESIGNATION");
				xlsWriteLabel(0,7,"DOJ");
				xlsWriteLabel(0,8,"JOINED ON");
				xlsWriteLabel(0,9,"SBU");
				xlsWriteLabel(0,10,"DEPT");
				xlsWriteLabel(0,11,"HOD");
				xlsWriteLabel(0,12,"BASIC");
                xlsWriteLabel(0,13,"HRA");
                xlsWriteLabel(0,14,"CON");
                xlsWriteLabel(0,15,"SUP");
				xlsWriteLabel(0,16,"MEAL");
				xlsWriteLabel(0,17,"MED");
				xlsWriteLabel(0,18,"LTA");
				xlsWriteLabel(0,19,"TEL");
				xlsWriteLabel(0,20,"TOTAL RETRIAL");
				xlsWriteLabel(0,21,"GROSS SALARY");
				xlsWriteLabel(0,22,"BONUS");
				xlsWriteLabel(0,23,"JOINING BONUS");
				xlsWriteLabel(0,24,"PF");
				xlsWriteLabel(0,25,"ESI");
				xlsWriteLabel(0,26,"GRATUITY");
				xlsWriteLabel(0,27,"MEDICLAM");
				xlsWriteLabel(0,28,"PA");
				xlsWriteLabel(0,29,"TOTAL");
				xlsWriteLabel(0,30,"CTC(per month)");
				xlsWriteLabel(0,31,"OFFER DATE");
				xlsWriteLabel(0,32,"STATUS");
				xlsWriteLabel(0,33,"HIRING MANAGER");
                xlsWriteLabel(0,34,"ADDITIONAL/REPLACEMENT");
				xlsWriteLabel(0,35,"SOURCE");
				xlsWriteLabel(0,36,"TPT");
				xlsWriteLabel(0,37,"CANDIDATE EXP.");
				xlsWriteLabel(0,38,"CURRENT ORGANIZATION");
				xlsWriteLabel(0,39,"CITY");
				xlsWriteLabel(0,40,"QUALIFICATION");
				xlsWriteLabel(0,41,"Ex.EMPLOYEE");
				xlsWriteLabel(0,42,"EMP TYPE");
				
				$xlsRow = 1;
                while(list($code,$location,$name,$father,$dob,$nbasic,$oesi,$emp_esi,$emppf,$retiral,$ctc,$level,$designation,$joining_date,$joinedon,$sbu,$dept,$sbuhead,$basic,$hra,$con,$sup,$meal,$med,$lta,$tel,$fep,$gsal,$bonus,$join_bonus,$pf,$esi,$gruity,$medclaim,$pa,$total,$ctcpm,$offerdate,$status,$hiring_manager,$add_replace,$sourcer,$tpt,$exp,$corg,$city,$qualification,$exemp,$employemnt_type)=mysqli_fetch_row($r)) {
                    ++$i;
					
					        $basic=$basic>0?$basic:$nbasic;
						  $fep =$fep>0?$fep:$retiral;
						  $total=$total>0?$total:$ctc*12;
						  $ctcpm=$ctcpm>0?$ctcpm:$ctc;
						  //$esi=$esi>0?$esi:$emp_esi;
						  $esi=$oesi>0?$oesi:$esi;
						  $pf=$pf>0?$pf:$emppf;
						  $dept = $obj->getName('department',$dept);
						  xlsWriteLabel($xlsRow,0,"$code");
						  xlsWriteLabel($xlsRow,1,"$location");
						  xlsWriteLabel($xlsRow,2,"$name");
						  xlsWriteLabel($xlsRow,3,"$father");
						  xlsWriteLabel($xlsRow,4,"$dob");
						  xlsWriteLabel($xlsRow,5,"$level");
						  xlsWriteLabel($xlsRow,6,"$designation");
                          xlsWriteLabel($xlsRow,7,"$joining_date");
						  xlsWriteLabel($xlsRow,8,"$joinedon");
						  xlsWriteLabel($xlsRow,9,"$sbu");
						  xlsWriteLabel($xlsRow,10,"$dept");
						  xlsWriteLabel($xlsRow,11,"$sbuhead");
						  xlsWriteLabel($xlsRow,12,"$basic");
						  xlsWriteLabel($xlsRow,13,"$hra");
						  xlsWriteLabel($xlsRow,14,"$con");
                          xlsWriteLabel($xlsRow,15,"$sup");
                          xlsWriteLabel($xlsRow,16,"$meal");
						  xlsWriteLabel($xlsRow,17,"$med");
						  xlsWriteLabel($xlsRow,18,"$lta");
						  xlsWriteLabel($xlsRow,19,"$tel");
						  xlsWriteLabel($xlsRow,20,"$fep");
						  xlsWriteLabel($xlsRow,21,"$gsal");
						  xlsWriteLabel($xlsRow,22,"$bonus");
						  xlsWriteLabel($xlsRow,23,"$join_bonus");
						  xlsWriteLabel($xlsRow,24,"$pf");
						  xlsWriteLabel($xlsRow,25,"$esi");
						  xlsWriteLabel($xlsRow,26,"$gruity");
						  xlsWriteLabel($xlsRow,27,"$medclaim");
						  xlsWriteLabel($xlsRow,28,"$pa");
						  xlsWriteLabel($xlsRow,29,"$total");
						  xlsWriteLabel($xlsRow,30,"$ctcpm");
						  xlsWriteLabel($xlsRow,31,"$offerdate");
						  xlsWriteLabel($xlsRow,32,"$status");
						  
						  xlsWriteLabel($xlsRow,33,"$hiring_manager");
						  xlsWriteLabel($xlsRow,34,"$add_replace");
						  xlsWriteLabel($xlsRow,35,"$sourcer");
						  xlsWriteLabel($xlsRow,36,"$tpt");
						  xlsWriteLabel($xlsRow,37,"$exp");
						  xlsWriteLabel($xlsRow,38,"$corg");
						  xlsWriteLabel($xlsRow,39,"$city");
						  xlsWriteLabel($xlsRow,40,"$qualification");
						  xlsWriteLabel($xlsRow,41,"$exemp");
						 xlsWriteLabel($xlsRow,42,"$employemnt_type");

                    $xlsRow++;
                    }
                     xlsEOF();
                 exit();

?>