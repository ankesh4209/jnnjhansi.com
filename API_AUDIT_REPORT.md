# Nagar Nigam Jhansi - Comprehensive API Audit Report

> **Audit Date:** September 2026  
> **Audited Environment:** Nagar Nigam Jhansi Codebase (`c:\xampp\htdocs\jnnjhansi`)  
> **Scope:** Backend REST API (`api/index.php`), Legacy WebServices (`webservice.php`), Frontend Templates (`templates/*`), Admin Subsystems, Database Schemas, and Third-Party Gateways.

---

## 1. Executive Summary

A comprehensive code audit was conducted across the backend and frontend components of the Nagar Nigam Jhansi web and mobile ecosystem. 
The portal combines a **modern dynamic RESTful API (`api/index.php`)**, legacy **SOAP-style/JSON web services (`webservice.php`)**, and server-side PHP template modules.

While the modern REST API provides clean JSON responses and CORS support, several critical issues were identified:
1. **Frontend-Backend Contract Mismatch:** A high-impact bug in `contactus.html` and `feedback.html` causes the frontend to display a red error notification even when the feedback is successfully created in the database.
2. **Security Vulnerabilities:** Plain-text SQL injection vulnerabilities in `webservice.php`, hardcoded credentials for SMS gateways (Cropsoft & One97), and legacy MD5 hashing.
3. **Architectural Duplications:** Redundant endpoints (`news` vs `works`), duplicated database tables (`complainant` vs `tbl_automation`, `tbl_department` vs `department`), and dual stock modules (`stock` vs `stock_hindi`).
4. **Missing Production Safeguards:** Absence of rate limiting, lack of pagination on image-heavy gallery endpoints, and unauthenticated public mutation endpoints.

---

## 2. Discrepancy Matrix: Frontend vs. Backend Comparison

### 2.1 Critical Bug: Response Property Mismatch (`data.success` vs `data.status`)

* **Severity:** **HIGH**
* **Affected Files:**
  - Frontend: `templates/contactus.html` (Line 139)
  - Frontend: `templates/feedback.html` (Line 85)
  - Backend: `api/index.php` (Line 56 - `json_response()`)
* **Technical Root Cause:**
  In `templates/contactus.html`:
  ```javascript
  // Frontend code:
  fetch('api/index.php?endpoint=feedback', { ... })
  .then(function(res) { return res.json(); })
  .then(function(data) {
    if (data && data.success) {  // <-- BUG: Checks for 'success' boolean
      // Shows green success message
    } else {
      // Shows red error message!
    }
  });
  ```
  However, `api/index.php` produces:
  ```php
  $response = [
      "status" => ($status_code >= 200 && $status_code < 300) ? "success" : "error",
      "code" => $status_code,
      "message" => $message
  ];
  ```
  Because `data.success` is `undefined`, the condition evaluates to `false`. **The user sees "Error sending message. Please try again" even though the database successfully inserted the record with HTTP 201 Created.**

* **Remediation:**
  Update `json_response()` in `api/index.php` to include `"success": ($status_code >= 200 && $status_code < 300)`:
  ```php
  $response = [
      "success" => ($status_code >= 200 && $status_code < 300),
      "status"  => ($status_code >= 200 && $status_code < 300) ? "success" : "error",
      "code"    => $status_code,
      "message" => $message
  ];
  ```

---

### 2.2 Broken / Incomplete APIs

| Endpoint | Issue Identified | Risk Level | Details |
|---|---|---|---|
| `GET /api/index.php?endpoint=track_complaint` | Ambiguous Status Mapping | Medium | Returns numeric codes (`status_code: 1` vs `app_status: 2, 3`) depending on whether record came from `complainant` or `tbl_automation`. Frontend mobile apps may misinterpret status. |
| `POST /webservice.php` (`action="save_comment"`) | No Input Validation | Medium | Accepts unvalidated survey comments directly from client. If `user_id` is passed as empty string, fails without descriptive HTTP status (returns generic `{"msg": "error"}`). |
| `GET /admin/returnofficer.php` | Legacy HTML Response | Low | Returns raw HTML `<select>` markup instead of structured JSON. Forces tight coupling between UI HTML and backend. |

