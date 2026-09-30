# Nagar Nigam Jhansi - Final Master API Documentation

> **Ek Nazar Me Sab Kuch (All-in-One Guide)**  
> Ye document pure project ke sabhi APIs ka single master reference hai. Isme saaf-saaf bataya gaya hai ki **kaun sa API kiske liye hai**, uska URL kya hai, aur usko kaise use karna hai.

---

## 🧭 "Kaun Kiska Hai" — Quick Summary Table

Agar aap **Mobile App (Flutter / React Native)** ya **Website Frontend** bana rahe hain, to aapko bas niche diye gaye table ko dekhna hai:

| S.No. | Kaam (Purpose) | Method | Endpoint / URL | Input Data | Kahan Use Hota Hai |
|:---:|---|:---:|---|---|---|
| **1** | **Home Screen Dashboard** (Mayor, Commissioner, Top 5 Notices, Tenders, News, Counts) | `GET` | `api/index.php?endpoint=home` | Kuch nahi | Mobile App Home Screen |
| **2** | **Public Notices List** (Search + Pagination ke saath) | `GET` | `api/index.php?endpoint=notices` | `page`, `limit`, `search` | Notices Screen / Page |
| **3** | **E-Tenders List** (Search + Download PDF link ke saath) | `GET` | `api/index.php?endpoint=tenders` | `page`, `limit`, `search` | Tenders Screen / Page |
| **4** | **Nagar Vikas & News** (City Works photo + description) | `GET` | `api/index.php?endpoint=news` | Kuch nahi | News & Projects Screen |
| **5** | **Mayor Profile** (Photo + Message) | `GET` | `api/index.php?endpoint=mayor` | Kuch nahi | Mayor Screen / About City |
| **6** | **Municipal Commissioner Profile** (Photo + Message) | `GET` | `api/index.php?endpoint=commissioner` | Kuch nahi | Commissioner Screen |
| **7** | **Officers Directory** (Name, Designation, Phone, Email) | `GET` | `api/index.php?endpoint=officers` | Kuch nahi | Officers / Contact Directory |
| **8** | **Departments List** (Vibhag ke naam aur Nodal Officer) | `GET` | `api/index.php?endpoint=departments` | Kuch nahi | Departments List / Dropdown |
| **9** | **Photo Gallery** (Photos & Thumbnails) | `GET` | `api/index.php?endpoint=gallery` | Kuch nahi | Photo Gallery Screen |
| **10**| **CMS Pages List** (About Us, History, Rules etc.) | `GET` | `api/index.php?endpoint=pages` | Kuch nahi | Menu / Info Drawer |
| **11**| **Single Page Content** (Kisi page ka pura text/HTML) | `GET` | `api/index.php?endpoint=page&id=1` | `id` (page ID) | Page Detail Screen |
| **12**| **Shikayat Track Karo** (Grievance Tracking by Token/Phone) | `GET` | `api/index.php?endpoint=track_complaint` | `reg_no` ya `phone` | Complaint Tracking Screen |
| **13**| **Nayi Shikayat Darj Karo** (Lodge Public Grievance) | `POST` | `api/index.php?endpoint=lodge_complaint` | `name`, `phone`, `details`, etc. | New Grievance Form |
| **14**| **Feedback / Sampark Sandesh** (Citizen Feedback Form) | `POST` | `api/index.php?endpoint=feedback` | `name`, `mobile`, `feedback` | Contact Us / Feedback Page |
| **15**| **Citizen Login** (Smart City Mobile App) | `POST` | `webservice.php` | `action: "login"`, `mobile`, `password` | Citizen Login Screen |
| **16**| **Citizen Register (SMS OTP)** (Naya account + SMS password) | `POST` | `webservice.php` | `action: "register"`, `name`, `mobile` | Citizen Registration Form |
| **17**| **Smart City 14-Point Survey** (Citizen survey submission) | `POST` | `webservice.php` | `action: "save_comment"`, `user_id`, answers | Smart City Survey Form |

---

## 🌐 1. Base URLs

* **Localhost (XAMPP):**  
  `http://localhost/jnnjhansi/`
* **Live Production Server:**  
  `https://jnnjhansi.com/`

> **Note for Android Emulator:** Agar aap Android Emulator se test kar rahe hain to `localhost` ki jagah `http://10.0.2.2/jnnjhansi/api/index.php` use karein.

---

## 📦 2. Standard Response Format

Sabhi REST APIs ka response standard JSON format me aata hai:

