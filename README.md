# 🏢 Nilayam Heights HMS & 🎓 Yadav PG for Stay (Yadav.in)

[![PHP Version](https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Containerized-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
[![Three.js](https://img.shields.io/badge/Three.js-3D%20WebGL-black?style=for-the-badge&logo=three.js&logoColor=white)](https://threejs.org/)
[![OWASP Hardened](https://img.shields.io/badge/OWASP-Security%20Hardened-success?style=for-the-badge&logo=owasp&logoColor=white)](https://owasp.org/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE)

> **🙏 जय श्री कृष्णा &bull; सर्वे भवन्तु सुखिनः सर्वे सन्तु निरामयाः 🙏**  
> *"May all be happy, may all be peaceful, healthy and protected."*

---

## 📌 Executive Overview (For the Team)

**Nilayam Heights HMS & Yadav PG** is an enterprise-grade, dual-property residential automation and student accommodation platform engineered with **PHP 8.4**, **MySQL 8.0**, and **Three.js WebGL**.

It solves real-world residential operational challenges by unifying two distinct property models within a single, secure software ecosystem:
1. **🏢 Nilayam Heights Housing Society**: A gated residential apartment society requiring automated maintenance billing, real-time security guard gate registry, official community notice broadcasts, 24/7 AI virtual resident assistance, and interactive 3D digital-twin campus modeling.
2. **🎓 Yadav PG for Stay (Yadav.in)**: A dedicated boy's student coliving hostel across 4 floors managing single, double, and triple sharing bed allocations, direct student self-registration, 1-minute digital government KYC verification, and warden vacancy tracking.

**Designed & Engineered by [Yuvraj](mailto:yuvrajyadav734@gmail.com)**

---

## 🏗️ System Architecture

```
Society-Management-System/
├── HMS-2/
│   ├── Includes/                     # Core system libraries & middleware
│   │   ├── config.php                # Environment-aware DB connection (MySQLi & PDO)
│   │   └── session.php               # Centralized RBAC middleware, session hardening & BCrypt auth
│   │
│   ├── admin/                        # Housing Society Administrator Portal
│   │   ├── Dashboard/admin.php       # 3D Overview, KPI metrics, quick visitor AJAX modal
│   │   ├── Bill/                     # Utility meter billing engine & dynamic invoice generation
│   │   ├── Announcement/             # Broadcast announcement management
│   │   ├── Members/                  # Resident member & security staff directory
│   │   ├── Home_Services/            # Local handyman / electrician directory
│   │   ├── Event_calender/           # Community calendar & festival scheduler
│   │   └── Chat/ai_assistant.php     # 24/7 Society AI Assistant interface
│   │
│   ├── members/                      # Housing Society Resident Portal
│   │   ├── Dashboard/member.php      # Resident self-service hub, noticeboard & directory
│   │   └── Bill/                     # Personal maintenance ledger & digital receipts
│   │
│   ├── staff/                        # Security Guard & Gate Staff Portal
│   │   └── Dashboard/staff.php       # Real-time visitor gate registry & check-in
│   │
│   ├── pg/                           # 🎓 Student Boy's PG Coliving Module (Yadav.in)
│   │   ├── login.php                 # Dedicated PG student & warden login portal
│   │   ├── register.php              # Real-time self-service student bed booking
│   │   ├── student/                  # Student Self-Service Suite
│   │   │   ├── profile.php           # Active student profile, occupancy & contact card
│   │   │   ├── kyc.php               # Personal & guardian KYC, document uploads, terms
│   │   │   ├── complaints.php        # Maintenance ticketing (+ Create complaint modal)
│   │   │   ├── vacate.php            # 30-day digital vacate notice intimation
│   │   │   ├── announcements.php     # PG broadcast notices from Owners Binod Yadav & Sumit Yadav
│   │   │   └── payments.php          # Payment history & deposit records
│   │   ├── admin/                    # Warden / PG Owner Operations Desk
│   │   │   ├── dashboard.php         # Live occupancy KPI cards & room-bed matrix
│   │   │   ├── rooms.php             # Room sharing config & resident bed assignment view
│   │   │   ├── students.php          # Student directory, KYC document viewer & approvals
│   │   │   ├── complaints_mgmt.php   # Maintenance ticket resolution workflow
│   │   │   ├── vacate_mgmt.php       # Vacate requests, settlement & deposit releases
│   │   │   └── announcements.php     # Broadcast notice dispatch
│   │   └── uploads/                  # Secure student identity documents & photos
│   │
│   ├── home/                         # Public responsive glassmorphism landing page
│   │   ├── home.php                  # Modern dual-property showcase portal
│   │   ├── home.css                  # High-contrast glassmorphic styling & responsive layout
│   │   └── logo.svg                  # Vector architectural brand emblem
│   │
│   ├── js/3d_society.js              # Throttled Three.js WebGL 3D architectural engine
│   ├── css/master_theme.css          # Modern dark-navy UI design system
│   ├── login.php                     # Hardened Society multi-role authentication endpoint
│   ├── register.php                  # Resident sign-up with server-side validation
│   └── hms.sql                       # Complete MySQL relational database schema
│
├── Dockerfile                        # Multi-stage production container build
├── docker-compose.yml                # Microservices orchestration (PHP 8.2 + MySQL 8.0)
└── .env.example                      # Environment variable template
```

---

## 🌟 Core Modules Breakdown

### 1. 🏢 Nilayam Heights Housing Society
- **Role-Based Access Control (RBAC)**: Strict privilege isolation between `admin` (Executive Committee), `member` (Apartment Owner / Tenant), and `staff` (Gate Security Guard).
- **Automated Billing Engine**: Calculates maintenance dues dynamically with utility meter readings (water, electricity units, and square footage multipliers) producing printable PDF/HTML invoices.
- **Real-Time Visitor Gate Registry**: Security guards log visitors, delivery personnel, and contractors via a fast, zero-refresh AJAX modal.
- **3D WebGL Digital Twin**: Custom architectural campus rendered in Three.js, automatically throttled to 30 FPS and paused when off-screen to preserve CPU/GPU battery.
- **Society AI Assistant**: Interactive resident helper providing instant answers to society rules, bylaws, and committee contacts.

### 2. 🎓 Yadav PG for Stay (Yadav.in)
- **Multi-Floor Bed Allocation Matrix**: Manages 8 rooms across Floors 1–4 with Single, Double, and Triple occupancy beds (`R-101 [Single]`, `R-406 [Double] Bed-B`).
- **Student Self-Service Registration (`pg/register.php`)**:
  - Streamlined sign-up without premature bed guessing (bed allocation is confirmed upon room visit).
  - Immediate redirect to Student KYC with automatic session initiation.
  - Automatic BCrypt password hashing.
- **Digital Student KYC Verification (`pg/student/kyc.php`)**:
  - Captures full Guardian / Parent details: Father Name & Mobile, Mother Name & Mobile, and Permanent Residential Address.
  - File upload engine storing student profile portraits, Address Proof Front/Back, and Government ID Proof Front/Back (`Aadhaar / Voter / College ID`).
  - Integrated Terms & Conditions modal ensuring disciplinary compliance.
- **Warden & Society Admin Verification Desks (`pg/admin/students.php` & `admin/pg_students.php`)**:
  - Admin/Warden can review student photos, inspect submitted documents in full, click **Verify KYC** / **Reject**, and allocate vacant beds with 1 click.
- **Pure Accommodation (No Food Provided)**:
  - Focused strictly on high-quality student living: **High-Speed Wi-Fi, RO Drinking Water, 24/7 Power Backup, and Daily Common Area Cleaning**.
  - All mess/meal modules have been cleanly omitted as food is not provided.
- **Direct Owner Pricing**:
  - Room charges and security deposit terms are custom and arranged directly with **Owners Binod Yadav & Sumit Yadav** upon room visit (no fixed fees advertised).

---

## 🔐 Enterprise Security Architecture (OWASP Hardened)

| Security Domain | Implementation Details |
| :--- | :--- |
| **SQL Injection (SQLi)** | **Zero raw SQL concatenation**. 100% converted to parameterized prepared statements (`mysqli_prepare()`, `mysqli_stmt_bind_param()`, `mysqli_stmt_execute()`). |
| **Password Storage** | **BCrypt hashing** (`PASSWORD_DEFAULT` with cost 12). Legacy plain-text passwords automatically upgrade to BCrypt upon first successful authentication. |
| **Cross-Site Scripting (XSS)** | All dynamic database outputs passed through `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`. |
| **Session Security** | `session.cookie_httponly = 1`, `session.cookie_samesite = 'Lax'`, and automatic `session_regenerate_id(true)` upon privilege escalation. |
| **Cross-Site Request Forgery (CSRF)** | Form tokens and strict HTTP POST state verification across financial and admission actions. |
| **Insecure Direct Object Reference (IDOR)** | Resident billing queries strictly bound to `$_SESSION['uid']`, preventing cross-account data leaks. |

---

## ⚡ Performance & Lighthouse Optimization

- **Three.js WebGL Performance Loop**:
  - Framerate capped at **30 FPS** using delta-time throttling to eliminate GPU overheating.
  - Monitored by `IntersectionObserver`: WebGL rendering halts completely when the user scrolls past the 3D viewport.
  - Pauses execution during background tab switches via the `document.visibilitychange` API.
- **Asset Optimization**:
  - Preconnect and DNS-prefetch headers for Google Fonts (`Outfit` & `Poppins`).
  - Preloaded critical background hero assets (`<link rel="preload" as="image" href="12.jpg">`).
  - High-resolution lightweight SVG brand vectors (`logo.svg`).

---

## 🚀 Quick Start Guide (For New Team Members)

### Option A: 🐳 Docker Compose (Recommended)

Run the entire application and database with a single command:

```bash
docker compose up -d --build
```

Access the application in your browser:
- 🌐 **Landing Page**: [http://localhost:8080/home/home.php](http://localhost:8080/home/home.php)
- 🎓 **Yadav PG Portal**: [http://localhost:8080/pg/login.php](http://localhost:8080/pg/login.php)

---

### Option B: 💻 Standalone Local Development (Mac / Linux / Windows)

#### 1. Prerequisites
- **PHP 8.0+** with `mysqli`, `pdo_mysql`, and `gd` extensions enabled (`php -v`)
- **MySQL 8.0+** running on `localhost:3306`

#### 2. Database Setup
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS hms;"
mysql -u root hms < "HMS-2/hms.sql"
```

#### 3. Start PHP Built-in Server
From the repository root:
```bash
php -S localhost:8000 -t HMS-2
```

Navigate to:
👉 **[http://localhost:8000/home/home.php](http://localhost:8000/home/home.php)**

---

## 🔑 Default Credentials & Test Accounts

| Role | Username / Email | Password | Access Portal |
| :--- | :--- | :--- | :--- |
| **Central Administrator (Dedicated SSO)** | `Yuvraj` | `password` | [`admin_login.php`](http://localhost:8000/admin_login.php) |
| **System Admin (Society)** | `Yuvraj` *(or `yuvrajyadav734@gmail.com`)* | `password` | [`admin/Dashboard/admin.php`](http://localhost:8000/admin/Dashboard/admin.php) |
| **Resident Member (Society)** | `rajesh.verma@gmail.com` | `password` | [`members/Dashboard/member.php`](http://localhost:8000/members/Dashboard/member.php) |
| **Gate Security Staff (Society)**| `deepak.facility@gmail.com` | `password` | [`staff/Dashboard/staff.php`](http://localhost:8000/staff/Dashboard/staff.php) |
| **PG Student Resident** | `yuvrajyadav734@gmail.com` | `password` | [`pg/student/profile.php`](http://localhost:8000/pg/login.php) |
| **Property Owners** | `Binod Yadav & Sumit Yadav` | In-Person | Direct stay agreements, pricing & room visits |
| **PG Warden / Tech Admin** | `Yuvraj` *(or `yuvrajyadav734@gmail.com`)* | `password` | [`pg/admin/dashboard.php`](http://localhost:8000/pg/login.php) |

---

## 📊 Database Schema Reference

| Table Name | Description | Key Relationships |
| :--- | :--- | :--- |
| `admin` | Housing Society & PG Warden administrators | Primary key: `id` |
| `member` | Registered apartment residents | Foreign key to `bill.member_id` |
| `society_kyc` | Resident identity, flat ownership & document proofs | Scoped to `member.id` |
| `staff` | Gate security guards & facility personnel | Role authorization |
| `bill` | Utility & maintenance invoices | Scoped to `member.id` |
| `announcement` | Society broadcast notices | Posted by `admin` |
| `registry` | Live gate security entries | Logged by `staff` |
| `pg_rooms` | Hostel rooms across Floors 1–4 | Single, Double, Triple sharing types |
| `pg_beds` | Individual beds inside PG rooms | Joined with `pg_students.bed_id` |
| `pg_students` | Active & vacating student residents | References `pg_rooms` and `pg_beds` |
| `pg_kyc` | Government ID & Guardian records | One-to-one with `pg_students.id` |
| `pg_complaints` | Maintenance tickets raised by students | Status: `Pending` / `Resolved` |
| `pg_vacate_intimations` | 30-Day move-out intimations | Deposit settlement workflow |
| `pg_payments` | Digital rent receipts & transaction logs | Scoped to `pg_students.id` |

---

## 👥 Engineering & Collaboration Guidelines

When contributing to this codebase, please uphold these engineering standards:
1. **Security First**: Always use `mysqli_prepare()` with strict type-binding. Never interpolate variables directly into SQL queries.
2. **Output Hygiene**: Always escape dynamic user data with `htmlspecialchars()`.
3. **Session Integrity**: Guard every authenticated endpoint using `require_role()` or `require_pg_role()`.
4. **Preserve Cultural & Brand Identity**: Yadav PG operates under the brand **Yadav.in** & **Yadav PG for Stay**, honoring the sacred slogan **"जय श्री कृष्णा (Jai Shree Krishna)"**.
5. **Clear Commit Messages**: Follow Conventional Commits (`feat:`, `fix:`, `refactor:`, `docs:`, `security:`).

---

## 👤 Author & Lead Engineer

- **Lead Full-Stack Developer**: **Yuvraj**
- **Email**: [yuvrajyadav734@gmail.com](mailto:yuvrajyadav734@gmail.com)
- **Primary Stack**: PHP 8.4 &bull; MySQL &bull; Three.js &bull; Docker &bull; JavaScript &bull; Linux &bull; Cloud Infrastructure

---

## 📄 License
This software is distributed under the [MIT License](LICENSE). Contributions and peer code reviews are welcome!
