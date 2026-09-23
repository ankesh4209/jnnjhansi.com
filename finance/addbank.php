<?php
if(!empty($_GET['id']))
{
$res = $obj->showdata('bank','name',$_GET['id']);
$row= mysql_fetch_object($res);
$name   = $row->name;
$branch      = $row->branch;
$bank_acno   = $row->bank_acno;
$working_proc= $row->working_proc;

}
if(!empty($_POST['Save']))
{
$id=$_POST['id'];
if($id) $obj->saveMasterBankData($id);
else $obj->saveMasterBankData();	
header("location:index.php?page=bank&msg=Data has been saved successfully");
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
<table width="60%" align="center" >
  <tr>
    <td width="25%"><strong>Bank Name</strong></td>
    <td width="35%" style="font-family: kruti_dev_010regular;font-size:18px;"><textarea id="name" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="name"><?=$name?></textarea></td>
  </tr>
   <tr>
    <td width="25%"><strong>Branch</strong></td>
    <td width="35%"><textarea id="branch" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="branch"><?=$branch?></textarea></td>
  </tr>
   <tr>
    <td width="25%"><strong>Bank Account Number</strong></td>
    <td width="35%" align="left"><input name="bank_acno" id="bank_acno" value="<?=$bank_acno?>" type="text" class="newtxt"/></td>
  </tr>
   <tr>
    <td width="25%"><strong>Working Procedure</strong></td>
    <td width="35%"><textarea id="working_proc" style="font-family: 'kruti_dev_010regular';font-size:15px;" rows="5" cols="8" name="working_proc"><?=$working_proc?></textarea></td>
  </tr>
  <tr>
    <td width="30%" align="right"><input name="Save" type="submit" class="btn" value="Save" /></td>
	<td width="30%" ><input name="reset" type="reset" class="btn" value="Reset" /></td>
  </tr>
  </table>
  </form>
<div>  
  <div align="center"><br />
       
      <br /> <br />
  </div>
</div>

</div>
</div>
</div>
</div>
<SCRIPT language="JavaScript">
var frmvalidator  = new Validator("master");
frmvalidator.addValidation("name","req","Title field can not be blank!");
 </SCRIPT>