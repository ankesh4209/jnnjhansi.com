<?php
if(!empty($_GET['id']))
{
$res = $obj->showdata('contractor','name',$_GET['id']);
$row= mysql_fetch_object($res);
$name= $row->name;
$firm_name = $row->firm_name;
$contact_no = $row->contact_no;
$bank_accountno = $row->bank_accountno;
$bank_name = $row->bank_name;
$bank_branch = $row->bank_branch;
$ifsc_code = $row->ifsc_code;
$pancard  =  $row->pancard;
}
if(!empty($_POST['Save']))
{
$id=$_POST['id'];
if($id) $obj->saveContractorMaster($id);
else $obj->saveContractorMaster();	
header("location:index.php?page=contractor&msg=Data has been saved successfully");
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
    <td width="60%"><textarea id="name" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="name"><?=$name?></textarea></td>
</tr>
<tr>
    <td width="50%"><strong>Firm Name</strong></td>
	<td width="50%"><textarea id="firm_name" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="firm_name"><?=$firm_name?></textarea></td>
</tr>
<tr>
    <td width="50%"><strong>Contact Number</strong></td>
    <td width="25%" align="left"><input name="contact_no" id="contact_no" value="<?=$contact_no?>" type="text" class="newtxt"/></td>
    </tr>
<tr>
	<td width="50%"><strong>Bank Account</strong></td>
    <td width="50%"><input name="bank_accountno" id="bank_accountno" value="<?=$bank_accountno?>" type="text" class="newtxt"/></td>
</tr>
<tr>
     <td width="50%"><strong>Bank Name</strong></td>
     <td width="50%"><textarea id="bank_name" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="bank_name"><?=$bank_name?></textarea></td>
</tr>
<tr>
	   <td width="50%"><strong>Branch Name</strong></td>
	   <td width="50%"><textarea id="bank_branch" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="bank_branch"><?=$bank_branch?></textarea></td>
</tr>
<tr>
	   <td width="50%"><strong>IFSC Code</strong></td>
       <td width="50%" align="left"><input name="ifsc_code" id="ifsc_code" value="<?=$ifsc_code?>" type="text" class="newtxt"/></td>
</tr>
<tr>
	   <td width="50%"><strong>Pan Card No.</strong></td>
       <td width="50%" align="left"><input name="pancard" id="pancard" value="<?=$pancard?>" type="text" class="newtxt"/></td>
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
var frmvalidator  = new Validator("master");
frmvalidator.addValidation("name","req","Name field can not be blank!");
frmvalidator.addValidation("firm_name","req","Firm name field can not be blank!");
frmvalidator.addValidation("contact_no","req","Contact no field can not be blank!");
frmvalidator.addValidation("bank_accountno","req","Bank Account no field can not be blank!");
frmvalidator.addValidation("bank_name","req","Bank Name field can not be blank!");
frmvalidator.addValidation("bank_branch","req","Bank branch field can not be blank!");
frmvalidator.addValidation("ifsc_code","req","IFSC field can not be blank!");
frmvalidator.addValidation("pancard","req","Pan Card field can not be blank!");
 </SCRIPT>