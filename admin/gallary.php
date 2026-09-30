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

$pid = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : (isset($_REQUEST['id']) ? [$_REQUEST['id']] : []);
if (!is_array($pid) && !empty($pid)) {
    $pid = [$pid];
}

if (!empty($_POST["SUBMIT_DELETE"]) || (isset($_GET['action']) && $_GET['action'] == 'delete'))
{	
    if (!empty($pid) && is_array($pid))
	{
		deleteaboutus($pid, $db);
	}
}

if($_POST["SUBMIT_CHANGE"])
{	
 if (is_array($pid))
	{
		ChangeStatus($pid, $db);
	}
}

viewpage($db);

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_on";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/gallary.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$pid,$photo,$photo_title,$description,$DOCUMENT_ROOT,$Upload_Date;
   global $SEARCH_VAL,$STATUS_ACTIVE_SEL,$STATUS_INACTIVE_SEL,$DATE_VAL;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/gallaryDisplayGrid.html");

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
	     $where_clauses[] = "(photo_name LIKE '%$clean_search%' OR g_id = '$clean_search')";
	     $params['search'] = $search;
	 }
	 if ($filter_status !== '') {
	     $clean_status = addslashes($filter_status);
	     $where_clauses[] = "status = '$clean_status'";
	     $params['filter_status'] = $filter_status;
	 }
	 if ($filter_date !== '') {
	     $clean_date = addslashes($filter_date);
	     $date_conditions = ["AddedDate LIKE '%$clean_date%'"];
	     $ts = strtotime(str_replace('/', '-', $filter_date));
	     if ($ts) {
	         $d_m_Y = date('d-m-Y', $ts);
	         $d_sl_m_Y = date('d/m/Y', $ts);
	         $Y_m_d = date('Y-m-d', $ts);
	         foreach ([$d_m_Y, $d_sl_m_Y, $Y_m_d] as $fmt) {
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
	 
	 $count="select count(g_id) as total from tbl_gallary $where_sql";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from tbl_gallary $where_sql order by g_id DESC limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $pid=$rows['g_id'];
			    $photoName = $rows['photo_name'];
			    if (!empty($photoName) && file_exists("../pic/thumb/" . $photoName)) {
				    $imgSrc = "../pic/thumb/" . $photoName;
			    } elseif (!empty($photoName) && file_exists("../pic/" . $photoName)) {
				    $imgSrc = "../pic/" . $photoName;
			    } else {
				    $imgSrc = "../images/logo.png";
			    }
			    $photo = "<img src='{$imgSrc}' alt='Gallery Photo' style='width:90px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #E2E8F0; box-shadow:0 2px 4px rgba(0,0,0,0.06);'>";
			    $photo_title = htmlspecialchars(!empty($rows['photo_title']) ? $rows['photo_title'] : $photoName);
			    
			    $rawDate=$rows['AddedDate'] ?? '';
			    if (!empty($rawDate) && $rawDate != '0000-00-00') {
				    $ts = strtotime(str_replace('/', '-', $rawDate));
				    $Upload_Date = $ts ? date('d-M-Y', $ts) : htmlspecialchars($rawDate);
			    } else {
				    $Upload_Date = '-';
			    }
			       
			  $status=$rows['status'];
			  if($status==1)
				{ $status='<span class="badge" style="background:#DEF7EC; color:#03543F; padding:4px 8px; border-radius:4px; font-size:11.5px; font-weight:600;">Live</span>';}
			  else
				{ $status='<span class="badge" style="background:#FDE8E8; color:#9B1C1C; padding:4px 8px; border-radius:4px; font-size:11.5px; font-weight:600;">Draft</span>';}
			  
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage=$page - $MAX;
				$PREV_PAGE_LINK="<<a href='gallary.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='gallary.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='gallary.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='5'>No Image Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteaboutus($pid, $db)
 {	
	global $PROMPT, $APP_ROOT;

	$baseDir = ($APP_ROOT ?? (dirname(__DIR__) . '/')) . 'pic/';
	$cleanPids = array_map('intval', $pid);
	$pids = implode(",", $cleanPids);
	if (empty($pids)) return;

	$res = $db->query("select photo_name from tbl_gallary where g_id in ($pids)");
	while ($rows = $db->fetch_array($res)) {
		$photo = $rows['photo_name'];
		if (!empty($photo)) {
			@unlink($baseDir . 'thumb/' . $photo);
			@unlink($baseDir . 'thumb/thumb_' . $photo);
			@unlink($baseDir . $photo);
		}
	}
	
 	$delete = "delete from tbl_gallary where g_id in ($pids)";
	$db->query($delete);

	$total = $db->affected_rows();
	$PROMPT = "Total $total Image(s) have been deleted.";
 }

function ChangeStatus($pid, $db)
{
  
   global $PROMPT;

	$product = implode(",", $pid);
    $statusid=$_POST['status'];
 	$change = "update tbl_gallary set status='$statusid' where g_id in ($product)";
	$db->query($change);

	$total = $db->affected_rows();

	$PROMPT = "Total $total Image's status have been changed.";

}
?>
