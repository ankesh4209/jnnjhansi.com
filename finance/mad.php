<?php
$res = $obj->showMasterData('mad','name');
$id=$_GET['id'];
if(!empty($_GET['task']) and !empty($_GET['id']))
{
$aff=$obj->delete('mad',$id);
//$aff=$obj->disableData('location',$id);
if($aff) header("location:index.php?page=mad&msg=Selected record has been deleted successfully");	
}
?>
<div id="con_container">
<div id="user_detail">
<?php include('master-header.php'); ?>
</div>
<!--User Detail End-->
<div id="content_area">
<div class="height_adj">
<div align="left">&nbsp; &nbsp;
<br />
<div >
  <div align="right" style="margin-bottom:5px;"><a href="index.php?page=addmad"><img src="images/add_new.png" width="75" height="23" border="0" title="Add New" /></a></div>
</div>
 <table width="100%" cellpadding="0" cellspacing="1" bgcolor="#efcca4" id="table19" class="tablecolor">
  <tr bgcolor="#de8f30">
    <th width="5%" align="center" class="newtxt1"><strong>S.No </strong></th>
  <th width="72%" height="25" align="left" class="newtxt1"><strong>Name</strong></th>
  <th width="16%" height="25" colspan="2" align="center" class="newtxt1"><strong>Action</strong></th>
</tr>
<?php 
$sn=1;
while($row=mysql_fetch_object($res))
{
$bgcolor=($sn%2)?'#fff':'#f3f3f3';
 ?>
  <tr bgcolor="<?=$bgcolor?>">
    <td align="center" class="newtxt1"><?=$sn?></td>
    <td align="left" style="font-family: 'kruti_dev_010regular';font-size:15px;"><?=$row->name?></td>
    <td width="8%" align="center" class="newtxt1"><a class="txtcolor" href="index.php?page=addmad&id=<?=$row->id?>">Edit</a></td>
    <td width="8%" align="center" class="newtxt1"><a class="txtcolor" href="index.php?page=mad&amp;id=<?=$row->id?>&amp;task=del" onClick="javascript:return confirm('Do you want to delete record No-<?=$sn;?>?');"  title="Click to delete">Delete</a></td>
  </tr>
  <?php $sn++; }?>
    </table>
  
<div>  
 <br /> <br />
</div>

</div>
</div>
</div>
</div>