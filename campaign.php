<?php	

include("config/data.config.php");
include("phplib/functions.library.php");
include("phplib/class.database.php");
include("phplib/data.constant.php");

//$PAGE_NAME = "Sign In...";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if($_POST['submit']!='') {
 if($_POST['name']!='' && $_POST['gender'] && $_POST['age']) {
  $insert="insert into smartcity_campagin (name,gender,age,ward_no,locality,occupation,address,pincode,work_place,mobile,water_supply
  ,traffic,safety,municipal_services,bus_services,others,water_supply_one,parking,cabling,sanitation,intellegent_traffic,infrastucture
  ,others_interventions,pay,time_join,garbage_free,hassle_free,area_activites,walk_cycle,pollution_free,history)
   values ('".$_POST['name']."','".$_POST['gender']."','".$_POST['age']."','".$_POST['wardno']."','".$_POST['locality']."','".$_POST['occupation']."'
   ,'".$_POST['address']."','".$_POST['pincode']."','".$_POST['workplace']."','".$_POST['mobile']."','".$_POST['water_supply']."','".$_POST['traffic']."'
   ,'".$_POST['safety']."','".$_POST['municipal_services']."','".$_POST['bus_services']."','".$_POST['others']."','".$_POST['water_supply_one']."','".$_POST['parking']."','".$_POST['cabling']."'
   ,'".$_POST['sanitation']."','".$_POST['intellegent_traffic']."','".$_POST['infrastucture']."','".$_POST['others_interventions']."','".$_POST['pay']."','".$_POST['time_join']."'
   ,'".$_POST['garbage_free']."','".$_POST['hassle_free']."','".$_POST['area_activites']."','".$_POST['walk_cycle']."','".$_POST['pollution_free']."','".$_POST['history']."')";
   
   $res=$db->query($insert);
  
   $msg="Thank you for your participation";
  }
}

$PAGE_CONTENTS	= ReadTemplate("$TEMPLATE_DIR/campaign.html");
$TEMPLATE		= ReadTemplate("$TEMPLATE_DIR/common/template_index.html");
$BOTTOMBAR		= ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
$TOPBAR      = ReadTemplate("$TEMPLATE_DIR/common/topbar.html");
$RIGHTBAR      = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");

ReplaceContent(Array("RIGHTBAR","TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();


?>
