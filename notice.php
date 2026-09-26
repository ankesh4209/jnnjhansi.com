<?php
// Transparent forwarder to notices.php with query string preserved
$query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?' . $_SERVER['QUERY_STRING'] : '';
header("Location: notices.php" . $query, true, 301);
exit;
?>
