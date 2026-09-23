<?php
switch($_GET['page'])
{
case 'mad':
case 'addmad':
$active_master1='imgborder';
break;
case 'contractor':
case 'addcontractor':
$active_master2='imgborder';
break;
case 'bank':
case 'addbank':
$active_master3='imgborder';
break;
case 'workdesc':
case 'addworkdesc':
$active_master4='imgborder';
break;
case 'sbu':
case 'addsbu':
$active_master5='imgborder';
break;
case 'sbuhead':
case 'addsbuhead':
$active_master6='imgborder';
break;
case 'source':
case 'addsource':
$active_master7='imgborder';
break;

}
?>
<h3 style="margin:0;padding:0">Add/Edit/View/Delete Masters</h3><br />
<table width="90%" border="0" class="report_ico" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="11%"><a href="index.php?page=mad"><img src="images/mad_icon.png"  border="0" class="<?=$active_master1?>" /></a></td>
    <td width="11%"><a href="index.php?page=contractor"><img src="images/contractor_icon.png"  border="0" class="<?=$active_master2?>"/></a></td>
	<td width="11%"><a href="index.php?page=bank"><img src="images/bank_icon.png"  border="0" class="<?=$active_master3?>"/></a></td>
		<td width="11%"><a href="index.php?page=workdesc"><img src="images/computer_ico.png"  border="0" class="<?=$active_master4?>"/></a></td>

	
    
  </tr>
  <tr>
    <td width="11%">Mad</td>
    <td width="11%">Contractor</td>
    <td width="11%">Nagar Nigam Banks</td>
	<td width="11%">Work Description</td>
	<td width="11%"></td>
	<td width="11%"></td>
	<td width="11%"></td>
	<td width="11%"></td>
  </tr>
</table>