<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php 
if (!isset($_SESSION['user_name']) || $_SESSION['user_name'] == '') {	
    header("Location: index.php"); 				
    exit;
}
?>
<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");

$PAGE_NAME = "Citizen Services Management";

$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '';
$PROMPT = "";

// Delete single via GET
$del_id = isset($_GET['del_id']) ? (int)$_GET['del_id'] : (isset($_GET['sid']) ? (int)$_GET['sid'] : 0);
if (isset($_GET['action']) && $_GET['action'] == 'del' && $del_id > 0) {
    $db->query("DELETE FROM portal_services WHERE service_id = $del_id");
    $PROMPT = "<div style='color:green; padding:5px; font-weight:bold;'>Service deleted successfully!</div>";
}

// Bulk Delete
if (isset($_POST["SUBMIT_DELETE"])) {	
    if (is_array($sid)) {
        foreach ($sid as $id) {
            $id = (int)$id;
            $db->query("DELETE FROM portal_services WHERE service_id = $id");
        }
        $PROMPT = "<div style='color:green; padding:5px; font-weight:bold;'>Selected services deleted successfully!</div>";
    }
}

// Bulk Status Change
if (isset($_POST["SUBMIT_CHANGE"])) {	
    if (is_array($sid)) {
        foreach ($sid as $id) {
            $id = (int)$id;
            $res = $db->query("SELECT status FROM portal_services WHERE service_id = $id");
            if ($row = $db->fetch_assoc()) {
                $new_status = ($row['status'] == 1) ? 0 : 1;
                $db->query("UPDATE portal_services SET status = $new_status WHERE service_id = $id");
            }
        }
        $PROMPT = "<div style='color:green; padding:5px; font-weight:bold;'>Service status updated successfully!</div>";
    }
}

// Toggle Home Display
if (isset($_GET['action']) && $_GET['action'] == 'toggle_home' && isset($_GET['toggle_id'])) {
    $t_id = (int)$_GET['toggle_id'];
    $res = $db->query("SELECT show_on_home FROM portal_services WHERE service_id = $t_id");
    if ($row = $db->fetch_assoc()) {
        $new_home = ($row['show_on_home'] == 1) ? 0 : 1;
        $db->query("UPDATE portal_services SET show_on_home = $new_home WHERE service_id = $t_id");
    }
}

viewservices($db);

$class1 = "leftab_off";
$class2 = "leftab_off";
$class3 = "leftab_off";
$class4 = "leftab_off";
$class5 = "leftab_off";
$class6 = "leftab_off";
$class7 = "leftab_off";
$class8 = "leftab_off";
$class9 = "leftab_off";
$class10 = "leftab_off";
$class11 = "leftab_off";
$class_settings = "leftab_off";
$class_services = "leftab_on";

$PAGE_CONTENTS = ReadTemplate("../$TEMPLATE_DIR/admin/services_info.html");
$TEMPLATE      = ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR     = ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR        = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "class_settings", "class_services"));
print $TEMPLATE;
flush();

function viewservices($db) {
    global $S1, $S2, $slno, $SERVICES_LIST, $TEMPLATE_DIR, $PROMPT;
    global $service_id, $service_title, $service_category, $service_subtitle, $service_link, $service_home, $service_status, $service_sort;
    global $SEARCH_VAL, $STATUS_ACTIVE_SEL, $STATUS_INACTIVE_SEL;
    global $CAT_TAX_SEL, $CAT_CERT_SEL, $CAT_NOC_SEL, $CAT_GRIEVANCE_SEL, $CAT_SERVICES_SEL, $CAT_UTILITY_SEL;

    $S1 = $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/servicesDisplayGrid.html");
    $SERVICES_LIST = "";

    $search = trim($_REQUEST['search'] ?? '');
    $filter_category = trim($_REQUEST['filter_category'] ?? '');
    $filter_status = isset($_REQUEST['filter_status']) && strlen($_REQUEST['filter_status']) > 0 ? (string)$_REQUEST['filter_status'] : '';

    $where_clauses = [];
    if ($search !== '') {
        $clean_search = addslashes($search);
        $where_clauses[] = "(title LIKE '%$clean_search%' OR subtitle LIKE '%$clean_search%' OR category LIKE '%$clean_search%' OR service_id = '$clean_search')";
    }
    if ($filter_category !== '') {
        $clean_cat = addslashes($filter_category);
        $where_clauses[] = "category = '$clean_cat'";
    }
    if ($filter_status !== '') {
        $clean_status = addslashes($filter_status);
        $where_clauses[] = "status = '$clean_status'";
    }

    $where_sql = count($where_clauses) ? ' WHERE ' . implode(' AND ', $where_clauses) : '';

    $SEARCH_VAL = htmlspecialchars($search);
    $STATUS_ACTIVE_SEL = ($filter_status === '1') ? 'selected' : '';
    $STATUS_INACTIVE_SEL = ($filter_status === '0') ? 'selected' : '';
    $CAT_TAX_SEL = ($filter_category === 'tax') ? 'selected' : '';
    $CAT_CERT_SEL = ($filter_category === 'cert') ? 'selected' : '';
    $CAT_NOC_SEL = ($filter_category === 'noc') ? 'selected' : '';
    $CAT_GRIEVANCE_SEL = ($filter_category === 'grievance') ? 'selected' : '';
    $CAT_SERVICES_SEL = ($filter_category === 'services') ? 'selected' : '';
    $CAT_UTILITY_SEL = ($filter_category === 'utility') ? 'selected' : '';

    $query = "SELECT * FROM portal_services $where_sql ORDER BY sort_order ASC, service_id ASC";
    $db->query($query);
    if ($db->num_rows()) {
        $slno = 1;
        while ($row = $db->fetch_assoc()) {
            $service_id       = $row['service_id'];
            $service_title    = htmlspecialchars($row['title']);
            $service_category = strtoupper($row['category']);
            $service_subtitle = htmlspecialchars($row['subtitle']);
            $service_link     = htmlspecialchars($row['link_url']);
            $service_sort     = $row['sort_order'];

            $service_home = ($row['show_on_home'] == 1) 
                ? "<a href='services_info.php?action=toggle_home&toggle_id=$service_id' style='color:#28a745; font-weight:bold;'>Yes (Toggle)</a>" 
                : "<a href='services_info.php?action=toggle_home&toggle_id=$service_id' style='color:#6c757d;'>No (Toggle)</a>";

            $service_status = ($row['status'] == 1) 
                ? "<span style='color:#28a745; font-weight:bold;'>Active</span>" 
                : "<span style='color:#dc3545;'>Draft</span>";

            ReplaceContent(Array("S1"));
            $SERVICES_LIST .= $S1;
            $S1 = $S2;
            $slno++;
        }
    } else {
        $SERVICES_LIST = "<tr><td colspan='8' align='center' style='padding:20px;'>No citizen services match your filter criteria.</td></tr>";
    }
}
?>
