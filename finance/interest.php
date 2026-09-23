<?php
$id=$_GET['id'];
$task=$_GET['task'];
//if(!empty($_GET['stval'])) $sbu_dis1='disabled';
//if(!empty($_GET['sid'])) $status_dis='disabled';
if(!empty($id) and $task=='del')
{
$aff=$obj->delete('interest',$id);	
if($aff>0) header("location:index.php?page=interest&msg=$id has been deleted successfully");
}
if(!empty($_POST['reset']))
{
$_SESSION['mrfFiler'] ="";
header("location:index.php?page=payamtlist");
}
if(!empty($_POST['filter']))
{
$filter='';
if(!empty($_POST['startdate']) and !empty($_POST['enddate']))
$filter.=" mrfdate BETWEEN '".$_POST['startdate']."' AND '".$_POST['enddate']."'";
if(!empty($_POST['status']))
if($_POST['status']=='all') 
{
//$filter='all'; 
$filter.=" AND status in('open','closed','hold')";
} 
else
{
$filter.=" AND status='".$_POST['status']."'";
}
if(!empty($_POST['emp_sbu']))
$filter.=" AND sbu=".$_POST['emp_sbu'];

$filter = str_replace("WHERE AND","WHERE",'WHERE'.$filter);
$r =$obj->showdata('interest','id DESC','','',$filter);
}
else
{
$filter="WHERE status=1";
$r =$obj->showdata('interest','id DESC','','',$filter);
}
$num=mysql_num_rows($r);
$_SESSION['mrfFiler'] = $filter;

?>
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>

<div id="con_container">
<!--User Detail Start-->
<div id="user_detail">
<p>
<form name="frm" method="post"  onsubmit="return filtervalid();" enctype="application/x-www-form-urlencoded">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
</table>
</form>
</p>
</div>
<!--User Detail End-->
<div id="content_area">
<div class="height_adj">
<div align="left">&nbsp;&nbsp;&nbsp;
<div >
  <div align="right" style="margin-bottom:5px;"><a href="index.php?page=addinterest"><img src="images/add_new.png" width="75" height="23" border="0" title="Add New" /></a></div>
</div>
 <table width="100%" cellpadding="0" cellspacing="1" id="table19" class="tablecolor">
 <tr>
 </tr>
  <tr bgcolor="#de8f30" class="theight">
    <th width="10%" align="center" style="font-family: 'kruti_dev_010regular';font-size:18px;"><strong>la[;k</strong></th>
  <th width="10%" align="center" style="font-family: 'kruti_dev_010regular';font-size:18px;"><strong>cSd dk uke</strong></th>
  <th width="20%"  align="center" style="font-family: 'kruti_dev_010regular';font-size:18px;"><strong>[kkrk la[;k</strong></th>
  <th width="20%"  align="center" style="font-family: 'kruti_dev_010regular';font-size:18px;"><strong>lwn jkf&ldquo;k</strong></th>
  <th width="10%" align="center" style="font-family: 'kruti_dev_010regular';font-size:18px;"><strong>tek fnukad</strong></th>
  <th colspan="0" width="20%" align="center" style="font-family: 'kruti_dev_010regular';font-size:18px;"><strong>fu;a=.k</strong></th>
  </tr>
  <?php 
  
  $sn=1;
  $total=0;
  while($row = mysql_fetch_object($r))
  {
  ?>
  <tr bgcolor="<?=$row_color?>">
     <td align="center" class="newtxt1"><?=$sn?></td>
    <td height="25" align="center" style="font-family: 'kruti_dev_010regular';font-size:15px;"><?=$obj->getName('bank',$row->bankid)?></td>
	<td height="25" align="center" class="newtxt1"><?=$row->accountno?></td>
	<td height="25" align="center" class="newtxt1"><?=$row->interest_amt?></td>
    <td height="25" align="center" class="newtxt1"><?php echo strftime("%d-%m-%Y", strtotime($row->interest_date));?></td>
	<td align="center" class="newtxt1"><a class="txtcolor" href="index.php?page=addinterest&task=edit&id=<?=$row->id?>" <?php if($_SESSION['usertype']!='SUPER' ) {?> onClick="javascript:alert('You have not permission');return false;" <?php } ?>  title="Click to Edit this record">Edit</a>&nbsp;
  <a class="txtcolor" href="index.php?page=interest&id=<?=$row->id?>&task=del" <?php if($_SESSION['usertype']=='SUPER'){?> onClick="javascript:return confirm(' Are you sure to delete-<?=$row->id?>?');"  title="" <?php } else { ?>onClick="javascript:alert('Are you sure!');return false;" title="Permission Denied" <?php } ?> >Delete</a></td>
  </tr>
  <?php $sn++; $total++;} ?>
   <tr  bgcolor="#de8f30"><td colspan="3" align="center"></td><td align="center" class="newtxt1" bgcolor="#FF9933" style="font-family: 'kruti_dev_010regular';font-size:20px;"><strong>dqy</strong></td><td bgcolor="#FF9933" class="newtxt1" align="center"><span class="style1"><?=$total?></span></td>
   <td colspan="6"></td></tr>
  <?php if($num<1){ ?>
  <tr style="background-color:#FF9900; color:#FF0000;" class="newtxt1"><td colspan="8" align="center">No Records</td></tr>
  <?php } ?>
    </table>
  </div>
</div>
</div>
</div>