```json
{
  "success": true,
  "status": "success",
  "code": 200,
  "message": "Data retrieved successfully",
  "data": { ... }
}
```

Agar Pagination ho:
```json
{
  "success": true,
  "status": "success",
  "code": 200,
  "message": "Notices retrieved successfully",
  "pagination": {
    "current_page": 1,
    "limit": 10,
    "total_records": 18,
    "total_pages": 2
  },
  "data": [ ... ]
}
```

---

## 🚀 3. Detailed Endpoint Guide (Category-wise)

---

### Category A: Mobile App Dashboard & Feeds

#### 1. Home Dashboard API
Mobile App ke home screen par ek saath sab kuch dikhane ke liye (Mayor, Commissioner, Top 5 Notices, Top 5 Tenders, News aur Statistics).

* **Method:** `GET`
* **Complete URL:** `http://localhost/jnnjhansi/api/index.php?endpoint=home`
* **Headers:** `Accept: application/json`
* **Success Response (200 OK):**
```json
{
  "success": true,
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
      "photo_url": "http://localhost/jnnjhansi/m_images/thumbs/mayor.jpg",
      "description": "Mayor's city message..."
    },
    "commissioner": {
      "id": 1,
      "name": "Satya Prakash",
      "designation": "Municipal Commissioner",
      "photo_url": "http://localhost/jnnjhansi/c_images/thumbs/comm.jpg",
      "description": "Commissioner's message..."
    },
    "latest_notices": [
      {
        "id": 105,
        "title": "Sanitation Guidelines",
        "pdf_name": "notice105.pdf",
        "pdf_url": "http://localhost/jnnjhansi/docs/notice105.pdf",
        "date": "2026-09-18"
      }
    ],
    "latest_tenders": [
      {
        "id": 84,
        "title": "Road Construction Tender",
        "pdf_name": "tender84.pdf",
        "pdf_url": "http://localhost/jnnjhansi/docs/tender84.pdf",
        "date": "2026-09-20"
      }
    ],
    "latest_news": [
      {
        "id": 42,
        "title": "Smart Solar Project",
        "description": "Details about work...",
        "image_url": "http://localhost/jnnjhansi/w_images/work42.jpg",
        "thumb_url": "http://localhost/jnnjhansi/w_images/thumbs/work42.jpg",
        "date": "2026-09-21"
      }
    ],
    "statistics": {
      "active_notices": 18,
      "active_tenders": 7,
      "departments_count": 12
    }
  }
}
```

---

#### 2. Public Notices API
* **Method:** `GET`
* **Complete URL:** `http://localhost/jnnjhansi/api/index.php?endpoint=notices&page=1&limit=10&search=diwali`
* **Query Parameters:**
  - `page` (Optional, default: 1): Page number
  - `limit` (Optional, default: 10): Ek page par kitne records
  - `search` (Optional): Search keyword
* **Success Response (200 OK):**
```json
{
  "success": true,
  "status": "success",
  "code": 200,
  "message": "Notices retrieved successfully",
  "pagination": {
    "current_page": 1,
    "limit": 10,
    "total_records": 1,
    "total_pages": 1
  },
  "data": [
    {
      "id": 105,
      "title": "Sanitation Guidelines Diwali 2026",
      "pdf_name": "sanitation_2026.pdf",
      "pdf_url": "http://localhost/jnnjhansi/docs/sanitation_2026.pdf",
      "added_date": "2026-09-18"
    }
  ]
}
```

---

#### 3. E-Tenders API
* **Method:** `GET`
* **Complete URL:** `http://localhost/jnnjhansi/api/index.php?endpoint=tenders&page=1&limit=10&search=road`
* **Query Parameters:**
  - `page` (Optional, default: 1)
  - `limit` (Optional, default: 10)
  - `search` (Optional): Tender title search
* **Success Response (200 OK):**
```json
{
  "success": true,
  "status": "success",
  "code": 200,
  "message": "Tenders retrieved successfully",
  "pagination": {
    "current_page": 1,
    "limit": 10,
    "total_records": 1,
    "total_pages": 1
  },
  "data": [
    {
      "id": 84,
      "title": "Road Construction Ward 5",
      "pdf_name": "tender_84.pdf",
      "pdf_url": "http://localhost/jnnjhansi/docs/tender_84.pdf",
      "added_date": "2026-09-20"
    }
  ]
}
```

---

