<?php
require 'config/data.config.php';
require 'phplib/class.database.php';

$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
if (!$db->open()) {
    die("Connection failed: " . $db->error());
}

echo "Connected successfully. Setting up database tables...\n";

// 1. Create site_settings table
$sql1 = "CREATE TABLE IF NOT EXISTS `site_settings` (
  `setting_id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT NULL,
  `setting_group` VARCHAR(50) NOT NULL,
  `label` VARCHAR(150) NOT NULL,
  `field_type` VARCHAR(30) DEFAULT 'text',
  `help_text` VARCHAR(255) NULL,
  `sort_order` INT DEFAULT 0,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$db->query($sql1);
echo "Table `site_settings` checked/created.\n";

// 2. Create portal_services table
$sql2 = "CREATE TABLE IF NOT EXISTS `portal_services` (
  `service_id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'services',
  `subtitle` VARCHAR(255) NULL,
  `action_text` VARCHAR(50) DEFAULT 'Apply Online →',
  `link_url` VARCHAR(255) NOT NULL,
  `icon_type` VARCHAR(50) DEFAULT 'services',
  `is_external` TINYINT(1) DEFAULT 1,
  `show_on_home` TINYINT(1) DEFAULT 1,
  `sort_order` INT DEFAULT 0,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$db->query($sql2);
echo "Table `portal_services` checked/created.\n";

