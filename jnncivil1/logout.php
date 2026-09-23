<?php
session_start();
unset($_SESSION['User_Email']);
unset($_SESSION['ContractorId']);
unset($_SESSION['hashiyatkishreni']);
//session_destroy($_SESSION['User_Email']);
//session_destroy($_SESSION['ContractorId']);
//session_destroy($_SESSION['hashiyatkishreni']);

//session_destroy();
header ("Location: index.php"); 
?>	