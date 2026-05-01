# Hostinger Database Setup Guide

## Method 1: phpMyAdmin Import (Recommended)

### Export from Local:
1. Open XAMPP phpMyAdmin
2. Select your database
3. Click "Export" → "Custom" → "SQL"
4. Download the .sql file

### Import to Hostinger:
1. Hostinger hPanel → MySQL Databases → phpMyAdmin
2. Select your database
3. Click "Import" → Choose file
4. Upload your .sql file
5. Wait for completion

## Method 2: Run Migrations on Hostinger

### Prerequisites:
- SSH access to Hostinger
- Composer installed on server

### Steps:
```bash
# SSH into Hostinger
ssh username@your-domain.com

# Navigate to project folder
cd public_html/your-project

# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database (if you have seeders)
php artisan db:seed
```

## Method 3: Manual SQL Execution

### Create Tables Manually:
1. Get SQL from your migration files in:
   `database/migrations/`

2. Execute each migration SQL in Hostinger phpMyAdmin

3. Common tables to create:
```sql
-- Users table
CREATE TABLE users (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    email_verified_at timestamp NULL DEFAULT NULL,
    password varchar(255) NOT NULL,
    remember_token varchar(100) DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    deleted_at timestamp NULL DEFAULT NULL,
    created_by bigint(20) UNSIGNED DEFAULT NULL,
    updated_by bigint(20) UNSIGNED DEFAULT NULL
);

-- Units table
CREATE TABLE units (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    plate_number varchar(255) NOT NULL UNIQUE,
    make varchar(255) DEFAULT NULL,
    model varchar(255) DEFAULT NULL,
    year int(11) DEFAULT NULL,
    motor_no varchar(255) DEFAULT NULL,
    chassis_no varchar(255) DEFAULT NULL,
    status enum('active','inactive','maintenance','vacant') DEFAULT 'active',
    driver_id bigint(20) UNSIGNED DEFAULT NULL,
    secondary_driver_id bigint(20) UNSIGNED DEFAULT NULL,
    boundary_rate decimal(10,2) DEFAULT 0.00,
    purchase_date date DEFAULT NULL,
    purchase_cost decimal(15,2) DEFAULT 0.00,
    roi_achieved tinyint(1) DEFAULT 0,
    unit_type enum('new','old','rented') DEFAULT 'new',
    coding_day enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') DEFAULT NULL,
    is_coding_exempt tinyint(1) DEFAULT 0,
    max_drivers int(11) DEFAULT 1,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    deleted_at timestamp NULL DEFAULT NULL,
    created_by bigint(20) UNSIGNED DEFAULT NULL,
    updated_by bigint(20) UNSIGNED DEFAULT NULL
);

-- Add other tables as needed...
```

## Verification Steps:
1. Check if all tables exist
2. Verify foreign key relationships
3. Test application connection
4. Check for any SQL errors
