<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php 
if (empty($_SESSION['user_name']))
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

$class1="leftab_off";
$class2="leftab_off";
$class3="leftab_off";
$class4="leftab_off";
$class5="leftab_off";
$class6="leftab_off";
$class7="leftab_off";
$class8="leftab_off";
$class9="leftab_off";
$class10="leftab_off";
$class11="leftab_off";


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

// 0. Live Database Counters for Admin Dashboard
$SERVICES_COUNT = 0;
$db->query("SELECT COUNT(1) AS cnt FROM portal_services");
if ($r = $db->fetch_array()) { $SERVICES_COUNT = (int)$r['cnt']; }

$TENDERS_COUNT = 0;
$db->query("SELECT COUNT(1) AS cnt FROM pdffiles");
if ($r = $db->fetch_array()) { $TENDERS_COUNT = (int)$r['cnt']; }

$NOTICES_COUNT = 0;
$db->query("SELECT COUNT(1) AS cnt FROM notice");
if ($r = $db->fetch_array()) { $NOTICES_COUNT = (int)$r['cnt']; }

$OFFICERS_COUNT = 0;
$db->query("SELECT COUNT(1) AS cnt FROM officers");
if ($r = $db->fetch_array()) { $OFFICERS_COUNT = (int)$r['cnt']; }

$GALLERY_COUNT = 0;
$db->query("SELECT COUNT(1) AS cnt FROM tbl_gallary");
if ($r = $db->fetch_array()) { $GALLERY_COUNT = (int)$r['cnt']; }

$WORKS_COUNT = 0;
$db->query("SELECT COUNT(1) AS cnt FROM jnnworks");
if ($r = $db->fetch_array()) { $WORKS_COUNT = (int)$r['cnt']; }

$FEEDBACK_COUNT = 0;
$db->query("SELECT COUNT(1) AS cnt FROM smartcity_reg");
if ($r = $db->fetch_array()) { $FEEDBACK_COUNT = (int)$r['cnt']; }

// 1. Fetch Active Mayor Info & Photo
$MAYOR_PHOTO = "../images/logo.png";
$MAYOR_NAME = "Hon'ble Mayor";
$MAYOR_ID = "";
$db->query("SELECT Mayer_Id, Mayer_Name, Mayer_Photo, Status FROM mayers WHERE Status='1' ORDER BY Mayer_Id DESC LIMIT 1");
if ($r = $db->fetch_array()) {
    $MAYOR_ID = $r['Mayer_Id'];
    $MAYOR_NAME = htmlspecialchars($r['Mayer_Name']);
    $mphoto = $r['Mayer_Photo'];
    if (!empty($mphoto) && file_exists(__DIR__ . "/../m_images/thumbs/" . $mphoto)) {
        $MAYOR_PHOTO = "../m_images/thumbs/" . $mphoto;
    } elseif (!empty($mphoto) && file_exists(__DIR__ . "/../m_images/" . $mphoto)) {
        $MAYOR_PHOTO = "../m_images/" . $mphoto;
    }
}
$MAYOR_EDIT_LINK = !empty($MAYOR_ID) ? "edit_mayer.php?mid={$MAYOR_ID}" : "mayer_info.php";

// 2. Fetch Active Commissioner Info & Photo
$COMM_PHOTO = "../images/logo.png";
$COMM_NAME = "Municipal Commissioner";
$COMM_ID = "";
$db->query("SELECT Comm_Id, Comm_Name, Comm_Photo, Status FROM municipal_comm WHERE Status='1' ORDER BY Comm_Id DESC LIMIT 1");
if ($r = $db->fetch_array()) {
    $COMM_ID = $r['Comm_Id'];
    $COMM_NAME = htmlspecialchars($r['Comm_Name']);
    $cphoto = $r['Comm_Photo'];
    if (!empty($cphoto) && file_exists(__DIR__ . "/../c_images/thumbs/" . $cphoto)) {
        $COMM_PHOTO = "../c_images/thumbs/" . $cphoto;
    } elseif (!empty($cphoto) && file_exists(__DIR__ . "/../c_images/" . $cphoto)) {
        $COMM_PHOTO = "../c_images/" . $cphoto;
    }
}
$COMM_EDIT_LINK = !empty($COMM_ID) ? "edit_commissioner.php?cid={$COMM_ID}" : "commissioner_info.php";

// 3. Fetch Hero Banner & Swachh Mission Images
$HERO_BANNER_SRC = "../images/hero_banner.jpg";
$SWACHH_BANNER_SRC = "../images/swachh_jhansi.jpg";
$db->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ('hero_banner_image', 'swachh_banner_image')");
while ($r = $db->fetch_array()) {
    if ($r['setting_key'] == 'hero_banner_image' && !empty($r['setting_value'])) {
        $HERO_BANNER_SRC = "../" . ltrim($r['setting_value'], '/');
    }
    if ($r['setting_key'] == 'swachh_banner_image' && !empty($r['setting_value'])) {
        $SWACHH_BANNER_SRC = "../" . ltrim($r['setting_value'], '/');
    }
}

