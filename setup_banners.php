<?php
require 'config/data.config.php';
require 'phplib/class.database.php';

$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open();

$sql = "CREATE TABLE IF NOT EXISTS `portal_banners` (
  `banner_id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `subtitle` VARCHAR(255) NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `link_url` VARCHAR(255) NULL,
  `sort_order` INT DEFAULT 0,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$db->query($sql);

$check = $db->query("SELECT COUNT(*) AS total FROM portal_banners");
$r = $db->fetch_assoc();
if ($r['total'] == 0) {
    $db->query("INSERT INTO portal_banners (title, subtitle, image_path, link_url, sort_order, status)
                VALUES ('Rani Lakshmibai Memorial & Jhansi Fort', 'Clean City • Smart Governance', 'images/hero_banner.jpg', 'index.php', 1, 1)");
    $db->query("INSERT INTO portal_banners (title, subtitle, image_path, link_url, sort_order, status)
                VALUES ('Swachh Survekshan Mission Jhansi', 'Segregation at source & Clean Streets', 'images/swachh_jhansi.jpg', 'sbm.php', 2, 1)");
}
echo "portal_banners ready.\n";
