<?php
/**
 * Dynamic Portal Data Library for Jhansi Nagar Nigam
 * Connects frontend templates with dynamic database settings & services
 */

function LoadSiteSettings($db) {
    global $site_settings;
    $site_settings = [];
    
    // Check if table exists
    $res = $db->query("SHOW TABLES LIKE 'site_settings'");
    if ($db->num_rows()) {
        $q = $db->query("SELECT setting_key, setting_value FROM site_settings");
        while ($row = $db->fetch_assoc()) {
            $k = $row['setting_key'];
            $v = $row['setting_value'];
            $site_settings[$k] = $v;
            $GLOBALS[$k] = $v;
        }
    }

    // Fallbacks if not set
    if (empty($GLOBALS['hero_tagline'])) $GLOBALS['hero_tagline'] = 'Clean City &bull; Better Services &bull; Brighter Tomorrow';
    if (empty($GLOBALS['hero_title'])) $GLOBALS['hero_title'] = "एक स्वच्छ, सुंदर<br>और विकसित झाँसी<br>हम सबका संकल्प";
    if (empty($GLOBALS['hero_subtitle'])) $GLOBALS['hero_subtitle'] = 'Jhansi Nagar Nigam &bull; Smart City Jhansi';
    if (empty($GLOBALS['hero_banner_image'])) $GLOBALS['hero_banner_image'] = 'images/hero_banner.jpg';
    if (empty($GLOBALS['hero_quote_verse'])) $GLOBALS['hero_quote_verse'] = "\"खूब लड़ी मर्दानी वह तो<br>झाँसी वाली रानी थी\"";
    if (empty($GLOBALS['hero_quote_author'])) $GLOBALS['hero_quote_author'] = '— सुभद्रा कुमारी चौहान';
    if (empty($GLOBALS['hero_quote_badge'])) $GLOBALS['hero_quote_badge'] = 'वीरभूमि झाँसी की गौरव गाथा';

    if (empty($GLOBALS['stat_1_count'])) $GLOBALS['stat_1_count'] = '4.25 L+';
    if (empty($GLOBALS['stat_1_label'])) $GLOBALS['stat_1_label'] = 'Citizens Served';
    if (empty($GLOBALS['stat_2_count'])) $GLOBALS['stat_2_count'] = '1.12 L+';
    if (empty($GLOBALS['stat_2_label'])) $GLOBALS['stat_2_label'] = 'Property Tax Payers';
    if (empty($GLOBALS['stat_3_count'])) $GLOBALS['stat_3_count'] = '98%';
    if (empty($GLOBALS['stat_3_label'])) $GLOBALS['stat_3_label'] = 'Waste Collection Coverage';
    if (empty($GLOBALS['stat_4_count'])) $GLOBALS['stat_4_count'] = '120+';
    if (empty($GLOBALS['stat_4_label'])) $GLOBALS['stat_4_label'] = 'Ward Areas & Zones';

    if (empty($GLOBALS['mayor_quote_title'])) $GLOBALS['mayor_quote_title'] = 'जनसेवा ही हमारा लक्ष्य है';
    if (empty($GLOBALS['mayor_quote_subtitle'])) $GLOBALS['mayor_quote_subtitle'] = 'झाँसी को स्वच्छ, स्वस्थ एवं तकनीक युक्त स्मार्ट शहर बनाना हमारी सर्वोच्च प्राथमिकता है।';

    if (empty($GLOBALS['swachh_badge'])) $GLOBALS['swachh_badge'] = 'Clean & Green City Mission';
    if (empty($GLOBALS['swachh_title'])) $GLOBALS['swachh_title'] = 'स्वच्छ झाँसी &bull; स्वस्थ झाँसी';
    if (empty($GLOBALS['swachh_desc'])) $GLOBALS['swachh_desc'] = 'Jhansi Nagar Nigam drives continuous door-to-door waste collection, city beautification, green plantation, and automated street sanitation. Join hands to keep our historic city clean.';
    if (empty($GLOBALS['swachh_btn_text'])) $GLOBALS['swachh_btn_text'] = 'Learn More & Participate';
    if (empty($GLOBALS['swachh_btn_url'])) $GLOBALS['swachh_btn_url'] = 'sbm.php';
    if (empty($GLOBALS['swachh_banner_image'])) $GLOBALS['swachh_banner_image'] = 'images/swachh_jhansi.jpg';

    if (empty($GLOBALS['contact_helpline'])) $GLOBALS['contact_helpline'] = '1533 / 0510-3-500-700';
    if (empty($GLOBALS['contact_tollfree'])) $GLOBALS['contact_tollfree'] = '1800-180-5123';
    if (empty($GLOBALS['contact_commissioner_office'])) $GLOBALS['contact_commissioner_office'] = '0510-2332097';
    if (empty($GLOBALS['contact_email'])) $GLOBALS['contact_email'] = 'nagarayukta@jnnjhansi.com';
    if (empty($GLOBALS['contact_alt_email'])) $GLOBALS['contact_alt_email'] = 'jnnjhansi@gmail.com';
    if (empty($GLOBALS['contact_address'])) $GLOBALS['contact_address'] = 'Municipal Corporation Office, Near Elite Chauraha, Civil Lines, Jhansi (Uttar Pradesh) - 284001';
    if (empty($GLOBALS['contact_office_hours'])) $GLOBALS['contact_office_hours'] = 'Monday - Saturday: 10:00 AM - 5:00 PM (Closed on 2nd Saturdays & Gazetted Holidays)';
    if (empty($GLOBALS['contact_map_embed'])) $GLOBALS['contact_map_embed'] = 'https://maps.google.co.in/maps?f=q&source=s_q&hl=en&geocode=&q=jhansi+nagar+nigam&aq=&sll=25.444121,78.567604&sspn=0.168342,0.338173&g=jhansi&ie=UTF8&hq=nagar+nigam&hnear=Jhansi,+Uttar+Pradesh&t=m&cid=13682369725505538264&ll=25.46141,78.572073&spn=0.027123,0.030899&z=14&iwloc=A&output=embed';

    if (empty($GLOBALS['social_facebook'])) $GLOBALS['social_facebook'] = 'https://www.facebook.com/CleanJhansi';
    if (empty($GLOBALS['social_twitter'])) $GLOBALS['social_twitter'] = 'https://twitter.com/CleanJhansi';
    if (empty($GLOBALS['social_smartcity'])) $GLOBALS['social_smartcity'] = 'https://smartcityjhansi.com/';
    if (empty($GLOBALS['social_webmail'])) $GLOBALS['social_webmail'] = 'http://webmail.jnnjhansi.com';

    return $site_settings;
}

