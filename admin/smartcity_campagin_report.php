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


$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());


$class11="leftab_off";
$class12="leftab_on";

$ward_no='';
if($_POST) {
 $ward_no=$_POST['ward_no'];
 wardWiseReport($db,$ward_no);
}

viewSmartCityCampaginReport($db);
getWardNo($ward_no);
 
$PAGE_CONTENTS	= ReadTemplate("../$TEMPLATE_DIR/admin/smartcity_campagin_report.html");
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

function viewSmartCityCampaginReport($db)
 {
   global $TOTAL_RECORDSET,$TOTAL_RECORDSET_male,$TOTAL_RECORDSET_female,$id,$name,$address,$mobile,$pages,$gender,$age,$ward_no;
	 
	 $count="select count(id) as total from smartcity_campagin";
   $db->query($count);
	 $row = $db->fetch_assoc();
   $TOTAL_RECORDSET = $row['total'];
   
   $count_male="select count(*) as total_male from smartcity_campagin where gender='Male'";
   $db->query($count_male);
	 $row_male = $db->fetch_assoc();
   $TOTAL_RECORDSET_male = $row_male['total_male'];
   
   $count_female="select count(*) as total_female from smartcity_campagin where gender='Female'";
   $db->query($count_female);
	 $row_female = $db->fetch_assoc();
   $TOTAL_RECORDSET_female = $row_female['total_female'];
  
 }
 
function getWardNo($ward_no) {
  global $ward_list;
  $ward_list='';
  if($ward_no=='all') {
    $ward_list.="<option value='all' selected>All</option>";
  } else {
    $ward_list.="<option value='all'>All</option>";
  }
  
  for($i=1;$i<62;$i++) {
    if($i<10) {
      if($ward_no=="0$i") {
        $ward_list.="<option value='0$i' selected>$i</option>";
      } else {
        $ward_list.="<option value='0$i'>$i</option>";
      }
    } else {
      if($ward_no==$i) {
        $ward_list.="<option value='$i' selected>$i</option>";
      } else {
        $ward_list.="<option value='$i'>$i</option>";
      }
    }
  }
}

