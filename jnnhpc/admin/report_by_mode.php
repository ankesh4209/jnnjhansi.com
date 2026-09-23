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

$cid = $_REQUEST['cid'];

if($_POST["SUBMIT_DELETE"])
{	
 if (is_array($cid))
	{
		deletecomplainant($cid, $db);
	}
}

if($_GET['d']!="")
 {
   $PROMPT="<tr><td bgcolor='#cac5b6' align='center' valign='middle' height='20'><b>Complain Disposed Successfully</b></td></tr>";
 }
 

selectNature($db);

if($_POST['search']!="")
{
 $sdate=$_REQUEST['sdate'];
 $edate=$_REQUEST['edate'];
 $category=$_REQUEST['nature'];
 $status=$_REQUEST['status'];
 $cmode=$_REQUEST['cmode'];
 $corporator=$_REQUEST['CorporatorId'];
 $wardno=$_REQUEST['WardId'];
 
 viewcomplainant($db,$db1,$sdate,$edate,$category,$status,$cmode,$corporator,$wardno);
 
}

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/report_by_mode.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();

function viewcomplainant($db,$db1,$sdate,$edate,$category,$status,$cmode,$corporator,$wardno)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$title,$nid,$cdate,$TEMPLATE_DIR,$NEWS_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$fid,$fname,$sfdesign,$sfpay,$sfadsalary,$faddress,$fcontact,$row_color;
   global   $cid ,$cname,$caddress,$ccontact,$ccno,$cdetails,$status,$nature_name,$complain_list,$officer_text,$sdate_text,$edate_text,$cmode,$corpname,$ward,$Mode_text;
   
            
	 $p_sdate=$sdate;
	 $p_edate=$edate;
	 $p_category=$category;
	 $p_status=$status;
	 $p_cmode=$cmode;
	 $p_corporator=$corporator;
	 $p_wardno=$wardno;
	 
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
	   if($category=='All')
		{
		   $category1='';
		}
		else
	   $category1="and department ='$category'";

	    if($cmode=='All')
		{
		   $cmode='';
		   $modetype='All';
		}
		else{
	     $cmode="and c_mode='$cmode'";
	     $modetype="<span style='font-family:Kruti Dev 011;font-size:14px;'>".$_REQUEST['cmode']."</span>";
		}
       //$corporator,$wardno
		if($corporator==''){
		  $corporator='';
		}else{
			$corporator="and CorporatorId='$corporator'";
		}

		if($wardno==''){
		  $wardno='';
		}else{
			$wardno="and WardId='$wardno'";
		}
	//------------------------------- for category ----------------------------------------------------------
	 $nature=$_POST['nature'];
	 if($nature=='All'){
	   $nature_name='All';
	 }else{
		$sql_total="select * from nature where nature_id='$nature' order by nature_name asc";
		$row_total=$db1->query($sql_total);
		$res_total=$db1->fetch_array($row_total);
		$nature_name = $res_total['nature_name'];
	 }
    $officer_text="Report For Department : $nature_name";
    $Mode_text="Report For Mode : $modetype";
	  $sdate_text=" From Date : ".date('d-m-Y',$sdate);
	  $edate_text=" From To : ".date('d-m-Y',$edate);
	  
	 $date_diff=$edate-$sdate;
   $date_diff=$date_diff/(24*60*60);
	 
   $query="select * from complainant where 1=1 and ComplainType='0' $status1 $sdate1 $edate1 $category1 $cmode $corporator $wardno";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $row_color = ($slno % 2) ? $color1 : $color2;
		   	$cid=$rows['c_id'];
	 	    $cname=stripslashes($rows['c_name']);
			  $caddress=stripslashes($rows['c_add']);
			  $ccontact=stripslashes($rows['add_phone']);
			  $ccno=stripslashes($rows['c_regno']);
			  $cdetails=stripslashes($rows['c_detail']);
	      $cmode=stripslashes($rows['c_mode']);
	      $wardno=stripslashes($rows['WardId']);
	      $CorporatorId=stripslashes($rows['CorporatorId']);
	      
		  
			 //echo $_POST['cmode'];
			  
				  if($_POST['cmode']=='ik’kZnx.k'){
					            $query1="select * from corporators where CorporatorId='$CorporatorId'";
								$db1->query($query1);
								$rows1 = $db1->fetch_array();

								$CorporatorName=stripslashes($rows1['CorporatorName']);
	      
				                $corpname="<th align='left'>ik’kZn</th>";
				                $ward="<th align='left'><span style='font-family:Kruti Dev 011;font-size:14px'>Ward No</th>";
				                $complain_list.="<tr><td align='left'>$slno</td>
							   <td align='left'>$cid</td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$cname</span></td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$caddress</span></td>
							   <td align='left'>$CorporatorName</td>
							   <td align='left'>$wardno</td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$ccontact</span></td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$cdetails</span></td>
							   <td align='left' style='font-family:Kruti Dev 011;font-size:14px;'>$cmode</td>
							 </tr>";	
			      }else{ 
				               $complain_list.="<tr><td align='left'>$slno</td>
							   <td align='left'>$cid</td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$cname</span></td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$caddress</span></td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$ccontact</span></td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$cdetails</span></td>
							   <td align='left' style='font-family:Kruti Dev 011;font-size:14px;'>$cmode</td>
							 </tr>";
			  }
				$slno++;
			}
			if($p_mode==''){
			$p_mode='All';
			 }
		$complain_list.="<tr><td colspan='9' align='right'><b><a href='javascript: void(0)' onclick='window.open(\"print_report_by_mode.php?nature=$p_category&sdate=$p_sdate&edate=$p_edate&status=$p_status&cmode=$p_cmode&CorporatorId=$p_corporator&WardId=$p_wardno\",\"windowname1\",\"width=650, height=550,scrollbars=yes\")'><input type='button' name='click' value='Print' class='button'></a></b></td></tr>";
			
		}
    
      
 }


