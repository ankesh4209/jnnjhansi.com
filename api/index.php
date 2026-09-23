<?php
/**
 * Dynamic RESTful API for Nagar Nigam Jhansi Portal
 * 
 * Supports mobile applications (Flutter, React Native, Android/iOS) and web apps.
 * Provides dynamic data for Home Dashboard, Notices, Tenders, News, Mayor,
 * Municipal Commissioner, Officials Directory, Departments, Gallery, CMS Pages, and Grievance Tracking.
 */

// Enable CORS and define JSON response headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Handle CORS preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Disable HTML error display in API output to keep JSON valid
ini_set('display_errors', 0);
error_reporting(0);

// Load Database configuration
require_once __DIR__ . '/../config/data.config.php';

// Establish Database Connection
$conn = @mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

if (!$conn) {
    echo json_response(500, "Database connection failed: " . mysqli_connect_error(), null);
    exit;
}

mysqli_set_charset($conn, "utf8mb4");

// Helper to determine dynamic Base URL
function getBaseUrl() {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    $protocol = $isHttps ? "https://" : "http://";
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    
    // Compute root folder relative to api/
    $scriptDir = dirname(dirname($_SERVER['SCRIPT_NAME']));
    $scriptDir = str_replace('\\', '/', $scriptDir);
    $basePath = rtrim($scriptDir, '/') . '/';
    
    return $protocol . $host . $basePath;
}

$BASE_URL = getBaseUrl();

