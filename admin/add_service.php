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

$PAGE_NAME = "Add Citizen Service";

$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$PROMPT = "";

if (isset($_POST["submit_add"])) {
    $title       = $db->escape_string(trim($_POST["title"]));
    $category    = $db->escape_string(trim($_POST["category"]));
    $subtitle    = $db->escape_string(trim($_POST["subtitle"]));
    $action_text = $db->escape_string(trim($_POST["action_text"]));
    $link_url    = $db->escape_string(trim($_POST["link_url"]));
    $icon_type   = $db->escape_string(trim($_POST["icon_type"]));
    $is_external = isset($_POST["is_external"]) ? 1 : 0;
    $show_on_home= isset($_POST["show_on_home"]) ? 1 : 0;
    $sort_order  = (int)$_POST["sort_order"];
    $status      = (int)$_POST["status"];

    if ($title == "" || $link_url == "") {
        $PROMPT = "<div style='color:red; font-weight:bold;'>Service Title and Link URL are required.</div>";
    } else {
        $sql = "INSERT INTO portal_services (title, category, subtitle, action_text, link_url, icon_type, is_external, show_on_home, sort_order, status)
                VALUES ('$title', '$category', '$subtitle', '$action_text', '$link_url', '$icon_type', $is_external, $show_on_home, $sort_order, $status)";
        if ($db->query($sql)) {
            echo "<script type='text/javascript'>window.location = 'services_info.php';</script>";
            exit;
        } else {
            $PROMPT = "<div style='color:red;'>Error saving service: " . $db->error() . "</div>";
        }
    }
}

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

$PAGE_CONTENTS = ReadTemplate("../$TEMPLATE_DIR/admin/add_service.html");
$TEMPLATE      = ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR     = ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR        = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "PROMPT", "class_settings", "class_services"));
print $TEMPLATE;
flush();
?>