#### 4. Development Works & News (Nagar Vikas)
* **Method:** `GET`
* **Complete URL:** `http://localhost/jnnjhansi/api/index.php?endpoint=news`
* **Success Response (200 OK):**
```json
{
  "success": true,
  "status": "success",
  "code": 200,
  "message": "News & development works retrieved successfully",
  "data": [
    {
      "id": 42,
      "title": "Smart Solar Lighting across Jhansi Fort",
      "description": "Nagar Nigam Jhansi installed 250 energy-efficient lights...",
      "image_url": "http://localhost/jnnjhansi/w_images/work_42.jpg",
      "thumb_url": "http://localhost/jnnjhansi/w_images/thumbs/work_42.jpg",
      "added_date": "2026-09-21"
    }
  ]
}
```

---

### Category B: Executive Profiles, Officers & Departments

#### 5. Mayor Profile
* **Method:** `GET`
* **Complete URL:** `http://localhost/jnnjhansi/api/index.php?endpoint=mayor`

#### 6. Commissioner Profile
* **Method:** `GET`
* **Complete URL:** `http://localhost/jnnjhansi/api/index.php?endpoint=commissioner`

#### 7. Officers Directory
* **Method:** `GET`
* **Complete URL:** `http://localhost/jnnjhansi/api/index.php?endpoint=officers`
* **Response:** Array of officers (Name, Designation, Office Contact, Email).

#### 8. Departments Directory
* **Method:** `GET`
* **Complete URL:** `http://localhost/jnnjhansi/api/index.php?endpoint=departments`
* **Response:** Departments list with Nodal Officer contact.

#### 9. Photo Gallery
* **Method:** `GET`
* **Complete URL:** `http://localhost/jnnjhansi/api/index.php?endpoint=gallery`
* **Response:** Photo list with full resolution URL and thumbnail URL.

#### 10. CMS Pages (About Us, History, Rules)
* **Page List:** `GET http://localhost/jnnjhansi/api/index.php?endpoint=pages`
* **Specific Page Detail:** `GET http://localhost/jnnjhansi/api/index.php?endpoint=page&id=1`

---

### Category C: Citizen Grievances & Feedback

#### 11. Track Grievance (Shikayat Status)
* **Method:** `GET`
* **Complete URL (by Reg No):**  
  `http://localhost/jnnjhansi/api/index.php?endpoint=track_complaint&reg_no=JNN-20260925-4192`
* **Complete URL (by Mobile Number):**  
  `http://localhost/jnnjhansi/api/index.php?endpoint=track_complaint&phone=9818247988`
* **Success Response (200 OK):**
```json
{
  "success": true,
  "status": "success",
  "code": 200,
  "message": "Complaint record found",
  "data": [
    {
      "id": 1502,
      "registration_no": "JNN-20260925-4192",
      "applicant_name": "Ramesh Kumar Sharma",
      "phone": "9818247988",
      "registered_date": "25-09-2026 11:32:00",
      "status_code": 1,
      "status_text": "Resolved",
      "details": "Street light not functional pole #12 Civil Lines",
      "disposal_remarks": "LED replaced on 27-09-2026"
    }
  ]
}
```

---

#### 12. Lodge Public Grievance (Nayi Shikayat)
* **Method:** `POST`
* **Complete URL:** `http://localhost/jnnjhansi/api/index.php?endpoint=lodge_complaint`
* **Headers:** `Content-Type: application/json`
* **Request Body (JSON):**
```json
{
  "name": "Anil Sahu",
  "phone": "9876543210",
  "address": "45/2 Sipri Bazar",
  "city": "Jhansi",
  "department_id": 1,
  "details": "Garbage cleanup required near community hall."
}
```
* **Success Response (201 Created):**
```json
{
  "success": true,
  "status": "success",
  "code": 201,
  "message": "Complaint registered successfully",
  "data": {
    "complaint_id": 1503,
    "registration_no": "JNN-20260930-8742",
    "applicant_name": "Anil Sahu",
    "phone": "9876543210",
    "status": "Pending",
    "registered_date": "30-09-2026 14:35:10"
  }
}
```

---

#### 13. Citizen Feedback / Contact Submission
* **Method:** `POST`
* **Complete URL:** `http://localhost/jnnjhansi/api/index.php?endpoint=feedback`
* **Headers:** `Content-Type: application/json`
* **Request Body (JSON):**
```json
{
  "name": "Sunita Verma",
  "mobile": "9818247988",
  "email": "sunita@example.com",
  "subject": "City Cleanliness",
  "feedback": "Please arrange more dustbins near Elite circle."
}
```
* **Success Response (201 Created):**
```json
{
  "success": true,
  "status": "success",
  "code": 201,
  "message": "Feedback submitted successfully",
  "data": {
    "feedback_id": 894,
    "name": "Sunita Verma",
    "mobile": "9818247988"
  }
}
```

