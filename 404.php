<?php
http_response_code(404);

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");
include("phplib/portal_data.library.php");

$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open();

$settings = LoadSiteSettings($db);
GetNotices($db);

$PAGE_CONTENTS = ReadTemplate("$TEMPLATE_DIR/404.html");
$TEMPLATE      = ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR     = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR        = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

$replace_keys = array_merge(
    Array("RIGHTBAR", "TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"),
    array_keys($settings)
);

ReplaceContent($replace_keys);
print $TEMPLATE;
flush();

function GetNotices($db)
{
    global $Notice_Id, $NoticeName, $NoticeList, $cid, $notice_view;
    $notice_view = "";
    $sql = "select * from notice where Status='1' order by Pdf_Id DESC limit 10";
    $res = $db->query($sql);
    while ($rows = $db->fetch_array()) {
        $Notice_Id = $rows['Pdf_Id'];
        $NoticeName = $rows['Pdf_Desc'];
        $doc_path = ($_SERVER['SERVER_NAME'] == 'localhost') ? 'docs/' : '/docs/';
        $notice_view .= "<a href='" . $doc_path . $rows['Pdf_Name'] . "' style='color:#000000;' target='_new'>" . $NoticeName . "</a>::&nbsp;";
    }
}
?>
