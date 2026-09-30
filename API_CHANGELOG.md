# Nagar Nigam Jhansi - API Changelog & Version History

All notable changes, enhancements, and bug fixes to the Nagar Nigam Jhansi APIs and web services are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.1] - 2026-09-30 (Audit & Compatibility Patch)

### Fixed
- **Frontend-Backend Contract Mismatch:** Identified and resolved property mismatch where frontend JavaScript (`templates/contactus.html` and `templates/feedback.html`) checked for boolean `data.success`, while backend returned only string `data.status = "success"`. Standardized `json_response()` to provide both `"success": true` and `"status": "success"`.
- **Grievance Status Normalization:** Unified status text output in `track_complaint` between `complainant` table (binary 0/1) and `tbl_automation` table (workflow codes 1/2/3).
- **Date Formatting Consistency:** Added automatic Unix timestamp parsing for legacy `a_rdate` records in `tbl_automation` to always output ISO `Y-m-d H:i:s` strings.

### Added
- **Complete OpenAPI 3.0 Specification:** Added `openapi.yaml` documenting all public endpoints, query parameters, schemas, and response objects.
- **Postman Collection (v2.1):** Added `postman_collection.json` with ready-to-test preconfigured requests, query parameters, and sample bodies for mobile and web developers.
- **API Inventory Matrix (`API_INVENTORY.md`):** Complete catalog of all 32 APIs, AJAX endpoints, authentication handlers, and outbound gateways.
- **Security & Integrity Audit Report (`API_AUDIT_REPORT.md`):** Comprehensive audit detailing security gaps, SQL injection vulnerabilities, hardcoded vendor credentials, and architectural recommendations.

---

## [1.0.0] - 2026-09-29 (Dynamic REST API Launch)

### Added
- **Dynamic REST Routing Architecture:** Implemented single-entry REST router at `api/index.php?endpoint={name}` with universal CORS support (`Access-Control-Allow-Origin: *`).
- **All-in-One Home Dashboard (`?endpoint=home`):** Aggregated Mayor, Municipal Commissioner, Top 5 Public Notices, Top 5 E-Tenders, Top 5 Development Works, and quick counts in a single network round-trip for Flutter and React Native mobile apps.
- **Paginated Public Notices API (`?endpoint=notices`):** Full-text search and pagination (`page`, `limit`, `search`) with direct document links.
- **Paginated E-Tenders API (`?endpoint=tenders`):** Full-text search across tender notices and downloadable PDF links.
- **Development Works & News API (`?endpoint=news`):** Media feed with responsive thumbnail and high-resolution photo URLs from `w_images/`.
- **Executive Profiles APIs (`?endpoint=mayor`, `?endpoint=commissioner`):** Direct endpoints for city leadership profiles and desk messages.
- **Officers Directory API (`?endpoint=officers`):** Complete municipal officers list with phone numbers, emails, and designations.
- **Departments List API (`?endpoint=departments`):** Hierarchical department directory with nodal officer contact details.
- **Photo Gallery API (`?endpoint=gallery`):** Event and city photo gallery with full and thumbnail image URLs.
- **Dynamic CMS Pages Engine (`?endpoint=pages`, `?endpoint=page&id={id}`):** Dual-format CMS API serving sanitized plain-text and rich HTML for municipal information pages.
- **Citizen Grievance Tracking API (`?endpoint=track_complaint`):** Multi-table lookup supporting both Complaint Registration Number and Citizen Phone Number with automated fallback to automation tables.
- **Grievance Registration API (`?endpoint=lodge_complaint`):** POST endpoint creating citizen complaints with automated registration code generator (`JNN-YYYYMMDD-XXXX`).
- **Feedback & Suggestions API (`?endpoint=feedback`):** Public citizen feedback submission integrated with municipal campaign databases.
- **Self-Documenting Help Endpoint (`?endpoint=help`):** Interactive endpoint returning JSON directory of all available endpoints and request parameter requirements.

### Changed
- **Dynamic Base URL Calculation:** Replaced hardcoded IP addresses with dynamic host and path detection (`getBaseUrl()`) adapting automatically between localhost, testing servers, and production domain.
- **Error Suppression in JSON Mode:** Configured `display_errors = 0` inside `api/index.php` to prevent PHP warnings/notices from corrupting JSON payloads.

---

## [0.2.0] - Legacy WebService Phase (Smart City Survey)

### Added
- **`webservice.php`:** JSON web service supporting Smart City citizen registration, login, and survey submission.
- **Cropsoft SMS Gateway Integration:** Automated SMS notification dispatching 5-digit generated passwords to registered citizen mobile numbers via `trans.cropsoft.co.in`.
- **14-Point Smart City Questionnaire:** Submission handler saving citizen ratings across 14 urban development priorities in `smartcity_comment`.

---

## [0.1.0] - Legacy Subsystem Phase

### Added
- **Administrative Dropdown Handlers:** Added asynchronous HTML fragment responders:
  - `/admin/returnofficer.php`
  - `/jnnsamadhan/admin/returncompward.php`
  - `/jnnsamadhan/admin/returncorporator.php`
  - `/jnnsamadhan/admin/returndate.php`
  - `/jnnsamadhan/admin/returnofficer.php`
  - `/stock/admin/returnitems.php`
  - `/stock_hindi/admin/returnitems.php`
  - `/pis/admin/returnposts.php`
- **Bulk SMS & Email Utilities:** Added One97 SMS push script (`jnnsamadhan/admin/bulk_sms.php`) and group email dispatcher (`jnnsamadhan/admin/bulk_email.php`).
- **Multi-Document Contractor Uploads:** Added file upload processing for 12+ contractor compliance certificates in `contractors/registration.php`.
