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

$PAGE_NAME = "Website Settings & Banners Management";

$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$PROMPT = "";

if (isset($_POST['submit_settings'])) {
    // 1. Handle file uploads for images if provided
    $upload_dir = "../images/";

    if (isset($_FILES['hero_banner_file']) && $_FILES['hero_banner_file']['error'] == UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['hero_banner_file']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $new_name = "hero_banner_" . time() . "." . $ext;
            if (move_uploaded_file($_FILES['hero_banner_file']['tmp_name'], $upload_dir . $new_name)) {
                $db->query("UPDATE site_settings SET setting_value = 'images/" . $db->escape_string($new_name) . "' WHERE setting_key = 'hero_banner_image'");
            }
        }
    }

    if (isset($_FILES['swachh_banner_file']) && $_FILES['swachh_banner_file']['error'] == UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['swachh_banner_file']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $new_name = "swachh_" . time() . "." . $ext;
            if (move_uploaded_file($_FILES['swachh_banner_file']['tmp_name'], $upload_dir . $new_name)) {
                $db->query("UPDATE site_settings SET setting_value = 'images/" . $db->escape_string($new_name) . "' WHERE setting_key = 'swachh_banner_image'");
            }
        }
    }

    // 2. Handle text/textarea settings
    if (isset($_POST['settings']) && is_array($_POST['settings'])) {
        foreach ($_POST['settings'] as $key => $val) {
            $safe_key = $db->escape_string($key);
            $safe_val = $db->escape_string($val);
            $db->query("UPDATE site_settings SET setting_value = '$safe_val' WHERE setting_key = '$safe_key'");
        }
    }

    $PROMPT = "<div style='background:#d4edda; color:#155724; border:1px solid #c3e6cb; padding:10px 14px; border-radius:4px; margin-bottom:12px; font-weight:bold;'>All website settings have been successfully updated! Changes are live immediately on the website.</div>";
}

// Fetch all current settings
$settings = [];
$res = $db->query("SELECT setting_key, setting_value FROM site_settings");
while ($row = $db->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Assign to global variables for template replacement
foreach ($settings as $k => $v) {
    $GLOBALS[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    // Also allow raw version for title/verses that contain HTML <br>
    $GLOBALS[$k . "_RAW"] = $v;
}

// Set menu active classes
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
$class_settings = "leftab_on";
$class_services = "leftab_off";

$PAGE_CONTENTS = ReadTemplate("../$TEMPLATE_DIR/admin/site_settings.html");
$TEMPLATE      = ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR     = ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR        = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

// Build replacement array
$replace_vars = array_keys($settings);
$replace_vars = array_merge($replace_vars, array_map(function($k) { return $k . "_RAW"; }, array_keys($settings)));
$replace_vars[] = "PROMPT";
$replace_vars[] = "TOPBAR";
$replace_vars[] = "BOTTOMBAR";
$replace_vars[] = "PAGE_CONTENTS";
$replace_vars[] = "TEMPLATE";
$replace_vars[] = "class_settings";
$replace_vars[] = "class_services";

ReplaceContent($replace_vars);
print $TEMPLATE;
flush();
?>
