<?php
switch($_GET['page'])
{
case 'report1':
$activeimg1='imgborder';
break;
case 'report2':
$activeimg2='imgborder';
break;
case 'report3':
$activeimg3='imgborder';
break;
case 'report4':
$activeimg4='imgborder';
break;
case 'report5':
$activeimg5='imgborder';
break;
}
?>
<h3 style="padding-top:0; margin-top:0">LMS Report Dashboard</h3>
<table width="100%" border="0" class="report_ico" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="11%"><a href="index.php?page=report1"><img src="images/mrf_code.png" width="49" height="50" border="0" class="<?=$activeimg1?>" title="SBU wise open MRF" /></a></td>
	<?php if($_SESSION['usertype']=='SUPER') {?>
	<td width="12%"><a href="index.php?page=report2"><img src="images/offered_ico.png" width="50" height="50" border="0"  class="<?=$activeimg9?>" /></a><a href="index.php?page=oar"></a></td>
	<?php } ?>
  </tr>
  <tr>
    <td width="11%">Date Wise Registration</td>
    <td width="11%">Amount Wise</td>
    <td width="11%">Category Wise</td>
    <td width="11%">Financial year wise renewal report</td>
    <td width="11%">Financial year wise new registered</td>
    <td width="11%"></td>
	 <td width="11%"></td>
	 <td width="12%"></td>
	 <td width="11%"></td>
  </tr>
</table>
