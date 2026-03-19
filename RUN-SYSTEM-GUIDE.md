# 🚀 Eurotaxi System - Complete Setup & Run Guide

## 📋 **Prerequisites**

### **Required Software:**
- **XAMPP** (Apache + MySQL + PHP)
- **PHP 7.4+** (comes with XAMPP)
- **Composer** (for Laravel dependencies)
- **Web Browser** (Chrome, Firefox, etc.)

### **Verify Installation:**
```bash
# Check PHP version
C:\xampp\php\php.exe -v

# Check if Laravel dependencies exist
dir c:\xampp\htdocs\Eurotaxisystem\vendor
```

## 🔧 **Step-by-Step Setup Procedure**

### **Step 1: Start XAMPP Services**
1. **Open XAMPP Control Panel**
2. **Start Apache** (green indicator)
3. **Start MySQL** (green indicator)
4. **Verify both services are running**

### **Step 2: Navigate to Project Directory**
```bash
cd c:\xampp\htdocs\Eurotaxisystem
```

### **Step 3: Clear All Laravel Caches**
```bash
# Clear application cache
C:\xampp\php\php.exe artisan cache:clear

# Clear configuration cache
C:\xampp\php\php.exe artisan config:clear

# Clear route cache
C:\xampp\php\php.exe artisan route:clear

# Clear view cache
C:\xampp\php\php.exe artisan view:clear
```

### **Step 4: Start Laravel Development Server**
```bash
# Start the server (run this command)
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000
```

**Expected Output:**
```
   INFO  Server running on [http://127.0.0.1:8000].  
   Press Ctrl+C to stop the server
```

### **Step 5: Access Your Application**
1. **Open Web Browser**
2. **Navigate to**: `http://127.0.0.1:8000`
3. **You should see**: Login page or dashboard

## 🌐 **Access Points**

### **Main Application:**
- **URL**: `http://127.0.0.1:8000`
- **Login**: `http://127.0.0.1:8000/login`
- **Dashboard**: `http://127.0.0.1:8000` (after login)

### **API Endpoints:**
- **Dashboard Data**: `http://127.0.0.1:8000/api/dashboard/realtime`
- **Revenue Trend**: `http://127.0.0.1:8000/api/revenue-trend`
- **Units Overview**: `http://127.0.0.1:8000/api/units-overview`

## 🔧 **Troubleshooting**

### **Common Issues & Solutions:**

#### **1. Port Already in Use**
```bash
# Try different port
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8001
```

#### **2. Database Connection Error**
- **Check XAMPP MySQL** is running
- **Verify .env file** database settings
- **Create database** if not exists

#### **3. 500 Server Error**
```bash
# Check Laravel logs
type storage\logs\laravel.log

# Clear caches again
C:\xampp\php\php.exe artisan optimize:clear
```

#### **4. Permissions Issues**
```bash
# Clear session files
del storage\framework\session\*

# Clear cache files
del storage\framework\cache\*
```

## 🚀 **Production Deployment**

### **For Production Use:**
```bash
# Optimize for production
C:\xampp\php\php.exe artisan optimize

# Generate app key (if missing)
C:\xampp\php\php.exe artisan key:generate

# Run database migrations
C:\xampp\php\php.exe artisan migrate

# Link storage (for file uploads)
C:\xampp\php\php.exe artisan storage:link
```

## 📱 **Mobile Access**

### **Access from Other Devices:**
1. **Find your IP address**: `ipconfig` in Command Prompt
2. **Use IP instead of localhost**: `http://YOUR_IP:8000`
3. **Ensure firewall allows port 8000**

## 🔒 **Security Notes**

### **For Production:**
- **Change APP_DEBUG** to `false` in `.env`
- **Set up HTTPS** with SSL certificate
- **Configure proper database credentials**
- **Enable CSRF protection** (already enabled)
- **Set up proper file permissions**

## 🎯 **Quick Start Commands**

### **One-Line Setup:**
```bash
cd c:\xampp\htdocs\Eurotaxisystem && C:\xampp\php\php.exe artisan optimize:clear && C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000
```

## 📊 **System Features**

### **What You Can Access:**
- **Dashboard** with real-time analytics
- **Units Overview** modal with detailed fleet data
- **Revenue Trend** charts with time periods
- **Unit Performance** analytics
- **Driver Management** system
- **Boundary Collection** tracking
- **Maintenance Records** management

## 🎉 **Success Indicators**

### **System is Working When:**
- ✅ **Server starts** without errors
- ✅ **Login page loads** at `http://127.0.0.1:8000`
- ✅ **Can login** with your credentials
- ✅ **Dashboard displays** with real data
- ✅ **Units Overview modal** opens and shows fleet data
- ✅ **Charts render** with your actual data

## 📞 **Need Help?**

### **Debug Commands:**
```bash
# Check Laravel version
C:\xampp\php\php.exe artisan --version

# Check routes
C:\xampp\php\php.exe artisan route:list

# Check configuration
C:\xampp\php\php.exe artisan config:cache
```

**Your Eurotaxi System is ready to run!** 🚀✨

Just follow the steps above and you'll have your fleet management system running in minutes!
