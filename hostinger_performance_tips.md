# Hostinger Performance Optimization Tips

## 🚀 Immediate Optimizations

### 1. Enable Caching
```bash
# Run these on Hostinger (if SSH available)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Set Proper File Permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

### 3. Enable Gzip Compression
Add to `.htaccess`:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

### 4. Enable Browser Caching
Add to `.htaccess`:
```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/ico "access plus 1 month"
    ExpiresByType image/icon "access plus 1 month"
    ExpiresByType text/html "access plus 600 seconds"
</IfModule>
```

## 🗄️ Database Optimization

### 1. Add Indexes
```sql
-- Add indexes to frequently queried columns
CREATE INDEX idx_units_plate_number ON units(plate_number);
CREATE INDEX idx_units_driver_id ON units(driver_id);
CREATE INDEX idx_boundaries_date ON boundaries(date);
CREATE INDEX idx_maintenance_unit_id ON maintenance(unit_id);
CREATE INDEX idx_drivers_status ON drivers(driver_status);
```

### 2. Optimize Queries
- Use `whereNull('table.deleted_at')` instead of just `whereNull('deleted_at')`
- Add `limit()` to large queries
- Use eager loading to prevent N+1 problems

## 📱 Frontend Optimization

### 1. Minify Assets
```bash
# In production .env
APP_ENV=production
APP_DEBUG=false

# Run optimization
npm run build
```

### 2. Lazy Loading
Add to images:
```html
<img loading="lazy" src="...">
```

### 3. Optimize Images
- Use WebP format
- Compress images
- Use responsive images

## 🔧 Laravel Specific Optimizations

### 1. Queue System (if available)
```bash
# Install queue worker
php artisan queue:work --daemon
```

### 2. Database Connection Pooling
In `config/database.php`:
```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    // Add these for better performance
    'options' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode=STRICT_TRANS_TABLES',
    ],
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
],
```

### 3. Enable Opcache (if available)
In `.htaccess`:
```apache
<IfModule mod_php.c>
    php_value opcache.enable 1
    php_value opcache.memory_consumption 128
    php_value opcache.max_accelerated_files 4000
    php_value opcache.revalidate_freq 60
</IfModule>
```

## 📊 Monitoring

### 1. Enable Laravel Telescope (if available)
```bash
composer require laravel/telescope
php artisan telescope:install
```

### 2. Monitor Database Queries
Add to `AppServiceProvider.php`:
```php
if (app()->environment('local')) {
    DB::listen(function ($query) {
        Log::info($query->sql, $query->bindings);
    });
}
```

## 🔍 Performance Testing

### 1. Test Load Times
- Use Google PageSpeed Insights
- Test with GTmetrix
- Monitor with Hostinger analytics

### 2. Database Query Analysis
```php
// Enable query logging
DB::enableQueryLog();
// Your queries here
$queries = DB::getQueryLog();
dd($queries);
```

## 🚨 Common Issues & Solutions

### Issue: Slow Page Loads
**Solution:** Enable caching, optimize images, add database indexes

### Issue: Database Timeouts
**Solution:** Optimize queries, increase timeout, check indexes

### Issue: Memory Limits
**Solution:** Increase PHP memory limit in `.htaccess`:
```apache
php_value memory_limit 256M
```

### Issue: File Upload Issues
**Solution:** Check upload limits in `.htaccess`:
```apache
php_value upload_max_filesize 64M
php_value post_max_size 64M
```

## 📈 Maintenance Tasks

### Weekly:
- Clear caches
- Check logs for errors
- Monitor performance

### Monthly:
- Update dependencies
- Backup database
- Review analytics

### Quarterly:
- Security updates
- Performance audit
- Database optimization
