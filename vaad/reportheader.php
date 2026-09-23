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
<h3 style="padding-top:0; margin-top:0">VAAD Report Dashboard</h3>
<table width="100%" border="0" class="report_ico" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="11%"><a href="index.php?page=report1"><img src="images/mrf_code.png"  border="0" class="<?=$activeimg1?>" title="SBU wise open MRF" /></a></td>
	
	<td width="12%"><a href="index.php?page=report2"><img src="images/paid.png" border="0"  class="<?=$activeimg2?>" /></a><a href="index.php?page=oar"></a></td>

	<td width="12%"><a href="index.php?page=report3"><img src="images/money_bag.png"  border="0"  class="<?=$activeimg3?>" /></a><a href="index.php?page=oar"></a></td>
	
  </tr>
  <tr>
    <td width="11%">Pending Report</td>
    <td width="11%">Completed Report</td>
    <td width="11%">Advocate Wise Report</td>
    <td width="11%">Court Wise Report</td>
	 <td width="11%">Departmental Report</td>
	 <td width="12%"></td>
	 <td width="11%"></td>
  </tr>
</table>
