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


 $sdate=$_REQUEST['sdate'];
 $edate=$_REQUEST['edate'];
 $category=$_REQUEST['dep'];
 $status=$_REQUEST['status'];
 
 viewcomplainant($db,$db1,$sdate,$edate,$category,$status);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/monthly_reportsprint.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();

function viewcomplainant($db,$db1,$sdate,$edate,$category,$status)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$title,$nid,$cdate,$TEMPLATE_DIR,$NEWS_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$fid,$fname,$sfdesign,$sfpay,$sfadsalary,$faddress,$fcontact,$row_color;
   global   $cid ,$cname,$caddress,$ccontact,$ccno,$cdetails,$status,$nature_name,$complain_list,$officer_text,$sdate_text,$edate_text,$c_mode;
   
                                                    
	 $CURRENT_PAGE_NO=0;                               
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	  $sql_total="select * from nature where nature_id='$category' order by nature_name asc";
    $row_total=$db1->query($sql_total);
    $res_total=$db1->fetch_array($row_total);
    $nature_name = $res_total['nature_name'];
    
    $officer_text="Report For : $nature_name";
	  $sdate_text=" From Date : ".date('d-m-Y',$sdate);
	  $edate_text=" From To : ".date('d-m-Y',$edate);
	  
	  $today_date=mktime(0,0,0,date('m'),date('d'),date('Y'));
	  
	  if($status==0){
       $status1="and status=0";
     } else if($status==1){
       $status1="and status=1"; 
     } else if($status==2) {
       $status1="and status=0 and tdate<='$today_date'";
     } else {
       $status1="";
     }
     
	 
	 $query="select * from complainant where department='$category' and c_date>='$sdate' and c_date<='$edate' $status1 order by c_id desc";
   $db->query($query);
		if($db->num_rows())
		{   $slno=1;
			while($rows = $db->fetch_array())
			{
			  $comp_no=$rows['c_id'];
			  $name=$rows['c_name'];
			  $rdate=date('d-m-Y',$rows['c_date']);
			  $tdate=date('d-m-Y',$rows['tdate']);
			  $address=$rows['c_add'];
			  $summry=$rows['c_detail'];
			  $medium=$rows['c_mode'];

			  if($status==0)
			   {
           $sattus1="Pending";
         } else if($status==1) {
           $sattus1="Disposed";
         } else if($status==2) {
           $sattus1="Default";
         } else {
           $sattus1="";
         }
		   $complain_list.="<tr>
                           <td align='left' align='left' style='padding-left:5px;' width='5%'>$slno</td>
                           <td align='left' align='left' style='padding-left:5px;' width='10%'>$comp_no</td>
                           <td align='left' width='10%'><span style='font-family:Kruti Dev 011;font-size:14px;'>$name</span></td>
                           <td align='left' width='10%'>$rdate</td>
                           <td align='left' width='10%'>$tdate</td>
                           <td align='left' width='10%'><span style='font-family:Kruti Dev 011;font-size:14px;'>$address</span></td>
                           <td align='left' width='30%'><span style='font-family:Kruti Dev 011;font-size:14px;'>$summry</span></td>
                           <td align='left' width='10%'>$medium</td>
                           <td align='left' width='10%'>$sattus1</td>
                           </tr>";
			
		  
		  $slno++;
		  }
		}
		else
	 {
			$complain_list.="<tr><td align='center' colspan='8'>No Complaint found</td></tr>";	
	 }

 }

?>
