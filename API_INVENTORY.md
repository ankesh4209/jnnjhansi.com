# Nagar Nigam Jhansi - Complete API Inventory & Catalog

> **Date:** September 2026  
> **Total Endpoints Documented:** 32  
> **Source of Truth:** Scanned codebase files (`api/index.php`, `webservice.php`, `admin/*`, `jnnsamadhan/*`, `contractors/*`, `stock/*`, `pis/*`, `vaad/*`)

---

## 1. RESTful API Catalog (`api/index.php`)

| ID | API Name | Method | Endpoint | Auth | Request Type | Database Table | Frontend / Consumer | Status |
|---|---|---|---|---|---|---|---|---|
| **REST-01** | Home Screen Aggregate Dashboard | `GET` | `?endpoint=home` | Public | None | `mayers`, `municipal_comm`, `notice`, `pdffiles`, `jnnworks`, `department` | Mobile App Home View, Web Homepage | **Active** |
| **REST-02** | Public Notices Directory | `GET` | `?endpoint=notices` | Public | Query: `page`, `limit`, `search` | `notice` | Mobile Notices Screen, `notices.php` | **Active** |
| **REST-03** | Municipal E-Tenders Directory | `GET` | `?endpoint=tenders` | Public | Query: `page`, `limit`, `search` | `pdffiles` | Mobile Tenders Screen, `tenders.php` | **Active** |
| **REST-04** | Development Works & News | `GET` | `?endpoint=news` / `works` | Public | None | `jnnworks` | Mobile News Screen, `nagar_vikash.php` | **Active** |
| **REST-05** | Mayor Profile & Desk | `GET` | `?endpoint=mayor` | Public | None | `mayers` | Mobile Mayor Screen, `mayor.php` | **Active** |
| **REST-06** | Municipal Commissioner Profile | `GET` | `?endpoint=commissioner` | Public | None | `municipal_comm` | Mobile Commissioner Screen, `commissioner.php` | **Active** |
| **REST-07** | Key Officers Directory | `GET` | `?endpoint=officers` | Public | None | `officers` | Mobile Officers Screen, `administration.php` | **Active** |
| **REST-08** | Municipal Departments List | `GET` | `?endpoint=departments` | Public | None | `tbl_department` (fallback `department`) | Mobile Departments Screen, `departments.php` | **Active** |
| **REST-09** | Photo Gallery Catalog | `GET` | `?endpoint=gallery` | Public | None | `tbl_gallary` | Mobile Photo Gallery, `gallery.php` | **Active** |
| **REST-10** | CMS Pages List | `GET` | `?endpoint=pages` | Public | None | `page` | Mobile Menu Drawer | **Active** |
| **REST-11** | Single CMS Page Detail | `GET` | `?endpoint=page&id={id}` | Public | Query: `id` | `page` | Mobile CMS Reader, `aboutus.php` | **Active** |
| **REST-12** | Citizen Grievance Tracking | `GET` | `?endpoint=track_complaint` | Public | Query/Body: `reg_no`, `phone` / `mobile` | `complainant`, `tbl_automation` | Mobile Grievance Tracker, `complaints.php` | **Active** |
| **REST-13** | Lodge Public Grievance | `POST` | `?endpoint=lodge_complaint` | Public | JSON/Form: `name`, `phone`, `address`, `city`, `details`, `department_id` | `complainant` | Mobile Grievance Filing Screen | **Active** |
| **REST-14** | Citizen Feedback & Contact Submission | `POST` | `?endpoint=feedback` | Public | JSON/Form: `name`, `feedback`, `mobile`, `email`, `subject` | `smartcity_reg` | `templates/contactus.html`, `templates/feedback.html` | **Active** |
| **REST-15** | API Documentation & Health Catalog | `GET` | `?endpoint=help` | Public | None | N/A | Developer Tooling, Browsers | **Active** |

---

## 2. Legacy Smart City WebServices (`webservice.php`)

