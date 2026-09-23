<?php
$report1="select updated_date,count(license_no) as reguser,sum(amount) as amount from license_data group by updated_date order by updated_date desc
";
$res = $obj->reportData($report1);
?>
<div id="con_container">
<!--User Detail Start-->
<div id="user_detail">
<?php include("reportheader.php"); ?>
</div>
<!--User Detail End-->
<div id="content_area">
<div class="height_adj">
<div id="summary">
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  
  <tr>
    <td width="47%" align="center" valign="top">
	<table width="97%" height="0" cellpadding="5" cellspacing="1"  bgcolor="#FFFFFF" id="gradient-style"  >
  <tr >
    <th colspan="0" align="center" ><strong>Sl No.</strong></th>
	<th colspan="0" align="center" ><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">fnukad</span></strong></th>
	<th colspan="0" align="center" ><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">dqy ykbZlsUl </span></strong></th>
	<th colspan="0" align="center" ><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">jkf&ldquo;k</span></strong></th>
    </tr>
<?php
  $sn=1;
  $total=0;
  while($row = mysql_fetch_object($res))
  {
?>
  <tr>
  <td colspan="0" align="center"><?=$sn?></td>
	<td colspan="0" align="center"><?=$row->updated_date?></td>
	<td colspan="0" align="center"><?=$row->reguser?></td>
	<td colspan="0" align="center"><?=$row->amount?></th>
    </tr>
 <?php $sn++; $total++;} ?> 
    </tbody>
</table></td>
  </tr>
</table>
</div>
</div><br />
</div>
</div>