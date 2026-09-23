<?php session_start(); ?>
<?php 
 if ($_SESSION['user_name']=='')
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


$class11="leftab_off";
$class12="leftab_off";
//$PAGE_NAME = "Sign In...";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

if($_POST['submit']!='') {
 if($_POST['name']!='' && $_POST['gender'] && $_POST['age']) {
  $cam_id=$_REQUEST['c_id'];
  $update="update smartcity_campagin set name = '".$_POST['name']."',gender='".$_POST['gender']."',age='".$_POST['age']."',ward_no='".$_POST['wardno']."'
  ,locality='".$_POST['locality']."',occupation='".$_POST['occupation']."',address='".$_POST['address']."',pincode='".$_POST['pincode']."',
  work_place='".$_POST['workplace']."',mobile='".$_POST['mobile']."',water_supply='".$_POST['water_supply']."'
  ,traffic='".$_POST['traffic']."',safety='".$_POST['safety']."',municipal_services='".$_POST['municipal_services']."',
  bus_services='".$_POST['bus_services']."',others='".$_POST['others']."',water_supply_one='".$_POST['water_supply_one']."',
  parking='".$_POST['parking']."',cabling='".$_POST['cabling']."',sanitation='".$_POST['sanitation']."',intellegent_traffic='".$_POST['intellegent_traffic']."'
  ,infrastucture='".$_POST['infrastucture']."',others_interventions='".$_POST['others_interventions']."',pay='".$_POST['pay']."'
  ,time_join='".$_POST['time_join']."',garbage_free='".$_POST['garbage_free']."',hassle_free='".$_POST['hassle_free']."'
  ,area_activites='".$_POST['area_activites']."',walk_cycle='".$_POST['walk_cycle']."',pollution_free='".$_POST['pollution_free']."'
  ,history='".$_POST['history']."' where id=$cam_id";
   
   $res=$db->query($update);
    echo "<script type='text/javascript'>
                <!--
                  window.location = 'smartcity_campagin.php'
                //-->
                </script>";		
	  exit;
  
  }
}

$id= $_GET['id'];

getCampaginData($db,$id);

$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/campaign_form.html");
if($_SESSION['type']==1) {
  $TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home.html");
} else {
 $TEMPLATE		= ReadTemplate("../$TEMPLATE_DIR/admin/common/template_home1.html");
}

$BOTTOMBAR		= ReadTemplate("../$TEMPLATE_DIR/admin/common/bottombar.html");
$TOPBAR      = ReadTemplate("../$TEMPLATE_DIR/admin/common/topbar.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS", "TEMPLATE"));
print $TEMPLATE;
flush();

