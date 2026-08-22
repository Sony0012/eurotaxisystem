# EURO TAXI MANAGEMENT SYSTEM (ETMS)
# Complete Enterprise Operations & End-to-End User Manual
**System URL:** `https://eurotaxisystem.site`  
**Version:** 5.0.0 (Exhaustive Technical & Operational Edition)  
**Language:** English  
**Scope:** Web Administration Portal & Mobile Driver Application  

---

# Master Table of Contents
1. [System Architecture & Multi-Tier Ecosystem](#1-system-architecture--multi-tier-ecosystem)
2. [User Roles, Privilege Hierarchies & Permission Matrices](#2-user-roles-privilege-hierarchies--permission-matrices)
3. [Authentication, MFA (SMS/Email OTP) & Profile Management](#3-authentication-mfa-smsemail-otp--profile-management)
4. [Live Dashboard & Operations Nerve Center](#4-live-dashboard--operations-nerve-center)
5. [Fleet & Unit Management (Full Vehicle Lifecycle)](#5-fleet--unit-management-full-vehicle-lifecycle)
6. [Boundary Collection & Financial Remittance Management](#6-boundary-collection--financial-remittance-management)
7. [Driver Management, KYC Verification, Debts & Terms](#7-driver-management-kyc-verification-debts--terms)
8. [Vehicle Maintenance, Workshop Bays & Job Orders](#8-vehicle-maintenance-workshop-bays--job-orders)
9. [Inventory, Spare Parts, Purchase History & Suppliers](#9-inventory-spare-parts-purchase-history--suppliers)
10. [Live GPS Fleet Telematics & Remote Engine Immobilizer](#10-live-gps-fleet-telematics--remote-engine-immobilizer)
11. [Number Coding Management (UVVRP & Smart Rotation)](#11-number-coding-management-uvvrp--smart-rotation)
12. [Driver Behavior, Incident Lifecycle & Accident SOS](#12-driver-behavior-incident-lifecycle--accident-sos)
13. [Unit Profitability & AI Decision Support System (AI-DSS)](#13-unit-profitability--ai-decision-support-system-ai-dss)
14. [Office Expenses, Utility Bills & Approval Pipeline](#14-office-expenses-utility-bills--approval-pipeline)
15. [Staff Records & Payroll / Salary Management](#15-staff-records--payroll--salary-management)
16. [Franchise Case & LTFRB Compliance Management](#16-franchise-case--ltfrb-compliance-management)
17. [Support Center, Internal Staff Chat & Announcements](#17-support-center-internal-staff-chat--announcements)
18. [Super Admin Control Center & Enterprise Security](#18-super-admin-control-center--enterprise-security)
19. [Archive Vault & Disaster Recovery Management](#19-archive-vault--disaster-recovery-management)
20. [Mobile Driver Application: Step-by-Step Operator Guide](#20-mobile-driver-application-step-by-step-operator-guide)
21. [Server Optimization, CDN Settings & Troubleshooting FAQ](#21-server-optimization-cdn-settings--troubleshooting-faq)

---

# 1. System Architecture & Multi-Tier Ecosystem

The **Euro Taxi Management System (ETMS)** integrates cloud computing, IoT fleet telematics, and financial accounting into a unified platform.

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

# 3. Authentication, MFA (SMS/Email OTP) & Profile Management

### 3.1 New User Self-Registration (`/register`)
1. Navigate to `https://eurotaxisystem.site/register`.
2. Fill out the mandatory registration fields:
   - **Full Name:** Official government legal name.
   - **Email Address:** Active personal/corporate email.
   - **Mobile Number:** 11-digit Philippine mobile format (`09XXXXXXXXX`).
   - **Username:** Unique login handle without special symbols.
   - **Password & Confirmation:** Minimum 8 characters with letters, numbers, and symbols.
   - **Desired Role:** Select `Admin`, `Dispatcher`, `Cashier`, `Mechanic`, or `Driver`.
3. Click **"Submit Registration"**.
4. *System Rule:* Newly registered accounts are set to `Pending Approval` and cannot log in until approved by a Super Admin.

### 3.2 Secure Login & Multi-Factor Device OTP (`/login`)
1. Enter your **Email/Username** and **Password** > Click **"Sign In"**.
2. **Multi-Factor Authentication (MFA / Device Verification):**
   - If logging in from a new browser, unfamiliar IP, or cleared cookies, ETMS triggers a 6-digit OTP.
   - OTP is immediately dispatched via SMS (using Semaphore API) and Email.
   - Enter the 6-digit PIN within the 5-minute countdown timer.
   - Upon successful verification, the browser fingerprint is saved in `verified_browsers`.

### 3.3 Forgot Password & Account Recovery
1. On the login screen, click **"Forgot Password?"**.
2. Select verification channel: **SMS OTP** or **Email OTP**.
3. Input registered phone number or email > Click **"Send Verification Code"**.
4. Enter received OTP > Input **New Password** > Confirm Password.
5. Click **"Reset Password"** to finalize.

### 3.4 My Account & Profile Customization (`/my-account`)
- **Update Personal Details:** Edit display name, phone number, and residential address.
- **Upload Profile Avatar:** Upload photo (JPG/PNG). System auto-optimizes to WebP.
- **Change Account Password:** Enter Current Password, New Password, and Confirm New Password.
- **Email Change Protocol:** Enter new email address > Click **"Request Email Change"**. ETMS sends a cryptographic verification link to the new inbox.

---

# 4. Live Dashboard & Operations Nerve Center (`/`)

The Dashboard updates asynchronously in real time via SSE polling.

### 4.1 Statistical Counter Cards
1. **Total Fleet:** Total registered vehicles in the database.
2. **Active On-Road:** Vehicles currently operating without active maintenance holds or flags.
3. **In Maintenance:** Vehicles currently docked inside the workshop bay.
4. **Coding Today:** Vehicles restricted by the Metro Manila UVVRP scheme today.
5. **Flagged / Impounded:** Vehicles locked due to violations, severe debt, or security flags.

### 4.2 Financial Gauges & Live Analytics
- **Today's Boundary Collection Meter:** Dynamic progress bar comparing Target Daily Boundary vs. Actual Remittances Collected.
- **7-Day Net Revenue Sparkline:** Daily graphical trend of Fleet Revenue minus Operating Expenses.
- **Emergency Alert Banner:** High-visibility red alert ticker displaying active driver SOS panic alerts, unresolved breakdown requests, and urgent PMS due warnings.

### 4.3 Quick Action Floating Dock
- **"Record Boundary"** — Opens instant collection remittance modal.
- **"Add Maintenance"** — Opens new repair job order modal.
- **"Flag Vehicle"** — Opens vehicle lockdown modal.
- **"Broadcast Alert"** — Opens instant notification modal to send push alerts to all drivers.

---

# 5. Fleet & Unit Management (Full Vehicle Lifecycle) (`/units`)

### 5.1 Step-by-Step: Adding a New Taxi Unit
1. Navigate to **Units** (`/units`) > Click **"+ Add New Unit"**.
2. Complete the vehicle technical specifications:
   - **Plate Number:** (e.g., `ABC-1234`) — *Unique index*.
   - **Body Number / Taxi ID:** (e.g., `TX-101`) — *Fleet identifier*.
   - **Make & Model:** (e.g., `Toyota Vios 1.3 Dual VVT-i`).
   - **Year Model:** (e.g., `2023`).
   - **Chassis / VIN Number:** 17-character VIN.
   - **Engine Number:** Stamped engine block serial.
   - **Franchise / Case Number:** Registered LTFRB CPC case reference.
   - **Coding Day:** `Monday` (1-2), `Tuesday` (3-4), `Wednesday` (5-6), `Thursday` (7-8), or `Friday` (9-0).
   - **GPS Tracker IMEI:** 15-digit hardware tracker IMEI for telematics sync.
   - **Initial Status:** `Active`, `In Maintenance`, or `Standby`.
3. Click **"Save Vehicle Record"**.

### 5.2 Unit Health Score (0% to 100%) & Reset Procedure
- **Computation Formula:** Score degrades based on:
  - Odometer distance travelled since last Periodic Maintenance Service (PMS).
  - Frequency of unexpected mechanical breakdown tickets.
  - Involvement in road collisions or driver abuse.
- **Resetting Health Score (`/units/{id}/reset-health`):**
  - Performed after a comprehensive 10,000 km PMS, major overhaul, or safety inspection.
  - Click **"Reset Health Score"** > Confirm action > System resets score to 100% and captures current GPS odometer as the baseline.

### 5.3 Flagging & Impounding Units (`/units/flagged`)
1. Locate target vehicle in the table > Click **"Flag Unit"**.
2. Select **Flag Reason:** `Unsettled Boundary Debt`, `Police Impound`, `Mechanical Safety Hazard`, or `Missing GPS Signal`.
3. Enter detailed remarks > Click **"Apply Flag"**. The unit status turns `Flagged` and is blocked from dispatch.

### 5.4 Exporting & Printing Fleet Masterlist (`/units/print`)
- Click **"Print Fleet Inventory"** to generate an official printable PDF summary listing all vehicles, chassis numbers, franchise expiration dates, and health statuses.

---

# 6. Boundary Collection & Financial Remittance Management (`/boundaries`)

### 6.1 Step-by-Step: Recording Daily Boundary Collection
1. Navigate to **Boundaries** (`/boundaries`) > Click **"+ Record Collection"**.
2. **Select Taxi Body Number:** (e.g., `TX-105`). The assigned driver auto-populates.
3. **Select Date & Shift Type:** Choose `Day Shift (12h)`, `Night Shift (12h)`, or `24-Hour Full Shift`.
4. **Expected Rate:** Automatically computed by active **Boundary Rules** (e.g., Standard ₱1,200, Coding Discount ₱800).
5. **Enter Actual Amount Paid:**
   - **Exact Remittance (Paid ₱1,200):** Status marks as `Settled`.
   - **Shortage (Paid ₱1,000):** System records ₱1,000 cash collected and automatically logs a **₱200 Shortage Debt** to the driver's ledger.
   - **Overpayment (Paid ₱1,500):** System credits ₱300 to amortize existing driver debts or records an advance credit balance.
6. Click **"Save Collection & Issue Receipt"**.

### 6.2 Managing Dynamic Boundary Rules (`/boundary-rules`)
1. Go to **Boundary Rules Settings** (`/boundary-rules`) > Click **"+ New Rule"**.
2. Configure parameters:
   - **Rule Title:** (e.g., `Coding Day Discount Rate`, `Sunday Special Rate`).
   - **Applicable Days:** Select active days (Monday through Sunday).
   - **Shift Type:** 12-Hour vs. 24-Hour.
   - **Base Rate (₱):** Standard boundary amount.
   - **Coding Day Discount (₱):** Deduction applied when vehicle is restricted by coding.
3. Click **"Save Rule"**.

---

# 7. Driver Management, KYC Verification, Debts & Terms (`/driver-management`)

### 7.1 Registering a Driver & Uploading KYC Documents
1. Navigate to **Driver Management** (`/driver-management`) > Click **"+ Register Driver"**.
2. **Personal Information:** First Name, Last Name, Nickname, Date of Birth, Mobile Phone, Address, Emergency Contact Name & Contact Number.
3. **License & Regulatory:** Professional Driver's License Number, Expiry Date, SSS/PhilHealth Numbers.
4. **Digital KYC Document Uploads:**
   - Professional Driver's License (Front & Back scanned image).
   - NBI Clearance (Valid within 6 months).
   - Police Clearance & Barangay Clearance.
   - Medical Certificate & Drug Test Result.
5. Click **"Save Driver Profile"**.

### 7.2 Terms & Conditions Contract Management (`/driver-management/terms`)
1. Click **"Terms & Conditions"** tab.
2. Click **"Upload Contract Document"** > Choose scanned agreement image or PDF.
3. Click **"Upload Term"**. Documents are synced directly to the Driver Mobile App where drivers must review and sign.

### 7.3 Managing Driver Debts & Installments (`/driver-management/debts`)
- **Viewing Arrears:** The Pending Debts ledger tracks accumulated boundary shortages, repair damage liabilities, and cash advances.
- **Recording Debt Repayment (`/driver-management/pay-debt`):**
  1. Click **"Pay Debt"** on the driver's row.
  2. Enter Payment Amount (e.g., ₱300).
  3. Select Payment Channel: `Cash Remittance`, `Boundary Deduction`, or `Incentive Offset`.
  4. Click **"Submit Payment"**. Debt balance updates immediately.

### 7.4 Suspension & Banning Protocols
- **Temporary Suspension:** Select duration (1 to 30 days) and specify reason (e.g., *Late Remittance Violation*). Driver app access is locked.
- **Permanent Ban (`/driver-management/{id}/suspend-or-ban`):** Used for gross misconduct, boundary absconding, or DUI collisions. Banned drivers are blacklisted fleet-wide.
- **Unbanning:** Super Admins can click **"Unban"** (`/driver-management/{id}/unban`) after formal clearance.

---

# 8. Vehicle Maintenance, Workshop Bays & Job Orders (`/maintenance`)

### 8.1 Step-by-Step: Creating a New Maintenance Job Order
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

### 8.2 Moving to Bay Repair (`/maintenance/{id}/toggle-in-progress`)
- Click **"Start Work / In Progress"**.
- Job order status updates to `In Progress` and vehicle status changes to `In Maintenance` across all dispatch screens.

### 8.3 Completing Job Orders & Resetting Health Score (`/maintenance/{id}/toggle-complete`)
1. Click **"Mark Completed"** on the job order.
2. Enter **Date Completed** and final mechanic remarks.
3. Click **"Confirm Completion"**.
4. **System Automated Execution:**
   - Deducts all specified spare parts from warehouse stock.
   - Adds total repair cost to the vehicle's financial expense ledger.
   - Resets vehicle health score to 100% (PMS complete).
   - Sets vehicle status back to `Active` (Roadworthy).

---

# 9. Inventory, Spare Parts, Purchase History & Suppliers (`/inventory-management`)

### 9.1 Overview Metrics & Sub-Tabs
- **Metric Cards:** `Total Parts Count`, `Total Stock Value (₱)`, `Out of Stock Count`.
- **Sub-Tabs:**
  1. `Active Parts` — Current spare parts catalog.
  2. `Purchase History` — Chronological log of stock purchases linked to office expenses.
  3. `Archived Parts` — Deleted parts available for restoration.

### 9.2 Step-by-Step: Adding a New Spare Part (`/spare-parts`)
1. On the Inventory page, click **"Add Part"**.
2. Fill out modal fields:
   - **Part Name:** (e.g., `Front Brake Pad Set - Toyota Vios 2023`).
   - **Supplier:** Select supplier from dropdown.
   - **Price (₱):** Unit cost per piece/set.
   - **In Stock:** Initial quantity on hand.
3. Click **"Save Part"**.

### 9.3 Restocking & Stock Adjustments
- Click **"Edit / Restock"** on any active part.
- Update stock count and unit price > Click **"Update Part"**.

### 9.4 Supplier Management Modal (`/suppliers`)
1. Click **"Suppliers"** button in the inventory header.
2. Click **"+ Add Supplier"**.
3. Input: Company Name, Contact Person, Mobile Number, Email Address, Office Address.
4. Click **"Save Supplier"**.

---

# 10. Live GPS Fleet Telematics & Remote Engine Immobilizer (`/live-tracking`)

### 10.1 Real-Time Map & Telemetry Pins
- Interactive map powered by OpenStreetMap/Leaflet showing real-time GPS locations for all active units.
- **Marker Color Codes:**
  - 🟢 **Green Pin:** In Motion (Speed > 0 km/h, Ignition ACC On).
  - 🟡 **Yellow Pin:** Idling (Speed = 0 km/h, Ignition ACC On).
  - 🔴 **Red Pin:** Parked (Ignition ACC Off).
  - 🟣 **Pulsing Purple Pin:** Emergency SOS Triggered / Roadside Rescue Requested.

### 10.2 Vehicle Telemetry Card
- Click any taxi pin to inspect:
  - Driver Name & Profile Photo
  - Current Speed (km/h) & Heading Direction
  - Engine ACC Status (On/Off)
  - Daily Distance Travelled (km)
  - GPS Timestamp & Cellular Signal Quality

### 10.3 Remote Engine Immobilizer (Emergency Fuel Cut-Off) (`/live-tracking/engine-control`)
1. Select target vehicle on the map.
2. Click **"Engine Control"**.
3. Select Action:
   - **`Kill Engine (Cut Fuel Relay)`** — Sends an immediate digital command to cut the vehicle ignition circuit.
   - **`Restore Engine (Enable Ignition)`** — Restores the fuel relay circuit.
4. **Safety Confirmation:** Enter Admin authorization password and confirm action.
   *CAUTION: Engine cut-off should only be executed during theft, carjacking, unauthorized boundary absconding, or severe emergency.*

---

# 11. Number Coding Management (UVVRP & Smart Rotation) (`/coding`)

### 11.1 Metro Manila UVVRP Scheme Matrix
ETMS automatically maps license plates to restriction days:
- **Monday:** License plate ending in `1` and `2`
- **Tuesday:** License plate ending in `3` and `4`
- **Wednesday:** License plate ending in `5` and `6`
- **Thursday:** License plate ending in `7` and `8`
- **Friday:** License plate ending in `9` and `0`

### 11.2 Daily Coding Automation
- **05:00 AM Cron Job:** Automatically flags all restricted vehicles as `Coding Today`.
- **SMS & Push Alerts:** Automated notifications sent to drivers assigned to coding vehicles.
- **Smart Rotation Suggestions (`/coding/suggestions`):** Recommends available standby/reserve vehicles to reassign to affected drivers so operations continue without revenue loss.

### 11.3 Coding Violations & Fine Tracking
- Log MMDA/LTO traffic citations: Apprehending Agency, Officer Name, Ticket Number, Violation Date, Fine Amount (₱), and Driver Liability.

---

# 12. Driver Behavior, Incident Lifecycle & Accident SOS (`/driver-behavior`)

### 12.1 Logging an Incident (`/driver-behavior/incidents`)
1. Navigate to **Driver Behavior** > Click **"+ Log Incident"**.
2. Select **Driver** and **Vehicle Unit**.
3. Select **Classification:** `Minor Customer Complaint`, `Reckless Driving`, `Route Violation`, `Boundary Evasion`, `Physical Damage`, `Major Collision`.
4. Enter **Incident Date & Time**, **Location**, and **Full Narrative**.
5. Upload **Evidence Files:** Scene Photos, Dashcam Footage, Police Blotter Report, Third-Party Statements.
6. Click **"Save Incident"**.

### 12.2 Handling Emergency SOS & Accident Alerts (`/driver-behavior/accidents`)
```
 ┌────────────────────────────────────────────────────────────────────────┐
 │                      🚨 EMERGENCY SOS DISPATCH ALERT                    │
 ├────────────────────────────────────────────────────────────────────────┤
 │ Taxi Unit: TX-104 (ABC-1234)        Driver: Juan Dela Cruz             │
 │ GPS Coordinates: 14.5995° N, 120.9842° E (EDSA corner Shaw Blvd)       │
 │ Trigger Time: 10:42:15 AM           Status: UNRESOLVED / ACTIVE        │
 ├────────────────────────────────────────────────────────────────────────┤
 │ [ 🔊 CHIME BROADCASTING ]   [ ACKNOWLEDGE ALERT ]   [ DISPATCH RESCUE ]│
 └────────────────────────────────────────────────────────────────────────┘
```
1. **Real-time Audio Chime:** Plays an urgent alert tone on all dispatcher screens.
2. **Acknowledge (`/accident-alerts/{id}/acknowledge`):** Dispatcher clicks acknowledge to log receipt.
3. **Dispatch Rescue:** Dispatches nearest mechanic or towing service.
4. **Accident Damage Estimation:** Enter repair estimate, affected body panels, and insurance claims.

### 12.3 Driver Incentive Program (`/driver-behavior/incentives`)
- Tracks driver **Safe Driving Points (0-100)**.
- **Release Monthly Incentive (`/driver-behavior/release-incentive`):** Grants bonus cash payouts to drivers with zero complaints and clean records.

---

# 13. Unit Profitability & AI Decision Support System (AI-DSS) (`/unit-profitability`)

### 13.1 Profitability Ledger & Calculation
$$\text{Net Yield} = \text{Total Boundary Remitted} - (\text{Maintenance Cost} + \text{Spare Parts} + \text{Franchise Fees} + \text{Operational Expenses})$$

- View real-time table of all fleet vehicles sorted by Net Yield, ROI %, and Cost per Kilometer.
- **CSV Export:** Download full financial data for accounting audits.

### 13.2 AI Decision Support System (AI-DSS) (`/unit-profitability/ai-dss`)
Powered by Google Gemini AI, the AI-DSS analyzes 90-day operational telemetry to generate executive intelligence:
- **Underperforming / "Lemon" Units:** Flags vehicles where recurring repairs exceed boundary collections.
- **Replacement Recommendations:** Recommends optimal retirement or resale timing before vehicle value depreciates past economic viability.
- **Preventive Maintenance Optimization:** Identifies driving pattern stresses and suggests component replacements before roadside breakdowns occur.

---

# 14. Office Expenses, Utility Bills & Approval Pipeline (`/office-expenses`)

### 14.1 Step-by-Step: Recording an Electric Bill or Utility Expense
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

### 14.2 Multi-Tier Approval Workflow
- Newly logged expenses enter `Pending Approval` status.
- Fleet Managers or Owners review the receipt attachment and click:
  - ✅ **"Approve"** (`/office-expenses/approve/{id}`) — Credits expense to company financial books.
  - ❌ **"Reject"** (`/office-expenses/reject/{id}`) — Rejects with required rejection reason notes.

---

# 15. Staff Records & Payroll / Salary Management (`/salary` & `/staff`)

### 15.1 Managing Office Staff (`/staff`)
- Maintain profiles for Dispatchers, Cashiers, Mechanics, Admin Officers, and Drivers.
- Store base monthly salary rates, daily rates, SSS/PhilHealth/Pag-IBIG details.

### 15.2 Payroll Computation & Payslip Generation (`/salary`)
1. Navigate to **Salary Management** > Click **"+ Compute Salary"**.
2. Select Staff Member and Pay Period (e.g., *1st Cut-off: 1st to 15th of the Month*).
3. System automatically calculates:
   - **Gross Earnings:** Base Pay + Overtime Hours + Performance Incentives.
   - **Deductions:** SSS + PhilHealth + Pag-IBIG + Cash Advance (Vale) Repayment + Late Penalties.
   - **Net Take-Home Pay.**
4. Click **"Save & Generate Payslip"** (`/salary/report`). Print or export PDF payslips with one click.

---

# 16. Franchise Case & LTFRB Compliance Management (`/franchise`)

- Track LTFRB Certificates of Public Convenience (CPC), franchise validity dates, and plate pairings.
- **Automated Expiry Alerts:** Displays color-coded warnings:
  - 🟡 **Yellow Alert:** 60 Days before expiration.
  - 🟠 **Orange Alert:** 30 Days before expiration.
  - 🔴 **Red Alert:** 7 Days before expiration (Urgent renewal required).
- Maintain case hearing dates, extension petitions, and inspection certificates.

---

# 17. Support Center, Internal Staff Chat & Announcements (`/support-center`, `/chat`, `/announcements`)

### 17.1 Support Center Helpdesk (`/support-center`)
- Centralized ticket inbox for driver inquiries, meter discrepancies, and roadside assistance requests.
- Dispatchers can reply in real time, view uploaded driver photos, and click **"Resolve Ticket"**.

### 17.2 Internal Staff Messaging (`/chat`)
- Secure 1-on-1 and department group messaging for office personnel.
- Real-time typing indicators, emoji reactions, photo attachments, and unread notification badges.

### 17.3 Company Announcements & Broadcasts (`/announcements`)
1. Click **"+ New Announcement"**.
2. Enter **Title**, **Body Text**, and **Target Audience** (`All Staff`, `Drivers Only`, `Office Only`).
3. **Pin to Top (`/announcements/{id}/toggle-pin`):** Keeps critical notices (e.g., *Holiday Boundary Advisory*) pinned at the top of the mobile feed.
4. Click **"Publish Announcement"**. Automatically pushes notification to all driver devices.

---

# 18. Super Admin Control Center & Enterprise Security (`/super-admin`)

*Strictly restricted to System Owners and Super Administrators.*

### 18.1 Sub-Tabs in Super Admin Control Center
1. **Overview Tab:** System KPIs (Total Staff, Active Accounts, Rejected Accounts) and Live Recent Login Activity table.
2. **Create Staff Tab:** Form to provision new personnel accounts (Full Name, Username, Email, Role, Initial Password, Phone).
3. **All Users Tab:** Master table of all accounts with Quick Actions (Toggle Disable/Enable, Reset Password, Edit Profile, Change Role, Archive Account).
4. **Page Access Tab:** Granular permission matrix allowing owners to toggle individual screen access chips for each user.
5. **Login History Tab:** Full security audit trail recording User ID, Action (`Login`, `Logout`, `Failed Login`), IP Address, Browser User-Agent, and Timestamp.
6. **System Security Tab:** Configure Master Archive Password.
7. **Client Activity Tab:** Live user heartbeat and active page tracking.

### 18.2 User Approval & Instant Disable Switch
- **Approve Account:** Click **"Approve"** on pending registrations to grant login access.
- **Toggle Disable (`/super-admin/toggle-disable/{id}`):** Instantly terminates active user sessions and blocks access for resigned or suspended employees.

---

# 19. Archive Vault & Disaster Recovery Management (`/archive`)

### 19.1 Soft-Delete Architecture
- To prevent accidental catastrophic data loss, deleting any Unit, Driver, Maintenance Record, Expense, Spare Part, or Staff moves the item to the **Archive Vault** instead of permanently deleting it from the database.

### 19.2 Restoring Records (`/archive/restore/{type}/{id}`)
1. Navigate to **Archive Vault** (`/archive`).
2. Filter by Category: `Units`, `Drivers`, `Maintenance`, `Expenses`, `Spare Parts`, `Users`.
3. Locate record > Click **"Restore"**.
4. The item is instantly restored to its active operational table with all relationships intact.

### 19.3 Permanent Purge (`/archive/force-delete/{type}/{id}`)
1. In the Archive Vault, click **"Permanent Delete"**.
2. System displays a high-risk confirmation modal.
3. Enter the **Master Archive Password**.
4. Click **"Confirm Permanent Purge"**. The record is permanently removed from the database.

---

# 20. Mobile Driver Application: Step-by-Step Operator Guide

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

# 21. Server Optimization, CDN Settings & Troubleshooting FAQ

### 21.1 Hostinger CDN & Image Optimization Settings
To ensure high-speed loading across mobile networks:
1. **WebP Image Compression:** **Keep ON** — Converts all uploaded receipts and vehicle photos to WebP format, reducing data usage by ~35%.
2. **Smart Image Optimisation:** **Keep ON** — Resizes camera uploads automatically (Desktop: 1600px, Mobile: 800px).
3. **Development Mode:** **Keep OFF during normal operations** — Turn ON only when uploading and testing new code files to bypass cache.

### 21.2 Database Synchronization Route (`/force-sync-db-2026`)
- In the event of schema updates or server cache staleness, authorized Super Admins can visit `https://eurotaxisystem.site/force-sync-db-2026` to run automated migrations, flush route/view cache, and re-index database tables.

### 21.3 Frequently Asked Questions & Operational Troubleshooting

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
