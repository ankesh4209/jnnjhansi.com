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


$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());



$class11="leftab_on";
$class12="leftab_off";


viewSmartCityCampagin($db);
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/smartcity_campagin.html");
if($_SESSION['type']==1) {
  $TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
} else {
 $TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home1.html");
}

$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewSmartCityCampagin($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$Status,$id,$name,$address,$mobile,$pages,$gender,$age,$ward_no,$Camp_Date;
   global $SEARCH_VAL,$GENDER_MALE_SEL,$GENDER_FEMALE_SEL,$DATE_VAL;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/smartcity_campaginGrid.html");

	 $CURRENT_PAGE_NO=0;
	 $TOTAL_PAGES=0;
	 $TOTAL_RECORDSET=0;
	 
	 $search = trim($_REQUEST['search'] ?? '');
	 $filter_gender = trim($_REQUEST['filter_gender'] ?? '');
	 $filter_date = trim($_REQUEST['filter_date'] ?? '');

	 $where_clauses = [];
	 $params = [];

	 if ($search !== '') {
	     $clean_search = addslashes($search);
	     $where_clauses[] = "(name LIKE '%$clean_search%' OR mobile LIKE '%$clean_search%' OR address LIKE '%$clean_search%' OR ward_no LIKE '%$clean_search%' OR id = '$clean_search')";
	     $params['search'] = $search;
	 }
	 if ($filter_gender !== '') {
	     $clean_gender = addslashes($filter_gender);
	     $where_clauses[] = "gender = '$clean_gender'";
	     $params['filter_gender'] = $filter_gender;
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
	 $GENDER_MALE_SEL = (strcasecmp($filter_gender, 'Male') === 0) ? 'selected' : '';
	 $GENDER_FEMALE_SEL = (strcasecmp($filter_gender, 'Female') === 0) ? 'selected' : '';
	 $DATE_VAL = htmlspecialchars($filter_date);

	 $page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
	 if(!($page) || $page < 0) 
	   $page = 0 ;
	$MAX=15;
	 $lastrow=$MAX+$page;
	 
	 $count="select count(id) as total from smartcity_campagin $where_sql";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from smartcity_campagin $where_sql order by id DESC limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $id=$rows['id'];
			 
			  $name=htmlspecialchars($rows['name']);
			  $address=htmlspecialchars($rows['address']);
			  $mobile=htmlspecialchars($rows['mobile']);
			  $gender=htmlspecialchars($rows['gender']);
			  $age=htmlspecialchars($rows['age']);
			  $ward_no=htmlspecialchars($rows['ward_no']);
			  $rawDate=$rows['AddedDate'] ?? '';
			  if (!empty($rawDate) && $rawDate != '0000-00-00') {
				  $ts = strtotime(str_replace('/', '-', $rawDate));
				  $Camp_Date = $ts ? date('d-M-Y', $ts) : htmlspecialchars($rawDate);
			  } else {
				  $Camp_Date = '-';
			  }
			  
			  ReplaceContent(Array("S1"));
				$PRODUCT_LIST.=$S1;
				$S1 = $S2;

				$slno++;
			
			}
			
		 if($page > 0)
			{	$prevpage = max(0, $page - $MAX);
				$PREV_PAGE_LINK = "<a href='smartcity_campagin.php?page=$prevpage&max=$MAX$next_links' class='button' style='padding:5px 12px; font-size:12px; background:#fff; border:1px solid #CBD5E1; color:#12365A; text-decoration:none;'>&laquo; Prev</a>";
			} else {
				$PREV_PAGE_LINK = "<span class='button' style='padding:5px 12px; font-size:12px; background:#F1F5F9; border:1px solid #E2E8F0; color:#94A3B8; cursor:not-allowed;'>&laquo; Prev</span>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK = "<a href='smartcity_campagin.php?page=$lastrow&max=$MAX$next_links' class='button' style='padding:5px 12px; font-size:12px; background:#fff; border:1px solid #CBD5E1; color:#12365A; text-decoration:none;'>Next &raquo;</a>";
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
      $PRODUCT_LIST="<tr><td colspan='9' align='center' style='padding: 40px 20px; color: #94A3B8;'><div style='font-size: 32px; margin-bottom: 8px;'>📭</div><div style='font-size: 14px; font-weight: 600; color: #475569;'>No campaign records found matching your filter criteria.</div></td></tr>";
      $PREV_PAGE_LINK = "";
      $NEXT_PAGE_LINK = "";
      $PAGE_NAVS = "";
      $pages = "";
    }
   
  return 1;
 }
 


?>
