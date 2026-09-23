<?php @session_start(); ?>
<?php 
 if ($_SESSION['username']=='')
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

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());


 if($_GET['value']=='Ikk”kZnx.k') {
    $sql="select * from corporators order by WardNo";
    $row=$db->query($sql);
     if($db->num_rows())
    {
      $c_corporator.="<select  name='CorporatorId' onchange='ShowWards(this.value)' style='font-family:Kruti Dev 011;font-size:13px;'>";
      $c_corporator.="<option value=''>Select Corporator</option>";
	  while($res=$db->fetch_array($row))
       {
         $corp_id = $res['CorporatorId'];
         $CorporatorName = $res['CorporatorName'];
         $WardNo = $res['WardNo'];
         $c_corporator.="<option value='$corp_id' style='font-family:Kruti Dev 011;font-size:13px;'>(Ward No-$WardNo)&nbsp;$CorporatorName</option>";
       }
      $c_corporator.="</select>";
    }

	/*
    }*/

}else{
$c_corporator.="<select  name='CorporatorId' onchange='ShowWards(this.value)'>
                    <option value=''>Select Corporator</option>";
      $c_corporator.="</select>";
	 /* $c_ward.="<select  name='WardId'>
                    <option>Select Ward</option>";
      $c_ward.="</select>";*/
}
    
    echo $c_corporator;
	
   // echo "<tr><td>Select Ward : </td><td>".$c_ward."</td></tr>";
?>
