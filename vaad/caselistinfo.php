<?php
session_start();
include_once('class.vaad.php');
ini_set('display_errors', 'Off');
error_reporting(0);
$obj = new vaad();
$id=$_GET['id'];
$gid= $_GET['gid'];
$name="";
$task=$_GET['task'];
if(!empty($id) and $task=='edit')
{
$res = $obj->showdata('addcaseinfo','',$_GET['id']);
$ro= mysql_fetch_object($res);
$comments= $ro->comments;
$court = $ro->court;
$schedule_date = $ro->schedule_date;
$result_date = $ro->result_date;
$ref_lawer=$ro->ref_lawer;
//header("location:caselistinfo.php?gid=$gid&id=$id&task=edit");

}
//$targetdate= date("Y-m-d",mktime(0,0,0,date('m')+1,date('d'),date('Y')));
if(!empty($_POST['Save']))
{

if(!empty($id) and $task=='edit'){
$n=$obj->saveCaseDetailsData($id);}
else{
$n=$obj->saveCaseDetailsData();
}
if($n>0) header("location:caselistinfo.php?gid=$gid&msg=Data saved successfully");
}
//to show records
if(!empty($id) and $task=='del')
{
$aff=$obj->delete('addcaseinfo',$id);	
if($aff>0) header("location:caselistinfo.php?msg=$id&gid=$gid has been deleted successfully");
}
if(!empty($_POST['reset']))
{
$_SESSION['mrfFiler'] ="";
header("location:caselistinfo.php?gid=$gid");
}
$filter="WHERE status=1 and caseid='".$_GET['gid']."'";
$res2 =$obj->showdata('addcaseinfo','id DESC','','',$filter);
$num=mysql_num_rows($res2);
$_SESSION['mrfFiler'] = $filter;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/style.css" type="text/css" rel="stylesheet" />
<script src="js/validatior.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="jsDatePick.min.1.3.js"></script>

 <!-- required plugins -->
<script type="text/javascript" src="js/keyboard.js"></script>
<!--[if IE]><script type="text/javascript" src="js/jquery.bgiframe.min.js"></script><![endif]-->
<!-- jquery.datePicker.js -->
<script type="text/javascript" src="js/jquery.datePicker.js"></script>
<!-- datePicker required styles -->
<link rel="stylesheet" href="css/keyboard.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
<link rel="stylesheet" href="css/datePicker.css" type="text/css" media="screen" />
 <!--User Detail Start-->
     <form name="addmrf" method="post" action="">
	 <input type="hidden" name="userid" value="<?=$_SESSION['userid']?>">
	  <input type="hidden" name="caseid" value="<?=$gid?>">

	 <!-- latter removed fields -->	 
        <!-- End latter removed fields -->          
		
        <!--User Detail End-->
        <div id="content_area">
          <div class="height_adj">
            <div align="left">&nbsp;&nbsp;&nbsp;
              <table width="100%" cellpadding="10" cellspacing="0" border="0" bgcolor="#B3E0FF" >
			  
				<tr>
                  <td width="25%" colspan="1" align="center" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;"> fooj.k</span></td>
               <td width="25%" colspan="3" align="left" bgcolor="#E8F6FF"><textarea name="comments" id="comments" style="font-family: 'kruti_dev_010regular';font-size:15px;width:180px;" rows="5" cols="8"><?=$comments?></textarea></td>
                </tr>
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">	vUrfjr U;k;ky;</span></td>
               <td width="25%" align="left" bgcolor="#E8F6FF">
               <select  name="court" id="court" style="font-family: kruti_dev_010regular;font-size:18px; width:180px;">
            <option value="0" style="font-family: kruti_dev_010regular;font-size:16px;">U;k;ky; </option>
            <?php echo $obj->getDropDownPageWisedetails('court',$court);?></select></td>
                </tr>
				
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">fu;r frfFk</td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input  name="schedule_date" id="schedule_date" value="<?=$schedule_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
                </tr>
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;">fu.kZ; frfFk</td>
               <td width="25%" align="left" bgcolor="#E8F6FF"><input  name="result_date" id="result_date" value="<?=$result_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
                </tr>
				
				<tr>
                  <td width="25%" align="center" bgcolor="#E8F6FF" class="newtxt1"><span style="font-family: kruti_dev_010regular;font-size:18px;">	izfrLFkkfir odhy</span></td>
               <td width="25%" align="left" bgcolor="#E8F6FF">
               <select  name="ref_lawer" id="ref_lawer" style="font-family: kruti_dev_010regular;font-size:18px;width:180px;">
            <option value="0" style="font-family: kruti_dev_010regular;font-size:15px;">lEcfU/kr odhy</option>
            <?php echo $obj->getDropDownPageWisedetails('advocate',$ref_lawer);?></select></td>
                </tr>
				
              </table>
              <div>
                <div align="center"><br />
                  <input name="back" type="reset"  class="btn" style="font-family: kruti_dev_010regular;font-size:18px;" value="nqckjk" />&nbsp;&nbsp;<input style="font-family: kruti_dev_010regular;font-size:18px;" name="Save" type="submit" class="btn" value="cpk"/>                  <br />
                </div>
              </div>
            </div>
          </div>
        </div>
