<?php
if(!empty($_GET['id']))
{
$res = $obj->showdata('vehicle','name',$_GET['id']);
$row= mysql_fetch_object($res);
$name= $row->name;
}
if(!empty($_POST['Save']))
{
$id=$_POST['id'];
if($id) $obj->saveMaster('vehicle',$id);
else $obj->saveMaster('vehicle');	
header("location:index.php?page=vehicle&msg=Data has been saved successfully");
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
<table width="40%" align="center" >
  <tr>
    <td width="13%"><strong>वाहन के नाम</strong></td>
    <td width="25%"><script language="javascript">CreateCustomHindiTextArea("name",'<?=$name?>','10','5',false);
</script></td>
    <td width="15%" ><input name="Save" type="submit" class="btn" value="Save" /></td>
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
frmvalidator.addValidation("name","req","Title field can not be blank!");
 </SCRIPT>