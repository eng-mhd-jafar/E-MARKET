# استخدام صورة PHP مع Apache
FROM php:8.2-apache

# تثبيت المكتبات المطلوبة لـ Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    libonig-dev \
    libpng-dev

# تثبيت إضافات PHP الضرورية
RUN docker-php-ext-install pdo_mysql zip bcmath gd

# إعداد Apache لتوجيه الترافيك إلى مجلد public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# تفعيل Mod Rewrite الخاص بـ Laravel
RUN a2enmod rewrite

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تحديد مجلد العمل
WORKDIR /var/www/html

# نسخ ملفات المشروع
COPY . .

# تثبيت حزم Laravel (بدون حزم التطوير لتقليل الحجم)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# ضبط صلاحيات المجلدات (مهم جداً لتجنب أخطاء التخزين)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# فتح المنفذ 80
EXPOSE 80