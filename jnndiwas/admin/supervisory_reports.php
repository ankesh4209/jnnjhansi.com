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


 
viewAllcomplainant($db,$db1);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/supervisory_reports.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();

function viewAllcomplainant($db,$db1)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$title,$nid,$cdate,$TEMPLATE_DIR,$NEWS_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$fid,$fname,$sfdesign,$sfpay,$sfadsalary,$faddress,$fcontact,$row_color;
   global   $cid ,$cname,$caddress,$ccontact,$ccno,$cdetails,$complain_list,$officer_text,$sdate_text,$edate_text,$heading_text;
   
                                                    
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
	  $heading_text="Name";
	  //----------------------------- for date -----------------------  
	 if($sdate!="")
	  {
	    $sdate=explode("/",$sdate);
	    $sdate=mktime(0,0,0,$sdate['1'],$sdate['0'],$sdate['2']);
    }
    
  if($edate!="")
	  {
	    $edate=explode("/",$edate);
	    $edate=mktime(0,0,0,$edate['1'],$edate['0'],$edate['2']);
      //$edate1="and c_date<='$edate'";
    }
   else
    {
      $edate=mktime(0,0,0,date('m'),date('d'),date('Y'));
    }
	
	//------------------------------- for category ---------------------
	  //$off_name=$_POST['super_off'];
	 
	  //$sql_total="select * from officer where o_id='$off_name' order by off_desi asc";
    //$row_total=$db1->query($sql_total);
    //$res_total=$db1->fetch_array($row_total);
    //$officer = $res_total['off_desi'];
    
    $officer_text="Report For Supervisory : All";
	  $sdate_text=" From Date : ".date('d-m-Y',$sdate);
	  $edate_text=" From To : ".date('d-m-Y',$edate);
	  
	 $date_diff=$edate-$sdate;
   $date_diff=$date_diff/(24*60*60);
	 
	 //$today=mktime(0,0,0,date('m'),date('d'),date('Y'));
	 
	 //--------------------------- Total Report ---------------------------------------------------------------
	 
     //$search_date=$sdate+($i*(24*60*60));
     $query21="select count(c_id) as total from complainant ";
     $res21=$db->query($query21);
     $row21 = $db->fetch_assoc();
     $TOTAL_COMPLAINT += $row21['total'];
   
	   $query22="select count(c_id) as total from complainant where status='1'";
     $res22=$db->query($query22);
     $row22 = $db->fetch_assoc();
     $TOTAL_DISPOSED += $row22['total'];
   
    
     //$query23="select count(c_id) as total from complainant where tdate>='$sdate' and status='0' and tdate<='$edate'";
     //$res23=$db->query($query23);
     //$row23 = $db->fetch_assoc();
     //$TOTAL_PENDING += $row23['total'];
    
     //$query24="select count(c_id) as total from complainant where tdate<'$sdate' and status='0'";
     //$res24=$db->query($query24);
     //$row24 = $db->fetch_assoc();
     //$TOTAL_DEFAULT += $row24['total'];
     $TOTAL_PENDING=$TOTAL_COMPLAINT-$TOTAL_DISPOSED;

    
	 
	 //--------------------------- End Total Report --------------------------------------------
	 $s=1;
	 $sql="select * from con_officer";
	 $res=$db1->query($sql);
	 while($rows=$db1->fetch_array($res))
   {
     $off_name=$rows['c_id'];
     $offname=$rows['off_desi'];
   //------------------------------- query for search Complain--------------------------------------
   $query1="select count(c_id) as total from complainant where  c_category='A' and con_officer='$off_name' ";
   $res1=$db->query($query1);
   $row1 = $db->fetch_assoc();
   $TOTAL_RECORDSET1 = $row1['total'];
   if($TOTAL_RECORDSET1>0)
    { $TOTAL_A=$TOTAL_RECORDSET1; }
   else
    { $TOTAL_A="0"; }
   
	 $query2="select count(c_id) as total from complainant where c_category='B' and con_officer='$off_name' ";
   $res2=$db->query($query2);
   $row2 = $db->fetch_assoc();
   $TOTAL_RECORDSET2 = $row2['total'];
   if($TOTAL_RECORDSET2>0)
    { $TOTAL_B=$TOTAL_RECORDSET2; }
   else
    { $TOTAL_B="0"; }
    
   $query3="select count(c_id) as total from complainant where c_category='C' and con_officer='$off_name' ";
   $res3=$db->query($query3);
   $row3 = $db->fetch_assoc();
   $TOTAL_RECORDSET3 = $row3['total'];
   if($TOTAL_RECORDSET3>0)
    { $TOTAL_C=$TOTAL_RECORDSET3; }
   else
    { $TOTAL_C="0"; }
    
   /*$query4="select count(c_id) as total from complainant where  c_category='D' and con_officer='$off_name' and c_date>='$sdate' and c_date<='$edate'";
   $res4=$db->query($query4);
   $row4 = $db->fetch_assoc();
   $TOTAL_RECORDSET4 = $row4['total'];
   if($TOTAL_RECORDSET4>0)
    { $TOTAL_D=$TOTAL_RECORDSET4; }
   else
    { $TOTAL_D="0"; }
    
   $query5="select count(c_id) as total from complainant where  c_category='T' and con_officer='$off_name' and c_date>='$sdate' and c_date<='$edate'";
   $res5=$db->query($query5);
   $row5 = $db->fetch_assoc();
   $TOTAL_RECORDSET5 = $row5['total'];
   if($TOTAL_RECORDSET5>0)
    {  $TOTAL_T=$TOTAL_RECORDSET5; }
   else
    {  $TOTAL_T="0"; }*/
    
    //-------------------------------------- query for search Complain------------------------------------
    
   //--------------------------------------- query for search disposed Complain--------------------------------------
   $query6="select count(c_id) as total from complainant where status='1' and c_category='A' and con_officer='$off_name' ";
   $res6=$db->query($query6);
   $row6 = $db->fetch_assoc();
   $TOTAL_RECORDSET6 = $row6['total'];
   if($TOTAL_RECORDSET6>0)
    { $TOTAL_DIS_A=$TOTAL_RECORDSET6; }
   else
    { $TOTAL_DIS_A="0"; }
   
	 $query7="select count(c_id) as total from complainant where status='1' and c_category='B' and con_officer='$off_name'";
   $res7=$db->query($query7);
   $row7 = $db->fetch_assoc();
   $TOTAL_RECORDSET7 = $row7['total'];
   if($TOTAL_RECORDSET7>0)
    { $TOTAL_DIS_B=$TOTAL_RECORDSET7; }
   else
    { $TOTAL_DIS_B="0"; }
    
   $query8="select count(c_id) as total from complainant where status='1' and c_category='C' and con_officer='$off_name'";
   $res8=$db->query($query8);
   $row8 = $db->fetch_assoc();
   $TOTAL_RECORDSET8 = $row8['total'];
   if($TOTAL_RECORDSET8>0)
    { $TOTAL_DIS_C=$TOTAL_RECORDSET8; }
   else
    { $TOTAL_DIS_C="0"; }
    
  /* $query9="select count(c_id) as total from complainant where status='1' and c_category='D' and con_officer='$off_name' and d_date>='$sdate' and d_date<='$edate'";
   $res9=$db->query($query9);
   $row9 = $db->fetch_assoc();
   $TOTAL_RECORDSET9 = $row9['total'];
   if($TOTAL_RECORDSET9>0)
    { $TOTAL_DIS_D=$TOTAL_RECORDSET9; }
   else
    { $TOTAL_DIS_D="0"; }
    
   $query10="select count(c_id) as total from complainant where status='1' and c_category='T' and con_officer='$off_name' and d_date>='$sdate' and d_date<='$edate'";
   $res10=$db->query($query10);
   $row10 = $db->fetch_assoc();
   $TOTAL_RECORDSET10 = $row10['total'];
   if($TOTAL_RECORDSET10>0)
    { $TOTAL_DIS_T=$TOTAL_RECORDSET10; }
   else
    { $TOTAL_DIS_T="0"; }*/
    
    //-------------------------------------- query for search disposed Complain------------------------------------
    
    //--------------------------------------- query for search Pending Complain--------------------------------------
    $TOTAL_PEN_A=$TOTAL_A-$TOTAL_DIS_A;
    $TOTAL_PEN_B=$TOTAL_B-$TOTAL_DIS_B;
    $TOTAL_PEN_C=$TOTAL_C-$TOTAL_DIS_C;
   /*$query11="select count(c_id) as total from complainant where status='0' and c_category='A' and con_officer='$off_name' and tdate>='$sdate' and tdate<='$edate'";
   $res11=$db->query($query11);
   $row11 = $db->fetch_assoc();
   $TOTAL_RECORDSET11 = $row11['total'];
   if($TOTAL_RECORDSET11>0)
    { $TOTAL_PEN_A=$TOTAL_RECORDSET11; }
   else
    { $TOTAL_PEN_A="0"; }
   
	 $query12="select count(c_id) as total from complainant where status='0' and c_category='B' and con_officer='$off_name' and tdate>='$sdate' and tdate<='$edate'";
   $res12=$db->query($query12);
   $row12 = $db->fetch_assoc();
   $TOTAL_RECORDSET12 = $row12['total'];
   if($TOTAL_RECORDSET12>0)
    { $TOTAL_PEN_B=$TOTAL_RECORDSET12; }
   else
    { $TOTAL_PEN_B="0"; }
    
   $query13="select count(c_id) as total from complainant where status='0' and c_category='C' and con_officer='$off_name' and tdate>='$sdate' and tdate<='$edate'";
   $res13=$db->query($query13);
   $row13 = $db->fetch_assoc();
   $TOTAL_RECORDSET13 = $row13['total'];
   if($TOTAL_RECORDSET13>0)
    { $TOTAL_PEN_C=$TOTAL_RECORDSET13; }
   else
    { $TOTAL_PEN_C="0"; }
    
   $query14="select count(c_id) as total from complainant where status='0' and c_category='D' and con_officer='$off_name' and tdate>='$sdate' and tdate<='$edate'";
   $res14=$db->query($query14);
   $row14 = $db->fetch_assoc();
   $TOTAL_RECORDSET14 = $row14['total'];
   if($TOTAL_RECORDSET14>0)
    { $TOTAL_PEN_D=$TOTAL_RECORDSET14; }
   else
    { $TOTAL_PEN_D="0"; }
    
   $query15="select count(c_id) as total from complainant where status='0' and c_category='T' and con_officer='$off_name' and tdate>='$sdate' and tdate<='$edate'";
   $res15=$db->query($query15);
   $row15 = $db->fetch_assoc();
   $TOTAL_RECORDSET15 = $row15['total'];
   if($TOTAL_RECORDSET15>0)
    { $TOTAL_PEN_T=$TOTAL_RECORDSET15; }
   else
    { $TOTAL_PEN_T="0"; }*/
    
    //-------------------------------------- query for search Pending Complain------------------------------------
    
    //--------------------------------------- query for search Default Complain--------------------------------------
   /*$query16="select count(c_id) as total from complainant where status='0' and c_category='A' and con_officer='$off_name' and tdate >'$sdate'";
   $res16=$db->query($query16);
   $row16 = $db->fetch_assoc();
   $TOTAL_RECORDSET16 = $row16['total'];
   if($TOTAL_RECORDSET16>0)
    { $TOTAL_DEF_A=$TOTAL_RECORDSET16; }
   else
    { $TOTAL_DEF_A="0"; }
   
	 $query17="select count(c_id) as total from complainant where status='0' and c_category='B' and con_officer='$off_name' and tdate >'$sdate'";
   $res17=$db->query($query17);
   $row17 = $db->fetch_assoc();
   $TOTAL_RECORDSET17 = $row17['total'];
   if($TOTAL_RECORDSET17>0)
    { $TOTAL_DEF_B=$TOTAL_RECORDSET17; }
   else
    { $TOTAL_DEF_B="0"; }
    
   $query18="select count(c_id) as total from complainant where status='0' and c_category='C' and con_officer='$off_name' and tdate >'$sdate'";
   $res18=$db->query($query18);
   $row18 = $db->fetch_assoc();
   $TOTAL_RECORDSET18 = $row18['total'];
   if($TOTAL_RECORDSET18>0)
    { $TOTAL_DEF_C=$TOTAL_RECORDSET18; }
   else
    { $TOTAL_DEF_C="0"; }
    
   $query19="select count(c_id) as total from complainant where status='0' and c_category='D' and con_officer='$off_name' and tdate >'$sdate'";
   $res19=$db->query($query19);
   $row19 = $db->fetch_assoc();
   $TOTAL_RECORDSET19 = $row19['total'];
   if($TOTAL_RECORDSET19>0)
    { $TOTAL_DEF_D=$TOTAL_RECORDSET19; }
   else
    { $TOTAL_DEF_D="0"; }
    
   $query20="select count(c_id) as total from complainant where status='0' and c_category='T' and con_officer='$off_name' and tdate >'$sdate'";
   $res20=$db->query($query20);
   $row20 = $db->fetch_assoc();
   $TOTAL_RECORDSET20 = $row20['total'];
   if($TOTAL_RECORDSET20>0)
    { $TOTAL_DEF_T=$TOTAL_RECORDSET20; }
   else
    { $TOTAL_DEF_T="0"; }*/
    
    //-------------------------------------- query for search default Complain------------------------------------
  
   
     $complain_list.="<tr >
                     <td align='center'>$s</td>
                     <td align='center'>$offname</td>
                     <td align='center' bgcolor='#FFFFFF'>&nbsp;$TOTAL_A&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$TOTAL_B&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$TOTAL_C</td>
                     <td align='center' bgcolor='#1fa30d'>&nbsp;$TOTAL_DIS_A&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$TOTAL_DIS_B&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$TOTAL_DIS_C</td>
                     <td align='center' bgcolor='#ffff00'>&nbsp;$TOTAL_PEN_A&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$TOTAL_PEN_B&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$TOTAL_PEN_C</td>   
                   </tr>";
                   
      $s++;
                   
    }
    
    $complain_list.="<tr>
                     <td align='center'></td>
                     <td align='center'></td>
                     <td align='center'><b>Total : $TOTAL_COMPLAINT</b></td>
                     <td align='center'><b>Total : $TOTAL_DISPOSED</b></td>
                     <td align='center'><b>Total : $TOTAL_PENDING</b></td>   
                   </tr>";
    
 }
 
?>
