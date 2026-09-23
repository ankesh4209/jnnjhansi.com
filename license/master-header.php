<?php
switch($_GET['page'])
{
case 'location':
case 'addlocation':
$active_master1='imgborder';
break;
case 'category':
case 'addlevel':
$active_master2='imgborder';
break;
case 'vehicle':
case 'addvehicle':
$active_master3='imgborder';
break;
case 'business':
case 'addbusiness':
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
    <td width="11%"><a href="index.php?page=location"><img src="images/level_icon.png" width="50" height="50" border="0" class="<?=$active_master1?>" /></a></td>
    <td width="11%"><a href="index.php?page=category"><img src="images/level_icon.png" width="48" height="50" border="0" class="<?=$active_master2?>"/></a></td>
	<td width="11%"><a href="index.php?page=vehicle"><img src="images/level_icon.png" width="48" height="50" border="0" class="<?=$active_master3?>"/></a></td>
	<td width="11%"><a href="index.php?page=business"><img src="images/level_icon.png" width="48" height="50" border="0" class="<?=$active_master4?>"/></a></td>
    
  </tr>
  <tr>
    <td width="11%"><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">izfr&#8217;Bku</span></strong></td>
    <td width="11%"><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">Js.kh</span></strong></td>
    <td width="11%"><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">okgu</span></strong></td>
	<td width="11%"><strong><span style="font-family: kruti_dev_010regular;font-size:18px;">O;olk;</span></strong></td>
	<td width="11%"></td>
	<td width="11%"></td>
	<td width="11%"></td>
	<td width="11%"></td>
  </tr>
</table>