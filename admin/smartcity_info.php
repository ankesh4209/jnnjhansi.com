<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user_name'])) {	
    header("Location: index.php"); 				
    exit;
}

include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");

$PAGE_NAME = "Citizen Feedback & Public Inquiries";

$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$PROMPT = "";

// 1. Handle Single Record Delete
if (isset($_GET['action']) && $_GET['action'] == 'del' && !empty($_GET['del_id'])) {
    $del_id = (int)$_GET['del_id'];
    $db->query("DELETE FROM smartcity_reg WHERE id = '$del_id'");
    $db->query("DELETE FROM smartcity_comment WHERE user_id = '$del_id'");
    $PROMPT = "<div style='background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-weight: 700;'>✓ Record #$del_id was deleted successfully.</div>";
}

// 2. Handle Bulk Delete Selected
if (isset($_POST['SUBMIT_DELETE']) && !empty($_POST['del_ids']) && is_array($_POST['del_ids'])) {
    $cleaned_ids = array_map('intval', $_POST['del_ids']);
    $cleaned_ids = array_filter($cleaned_ids, function($v) { return $v > 0; });
    if (!empty($cleaned_ids)) {
        $ids_str = implode(',', $cleaned_ids);
        $db->query("DELETE FROM smartcity_reg WHERE id IN ($ids_str)");
        $db->query("DELETE FROM smartcity_comment WHERE user_id IN ($ids_str)");
        $del_cnt = count($cleaned_ids);
        $PROMPT = "<div style='background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-weight: 700;'>✓ $del_cnt selected record(s) deleted successfully.</div>";
    }
}

// 3. Search Filter & Pagination Logic
$search = isset($_REQUEST['search']) ? trim($_REQUEST['search']) : '';
$SEARCH_VALUE = htmlspecialchars($search);
$where_clause = "";
$search_link_param = "";
$RESET_SEARCH_BTN = "";

if ($search !== '') {
    $safe_search = $db->escape_string($search);
    $where_clause = "WHERE (name LIKE '%$safe_search%' OR mobile LIKE '%$safe_search%' OR address LIKE '%$safe_search%' OR id = '$safe_search')";
    $search_link_param = "&search=" . urlencode($search);
    $RESET_SEARCH_BTN = "<a href='smartcity_info.php' class='button' style='background: #E2E8F0; color: #475569; text-decoration: none; padding: 7px 12px; font-size: 12px;'>Clear</a>";
}

// Count total matching records
$count_query = "SELECT COUNT(id) as total FROM smartcity_reg $where_clause";
$db->query($count_query);
$row = $db->fetch_assoc();
$TOTAL_RECORDSET = (int)$row['total'];

$MAX = 15; // Show 15 per page for easier reading
$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
if ($page < 0) { $page = 0; }
if ($page >= $TOTAL_RECORDSET && $TOTAL_RECORDSET > 0) {
    $page = max(0, floor(($TOTAL_RECORDSET - 1) / $MAX) * $MAX);
}

// Fetch current page records
$query = "SELECT * FROM smartcity_reg $where_clause ORDER BY id DESC LIMIT $page, $MAX";
$db->query($query);

$PRODUCT_LIST = "";
$S1 = $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/smartcity_infoGrid.html");

