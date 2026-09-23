<?php
include("../config/data.config.php");
include("../phplib/functions.library.php");
include("../phplib/class.database.php");
include("../phplib/data.constant.php");
//include("../config/permission.config.php");

$PAGE_NAME = "Welcome to the Administrative Panel";

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$id=$_GET['id'];
$id=explode("-",$id);
$id1=$id[0];
$id2=$id[1];

$update="update tbl_objection set obj_status='$id1' where obj_id='$id2'";
$db->query($update);
    
?>
