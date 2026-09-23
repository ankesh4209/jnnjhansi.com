<?php session_start(); ?>
<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if($_POST['submit']!='')
 {
  addocomplainant($db);
   echo "<script type='text/javascript'>
    <!-- 
     window.location = 'page.php'
    //-->
    </script>"; 
 }
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/add_architect.html");
$TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");
ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function addocomplainant($db)
 {
      $dname=addslashes($_POST['dname']);
      $off_des=addslashes($_POST['off_des']);
      $off_name=addslashes($_POST['off_name']);
      $contact=$_POST['contact'];
      $soffcontact=$_POST['soff_contact'];
 
      $insert="insert into tbl_department (d_name,d_off_name,d_off_designation,d_off_contact,d_soff_contact) 
               values('$dname','$off_name','$off_des','$contact','$soffcontact')";
      $db->query($insert);
  
 }
  
 
?>