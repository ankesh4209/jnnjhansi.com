<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
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
	 
		deleteworks($cid, $db);
	}
}

if($_POST["SUBMIT_CHANGE"])
{	
 if (is_array($cid))
	{
		ChangeStatus($cid, $db);
	}
}

viewwork($db);

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_on";
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/work_info.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewwork($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$Work_Id,$Work_Name,$Work_Photo,$Work_Desc,$w_image,$Work_Date;
   global $SEARCH_VAL,$STATUS_ACTIVE_SEL,$STATUS_INACTIVE_SEL,$DATE_VAL;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/work_infoGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $search = trim($_REQUEST['search'] ?? '');
	 $filter_status = isset($_REQUEST['filter_status']) && strlen($_REQUEST['filter_status']) > 0 ? (string)$_REQUEST['filter_status'] : '';
	 $filter_date = trim($_REQUEST['filter_date'] ?? '');

	 $where_clauses = [];
	 $params = [];

	 if ($search !== '') {
	     $clean_search = addslashes($search);
	     $where_clauses[] = "(Work_Name LIKE '%$clean_search%' OR Work_Desc LIKE '%$clean_search%' OR Work_Id = '$clean_search')";
	     $params['search'] = $search;
	 }
	 if ($filter_status !== '') {
	     $clean_status = addslashes($filter_status);
	     $where_clauses[] = "Status = '$clean_status'";
	     $params['filter_status'] = $filter_status;
	 }
	 if ($filter_date !== '') {
	     $clean_date = addslashes($filter_date);
	     $ts = strtotime(str_replace('/', '-', $filter_date));
	     $date_conditions = ["AddedDate LIKE '%$clean_date%'"];
	     if ($ts) {
	         $m_d_y = date('m/d/Y', $ts);
	         $d_m_y = date('d/m/Y', $ts);
	         $Y_m_d = date('Y-m-d', $ts);
	         $d_m_Y = date('d-m-Y', $ts);
	         foreach ([$m_d_y, $d_m_y, $Y_m_d, $d_m_Y] as $fmt) {
	             $date_conditions[] = "AddedDate LIKE '%$fmt%'";
	         }
	     }
	     $where_clauses[] = "(" . implode(" OR ", array_unique($date_conditions)) . ")";
	     $params['filter_date'] = $filter_date;
	 }

	 $where_sql = count($where_clauses) ? ' WHERE ' . implode(' AND ', $where_clauses) : '';
	 $query_string = http_build_query($params);
	 $next_links = $query_string ? '&' . $query_string : '';

	 $SEARCH_VAL = htmlspecialchars($search);
	 $STATUS_ACTIVE_SEL = ($filter_status === '1') ? 'selected' : '';
	 $STATUS_INACTIVE_SEL = ($filter_status === '0') ? 'selected' : '';
	 $DATE_VAL = htmlspecialchars($filter_date);

	 $page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
	 if(!($page) || $page < 0) 
	   $page = 0 ;
	 $MAX=10;
	 $lastrow=$MAX+$page;
	 
	 $count="select count(Work_Id) as total from jnnworks $where_sql";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from jnnworks $where_sql order by Work_Id DESC limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $Work_Id=$rows['Work_Id'];
			  $Work_Desc=stripslashes($rows['Work_Desc']);
			  $Work_Desc=substr($Work_Desc,0,30);
			  $Work_Name=$rows['Work_Name'];
			 	 
			  $status=$rows['Status'];
			  $photoName = $rows['Work_Photo'];
			  if (!empty($photoName) && file_exists("../w_images/thumbs/" . $photoName)) {
				  $imgSrc = "../w_images/thumbs/" . $photoName;
			  } elseif (!empty($photoName) && file_exists("../w_images/" . $photoName)) {
				  $imgSrc = "../w_images/" . $photoName;
			  } else {
				  $imgSrc = "../images/logo.png";
			  }
			  $w_image = "<img src='{$imgSrc}' alt='Work Photo' style='width:90px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #E2E8F0; box-shadow:0 2px 4px rgba(0,0,0,0.06);'>";
			  $rawDate = $rows['AddedDate'] ?? '';
			  if (!empty($rawDate) && $rawDate != '0000-00-00') {
				  $ts = strtotime(str_replace('/', '-', $rawDate));
				  $Work_Date = $ts ? date('d-M-Y', $ts) : htmlspecialchars($rawDate);
			  } else {
				  $Work_Date = '-';
			  }

			  if($status==1)
				{ $status='<span class="badge" style="background:#DEF7EC; color:#03543F; padding:4px 8px; border-radius:4px; font-size:11.5px; font-weight:600;">Live</span>';}
			  else
				{$status='<span class="badge" style="background:#FDE8E8; color:#9B1C1C; padding:4px 8px; border-radius:4px; font-size:11.5px; font-weight:600;">Draft</span>';}
			  
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='works.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='works.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='works.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='5'>No JNN work(s) found</td></tr>";
    }
   
  return 1;
 }
 
function deleteworks($cid, $db)
 {	
	global $PROMPT,$DOCUMENT_ROOT;

	foreach($cid as $value)
	{
      $query="select * from jnnworks where Work_Id='$value'";
	  $db->query($query);
	  $rows = $db->fetch_array();
	  $w_image=$rows['Work_Photo'];
	  if($_SERVER['SERVER_NAME']=='localhost')
		{
		  @unlink($DOCUMENT_ROOT.'jnnweb/w_images/thumbs/'.$w_image);
	      @unlink($DOCUMENT_ROOT.'jnnweb/w_images/'.$w_image);
		}
		else
		{
			
			@unlink($DOCUMENT_ROOT.'/w_images/thumbs/'.$w_image);
	        @unlink($DOCUMENT_ROOT.'/w_images/'.$w_image);
		}
	 
	}

	$cids= implode(",", $cid);

 	$delete = "delete from jnnworks where Work_Id in ($cids)";
	$db->query($delete);

	$total = $db->affected_rows();

	$PROMPT = "Total $total works's have been deleted.";
 }

function ChangeStatus($cid, $db)
{
  
   global $PROMPT;

	$cids = implode(",", $cid);
    $statusid=$_POST['status'];
 	$change = "update jnnworks set Status='$statusid' where Work_Id in ($cids)";
	$db->query($change);

	$total = $db->affected_rows();

	$PROMPT = "Total $total works's status have been changed.";

}
?>