</form>
<!--*********************************************************Show Records******************************************************************************* -->
<div id="con_container">
<!--User Detail Start-->

<!--User Detail End-->
<div id="content_area">
<div class="height_adj">
<div align="left">&nbsp;&nbsp;&nbsp;

 <table width="100%" cellpadding="0" cellspacing="1"  id="table19" class="tablecolor" >
 <tr>

 </tr>
  <tr bgcolor="#de8f30" class="theight">
  <th width="5%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>d-la-</strong></th>
  <th width="15%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>izfrLFkkfir odhy</strong></th>
  <th width="20%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>vUrfjr U;k;ky;</strong></th>
  <th width="20%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>fooj.k</strong></th>
  <th width="20%" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>fu;r frfFk</strong></th>
  <th width="20%" align="center" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>fu;a=.k</strong></th>
  </tr>
  <?php 
  
  $sn=1;
  $total=0;
  while($row = mysql_fetch_object($res2))
  {
 
  ?>
  <tr bgcolor="<?=$row_color?>">
      <td align="center" class="newtxt1"><?=$sn?></td>
	<td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=$obj->getName('advocate',$row->ref_lawer)?></td>
	<td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=$obj->getName('court',$row->court)?></td>
	<td height="25" align="center" class="newtxt1" style="font-family: kruti_dev_010regular;font-size:18px;"><?=$row->comments?></td>
	<td height="25" align="center" class="newtxt1"><?php echo strftime("%d-%m-%Y", strtotime($row->schedule_date));?></td>
	<td align="center" class="newtxt1">
&nbsp;<a class="txtcolor"  href="caselistinfo.php?gid=<?=$gid?>&id=<?=$row->id?>&task=edit" <?php if($_SESSION['usertype']!='SUPER' ) {?> onClick="javascript:alert('You have not permission');return false;" <?php } ?>  title="Click to Edit this record">Edit</a>&nbsp;
  <a class="txtcolor"  href="caselistinfo.php?id=<?=$row->id?>&gid=<?=$gid?>&task=del" <?php if($_SESSION['usertype']=='SUPER'){?> onClick="javascript:return confirm(' Are you sure to delete-<?=$row->id?>?');"  title="" <?php } else { ?>onClick="javascript:alert('You have not permission!');return false;" title="Permission Denied" <?php } ?> >Delete</a></td>
  </tr>
  <?php $sn++; $total++;} ?>
   <tr  bgcolor="#de8f30"><td colspan="4" align="center"></td><td align="center" class="newtxt1" bgcolor="#FF9933" style="font-family: kruti_dev_010regular;font-size:18px;"><strong>dqy</strong></td><td bgcolor="#FF9933" class="newtxt1" align="center"><span class="style1"><?=$total?></span></td>
   <td colspan="8"></td></tr>
  <?php if($num<1){ ?>
  <tr style="background-color:#FF9900; color:#FF0000;" class="newtxt1"><td colspan="6" align="center">No Records</td></tr>
  <?php } ?>
    </table>
  </div>
</div>
</div>
</div>
</body>
</html>
<SCRIPT language="JavaScript">
window.onload = function(){
			new JsDatePick({
			useMode:2,
			target:"schedule_date",
			dateFormat:"%Y-%m-%d"
			}),
				new JsDatePick({
			useMode:2,
			target:"result_date",
			dateFormat:"%Y-%m-%d"
			})
			
		};
var frmvalidator  = new Validator("addmrf");
frmvalidator.addValidation("comments","req","Please enter comments");
frmvalidator.addValidation("court","dontselect=0","Please select court!");
frmvalidator.addValidation("schedule_date","req","Please select schedule date.!");
frmvalidator.addValidation("ref_lawer","dontselect=0","Please select advocate!");
 </SCRIPT>