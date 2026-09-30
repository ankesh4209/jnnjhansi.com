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

$pid = $_REQUEST['pid'];

if($_POST["SUBMIT_DELETE"])
{	
 if (is_array($pid))
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
$class4="leftab_on";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";

 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/page.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function viewpage($db)
 {
   global $S1,$S2,$slno,$page,$prevpage,$NEXT_PAGE_LINK,$TOTAL_PAGES,$PREV_PAGE_LINK,$TEMPLATE_DIR,$PRODUCT_LIST;
   global $TOTAL_RECORDSET,$PAGE_NAVS,$status,$pid,$pname,$description,$Page_Date;
   global $SEARCH_VAL,$STATUS_ACTIVE_SEL,$STATUS_INACTIVE_SEL,$DATE_VAL;
   
   $S1	= $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/pageDisplayGrid.html");

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
	     $where_clauses[] = "(p_name LIKE '%$clean_search%' OR p_text LIKE '%$clean_search%' OR p_id = '$clean_search')";
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
	 
	 $count="select count(p_id) as total from page $where_sql";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
  
   $query="select * from page $where_sql order by p_id DESC limit  $page, $MAX";
    
   $db->query($query);
		if($db->num_rows())
		{
		  $slno=$page+1;
			while($rows = $db->fetch_array())
			{
			  $pid=$rows['p_id'];
        $pname=stripslashes($rows['p_name']);
        $rawDate=$rows['AddedDate'] ?? '';
        if (!empty($rawDate) && $rawDate != '0000-00-00') {
            $ts = strtotime(str_replace('/', '-', $rawDate));
            $Page_Date = $ts ? date('d-M-Y', $ts) : htmlspecialchars($rawDate);
        } else {
            $Page_Date = '-';
        }
        if($pid=="3" || $pid=="4" || $pid=="5")
		{
		  $description="&nbsp;";
		}
		else
		{
		  $description=substr($rows['p_text'],0,30);
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
				$PREV_PAGE_LINK="<<a href='page.php?page=$prevpage&max=$MAX&$next_links' >Prev</a>";
			}
			
			if($TOTAL_RECORDSET > $lastrow)
			{	$NEXT_PAGE_LINK="<a href='page.php?page=$lastrow&max=$MAX&$next_links' >Next></a>";
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
          $PAGE_NAVS.=" <a href='page.php?page=$i&max=$MAX&left_id=1&$next_links' >$toPrint</a> |";
				}
				$TOTAL_PAGES=$toPrint;
			}
		
		}
		else
		{
      $PRODUCT_LIST="<tr><td colspan='5'>No Pages Found</td></tr>";
    }
   
  return 1;
 }
 
function deleteaboutus($pid, $db)
 {	
	global $PROMPT;

	$product = implode(",", $pid);

 	$delete = "delete from page where p_id in ($product)";
	$db->query($delete);

	$total = $db->affected_rows();

	$PROMPT = "Total $total page have been deleted.";
 }

function ChangeStatus($pid, $db)
{
  
   global $PROMPT;

	$product = implode(",", $pid);
    $statusid=$_POST['status'];
 	$change = "update page set status='$statusid' where p_id in ($product)";
	$db->query($change);

	$total = $db->affected_rows();

	$PROMPT = "Total $total Page's status have been changed.";

}
?>