function wardWiseReport($db,$ward_no) {
 global $water_supply_data,$traffic_data,$safety_data,$municipal_data,$bus_data;
 global $water_supply_data_one,$parking_data,$underground_data,$solid_data,$transport_data,$education_data;
 global $yes_data,$no_data;
 global $garbage_free,$hassle_free,$area,$walk_cycle,$pollution_free,$history;
 
   $water_supply_data='';
   if($ward_no=='all') {
     $count_water_supply="select count(*) as total, water_supply from smartcity_campagin where water_supply IN (1,2,3,4,5) group by water_supply order by water_supply ASC";
   } else {
     $count_water_supply="select count(*) as total, water_supply from smartcity_campagin where water_supply IN (1,2,3,4,5) AND ward_no='$ward_no' group by water_supply order by water_supply ASC";
   }
   $db->query($count_water_supply);
	 while($row_water_supply = $db->fetch_array()) {
     if($row_water_supply['water_supply']=='1' OR $row_water_supply['water_supply']=='2' OR $row_water_supply['water_supply']=='3' OR $row_water_supply['water_supply']=='4' OR $row_water_supply['water_supply']=='5') {
       $water_supply_data.="<td align='center'>".$row_water_supply['total']."</td>";
     }
   }
   
   $traffic_data='';
   if($ward_no=='all') {
     $count_traffic="select count(*) as total, traffic from smartcity_campagin  group by traffic order by traffic ASC";
   } else {
     $count_traffic="select count(*) as total, traffic from smartcity_campagin where ward_no='$ward_no' group by traffic order by traffic ASC";
   }
   $db->query($count_traffic);
	 while($row_traffic = $db->fetch_array()) {
     if($row_traffic['traffic']==1 OR $row_traffic['traffic']==2 OR $row_traffic['traffic']==3 OR $row_traffic['traffic']==4 OR $row_traffic['traffic']==5) {
       $traffic_data.="<td align='center'>".$row_traffic['total']."</td>";
     }
   }
   
   $safety_data='';
   if($ward_no=='all') {
     $count_safety="select count(*) as total, safety from smartcity_campagin group by safety order by safety ASC";
   } else {
     $count_safety="select count(*) as total, safety from smartcity_campagin where ward_no='$ward_no' group by safety order by safety ASC";
   }
   $db->query($count_safety);
	 while($row_safety = $db->fetch_array()) {
     if($row_safety['safety']==1 OR $row_safety['safety']==2 OR $row_safety['safety']==3 OR $row_safety['safety']==4 OR $row_safety['safety']==5) {
       $safety_data.="<td align='center'>".$row_safety['total']."</td>";
     }
   }
   
   $municipal_data='';
   if($ward_no=='all') {
     $count_municipal="select count(*) as total, municipal_services from smartcity_campagin group by municipal_services order by municipal_services ASC";
   } else {
     $count_municipal="select count(*) as total, municipal_services from smartcity_campagin where ward_no='$ward_no' group by municipal_services order by municipal_services ASC";
   }
   $db->query($count_municipal);
	 while($row_municipal = $db->fetch_array()) {
     if($row_municipal['municipal_services']==1 OR $row_municipal['municipal_services']==2 OR $row_municipal['municipal_services']==3 OR $row_municipal['municipal_services']==4 OR $row_municipal['municipal_services']==5) {
       $municipal_data.="<td align='center'>".$row_municipal['total']."</td>";
     }
   }
   
   $bus_data='';
   if($ward_no=='all') {
     $count_bus="select count(*) as total, bus_services from smartcity_campagin group by bus_services order by bus_services ASC";
   } else {
     $count_bus="select count(*) as total, bus_services from smartcity_campagin where ward_no='$ward_no' group by bus_services order by bus_services ASC";
   }
   $db->query($count_bus);
	 while($row_bus = $db->fetch_array()) {
     if($row_bus['bus_services']==1 OR $row_bus['bus_services']==2 OR $row_bus['bus_services']==3 OR $row_bus['bus_services']==4 OR $row_bus['bus_services']==5) {
       $bus_data.="<td align='center'>".$row_bus['total']."</td>";
     }
   }
   
   
   //--------------------
   $water_supply_data_one='';
   if($ward_no=='all') {
     $count_water_supply_one="select count(*) as total, water_supply_one from smartcity_campagin group by water_supply_one order by water_supply_one ASC";
   } else {
     $count_water_supply_one="select count(*) as total, water_supply_one from smartcity_campagin where ward_no='$ward_no' group by water_supply_one order by water_supply_one ASC";
   }
   $db->query($count_water_supply_one);
	 while($row_water_supply_one = $db->fetch_array()) {
     if($row_water_supply_one['water_supply_one']==1 OR $row_water_supply_one['water_supply_one']==2 OR $row_water_supply_one['water_supply_one']==3 OR $row_water_supply_one['water_supply_one']==4 OR $row_water_supply_one['water_supply_one']==5 OR $row_water_supply_one['water_supply_one']==6) {
       $water_supply_data_one.="<td align='center'>".$row_water_supply_one['total']."</td>";
     }
   }
   
   $parking_data='';
   if($ward_no=='all') {
     $count_parking="select count(*) as total, parking from smartcity_campagin  group by parking order by parking ASC";
   } else {
     $count_parking="select count(*) as total, parking from smartcity_campagin where ward_no='$ward_no' group by parking order by parking ASC";
   }
   $db->query($count_parking);
	 while($row_parking = $db->fetch_array()) {
     if($row_parking['parking']==1 OR $row_parking['parking']==2 OR $row_parking['parking']==3 OR $row_parking['parking']==4 OR $row_parking['parking']==5 OR $row_parking['parking']==6) {
       $parking_data.="<td align='center'>".$row_parking['total']."</td>";
     }
   }
   
   $underground_data='';
   if($ward_no=='all') {
     $count_underground="select count(*) as total, cabling from smartcity_campagin group by cabling order by cabling ASC";
   } else {
     $count_underground="select count(*) as total, cabling from smartcity_campagin where ward_no='$ward_no' group by cabling order by cabling ASC";
   }
   $db->query($count_underground);
	 while($row_underground = $db->fetch_array()) {
     if($row_underground['cabling']==1 OR $row_underground['cabling']==2 OR $row_underground['cabling']==3 OR $row_underground['cabling']==4 OR $row_underground['cabling']==5 OR $row_underground['cabling']==6) {
       $underground_data.="<td align='center'>".$row_underground['total']."</td>";
     }
   }
   
   $solid_data='';
   if($ward_no=='all') {
     $count_solid="select count(*) as total, sanitation from smartcity_campagin group by sanitation order by sanitation ASC";
   } else {
     $count_solid="select count(*) as total, sanitation from smartcity_campagin where ward_no='$ward_no' group by sanitation order by sanitation ASC";
   }
   $db->query($count_solid);
	 while($row_solid = $db->fetch_array()) {
     if($row_solid['sanitation']==1 OR $row_solid['sanitation']==2 OR $row_solid['sanitation']==3 OR $row_solid['sanitation']==4 OR $row_solid['sanitation']==5 OR $row_solid['sanitation']==6) {
       $solid_data.="<td align='center'>".$row_solid['total']."</td>";
     }
   }
   
   $transport_data='';
   if($ward_no=='all') {
     $count_transport="select count(*) as total, intellegent_traffic from smartcity_campagin group by intellegent_traffic order by intellegent_traffic ASC";
   } else {
     $count_transport="select count(*) as total, intellegent_traffic from smartcity_campagin where ward_no='$ward_no' group by intellegent_traffic order by intellegent_traffic ASC";
   }
   $db->query($count_transport);
	 while($row_transport = $db->fetch_array()) {
     if($row_transport['intellegent_traffic']==1 OR $row_transport['intellegent_traffic']==2 OR $row_transport['intellegent_traffic']==3 OR $row_transport['intellegent_traffic']==4 OR $row_transport['intellegent_traffic']==5 OR $row_transport['intellegent_traffic']==6) {
       $transport_data.="<td align='center'>".$row_transport['total']."</td>";
     }
   }
   
   $education_data='';
   if($ward_no=='all') {
     $count_education="select count(*) as total, infrastucture from smartcity_campagin group by infrastucture order by infrastucture ASC";
   } else {
     $count_education="select count(*) as total, infrastucture from smartcity_campagin where ward_no='$ward_no' group by infrastucture order by infrastucture ASC";
   }
   $db->query($count_education);
	 while($row_education = $db->fetch_array()) {
     if($row_education['infrastucture']==1 OR $row_education['infrastucture']==2 OR $row_education['infrastucture']==3 OR $row_education['infrastucture']==4 OR $row_education['infrastucture']==5 OR $row_education['infrastucture']==6) {
       $education_data.="<td align='center'>".$row_education['total']."</td>";
     }
   }
   
   //-------------------------
   $yes_data='';
   if($ward_no=='all') {
     $count_yes="select count(*) as total, pay from smartcity_campagin group by pay order by pay DESC";
   } else {
     $count_yes="select count(*) as total, pay from smartcity_campagin where ward_no='$ward_no' group by pay order by pay DESC";
   }
   $db->query($count_yes);
	 while($row_yes = $db->fetch_array()) {
     if($row_yes['pay']=='Yes' OR $row_yes['pay']=='No') {
       $yes_data.="<td align='center'>".$row_yes['total']."</td>";
     }
   }
   
   $no_data='';
   if($ward_no=='all') {
     $count_no="select count(*) as total, time_join from smartcity_campagin group by time_join order by time_join DESC";
   } else {
     $count_no="select count(*) as total, time_join from smartcity_campagin where ward_no='$ward_no' group by time_join order by time_join DESC";
   }
   $db->query($count_no);
	 while($row_no = $db->fetch_array()) {
     if($row_no['time_join']=='Yes' OR $row_no['time_join']=='No') {
       $no_data.="<td align='center'>".$row_no['total']."</td>";
     }
   }
   
   
   $garbage_free='';
   if($ward_no=='all') {
     $count_garbage="select count(*) as total, garbage_free from smartcity_campagin group by garbage_free ";
   } else {
     $count_garbage="select count(*) as total, garbage_free from smartcity_campagin where ward_no='$ward_no' group by garbage_free";
   }
   $db->query($count_garbage);
	 while($row_garbage = $db->fetch_array()) {
	   if($row_garbage['garbage_free']!='') {
       $garbage_free.="<td align='center'>".$row_garbage['total']."</td>";
     }
   }
   
   $hassle_free='';
   if($ward_no=='all') {
     $count_hassle="select count(*) as total, hassle_free from smartcity_campagin group by hassle_free ";
   } else {
     $count_hassle="select count(*) as total, hassle_free from smartcity_campagin where ward_no='$ward_no' group by hassle_free";
   }
   $db->query($count_hassle);
	 while($row_hassle = $db->fetch_array()) {
	   if($row_hassle['hassle_free']!='') {
       $hassle_free.="<td align='center'>".$row_hassle['total']."</td>";
      }
   }
   
   
   $area='';
   if($ward_no=='all') {
     $count_area="select count(*) as total, area_activites from smartcity_campagin group by area_activites ";
   } else {
     $count_area="select count(*) as total, area_activites from smartcity_campagin where ward_no='$ward_no' group by area_activites";
   }
   $db->query($count_area);
	 while($row_area = $db->fetch_array()) {
	   if($row_area['area_activites']!='') {
       $area.="<td align='center'>".$row_area['total']."</td>";
      }
   }
   
   
   $walk_cycle='';
   if($ward_no=='all') {
     $count_walk="select count(*) as total, walk_cycle from smartcity_campagin group by walk_cycle ";
   } else {
     $count_walk="select count(*) as total, walk_cycle from smartcity_campagin where ward_no='$ward_no' group by walk_cycle";
   }
   $db->query($count_walk);
	 while($row_walk = $db->fetch_array()) {
	    if($row_walk['walk_cycle']!='') {
       $walk_cycle.="<td align='center'>".$row_walk['total']."</td>";
      }
   }
   
   
   $pollution_free='';
   if($ward_no=='all') {
     $count_pollution="select count(*) as total, pollution_free from smartcity_campagin group by pollution_free ";
   } else {
     $count_pollution="select count(*) as total, pollution_free from smartcity_campagin where ward_no='$ward_no' group by pollution_free";
   }
   $db->query($count_pollution);
	 while($row_pollution = $db->fetch_array()) {
	   if($row_pollution['pollution_free']!='') {
       $pollution_free.="<td align='center'>".$row_pollution['total']."</td>";
      }
   }
   
   
   $history='';
   if($ward_no=='all') {
     $count_history="select count(*) as total, history from smartcity_campagin group by history ";
   } else {
     $count_history="select count(*) as total, history from smartcity_campagin where ward_no='$ward_no' group by history";
   }
   $db->query($count_history);
	 while($row_history = $db->fetch_array()) {
	    if($row_history['history']!='') {
       $history.="<td align='center'>".$row_history['total']."</td>";
      }
   }
   

}

?>
