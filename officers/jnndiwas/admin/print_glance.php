<?php session_start(); ?>
<?php	

include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

 $sdate=$_REQUEST['sdate'];
$edate=$_REQUEST['edate'];
$category=$_REQUEST['zone'];

 viewAllcomplainant($db,$db1,$sdate,$edate,$category);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/print_glance.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();


function viewAllcomplainant($db,$db1,$sdate,$edate,$category)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$title,$nid,$cdate,$TEMPLATE_DIR,$NEWS_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$fid,$fname,$sfdesign,$sfpay,$sfadsalary,$faddress,$fcontact,$row_color;
   global   $cid ,$cname,$caddress,$ccontact,$ccno,$cdetails,$complain_list,$officer_text,$sdate_text,$edate_text,$c_mode;
   
                                                    
	 $CURRENT_PAGE_NO=0;                               
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $page = $_GET['page'];
	 
	 if(!($page)) 
	 $page = 0 ;
	 $MAX=10;
	 $lastrow=$MAX+$page;
	  $color1="#6495ED";
	  $color2="#F0E68C";
	  
	  //----------------------------- for date -----------------------  
	 if($sdate!="")
	  {
	    $sdate=explode("/",$sdate);
	    $sdate=mktime(0,0,0,$sdate['1'],$sdate['0'],$sdate['2']);
      $sdate1="and c_date>='$sdate'";
    }
   else
    {
      $sdate1="";
    }
    
  if($edate!="")
	  {
	    $edate=explode("/",$edate);
	    $edate=mktime(0,0,0,$edate['1'],$edate['0'],$edate['2']);
      $edate1="and c_date<='$edate'";
    }
   else
    {
      $edate1="";
    }
	  if($status==1000)
	   $status1="";
	  else
	   $status1="and status =$status";
	   
	   if($category=='')
		   $category1='';
		else
	   $category1="and department ='$category'";

	    if($c_mode=='')
		   $c_mode='';
		 else
	     $c_mode="and c_mode='$cmode'";
	  
	  $today_date=mktime(0,0,0,date('m'),date('d'),date('Y'));
    if($sdate!=""){
      $default_condition1="and tdate<='$today_date'";
    } else {
	    $default_condition1="";
	  }
  //------------------------------- for category ----------------------------------------------------------
	  $nature=$_POST['nature'];
	 
	  $sql_total="select * from nature where nature_id='$nature' order by nature_name asc";
    $row_total=$db1->query($sql_total);
    $res_total=$db1->fetch_array($row_total);
    $nature_name = $res_total['nature_name'];
    
    $officer_text="Report For Nature : $nature_name";
	  $sdate_text=" From Date : ".date('d-m-Y',$sdate);
	  $edate_text=" From To : ".date('d-m-Y',$edate);
	  
	 $date_diff=$edate-$sdate;
   $date_diff=$date_diff/(24*60*60);
	 
	$GTotal=0;
	$GPending=0;
	$GDisposed=0;
	$GDefault=0;
   $sql_dep="select * from nature order by nature_name asc";  
   $db->query($sql_dep);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  
			  $dep_name=$rows['nature_name'];
			  $total_query="select count(*) as total from complainant where 1=1 and department='$rows[nature_id]' $sdate1 $edate1 ";
			
			  $pending_query="select  count(*) as pending  from complainant where 1=1 and status='0' and department='$rows[nature_id]' $sdate1 $edate1";

			  $dispose_query="select  count(*) as dispose from complainant where 1=1 and status='1' and department='$rows[nature_id]' $sdate1 $edate1";
			  
			  $default_query="select  count(*) as totaldefault from complainant where 1=1 and status='0' and department='$rows[nature_id]' $sdate1 $edate1 $default_condition1";
			  
			  $row_total=$db1->query($total_query);
			  $res_total=$db1->fetch_array($row_total);
			  $total= $res_total['total'];

			  $row_pending=$db1->query($pending_query);
			  $res_pending=$db1->fetch_array($row_pending);
			  $pending= $res_pending['pending'];


			  $row_dispose=$db1->query($dispose_query);
			  $res_dispose=$db1->fetch_array($row_dispose);
			  $dispose= $res_dispose['dispose'];
			  
			  $row_default=$db1->query($default_query);
			  $res_default=$db1->fetch_array($row_default);
			  $default= $res_default['totaldefault'];


			  $row_color = ($slno % 2) ? $color1 : $color2;
		   	$cid=$rows['c_id'];
	 	   /* $cname=stripslashes($rows['c_name']);
			  $caddress=stripslashes($rows['c_add']);
			  $ccontact=stripslashes($rows['c_contno']);
			  $ccno=stripslashes($rows['c_regno']);
			  $cdetails=stripslashes($rows['c_detail']);
	      $cmode=stripslashes($rows['c_mode']);
	      
			  $complain_list.="<tr>
                           <td align='center'>$cid</td>
                           <td align='center'><span style='font-family:Kruti Dev 011;font-size:14px;'>$cname</span></td>
                           <td align='center'><span style='font-family:Kruti Dev 011;font-size:14px;'>$caddress</span></td>
                           <td align='center'><span style='font-family:Kruti Dev 011;font-size:14px;'>$ccontact</span></td>
                           <td align='center'><span style='font-family:Kruti Dev 011;font-size:14px;'>$cdetails</span></td>
                           <td align='center'>$cmode</td>
                         </tr>";*/

				$complain_list.="<tr>
                           <td align='center' align='left' style='padding-left:10px;'>$dep_name</td>
                           <td align='center'>$total</td>
                           <td align='center'>$pending</td>
                           <td align='center'>$dispose</td>
                          
                           </tr>";
			$GTotal+=$total;
			$GPending+=$pending;
			$GDisposed+=$dispose;
			$GDefault+=$default;
		}
			$complain_list.="<tr>
                           <td align='center' align='left' style='padding-left:10px;'>&nbsp;</td>
                           <td align='center'><b>Total:&nbsp;$GTotal</b></span></td>
                           <td align='center'><b>Total:&nbsp;$GPending</b></td>
                           <td align='center'><b>Total:&nbsp;$GDisposed</b></td>
						  
                           </tr>";
		}
    
 }


 
 
?>
