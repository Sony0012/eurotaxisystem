USE laravel; UPDATE users SET password='', is_active=1 WHERE email='admin@eurotaxisystem.com'; SELECT id,email,password,is_active FROM users WHERE email='admin@eurotaxisystem.com';