function GetServiceIconSvg($icon_type) {
    switch ($icon_type) {
        case 'tax':
            return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>';
        case 'cert':
            return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>';
        case 'noc':
            return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>';
        case 'grievance':
            return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path><line x1="9" y1="9" x2="15" y2="9"></line><line x1="9" y1="13" x2="13" y2="13"></line></svg>';
        case 'tender':
            return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>';
        case 'utility':
            return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>';
        case 'services':
        default:
            return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>';
    }
}

function GetHomeServicesHtml($db) {
    $html = "";
    $res = $db->query("SELECT * FROM portal_services WHERE status = 1 AND show_on_home = 1 ORDER BY sort_order ASC, service_id ASC");
    if ($db->num_rows()) {
        while ($row = $db->fetch_assoc()) {
            $title    = htmlspecialchars($row['title']);
            $action   = !empty($row['action_text']) ? htmlspecialchars($row['action_text']) : 'Apply Online &rarr;';
            $link     = htmlspecialchars($row['link_url']);
            $target   = ($row['is_external'] == 1) ? 'target="_blank"' : '';
            $icon_cls = "icon-" . htmlspecialchars($row['icon_type']);
            $svg      = GetServiceIconSvg($row['icon_type']);

            $html .= '<a href="' . $link . '" ' . $target . ' class="quick-card">';
            $html .= '  <div class="quick-card-icon ' . $icon_cls . '">' . $svg . '</div>';
            $html .= '  <span class="quick-card-title">' . $title . '</span>';
            $html .= '  <span class="quick-card-subtitle">' . $action . '</span>';
            $html .= '</a>' . "\n";
        }
    }
    return $html;
}

function GetAllServicesHtml($db) {
    $html = "";
    $res = $db->query("SELECT * FROM portal_services WHERE status = 1 ORDER BY sort_order ASC, service_id ASC");
    if ($db->num_rows()) {
        while ($row = $db->fetch_assoc()) {
            $title    = htmlspecialchars($row['title']);
            $subtitle = htmlspecialchars($row['subtitle']);
            $action   = !empty($row['action_text']) ? htmlspecialchars($row['action_text']) : 'Apply Online &rarr;';
            $link     = htmlspecialchars($row['link_url']);
            $target   = ($row['is_external'] == 1) ? 'target="_blank"' : '';
            $cat      = htmlspecialchars($row['category']);
            $icon_cls = "icon-" . htmlspecialchars($row['icon_type']);
            $svg      = GetServiceIconSvg($row['icon_type']);

            $html .= '<a href="' . $link . '" ' . $target . ' class="quick-card filterable-item ' . $cat . '" style="align-items: flex-start; text-align: left; padding: 22px 18px;">';
            $html .= '  <div class="quick-card-icon ' . $icon_cls . '" style="margin-bottom: 12px;">' . $svg . '</div>';
            $html .= '  <div class="quick-card-title" style="font-size: 15px;">' . $title . '</div>';
            if (!empty($subtitle)) {
                $html .= '  <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">' . $subtitle . '</div>';
            }
            $html .= '  <div class="quick-card-subtitle" style="margin-top: auto;">' . $action . '</div>';
            $html .= '</a>' . "\n";
        }
    }
    return $html;
}

function GetHomeAnnouncementsHtml($db) {
    $html = "";
    $res = $db->query("SELECT * FROM notice WHERE status = '1' ORDER BY Pdf_Id DESC LIMIT 5");
    if ($db->num_rows()) {
        $count = 0;
        while ($row = $db->fetch_assoc()) {
            $title = htmlspecialchars($row['Pdf_Desc']);
            $file = htmlspecialchars($row['Pdf_Name']);
            $added = !empty($row['AddedDate']) ? $row['AddedDate'] : date('d M Y');
            
            // Format date
            $time = strtotime($added);
            $day = $time ? date('d', $time) : '20';
            $my = $time ? date('M Y', $time) : 'Sep 2026';
            
            $badge = ($count < 2) ? ' <span class="badge-new">New</span>' : '';
            
            $html .= '<a href="docs/' . $file . '" target="_blank" class="announcement-item">';
            $html .= '  <div class="announcement-date">';
            $html .= '    <div class="announcement-day">' . $day . '</div>';
            $html .= '    <div class="announcement-my">' . $my . '</div>';
            $html .= '  </div>';
            $html .= '  <div class="announcement-body">';
            $html .= '    <div class="announcement-title">' . $title . $badge . '</div>';
            $html .= '    <div style="font-size:11.5px; color:#64748B;">Jhansi Nagar Nigam &bull; Official Notification</div>';
            $html .= '  </div>';
            $html .= '</a>' . "\n";
            $count++;
        }
    }
    return $html;
}
?>