if ($db->num_rows()) {
    $slno = $page + 1;
    while ($r = $db->fetch_assoc()) {
        $id = $r['id'];
        $raw_name = trim($r['name']);
        $raw_mobile = trim($r['mobile']);
        $raw_address = trim($r['address']);

        // Default parsed values
        $name = htmlspecialchars($raw_name !== '' ? $raw_name : 'Anonymous Citizen');
        $mobile = htmlspecialchars($raw_mobile !== '' ? $raw_mobile : 'N/A');
        $subject = "General Query / Feedback";
        $email = "";
        $message = $raw_address;

        // Extract structured Subject, Email, and Message if present
        if (preg_match('/Sub:\s*(.*?)\s*\|\s*Email:\s*(.*?)\s*-\s*(.*)/is', $raw_address, $m)) {
            $subject = trim($m[1]);
            $email = trim($m[2]);
            $message = trim($m[3]);
        } elseif (preg_match('/Sub:\s*(.*?)\s*-\s*(.*)/is', $raw_address, $m)) {
            $subject = trim($m[1]);
            $message = trim($m[2]);
        } elseif (preg_match('/Email:\s*([^\s|]+)/i', $raw_address, $m)) {
            $email = trim($m[1]);
        }

        // Email block HTML
        $email_block = "";
        if (!empty($email)) {
            $email_block = '<span style="color: #64748B; font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-flex; align-items: center; gap: 4px;" title="' . htmlspecialchars($email) . '">✉️ ' . htmlspecialchars($email) . '</span>';
        }

        $subject = htmlspecialchars($subject);
        $message = nl2br(htmlspecialchars($message !== '' ? $message : '(No message text provided)'));

        ReplaceContent(Array("S1"));
        $PRODUCT_LIST .= $S1;
        $S1 = $S2;
        $slno++;
    }
} else {
    $empty_msg = ($search !== '') 
        ? "No records matched your search query \"<strong>" . htmlspecialchars($search) . "</strong>\"." 
        : "No citizen feedback or queries recorded yet.";
    $PRODUCT_LIST = '<tr><td colspan="6" align="center" style="padding: 40px 20px; color: #94A3B8;"><div style="font-size: 32px; margin-bottom: 8px;">📭</div><div style="font-size: 14px; font-weight: 600; color: #475569;">' . $empty_msg . '</div></td></tr>';
}

// Build Modern Pagination
$PAGINATION_HTML = "";
if ($TOTAL_RECORDSET > 0) {
    $start_rec = $page + 1;
    $end_rec = min($page + $MAX, $TOTAL_RECORDSET);
    $PAGINATION_HTML .= "<span style='color: #64748B; margin-right: 12px;'>Showing <strong>$start_rec - $end_rec</strong> of <strong>$TOTAL_RECORDSET</strong></span>";

    if ($page > 0) {
        $prev_page = max(0, $page - $MAX);
        $PAGINATION_HTML .= "<a href='smartcity_info.php?page=$prev_page$search_link_param' class='button' style='padding: 5px 12px; font-size: 11.5px; background: #FFFFFF; border: 1px solid #CBD5E1; color: #12365A; text-decoration: none;'>&larr; Prev</a> ";
    } else {
        $PAGINATION_HTML .= "<span class='button' style='padding: 5px 12px; font-size: 11.5px; background: #F1F5F9; border: 1px solid #E2E8F0; color: #94A3B8; cursor: not-allowed;'>&larr; Prev</span> ";
    }

    $total_pages = ceil($TOTAL_RECORDSET / $MAX);
    $curr_page = floor($page / $MAX) + 1;
    $PAGINATION_HTML .= "<span style='padding: 5px 10px; background: #12365A; color: #FFFFFF; border-radius: 4px; font-weight: 700; font-size: 11.5px;'>Page $curr_page of $total_pages</span> ";

    if ($page + $MAX < $TOTAL_RECORDSET) {
        $next_page = $page + $MAX;
        $PAGINATION_HTML .= "<a href='smartcity_info.php?page=$next_page$search_link_param' class='button' style='padding: 5px 12px; font-size: 11.5px; background: #FFFFFF; border: 1px solid #CBD5E1; color: #12365A; text-decoration: none;'>Next &rarr;</a>";
    } else {
        $PAGINATION_HTML .= "<span class='button' style='padding: 5px 12px; font-size: 11.5px; background: #F1F5F9; border: 1px solid #E2E8F0; color: #94A3B8; cursor: not-allowed;'>Next &rarr;</span>";
    }
}

// Sidebar active classes
$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";
$class10="leftab_on";
$class11="leftab_off";

$PAGE_CONTENTS = ReadTemplate("../$TEMPLATE_DIR/admin/smartcity_info.html");
$TEMPLATE      = ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR     = ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR        = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "PROMPT", "SEARCH_VALUE", "RESET_SEARCH_BTN", "TOTAL_RECORDSET", "PAGINATION_HTML", "PRODUCT_LIST"));
print $TEMPLATE;
flush();
?>
