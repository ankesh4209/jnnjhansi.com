<?php
$sn=1;
$res =$obj->showdata('users','name');
$task=$_GET['task'];
$id=$_GET['id'];
if(!empty($id) and $task=='del')
{
if($id!=21)
{
$aff=$obj->delete('users',$id);
if($aff>0) header("location:index.php?page=userlist&msg=Selected record has been deleted successfully");	
}
else
{
header("location:index.php?page=userlist&msg=This is super admin, you can not delete this record!");
}


}
?>
<div id="con_container">
<!--User Detail Start-->
<div id="user_detail">
<h3>User details</h3>
<p></p>
</div>
<!--User Detail End-->
<div id="content_area">
<div class="height_adj">
<div align="left">&nbsp;&nbsp;&nbsp;
  <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B3E0FF" id="table19" >
    <tr bgcolor="#D9EBFF">
      <td width="7%" align="center" class="newtxt1"><strong>SN#</strong></td>
      <td width="11%" height="20" align="center" class="newtxt1"><strong>Name</strong></td>
      <td width="12%" height="20" align="center" class="newtxt1"><strong>Username</strong></td>
	  <td width="12%" height="15" align="center" class="newtxt1"><strong>Type</strong></td>
      
      <td width="11%" height="20" align="center" class="newtxt1"><strong>E-mail</strong></td>
	 
      <td width="11%" style="display:none" align="center" class="newtxt1"><strong>Action</strong></td>
      </tr>
    <?php while($row=mysql_fetch_object($res))
	{
	
	$bgcolor=($sn%2)?'#E8F6FF':'#D9EBFF';
	$decodedpass=$obj->decodepassword($row->id);
	$autoemail =$row->emailchk?'<img src="images/tick.png" title="Yes" />':'<img src="images/publish_x.png" title="No" />';
	?>
	<tr bgcolor="<?=$bgcolor?>">
      <td align="center" class="newtxt1"><?=$sn?></td>
      <td height="20" align="center" class="newtxt1"><a href="index.php?page=adduser&id=<?=$row->id?>" title="Click  to modify this record" <?php if($_SESSION['usertype']=='HR' or $_SESSION['usertype']=='ADMIN') {?> onClick="javascript:alert('Permission denied. Please contact Super Admin?');return false;" <?php } ?>><?=$row->name?></a></td>
      <td height="20" align="center" class="newtxt1"><?=$row->username?></td>
	  <td height="20" align="center" class="newtxt1"><?=$row->type?></td>
	  
       <td align="center" class="newtxt1"><?=$row->email?></td>
	   
     <td style="display:none" align="center" class="newtxt1"><a href="index.php?page=userlist&id=<?=$row->id?>&task=del" title="Click  to delete this record" <?php if($_SESSION['usertype']=='SUPER'){?> onClick="javascript:return confirm('Do you want to delete record No-<?=$sn;?>?');"  title="Click to delete" <?php } else { ?>onClick="javascript:alert('Permission denied. Please contact Super Admin?');return false;" title="Permission denied" <?php } ?>>Delete</a></td> 
      </tr>
    <?php $sn++; } ?>
     </table>
 </div>
</div>
</div>
</div>