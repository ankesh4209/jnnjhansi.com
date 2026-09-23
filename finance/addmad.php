<?php
if(!empty($_GET['id']))
{
$res			=	$obj->showdata('mad','',$_GET['id']);
$row			=	mysql_fetch_object($res);
$name			=	$row->name;
$ref_letno		=	$row->ref_letno;
$mad_tot_amount =	$row->mad_tot_amount;
$bank_name		=	$row->bank_name;
$bank_acno		=	$row->bank_acno;
$receive_date	=	$row->receive_date;
$comments		=	$row->comments;
$total_mad_ava_amt=$row->total_mad_ava_amt;

}
if(!empty($_POST['Save']))
{
$id=$_POST['id'];
if($id) $obj->saveMasterMad($id);
else $obj->saveMasterMad();	
header("location:index.php?page=mad&msg=Data has been saved successfully");
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
<form name="master" method="post">
<input type="hidden" value="<?=$_GET['id']?>" name="id" />
<input type="hidden" name="userid" value="<?=$_SESSION['userid']?>">
<table width="40%" align="center" >
  <tr>
    <td width="25%" style="font-family: 'kruti_dev_010regular';font-size:18px;">uke</td>
    <td width="25%"><input name="name" id="name" style="font-family: 'kruti_dev_010regular';font-size:18px;" value="<?=$name?>" type="text" class="newtxt"/></td>
  </tr>
  <tr>
   <td width="25%" align="left" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">i= la[;k</td>
   <td width="25%" align="left" ><input name="ref_letno" id="ref_letno" value="<?=$ref_letno?>" type="text" class="newtxt"/></td>
    </tr>
	<tr>
    <td width="25%" align="left" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">en jkf&ldquo;k</td>
    <td width="25%" align="left" ><input name="mad_tot_amount" id="mad_tot_amount" value="<?=$mad_tot_amount?>" type="text" class="newtxt"/></td>
   </tr>
   <tr>
   <td width="25%" align="left" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">cSd dk uke </td>
   <td width="25%" align="left"><label>
   <select class="txtarea_id" name="bank_name" id="bank_name" style="font-family: 'kruti_dev_010regular';font-size:15px;width:130px;" >
   <option value="0" style="font-family: 'kruti_dev_010regular';font-size:15px;">cSd pqu</option> <?=getDropDownPageWise('bank',$row->bank_name)?></select></td>
   </tr>
   <tr>
   <td width="25%" align="left" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">[kkrk la[;k</td>
   <td width="25%" align="left" class="newtxt1" ><input name="bank_acno" id="bank_acno" value="<?=$bank_acno?>" type="text" class="newtxt"/></td>
   </tr>
   <tr>
   <td width="25%" align="left" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">izkIr fnukad</td>
   <td width="25%" align="left" ><input name="receive_date" id="receive_date" value="<?=$receive_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
   </tr>
   <tr>
   <td width="25%" colspan="0" align="left"  class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;">fooj.k</td>
   <td width="25%" colspan="0" align="left"><textarea id="comments" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="comments"><?=$comments?></textarea></td>
    </tr>
   <tr>
   <td width="25%" align="left" class="newtxt1" style="font-family: 'kruti_dev_010regular';font-size:18px;" >dqy cpk jkf&ldquo;k</td>
   <td width="25%" align="left"><input readonly="true" name="total_mad_ava_amt" id="total_mad_ava_amt" value="<?=$total_mad_ava_amt?>" type="text" class="newtxt"/></td>
   </tr>
  </table>
  <div align="center"><br />
  <input name="back" type="reset"  class="btn" style="font-family: 'kruti_dev_010regular';font-size:15px;" value="nqckjk" />&nbsp;&nbsp;<input name="Save" type="submit" style="font-family: 'kruti_dev_010regular';font-size:15px;" class="btn" value="cpk"/>
   <br />
   </div>
  </form>
<div>  
</div>

</div>
</div>
</div>
</div>
<SCRIPT language="JavaScript">
window.onload = function(){
			new JsDatePick({
			useMode:2,
			target:"receive_date",
			dateFormat:"%Y-%m-%d"
			});
		};
var frmvalidator  = new Validator("master");
frmvalidator.addValidation("name","req","Title field can not be blank!");
frmvalidator.addValidation("ref_letno","req","Please enter reference no.!");
frmvalidator.addValidation("mad_tot_amount","req","Please enter tottal mad amount!");
frmvalidator.addValidation("bank_name","dontselect=0","Please select bank!");
frmvalidator.addValidation("bank_acno","req","Account no.can not be blank!");
frmvalidator.addValidation("receive_date","req","Date can not be blank!");
//frmvalidator.addValidation("total_mad_ava_amt","req","Title field can not be blank!");
 </SCRIPT>