---

### 2.3 Duplicate & Redundant Endpoints

1. **`news` vs `works` (`api/index.php` Lines 257-258):**
   - Both `?endpoint=news` and `?endpoint=works` execute identical logic querying `jnnworks`.
   - *Recommendation:* Deprecate `works` alias in favor of the canonical `news` endpoint.
2. **`pages` vs `page` (`api/index.php` Lines 371-372):**
   - Both plural and singular are accepted. `?endpoint=pages&id=1` and `?endpoint=page&id=1` do the same thing.
   - *Recommendation:* Keep `GET /pages` for list and `GET /page?id={id}` for single item, but standardize routing.
3. **Dual Stock Inventory Systems (`stock/` and `stock_hindi/`):**
   - Two entirely separate database table schemas exist (`epr_items`, `epr_stocks` vs `items`, `stocks`).
   - *Recommendation:* Unify inventory database and support bilingual names via columns (`name_en`, `name_hi`).

---

### 2.4 Missing APIs (Gap Analysis Against Modern App Requirements)

| Required Feature | Current Status in Codebase | Recommended Solution |
|---|---|---|
| **Online Payment Gateway** | **ABSENT** (Only internal manual finance ledger `finance/addpayamt.php` exists). | Integrate Razorpay / Paytm PG webhook endpoints for municipal taxes, water bills, and tender form fees. |
| **AI / Citizen Chatbot** | **ABSENT** (No AI/NLP service connected). | Build `/api/index.php?endpoint=ai_chat` using Google Gemini or Dialogflow for citizen query resolution. |
| **Academic Courses / Programs** | **NOT APPLICABLE** (Municipal Portal; not an educational institution). | N/A - Not part of municipal charter. |
| **Lead / Admission APIs** | **NOT APPLICABLE** (Citizen Grievance & Smart City Survey fulfill citizen outreach). | N/A - Replaced by civic grievance workflows. |
| **User Profile Management** | **ABSENT** for general public (only Smart City login in `webservice.php`). | Introduce JWT-based citizen account management (`/api/v1/auth/profile`). |
| **Push Notifications (FCM)** | **ABSENT** (Only HTTP SMS gateways used). | Add Firebase Cloud Messaging (FCM) integration for real-time grievance status alerts on mobile apps. |

---

### 2.5 Missing Pagination & Query Filtering

| Endpoint | Current Implementation | Missing Capability | Impact |
|---|---|---|---|
| `GET /api/index.php?endpoint=gallery` | Returns all records (`SELECT * FROM tbl_gallary`) | No `page` or `limit` parameter | High bandwidth usage as gallery grows; slow mobile app loading. |
| `GET /api/index.php?endpoint=news` | Returns all records (`SELECT * FROM jnnworks`) | No pagination | Excessive payload size over cellular networks. |
| `GET /api/index.php?endpoint=officers` | Returns full list | No department filter (`?dept_id=...`) | Users cannot filter officers by department. |
| `GET /api/index.php?endpoint=departments` | Returns full list | No search keyword parameter | Cannot search departments dynamically. |

---

## 3. Security & Vulnerability Audit

### 3.1 SQL Injection in `webservice.php`
* **Vulnerability Location:** `webservice.php` Line 26:
  ```php
  $mobile = $obj->mobile;
  $password = $obj->password;
  $select = "select * from smartcity_reg where mobile='$mobile' AND password='$password'";
  $res = $db->query($select);
  ```
* **Risk:** **CRITICAL**. An attacker can bypass citizen authentication using standard SQL injection payload (`' OR '1'='1`).
* **Fix:** Use prepared statements with parameter binding or `mysqli_real_escape_string()`.

