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
if (isset($_GET['action']) && $_GET['action'] == 'del' && isset($_GET['del_id'])) {
    $del_id = (int)$_GET['del_id'];
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

    $S1 = $S2 = ReadTemplate("../$TEMPLATE_DIR/admin/servicesDisplayGrid.html");
    $SERVICES_LIST = "";

    $query = "SELECT * FROM portal_services ORDER BY sort_order ASC, service_id ASC";
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
        $SERVICES_LIST = "<tr><td colspan='8' align='center' style='padding:20px;'>No citizen services found. Click Add Service above to create one.</td></tr>";
    }
}
?>
