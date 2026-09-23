<?php
switch($_GET['page'])
{
case 'advocate':
case 'addadvocate':
$active_master1='imgborder';
break;
case 'department':
case 'adddepartment':
$active_master2='imgborder';
break;
case 'court':
case 'addcourt':
$active_master3='imgborder';
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
    <td width="11%"><a href="index.php?page=advocate"><img src="images/mad_icon.png"  border="0" class="<?=$active_master1?>" /></a></td>
    <td width="11%"><a href="index.php?page=department"><img src="images/contractor_icon.png"  border="0" class="<?=$active_master2?>"/></a></td>
	<td width="11%"><a href="index.php?page=court"><img src="images/bank_icon.png"  border="0" class="<?=$active_master3?>"/></a></td>
	
    
  </tr>
  <tr>
    <td width="11%">Advocate</td>
    <td width="11%">Municipal Department</td>
    <td width="11%">Court</td>
	<td width="11%"></td>
	<td width="11%"></td>
	<td width="11%"></td>
	<td width="11%"></td>
	<td width="11%"></td>
  </tr>
</table>