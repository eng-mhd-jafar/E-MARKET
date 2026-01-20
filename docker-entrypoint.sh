#!/bin/bash

# كاش للإعدادات والراوت لتحسين الأداء
php artisan config:cache
php artisan route:cache
php artisan view:cache

# تشغيل المايكريشن (لإنشاء الجداول في قاعدة البيانات)
# ملاحظة: استخدم --force لأننا في بيئة إنتاج
php artisan migrate --force

# تشغيل Apache
apache2-foreground