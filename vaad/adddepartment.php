<?php
if(!empty($_GET['id']))
{
$res = $obj->showdata('department','name',$_GET['id']);
$row= mysql_fetch_object($res);
$name= $row->name;
}
if(!empty($_POST['Save']))
{
$id=$_POST['id'];
if($id) $obj->saveMaster('department',$id);
else $obj->saveMaster('department');	
header("location:index.php?page=department&msg=Data has been saved successfully");
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
    <td width="25%"><strong>Department Name</strong></td>
    <td width="35%"><input type="text" name="name" style="font-family: 'kruti_dev_010regular';font-size:15px;" id="name" value="<?=$name?>"></td>
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
frmvalidator.addValidation("name","req","name field can not be blank!");
 </SCRIPT>