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
 

$search=$_REQUEST['s_comp'];
$sdate=$_REQUEST['sdate'];
$edate=$_REQUEST['edate'];
$category=$_REQUEST['category'];

viewcomplainant($db,$search,$sdate,$edate,$category);

if($_SESSION['type']==1)
 {
  $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar.html");
 }
else
 {
   $LEFTBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/leftbar1.html");
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/default_comp.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "LEFTBAR"));
print $TEMPLATE;
flush();

function viewcomplainant($db,$search,$sdate,$edate,$category)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$title,$nid,$cdate,$TEMPLATE_DIR,$NEWS_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$fid,$fname,$sfdesign,$sfpay,$sfadsalary,$faddress,$fcontact,$row_color;
    global   $cid ,$cname,$caddress,$ccontact,$ccno,$cdetails;
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/defa_complainantGrid.html");
                                                    
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
	 
	 $count="select count(c_id) as total from complainant where tdate<='$today' and status='0' $sdate1 $edate1 $category1 $search_key";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from complainant where tdate<='$today' and status='0' $sdate1 $edate1 $category1 $search_key order by c_id desc limit  $page, $MAX ";
    
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
			  $ccontact=stripslashes($rows['c_contno']);
			  $ccno=stripslashes($rows['c_regno']);
			  $cdetails=stripslashes($rows['c_detail']);
	
			  ReplaceContent(Array("S1"));
	   	  $NEWS_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
		 if($page > 0)
			{	
        $prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='default_comp.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	
        $NEXT_PAGE_LINK="<a href='default_comp.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='default_comp.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $NEWS_LIST="<tr><td colspan='7' height='25' valign='middle' align='center'>No Default Complainant Records Found</td></tr>";
    }
   
  return 1;
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

?>
