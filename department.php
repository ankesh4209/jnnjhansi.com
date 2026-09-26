<?php
$query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?' . $_SERVER['QUERY_STRING'] : '';
header("Location: departments.php" . $query, true, 301);
exit;
?>
