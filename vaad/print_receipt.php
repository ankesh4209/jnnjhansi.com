<?php
ini_set('display_errors', 'Off');
error_reporting(0);
include_once('class.vaad.php');
include_once('number2word.php');
$obj = new vaad();
$r =$obj->showdata('addcase','',$_GET['id']);	
$row = mysql_fetch_object($r);
$orderno =str_replace("\\"," ",stripslashes($row->orderno));
$activity_no = str_replace("\\"," ",stripslashes($row->activity_no));
$party = str_replace("\\"," ",stripslashes($row->party));
$property_desc = str_replace("\\"," ",stripslashes($row->property_desc));
$department = $row->department;
$court = $row->court;
$dhara = $row->dhara;
$schedule_date = $row->schedule_date;
$result_date=$row->result_date;
$ref_lawer1=$obj->getName('advocate',$row->ref_lawer);
$ref_lawer =stripslashes($ref_lawer1);

$description=str_replace("\\"," ",stripslashes($row->description));
$case_status=$row->case_status;
$reason_status=$row->reason_status;
$last_date = $row->last_date;
$cur_discuss_date= $row->cur_discuss_date;
$comments= str_replace("\\"," ",stripslashes($row->comments));
$next_discss_date= $row->next_discss_date;

//------- case info data -----------------
 $filter="WHERE status=1 and caseid='".$_GET['id']."'";
 $res2 =$obj->showdata('addcaseinfo','id DESC','','',$filter);
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
<div class="wrapper">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="receipt">
    <tr>
      <td colspan="4"><h2>Jhansi Municipal Corporation Case Summary </h2></td>
    </tr>
    <tr>
      <td style="font-family: kruti_dev_010regular;font-size:18px;">izdj.k la0</td>
      <td style="font-family: kruti_dev_010regular;font-size:18px;"><?=$row->activity_no?></td>
      <td style="font-family: kruti_dev_010regular;font-size:18px;">i{kdkj</td>
      <td style="font-family: kruti_dev_010regular;font-size:18px;"><?=$party?></td>
    </tr>
    <tr>
      <td style="font-family: kruti_dev_010regular;font-size:18px;">uxj fuxe vuqHkkx</td>
      <td style="font-family: kruti_dev_010regular;font-size:18px;"><?=$obj->getName('department',$department)?></td>
      <td style="font-family: kruti_dev_010regular;font-size:18px;">/kkjk</td>
      <td style="font-family: kruti_dev_010regular;font-size:18px;"><?=$dhara?></td>
    </tr>
    <tr>
      <td style="font-family: kruti_dev_010regular;font-size:18px;">U;k;ky;</td>
      <td style="font-family: kruti_dev_010regular;font-size:18px;"><?=$obj->getName('court',$court)?></td>
      <td style="font-family: kruti_dev_010regular;font-size:18px;">fu;r frfFk</td>
      <td><?php echo strftime("%d-%m-%Y", strtotime($schedule_date));?></td>
    </tr>
	<tr>
      <td style="font-family: kruti_dev_010regular;font-size:18px;">lEcfU/kr odhy</td>
      <td style="font-family: kruti_dev_010regular;font-size:18px;"><?=$ref_lawer?></td>
      <td style="font-family: kruti_dev_010regular;font-size:18px;">fu.kZ; frfFk</td>
      <td><?php if($result_date!='0000-00-00') {echo date("d-m-Y", strtotime($result_date));}?></td>
    </tr>
	<tr>
      <td  style="font-family: kruti_dev_010regular;font-size:18px;">lEifRr fooj.k</td>
      <td  style="font-family: kruti_dev_010regular;font-size:18px;"><?=$property_desc?></td>
      <td  style="font-family: kruti_dev_010regular;font-size:18px;">fo'ks"k fooj.k</td>
      <td  style="font-family: kruti_dev_010regular;font-size:18px;"><?=$description?></td>
    </tr>
	<!--<tr>
      <?php if($last_date){?><td style="font-family: kruti_dev_010regular;font-size:18px;">fiNyh rkjh[k</td>
      <td style="font-family: kruti_dev_010regular;font-size:18px;"><?php echo strftime("%d-%m-%Y", strtotime($last_date));?></td><?php }?>
      <?php if($next_discss_date){?><td style="font-family: kruti_dev_010regular;font-size:18px;">fu.kZ; vxyh rkjh[k</td>
      <td><?php echo strftime("%d-%m-%Y", strtotime($next_discss_date));?></td><?php }?>
    </tr>-->
    <tr>
     <td colspan="4">
       <table width="100%" border="0" cellspacing="0" cellpadding="0" class="receipt">
       <?php 
      
      $sn=1;
      $total=0;
      while($row = mysql_fetch_object($res2))
      {
     
      ?>
      <tr>
      <td align="left" style="width:20px;"><?=$sn?></td>
    	<td height="25" align="center"  style="font-family: kruti_dev_010regular;font-size:18px;"><?=$obj->getName('advocate',$row->ref_lawer)?></td>
    	<td height="25" align="center"  style="font-family: kruti_dev_010regular;font-size:18px;"><?=$obj->getName('court',$row->court)?></td>
    	<td height="25" align="center"  style="font-family: kruti_dev_010regular;font-size:18px;"><?=$row->comments?></td>
    	<td height="25" align="center" width="50"><?php echo strftime("%d-%m-%Y", strtotime($row->schedule_date));?></td>
    	</tr>
      <?php $sn++; $total++;} ?>  
      </table>
     </td>
    </tr>
    <tr>
      <td colspan="3">
      <p align="center" id="pbutton"><input style="background:#000066; color:#FFFFFF; border:none; padding:5px; font-size:11px;" type="button" value="Print" name="pprint" onClick="printPage()"><!--&nbsp;&nbsp;<input style="background:#000066; color:#FFFFFF; border:none; font-size:11px;" type="button" value="Normal print" name="pprint1" onClick="printPage1()">-->&nbsp;<input style="background:#000066; color:#FFFFFF; padding:5px; border:none; font-size:11px;" type="button" value="Close" name="pclose" onClick="self.close();"></p></td><td>&nbsp;</td>

    </tr>
  </table>
  
  
</div>
</body>
</html>
