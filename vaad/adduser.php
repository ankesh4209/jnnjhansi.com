<?php
$id=$_GET['id'];
if(!empty($id))
{
$res =$obj->showdata('users','name',$id);
$row = mysql_fetch_object($res);
$decodedpass=$obj->decodepassword($row->id);	
}
if(!empty($_POST['save']))
{
$UserName=trim($_POST['username']);  
$UserEmail=trim($_POST['email']); 
$Name=trim($_POST['name']);
$cuserid=$_POST['cuserid'];	
if(!empty($cuserid)) {
$aff=$obj->saveUser($cuserid);
if($aff) header("location:index.php?page=userlist&msg=User data has been saved successfully");
}
else {
$chk=$obj->checkDupUser($UserEmail,$UserName);
if($chk>0) { header("location:index.php?page=userlist&msg=Username already exits"); exit; }
$aff=$obj->saveUser();
$obj->SendingMail($UserName,$UserEmail,$Name);  // sending mail
if($aff) header("location:index.php?page=userlist&msg=User data has been saved successfully");
}
}
?>
<div id="user_detail">
	  <form name="user" method="post">
	  <input type="hidden" name="cuserid" value="<?=$id?>" />
          <table width="100%" border="0" cellpadding="3" cellspacing="1" bordercolor="#D7DBC1" >
            <tr>
              <td width="14%" height="20" align="left" class="newtxt"><strong>Login-id</strong></td>
              <td width="21%" height="20" align="left"><input type="text" value="<?=$row->username?>" class="txtarea_id" onkeyup="javascript:{this.value = this.value.toUpperCase();}" maxlength="5" name="username" /></td>
              <td width="15%" height="20" align="left" class="newtxt"><strong>Password</strong></td>
              <td width="18%" height="20" align="left"><input name="password" title="<?=$decodedpass?>" type="password" class="txtarea_id" value="<?=$decodedpass?>" /></td>
              <td width="14%" height="20" align="left" class="newtxt"><strong>Confirm Password </strong></td>
              <td width="18%" height="20" align="left"><input name="confpassword" type="password" class="txtarea_id" id="confpassword" value="<?=$decodedpass?>" /></td>
            </tr>
            <tr>
              <td height="20" align="left" class="newtxt"><strong>Name</strong></td>
              <td height="20" align="left"><input type="text" value="<?=$row->name;?>" class="txtarea_id" name="name" /></td>
              <td height="20" align="left" class="newtxt"><strong>E-mail</strong></td>
              <td height="20" align="left"><input type="text" class="txtarea_id" name="email" value="<?=$row->email;?>" /></td>
              
            </tr>
			 <tr>
              <td height="20" colspan="0" class="newtxt"><strong>User Type</strong></td>
              <td height="20" colspan="0" style="padding-top:15px;"><select name="type" class="txtarea_id">
			  <option value="">---User Type ---</option>
			  <option value="USER" <?php if($row->type=='USER'){echo"selected";}?>>USER</option>
			  
			  </select></td>
              <td height="20"></td>
              <td height="20">&nbsp;</td>
            </tr>
           
            <tr>
              <td width="14%" height="20" align="left" class="newtxt">&nbsp;</td>
              <td width="21%" height="20" align="left">&nbsp;</td>
              <td height="20" colspan="2" align="left" style="padding-top:15px;"><input name="save" type="submit" class="btn" value="Save" />
                &nbsp;&nbsp;
              <input name="cancel" type="button" onclick="document.location.href='index.php?page=userlist'" class="btn" value="Cancel" /></td>
              <td width="14%" height="20" align="left"></td>
              <td width="18%" height="20" align="left">&nbsp;</td>
            </tr>
          </table>
  </form>
</div>
			   
<SCRIPT language="JavaScript">
var frmvalidator  = new Validator("user");
frmvalidator.addValidation("username","req","Login id can not be blank!");
frmvalidator.addValidation("password","req","Password can not be blank!");
frmvalidator.addValidation("name","req","Name field can not be blank!");
frmvalidator.addValidation("email","req","email field can not be blank!"); 
frmvalidator.addValidation("email","email","Please enter valid email!");

frmvalidator.addValidation("type","dontselect=0","Please select type!");
//frmvalidator.addValidation("emailchk","selone_radio","Please select email reminder status!");
frmvalidator.setAddnlValidationFunction("DoCustomValidation"); 
function DoCustomValidation()
{
  var frm = document.forms["user"];
  if(frm.password.value != frm.confpassword.value)
  {
    sfm_show_error_msg('The Password and verified password don not match!',frm.password);
    return false;
  }
  else
  {
    return true;
  }
}

 </SCRIPT>