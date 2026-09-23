<?php session_start(); ?>
<?php 
 if ($_SESSION['username']=='')
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
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());


if(isset($_POST['submit']) && $_POST['submit']!=''){
	$sdate=$_REQUEST['sdate'];
    $edate=$_REQUEST['edate'];
	
	
	$file=GetAllcomplainantReport($db,$db1,$sdate,$edate);
	$Link="<a href='../../download.php?file=$file'>Click Here</a> to download report.";
}


if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/exportdata.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();


function GetAllcomplainantReport($db,$db1,$sdate,$edate)
 {
     global $db,$db1,$DOCUMENT_ROOT;
	$cr="\r\n";
    $t="\t";
    $candition='1';
	 
	    $sdate=explode("/",$sdate);
	    $sdate1=mktime(0,0,0,$sdate['1'],$sdate['0'],$sdate['2']);
         
		$edate=explode("/",$edate);
		$edate1=mktime(0,0,0,$edate['1'],$edate['0'],$edate['2']);
	    
	    $d_rang=" and c_date>='$sdate1' and c_date<='$edate1' order by c_id";
		$query="SELECT * from complainant where $candition  $d_rang";	
	
	
	$lines = "S.NO.".$t."Complaint ID".$t."Comp. Name".$t."Comp. Address".$t."Comp. Detail".$t."Department".$t."Officer".$t."Officer Contact".$t."Comp. Date".$t."Comp. target Date".$t."Comp. Category".$t."Comp. Mode".$t."Disposed Date".$t."Disposal Text ".$t."Status".$cr;

	$res=$db->query($query);
	if($db->num_rows() > 0) {
	$i=0;
	
	while($row=$db->fetch_array($res)){
		
		$sn=$i+1;
		$dep_query="SELECT nature_name FROM nature WHERE nature_id='".$row['department']."'";
		$res_dep=$db1->query($dep_query);
		$dep_row=$db1->fetch_array($res_dep);
		$department=$dep_row['nature_name'];

		$officer_query="SELECT * FROM con_officer WHERE c_id='".$row['con_officer']."'";
		$res_officer=$db1->query($officer_query);
		$officer_row=$db1->fetch_array($res_officer);
		$con_officer=$officer_row['officer_name'];
		$officer_contno=$officer_row['officer_contno'];
        
		$c_date=date('d-m-Y',$row['c_date']);
		$c_tdate=date('d-m-Y',$row['tdate']);
		$d_date=date('d-m-Y',$row['d_date']);
		if($row['status']==0){
		  $status='Pending';
		}else{
		  $status='Disposed';
		}

		$lines .= $sn."$t ".$row['c_id']."$t".$row['c_name']."$t".$row['c_add']."$t".$row['c_detail']."$t".$department."$t".$con_officer."$t".$officer_contno."$t".$c_date."$t".$c_tdate."$t".$row['c_category']."$t".$row['c_mode']."$t".$d_date."$t".$row['disposaltext']."$t".$status."$cr";	
		$i++;
	}
	}else{
		$msg='No Record Found';
	}
    
	$filename="ComplainantReport".date('dmY').".xls";

	if($_SERVER['SERVER_NAME']=='localhost'){
	 $filePath=$DOCUMENT_ROOT."jnn/excel/".$filename;
	 $fullpath=$DOCUMENT_ROOT."jnn/excel/";
	}else{
	 $filePath=$DOCUMENT_ROOT."/excel/".$filename;
	 $fullpath=$DOCUMENT_ROOT."/excel/";
	}
	$fp = fopen($filePath,"w");
	chmod($filePath,0777);
	if($fp)
	{
		fwrite($fp,$lines); 	
		fclose($fp);  				
	}

	

	return $filename;
         
 }
?>
