<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

GetNotices($db);

$mid = isset($_GET['mid']) ? intval($_GET['mid']) : 15;
$query = "select * from mayers where Mayer_Id='$mid'";
$db->query($query);
if ($db->num_rows() == 0) {
    $db->query("select * from mayers where status='1' order by Mayer_Id desc limit 1");
}
$rows = $db->fetch_array();

$c_image = "<img src='m_images/thumbs/".$rows['Mayer_Photo']."' style='max-width:100%; height:auto; border-radius:8px;'>";
$Mayer_Name = $rows['Mayer_Name'];
$Mayer_Desc = $rows['Mayer_Desc'];

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