| ID | Service Action | Method | Endpoint | Auth | Request Payload | Database Table | External Gateways | Status |
|---|---|---|---|---|---|---|---|---|
| **WS-01** | Smart City Citizen Login | `POST` | `/webservice.php` | Public | JSON: `action="login"`, `mobile`, `password` | `smartcity_reg` | None | **Active** |
| **WS-02** | Smart City Citizen Registration | `POST` | `/webservice.php` | Public | JSON: `action="register"`, `name`, `mobile`, `address` | `smartcity_reg` | **Cropsoft SMS Gateway** | **Active** |
| **WS-03** | Smart City 14-Point Survey Comments | `POST` | `/webservice.php` | Public | JSON: `action="save_comment"`, `user_id`, `answer1`..`answer14` | `smartcity_comment` | None | **Active** |

---

## 3. Subsystem AJAX Dependent Dropdown Endpoints

| ID | Endpoint | Method | Input Params | Response | Source Table | Consumer Component | Status |
|---|---|---|---|---|---|---|---|
| **AJAX-01** | `/admin/returnofficer.php` | `GET` | `id` (int) | HTML `<select>` | `con_officer` | Admin Concerning Officer Selector | **Active** |
| **AJAX-02** | `/jnnsamadhan/admin/returncompward.php` | `GET` | `Corporator` | HTML `<select>` | `ward` | Samadhan Ward Dropdown Filter | **Active** |
| **AJAX-03** | `/jnnsamadhan/admin/returncorporator.php` | `GET` | `WardId` | HTML `<select>` | `corporators` | Samadhan Corporator Selector | **Active** |
| **AJAX-04** | `/jnnsamadhan/admin/returndate.php` | `GET` | `date` | HTML `<select>` | `complainant` | Samadhan Grievance Date Filter | **Active** |
| **AJAX-05** | `/jnnsamadhan/admin/returnofficer.php` | `GET` | `department` | HTML `<select>` | `officer` | Samadhan Department Nodal Officer | **Active** |
| **AJAX-06** | `/stock/admin/returnitems.php` | `GET` | `ItemType` | HTML `<select>` | `epr_items` | Stock Management Category Items | **Active** |
| **AJAX-07** | `/stock_hindi/admin/returnitems.php` | `GET` | `ItemType` | HTML `<select>` | `items` | Hindi Stock Category Items | **Active** |
| **AJAX-08** | `/pis/admin/returnposts.php` | `GET` | `value` | HTML `<select>` | `posts` | PIS Employee Designation Selector | **Active** |

---

## 4. Administrative Session & Authentication Endpoints

| ID | Module Name | Method | Endpoint | Credential Fields | Target Table | Security Mechanism | Status |
|---|---|---|---|---|---|---|---|
| **AUTH-01** | Main Portal Admin | `POST` | `/admin/index.php` | `uname`, `password` | `login` / `tbl_admin` | MD5 / Fallback Admin Passwords + PHP Session | **Active** |
| **AUTH-02** | Contractors Portal | `POST` | `/contractors/admin/index.php` | `username`, `password` | `cw_admin` | MD5 Hash + PHP Session | **Active** |
| **AUTH-03** | Corporators Portal | `POST` | `/corporators/admin/index.php` | `username`, `password` | `cp_admin` | Plain/MD5 Hash + PHP Session | **Active** |
| **AUTH-04** | JNN Samadhan Grievance Portal | `POST` | `/jnnsamadhan/admin/index.php` | `username`, `password` | `admin` | MD5 Hash + PHP Session | **Active** |
| **AUTH-05** | Vaad Legal Case Management | `POST` | `/vaad/index.php` | `username`, `password` | `users` | Session Auth | **Active** |
| **AUTH-06** | License Management System | `POST` | `/license/index.php` | `username`, `password` | `admin` | Session Auth | **Active** |
| **AUTH-07** | Personnel Information (PIS) | `POST` | `/pis/admin/index.php` | `username`, `password` | `tbl_admin` | Session Auth | **Active** |
| **AUTH-08** | Inventory Management (Stock) | `POST` | `/stock/admin/index.php` | `username`, `password` | `epr_admin` | Session Auth | **Active** |

---

## 5. Media & File Upload Processing Endpoints