function viewAllcomplainant($db,$db1,$sdate,$edate,$category,$status,$cmode,$corporator,$wardno)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$title,$nid,$cdate,$TEMPLATE_DIR,$NEWS_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$fid,$fname,$sfdesign,$sfpay,$sfadsalary,$faddress,$fcontact,$row_color;
   global   $cid ,$cname,$caddress,$ccontact,$ccno,$cdetails,$complain_list,$officer_text,$sdate_text,$edate_text,$c_mode,$Mode_text,$corpname,$ward;
                                                    
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
	   
	   if($category=='All')
		{
		   $category1='';
		}
		else
	   $category1="and department ='$category'";

	    if($cmode=='All')
		{
		   $cmode='';
		   $modetype='All';
		}
		else{
	     $c_mode="and c_mode='$cmode'";
		 $modetype=$_REQUEST['cmode'];
		}
	//------------------------------- for category ----------------------------------------------------------
	$nature=$_POST['nature'];
	if($nature=='All'){
	  $nature_name = 'All';
	}else{
		$sql_total="select * from nature where nature_id='$nature' order by nature_name asc";
		$row_total=$db1->query($sql_total);
		$res_total=$db1->fetch_array($row_total);
		$nature_name = $res_total['nature_name'];
	}		
     
	 $officer_text="Report For Department : $nature_name";
     $Mode_text="Report For Mode : $modetype";
	  $sdate_text=" From Date : ".date('d-m-Y',$sdate);
	  $edate_text=" From To : ".date('d-m-Y',$edate);
	  
	 $date_diff=$edate-$sdate;
   $date_diff=$date_diff/(24*60*60);
	 
	$query="select * from complainant where 1=1 and ComplainType='0' $status1 $sdate1 $edate1 $c_mode";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $row_color = ($slno % 2) ? $color1 : $color2;
		   	$cid=$rows['c_id'];
	 	    $cname=stripslashes($rows['c_name']);
			  $caddress=stripslashes($rows['c_add']);
			  $ccontact=stripslashes($rows['add_phone']);
			  $ccno=stripslashes($rows['c_regno']);
			  $cdetails=stripslashes($rows['c_detail']);
	      $cmode=stripslashes($rows['c_mode']);
	      
			    if($_POST['cmode']=='ik’kZn'){
					            $query1="select * from corporators where 1=1 $status1 $sdate1 $edate1 $category1 $c_mode";
          $db1->query($query1);
		  $rows1 = $db1->fetch_array();

		  $wardno=stripslashes($rows1['WardNo']);
		  $CorporatorName=stripslashes($rows1['CorporatorName']);
	      
				                $corpname="<th align='left'>ik’kZn</th>";
				                $ward="<th align='left'><span style='font-family:Kruti Dev 011;font-size:14px'>Ward No</th>";
				                $complain_list.="<tr><td align='left'>$slno</td>
							   <td align='left'>$cid</td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$cname</span></td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$caddress</span></td>
							   <td align='left'>$CorporatorName</td>
							   <td align='left'>$wardno</td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$ccontact</span></td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$cdetails</span></td>
							   <td align='left' style='font-family:Kruti Dev 011;font-size:14px;'>$cmode</td>
							 </tr>";	
			      }else{
				               $complain_list.="<tr><td align='left'>$slno</td>
							   <td align='left'>$cid</td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$cname</span></td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$caddress</span></td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$ccontact</span></td>
							   <td align='left'><span style='font-family:Kruti Dev 011;font-size:14px;'>$cdetails</span></td>
							   <td align='left' style='font-family:Kruti Dev 011;font-size:14px;'>$cmode</td>
							 </tr>";
			  }
			}
			
		}
    
 }
 
function deletecomplainant($cid, $db)
 {	
	global $PROMPT;
 	$complainant = implode("," ,$cid);
 	$delete = "delete from complainant where c_id in ($complainant)";
	$db->query($delete);
	$total = $db->affected_rows();
	$PROMPT = "Total $total Complainant records have been deleted.";
 }

function selectNature($db)
 {	
	global $c_nature;
  
    $sql="select * from nature order by nature_name asc";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      while($res=$db->fetch_array($row))
       {
         $cid = $res['nature_id'];
         $city = $res['nature_name'];
         $c_nature.="<option value='$cid'>$city</option>";
       }
    }
 }
?>
