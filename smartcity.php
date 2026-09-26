<?php
$query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?' . $_SERVER['QUERY_STRING'] : '';
header("Location: smart_city.php" . $query, true, 301);
exit;
?>
