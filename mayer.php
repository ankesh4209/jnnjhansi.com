<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

GetNotices($db);

$mid = isset($_GET['mid']) ? intval($_GET['mid']) : 0;
if ($mid > 0) {
    $query = "select * from mayers where Mayer_Id='$mid'";
} else {
    $query = "select * from mayers where status='1' order by Mayer_Id desc limit 1";
}
$db->query($query);
if ($db->num_rows() == 0) {
    $db->query("select * from mayers where status='1' order by Mayer_Id desc limit 1");
}
$rows = $db->fetch_array();

$mayer_photo = (!empty($rows['Mayer_Photo']) && file_exists('m_images/thumbs/' . $rows['Mayer_Photo']))
    ? "m_images/thumbs/" . $rows['Mayer_Photo']
    : ((!empty($rows['Mayer_Photo']) && file_exists('m_images/' . $rows['Mayer_Photo'])) ? "m_images/" . $rows['Mayer_Photo'] : "images/logo.png");

$c_image = "<img src='" . $mayer_photo . "' style='width:100%; height:100%; object-fit:cover; border-radius:8px;' alt='Mayor of Jhansi'>";
$Mayer_Name = !empty($rows['Mayer_Name']) ? $rows['Mayer_Name'] : "Hon'ble Mayor";
$Mayer_Desc = !empty($rows['Mayer_Desc']) ? $rows['Mayer_Desc'] : "Welcome to the official message from the Mayor, Jhansi Municipal Corporation.";

$PAGE_CONTENTS = ReadTemplate("$TEMPLATE_DIR/mayer.html");
$TEMPLATE      = ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR     = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR        = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

ReplaceContent(Array("RIGHTBAR","TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function GetNotices($db)
{
   global $Notice_Id,$NoticeName,$NoticeList,$cid,$notice_view;
   $sql="select * from notice where Status='1' order by Pdf_Id DESC";
   $res=$db->query($sql);
   $i=0;
   while($rows = $db->fetch_array())
   {
      $Notice_Id=$rows['Pdf_Id'];
      $NoticeName=$rows['Pdf_Desc'];
      $notice_view.="<a href='docs/".$rows['Pdf_Name']."' style='color:#000000;' target='_new'>".$NoticeName."</a>::&nbsp;";
      $i++;
   }			
}
?>