// 3. Insert initial settings if not present
$initial_settings = [
    // Hero Banner
    ['hero_tagline', 'Clean City • Better Services • Brighter Tomorrow', 'hero', 'Hero Tagline Badge', 'text', 'Top badge above hero heading', 1],
    ['hero_title', "एक स्वच्छ, सुंदर<br>और विकसित झाँसी<br>हम सबका संकल्प", 'hero', 'Hero Main Heading', 'textarea', 'Main bold title on homepage hero', 2],
    ['hero_subtitle', 'Jhansi Nagar Nigam • Smart City Jhansi', 'hero', 'Hero Subtitle', 'text', 'Sub-heading below main heading', 3],
    ['hero_banner_image', 'images/hero_banner.jpg', 'hero', 'Hero Banner Background Image', 'file', 'Path to hero banner background', 4],
    ['hero_quote_verse', "खूब लड़ी मर्दानी वह तो<br>झाँसी वाली रानी थी", 'hero', 'Hero Quote Verse', 'textarea', 'Rani Lakshmibai inspirational verse', 5],
    ['hero_quote_author', '— सुभद्रा कुमारी चौहान', 'hero', 'Hero Quote Author', 'text', 'Poet / Author credit', 6],
    ['hero_quote_badge', 'वीरभूमि झाँसी की गौरव गाथा', 'hero', 'Hero Quote Badge', 'text', 'Top header label for the quote card', 7],
    
    // News Ticker
    ['ticker_text', 'Jhansi Nagar Nigam - Dedicated to transparent, prompt & citizen-centric municipal governance. Pay property tax online, apply for birth/death certificates, and submit grievances with ease.', 'general', 'News Ticker Default Text', 'textarea', 'Fallback news ticker message', 10],

    // Key Statistics
    ['stat_1_count', '4.25 L+', 'statistics', 'Stat 1 Number / Count', 'text', 'e.g. 4.25 L+', 20],
    ['stat_1_label', 'Citizens Served', 'statistics', 'Stat 1 Label', 'text', 'Description for stat 1', 21],
    ['stat_2_count', '1.12 L+', 'statistics', 'Stat 2 Number / Count', 'text', 'e.g. 1.12 L+', 22],
    ['stat_2_label', 'Property Tax Payers', 'statistics', 'Stat 2 Label', 'text', 'Description for stat 2', 23],
    ['stat_3_count', '98%', 'statistics', 'Stat 3 Number / Count', 'text', 'e.g. 98%', 24],
    ['stat_3_label', 'Waste Collection Coverage', 'statistics', 'Stat 3 Label', 'text', 'Description for stat 3', 25],
    ['stat_4_count', '120+', 'statistics', 'Stat 4 Number / Count', 'text', 'e.g. 120+', 26],
    ['stat_4_label', 'Ward Areas & Zones', 'statistics', 'Stat 4 Label', 'text', 'Description for stat 4', 27],

    // Leadership Quote
    ['mayor_quote_title', 'जनसेवा ही हमारा लक्ष्य है', 'leadership', 'Mayor Quote Title', 'text', 'Highlighted banner quote headline', 30],
    ['mayor_quote_subtitle', 'झाँसी को स्वच्छ, स्वस्थ एवं तकनीक युक्त स्मार्ट शहर बनाना हमारी सर्वोच्च प्राथमिकता है।', 'leadership', 'Mayor Quote Subtitle', 'textarea', 'Leadership commitment statement', 31],

    // Swachh Jhansi Mission Banner
    ['swachh_badge', 'Clean & Green City Mission', 'swachh', 'Swachh Mission Badge', 'text', 'Top badge', 40],
    ['swachh_title', 'स्वच्छ झाँसी • स्वस्थ झाँसी', 'swachh', 'Swachh Mission Title', 'text', 'Main mission title', 41],
    ['swachh_desc', 'Jhansi Nagar Nigam drives continuous door-to-door waste collection, city beautification, green plantation, and automated street sanitation. Join hands to keep our historic city clean.', 'swachh', 'Swachh Mission Description', 'textarea', 'Mission details paragraph', 42],
    ['swachh_btn_text', 'Learn More & Participate', 'swachh', 'Swachh Mission Button Text', 'text', 'Button label', 43],
    ['swachh_btn_url', 'sbm.php', 'swachh', 'Swachh Mission Button Link', 'text', 'Button destination URL', 44],
    ['swachh_banner_image', 'images/swachh_jhansi.jpg', 'swachh', 'Swachh Mission Banner Image', 'file', 'Image path or upload', 45],

    // Contact Details & Helplines
    ['contact_helpline', '1533 / 0510-3-500-700', 'contact', '24x7 Citizen Helpline Phone', 'text', 'Toll-free helpline numbers', 50],
    ['contact_tollfree', '1800-180-5123', 'contact', 'Secondary Toll-Free Number', 'text', 'Additional support line', 51],
    ['contact_email', 'nagarayukta@jnnjhansi.com', 'contact', 'Official Email Address', 'text', 'Primary email', 52],
    ['contact_alt_email', 'jnnjhansi@gmail.com', 'contact', 'Alternative / Public Email', 'text', 'Secondary email', 53],
    ['contact_address', 'Municipal Corporation Office, Near Elite Chauraha, Civil Lines, Jhansi (Uttar Pradesh) - 284001', 'contact', 'Office Address', 'textarea', 'Official headquarters address', 54],
    ['contact_office_hours', 'Monday - Saturday: 10:00 AM - 5:00 PM (Closed on 2nd Saturdays & Gazetted Holidays)', 'contact', 'Office Working Hours', 'text', 'Working hours text', 55],
    ['contact_commissioner_office', '0510-2332097', 'contact', 'Commissioner Office Direct Phone', 'text', 'Administrative desk line', 56],
    ['contact_map_embed', 'https://maps.google.co.in/maps?f=q&source=s_q&hl=en&geocode=&q=jhansi+nagar+nigam&aq=&sll=25.444121,78.567604&sspn=0.168342,0.338173&g=jhansi&ie=UTF8&hq=nagar+nigam&hnear=Jhansi,+Uttar+Pradesh&t=m&cid=13682369725505538264&ll=25.46141,78.572073&spn=0.027123,0.030899&z=14&iwloc=A&output=embed', 'contact', 'Google Maps Embed URL', 'textarea', 'URL for iframe embed', 57],

    // Social Links
    ['social_facebook', 'https://www.facebook.com/CleanJhansi', 'social', 'Facebook Page URL', 'text', 'Full URL', 60],
    ['social_twitter', 'https://twitter.com/CleanJhansi', 'social', 'Twitter / X Profile URL', 'text', 'Full URL', 61],
    ['social_smartcity', 'https://smartcityjhansi.com/', 'social', 'Smart City Jhansi Portal URL', 'text', 'Full URL', 62],
    ['social_webmail', 'http://webmail.jnnjhansi.com', 'social', 'Official Webmail Portal URL', 'text', 'Full URL', 63],
];

