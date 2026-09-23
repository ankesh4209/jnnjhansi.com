<?php
//$res = $obj->showdata('department','name');
$res = $obj->showMasterData('location','name');
$id=$_GET['id'];
if(!empty($_GET['task']) and !empty($_GET['id']))
{
$aff=$obj->delete('location',$id);
//$aff=$obj->disableData('location',$id);
if($aff) header("location:index.php?page=location&msg=Selected record has been deleted successfully");	
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
  <div align="right" style="margin-bottom:5px;"><a href="index.php?page=addlocation"><img src="images/add_new.png" width="75" height="23" border="0" title="Add New" /></a></div>
</div>
 <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B3E0FF" id="table19" >
  <tr bgcolor="#D9EBFF">
    <td width="5%" align="center" class="newtxt1"><strong>S.No </strong></td>
    <td align="left" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>uke</strong></td>
  <td width="16%" height="25" colspan="2" align="center" class="newtxt1"><strong>Action</strong></td>
</tr>
<?php 
$sn=1;
while($row=mysql_fetch_object($res))
{
$bgcolor=($sn%2)?'#E8F6FF':'#D9EBFF';
 ?>
  <tr bgcolor="<?=$bgcolor?>">
    <td align="center" class="newtxt1"><?=$sn?></td>
    <td align="left" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=str_replace("\\","",stripslashes($row->name));?></td>
    <td width="8%" align="center" class="newtxt1"><a href="index.php?page=addlocation&id=<?=$row->id?>">Edit</a></td>
    <td width="8%" align="center" class="newtxt1"><a href="index.php?page=location&amp;id=<?=$row->id?>&amp;task=del" onClick="javascript:return confirm('Do you want to delete record No-<?=$sn;?>?');"  title="Click to delete">Delete</a></td>
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