<?php
$res = $obj->showMasterData('advocate','name');
$id=$_GET['id'];
if(!empty($_GET['task']) and !empty($_GET['id']))
{
$aff=$obj->delete('advocate',$id);
if($aff) header("location:index.php?page=advocate&msg=Selected record has been deleted successfully");	
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
  <div align="right" style="margin-bottom:5px;"><a href="index.php?page=addadvocate"><img src="images/add_new.png" width="75" height="23" border="0" title="Add New" /></a></div>
</div>
 <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#efcca4" id="table19" >
  <tr bgcolor="#de8f30">
  <td width="5%" align="center" class="newtxt1"><strong>S.No </strong></td>
  <td width="22%" height="25" align="left" class="newtxt1"><strong>Name</strong></td>
   <td width="22%" height="25" align="left" class="newtxt1"><strong>License No.</strong></td>
   <td width="22%" height="25" align="left" class="newtxt1"><strong>Contact No.</strong></td>
    <td width="22%" height="25" align="left" class="newtxt1"><strong>App. Date</strong></td>
  <td width="16%" height="25"  align="center" class="newtxt1"><strong>Action</strong></td>
</tr>
 <?php 
$sn=1;
while($row=mysql_fetch_object($res))
{
$bgcolor=($sn%2)?'#fff':'#f3f3f3';
 ?>
  <tr bgcolor="<?=$bgcolor?>">
    <td align="center" class="newtxt1"><?=$sn?></td>
    <td align="left" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;"><?=str_replace("\\","",stripslashes($row->name));?></td>
	<td align="left" class="newtxt1"><?=$row->licence_no?></td>
	<td align="left" class="newtxt1"><?=$row->contact_no?></td>
	<td align="left" class="newtxt1"><?=$row->app_date?></td>
    <td width="8%" align="center" class="newtxt1"><a href="index.php?page=addadvocate&id=<?=$row->id?>">Edit</a></td>
    <!--<td width="8%" align="center" class="newtxt1"><a href="index.php?page=advocate&id=<?=$row->id?>&task=del" onClick="javascript:return confirm('Do you want to delete record No-<?=$sn;?>?');"  title="Click to delete">Delete</a></td>-->
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