---

### Category D: Citizen Login & SMS WebServices (`webservice.php`)

Ye endpoints `webservice.php` par chalte hain aur raw JSON body accept karte hain:

#### 14. Citizen Login
* **Method:** `POST`
* **Complete URL:** `http://localhost/jnnjhansi/webservice.php`
* **Request Body:**
```json
{
  "action": "login",
  "mobile": "9818247988",
  "password": "54321"
}
```
* **Success Response:**
```json
{
  "msg": "success",
  "user_id": 894,
  "username": "Sunita Verma"
}
```

---

#### 15. Citizen Registration (Sends SMS Password)
* **Method:** `POST`
* **Complete URL:** `http://localhost/jnnjhansi/webservice.php`
* **Request Body:**
```json
{
  "action": "register",
  "name": "Mohit Gupta",
  "mobile": "9818247988",
  "address": "Civil Lines, Jhansi"
}
```
* **Kaise Kaam Karta Hai:**
  Ye backend me 5-digit random password generate karta hai aur citizen ke mobile par Cropsoft SMS Gateway se SMS bhejta hai.
* **Success Response:**
```json
{
  "msg": "success"
}
```

---

#### 16. Smart City 14-Point Survey
* **Method:** `POST`
* **Complete URL:** `http://localhost/jnnjhansi/webservice.php`
* **Request Body:**
```json
{
  "action": "save_comment",
  "user_id": "894",
  "answer1": "Clean Water",
  "answer2": "Waste Management",
  "answer3": "Sewerage",
  "answer4": "Traffic",
  "answer5": "Smart Lighting",
  "answer6": "CCTV",
  "answer7": "Tourism",
  "answer8": "Parks",
  "answer9": "Digital Centers",
  "answer10": "Housing",
  "answer11": "Emergency",
  "answer12": "Transport",
  "answer13": "Industry",
  "answer14": "Solar Energy"
}
```
* **Success Response:**
```json
{
  "msg": "success"
}
```

---

## 💻 4. Ready-to-Use Frontend Integration Snippets

### Flutter (Dart) Snippet:
```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class JnnApi {
  static const String baseUrl = "http://localhost/jnnjhansi/api/index.php";

  // 1. Home Screen Data
  static Future<Map<String, dynamic>?> getHome() async {
    final res = await http.get(Uri.parse("$baseUrl?endpoint=home"));
    if (res.statusCode == 200) {
      return jsonDecode(res.body)['data'];
    }
    return null;
  }

  // 2. Track Grievance
  static Future<List<dynamic>> trackComplaint(String regNoOrPhone) async {
    final isNum = int.tryParse(regNoOrPhone) != null && regNoOrPhone.length == 10;
    final param = isNum ? "phone" : "reg_no";
    final res = await http.get(Uri.parse("$baseUrl?endpoint=track_complaint&$param=$regNoOrPhone"));
    if (res.statusCode == 200) {
      return jsonDecode(res.body)['data'] ?? [];
    }
    return [];
  }
}
```

### JavaScript / React Native Snippet:
```javascript
const BASE_URL = "http://localhost/jnnjhansi/api/index.php";

// 1. Get Notices
async function getNotices(page = 1, search = "") {
  const res = await fetch(`${BASE_URL}?endpoint=notices&page=${page}&search=${encodeURIComponent(search)}`);
  return await res.json();
}

// 2. Lodge Complaint
async function lodgeComplaint(formData) {
  const res = await fetch(`${BASE_URL}?endpoint=lodge_complaint`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(formData)
  });
  return await res.json();
}
```

---

## 📁 5. Testing Files Attached

1. **Postman Collection:** [`postman_collection.json`](file:///c:/xampp/htdocs/jnnjhansi/postman_collection.json) — Postman me direct **Import** karein aur 1-click me sabhi APIs test karein.
2. **Swagger / OpenAPI:** [`openapi.yaml`](file:///c:/xampp/htdocs/jnnjhansi/openapi.yaml) — Swagger Editor ya automated SDK tools me use karein.
3. **Audit Report:** [`API_AUDIT_REPORT.md`](file:///c:/xampp/htdocs/jnnjhansi/API_AUDIT_REPORT.md) — Bug findings and security analysis.
4. **API Inventory:** [`API_INVENTORY.md`](file:///c:/xampp/htdocs/jnnjhansi/API_INVENTORY.md) — Detailed matrix of all 32 endpoints.