| ID | Functionality | Method | Endpoint | Upload Field | Target Directory | Database Record | Status |
|---|---|---|---|---|---|---|---|
| **UPL-01** | Public Notice PDF Upload | `POST` | `/admin/add_notice.php` | `file` (`.pdf`) | `/docs/` | `notice` | **Active** |
| **UPL-02** | E-Tender Document PDF Upload | `POST` | `/admin/add_tender.php` | `file` (`.pdf`) | `/docs/` | `pdffiles` | **Active** |
| **UPL-03** | Gallery Image & Thumbnail Upload | `POST` | `/admin/add_photo.php` | `photo` (`.jpg`, `.png`) | `/pic/`, `/pic/thumb/` | `tbl_gallary` | **Active** |
| **UPL-04** | Mayor Photo Upload | `POST` | `/admin/add_mayer.php` | `photo` (`.jpg`, `.png`) | `/m_images/`, `/m_images/thumbs/` | `mayers` | **Active** |
| **UPL-05** | Commissioner Photo Upload | `POST` | `/admin/add_commissioner.php` | `photo` (`.jpg`, `.png`) | `/c_images/`, `/c_images/thumbs/` | `municipal_comm` | **Active** |
| **UPL-06** | Nagar Vikas Work Photo Upload | `POST` | `/admin/add_work.php` | `photo` (`.jpg`, `.png`) | `/w_images/`, `/w_images/thumbs/` | `jnnworks` | **Active** |
| **UPL-07** | Contractor Registration Dossier | `POST` | `/contractors/registration.php` | 12 file fields | `/sapathpatra/`, `/pancard/`, etc. | `contractors` | **Active** |

---

## 6. Communication & Outbound Gateway Endpoints

| ID | Gateway Name | Protocol | Target External Endpoint | Triggering Script | Parameters Transmitted | Status |
|---|---|---|---|---|---|---|
| **COMM-01** | Cropsoft SMS Gateway | HTTP GET | `http://trans.cropsoft.co.in/reseller/sendsms.jsp` | `/webservice.php` | `user`, `password`, `mobiles`, `sms`, `senderid` | **Active** |
| **COMM-02** | One97 SMS Gateway | HTTP GET | `http://websms.one97.net/sendsms/push_sms.php` | `/jnnsamadhan/admin/bulk_sms.php` | `user`, `pwd`, `from`, `to`, `msg` | **Active** |
| **COMM-03** | PHPMailer / Mail System | SMTP / PHP Mail | Local MTA / SMTP Server | `/jnnsamadhan/admin/bulk_email.php`, `/phplib/mailclass.php` | `MIME-Version`, `From`, `To`, `Subject`, `Body` | **Active** |

---

## 7. Status Check for Additional Domain Categories

Per user audit requirements, each specific category has been verified against the codebase:

1. **All CRUD APIs:** Present across Admin modules (`admin/*`, `jnnsamadhan/*`, `contractors/*`, `stock/*`, `pis/*`, `vaad/*`) and REST API (`api/index.php`).
2. **Login / Register / OTP APIs:** Present via `webservice.php` (`action="login"`, `action="register"` with auto SMS password generation) and Administrative forms.
3. **Admin APIs:** Present across `admin/`, `jnnsamadhan/admin/`, `contractors/admin/`, `stock/admin/`, `pis/admin/`.
4. **User / Citizen APIs:** Present via `api/index.php` (`lodge_complaint`, `track_complaint`, `feedback`, `notices`, `tenders`).
5. **Dashboard APIs:** Present via `api/index.php?endpoint=home` (all-in-one statistics and executive profiles).
6. **Program / Course APIs:** **NOT APPLICABLE / ABSENT.** (Municipal civic portal; does not offer academic or course programs).
7. **Lead / Admission APIs:** **NOT APPLICABLE / ABSENT.** (Grievance registration and Smart City citizen surveys serve as citizen civic touchpoints).
8. **Payment Gateway APIs:** **ABSENT.** (No online PG SDK like Razorpay/Paytm/Billdesk is integrated; internal manual ledger `finance/addpayamt.php` and contractor payments `cw_payments` exist).
9. **Upload / Media APIs:** Present via `/admin/add_*.php` and `/contractors/registration.php`.
10. **AI / Chatbot APIs:** **ABSENT.** (No OpenAI, Gemini, Claude, or Dialogflow integration is configured in current codebase).
11. **Notification / Email APIs:** Present via Cropsoft SMS, One97 SMS, and PHPMailer / `bulk_email.php`.
12. **Search & Filter APIs:** Present in `notices` and `tenders` (`search`), `track_complaint` (`reg_no`, `phone`), and AJAX dropdown helpers.