function getCampaginData($db,$id) {
  global $id,$name,$gender,$age,$ward_no,$locality,$occupation,$address,$pincode,$work_place,$mobile,$water_supply,$traffic,$safety;
  global $municipal_services,$bus_services,$others,$water_supply_one,$parking,$cabling,$sanitation,$intellegent_traffic,$infrastucture;
  global $others_interventions,$pay,$time_join,$garbage_free,$hassle_free,$area_activites,$walk_cycle,$pollution_free,$history;
  global $female_check,$trans_check,$male_check,$retried_check,$businees_check,$employee_check,$professional_check,$student_check,$unemployed_check;
  global $work_place,$out_jhs_check,$jhs_check,$yes_checked,$no_checked,$time_join_yes_checked,$time_join_no_checked;
  global $garbage_free1,$garbage_free2,$garbage_free3,$garbage_free4,$garbage_free5;
  global $hassle_free1,$hassle_free2,$hassle_free3,$hassle_free4,$hassle_free5;
  global $area_activites1,$area_activites2,$area_activites3,$area_activites4,$area_activites5;
  global $walk_cycle1,$walk_cycle2,$walk_cycle3,$walk_cycle4,$walk_cycle5;
  global $pollution_free1,$pollution_free2,$pollution_free3,$pollution_free4,$pollution_free5;
  global $history1,$history2,$history3,$history4,$history5;
  
   $query="select * from smartcity_campagin where id=$id"; 
   $db->query($query);
   $rows = $db->fetch_array();
   
   $name = $rows['name'];
   $gender = $rows['gender'];
   if($gender=='Female') {
     $female_check="checked";
   } else if($gender=='Transgender') {
     $trans_check="checked";
   } else {
     $male_check="checked";
   }
   $age = $rows['age'];
   $ward_no = $rows['ward_no'];
   $locality = $rows['locality'];
   $occupation = $rows['occupation'];

   if($occupation=='Retired') {
     $retried_check='checked';
   } else if($occupation=='Business') {
     $businees_check='checked';
   } else if($occupation=='Employee') {
     $employee_check='checked';
   } else if($occupation=='Professional') {
     $professional_check='checked';
   } else if($occupation=='Student') {
     $student_check='checked';
   } else if($occupation=='Unemployed') {
     $unemployed_check='checked';
   } else {
   }
   
   $address = $rows['address'];
   $pincode = $rows['pincode'];
   
   $work_place = $rows['work_place'];
   if($work_place=='jhansi') {
     $jhs_check='checked';
   } else if($work_place=='out of jhansi') {
     $out_jhs_check='checked';
   } else {
   }
   
   $mobile = $rows['mobile'];
   $water_supply = $rows['water_supply'];
   $traffic = $rows['traffic'];
   $safety = $rows['safety'];
   $municipal_services = $rows['municipal_services'];
   $bus_services = $rows['bus_services'];
   $others = $rows['others'];
   $water_supply_one = $rows['water_supply_one'];
   $parking = $rows['parking'];
   $cabling = $rows['cabling'];
   $sanitation = $rows['sanitation'];
   $intellegent_traffic = $rows['intellegent_traffic'];
   $infrastucture = $rows['infrastucture'];
   $others_interventions = $rows['others_interventions'];
   
   $pay = $rows['pay'];
   if($pay=='Yes') {
     $yes_checked='checked';
   } else if($pay=='No') {
     $no_checked='checked';
   } else{
   }
   
   $time_join = $rows['time_join'];
   if($time_join=='Yes') {
     $time_join_yes_checked='checked';
   } else if($time_join=='No') {
     $time_join_no_checked='checked';
   } else{
   }
   
   $garbage_free = $rows['garbage_free'];
   if($garbage_free=='Strongly Agree') {
      $garbage_free1='checked';
   } else if($garbage_free=='Agree') {
      $garbage_free2='checked';
   } else if($garbage_free=='Partiially Agree') {
      $garbage_free3='checked';
   } else if($garbage_free=='Disagree') {
      $garbage_free4='checked';
   } else if($garbage_free=='Strongly Disagree') {
      $garbage_free5='checked';
   } else {
   }
   
   $hassle_free = $rows['hassle_free'];
   if($hassle_free=='Strongly Agree') {
      $hassle_free1='checked';
   } else if($hassle_free=='Agree') {
      $hassle_free2='checked';
   } else if($hassle_free=='Partiially Agree') {
      $hassle_free3='checked';
   } else if($hassle_free=='Disagree') {
      $hassle_free4='checked';
   } else if($hassle_free=='Strongly Disagree') {
      $hassle_free5='checked';
   } else {
   }
   
   $area_activites = $rows['area_activites'];
   if($area_activites=='Strongly Agree') {
      $area_activites1='checked';
   } else if($area_activites=='Agree') {
      $area_activites2='checked';
   } else if($area_activites=='Partiially Agree') {
      $area_activites3='checked';
   } else if($area_activites=='Disagree') {
      $area_activites4='checked';
   } else if($area_activites=='Strongly Disagree') {
      $area_activites5='checked';
   } else {
   }
   
   $walk_cycle = $rows['walk_cycle'];
   if($walk_cycle=='Strongly Agree') {
      $walk_cycle1='checked';
   } else if($walk_cycle=='Agree') {
      $walk_cycle2='checked';
   } else if($walk_cycle=='Partiially Agree') {
      $walk_cycle3='checked';
   } else if($walk_cycle=='Disagree') {
      $walk_cycle4='checked';
   } else if($walk_cycle=='Strongly Disagree') {
      $walk_cycle5='checked';
   } else {
   }
   
   $pollution_free = $rows['pollution_free'];
   if($pollution_free=='Strongly Agree') {
      $pollution_free1='checked';
   } else if($pollution_free=='Agree') {
      $pollution_free2='checked';
   } else if($pollution_free=='Partiially Agree') {
      $pollution_free3='checked';
   } else if($pollution_free=='Disagree') {
      $pollution_free4='checked';
   } else if($pollution_free=='Strongly Disagree') {
      $pollution_free5='checked';
   } else {
   }
   
   $history = $rows['history'];
   if($history=='Strongly Agree') {
      $history1='checked';
   } else if($history=='Agree') {
      $history2='checked';
   } else if($history=='Partiially Agree') {
      $history3='checked';
   } else if($history=='Disagree') {
      $history4='checked';
   } else if($history=='Strongly Disagree') {
      $history5='checked';
   } else {
   }
}

?>