// 4. Fetch Latest 6 Gallery Images
$GALLERY_ITEMS_HTML = "";
$db->query("SELECT * FROM tbl_gallary ORDER BY g_id DESC LIMIT 6");
$gcount = 0;
while ($r = $db->fetch_array()) {
    $gcount++;
    $gid = $r['g_id'];
    $pname = $r['photo_name'];
    $gthumb = "../images/logo.png";
    if (!empty($pname) && file_exists(__DIR__ . "/../pic/thumb/" . $pname)) {
        $gthumb = "../pic/thumb/" . $pname;
    } elseif (!empty($pname) && file_exists(__DIR__ . "/../pic/" . $pname)) {
        $gthumb = "../pic/" . $pname;
    }
    $GALLERY_ITEMS_HTML .= '
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
      <div style="height: 105px; overflow: hidden; background: #F8FAFC; display: flex; align-items: center; justify-content: center; position: relative;">
        <img src="' . htmlspecialchars($gthumb) . '" alt="Photo #' . $gid . '" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src=\'../images/logo.png\';" />
        <span style="position: absolute; top: 6px; left: 6px; background: rgba(18, 54, 90, 0.75); color: #fff; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">#' . $gid . '</span>
      </div>
      <div style="padding: 8px 10px 10px; display: flex; flex-direction: column; gap: 4px; flex: 1;">
        <span style="font-size: 11px; font-weight: 600; color: #475569; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="' . htmlspecialchars($pname) . '">
          ' . htmlspecialchars($pname) . '
        </span>
        <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; font-size: 11px;">
          <span style="color: #059669; font-weight: 700;">● Active</span>
          <a href="edit_photo.php?pid=' . $gid . '" style="color: #F47721; font-weight: 700; text-decoration: none;">Edit &rarr;</a>
        </div>
      </div>
    </div>';
}
if ($gcount === 0) {
    $GALLERY_ITEMS_HTML = '<div style="grid-column: 1 / -1; padding: 24px; text-align: center; color: #94A3B8; font-size: 13px;">No gallery photos uploaded yet. <a href="gallary.php" style="color: #F47721; font-weight: 700;">Upload Photos &rarr;</a></div>';
}

// 5. Fetch Latest 4 City Works Images
$WORKS_ITEMS_HTML = "";
$db->query("SELECT * FROM jnnworks ORDER BY Work_Id DESC LIMIT 4");
$wcount = 0;
while ($r = $db->fetch_array()) {
    $wcount++;
    $wid = $r['Work_Id'];
    $wname = htmlspecialchars($r['Work_Name']);
    $wphoto = $r['Work_Photo'];
    $wthumb = "../images/logo.png";
    if (!empty($wphoto) && file_exists(__DIR__ . "/../w_images/thumbs/" . $wphoto)) {
        $wthumb = "../w_images/thumbs/" . $wphoto;
    } elseif (!empty($wphoto) && file_exists(__DIR__ . "/../w_images/" . $wphoto)) {
        $wthumb = "../w_images/" . $wphoto;
    }
    $WORKS_ITEMS_HTML .= '
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
      <div style="height: 105px; overflow: hidden; background: #F8FAFC; display: flex; align-items: center; justify-content: center; position: relative;">
        <img src="' . htmlspecialchars($wthumb) . '" alt="' . $wname . '" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src=\'../images/logo.png\';" />
        <span style="position: absolute; top: 6px; left: 6px; background: rgba(244, 119, 33, 0.85); color: #fff; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">ID #' . $wid . '</span>
      </div>
      <div style="padding: 8px 10px 10px; display: flex; flex-direction: column; gap: 4px; flex: 1;">
        <span style="font-size: 11.5px; font-weight: 700; color: #1E293B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="' . $wname . '">
          ' . $wname . '
        </span>
        <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; font-size: 11px;">
          <span style="color: #0284C7; font-weight: 700;">● Project</span>
          <a href="edit_work.php?cid=' . $wid . '" style="color: #F47721; font-weight: 700; text-decoration: none;">Edit &rarr;</a>
        </div>
      </div>
    </div>';
}
if ($wcount === 0) {
    $WORKS_ITEMS_HTML = '<div style="grid-column: 1 / -1; padding: 24px; text-align: center; color: #94A3B8; font-size: 13px;">No city works photos uploaded yet. <a href="works.php" style="color: #F47721; font-weight: 700;">Add Good Work &rarr;</a></div>';
}

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/welcome.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE", "SERVICES_COUNT", "TENDERS_COUNT", "NOTICES_COUNT", "OFFICERS_COUNT", "GALLERY_COUNT", "WORKS_COUNT", "FEEDBACK_COUNT", "MAYOR_PHOTO", "MAYOR_NAME", "MAYOR_EDIT_LINK", "COMM_PHOTO", "COMM_NAME", "COMM_EDIT_LINK", "HERO_BANNER_SRC", "SWACHH_BANNER_SRC", "GALLERY_ITEMS_HTML", "WORKS_ITEMS_HTML"));
print $TEMPLATE;
flush();
?>
