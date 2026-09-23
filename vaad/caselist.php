<?php
$id=$_GET['id'];
$task=$_GET['task'];

if(!empty($id) and $task=='del')
{
$aff=$obj->delete('addcase',$id);	
if($aff>0) header("location:index.php?page=caselist&msg=$id has been deleted successfully");
}
if(!empty($_POST['reset']))
{
$_SESSION['mrfFiler'] ="";
header("location:index.php?page=caselist");
}
$filter="WHERE status=1";
$r =$obj->showdata('addcase','id DESC','','',$filter);
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
<div id="user_detail"><h3 style="margin:0; padding:0;">View Municipal Corporation Cases</h3>
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
  <div align="right" style="margin-bottom:5px;"><a href="index.php?page=addcase"><img src="images/add_new.png" width="75" height="23" border="0" title="Add New" /></a></div>
</div>
 <table width="100%" cellpadding="0" cellspacing="1"  id="table19" class="tablecolor" >
 <tr>

 </tr>
  <tr bgcolor="#de8f30" class="theight">
  <th width="5%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>d-la-</strong></th>
  <th width="5%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>izdj.k la0</strong></th>
  <th width="10%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>i{kdkj </strong></th>

  <th width="10%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>lEifRr fooj.k </strong></th>


  <th width="10%"  align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>uxj fuxe vuqHkkx</strong></th>
  <th width="5%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>U;k;ky;@/kkjk</strong></th>  

  <th width="10%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>fu;r frfFk</strong></th>  

  <th width="10%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>lEcfU/kr odhy</strong></th>

   <th width="5%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>fLFkr</strong></th>
  <th width="15%" align="center" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>fu;a=.k</strong></th>
  </tr>
  <?php 
  
  $sn=1;
  $total=0;
  while($row = mysql_fetch_object($r))
  {
  $case_status= $row->case_status;
  if($case_status=='0'){$case_status="[kwyk";} 
  else{
	  
	  if($case_status=='COMPLETED'){$case_status="iwjk";} else{$case_status="yafcr";};
	  
	  }

   $receipt="<a href=\"javascript:void();\" onclick=\"javascript:window.open('print_receipt.php?id=$row->id','mywin','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=678,height=500,top=50,left=100');\"><img src=\"images/offered_ico.png\" alt=\"Receipt\" title=\"Receipt \" border=\"0\" width=\"16\" height=\"16\"></a>";
   $party         = str_replace("\\","",stripslashes($row->party));
    $property_desc= str_replace("\\","",stripslashes($row->property_desc));
	$department=$obj->getName('department',$row->department);
	$department=str_replace("\\","",stripslashes($department));
	$ref_lawer =$obj->getName('advocate',$row->ref_lawer);
	$ref_lawer= str_replace("\\","",stripslashes($ref_lawer));
  ?>
  <tr bgcolor="<?=$row_color?>">
      <td align="center" class="newtxt1"><?=$row->id?></td>
    <td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=$row->activity_no?></td>
	<td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=$party?></td>
	<td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=$property_desc?></td>
    <td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=$department?></td>
	<td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=$row->dhara?></td>
	<td height="25" align="center" class="newtxt1"><?php echo strftime("%d-%m-%Y", strtotime($row->schedule_date));?></td>
	<td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=$ref_lawer?></td>
		

    <td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=$case_status?></td>
	<td align="center" class="newtxt1"><?=$receipt?>&nbsp;<a href="caselistinfo.php?gid=<?=$row->id?>&keepThis=true&TB_iframe=true&height=600&width=600" title="Click to Case Details" class="thickbox">Details</a>
&nbsp;<a class="txtcolor"  href="index.php?page=addcase&task=edit&id=<?=$row->id?>" <?php if($_SESSION['usertype']!='SUPER' ) {?> onClick="javascript:alert('You have not permission');return false;" <?php } ?>  title="Click to Edit this record">Edit</a>&nbsp;
  <a class="txtcolor"  href="index.php?page=caselist&id=<?=$row->id?>&task=del" <?php if($_SESSION['usertype']=='SUPER'){?> onClick="javascript:return confirm(' Are you sure to delete-<?=$row->id?>?');"  title="" <?php } else { ?>onClick="javascript:alert('You have not permission!');return false;" title="Permission Denied" <?php } ?> >Delete</a></td>
  </tr>
  <?php $sn++; $total++;} ?>
   <tr  bgcolor="#de8f30"><td colspan="6" align="center"></td><td align="center" class="newtxt1" bgcolor="#FF9933" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>dqy</strong></td><td bgcolor="#FF9933" class="newtxt1" align="center"><span class="style1"><?=$total?></span></td>
   <td colspan="8"></td></tr>
  <?php if($num<1){ ?>
  <tr style="background-color:#FF9900; color:#FF0000;" class="newtxt1"><td colspan="10" align="center">No Records</td></tr>
  <?php } ?>
    </table>
  </div>
</div>
</div>
</div>