<?php
if(!empty($_GET['id']))
{
$res = $obj->showdata('advocate','name',$_GET['id']);
$row= mysql_fetch_object($res);
$name= stripslashes($row->name);
$address = stripslashes($row->address);
$licence_no = $row->licence_no;
$contact_no = $row->contact_no;
$app_date = $row->app_date;

}
if(!empty($_POST['Save']))
{
$id=$_POST['id'];
if($id) $obj->saveAdvocateMaster($id);
else $obj->saveAdvocateMaster();	
header("location:index.php?page=advocate&msg=Data has been saved successfully");
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
<table width="80%" align="center" >
  <tr>
    <td width="20%"><strong>Name</strong></td>
    <?php if($_GET['id']!='') {?>
     <td width="60%"><span style="font-family: 'kruti_dev_010regular';font-size:15px;"><?=$name?></span></td>
    <?php } else { ?>
     <td width="60%"><input type="text" name="name" style="font-family: 'kruti_dev_010regular';font-size:15px;" id="name" value=""></td>
    <?php } ?>
</tr>
<tr>
    <td width="50%"><strong>Address</strong></td>
	<td width="50%"><textarea id="address" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="address"><?=$address?></textarea></td>
</tr>
<tr>
    <td width="50%"><strong>licence Number</strong></td>
    <td width="25%" align="left" bgcolor=""><input name="licence_no" id="licence_no" value="<?=$licence_no?>" type="text" class="newtxt"/></td>
    </tr>
	<tr>
    <td width="50%"><strong>Contact Number</strong></td>
    <td width="25%" align="left" bgcolor=""><input name="contact_no" id="contact_no" value="<?=$contact_no?>" type="text" class="newtxt"/></td>
    </tr>
<tr>
	<td width="50%"><strong>Appoint Date</strong></td>
<td width="25%" align="left" bgcolor=""><input name="app_date" id="app_date" value="<?=$app_date?>" type="text" class="txtarea_Mid ipbutton"/></td>
</tr>
<tr>
    <td width="40%" align="right"><input name="Save" type="submit" class="btn" value="Save"/></td>
	<td width="40%" ><input name="reset" type="reset" class="btn" value="Reset" /></td>

  </tr>
  </table>
  </form>
<div>  
  <div align="center"><br />
       
      <br /> <br /> <br /> <br />
  </div>
</div>

</div>
</div>
</div>
</div>
<SCRIPT language="JavaScript">
window.onload = function(){
			new JsDatePick({
			useMode:2,
			target:"app_date",
			dateFormat:"%Y-%m-%d"
			})
			
		};
var frmvalidator  = new Validator("master");
frmvalidator.addValidation("name","req","Title field can not be blank!");
frmvalidator.addValidation("address","req","address field can not be blank!");
frmvalidator.addValidation("licence_no","req","licence field can not be blank!");
frmvalidator.addValidation("contact_no","req","contact field can not be blank!");
frmvalidator.addValidation("app_date","req","date field can not be blank!");


 </SCRIPT>