### 3.2 Hardcoded Third-Party Gateway Credentials
* **Vulnerability Location 1:** `webservice.php` Line 53 (Cropsoft SMS Gateway):
  ```php
  $url1="http://trans.cropsoft.co.in/reseller/sendsms.jsp?user=JNNJHS&password=JNNJHS&mobiles=$mobile...";
  ```
* **Vulnerability Location 2:** `jnnsamadhan/admin/bulk_sms.php` Line 46 (One97 SMS Gateway):
  ```php
  $url3="http://websms.one97.net/sendsms/push_sms.php?user=anoopjhoshi&pwd=anoopjhoshi&from=UPHDB...";
  ```
* **Risk:** **HIGH**. Plain-text vendor credentials exposed in version control. Third parties could exhaust SMS quota or send fraudulent messages.
* **Fix:** Store SMS gateway credentials in protected environment variables or `config/sms.config.php` outside webroot.

### 3.3 Public Unauthenticated Mutation Endpoints
* **Endpoints:** `POST /api/index.php?endpoint=lodge_complaint`, `POST /api/index.php?endpoint=feedback`
* **Risk:** **MEDIUM**. No CAPTCHA, token validation, or IP rate-limiting. Vulnerable to automated spam attacks and bot flooding.
* **Fix:** Implement rate limiting (e.g. 5 requests per minute per IP) and Google reCAPTCHA v3 or Cloudflare Turnstile token validation.

### 3.4 Legacy Password Hashing
* **Location:** `admin/index.php`, `contractors/admin/index.php`, `jnnsamadhan/admin/index.php`
* **Risk:** **MEDIUM**. Passwords are encrypted using legacy MD5 algorithm (`strcmp(md5($clean_pass), $v_password) == 0`).
* **Fix:** Migrate to PHP standard `password_hash($password, PASSWORD_BCRYPT)` with automatic rehash on login.

---

## 4. Frontend & Backend Mismatch Summary Table

| Frontend Component | Target API | Expected Field / Behavior | Actual Backend Behavior | Impact / Result |
|---|---|---|---|---|
| `templates/contactus.html` | `POST ?endpoint=feedback` | Expects `data.success == true` | Returns `data.status = "success"` | User sees error banner despite successful form submission. |
| `templates/feedback.html` | `POST ?endpoint=feedback` | Expects `data.success == true` | Returns `data.status = "success"` | User sees error banner despite successful form submission. |
| Mobile Complaint Form | `POST ?endpoint=lodge_complaint` | Supports `phone` or `mobile` | Backend accepts both | Fully compatible. |
| Mobile Complaint Form | `POST ?endpoint=lodge_complaint` | Supports `details` or `message` | Backend accepts both | Fully compatible. |
| Mobile Grievance Tracker | `GET ?endpoint=track_complaint` | Expects unified `status_text` | Returns "Resolved" or "Pending" | Fully compatible. |
| Mobile Notices Screen | `GET ?endpoint=notices` | Expects pagination object | Returns `current_page`, `limit`, `total_records`, `total_pages` | Fully compatible. |

---

## 5. Architectural Recommendations & Roadmap

1. **Unify Response Wrapper:** Update `json_response()` in `api/index.php` to include `"success": true/false` alongside `"status": "success"/"error"`.
2. **Implement Rate Limiting:** Add Redis or MySQL-based request throttling on all POST endpoints (`lodge_complaint`, `feedback`, `webservice.php`).
3. **Environment Security:** Remove hardcoded SMS credentials and database credentials from code files; load from `.env` or secure configuration files.
4. **Parameterize Legacy Queries:** Refactor `webservice.php` to use prepared statements to eliminate SQL injection vulnerabilities.
5. **Modern API Routing (v2):** Provide clean URLs via `.htaccess` rewrite rules (e.g. `/api/v1/notices` instead of `/api/index.php?endpoint=notices`).
6. **Add Pagination to Gallery & News:** Add `page` and `limit` query parameters to `gallery` and `news` endpoints.
