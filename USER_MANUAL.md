# EURO TAXI MANAGEMENT SYSTEM (ETMS)
# Complete Enterprise Operations & End-to-End User Manual
**System URL:** `https://eurotaxisystem.site`  
**Version:** 6.0.0 (Master Enterprise Edition)  
**Language:** English  
**Scope:** Full Web Administration Portal & Mobile Driver Application  

---

# Master Table of Contents
1. [System Architecture & Multi-Tier Ecosystem](#1-system-architecture--multi-tier-ecosystem)
2. [User Roles, Privilege Hierarchies & Permission Matrices](#2-user-roles-privilege-hierarchies--permission-matrices)
3. [Authentication, MFA (SMS/Email OTP) & Security Logins](#3-authentication-mfa-smsemail-otp--security-logins)
4. [My Account & Profile Management (Complete Flow)](#4-my-account--profile-management-complete-flow)
5. [Real-Time Notifications, Push Subscriptions & Audio Broadcasts](#5-real-time-notifications-push-subscriptions--audio-broadcasts)
6. [Internal Staff Chat, Support Center & Messaging Center](#6-internal-staff-chat-support-center--messaging-center)
7. [Live Dashboard & Operations Nerve Center](#7-live-dashboard--operations-nerve-center)
8. [Fleet & Unit Management: Step-by-Step "Add Unit" & Lifecycle](#8-fleet--unit-management-step-by-step-add-unit--lifecycle)
9. [Boundary Collection & Financial Remittance Management](#9-boundary-collection--financial-remittance-management)
10. [Driver Management: Step-by-Step "Add Driver", KYC & Debts](#10-driver-management-step-by-step-add-driver-kyc--debts)
11. [Vehicle Maintenance, Workshop Bays & Job Orders](#11-vehicle-maintenance-workshop-bays--job-orders)
12. [Inventory, Spare Parts, Purchase History & Suppliers](#12-inventory-spare-parts-purchase-history--suppliers)
13. [Live GPS Fleet Telematics & Remote Engine Immobilizer](#13-live-gps-fleet-telematics--remote-engine-immobilizer)
14. [Number Coding Management (UVVRP & Smart Rotation)](#14-number-coding-management-uvvrp--smart-rotation)
15. [Driver Behavior, Incident Lifecycle & Accident SOS](#15-driver-behavior-incident-lifecycle--accident-sos)
16. [Unit Profitability & AI Decision Support System (AI-DSS)](#16-unit-profitability--ai-decision-support-system-ai-dss)
17. [Office Expenses, Utility Bills & Approval Pipeline](#17-office-expenses-utility-bills--approval-pipeline)
18. [Staff Records & Payroll / Salary Management](#18-staff-records--payroll--salary-management)
19. [Franchise Case & LTFRB Compliance Management](#19-franchise-case--ltfrb-compliance-management)
20. [Super Admin Control Center & Enterprise Security](#20-super-admin-control-center--enterprise-security)
21. [Archive Vault & Disaster Recovery Management](#21-archive-vault--disaster-recovery-management)
22. [Mobile Driver Application: Step-by-Step Operator Guide](#22-mobile-driver-application-step-by-step-operator-guide)
23. [Server Optimization, CDN Settings & Troubleshooting FAQ](#23-server-optimization-cdn-settings--troubleshooting-faq)

---

# 1. System Architecture & Multi-Tier Ecosystem

The **Euro Taxi Management System (ETMS)** integrates cloud computing, IoT fleet telematics, and financial accounting into a unified enterprise platform.

```
 ┌─────────────────────────────────────────────────────────────────────────────┐
 │                      ETMS CLOUD INFRASTRUCTURE                              │
 │                 (Hostinger VPS / Nginx / PHP 8.2 / MySQL)                   │
 ├──────────────────────────────────────┬──────────────────────────────────────┤
 │         WEB ADMIN PORTAL             │        DRIVER MOBILE APP             │
 │  (Desktop / Laptop / Operations)     │   (Capacitor / Android APK / PWA)    │
 ├──────────────────────────────────────┼──────────────────────────────────────┤
 │ • Executive Live Dashboard           │ • On-Duty Shift Start & Checklist    │
 │ • Fleet Inventory & Health Scores    │ • Real-Time Boundary & Debt Status   │
 │ • Boundary Remittance & Debt Ledger  │ • 1-Tap Emergency SOS Alarm Button   │
 │ • Workshop Bay & Spare Parts Stock   │ • Roadside Breakdown Rescue Request  │
 │ • GPS Telematics & Engine Kill Relay │ • Photo Accident Incident Report     │
 │ • Office Expenses & Utility Bills    │ • Official Company Terms & Rules     │
 │ • Staff Payroll & Payslip Generator  │ • 24/7 Support Desk Chat Inbox       │
 │ • AI-DSS (Gemini) Profit Analytics   │ • Push Notifications & Advisories    │
 │ • Super Admin Security & Page Matrix │ • Clean Driver Performance Scorecard │
 └──────────────────────────────────────┴──────────────────────────────────────┘
```

---

# 2. User Roles, Privilege Hierarchies & Permission Matrices

ETMS enforces a strict **Role-Based Access Control (RBAC)** architecture augmented by **Granular Page Permission Matrices**:

| Role | Administrative Scope | Authorized System Modules |
| :--- | :--- | :--- |
| **Super Admin / Owner** | Complete system sovereignty. User account approvals, role definition, page access matrices, security master password, and audit monitoring. | All Modules + Super Admin Center + Security |
| **Fleet Manager / Admin** | Day-to-day fleet operations, unit registration, maintenance approvals, office expense approvals, AI profitability analysis. | Dashboard, Units, Boundaries, Maintenance, Profitability, Expenses, Coding |
| **Dispatcher / Operations** | Real-time vehicle monitoring, coding shift swaps, emergency SOS dispatching, roadside rescue coordination. | Live Tracking, Driver Behavior, SOS Alerts, Rescue, Coding, Support Center |
| **Cashier / Finance** | Daily boundary remittance collection, driver debt recovery, office utility payment entry, employee salary computation. | Boundaries, Driver Debts, Office Expenses, Salary, Profitability |
| **Workshop Mechanic** | Vehicle servicing, Periodic Maintenance Service (PMS), spare parts stock consumption, health score reset. | Maintenance Bay, Spare Parts Inventory, Suppliers |
| **Taxi Driver** | Vehicle operation, boundary tracking, debt repayments, emergency SOS signaling, breakdown rescue requests. | Mobile Driver App exclusively |

---

# 3. Authentication, Multi-Factor Authentication (MFA) & One-Time PIN (OTP) System

ETMS integrates an enterprise-grade **One-Time PIN (OTP) & Multi-Factor Security System** powered by the **Semaphore SMS Gateway** and secure SMTP email services.

```
 ┌─────────────────────────────────────────────────────────────────────────────┐
 │                      6-DIGIT OTP SECURITY ARCHITECTURE                      │
 ├─────────────────────────────────────────────────────────────────────────────┤
 │   [ USER INITIATES ACTION ] ───► [ SYSTEM GENERATES CRYPTOGRAPHIC 6-DIGIT ] │
 │                                               │                             │
 │                  ┌────────────────────────────┴──────────────────────────┐  │
 │                  ▼                                                       ▼  │
 │      [ SEMAPHORE SMS GATEWAY ]                                [ SMTP EMAIL ]│
 │      (Globe / Smart / DITO / TNT)                            (HTML Template)│
 │                  │                                                       │  │
 │                  └────────────────────────────┬──────────────────────────┘  │
 │                                               ▼                             │
 │                            [ 5-MINUTE COUNTDOWN TIMER ]                     │
 │                            [ 60-SEC RESEND THROTTLING ]                     │
 │                                               │                             │
 │   [ USER ENTERS PIN ] ───► [ VERIFIED & SAVED TO VERIFIED_BROWSERS ]        │
 └─────────────────────────────────────────────────────────────────────────────┘
```

---

### 3.1 The Four (4) Core OTP Use Cases in ETMS

#### Case 1: Device Verification OTP (MFA on Web Login)
* **Trigger:** When a user logs in from an unrecognized computer, new browser, mobile device, or after clearing browser cookies.
* **Flow:**
  1. User enters correct Username/Email and Password.
  2. The system pauses the session and generates a secure random 6-digit numeric token (e.g., `482915`).
  3. The OTP is sent immediately to the user's verified mobile number via SMS and/or registered email.
  4. An on-screen modal with a **5-Minute Countdown Timer** appears.
  5. User inputs the 6 digits > Clicks **"Verify OTP & Continue"**.
  6. Upon successful match, the device is whitelisted in the `verified_browsers` database table, allowing seamless future logins from that specific trusted device.

#### Case 2: New User Self-Registration OTP (`/register`)
* **Trigger:** When a new staff member or driver registers an account.
* **Flow:**
  1. User fills out registration details (Name, 11-digit mobile `09XXXXXXXXX`, Email, Password).
  2. Before the account is saved, ETMS dispatches an SMS OTP to confirm ownership of the mobile number.
  3. User enters the 6-digit code to verify their phone number (`/register/verify-otp`).
  4. Once phone ownership is proven, the registration enters the Super Admin approval queue.

#### Case 3: Forgot Password & Account Recovery OTP (`/forgot-password`)
* **Trigger:** When a user forgets their password.
* **Flow:**
  1. User clicks **"Forgot Password?"** on the login screen.
  2. Selects **"SMS OTP"** or **"Email OTP"**.
  3. Enters registered phone number or email address > Clicks **"Send Verification Code"**.
  4. Receives 6-digit PIN > Inputs code in the verification modal (`/forgot-password/verify-otp`).
  5. Upon verification, the user is redirected to create a new secure password without needing admin intervention.

#### Case 4: Mobile Driver App Onboarding & Device Pairing
* **Trigger:** When a driver logs into the Android / Capacitor mobile app for the first time or changes smartphones.
* **Flow:**
  1. Driver inputs mobile number and password.
  2. Driver App triggers API endpoint `/api/verify-device-otp`.
  3. Driver receives SMS OTP and submits the code within the mobile app interface.
  4. The unique smartphone hardware UUID is paired with the driver's profile to prevent account sharing.

---

### 3.2 OTP Rules, Throttling & Security Parameters

| Parameter | Configuration | Security Purpose |
| :--- | :--- | :--- |
| **Token Format** | 6-Digit Numeric (`000000` - `999999`) | High entropy, easy to type on mobile keyboards. |
| **Expiration Window** | Exactly 5 Minutes (300 seconds) | Prevents stale token replay attacks. |
| **Resend Cooldown** | 60 Seconds | Throttles requests to prevent SMS gateway flooding and spam. |
| **Max Failed Attempts** | 3 Consecutive Attempts | Automatically invalidates the OTP and locks the session for 15 minutes to prevent brute-force attacks. |
| **SMS Delivery Provider** | Semaphore SMS API (Philippines) | Direct local tier-1 telecom routing for 3-5 second delivery times. |

---

### 3.3 New User Self-Registration Walkthrough (`/register`)
1. Navigate to `https://eurotaxisystem.site/register`.
2. Complete all required fields: Full Name, Email, Mobile Phone, Username, Password, Role.
3. Click **"Submit Registration"** > Verify SMS OTP.
4. Account is queued in the Super Admin Control Center awaiting administrative activation.

---

# 4. My Account & Profile Management (Complete Flow) (`/my-account`)

The **My Account** module allows every authenticated user to manage their identity, security credentials, contact information, and notifications.

```
 ┌─────────────────────────────────────────────────────────────────────────────┐
 │                               MY ACCOUNT PANEL                              │
 ├───────────────────────────────┬─────────────────────────────────────────────┤
 │ [ 📸 Profile Avatar ]         │  Account Stats:                             │
 │ Juan Dela Cruz                │  • Last Login: Aug 22, 2026                 │
 │ Role: Fleet Manager           │  • Status: Active                           │
 │ Member Since: Jan 2026        │  • Security: 2FA Verified                   │
 ├───────────────────────────────┴─────────────────────────────────────────────┤
 │ 1. Profile Information (Full Name, Contact Number, Home Address)            │
 │ 2. Change Profile Photo (Upload, Auto-WebP Optimization)                    │
 │ 3. Change Account Password (Current Password, New Password, Confirmation)   │
 │ 4. Change Email Address (Verification Token Link sent to New Email)         │
 │ 5. Web Push Notification Subscription & Audio Chime Diagnostic              │
 └─────────────────────────────────────────────────────────────────────────────┘
```

### 4.1 Updating Profile Details
1. Open top-right profile menu > Select **"My Account"** (`/my-account`).
2. In the **Profile Information** card, edit:
   - **Full Name:** Your official name.
   - **Contact Number:** 11-digit mobile phone.
   - **Address:** Complete residential or office location.
3. Click **"Save Changes"**.

### 4.2 Changing Profile Photo Avatar
1. Hover over your profile photo avatar on `/my-account` > Click the **Camera Icon** (or **"Change Profile"**).
2. Select an image file (PNG/JPG) from your computer or phone.
3. Click **"Upload & Save Avatar"**. The system automatically crops, compresses, and converts the image to modern WebP format.

### 4.3 Updating Account Password
1. Scroll to the **Change Password** card.
2. Enter:
   - **Current Password:** Your active password.
   - **New Password:** Minimum 8 characters with alphanumeric and symbol mix.
   - **Confirm New Password:** Re-enter the exact new password.
3. Click **"Update Password"**. The system invalidates all other untrusted sessions.

### 4.4 Email Change Protocol (Two-Step Token Verification)
1. Scroll to the **Change Email Address** card.
2. Enter **New Email Address** > Click **"Request Email Change"**.
3. ETMS sends a secure, time-sensitive cryptographic verification link to the *new* email address.
4. Open the new inbox > Click the verification button/link (`/my-account/verify-email/{token}`).
5. Your login email address updates immediately upon verification.

---

# 5. Real-Time Notifications, Push Subscriptions & Audio Broadcasts

ETMS features a multi-channel alert delivery mechanism comprising **In-App Notification Bells**, **Real-Time Sound Broadcasts**, and **Web Push Service Workers**.

```
 ┌─────────────────────────────────────────────────────────────────────────────┐
 │                         NOTIFICATION BELL DROPDOWN                          │
 ├─────────────────────────────────────────────────────────────────────────────┤
 │ 🔴 [SOS PANIC ALERT] Taxi TX-104 triggered emergency in EDSA Shaw (10:42 AM)│
 │ 🟡 [CODING WARNING] 4 Vehicles restricted by UVVRP today                    │
 │ 🔧 [PMS DUE TODAY] Unit TX-108 reached 5,000 km threshold                  │
 │ ⚠️ [BOUNDARY SHORTAGE] Driver Juan Dela Cruz short ₱200 on shift #1024      │
 │ 📄 [FRANCHISE EXPIRY] LTFRB Case #2019-1234 expires in 30 days             │
 ├─────────────────────────────────────────────────────────────────────────────┤
 │             [ MARK ALL AS READ ]          [ DISMISS ALL ALERTS ]            │
 └─────────────────────────────────────────────────────────────────────────────┘
```

### 5.1 Real-Time In-App Alert Bell
- Located at the top navigation bar with a live pulsing red badge count.
- Clicking the bell opens the active notifications menu sorted by urgency.
- **Alert Types:**
  - 🚨 **Emergency SOS & Accident Alerts:** High-priority red notification.
  - 🚗 **UVVRP Number Coding Alerts:** Daily 05:00 AM broadcast.
  - 🔧 **Maintenance & PMS Due:** Mileage wear thresholds.
  - 💰 **Financial & Boundary Shortages:** Real-time ledger updates.
  - 📄 **LTFRB Franchise Expiration Warnings:** 60-day, 30-day, and 7-day milestones.

### 5.2 Emergency Sound Broadcasts (Real-Time Audio Chime)
- When a driver presses the **Emergency SOS button** or submits an **Accident Report**, the server immediately triggers an urgent high-pitch sound chime across all logged-in dispatcher and manager browsers.
- *Testing Sound:* Go to `/my-account` > Click **"Test Audio Chime"** to confirm speaker volume.

### 5.3 Web Push Notifications & Service Worker (`sw.js`)
- Click **"Enable Push Notifications"** when prompted by the browser.
- Allows the operating system (Windows, macOS, Android) to display desktop banner notifications even when the browser tab is minimized or in the background.

---

# 6. Internal Staff Chat, Support Center & Messaging Center

ETMS provides two dedicated communication channels: **Driver Support Center** (Helpdesk) and **Internal Staff Chat**.

```
 ┌─────────────────────────────────────────────────────────────────────────────┐
 │                           MESSAGING & SUPPORT CENTER                        │
 ├──────────────────────────────────────┬──────────────────────────────────────┤
 │  DRIVER SUPPORT CENTER (/support)    │    INTERNAL STAFF CHAT (/chat)       │
 ├──────────────────────────────────────┼──────────────────────────────────────┤
 │ • Driver Helpdesk Tickets            │ • 1-on-1 Direct Staff Messaging      │
 │ • Real-Time Conversation Stream      │ • Department Channels (Dispatch/Bay) │
 │ • Photo & Document Lightbox Viewer   │ • Emoji Message Reactions (👍❤️😂)   │
 │ • Resolve & Close Ticket Workflow    │ • Online Presence Indicator (🟢)     │
 └──────────────────────────────────────┴──────────────────────────────────────┘
```

### 6.1 Driver Support Center (`/support-center`)
1. **Inbox Sidebar:** Displays incoming support tickets from drivers, color-coded with unread badges.
2. **Conversation Thread:** Click any driver to view message history, timestamps, and attached photos (e.g., defective meter, app error screenshot).
3. **Replying to Driver:** Type reply in the message box > Click **"Send Message"** (or press `Enter`). The response instantly appears in the Driver Mobile App.
4. **Closing Tickets:** Click **"Mark Ticket as Resolved"** once the issue is settled.

### 6.2 Internal Staff Messaging (`/chat`)
1. **User List:** View all online and offline staff members (Dispatchers, Cashiers, Mechanics, Admins).
2. **Direct Messaging:** Click a colleague's name to open an encrypted 1-on-1 chat window.
3. **Emoji Reactions:** Hover over any message bubble > Select reaction emoji (`👍`, `❤️`, `😂`, `😮`, `😢`, `🙏`).
4. **File Attachments:** Click the paperclip icon to share receipts, job order sheets, or vehicle photos.

---

# 7. Live Dashboard & Operations Nerve Center (`/`)

The Dashboard updates asynchronously in real time via SSE polling.

### 7.1 Statistical Counter Cards
1. **Total Fleet:** Total registered vehicles in the database.
2. **Active On-Road:** Vehicles currently operating without active maintenance holds or flags.
3. **In Maintenance:** Vehicles currently docked inside the workshop bay.
4. **Coding Today:** Vehicles restricted by the Metro Manila UVVRP scheme today.
5. **Flagged / Impounded:** Vehicles locked due to violations, severe debt, or security flags.

### 7.2 Financial Gauges & Live Analytics
- **Today's Boundary Collection Meter:** Dynamic progress bar comparing Target Daily Boundary vs. Actual Remittances Collected.
- **7-Day Net Revenue Sparkline:** Daily graphical trend of Fleet Revenue minus Operating Expenses.
- **Emergency Alert Banner:** High-visibility red alert ticker displaying active driver SOS panic alerts, unresolved breakdown requests, and urgent PMS due warnings.

### 7.3 Quick Action Floating Dock
- **"Record Boundary"** — Opens instant collection remittance modal.
- **"Add Maintenance"** — Opens new repair job order modal.
- **"Flag Vehicle"** — Opens vehicle lockdown modal.
- **"Broadcast Alert"** — Opens instant notification modal to send push alerts to all drivers.

---

# 8. Fleet & Unit Management: Step-by-Step "Add Unit" & Lifecycle (`/units`)

### 8.1 Step-by-Step: Adding a New Taxi Unit
```
 ┌─────────────────────────────────────────────────────────────────────────────┐
 │                           NEW UNIT CREATION MODAL                           │
 ├─────────────────────────────────────────────────────────────────────────────┤
 │  Plate Number: [ ABC-1234 ]            Taxi Body Number: [ TX-101 ]         │
 │  Make & Model: [ Toyota Vios 1.3 ]     Year Model:       [ 2023 ]           │
 │  Chassis (VIN):[ 17-Digit Number ]     Engine Number:    [ 1NZ-FE123456 ]   │
 │  Franchise CPC:[ 2019-01234-CPC ]      Coding Day:       [ Monday (1-2) ]   │
 │  GPS Tracker IMEI: [ 864501234567890 ] Initial Status:   [ Active ]         │
 ├─────────────────────────────────────────────────────────────────────────────┤
 │                     [ CANCEL ]        [ SAVE VEHICLE RECORD ]               │
 └─────────────────────────────────────────────────────────────────────────────┘
```

1. Navigate to **Units** (`/units`) > Click **"+ Add New Unit"**.
2. **Step 1: Input Core Identifiers:**
   - **Plate Number:** (e.g., `ABC-1234`) — *Unique index*.
   - **Body Number / Taxi ID:** (e.g., `TX-101`) — *Fleet identifier*.
   - **Make & Model:** (e.g., `Toyota Vios 1.3 Dual VVT-i`).
   - **Year Model:** (e.g., `2023`).
3. **Step 2: Technical & Legal Specifications:**
   - **Chassis / VIN Number:** 17-character VIN from LTO Certificate of Registration (CR).
   - **Engine Number:** Stamped engine block serial.
   - **Franchise / Case Number:** Registered LTFRB CPC case reference.
   - **Coding Day:** `Monday` (1-2), `Tuesday` (3-4), `Wednesday` (5-6), `Thursday` (7-8), or `Friday` (9-0).
4. **Step 3: IoT Telematics & Initial Status:**
   - **GPS Tracker IMEI:** 15-digit hardware tracker IMEI for telematics sync.
   - **Initial Status:** `Active`, `In Maintenance`, or `Standby`.
5. Click **"Save Vehicle Record"**.

### 8.2 Unit Health Score (0% to 100%) & Reset Procedure
- **Computation Formula:** Score degrades based on:
  - Odometer distance travelled since last Periodic Maintenance Service (PMS).
  - Frequency of unexpected mechanical breakdown tickets.
  - Involvement in road collisions or driver abuse.
- **Resetting Health Score (`/units/{id}/reset-health`):**
  - Performed after a comprehensive 10,000 km PMS, major overhaul, or safety inspection.
  - Click **"Reset Health Score"** > Confirm action > System resets score to 100% and captures current GPS odometer as the baseline.

### 8.3 Flagging & Impounding Units (`/units/flagged`)
1. Locate target vehicle in the table > Click **"Flag Unit"**.
2. Select **Flag Reason:** `Unsettled Boundary Debt`, `Police Impound`, `Mechanical Safety Hazard`, or `Missing GPS Signal`.
3. Enter detailed remarks > Click **"Apply Flag"**. The unit status turns `Flagged` and is blocked from dispatch.

---

# 9. Boundary Collection & Financial Remittance Management (`/boundaries`)

### 9.1 Step-by-Step: Recording Daily Boundary Collection
1. Navigate to **Boundaries** (`/boundaries`) > Click **"+ Record Collection"**.
2. **Select Taxi Body Number:** (e.g., `TX-105`). The assigned driver auto-populates.
3. **Select Date & Shift Type:** Choose `Day Shift (12h)`, `Night Shift (12h)`, or `24-Hour Full Shift`.
4. **Expected Rate:** Automatically computed by active **Boundary Rules** (e.g., Standard ₱1,200, Coding Discount ₱800).
5. **Enter Actual Amount Paid:**
   - **Exact Remittance (Paid ₱1,200):** Status marks as `Settled`.
   - **Shortage (Paid ₱1,000):** System records ₱1,000 cash collected and automatically logs a **₱200 Shortage Debt** to the driver's ledger.
   - **Overpayment (Paid ₱1,500):** System credits ₱300 to amortize existing driver debts or records an advance credit balance.
6. Click **"Save Collection & Issue Receipt"**.

### 9.2 Managing Dynamic Boundary Rules (`/boundary-rules`)
1. Go to **Boundary Rules Settings** (`/boundary-rules`) > Click **"+ New Rule"**.
2. Configure parameters:
   - **Rule Title:** (e.g., `Coding Day Discount Rate`, `Sunday Special Rate`).
   - **Applicable Days:** Select active days (Monday through Sunday).
   - **Shift Type:** 12-Hour vs. 24-Hour.
   - **Base Rate (₱):** Standard boundary amount.
   - **Coding Day Discount (₱):** Deduction applied when vehicle is restricted by coding.
3. Click **"Save Rule"**.

---

# 10. Driver Management: Step-by-Step "Add Driver", KYC & Debts (`/driver-management`)

### 10.1 Step-by-Step: Registering a New Driver
```
 ┌─────────────────────────────────────────────────────────────────────────────┐
 │                         NEW DRIVER ONBOARDING MODAL                         │
 ├─────────────────────────────────────────────────────────────────────────────┤
 │  First Name:    [ Juan ]              Last Name:       [ Dela Cruz ]        │
 │  Mobile Phone:  [ 09171234567 ]       Date of Birth:   [ 1985-06-15 ]       │
 │  License No:    [ N01-12-345678 ]     License Expiry:  [ 2028-06-15 ]       │
 │  Address:       [ Block 4 Lot 12, Pasig City, Metro Manila ]                │
 │  Emergency Contact: [ Maria Dela Cruz (Wife) - 09181234567 ]                │
 ├─────────────────────────────────────────────────────────────────────────────┤
 │  DIGITAL KYC UPLOADS:                                                       │
 │  [ 📁 License Front/Back ]  [ 📁 NBI Clearance ]  [ 📁 Medical / Drug Test ]│
 ├─────────────────────────────────────────────────────────────────────────────┤
 │                     [ CANCEL ]        [ SAVE DRIVER PROFILE ]               │
 └─────────────────────────────────────────────────────────────────────────────┘
```

1. Navigate to **Driver Management** (`/driver-management`) > Click **"+ Register Driver"**.
2. **Step 1: Personal Profile:**
   - **First Name, Middle Name, Last Name:** Official legal names.
   - **Nickname:** Common alias used on the radio/app.
   - **Date of Birth & Gender:** Formatted input.
   - **Mobile Phone:** Primary 11-digit mobile number for SMS OTP.
   - **Residential Address:** Complete home address.
   - **Emergency Contact:** Full name, relationship, and contact number.
3. **Step 2: Professional License & Regulatory:**
   - **Driver's License Number:** Professional license series.
   - **License Expiry Date:** Expiration timestamp.
   - **SSS / PhilHealth / Pag-IBIG / TIN Numbers:** Statutory IDs.
4. **Step 3: Digital KYC Uploads:**
   - Upload clear photos/scans of: Driver's License (Front & Back), NBI Clearance, Police Clearance, Barangay Clearance, Medical / Drug Test.
5. Click **"Save Driver Profile"**.

### 10.2 Terms & Conditions Contract Management (`/driver-management/terms`)
1. Click **"Terms & Conditions"** tab.
2. Click **"Upload Contract Document"** > Choose scanned agreement image or PDF.
3. Click **"Upload Term"**. Documents are synced directly to the Driver Mobile App where drivers must review and sign.

### 10.3 Managing Driver Debts & Installments (`/driver-management/debts`)
- **Viewing Arrears:** The Pending Debts ledger tracks accumulated boundary shortages, repair damage liabilities, and cash advances.
- **Recording Debt Repayment (`/driver-management/pay-debt`):**
  1. Click **"Pay Debt"** on the driver's row.
  2. Enter Payment Amount (e.g., ₱300).
  3. Select Payment Channel: `Cash Remittance`, `Boundary Deduction`, or `Incentive Offset`.
  4. Click **"Submit Payment"**. Debt balance updates immediately.

### 10.4 Suspension & Banning Protocols
- **Temporary Suspension:** Select duration (1 to 30 days) and specify reason (e.g., *Late Remittance Violation*). Driver app access is locked.
- **Permanent Ban (`/driver-management/{id}/suspend-or-ban`):** Used for gross misconduct, boundary absconding, or DUI collisions. Banned drivers are blacklisted fleet-wide.
- **Unbanning:** Super Admins can click **"Unban"** (`/driver-management/{id}/unban`) after formal clearance.

---

# 11. Vehicle Maintenance, Workshop Bays & Job Orders (`/maintenance`)

### 11.1 Step-by-Step: Creating a New Maintenance Job Order
1. Navigate to **Maintenance** (`/maintenance`) > Click **"+ New Job Order"** (or **"+ Record Maintenance"**).
2. **Select Vehicle Unit:** Choose Plate/Body Number (e.g., `TX-105 / ABC-5678`).
   - *Security Check:* System automatically blocks maintenance creation if the unit is flagged as `Missing / Stolen`.
3. **Select Driver:** (Optional) Driver assigned at the time of issue.
4. **Select Maintenance Type:**
   - `Preventive Maintenance Service (PMS)` (Oil change, tune-up, 5k/10k check).
   - `Mechanical Repair` (Brakes, clutch, transmission, suspension).
   - `Electrical Repair` (Alternator, battery, lights, wiring, GPS tracker).
   - `Tire & Wheel` (Puncture, replacement, alignment).
   - `Body & Paint / Accident Repair`.
   - `Engine Overhaul / Major Repair`.
5. **Odometer Reading:** Enter current physical speedometer mileage.
6. **Date Started:** Select repair start timestamp.
7. **Select Assigned Mechanic(s):** Multi-select accredited workshop staff.
8. **Spare Parts Used (Inventory Link):**
   - Search spare part from inventory.
   - Enter quantity (e.g., *Engine Oil 10W-40 x 4L*, *Brake Pads x 1 Set*).
   - The system automatically calculates parts cost and verifies warehouse stock.
9. **Labor & Other Charges:** Enter labor cost and external machining fees.
10. **Description & Dispatcher Notes:** Enter symptoms, driver complaint, and diagnosis notes.
11. **Initial Status:** Set to `Pending` or `In Progress`.
12. Click **"Save Maintenance Record"**.

### 11.2 Moving to Bay Repair (`/maintenance/{id}/toggle-in-progress`)
- Click **"Start Work / In Progress"**.
- Job order status updates to `In Progress` and vehicle status changes to `In Maintenance` across all dispatch screens.

### 11.3 Completing Job Orders & Resetting Health Score (`/maintenance/{id}/toggle-complete`)
1. Click **"Mark Completed"** on the job order.
2. Enter **Date Completed** and final mechanic remarks.
3. Click **"Confirm Completion"**.
4. **System Automated Execution:**
   - Deducts all specified spare parts from warehouse stock.
   - Adds total repair cost to the vehicle's financial expense ledger.
   - Resets vehicle health score to 100% (PMS complete).
   - Sets vehicle status back to `Active` (Roadworthy).

---

# 12. Inventory, Spare Parts, Purchase History & Suppliers (`/inventory-management`)

### 12.1 Overview Metrics & Sub-Tabs
- **Metric Cards:** `Total Parts Count`, `Total Stock Value (₱)`, `Out of Stock Count`.
- **Sub-Tabs:**
  1. `Active Parts` — Current spare parts catalog.
  2. `Purchase History` — Chronological log of stock purchases linked to office expenses.
  3. `Archived Parts` — Deleted parts available for restoration.

### 12.2 Step-by-Step: Adding a New Spare Part (`/spare-parts`)
1. On the Inventory page, click **"Add Part"**.
2. Fill out modal fields:
   - **Part Name:** (e.g., `Front Brake Pad Set - Toyota Vios 2023`).
   - **Supplier:** Select supplier from dropdown.
   - **Price (₱):** Unit cost per piece/set.
   - **In Stock:** Initial quantity on hand.
3. Click **"Save Part"**.

### 12.3 Restocking & Stock Adjustments
- Click **"Edit / Restock"** on any active part.
- Update stock count and unit price > Click **"Update Part"**.

### 12.4 Supplier Management Modal (`/suppliers`)
1. Click **"Suppliers"** button in the inventory header.
2. Click **"+ Add Supplier"**.
3. Input: Company Name, Contact Person, Mobile Number, Email Address, Office Address.
4. Click **"Save Supplier"**.

---

# 13. Live GPS Fleet Telematics & Remote Engine Immobilizer (`/live-tracking`)

### 13.1 Real-Time Map & Telemetry Pins
- Interactive map powered by OpenStreetMap/Leaflet showing real-time GPS locations for all active units.
- **Marker Color Codes:**
  - 🟢 **Green Pin:** In Motion (Speed > 0 km/h, Ignition ACC On).
  - 🟡 **Yellow Pin:** Idling (Speed = 0 km/h, Ignition ACC On).
  - 🔴 **Red Pin:** Parked (Ignition ACC Off).
  - 🟣 **Pulsing Purple Pin:** Emergency SOS Triggered / Roadside Rescue Requested.

### 13.2 Vehicle Telemetry Card
- Click any taxi pin to inspect:
  - Driver Name & Profile Photo
  - Current Speed (km/h) & Heading Direction
  - Engine ACC Status (On/Off)
  - Daily Distance Travelled (km)
  - GPS Timestamp & Cellular Signal Quality

### 13.3 Remote Engine Immobilizer (Emergency Fuel Cut-Off) (`/live-tracking/engine-control`)
1. Select target vehicle on the map.
2. Click **"Engine Control"**.
3. Select Action:
   - **`Kill Engine (Cut Fuel Relay)`** — Sends an immediate digital command to cut the vehicle ignition circuit.
   - **`Restore Engine (Enable Ignition)`** — Restores the fuel relay circuit.
4. **Safety Confirmation:** Enter Admin authorization password and confirm action.
   *CAUTION: Engine cut-off should only be executed during theft, carjacking, unauthorized boundary absconding, or severe emergency.*

---

# 14. Number Coding Management (UVVRP & Smart Rotation) (`/coding`)

### 14.1 Metro Manila UVVRP Scheme Matrix
ETMS automatically maps license plates to restriction days:
- **Monday:** License plate ending in `1` and `2`
- **Tuesday:** License plate ending in `3` and `4`
- **Wednesday:** License plate ending in `5` and `6`
- **Thursday:** License plate ending in `7` and `8`
- **Friday:** License plate ending in `9` and `0`

### 14.2 Daily Coding Automation
- **05:00 AM Cron Job:** Automatically flags all restricted vehicles as `Coding Today`.
- **SMS & Push Alerts:** Automated notifications sent to drivers assigned to coding vehicles.
- **Smart Rotation Suggestions (`/coding/suggestions`):** Recommends available standby/reserve vehicles to reassign to affected drivers so operations continue without revenue loss.

### 14.3 Coding Violations & Fine Tracking
- Log MMDA/LTO traffic citations: Apprehending Agency, Officer Name, Ticket Number, Violation Date, Fine Amount (₱), and Driver Liability.

---

# 15. Driver Behavior, Incident Lifecycle & Accident SOS (`/driver-behavior`)

### 15.1 Logging an Incident (`/driver-behavior/incidents`)
1. Navigate to **Driver Behavior** > Click **"+ Log Incident"**.
2. Select **Driver** and **Vehicle Unit**.
3. Select **Classification:** `Minor Customer Complaint`, `Reckless Driving`, `Route Violation`, `Boundary Evasion`, `Physical Damage`, `Major Collision`.
4. Enter **Incident Date & Time**, **Location**, and **Full Narrative**.
5. Upload **Evidence Files:** Scene Photos, Dashcam Footage, Police Blotter Report, Third-Party Statements.
6. Click **"Save Incident"**.

### 15.2 Handling Emergency SOS & Accident Alerts (`/driver-behavior/accidents`)
1. **Real-time Audio Chime:** Plays an urgent alert tone on all dispatcher screens.
2. **Acknowledge (`/accident-alerts/{id}/acknowledge`):** Dispatcher clicks acknowledge to log receipt.
3. **Dispatch Rescue:** Dispatches nearest mechanic or towing service.
4. **Accident Damage Estimation:** Enter repair estimate, affected body panels, and insurance claims.

### 15.3 Driver Incentive Program (`/driver-behavior/incentives`)
- Tracks driver **Safe Driving Points (0-100)**.
- **Release Monthly Incentive (`/driver-behavior/release-incentive`):** Grants bonus cash payouts to drivers with zero complaints and clean records.

---

# 16. Unit Profitability & AI Decision Support System (AI-DSS) (`/unit-profitability`)

### 16.1 Profitability Ledger & Calculation
$$\text{Net Yield} = \text{Total Boundary Remitted} - (\text{Maintenance Cost} + \text{Spare Parts} + \text{Franchise Fees} + \text{Operational Expenses})$$

- View real-time table of all fleet vehicles sorted by Net Yield, ROI %, and Cost per Kilometer.
- **CSV Export:** Download full financial data for accounting audits.

### 16.2 AI Decision Support System (AI-DSS) (`/unit-profitability/ai-dss`)
Powered by Google Gemini AI, the AI-DSS analyzes 90-day operational telemetry to generate executive intelligence:
- **Underperforming / "Lemon" Units:** Flags vehicles where recurring repairs exceed boundary collections.
- **Replacement Recommendations:** Recommends optimal retirement or resale timing before vehicle value depreciates past economic viability.
- **Preventive Maintenance Optimization:** Identifies driving pattern stresses and suggests component replacements before roadside breakdowns occur.

---

# 17. Office Expenses, Utility Bills & Approval Pipeline (`/office-expenses`)

### 17.1 Step-by-Step: Recording an Electric Bill or Utility Expense
1. Navigate to **Office Expenses** (`/office-expenses`).
2. Click **"+ Add Expense"**.
3. **Expense Date:** Select payment or invoice date (defaults to today).
4. **Amount (₱):** Enter invoice amount (e.g., `12450.75`).
5. **Expense Category:** Click category dropdown and select:
   - **Utilities & Bills:** `Electricity (Meralco)`, `Water (Maynilad)`, `Internet & WiFi`, `Communications`.
   - **Materials & Supplies:** `Office Supplies`, `Pantry & Cleaning`.
   - **Facility & Infrastructure:** `Building Repairs`, `Construction Materials`, `Office Equipment`.
   - **Fleet Inventory & Parts:** `Spare Parts Purchase` *(Enables warehouse inventory sync)*.
   - **Administrative & Govt:** `Govt Permits & Fees`, `LTO & Registration`, `Insurance`, `Franchise Renewal`, `Staff Meals & Incentives`.
   - **Misc:** `Petty Cash`, `Other (Custom)`.
6. **Description / Particulars:** Enter detailed description (e.g., *Meralco Electric Bill for Depot & Office - Account # 1234567890 - Billing Period Aug 2026*).
7. **Payment Channel & Reference:** Select `Cash`, `Bank Transfer / Check`, or `E-Wallet / GCash` and input Reference Number.
8. **Attach Official Receipt (OR):** Upload receipt photo or billing statement PDF.
9. Click **"Save Expense"**.

### 17.2 Multi-Tier Approval Workflow
- Newly logged expenses enter `Pending Approval` status.
- Fleet Managers or Owners review the receipt attachment and click:
  - ✅ **"Approve"** (`/office-expenses/approve/{id}`) — Credits expense to company financial books.
  - ❌ **"Reject"** (`/office-expenses/reject/{id}`) — Rejects with required rejection reason notes.

---

# 18. Staff Records & Payroll / Salary Management (`/salary` & `/staff`)

### 18.1 Managing Office Staff (`/staff`)
- Maintain profiles for Dispatchers, Cashiers, Mechanics, Admin Officers, and Drivers.
- Store base monthly salary rates, daily rates, SSS/PhilHealth/Pag-IBIG details.

### 18.2 Payroll Computation & Payslip Generation (`/salary`)
1. Navigate to **Salary Management** > Click **"+ Compute Salary"**.
2. Select Staff Member and Pay Period (e.g., *1st Cut-off: 1st to 15th of the Month*).
3. System automatically calculates:
   - **Gross Earnings:** Base Pay + Overtime Hours + Performance Incentives.
   - **Deductions:** SSS + PhilHealth + Pag-IBIG + Cash Advance (Vale) Repayment + Late Penalties.
   - **Net Take-Home Pay.**
4. Click **"Save & Generate Payslip"** (`/salary/report`). Print or export PDF payslips with one click.

---

# 19. Franchise Case & LTFRB Compliance Management (`/franchise`)

- Track LTFRB Certificates of Public Convenience (CPC), franchise validity dates, and plate pairings.
- **Automated Expiry Alerts:** Displays color-coded warnings:
  - 🟡 **Yellow Alert:** 60 Days before expiration.
  - 🟠 **Orange Alert:** 30 Days before expiration.
  - 🔴 **Red Alert:** 7 Days before expiration (Urgent renewal required).
- Maintain case hearing dates, extension petitions, and inspection certificates.

---

# 20. Super Admin Control Center & Enterprise Security (`/super-admin`)

*Strictly restricted to System Owners and Super Administrators.*

### 20.1 Sub-Tabs in Super Admin Control Center
1. **Overview Tab:** System KPIs (Total Staff, Active Accounts, Rejected Accounts) and Live Recent Login Activity table.
2. **Create Staff Tab:** Form to provision new personnel accounts (Full Name, Username, Email, Role, Initial Password, Phone).
3. **All Users Tab:** Master table of all accounts with Quick Actions (Toggle Disable/Enable, Reset Password, Edit Profile, Change Role, Archive Account).
4. **Page Access Tab:** Granular permission matrix allowing owners to toggle individual screen access chips for each user.
5. **Login History Tab:** Full security audit trail recording User ID, Action (`Login`, `Logout`, `Failed Login`), IP Address, Browser User-Agent, and Timestamp.
6. **System Security Tab:** Configure Master Archive Password.
7. **Client Activity Tab:** Live user heartbeat and active page tracking.

### 20.2 User Approval & Instant Disable Switch
- **Approve Account:** Click **"Approve"** on pending registrations to grant login access.
- **Toggle Disable (`/super-admin/toggle-disable/{id}`):** Instantly terminates active user sessions and blocks access for resigned or suspended employees.

---

# 21. Archive Vault & Disaster Recovery Management (`/archive`)

### 21.1 Soft-Delete Architecture
- To prevent accidental catastrophic data loss, deleting any Unit, Driver, Maintenance Record, Expense, Spare Part, or Staff moves the item to the **Archive Vault** instead of permanently deleting it from the database.

### 21.2 Restoring Records (`/archive/restore/{type}/{id}`)
1. Navigate to **Archive Vault** (`/archive`).
2. Filter by Category: `Units`, `Drivers`, `Maintenance`, `Expenses`, `Spare Parts`, `Users`.
3. Locate record > Click **"Restore"**.
4. The item is instantly restored to its active operational table with all relationships intact.

### 21.3 Permanent Purge (`/archive/force-delete/{type}/{id}`)
1. In the Archive Vault, click **"Permanent Delete"**.
2. System displays a high-risk confirmation modal.
3. Enter the **Master Archive Password**.
4. Click **"Confirm Permanent Purge"**. The record is permanently removed from the database.

---

# 22. Mobile Driver Application: Step-by-Step Operator Guide

The Driver App runs on Android smartphones, tablets, or mobile web browsers.

```
 ┌──────────────────────────────────────────────┐
 │             EURO TAXI DRIVER APP             │
 ├──────────────────────────────────────────────┤
 │  🟢 [ ON DUTY ]         Taxi Body: TX-108    │
 │                                              │
 │  💰 Today's Boundary:    ₱1,200 / ₱1,200     │
 │  ⚠️ Accumulated Debt:    ₱0.00               │
 │  ⭐ Clean Driving Score: 98 / 100            │
 ├──────────────────────────────────────────────┤
 │  [ 🚨 EMERGENCY SOS ]    [ 🔧 REQUEST RESCUE] │
 ├──────────────────────────────────────────────┤
 │  📋 View Shift Ledger & Payment Receipts     │
 │  📸 Submit Accident Incident Report          │
 │  📜 Company Terms & Signed Agreements        │
 │  💬 Live Support Desk Chat                   │
 └──────────────────────────────────────────────┘
```

### Driver Workflows:
1. **Starting a Shift:**
   - Log into the app using Driver credentials.
   - Enter Vehicle Body Number > Complete pre-trip inspection checklist > Tap **"Go On Duty"**.
2. **Checking Daily Boundary & Balances:**
   - View current shift boundary due, partial payments credited by cashiers, and remaining debt balance.
3. **Triggering Emergency SOS (Panic Alarm):**
   - In case of robbery, medical emergency, or life-threatening situation:
   - **Press and hold the red "EMERGENCY SOS" button for 3 seconds.**
   - The app immediately transmits real-time GPS coordinates to the dispatch console and sounds loud emergency alarms in the office.
4. **Requesting Roadside Breakdown Rescue:**
   - Tap **"Request Rescue"** > Select issue: `Flat Tire`, `Engine Overheat`, `Mechanical Breakdown`, `Battery Dead`, `Out of Fuel`.
   - Transmit live location. Dispatcher immediately assigns a roving mechanic.
5. **Submitting an Accident Report:**
   - Tap **"Report Accident"**.
   - Capture scene photos using the camera (Vehicle damage, third-party plate, location).
   - Enter brief description > Tap **"Submit Accident Report"**.

---

# 23. Server Optimization, CDN Settings & Troubleshooting FAQ

### 23.1 Hostinger CDN & Image Optimization Settings
To ensure high-speed loading across mobile networks:
1. **WebP Image Compression:** **Keep ON** — Converts all uploaded receipts and vehicle photos to WebP format, reducing data usage by ~35%.
2. **Smart Image Optimisation:** **Keep ON** — Resizes camera uploads automatically (Desktop: 1600px, Mobile: 800px).
3. **Development Mode:** **Keep OFF during normal operations** — Turn ON only when uploading and testing new code files to bypass cache.

### 23.2 Database Synchronization Route (`/force-sync-db-2026`)
- In the event of schema updates or server cache staleness, authorized Super Admins can visit `https://eurotaxisystem.site/force-sync-db-2026` to run automated migrations, flush route/view cache, and re-index database tables.

### 23.3 Frequently Asked Questions & Operational Troubleshooting

| Issue / Symptom | Root Cause | Resolution Step |
| :--- | :--- | :--- |
| **Driver cannot log in; message says "Account Pending Approval".** | Account is newly registered. | Super Admin must navigate to `/super-admin` and click **"Approve"** on the driver's profile. |
| **Driver cannot log in; message says "Account Suspended".** | Driver was placed on temporary suspension. | Check `/driver-management`. Review suspension remarks or click **"Unban"** if cleared. |
| **Vehicle shows "Missing / Stolen" error when recording maintenance.** | Security lockdown flag is active. | Verify vehicle recovery in `/units` before recording maintenance job orders. |
| **Live GPS Map shows vehicle stationary or offline.** | Tracker power disconnected or SIM offline. | Check vehicle physical OBD/GPS device. Ensure tracker IMEI is correctly mapped in `/units`. |
| **Emergency SOS audio chime is not sounding on computer.** | Browser autoplay sound permissions blocked. | Click browser lock icon beside URL > Allow **Sound** and **Notifications** for `eurotaxisystem.site`. |
| **Spare part cannot be added to job order.** | Part is out of stock in warehouse. | Restock inventory at `/spare-parts` or adjust stock count. |

---
*End of Comprehensive User Manual. Euro Taxi Management System (ETMS) © 2026. All Rights Reserved.*
