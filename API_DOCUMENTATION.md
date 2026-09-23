# Nagar Nigam Jhansi - RESTful API Documentation
> **Version:** 1.0.0  
> **Date:** September 2026  
> **Target Audience:** Mobile App Developers (Flutter / React Native / Android Kotlin / iOS Swift)  
> **Response Format:** JSON (UTF-8)  
> **CORS:** Enabled (`Access-Control-Allow-Origin: *`)

---

## 1. Overview & Base URLs

This dynamic API connects mobile and web applications to the **Nagar Nigam Jhansi (Jhansi Municipal Corporation)** database. All responses are returned in standard JSON format with UTF-8 encoding.

### Environment URLs:
* **Development (Localhost / XAMPP):**  
  `http://localhost/jnnjhansi/api/index.php`  
  *(Note for Physical Devices / Android Emulators: Use your computer's local IP, e.g. `http://192.168.1.X/jnnjhansi/api/index.php` or `http://10.0.2.2/jnnjhansi/api/index.php` for Android Emulator)*
* **Production (Live Server):**  
  `https://jnnjhansi.com/api/index.php`

---

## 2. Global Headers & Formatting

### Request Headers
```http
Content-Type: application/json
Accept: application/json
```

### Standard Response Structure

#### Success Response
```json
{
  "status": "success",
  "code": 200,
  "message": "Descriptive success message",
  "data": { ... }
}
```

#### Paginated Response
```json
{
  "status": "success",
  "code": 200,
  "message": "Descriptive success message",
  "data": [ ... ],
  "pagination": {
    "current_page": 1,
    "limit": 10,
    "total_records": 77,
    "total_pages": 8
  }
}
```

#### Error Response
```json
{
  "status": "error",
  "code": 400,
  "message": "Detailed error description"
}
```

---

## 3. Endpoints Directory

---

### 3.1. Home Screen Dashboard (All-in-One)
Fetches all essential data required for rendering the mobile application home screen in a single HTTP request.

* **Endpoint:** `?endpoint=home`
* **Method:** `GET`
* **Authentication:** None
* **Sample URL:**  
  `GET /api/index.php?endpoint=home`

#### Response `200 OK`:
```json
{
  "status": "success",
  "code": 200,
  "message": "Home dashboard data loaded",
  "data": {
    "portal_name": "Nagar Nigam Jhansi",
    "base_url": "http://localhost/jnnjhansi/",
    "mayor": {
      "id": 1,
      "name": "Bihari Lal Arya",
      "designation": "Mayor, Jhansi Nagar Nigam",
      "photo_url": "http://localhost/jnnjhansi/m_images/thumbs/photo.jpg",
      "description": "Mayor's welcome message and vision for the city of Jhansi...",
      "added_date": "2023-05-15"
    },
    "commissioner": {
      "id": 1,
      "name": "Shri Satyaprakash (IAS)",
      "designation": "Municipal Commissioner",
      "photo_url": "http://localhost/jnnjhansi/c_images/thumbs/comm.jpg",
      "description": "Commissioner's message on citizen services, cleanliness, and digitization...",
      "added_date": "2023-06-01"
    },
    "latest_notices": [
      {
        "id": 172,
        "title": "OTS Form for Offline",
        "pdf_name": "Offline Form_OTS_Nagar Vikas Vibhag.pdf",
        "pdf_url": "http://localhost/jnnjhansi/docs/Offline Form_OTS_Nagar Vikas Vibhag.pdf",
        "date": "2026-11-14"
      }
    ],
    "latest_tenders": [
      {
        "id": 948,
        "title": "Tender Date.18.09.2026 to 25.09.2026",
        "pdf_name": "TENDER NIT DATE.18.09.2026 TO 25.09.2026.pdf",
        "pdf_url": "http://localhost/jnnjhansi/docs/TENDER NIT DATE.18.09.2026 TO 25.09.2026.pdf",
        "date": "2026-09-25"
      }
    ],
    "latest_news": [
      {
        "id": 10,
        "title": "Road Beautification Project Jhansi",
        "description": "Development work underway at major intersections...",
        "image_url": "http://localhost/jnnjhansi/w_images/work1.jpg",
        "thumb_url": "http://localhost/jnnjhansi/w_images/thumbs/work1.jpg",
        "date": "2026-08-10"
      }
    ],
    "statistics": {
      "active_notices": 77,
      "active_tenders": 157,
      "departments_count": 15
    }
  }
}
```

---

### 3.2. Public Notices
Retrieves circulars, press releases, and public notices with direct PDF download links.

* **Endpoint:** `?endpoint=notices`
* **Method:** `GET`
* **Query Parameters:**
  | Parameter | Type | Required | Default | Description |
  |---|---|---|---|---|
  | `page` | Integer | No | `1` | Page number for pagination |
  | `limit` | Integer | No | `10` | Records per page (Max: 100) |
  | `search` | String | No | `""` | Search query matching notice title |

* **Sample URLs:**
  * `GET /api/index.php?endpoint=notices&page=1&limit=10`
  * `GET /api/index.php?endpoint=notices&search=OTS`

#### Response `200 OK`:
```json
{
  "status": "success",
  "code": 200,
  "message": "Notices retrieved successfully",
  "data": [
    {
      "id": 172,
      "title": "OTS Form for Offline",
      "pdf_name": "Offline Form_OTS_Nagar Vikas Vibhag.pdf",
      "pdf_url": "http://localhost/jnnjhansi/docs/Offline Form_OTS_Nagar Vikas Vibhag.pdf",
      "added_date": "2026-11-14"
    }
  ],
  "pagination": {
    "current_page": 1,
    "limit": 10,
    "total_records": 77,
    "total_pages": 8
  }
}
```

---

### 3.3. E-Tenders & NIT
Retrieves all municipal tenders, NITs, and bids with direct PDF download links.

* **Endpoint:** `?endpoint=tenders`
* **Method:** `GET`
* **Query Parameters:**
  | Parameter | Type | Required | Default | Description |
  |---|---|---|---|---|
  | `page` | Integer | No | `1` | Page number |
  | `limit` | Integer | No | `10` | Records per page (Max: 100) |
  | `search` | String | No | `""` | Search tender title/NIT keyword |

* **Sample URL:**  
  `GET /api/index.php?endpoint=tenders&page=1&limit=15`

#### Response `200 OK`:
```json
{
  "status": "success",
  "code": 200,
  "message": "Tenders retrieved successfully",
  "data": [
    {
      "id": 948,
      "title": "Tender Date.18.09.2026 to 25.09.2026",
      "pdf_name": "TENDER NIT DATE.18.09.2026 TO 25.09.2026.pdf",
      "pdf_url": "http://localhost/jnnjhansi/docs/TENDER NIT DATE.18.09.2026 TO 25.09.2026.pdf",
      "added_date": "2026-09-25"
    }
  ],
  "pagination": {
    "current_page": 1,
    "limit": 15,
    "total_records": 157,
    "total_pages": 11
  }
}
```

---

### 3.4. Development News & City Works
List of municipal development works, news stories, and city initiatives.

* **Endpoint:** `?endpoint=news` *(or `?endpoint=works`)*
* **Method:** `GET`
* **Sample URL:**  
  `GET /api/index.php?endpoint=news`

#### Response `200 OK`:
```json
{
  "status": "success",
  "code": 200,
  "message": "News & development works retrieved successfully",
  "data": [
    {
      "id": 10,
      "title": "Solid Waste Processing Plant Inauguration",
      "description": "New waste processing unit inaugurated in Jhansi...",
      "image_url": "http://localhost/jnnjhansi/w_images/waste_plant.jpg",
      "thumb_url": "http://localhost/jnnjhansi/w_images/thumbs/waste_plant.jpg",
      "added_date": "2026-07-15"
    }
  ]
}
```

---

### 3.5. Officers & Administration Directory
Full phone & email directory of Nagar Nigam officers, executive engineers, and administrators.

* **Endpoint:** `?endpoint=officers`
* **Method:** `GET`
* **Sample URL:**  
  `GET /api/index.php?endpoint=officers`

#### Response `200 OK`:
```json
{
  "status": "success",
  "code": 200,
  "message": "Officers directory retrieved successfully",
  "data": [
    {
      "id": 2,
      "name": "Mr. Anna Sudhan (I.A.S.)",
      "designation": "Municipal Commissioner",
      "office_contact": "8808053861",
      "residence_contact": "0510-2332097",
      "email": "nagarayukta@jnnjhansi.com"
    },
    {
      "id": 3,
      "name": "Harish Verma",
      "designation": "Chief Engineer",
      "office_contact": "8726447899",
      "residence_contact": "",
      "email": "ce@jnnjhansi.com"
    }
  ]
}
```

---

### 3.6. Departments List
List of municipal departments (Property Tax, Health, Works, Street Light, etc.) with department in-charge contact numbers.

* **Endpoint:** `?endpoint=departments`
* **Method:** `GET`
* **Sample URL:**  
  `GET /api/index.php?endpoint=departments`

#### Response `200 OK`:
```json
{
  "status": "success",
  "code": 200,
  "message": "Departments list retrieved successfully",
  "data": [
    {
      "id": 3,
      "name": "Property Tax Department (सम्पत्ति विभाग)",
      "officer_name": "Shri Harish Verma",
      "officer_designation": "Chief Engineer",
      "officer_contact": "8726447899",
      "support_contact": "9935241173"
    },
    {
      "id": 5,
      "name": "Health Department (स्वास्थ्य विभाग)",
      "officer_name": "Dr. Dushyant Singh",
      "officer_designation": "Nagar Swasthya Adhikari",
      "officer_contact": "9415878056",
      "support_contact": "9935241173"
    }
  ]
}
```

---

### 3.7. Photo Gallery
Photo gallery of official events, historical monuments, and civic programs.

* **Endpoint:** `?endpoint=gallery`
* **Method:** `GET`
* **Sample URL:**  
  `GET /api/index.php?endpoint=gallery`

#### Response `200 OK`:
```json
{
  "status": "success",
  "code": 200,
  "message": "Photo gallery retrieved successfully",
  "data": [
    {
      "id": 28,
      "photo_name": "DSC_5380.JPG",
      "image_url": "http://localhost/jnnjhansi/pic/DSC_5380.JPG",
      "thumb_url": "http://localhost/jnnjhansi/pic/thumb/DSC_5380.JPG"
    }
  ]
}
```

---

### 3.8. Dynamic CMS Pages (About Us, History, Citizen Charter)
* **Endpoint:** `?endpoint=pages` (or `?endpoint=page&id={id}`)
* **Method:** `GET`

#### Get List of Pages:
`GET /api/index.php?endpoint=pages`

#### Get Specific Page Content:
`GET /api/index.php?endpoint=page&id=1`

#### Response `200 OK`:
```json
{
  "status": "success",
  "code": 200,
  "message": "Page content retrieved",
  "data": {
    "id": 1,
    "title": "About Jhansi Nagar Nigam",
    "content_html": "<p>Jhansi Nagar Nigam was established to provide civic amenities...</p>",
    "content_text": "Jhansi Nagar Nigam was established to provide civic amenities..."
  }
}
```

---

### 3.9. Lodge Citizen Grievance / Complaint
Allows citizens to file a complaint directly from the mobile app.

* **Endpoint:** `?endpoint=lodge_complaint`
* **Method:** `POST`
* **Headers:** `Content-Type: application/json`

#### Request Body (JSON):
```json
{
  "name": "Amit Sharma",
  "phone": "9876543210",
  "address": "45, Civil Lines, Jhansi",
  "city": "Jhansi",
  "details": "Street light pole #14 is non-functional for past 3 days.",
  "department_id": 8
}
```

#### Field Specifications:
| Field | Type | Mandatory | Description |
|---|---|---|---|
| `name` | String | **Yes** | Full name of the complainant |
| `phone` | String | **Yes** | 10-digit mobile number |
| `address` | String | No | Citizen address/locality |
| `city` | String | No | City (Defaults to `Jhansi`) |
| `details` | String | **Yes** | Detailed grievance description |
| `department_id` | Integer | No | Department ID (Defaults to `1`) |

#### Response `201 Created`:
```json
{
  "status": "success",
  "code": 201,
  "message": "Complaint registered successfully",
  "data": {
    "complaint_id": 6657,
    "registration_no": "JNN-20260923-4819",
    "applicant_name": "Amit Sharma",
    "phone": "9876543210",
    "status": "Pending",
    "registered_date": "23-09-2026 17:05:12"
  }
}
```

---

### 3.10. Track Complaint Status
Search and track grievance status by Registration Number or Registered Mobile Number.

* **Endpoint:** `?endpoint=track_complaint`
* **Method:** `GET`
* **Query Parameters:**
  | Parameter | Type | Mandatory | Description |
  |---|---|---|---|
  | `reg_no` | String | One of both | Unique Registration No (e.g. `JNN-20260923-4819`) |
  | `phone` | String | One of both | 10-digit mobile number |

* **Sample URLs:**
  * `GET /api/index.php?endpoint=track_complaint&reg_no=JNN-20260923-4819`
  * `GET /api/index.php?endpoint=track_complaint&phone=9876543210`

#### Response `200 OK`:
```json
{
  "status": "success",
  "code": 200,
  "message": "Complaint record found",
  "data": [
    {
      "id": 6657,
      "registration_no": "JNN-20260923-4819",
      "applicant_name": "Amit Sharma",
      "phone": "9876543210",
      "registered_date": "23-09-2026 17:05:12",
      "status_code": 0,
      "status_text": "Pending",
      "details": "Street light pole #14 is non-functional for past 3 days.",
      "disposal_remarks": ""
    }
  ]
}
```

---

### 3.11. Citizen Feedback & Survey
Submit citizen suggestion or survey answer.

* **Endpoint:** `?endpoint=feedback`
* **Method:** `POST`
* **Request Body (JSON):**
```json
{
  "name": "Vikas Patel",
  "mobile": "9876543210",
  "feedback": "Great initiative on online door-to-door waste collection."
}
```

#### Response `201 Created`:
```json
{
  "status": "success",
  "code": 201,
  "message": "Feedback submitted successfully",
  "data": {
    "feedback_id": 142,
    "name": "Vikas Patel",
    "mobile": "9876543210"
  }
}
```

---

## 4. Mobile Integration Code Samples

### A. Flutter (Dart) Example

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class JnnApiService {
  static const String baseUrl = "http://YOUR_SERVER_IP/jnnjhansi/api/index.php";

  // 1. Fetch Home Screen Dashboard Data
  static Future<Map<String, dynamic>?> getHomeData() async {
    final response = await http.get(Uri.parse("$baseUrl?endpoint=home"));
    if (response.statusCode == 200) {
      final json = jsonDecode(response.body);
      return json['data'];
    }
    return null;
  }

  // 2. Fetch Notices with Pagination
  static Future<List<dynamic>> getNotices({int page = 1, int limit = 10, String search = ""}) async {
    final response = await http.get(Uri.parse("$baseUrl?endpoint=notices&page=$page&limit=$limit&search=$search"));
    if (response.statusCode == 200) {
      final json = jsonDecode(response.body);
      return json['data'];
    }
    return [];
  }

  // 3. Lodge a Complaint (POST)
  static Future<Map<String, dynamic>?> lodgeComplaint({
    required String name,
    required String phone,
    required String address,
    required String details,
    int departmentId = 1,
  }) async {
    final response = await http.post(
      Uri.parse("$baseUrl?endpoint=lodge_complaint"),
      headers: {"Content-Type": "application/json"},
      body: jsonEncode({
        "name": name,
        "phone": phone,
        "address": address,
        "city": "Jhansi",
        "details": details,
        "department_id": departmentId,
      }),
    );
    if (response.statusCode == 201) {
      final json = jsonDecode(response.body);
      return json['data'];
    }
    return null;
  }

  // 4. Track Complaint
  static Future<List<dynamic>> trackComplaint(String regNoOrPhone) async {
    final isNumber = int.tryParse(regNoOrPhone) != null && regNoOrPhone.length == 10;
    final param = isNumber ? "phone" : "reg_no";
    final response = await http.get(Uri.parse("$baseUrl?endpoint=track_complaint&$param=$regNoOrPhone"));
    if (response.statusCode == 200) {
      final json = jsonDecode(response.body);
      return json['data'];
    }
    return [];
  }
}
```

---

### B. React Native / JavaScript Example

```javascript
const BASE_URL = "http://YOUR_SERVER_IP/jnnjhansi/api/index.php";

export const api = {
  // 1. Get Home Dashboard
  getHomeData: async () => {
    const res = await fetch(`${BASE_URL}?endpoint=home`);
    const data = await res.json();
    return data.data;
  },

  // 2. Get Notices
  getNotices: async (page = 1, search = "") => {
    const res = await fetch(`${BASE_URL}?endpoint=notices&page=${page}&search=${encodeURIComponent(search)}`);
    const data = await res.json();
    return data;
  },

  // 3. Lodge Grievance
  lodgeComplaint: async (complaintData) => {
    const res = await fetch(`${BASE_URL}?endpoint=lodge_complaint`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(complaintData)
    });
    return await res.json();
  },

  // 4. Track Grievance
  trackComplaint: async (regNo) => {
    const res = await fetch(`${BASE_URL}?endpoint=track_complaint&reg_no=${encodeURIComponent(regNo)}`);
    return await res.json();
  }
};
```

---

## 5. HTTP Status Codes Summary

| Code | Status | Meaning |
|---|---|---|
| `200` | OK | Request was successful, data returned. |
| `201` | Created | Resource successfully created (Complaint filed / Feedback saved). |
| `400` | Bad Request | Missing required parameters or payload fields. |
| `404` | Not Found | Requested record, page, or complaint does not exist. |
| `405` | Method Not Allowed | Called with invalid HTTP method (e.g. GET instead of POST). |
| `500` | Server Error | Database or server processing exception. |

---

*Documentation maintained by Nagar Nigam Jhansi Technical Team.*
