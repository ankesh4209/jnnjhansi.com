<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php 
if (empty($_SESSION['user_name'])) {	
    header("Location: index.php"); 				
    exit;
}
?>

<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");


$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$pid = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : (isset($_REQUEST['id']) ? [$_REQUEST['id']] : []);
if (!is_array($pid) && !empty($pid)) {
    $pid = [$pid];
}

$class1="leftab_on";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

if (!empty($_POST["SUBMIT_DELETE"]) || (isset($_GET['action']) && $_GET['action'] == 'delete'))
{	
    if (!empty($pid) && is_array($pid))
	{
		deletePdf($pid, $db);
	}
}

if($_POST["SUBMIT_CHANGE"])
{	
 if (is_array($pid))
	{
		ChangeStatus($pid, $db);
	}
}

viewPdf($db);
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/tender_info.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewPdf($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$Pdf_Id,$Pdf_Name,$Pdf_Desc,$AddedDate,$StartDate,$EndDate,$pages;
   global $SEARCH_VAL,$STATUS_ACTIVE_SEL,$STATUS_INACTIVE_SEL,$DATE_VAL;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/tender_infoGrid.html");

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
	     $where_clauses[] = "(Pdf_Name LIKE '%$clean_search%' OR Pdf_Desc LIKE '%$clean_search%' OR Pdf_Id = '$clean_search')";
	     $params['search'] = $search;
	 }
	 if ($filter_status !== '') {
	     $clean_status = addslashes($filter_status);
	     $where_clauses[] = "status = '$clean_status'";
	     $params['filter_status'] = $filter_status;
	 }
	 if ($filter_date !== '') {
	     $clean_date = addslashes($filter_date);
	     $date_conditions = [
	         "AddedDate LIKE '%$clean_date%'",
	         "StartDate LIKE '%$clean_date%'",
	         "EndDate LIKE '%$clean_date%'"
	     ];
	     $ts = strtotime(str_replace('/', '-', $filter_date));
	     if ($ts) {
	         $d_m_Y = date('d-m-Y', $ts);
	         $d_sl_m_Y = date('d/m/Y', $ts);
	         $Y_m_d = date('Y-m-d', $ts);
	         foreach ([$d_m_Y, $d_sl_m_Y, $Y_m_d] as $fmt) {
	             $date_conditions[] = "AddedDate LIKE '%$fmt%'";
	             $date_conditions[] = "StartDate LIKE '%$fmt%'";
	             $date_conditions[] = "EndDate LIKE '%$fmt%'";
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
	 
	 $count="select count(Pdf_Id) as total from pdffiles $where_sql";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from pdffiles $where_sql order by Pdf_Id DESC limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $Pdf_Id=$rows['Pdf_Id'];
			  $Pdf_Name=htmlspecialchars($rows['Pdf_Name']);
			  $Pdf_Desc=htmlspecialchars($rows['Pdf_Desc']);
			  $AddedDate=$rows['AddedDate'];

			  $rawStart = trim($rows['StartDate'] ?? '');
			  $rawEnd = trim($rows['EndDate'] ?? '');
			  $StartDate = !empty($rawStart) ? htmlspecialchars($rawStart) : '<span style="color:#94A3B8;">—</span>';
			  $EndDate = !empty($rawEnd) ? htmlspecialchars($rawEnd) : '<span style="color:#94A3B8;">—</span>';
			 
			  $Status = ($rows['status'] == 1) 
			      ? '<span style="background:#DCFCE7; color:#15803D; font-size:11px; font-weight:700; padding:3px 8px; border-radius:12px;">Active</span>' 
			      : '<span style="background:#FEE2E2; color:#B91C1C; font-size:11px; font-weight:700; padding:3px 8px; border-radius:12px;">Inactive</span>';
			  
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			}
			
		 if($page > 0)
			{	$prevpage = max(0, $page - $MAX);
				$PREV_PAGE_LINK = "<a href='tender_info.php?page=$prevpage&max=$MAX$next_links' class='button' style='padding:5px 12px; font-size:12px; background:#fff; border:1px solid #CBD5E1; color:#12365A; text-decoration:none;'>&laquo; Prev</a>";
			} else {
				$PREV_PAGE_LINK = "<span class='button' style='padding:5px 12px; font-size:12px; background:#F1F5F9; border:1px solid #E2E8F0; color:#94A3B8; cursor:not-allowed;'>&laquo; Prev</span>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK = "<a href='tender_info.php?page=$lastrow&max=$MAX$next_links' class='button' style='padding:5px 12px; font-size:12px; background:#fff; border:1px solid #CBD5E1; color:#12365A; text-decoration:none;'>Next &raquo;</a>";
			} else {
				$NEXT_PAGE_LINK = "<span class='button' style='padding:5px 12px; font-size:12px; background:#F1F5F9; border:1px solid #E2E8F0; color:#94A3B8; cursor:not-allowed;'>Next &raquo;</span>";
			}
							
			$curr_p = floor($page / $MAX) + 1;
			$tot_p = max(1, ceil($TOTAL_RECORDSET / $MAX));
			$PAGE_NAVS = "<span style='padding: 5px 12px; background: #12365A; color: #FFFFFF; border-radius: 4px; font-weight: 700; font-size: 12px;'>Page $curr_p of $tot_p</span> <span style='color: #64748B; font-size: 12px; margin-left: 6px;'>(Total: $TOTAL_RECORDSET items)</span>";
			$pages = "";
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='8' align='center' style='padding: 40px 20px; color: #94A3B8;'><div style='font-size: 32px; margin-bottom: 8px;'>📭</div><div style='font-size: 14px; font-weight: 600; color: #475569;'>No tenders found matching your filter criteria.</div></td></tr>";
      $PREV_PAGE_LINK = "";
      $NEXT_PAGE_LINK = "";
      $PAGE_NAVS = "";
      $pages = "";
    }
   
  return 1;
 }
 
function deletePdf($pid, $db)
 {	
	global $PROMPT, $APP_ROOT;

	$cleanPids = array_map('intval', $pid);
	$pids = implode(",", $cleanPids);
	if (empty($pids)) return;

	$del_path = ($APP_ROOT ?? (dirname(__DIR__) . '/')) . 'docs/';

	$sel_pdffile = "select Pdf_Name from pdffiles where Pdf_Id in ($pids)";
    $db->query($sel_pdffile);
    while ($rows = $db->fetch_array()) {
		$pdfname = $rows['Pdf_Name'];
		if (!empty($pdfname) && file_exists($del_path . $pdfname)) {
			@unlink($del_path . $pdfname);
		}
	}

 	$delete = "delete from pdffiles where Pdf_Id in ($pids)";
	$db->query($delete);

	$total = $db->affected_rows();
	$PROMPT = "Total $total tender(s) have been deleted.";
 }

function ChangeStatus($pid, $db)
{
  
   global $PROMPT;

	$pids = implode(",", $pid);
    $statusid=$_POST['Status'];
 	$change = "update pdffiles set Status='$statusid' where Pdf_Id in ($pids)";
	$db->query($change);

	$total = $db->affected_rows();

	$PROMPT = "Total $total tender(s) status have been changed.";

}
?>
