<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Euro Taxi System</title>
    <meta http-equiv="refresh" content="0; url=<?php echo e(auth()->check() ? route('dashboard') : route('login')); ?>">
</head>
<body>
    <p>Redirecting... <a href="<?php echo e(auth()->check() ? route('dashboard') : route('login')); ?>">Click here if not redirected.</a></p>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\eurotaxisystem\resources\views\welcome.blade.php ENDPATH**/ ?>