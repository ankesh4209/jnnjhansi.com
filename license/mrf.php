<?php
if(!empty($_GET['sid'])) $sbuid=$_GET['sid']; else $sbuid=0;
$res = $obj->showdata('department','name');
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
	<table width="97%" height="360" cellpadding="5" cellspacing="1"  bgcolor="#FFFFFF" id="gradient-style"  >
  
  <tr >
    <th colspan="4" align="center" ><strong>Under construction</strong></th>
    </tr>
  <tr >
    
    </tbody>
</table></td>
  </tr>
</table>
</div>
</div><br />
</div>
</div>