// JSON response wrapper
function json_response($status_code, $message, $data = null, $pagination = null) {
    http_response_code($status_code);
    $response = [
        "status" => ($status_code >= 200 && $status_code < 300) ? "success" : "error",
        "code" => $status_code,
        "message" => $message
    ];
    if ($pagination !== null) {
        $response["pagination"] = $pagination;
    }
    if ($data !== null) {
        $response["data"] = $data;
    }
    return json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

// Get requested endpoint
$endpoint = isset($_GET['endpoint']) ? trim($_GET['endpoint']) : 'help';
$method = $_SERVER['REQUEST_METHOD'];

// Route endpoints
switch ($endpoint) {

    // ==========================================
    // 1. HOME DASHBOARD (All-in-one for App Home)
    // ==========================================
    case 'home':
        // Mayor Details
        $mayor = null;
        $q_mayor = mysqli_query($conn, "SELECT Mayer_Id, Mayer_Name, Mayer_Photo, Mayer_Desc, AddedDate FROM mayers WHERE Status='1' ORDER BY Mayer_Id DESC LIMIT 1");
        if ($row = mysqli_fetch_assoc($q_mayor)) {
            $mayor = [
                "id" => (int)$row['Mayer_Id'],
                "name" => $row['Mayer_Name'],
                "designation" => "Mayor, Jhansi Nagar Nigam",
                "photo_url" => $row['Mayer_Photo'] ? $BASE_URL . "m_images/thumbs/" . $row['Mayer_Photo'] : null,
                "description" => strip_tags($row['Mayer_Desc']),
                "added_date" => $row['AddedDate']
            ];
        }

        // Commissioner Details
        $commissioner = null;
        $q_comm = mysqli_query($conn, "SELECT Comm_Id, Comm_Name, Comm_Photo, Comm_Desc, AddedDate FROM municipal_comm WHERE Status='1' ORDER BY Comm_Id DESC LIMIT 1");
        if ($row = mysqli_fetch_assoc($q_comm)) {
            $commissioner = [
                "id" => (int)$row['Comm_Id'],
                "name" => $row['Comm_Name'],
                "designation" => "Municipal Commissioner",
                "photo_url" => $row['Comm_Photo'] ? $BASE_URL . "c_images/thumbs/" . $row['Comm_Photo'] : null,
                "description" => strip_tags($row['Comm_Desc']),
                "added_date" => $row['AddedDate']
            ];
        }

        // Latest Notices (Top 5)
        $latest_notices = [];
        $q_notices = mysqli_query($conn, "SELECT Pdf_Id, Pdf_Name, Pdf_Desc, AddedDate FROM notice WHERE Status='1' ORDER BY Pdf_Id DESC LIMIT 5");
        while ($row = mysqli_fetch_assoc($q_notices)) {
            $latest_notices[] = [
                "id" => (int)$row['Pdf_Id'],
                "title" => $row['Pdf_Desc'],
                "pdf_name" => $row['Pdf_Name'],
                "pdf_url" => $row['Pdf_Name'] ? $BASE_URL . "docs/" . $row['Pdf_Name'] : null,
                "date" => $row['AddedDate']
            ];
        }

        // Latest Tenders (Top 5)
        $latest_tenders = [];
        $q_tenders = mysqli_query($conn, "SELECT Pdf_Id, Pdf_Name, Pdf_Desc, AddedDate FROM pdffiles WHERE Status='1' ORDER BY Pdf_Id DESC LIMIT 5");
        while ($row = mysqli_fetch_assoc($q_tenders)) {
            $latest_tenders[] = [
                "id" => (int)$row['Pdf_Id'],
                "title" => $row['Pdf_Desc'],
                "pdf_name" => $row['Pdf_Name'],
                "pdf_url" => $row['Pdf_Name'] ? $BASE_URL . "docs/" . $row['Pdf_Name'] : null,
                "date" => $row['AddedDate']
            ];
        }

        // Latest Development Works / News (Top 5)
        $latest_news = [];
        $q_news = mysqli_query($conn, "SELECT Work_Id, Work_Name, Work_Photo, Work_Desc, AddedDate FROM jnnworks WHERE Status='1' ORDER BY Work_Id DESC LIMIT 5");
        while ($row = mysqli_fetch_assoc($q_news)) {
            $latest_news[] = [
                "id" => (int)$row['Work_Id'],
                "title" => $row['Work_Name'],
                "description" => strip_tags($row['Work_Desc']),
                "image_url" => $row['Work_Photo'] ? $BASE_URL . "w_images/" . $row['Work_Photo'] : null,
                "thumb_url" => $row['Work_Photo'] ? $BASE_URL . "w_images/thumbs/" . $row['Work_Photo'] : null,
                "date" => $row['AddedDate']
            ];
        }

        // Quick Counts
        $count_notices = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM notice WHERE Status='1'"))['c'];
        $count_tenders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM pdffiles WHERE Status='1'"))['c'];
        $count_departments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM department"))['c'];

        echo json_response(200, "Home dashboard data loaded", [
            "portal_name" => "Nagar Nigam Jhansi",
            "base_url" => $BASE_URL,
            "mayor" => $mayor,
            "commissioner" => $commissioner,
            "latest_notices" => $latest_notices,
            "latest_tenders" => $latest_tenders,
            "latest_news" => $latest_news,
            "statistics" => [
                "active_notices" => (int)$count_notices,
                "active_tenders" => (int)$count_tenders,
                "departments_count" => (int)$count_departments
            ]
        ]);
        break;

    // ==========================================
    // 2. NOTICES API (With Pagination & Search)
    // ==========================================
    case 'notices':
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? min(100, max(1, (int)$_GET['limit'])) : 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $where = "WHERE Status='1'";
        if ($search !== '') {
            $s = mysqli_real_escape_string($conn, $search);
            $where .= " AND (Pdf_Desc LIKE '%$s%' OR Pdf_Name LIKE '%$s%')";
        }

        $total_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM notice $where");
        $total_records = (int)mysqli_fetch_assoc($total_res)['total'];
        $total_pages = ceil($total_records / $limit);

        $query = "SELECT Pdf_Id, Pdf_Name, Pdf_Desc, AddedDate FROM notice $where ORDER BY Pdf_Id DESC LIMIT $offset, $limit";
        $res = mysqli_query($conn, $query);

        $notices = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $notices[] = [
                "id" => (int)$row['Pdf_Id'],
                "title" => $row['Pdf_Desc'],
                "pdf_name" => $row['Pdf_Name'],
                "pdf_url" => $row['Pdf_Name'] ? $BASE_URL . "docs/" . $row['Pdf_Name'] : null,
                "added_date" => $row['AddedDate']
            ];
        }

        echo json_response(200, "Notices retrieved successfully", $notices, [
            "current_page" => $page,
            "limit" => $limit,
            "total_records" => $total_records,
            "total_pages" => $total_pages
        ]);
        break;

    // ==========================================
    // 3. TENDERS API (With Pagination & Search)
    // ==========================================
    case 'tenders':
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? min(100, max(1, (int)$_GET['limit'])) : 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $where = "WHERE Status='1'";
        if ($search !== '') {
            $s = mysqli_real_escape_string($conn, $search);
            $where .= " AND (Pdf_Desc LIKE '%$s%' OR Pdf_Name LIKE '%$s%')";
        }

        $total_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM pdffiles $where");
        $total_records = (int)mysqli_fetch_assoc($total_res)['total'];
        $total_pages = ceil($total_records / $limit);

        $query = "SELECT Pdf_Id, Pdf_Name, Pdf_Desc, AddedDate FROM pdffiles $where ORDER BY Pdf_Id DESC LIMIT $offset, $limit";
        $res = mysqli_query($conn, $query);

        $tenders = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $tenders[] = [
                "id" => (int)$row['Pdf_Id'],
                "title" => $row['Pdf_Desc'],
                "pdf_name" => $row['Pdf_Name'],
                "pdf_url" => $row['Pdf_Name'] ? $BASE_URL . "docs/" . $row['Pdf_Name'] : null,
                "added_date" => $row['AddedDate']
            ];
        }

        echo json_response(200, "Tenders retrieved successfully", $tenders, [
            "current_page" => $page,
            "limit" => $limit,
            "total_records" => $total_records,
            "total_pages" => $total_pages
        ]);
        break;

    // ==========================================
    // 4. DEVELOPMENT WORKS / NEWS API
    // ==========================================
    case 'news':
    case 'works':
        $query = "SELECT Work_Id, Work_Name, Work_Photo, Work_Desc, AddedDate FROM jnnworks WHERE Status='1' ORDER BY Work_Id DESC";
        $res = mysqli_query($conn, $query);

        $works = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $works[] = [
                "id" => (int)$row['Work_Id'],
                "title" => $row['Work_Name'],
                "description" => strip_tags($row['Work_Desc']),
                "image_url" => $row['Work_Photo'] ? $BASE_URL . "w_images/" . $row['Work_Photo'] : null,
                "thumb_url" => $row['Work_Photo'] ? $BASE_URL . "w_images/thumbs/" . $row['Work_Photo'] : null,
                "added_date" => $row['AddedDate']
            ];
        }

        echo json_response(200, "News & development works retrieved successfully", $works);
        break;

    // ==========================================
    // 5. MAYOR & COMMISSIONER DETAILS
    // ==========================================
    case 'mayor':
        $res = mysqli_query($conn, "SELECT * FROM mayers WHERE Status='1' ORDER BY Mayer_Id DESC LIMIT 1");
        if ($row = mysqli_fetch_assoc($res)) {
            echo json_response(200, "Mayor details retrieved", [
                "id" => (int)$row['Mayer_Id'],
                "name" => $row['Mayer_Name'],
                "photo_url" => $row['Mayer_Photo'] ? $BASE_URL . "m_images/thumbs/" . $row['Mayer_Photo'] : null,
                "description" => $row['Mayer_Desc'],
                "added_date" => $row['AddedDate']
            ]);
        } else {
            echo json_response(404, "Mayor details not found");
        }
        break;

    case 'commissioner':
        $res = mysqli_query($conn, "SELECT * FROM municipal_comm WHERE Status='1' ORDER BY Comm_Id DESC LIMIT 1");
        if ($row = mysqli_fetch_assoc($res)) {
            echo json_response(200, "Commissioner details retrieved", [
                "id" => (int)$row['Comm_Id'],
                "name" => $row['Comm_Name'],
                "photo_url" => $row['Comm_Photo'] ? $BASE_URL . "c_images/thumbs/" . $row['Comm_Photo'] : null,
                "description" => $row['Comm_Desc'],
                "added_date" => $row['AddedDate']
            ]);
        } else {
            echo json_response(404, "Commissioner details not found");
        }
        break;

    // ==========================================
    // 6. OFFICIALS / ADMINISTRATION DIRECTORY
    // ==========================================
    case 'officers':
        $res = mysqli_query($conn, "SELECT Officer_Id, FirstName, LastName, Designation, OfficeNo, ResidenceNo, Email, AddedDate FROM officers WHERE Status='1' ORDER BY Officer_Id ASC");
        $officers = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $officers[] = [
                "id" => (int)$row['Officer_Id'],
                "name" => trim($row['FirstName'] . ' ' . $row['LastName']),
                "designation" => $row['Designation'],
                "office_contact" => $row['OfficeNo'],
                "residence_contact" => $row['ResidenceNo'],
                "email" => $row['Email']
            ];
        }
        echo json_response(200, "Officers directory retrieved successfully", $officers);
        break;

    // ==========================================
    // 7. DEPARTMENTS LIST
    // ==========================================
    case 'departments':
        $res = mysqli_query($conn, "SELECT d_id, d_name, d_off_name, d_off_designation, d_off_contact, d_soff_contact FROM tbl_department ORDER BY d_id ASC");
        if (!$res || mysqli_num_rows($res) == 0) {
            $res = mysqli_query($conn, "SELECT d_id, d_name FROM department ORDER BY d_id ASC");
        }
        $departments = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $departments[] = [
                "id" => (int)$row['d_id'],
                "name" => $row['d_name'],
                "officer_name" => isset($row['d_off_name']) ? $row['d_off_name'] : null,
                "officer_designation" => isset($row['d_off_designation']) ? $row['d_off_designation'] : null,
                "officer_contact" => isset($row['d_off_contact']) ? $row['d_off_contact'] : null,
                "support_contact" => isset($row['d_soff_contact']) ? $row['d_soff_contact'] : null
            ];
        }
        echo json_response(200, "Departments list retrieved successfully", $departments);
        break;

    // ==========================================
    // 8. PHOTO GALLERY API
    // ==========================================
    case 'gallery':
        $res = mysqli_query($conn, "SELECT g_id, photo_name FROM tbl_gallary WHERE status='1' ORDER BY g_id DESC");
        $gallery = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $gallery[] = [
                "id" => (int)$row['g_id'],
                "photo_name" => $row['photo_name'],
                "image_url" => $BASE_URL . "pic/" . $row['photo_name'],
                "thumb_url" => $BASE_URL . "pic/thumb/" . $row['photo_name']
            ];
        }
        echo json_response(200, "Photo gallery retrieved successfully", $gallery);
        break;

    // ==========================================
    // 9. DYNAMIC CMS PAGES (About Us, History, etc.)
    // ==========================================
    case 'page':
    case 'pages':
        $page_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($page_id) {
            $stmt = mysqli_prepare($conn, "SELECT p_id, p_name, p_text FROM page WHERE p_id = ? AND status = '1'");
            mysqli_stmt_bind_param($stmt, "i", $page_id);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($res)) {
                echo json_response(200, "Page content retrieved", [
                    "id" => (int)$row['p_id'],
                    "title" => $row['p_name'],
                    "content_html" => $row['p_text'],
                    "content_text" => trim(strip_tags($row['p_text']))
                ]);
            } else {
                echo json_response(404, "Page not found");
            }
        } else {
            // Return list of available pages
            $res = mysqli_query($conn, "SELECT p_id, p_name FROM page WHERE status = '1' ORDER BY p_id ASC");
            $pages = [];
            while ($row = mysqli_fetch_assoc($res)) {
                $pages[] = [
                    "id" => (int)$row['p_id'],
                    "title" => $row['p_name']
                ];
            }
            echo json_response(200, "Pages list retrieved", $pages);
        }
        break;

    // ==========================================
    // 10. COMPLAINT / GRIEVANCE TRACKING
    // ==========================================
    case 'track_complaint':
        $reg_no = isset($_GET['reg_no']) ? trim($_GET['reg_no']) : '';
        $phone = isset($_GET['phone']) ? trim($_GET['phone']) : '';

        if (empty($reg_no) && empty($phone)) {
            echo json_response(400, "Please provide 'reg_no' or 'phone' query parameter");
            break;
        }

        $where_auto = "WHERE 1=1";
        $where_comp = "WHERE 1=1";
        if (!empty($reg_no)) {
            $reg_escaped = mysqli_real_escape_string($conn, $reg_no);
            $where_auto .= " AND a_rno = '$reg_escaped'";
            $where_comp .= " AND c_regno = '$reg_escaped'";
        }
        if (!empty($phone)) {
            $phone_escaped = mysqli_real_escape_string($conn, $phone);
            $where_auto .= " AND a_contactno = '$phone_escaped'";
            $where_comp .= " AND c_contno = '$phone_escaped'";
        }

        $complaints = [];

        // Check complainant table first
        $res2 = mysqli_query($conn, "SELECT c_id, c_regno, c_name, c_contno, c_date, status, c_detail, disposaltext FROM complainant $where_comp LIMIT 10");
        if ($res2 && mysqli_num_rows($res2) > 0) {
            while ($row2 = mysqli_fetch_assoc($res2)) {
                $complaints[] = [
                    "id" => (int)$row2['c_id'],
                    "registration_no" => $row2['c_regno'],
                    "applicant_name" => $row2['c_name'],
                    "phone" => $row2['c_contno'],
                    "registered_date" => $row2['c_date'],
                    "status_code" => (int)$row2['status'],
                    "status_text" => ($row2['status'] == 1 ? "Resolved" : "Pending"),
                    "details" => $row2['c_detail'],
                    "disposal_remarks" => $row2['disposaltext']
                ];
            }
        } else {
            // Check in tbl_automation
            $res = mysqli_query($conn, "SELECT a_id, a_rno, a_name, a_fname, a_contactno, a_rdate, app_status, a_detail FROM tbl_automation $where_auto LIMIT 10");
            if ($res && mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $statusText = "Pending";
                    if ($row['app_status'] == 2) $statusText = "Completed / Resolved";
                    else if ($row['app_status'] == 3) $statusText = "Under Review";

                    $complaints[] = [
                        "id" => (int)$row['a_id'],
                        "registration_no" => $row['a_rno'],
                        "applicant_name" => $row['a_name'],
                        "father_name" => $row['a_fname'],
                        "phone" => $row['a_contactno'],
                        "registered_date" => is_numeric($row['a_rdate']) ? date('Y-m-d H:i:s', $row['a_rdate']) : $row['a_rdate'],
                        "status_code" => (int)$row['app_status'],
                        "status_text" => $statusText,
                        "details" => $row['a_detail']
                    ];
                }
            }
        }

        if (count($complaints) > 0) {
            echo json_response(200, "Complaint record found", $complaints);
        } else {
            echo json_response(404, "No complaint found with the given details");
        }
        break;

    // ==========================================
    // 11. LODGE COMPLAINT / CITIZEN GRIEVANCE (POST)
    // ==========================================
    case 'lodge_complaint':
        if ($method !== 'POST') {
            echo json_response(405, "Method not allowed. Use POST request.");
            break;
        }

        // Read raw JSON or POST form data
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $name = isset($input['name']) ? trim($input['name']) : '';
        $phone = isset($input['phone']) ? trim($input['phone']) : '';
        $address = isset($input['address']) ? trim($input['address']) : '';
        $city = isset($input['city']) ? trim($input['city']) : 'Jhansi';
        $details = isset($input['details']) ? trim($input['details']) : '';
        $department_id = isset($input['department_id']) ? (int)$input['department_id'] : 1;

        if (empty($name) || empty($phone) || empty($details)) {
            echo json_response(400, "Required fields missing: 'name', 'phone', and 'details' are mandatory.");
            break;
        }

        // Generate unique registration number
        $reg_no = "JNN-" . date("Ymd") . "-" . rand(1000, 9999);
        $date = date("d-m-Y H:i:s");

        $stmt = mysqli_prepare($conn, "INSERT INTO complainant (c_name, c_add, c_city, c_contno, c_regno, c_detail, department, c_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
        mysqli_stmt_bind_param($stmt, "ssssssis", $name, $address, $city, $phone, $reg_no, $details, $department_id, $date);

        if (mysqli_stmt_execute($stmt)) {
            $insert_id = mysqli_insert_id($conn);
            echo json_response(201, "Complaint registered successfully", [
                "complaint_id" => $insert_id,
                "registration_no" => $reg_no,
                "applicant_name" => $name,
                "phone" => $phone,
                "status" => "Pending",
                "registered_date" => $date
            ]);
        } else {
            echo json_response(500, "Failed to register complaint: " . mysqli_error($conn));
        }
        break;

    // ==========================================
    // 12. CITIZEN FEEDBACK / SMART CITY SURVEY (POST)
    // ==========================================
    case 'feedback':
        if ($method !== 'POST') {
            echo json_response(405, "Method not allowed. Use POST request.");
            break;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $name = isset($input['name']) ? trim($input['name']) : '';
        $mobile = isset($input['mobile']) ? trim($input['mobile']) : '';
        $feedback = isset($input['feedback']) ? trim($input['feedback']) : '';

        if (empty($name) || empty($mobile) || empty($feedback)) {
            echo json_response(400, "Required fields: 'name', 'mobile', and 'feedback'.");
            break;
        }

        $password = rand(10000, 99999);
        $stmt = mysqli_prepare($conn, "INSERT INTO smartcity_reg (name, mobile, password, address) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $name, $mobile, $password, $feedback);

        if (mysqli_stmt_execute($stmt)) {
            echo json_response(201, "Feedback submitted successfully", [
                "feedback_id" => mysqli_insert_id($conn),
                "name" => $name,
                "mobile" => $mobile
            ]);
        } else {
            echo json_response(500, "Error submitting feedback");
        }
        break;

    // ==========================================
    // 13. API DOCUMENTATION / HELP
    // ==========================================
    case 'help':
    default:
        $help = [
            "title" => "Nagar Nigam Jhansi Dynamic REST API",
            "version" => "1.0.0",
            "base_url" => $BASE_URL . "api/index.php",
            "endpoints" => [
                [
                    "endpoint" => "?endpoint=home",
                    "method" => "GET",
                    "description" => "All-in-one home dashboard data for mobile apps: Mayor, Commissioner, top notices, top tenders, news, and statistics."
                ],
                [
                    "endpoint" => "?endpoint=notices",
                    "method" => "GET",
                    "params" => "page (optional), limit (optional), search (optional)",
                    "description" => "Dynamic list of public notices with direct PDF download links."
                ],
                [
                    "endpoint" => "?endpoint=tenders",
                    "method" => "GET",
                    "params" => "page (optional), limit (optional), search (optional)",
                    "description" => "Dynamic list of e-tenders with direct PDF download links."
                ],
                [
                    "endpoint" => "?endpoint=news",
                    "method" => "GET",
                    "description" => "Development works and municipal news with image thumbnails and full URLs."
                ],
                [
                    "endpoint" => "?endpoint=mayor",
                    "method" => "GET",
                    "description" => "Mayor details, message, and profile photo."
                ],
                [
                    "endpoint" => "?endpoint=commissioner",
                    "method" => "GET",
                    "description" => "Municipal Commissioner details, message, and profile photo."
                ],
                [
                    "endpoint" => "?endpoint=officers",
                    "method" => "GET",
                    "description" => "Officers directory with designations, phone numbers, and emails."
                ],
                [
                    "endpoint" => "?endpoint=departments",
                    "method" => "GET",
                    "description" => "List of all municipal departments."
                ],
                [
                    "endpoint" => "?endpoint=gallery",
                    "method" => "GET",
                    "description" => "Photo gallery with image and thumbnail URLs."
                ],
                [
                    "endpoint" => "?endpoint=pages",
                    "method" => "GET",
                    "params" => "id (optional, e.g. ?endpoint=page&id=1)",
                    "description" => "Dynamic CMS pages like About Us, Citizen Charter, History, etc."
                ],
                [
                    "endpoint" => "?endpoint=track_complaint",
                    "method" => "GET",
                    "params" => "reg_no or phone (required, e.g. ?endpoint=track_complaint&reg_no=...)",
                    "description" => "Track citizen grievance or complaint status."
                ],
                [
                    "endpoint" => "?endpoint=lodge_complaint",
                    "method" => "POST",
                    "body" => ["name" => "String", "phone" => "String", "address" => "String", "city" => "String", "details" => "String", "department_id" => "Int"],
                    "description" => "Lodge a new grievance/complaint directly from mobile app."
                ],
                [
                    "endpoint" => "?endpoint=feedback",
                    "method" => "POST",
                    "body" => ["name" => "String", "mobile" => "String", "feedback" => "String"],
                    "description" => "Submit citizen feedback or suggestions."
                ]
            ]
        ];

        echo json_response(200, "API Documentation & Endpoints Directory", $help);
        break;
}

// Close DB connection
if ($conn) {
    mysqli_close($conn);
}
?>
