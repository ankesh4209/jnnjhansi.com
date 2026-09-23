<?php
$report1="select madid,receive_date,sum(mad_amount) as madamt from addmaddata group by receive_date,madid order by receive_date desc";
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
	<th colspan="0" align="center" ><strong>Mad</strong></th>
	<th colspan="0" align="center" ><strong>Received Date</strong></th>
	<th colspan="0" align="center" ><strong>Amount</strong></th>
    </tr>
<?php
  $sn=1;
  $total=0;
  while($row = mysql_fetch_object($res))
  {
?>
  <tr>
  <td colspan="0" align="center"><?=$sn?></td>
	<td colspan="0" align="center"><?=$row->madid?></td>
	<td colspan="0" align="center"><?php echo strftime("%d-%m-%Y", strtotime($row->receive_date));?></td>
	<td colspan="0" align="center"><?=$row->madamt?></th>
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