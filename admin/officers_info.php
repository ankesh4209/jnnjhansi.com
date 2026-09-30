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

$oid = isset($_REQUEST['oid']) ? $_REQUEST['oid'] : (isset($_REQUEST['id']) ? [$_REQUEST['id']] : []);
if (!is_array($oid) && !empty($oid)) {
    $oid = [$oid];
}

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_on";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

if (!empty($_POST["SUBMIT_DELETE"]) || (isset($_GET['action']) && $_GET['action'] == 'delete'))
{	
    if (!empty($oid) && is_array($oid))
	{
		deleteOfficers($oid, $db);
	}
}

if($_POST["SUBMIT_CHANGE"])
{	
 if (is_array($oid))
	{
		ChangeStatus($oid, $db);
	}
}

ViewOfficers($db);
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/officers_info.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function ViewOfficers($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$OfficerName,$OffNo,$RedNo,$Email,$Officer_Id,$Designation,$pages,$officer_photo,$Officer_Date;
   global $SEARCH_VAL,$STATUS_ACTIVE_SEL,$STATUS_INACTIVE_SEL,$DATE_VAL;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/officers_infoGrid.html");

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
	     $where_clauses[] = "(FirstName LIKE '%$clean_search%' OR LastName LIKE '%$clean_search%' OR Designation LIKE '%$clean_search%' OR Email LIKE '%$clean_search%' OR OfficeNo LIKE '%$clean_search%' OR Officer_Id = '$clean_search')";
	     $params['search'] = $search;
	 }
	 if ($filter_status !== '') {
	     $clean_status = addslashes($filter_status);
	     $where_clauses[] = "Status = '$clean_status'";
	     $params['filter_status'] = $filter_status;
	 }
	 if ($filter_date !== '') {
	     $clean_date = addslashes($filter_date);
	     $where_clauses[] = "AddedDate LIKE '%$clean_date%'";
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
	 
	 $count="select count(Officer_Id) as total from officers $where_sql";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from officers $where_sql order by Officer_Id DESC limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $Officer_Id=$rows['Officer_Id'];
			  
			  $OfficerName=$rows['FirstName']." ".$rows['LastName'];
			  $Email=$rows['Email'];
			  $Status=$rows['Status'];
			  $OffNo=$rows['OfficeNo'];
			  $RedNo=$rows['ResidenceNo'];
			  $Designation=$rows['Designation'];
			  
			  $rawDate = $rows['AddedDate'] ?? '';
			  if (!empty($rawDate) && $rawDate != '0000-00-00') {
				  $ts = strtotime(str_replace('/', '-', $rawDate));
				  $Officer_Date = $ts ? date('d-M-Y', $ts) : htmlspecialchars($rawDate);
			  } else {
				  $Officer_Date = '-';
			  }
			  
			  $photoFile = !empty($rows['Photo']) ? $rows['Photo'] : '';
			  if (!empty($photoFile) && file_exists("../c_images/" . $photoFile)) {
				  $officer_photo = "<img src='../c_images/{$photoFile}' alt='{$OfficerName}' style='width:45px; height:45px; border-radius:50%; object-fit:cover; border:2px solid #E2E8F0; box-shadow:0 2px 4px rgba(0,0,0,0.08);'>";
			  } elseif (!empty($photoFile) && file_exists("../c_images/thumbs/" . $photoFile)) {
				  $officer_photo = "<img src='../c_images/thumbs/{$photoFile}' alt='{$OfficerName}' style='width:45px; height:45px; border-radius:50%; object-fit:cover; border:2px solid #E2E8F0; box-shadow:0 2px 4px rgba(0,0,0,0.08);'>";
			  } else {
				  $initials = strtoupper(substr($rows['FirstName'] ?? 'O', 0, 1) . substr($rows['LastName'] ?? '', 0, 1));
				  if (!$initials) $initials = 'NN';
				  $officer_photo = "<div style='width:45px; height:45px; border-radius:50%; background:linear-gradient(135deg, #12365A, #0284C7); color:#FFFFFF; display:inline-flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; box-shadow:0 2px 4px rgba(0,0,0,0.08);'>{$initials}</div>";
			  }
			  
			  if($Status==1)
			  {  
				  $Status='<span class="badge" style="background:#DEF7EC; color:#03543F; padding:4px 8px; border-radius:4px; font-size:11.5px; font-weight:600;">Active</span>';
			  }
			  else
			  {  
				  $Status='<span class="badge" style="background:#FDE8E8; color:#9B1C1C; padding:4px 8px; border-radius:4px; font-size:11.5px; font-weight:600;">Inactive</span>';
			  }
			  
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage = max(0, $page - $MAX);
				$PREV_PAGE_LINK = "<a href='officers_info.php?page=$prevpage&max=$MAX$next_links' class='button' style='padding:5px 12px; font-size:12px; background:#fff; border:1px solid #CBD5E1; color:#12365A; text-decoration:none;'>&laquo; Prev</a>";
			} else {
				$PREV_PAGE_LINK = "<span class='button' style='padding:5px 12px; font-size:12px; background:#F1F5F9; border:1px solid #E2E8F0; color:#94A3B8; cursor:not-allowed;'>&laquo; Prev</span>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK = "<a href='officers_info.php?page=$lastrow&max=$MAX$next_links' class='button' style='padding:5px 12px; font-size:12px; background:#fff; border:1px solid #CBD5E1; color:#12365A; text-decoration:none;'>Next &raquo;</a>";
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
      $PRODUCT_LIST="<tr><td colspan='9' align='center' style='padding: 40px 20px; color: #94A3B8;'><div style='font-size: 32px; margin-bottom: 8px;'>📭</div><div style='font-size: 14px; font-weight: 600; color: #475569;'>No officers found matching your filter criteria.</div></td></tr>";
      $PREV_PAGE_LINK = "";
      $NEXT_PAGE_LINK = "";
      $PAGE_NAVS = "";
      $pages = "";
    }
   
  return 1;
 }
 
function deleteOfficers($oid, $db)
 {	
	global $PROMPT;

	$cleanOids = array_map('intval', $oid);
	$oids = implode(",", $cleanOids);
	if (empty($oids)) return;

 	$delete = "delete from officers where Officer_Id in ($oids)";
	$db->query($delete);

	$total = $db->affected_rows();
	$PROMPT = "Total $total Officer(s) have been deleted.";
 }

function ChangeStatus($oid, $db)
{
  
   global $PROMPT;

	$oids = implode(",", $oid);
    $statusid=$_POST['Status'];
 	$change = "update officers set Status='$statusid' where Officer_Id in ($oids)";
	$db->query($change);

	$total = $db->affected_rows();

	$PROMPT = "Total $total Officer's status have been changed.";

}
?>
