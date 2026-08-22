# EURO TAXI MANAGEMENT SYSTEM (ETMS)
# Comprehensive Enterprise User & Operations Manual
**Document Version:** 4.0.0 (Complete Operations Edition)  
**System URL:** `https://eurotaxisystem.site`  
**Applicable Software:** ETMS Web Administration Portal & ETMS Mobile Driver Application  
**Languages Supported:** English  

---

# Table of Contents
1. [System Overview & Architecture](#1-system-overview--architecture)
2. [User Roles, Access Matrix & Security Hierarchy](#2-user-roles-access-matrix--security-hierarchy)
3. [Authentication, MFA, Account Setup & Security](#3-authentication-mfa-account-setup--security)
4. [Live Dashboard & Fleet Operations Nerve Center](#4-live-dashboard--fleet-operations-nerve-center)
5. [Fleet & Unit Management (Full Vehicle Lifecycle)](#5-fleet--unit-management-full-vehicle-lifecycle)
6. [Boundary Collection & Financial Remittance Management](#6-boundary-collection--financial-remittance-management)
7. [Driver Management, KYC Verification & Debt Ledger](#7-driver-management-kyc-verification--debt-ledger)
8. [Vehicle Maintenance & Workshop Bay Management](#8-vehicle-maintenance--workshop-bay-management)
9. [Inventory, Spare Parts & Supplier Management](#9-inventory-spare-parts--supplier-management)
10. [Live GPS Fleet Telematics & Remote Engine Immobilizer](#10-live-gps-fleet-telematics--remote-engine-immobilizer)
11. [Number Coding Management (UVVRP & Smart Rotation)](#11-number-coding-management-uvvrp--smart-rotation)
12. [Driver Behavior, Incident Lifecycle & Accident SOS](#12-driver-behavior-incident-lifecycle--accident-sos)
13. [Unit Profitability & AI Decision Support System (AI-DSS)](#13-unit-profitability--ai-decision-support-system-ai-dss)
14. [Office Expenses & Financial Approval Pipeline](#14-office-expenses--financial-approval-pipeline)
15. [Staff Records & Payroll / Salary Management](#15-staff-records--payroll--salary-management)
16. [Franchise Case & LTFRB Compliance Management](#16-franchise-case--ltfrb-compliance-management)
17. [Support Center, Internal Staff Chat & Announcements](#17-support-center-internal-staff-chat--announcements)
18. [Super Admin Control Center & Enterprise Security](#18-super-admin-control-center--enterprise-security)
19. [Archive Vault & Disaster Recovery Management](#19-archive-vault--disaster-recovery-management)
20. [Mobile Driver Application: Step-by-Step Operator Guide](#20-mobile-driver-application-step-by-step-operator-guide)
21. [Server Optimization, CDN Settings & Troubleshooting FAQ](#21-server-optimization-cdn-settings--troubleshooting-faq)

---

# 1. System Overview & Architecture

The **Euro Taxi Management System (ETMS)** is a centralized, cloud-hosted Enterprise Resource Planning (ERP) and Fleet Telematics ecosystem designed for taxi fleet operations.

```
 ┌────────────────────────────────────────────────────────────────────────┐
 │                   ETMS CLOUD SERVER (eurotaxisystem.site)              │
 ├───────────────────────────────────┬────────────────────────────────────┤
 │       WEB MANAGEMENT PORTAL       │        MOBILE DRIVER APP           │
 │  (Super Admin / Managers / Staff) │   (Android / PWA / Drivers)        │
 ├───────────────────────────────────┼────────────────────────────────────┤
 │ • Dashboard & Real-Time KPIs      │ • On-Duty Shift & Boundary Tracker │
 │ • Fleet Inventory & Health Scores │ • Live Balance & Debt Payments     │
 │ • Boundary Ledgers & Expenses     │ • 1-Tap Emergency SOS Alarm        │
 │ • Workshop Bay & Spare Parts      │ • Breakdown Rescue Request         │
 │ • Live GPS Map & Engine Cut-off   │ • Accident Self-Reporting (Photos) │
 │ • AI-DSS Profitability Analytics  │ • Support Ticket Chat Desk         │
 │ • Super Admin Security & Matrix   │ • Company Rules & Announcements    │
 └───────────────────────────────────┴────────────────────────────────────┘
```

---

# 2. User Roles, Access Matrix & Security Hierarchy

ETMS implements **Role-Based Access Control (RBAC)** coupled with a **Granular Page Permission Matrix**.

| Role | Default Capabilities | Primary Screens Accessed |
| :--- | :--- | :--- |
| **Super Admin / Owner** | Complete system authority. Account approvals, role creation, page-access customization, security archive master password, audit monitoring. | Super Admin Panel, Audit Trail, All Modules |
| **Fleet Manager / Admin** | Manages units, drivers, maintenance job orders, approves office expenses, analyzes AI profitability. | Dashboard, Units, Drivers, Maintenance, Profitability, Coding |
| **Dispatcher / Operations** | Monitors real-time GPS fleet positions, handles SOS emergencies, coordinates roadside rescue, manages coding rotations. | Live Tracking, Driver Behavior, SOS Alerts, Rescue, Coding, Support |
| **Cashier / Finance** | Collects daily boundary remittances, manages driver debt ledgers, logs operational expenses, processes salary payroll. | Boundaries, Driver Debts, Office Expenses, Salary, Profitability |
| **Mechanic / Bay Staff** | Performs vehicle repairs, tracks PMS schedules, logs parts used from inventory, updates unit health scores. | Maintenance, Spare Parts Inventory, Suppliers |
| **Taxi Driver** | Operates vehicle, remits boundary, tracks personal ledger, requests rescue, reports accidents. | Mobile Driver App |

---

# 3. Authentication, MFA, Account Setup & Security

### 3.1 User Registration (`/register`)
1. Open `https://eurotaxisystem.site/register`.
2. Complete the registration form:
   - **Full Name:** Complete legal name.
   - **Email Address:** Valid email address for security notifications.
   - **Mobile Number:** 11-digit Philippine mobile number (`09XXXXXXXXX`).
   - **Username:** Unique system handle.
   - **Password & Confirm Password:** Minimum 8 characters.
   - **Desired Role:** Admin, Dispatcher, Cashier, Mechanic, or Driver.
3. Click **"Submit Registration"**.
4. *Note:* Newly registered accounts are placed in `Pending Approval` status until activated by a Super Admin.

### 3.2 Login & Two-Factor Authentication (`/login`)
1. Enter registered **Email/Username** and **Password**.
2. Click **"Sign In"**.
3. **Multi-Factor Authentication (MFA / Device OTP):**
   - If logging in from an unrecognized browser or new device, ETMS generates a 6-digit One-Time PIN.
   - The OTP is sent via SMS (via Semaphore API) and Email.
   - Enter the 6-digit code in the verification modal within 5 minutes.
   - Upon verification, the device is whitelisted in `verified_browsers`.

### 3.3 Password Reset & Forgot Password Flow
1. On the login screen, click **"Forgot Password?"**.
2. Choose recovery channel: **SMS OTP** or **Email OTP**.
3. Enter registered contact detail > Click **"Send Verification Code"**.
4. Enter the received OTP code and set your new password.

### 3.4 User Profile & My Account (`/my-account`)
- **Update Profile:** Edit name, mobile number, and residential address.
- **Profile Photo:** Upload clear headshot (PNG/JPG, auto-converted to WebP).
- **Change Password:** Enter current password and new secure password.
- **Request Email Change:** Sends a secure token link to the new email address. Click the link to finalize the update.

---

# 4. Live Dashboard & Fleet Operations Nerve Center (`/`)

The Dashboard updates asynchronously in real time without requiring manual page reloads.

```
 ┌────────────────────────────────────────────────────────────────────────┐
 │                           DASHBOARD OVERVIEW                           │
 ├──────────────┬──────────────┬──────────────┬──────────────┬────────────┤
 │ TOTAL FLEET  │ ACTIVE ROAD  │ MAINTENANCE  │ CODING TODAY │  FLAGGED   │
 │     48       │      38      │      5       │      4       │     1      │
 ├──────────────┴──────────────┴──────────────┴──────────────┴────────────┤
 │ 💰 TODAY'S BOUNDARY COLLECTION: [=========>      ] ₱45,600 / ₱57,600   │
 │ 🚨 ACTIVE ALERTS: 1 Accident Report (TX-104) • 2 PMS Due Today        │
 ├──────────────────────────────────────┬─────────────────────────────────┤
 │     7-DAY NET REVENUE SPARKLINE      │    FLEET UTILIZATION GAUGES     │
 └──────────────────────────────────────┴─────────────────────────────────┘
```

### Dashboard Widgets & Actions:
1. **Fleet Counter Cards:** Instant counts of units in Active, Maintenance, Coding, and Flagged states. Clicking any card filters the vehicle table.
2. **Boundary Progress Meter:** Real-time gauge showing target collection vs. actual cash remitted for the current date.
3. **Emergency Alert Ticker:** High-priority banners for active driver SOS panic alerts, accident reports, and maintenance alerts.
4. **Quick Action Dock:**
   - **"Record Boundary"** — Opens instant collection remittance modal.
   - **"Add Maintenance"** — Opens new repair job order modal.
   - **"Flag Vehicle"** — Quickly places a vehicle on operational hold.
   - **"Broadcast Alert"** — Sends an immediate push notification to all active drivers.

---

# 5. Fleet & Unit Management (Full Vehicle Lifecycle) (`/units`)

### 5.1 Adding a New Vehicle Unit
1. Go to **Units** (`/units`) > Click **"+ Add New Unit"**.
2. Complete all vehicle fields:
   - **Plate Number:** (e.g., `ABC-1234`) — *Unique identifier*.
   - **Body Number / Taxi ID:** (e.g., `TX-101`) — *Fleet number*.
   - **Make & Model:** (e.g., `Toyota Vios 1.3 Dual VVT-i`).
   - **Year Model:** (e.g., `2023`).
   - **Chassis / VIN Number:** 17-digit chassis number.
   - **Engine Number:** Stamped engine code.
   - **Franchise / Case Number:** Registered LTFRB franchise reference.
   - **Coding Day:** Select Monday through Friday (or auto-assigned based on plate ending).
   - **GPS Tracker IMEI:** 15-digit device serial number for live telematics.
   - **Initial Status:** `Active`, `In Maintenance`, or `Standby`.
3. Click **"Save Vehicle Record"**.

### 5.2 Editing & Updating Unit Details
1. In the Units list, locate the target vehicle.
2. Click the **"Edit"** (Pencil) icon.
3. Modify plate details, tracker IMEI, or assigned driver.
4. Click **"Update Unit"**.

### 5.3 Vehicle Health Score & Reset Protocol
- **Health Score (0% to 100%):** Calculated based on:
  - Total mileage logged since last Periodic Maintenance Service (PMS).
  - Frequency of mechanical breakdown tickets.
  - Incident/accident involvement history.
- **Recover / Reset Health (`/units/{id}/reset-health`):**
  - Performed after a comprehensive engine overhaul, PMS 10,000 km service, or major restoration.
  - Resets the health counter back to 100% and marks the last service odometer reading.

### 5.4 Flagging & Impounding Units (`/units/flagged`)
1. Click **"Flag Unit"** on any vehicle row.
2. Select Flag Reason: `Unsettled Boundary Arrears`, `Police Apprehension / Impound`, `Suspected Mechanical Hazard`, or `Missing / Lost Signal`.
3. Enter detailed notes.
4. Click **"Apply Flag"**. The unit status turns `Flagged` and is locked from dispatch.

### 5.5 Printing Fleet Masterlist (`/units/print`)
- Click **"Print Fleet Inventory"** to generate a clean, official PDF report of all vehicles, franchise expirations, and operational statuses.

---

# 6. Boundary Collection & Financial Remittance Management (`/boundaries`)

### 6.1 Step-by-Step: Recording Daily Boundary Remittance
1. Navigate to **Boundaries** (`/boundaries`).
2. Click **"+ Record Collection"**.
3. **Select Taxi Unit / Body Number:** The assigned driver's name auto-populates.
4. **Select Date & Shift:** Choose `Day Shift (12h)`, `Night Shift (12h)`, or `24-Hour Shift`.
5. **Expected Rate:** Automatically computed by active **Boundary Rules** (e.g., Standard ₱1,200, Coding Discount ₱800).
6. **Enter Actual Amount Paid:**
   - **Case A: Exact Remittance (Paid ₱1,200):** Status marks as `Settled`.
   - **Case B: Shortage (Paid ₱1,000):** System records ₱1,000 collected and automatically writes a **₱200 Shortage Debt** to the driver's ledger.
   - **Case C: Overpayment (Paid ₱1,500):** System credits ₱300 to amortize existing driver debts or records an advance credit balance.
7. Click **"Save Collection & Issue Receipt"**.

### 6.2 Managing Boundary Rules (`/boundary-rules`)
1. Navigate to **Boundary Rules Settings**.
2. Click **"+ New Rule"**.
3. Configure parameters:
   - **Rule Name:** (e.g., `Weekend Rain Discount`, `Holiday 24H Rate`).
   - **Applicable Days:** Monday to Sunday.
   - **Shift Type:** 12-Hour vs. 24-Hour.
   - **Base Boundary Rate:** Currency amount.
   - **Coding Day Discount Amount:** Deduction applied when vehicle is restricted by coding.
4. Click **"Save Boundary Rule"**.

---

# 7. Driver Management, KYC Verification & Debt Ledger (`/driver-management`)

### 7.1 Registering a New Driver
1. Navigate to **Driver Management** (`/driver-management`) > Click **"+ Register Driver"**.
2. **Personal Information:** First Name, Last Name, Middle Name, Nickname, Date of Birth, Civil Status, Mobile Phone, Residential Address, Emergency Contact Name & Number.
3. **Professional Credentials:**
   - Professional Driver's License Number.
   - License Expiry Date.
   - SSS / PhilHealth / Pag-IBIG Numbers.
4. **Digital KYC Document Uploads:**
   - Driver's License (Front & Back scanned image).
   - NBI Clearance (Valid within 6 months).
   - Police Clearance & Barangay Clearance.
   - Medical Certificate & Drug Test Result.
5. Click **"Save Driver Profile"**.

### 7.2 Uploading Driver Terms & Contracts (`/driver-management/terms`)
1. Navigate to **Terms & Conditions** tab.
2. Click **"Upload Contract Document"** > Choose scanned agreement image or PDF.
3. Click **"Upload Term"**. The document is instantly synced to the Driver Mobile App where drivers must review and digitally sign.

### 7.3 Managing Driver Debts & Installment Deductions (`/driver-management/debts`)
- **Viewing Debts:** The Debt Ledger tracks accumulated boundary shortages, vehicle repair damage charges, and cash advances.
- **Recording Debt Repayment (`/driver-management/pay-debt`):**
  1. Click **"Pay Debt"** on the driver's row.
  2. Enter Payment Amount (e.g., ₱300).
  3. Select Payment Source: `Cash Remittance`, `Salary Deduction`, or `Incentive Offset`.
  4. Click **"Submit Payment"**. Debt balance updates immediately.

### 7.4 Driver Suspension & Banning Protocols
- **Temporary Suspension:** Select duration (1 to 30 days) and specify reason (e.g., *Customer Overcharging Complaint*). The driver app is temporarily locked.
- **Permanent Ban (`/driver-management/{id}/suspend-or-ban`):** Used for gross misconduct, boundary absconding, or DUI accidents. Banned drivers cannot be assigned to any vehicle.
- **Unbanning:** Super Admins and Managers can click **"Unban"** (`/driver-management/{id}/unban`) after formal clearance.

---

# 8. Vehicle Maintenance & Workshop Bay Management (`/maintenance`)

### 8.1 Step-by-Step: Adding a New Maintenance Job Order
1. Navigate to **Maintenance** (`/maintenance`).
2. Click **"+ New Job Order"** (or **"+ Record Maintenance"**).
3. **Select Vehicle Unit:** Choose Plate/Body Number (e.g., `TX-105 / ABC-5678`).
   - *Safety Lock:* The system blocks maintenance creation if the vehicle is currently flagged as `Missing / Stolen`.
4. **Select Driver:** (Optional) Assigned driver at the time of issue.
5. **Select Maintenance Type:**
   - `Preventive Maintenance Service (PMS)` (Oil change, tune-up, 5k/10k check).
   - `Mechanical Repair` (Brakes, clutch, transmission, suspension).
   - `Electrical Repair` (Alternator, battery, lights, wiring, GPS tracker).
   - `Tire & Wheel` (Puncture, replacement, alignment).
   - `Body & Paint / Accident Repair`.
   - `Engine Overhaul / Major Repair`.
6. **Odometer Reading:** Enter current physical speedometer mileage.
7. **Date Started:** Select repair start timestamp.
8. **Select Assigned Mechanic(s):** Multi-select accredited workshop staff.
9. **Spare Parts Used:**
   - Search spare part name from inventory.
   - Enter quantity (e.g., *Engine Oil 10W-40 x 4L*, *Brake Pads x 1 Set*).
   - The system automatically calculates parts cost and verifies warehouse stock.
10. **Labor & Other Charges:** Enter labor cost and external machining fees.
11. **Description & Dispatcher Notes:** Enter symptoms, driver complaint, and diagnosis notes.
12. **Initial Status:** Set to `Pending` or `In Progress`.
13. Click **"Save Maintenance Record"**.

### 8.2 Moving to Repair Bay (`/maintenance/{id}/toggle-in-progress`)
- Click **"Start Work / In Progress"**.
- The job order status changes to `In Progress` and the vehicle status turns `In Maintenance` across all dispatch screens.

### 8.3 Completing Job Orders & Resetting Vehicle Health (`/maintenance/{id}/toggle-complete`)
1. Click **"Mark Completed"** on the job order.
2. Enter **Date Completed** and final mechanic notes.
3. Click **"Confirm Completion"**.
4. **System Automated Actions:**
   - Deducts all specified spare parts from the warehouse inventory.
   - Adds total repair cost to the vehicle's financial expense ledger.
   - Resets the vehicle's health score to 100% (PMS complete).
   - Changes vehicle status back to `Active` (Roadworthy).

---

# 9. Inventory, Spare Parts & Supplier Management (`/inventory-management`)

### 9.1 Adding a Spare Part to Catalog (`/spare-parts`)
1. Go to **Inventory** (`/inventory-management`) > Click **"+ Add Spare Part"**.
2. Input fields:
   - **Part SKU / Item Code:** (e.g., `BP-TOY-01`).
   - **Part Name:** (e.g., `Front Brake Pad Set - Vios 2023`).
   - **Category:** `Engine`, `Braking`, `Suspension`, `Electrical`, `Lubricants`, `Tires`, `Body`.
   - **Purchase Cost (₱):** Warehouse buying price per unit.
   - **Charge Price (₱):** Standard price charged to job order or driver.
   - **Initial Quantity on Hand:** Stock count.
   - **Minimum Reorder Level:** Safety stock threshold (e.g., 5 units).
3. Click **"Save Spare Part"**.

### 9.2 Low Stock Warnings & Stock Adjustments
- When stock drops to or below the minimum reorder level, the item is highlighted with an orange **Low Stock Alert**.
- To restock, click **"Restock / Adjust Stock"**, enter quantity received and select supplier.

### 9.3 Supplier Directory (`/suppliers`)
- Add accredited automotive suppliers: Company Name, Contact Person, Phone, Email, Office Address, and Credit Terms.

---

# 10. Live GPS Fleet Telematics & Remote Engine Immobilizer (`/live-tracking`)

### 10.1 Real-Time Fleet Map
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

# 14. Office Expenses & Financial Approval Pipeline (`/office-expenses`)

### 14.1 Recording an Expense
1. Go to **Office Expenses** (`/office-expenses`) > Click **"+ Record Expense"**.
2. Complete fields:
   - **Category:** `Office Rent`, `Electricity / Water`, `Internet & Software`, `Fuel & Tolls`, `Permits & Taxes`, `Legal & Accounting`, `Marketing`, `Office Supplies`.
   - **Expense Title & Description:** Detailed justification.
   - **Amount (₱):** Numeric value.
   - **Payment Method:** `Cash`, `Bank Transfer`, `GCash`, `Check`.
   - **Official Receipt (OR) Attachment:** Upload clear receipt photo or invoice PDF.
3. Click **"Submit Expense"**.

### 14.2 Multi-Tier Approval Workflow
- Newly submitted expenses enter `Pending Approval` status.
- Authorized Managers or Owners review the receipt attachment and click:
  - ✅ **"Approve"** (`/office-expenses/approve/{id}`) — Deducts from office cash balance and credits to financial statements.
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

### 18.1 User Account Approvals & Account Control
- **Approve / Reject Queue:** Review self-registered accounts. Click **"Approve"** to grant system entry.
- **Instant Disable Switch (`/super-admin/toggle-disable/{id}`):** One-click toggle to immediately terminate active sessions and block login access for resigned or suspended staff.

### 18.2 Granular Page-Access Permission Matrix (`/super-admin/page-access/{id}`)
Super Admins configure checkbox matrices granting access to specific screens per user:
- `Dashboard`, `Units`, `Boundaries`, `Maintenance`, `Driver Management`, `Live Tracking`, `Coding`, `Driver Behavior`, `Office Expenses`, `Salary`, `Profitability`, `Spare Parts Inventory`, `Archive`.

### 18.3 Custom Role & Permission Manager (`/super-admin/roles`)
- Create new organizational roles (e.g., *Junior Cashier*, *Night Dispatcher*).
- Define read, write, update, and delete privileges per role.

### 18.4 Audit Trail & Login History (`/super-admin/login-history`)
- Immutable log recording: Timestamp, User ID, Full Name, Action (Create, Update, Delete, Export, Login, Logout), IP Address, Browser User-Agent, and Changed Data Payload.

### 18.5 Archive Security Password Setup (`/super-admin/security/update-archive-password`)
- Set the Master Archive Security Password required to permanently purge any record from the system.

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
*End of Complete User Manual. Euro Taxi Management System (ETMS) © 2026. All Rights Reserved.*
