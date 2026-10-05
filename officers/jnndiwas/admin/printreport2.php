<?php session_start(); ?>
<?php	

include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$search=$_REQUEST['s_comp'];
$sdate=$_REQUEST['sdate'];
$edate=$_REQUEST['edate'];
$category=$_REQUEST['category'];

if($search!="")
 {
   $search_text="Complaint No. $search";
 }

if($sdate!="")
 {
   $sdate_text="From Date: $sdate";
 }

if($edate!="")
 {
   $edate_text="Till Date. $edate";
 }

if($category!="")
 {
   $category_text="Category. $category";
 }

viewcomplainant($db,$search,$sdate,$edate,$category);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/printreport2.html");

ReplaceContent(Array("PAGE_CONTENTS"));
print $PAGE_CONTENTS;
flush();


function viewcomplainant($db,$search,$sdate,$edate,$category)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$title,$nid,$cdate,$TEMPLATE_DIR,$NEWS_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$fid,$fname,$sfdesign,$sfpay,$sfadsalary,$faddress,$fcontact,$row_color;
    global   $cid ,$cname,$caddress,$ccontact,$ccno,$cdetails;
                                                    
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
	
	//------------------------------- for category ---------------------
	if($category!="")
	 {
    $category1="and c_category='$category'";
   }
  else
   {
    $category1="";
   }
	
	//------------------------------ complaint No  ----------------------------
	 if($search!="")
	  {
      $search_key="and c_id='$search'";
    }
   else
    {
      $search_key="";
    }
  
   $today=mktime(0,0,0,date('m'),date('d'),date('Y'));
   
   $query="select * from complainant where tdate>='$today' and status='0' $search_key $sdate1 $edate1 $category1 ";
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
		  $NEWS_LIST.="<table cellspacing='3' cellpadding='3' width='100%'>";
		  $NEWS_LIST.=" <tr>
                       <td>S.N.</td>
                       <td>Name</td>
                       <td>Address</td>
                       <td>Contact</td>
                       <td>Comp No</td>
                       <td>Details</td>
                      </tr>";
			while($rows = $db->fetch_array())
			{
			  $row_color = ($slno % 2) ? $color1 : $color2;
		   	$cid=$rows['c_id'];
	 	    $cname=stripslashes($rows['c_name']);
			  $caddress=stripslashes($rows['c_add']);
			  $ccontact=stripslashes($rows['c_contno']);
			  $ccno=stripslashes($rows['c_regno']);
			  $cdetails=stripslashes($rows['c_detail']);
	
	   	  $NEWS_LIST.="<tr>
                       <td>$slno</td>
                       <td style='font-family:Kruti Dev 011;font-size:20px;'>$cname</td>
                       <td style='font-family:Kruti Dev 011;font-size:20px;'>$caddress</td>
                       <td>$ccontact</td>
                       <td align='center'>$cid</td>
                       <td style='font-family:Kruti Dev 011;font-size:20px;'>$cdetails</td>
                      </tr>";

				$slno++;
			
			}
		 $NEWS_LIST.="</table>";
   }
  return 1;
 }
?>
