<?php
 $conn=odbc_connect('demo3','','');
$sql="SELECT * FROM login";
$rs=odbc_exec($conn,$sql); 
odbc_fetch_row($rs) ;
$username=odbc_result($rs,"password");
 echo "<tr><td>$username</td>";
odbc_close($conn);  

?>
