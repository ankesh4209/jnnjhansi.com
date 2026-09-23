<?php
ini_set('display_errors', 'Off');
error_reporting(0);
include_once('class.vaad.php');
$obj = new vaad();

if($_GET['from_date']!='') {
  $search_from_date=$_GET['from_date'];
}
if($_GET['to_date']!='') {
  $search_to_date=$_GET['to_date'];
}
if($search_from_date!='' && $search_to_date!='') {
   $where=" and schedule_date>='$search_from_date' and schedule_date<='$search_to_date'";
} else {
   $where="";
}

$report1="select id,activity_no,party,department,dhara,ref_lawer,department,schedule_date,cur_discuss_date,next_discss_date,property_desc,description FROM addcase WHERE case_status='PENDING' ".$where." order by id desc";
$res = $obj->reportData($report1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("utility.html"); ?>
<meta http-equiv="Content-Type" content="text/html" />
<title>Receipt</title>
<link href="form.css" rel="stylesheet" type="text/css" />
<style>
@font-face {
 font-family: "kruti";
 src: url('730926514-Kruti_Dev_010.eot');
 src: url('730926514-Kruti_Dev_010.eot?#iefix') format('embedded-opentype'),  url('730926514-Kruti_Dev_010.svg#Kruti Dev 010') format('svg'),  url('730926514-Kruti_Dev_010.woff') format('woff'),  url('730926514-Kruti_Dev_010.ttf') format('truetype');
 font-weight: normal;
 font-style: normal;
}
body {
	font-family: "Kruti Dev 010";
	direction: ltr;
}
</style>
</head>
<body>
<div class="wrapper" style="width:750px;">
  <table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="9"><h2>Jhansi Municipal Corporation Pending Report </h2></td>
    </tr>
    <tr >
    <th align="center"  style="font-family: kruti_dev_010regular;font-size:16px;width:25px;"><strong>d-la-</strong></th>
    <th  align="center"  style="font-family: kruti_dev_010regular;font-size:16px;width:50px;"><strong>izdj.k la0</strong></th>
    <th  align="center"  style="font-family: kruti_dev_010regular;font-size:16px;width:100px;"><strong>i{kdkj </strong></th>
    <th   align="center"  style="font-family: kruti_dev_010regular;font-size:16px;width:100px;"><strong>uxj fuxe vuqHkkx</strong></th>
    <th  align="center"  style="font-family: kruti_dev_010regular;font-size:16px;width:100px;"><strong>U;k;ky;@/kkjk</strong></th>  
    <th  align="center"  style="font-family: kruti_dev_010regular;font-size:16px;width:100px;"><strong>fu;r frfFk</strong></th>  
    <th  align="center"  style="font-family: kruti_dev_010regular;font-size:16px;width:100px;"><strong>lEcfU/kr odhy</strong></th>
    <th  align="center"  style="font-family: kruti_dev_010regular;font-size:16px;width:100px;"><strong>fo'ks"k fooj.k</strong></th>
    <th align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>lEifRr fooj.k </strong></th>
    </tr>
       <?php 
      
      $sn=1;
      $total=0;
      while($row = mysql_fetch_object($res))
      {
     
      ?>
      <tr>
      <td align="left"><?=$row->id?></td>
    	<td height="25" align="center"  style="font-family: kruti_dev_010regular;font-size:16px;"><?=$row->activity_no?></td>
    	<td height="25" align="center"  style="font-family: kruti_dev_010regular;font-size:16px;"><?=stripslashes($row->party)?></td>
    	<td height="25" align="center"  style="font-family: kruti_dev_010regular;font-size:16px;"><?=stripslashes($obj->getName('department',$row->department))?></td>
    	<td height="25" align="center"  style="font-family: kruti_dev_010regular;font-size:16px;"><?=stripslashes($row->dhara)?></td>
      <td height="25" align="center" ><?php echo strftime("%d-%m-%Y", strtotime($row->schedule_date));?></td>
      <td height="25" align="center" style="font-family: kruti_dev_010regular;font-size:16px;"><?=stripslashes($obj->getName('advocate',$row->ref_lawer))?></td>
      <td height="25" align="center" style="font-family: kruti_dev_010regular;font-size:16px;"><?=stripslashes($row->description)?></td>
    	<td height="25" align="center" style="font-family: kruti_dev_010regular;font-size:18px;"><?=stripslashes($row->property_desc)?></td>
      </tr>
      <?php $sn++; $total++;} ?>  
    <tr>
      <td colspan="6">
      <p align="center" id="pbutton"><input style="background:#000066; color:#FFFFFF; border:none; padding:5px; font-size:11px;" type="button" value="Print" name="pprint" onClick="window.print();"><!--&nbsp;&nbsp;<input style="background:#000066; color:#FFFFFF; border:none; font-size:11px;" type="button" value="Normal print" name="pprint1" onClick="printPage1()">-->&nbsp;<input style="background:#000066; color:#FFFFFF; padding:5px; border:none; font-size:11px;" type="button" value="Close" name="pclose" onClick="self.close();"></p></td><td>&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>