foreach ($initial_settings as $s) {
    $k = $db->escape_string($s[0]);
    $v = $db->escape_string($s[1]);
    $g = $db->escape_string($s[2]);
    $lbl = $db->escape_string($s[3]);
    $ft = $db->escape_string($s[4]);
    $ht = $db->escape_string($s[5]);
    $so = (int)$s[6];

    $check = $db->query("SELECT setting_id FROM site_settings WHERE setting_key = '$k'");
    if (!$db->num_rows()) {
        $insert = "INSERT INTO site_settings (setting_key, setting_value, setting_group, label, field_type, help_text, sort_order) 
                   VALUES ('$k', '$v', '$g', '$lbl', '$ft', '$ht', $so)";
        $db->query($insert);
    }
}
echo "Seeded default `site_settings` successfully.\n";

// 4. Insert initial services if empty
$check_services = $db->query("SELECT COUNT(*) AS total FROM portal_services");
$serv_row = $db->fetch_assoc();
if ($serv_row['total'] == 0) {
    $services_data = [
        ['Property Tax', 'tax', 'Self Assessment & Online Payment', 'Pay Online →', 'http://www.jhansipropertytax.com/', 'tax', 1, 1, 1],
        ['Birth / Death Registration', 'cert', 'Civil Registration System CRS Portal', 'Apply Online →', 'https://dc.crsorgi.gov.in/crs/', 'cert', 1, 1, 2],
        ['Apply NOC', 'noc', 'Online Trade & Construction NOC', 'Apply Online →', 'https://jnnnoc.in/', 'noc', 1, 1, 3],
        ['Public Grievance', 'grievance', 'Jansunwai Integrated Redressal', 'Lodge Complaint →', 'http://jansunwai.up.nic.in', 'grievance', 1, 1, 4],
        ['e-Tender & NIT', 'services', 'Public Procurement & Works', 'View Tenders →', 'tenders.php', 'tender', 0, 1, 5],
        ['Online Services', 'services', 'Access All Municipal Services', 'All Services →', 'services.php', 'services', 0, 1, 6],
        ['Building Plan Approval', 'noc', 'Online Building Permission System (UP-OBPS)', 'Apply Online →', 'https://upobps.in/', 'noc', 1, 0, 7],
        ['Water & Sewer Connection', 'utility', 'New Connection Request & Bill Payment', 'Apply Online →', 'services.php', 'utility', 0, 0, 8],
        ['Trade License / Vyapar Shulk', 'tax', 'Municipal Trade License Application', 'Apply Online →', 'services.php', 'tax', 0, 0, 9],
        ['RTI Online Request', 'grievance', 'Sou-motu disclosure & RTI Portal', 'View Guidelines →', 'docs/Sou-motu disclosure under RTI Act.pdf', 'grievance', 1, 0, 10],
    ];

    foreach ($services_data as $sd) {
        $title = $db->escape_string($sd[0]);
        $cat = $db->escape_string($sd[1]);
        $sub = $db->escape_string($sd[2]);
        $act = $db->escape_string($sd[3]);
        $link = $db->escape_string($sd[4]);
        $icon = $db->escape_string($sd[5]);
        $is_ext = (int)$sd[6];
        $home = (int)$sd[7];
        $sort = (int)$sd[8];

        $ins = "INSERT INTO portal_services (title, category, subtitle, action_text, link_url, icon_type, is_external, show_on_home, sort_order, status)
                VALUES ('$title', '$cat', '$sub', '$act', '$link', '$icon', $is_ext, $home, $sort, 1)";
        $db->query($ins);
    }
    echo "Seeded default `portal_services` successfully.\n";
} else {
    echo "`portal_services` already has records.\n";
}

echo "Setup completed successfully!\n";
?>
