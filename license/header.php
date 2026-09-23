<?php
ob_start();
include_once('class.hrrecruitment.php');
$page = $_GET['page'];
$chkpage = $page . '.php';
$page = $_GET['page'];
$obj = new recruitment();

function getDropDownPageWise($tableval, $selval) {
    $obj = new recruitment();
    $res = $obj->getDropDown($tableval, $selval);
    while ($row = mysql_fetch_object($res)) {
        $ListArray[$row->id] = $row->name;
    }
    $option = '';
    foreach ($ListArray as $key => $val) {
        $selected = '';
        if ($key == $selval) {
            $selected = 'selected';
        }
       $val= str_replace("\\","",stripslashes($val));
//$option.="<option value='$key' $selected>$val</option>";
        $option.="<option style=\"font-family: 'kruti_dev_010regular';font-size:15px;width:130px;\" value='$key' $selected>$val</option>";
    }
    return $option;
}

switch ($page) {

    case 'addlicense':
        $active2 = 'active';
        break;
    case 'licenselist':
        $active3 = 'active';
        break;
    case 'adduser':
        $active4 = 'active';
        break;
    case 'userlist':
        $active5 = 'active';
    case 'downloadform':
        $active6 = 'active';
        break;
    case 'report1':
    case 'report2':
    case 'report3':
    case 'report4':
    case 'report5':
    case 'mrf_closed':
    case 'chanel_closed':
    case 'recoffered':
    case 'sbu_open_closed':
        $active00 = 'active';
        break;
    case 'location':
    case 'addlocation':
    case 'recruiters':
    case 'addrecruiter':
    case 'sbu':
    case 'addsbu':
    case 'sbuhead':
    case 'addsbuhead':
    case 'level':
    case 'addlevel':
    case 'position':
    case 'addposition':
    case 'source':
    case 'addsource':
        $active_master = 'active';
        break;
}
//
if (!empty($_POST['loginbtn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($obj->login($username, $password) > 0) {
        header("location:index.php?page=licenselist");
    } else {
        header("location:index.php?msg=Please enter valid username and password");
    }
}
if (empty($page) or $page == 'main') {
    ?>
    <div class="menu_login">
        <div id="user_login">
            <form name="login" action="#" method="POST">
                <label>Username:</label>
                <input name="username" type="text" value="" maxlength="5" onkeyup="javascript:{
                                this.value = this.value.toUpperCase();
                            }"/>
                &nbsp;<label>Password:</label>
                <input name="password" type="password" value="" />
                <input type="submit" name="loginbtn" value="Login" class="btn"/>
                &nbsp;&nbsp;<span style="font-size:11px; display:none;" class="forgot_ps"><a href="forgotpassword.html?keepThis=true&TB_iframe=true&height=120&width=350" title="Forgot Your Password" class="thickbox">Forgot Password</a></span>
            </form>
            <SCRIPT language="JavaScript">
                var frmvalidator = new Validator("login");
                frmvalidator.addValidation("username", "req", "Username can not be blank!");
                frmvalidator.addValidation("username", "maxlen=5", "Please enter valid username");
                frmvalidator.addValidation("password", "req", "Password can not be blank!");
            </SCRIPT>
        </div>
    </div>
    <?php
} elseif ($_SESSION['usertype'] == 'SUPER') {
    ?>   <div class="menu">
        <ul>
            <li class="<?= $active_master ?> first"><a href="index.php?page=location">Masters</a></li>
            <li class="<?= $active3 ?>"><a href="index.php?page=licenselist">View License</a></li>
            <li class="<?= $active2 ?>"><a href="index.php?page=addlicense">New/Renew License</a></li>
            <li class="<?= $active5 ?>"><a href="index.php?page=userlist">View User</a></li>
            <li class="<?= $active4 ?>"><a href="index.php?page=adduser">Add User</a></li>
            <li class="<?= $active00 ?>"><a href="index.php?page=report1">Reports</a></li>
            <li class="<?= $active6 ?>"><a href="index.php?page=downloadform">Download Form</a></li>

        </ul>
    </div>
<?php } elseif ($_SESSION['usertype'] == 'USER') { ?>
    <div class="menu">
        <ul>

            <li class="<?= $active3 ?>"><a href="index.php?page=licenselist">View License</a></li>
            <li class="<?= $active2 ?>"><a href="index.php?page=addlicense">New/Renew License</a></li>
            <li class="<?= $active6 ?>"><a href="index.php?page=downloadform">Download Form</a></li>
        </ul>
    </div>
<?php } else { ?>

    <div class="menu">
        <ul>

            <li class="<?= $active3 ?>"><a href="index.php?page=licenselist">View License</a></li>
            <li class="<?= $active2 ?>"><a href="index.php?page=addlicense">New/Renew License</a></li>
            <li class="<?= $active6 ?>"><a href="index.php?page=downloadform">Download Form</a></li>

        </ul>
    </div>			
<?php } if (!$_SESSION['usertype']) { ?>

    <div class="menu">
        <ul>

            <li class="<?= $active6 ?>"><a href="downloadform.php">Download Form</a></li>

        </ul>
    </div>	           
<?php } ?>