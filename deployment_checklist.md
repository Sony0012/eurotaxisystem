# Hostinger Deployment Checklist

## Pre-Deployment Checklist:
- [ ] Backup current website files
- [ ] Export local database
- [ ] Get Hostinger database credentials
- [ ] Update .env file with database details

## File Upload Steps:
1. **Upload all project files** via File Manager or FTP
2. **Upload updated .env file** to Hostinger
3. **Verify file permissions** (755 for folders, 644 for files)

## Database Setup:
- [ ] Create database in Hostinger
- [ ] Create database user
- [ ] Grant user privileges
- [ ] Import database from local
- [ ] Verify all tables exist

## Post-Deployment Tests:
- [ ] Website loads without errors
- [ ] Login page works
- [ ] Database connection successful
- [ ] All pages load correctly
- [ ] Forms submit data properly

## Troubleshooting Common Issues:

### 1. "SQLSTATE[HY000] [2002] Connection refused"
**Solution:** Check database host and credentials in .env

### 2. "500 Internal Server Error"
**Solutions:**
- Check .env file permissions
- Verify database connection
- Check Laravel logs: storage/logs/laravel.log

### 3. "Database not found"
**Solution:** Run migrations or import database

### 4. "Permission denied"
**Solutions:**
- Set storage folder to 755
- Set .env file to 644
- Run: chmod -R 755 storage/

### 5. "App key not set"
**Solution:** Run `php artisan key:generate`

## Commands to Run on Hostinger (if SSH available):
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate app key
php artisan key:generate

# Set permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Run migrations (if needed)
php artisan migrate
```

## Final Verification:
1. Open your website URL
2. Try to login
3. Check if data displays correctly
4. Test adding new records
5. Verify all features work
