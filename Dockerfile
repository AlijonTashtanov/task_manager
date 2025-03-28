# 1. PHP 8.2 Alpine bazasi
FROM php:8.2-fpm-alpine

# 2. Ishchi katalog yaratish
WORKDIR /var/www

# 3. Laravel uchun kerakli PHP kengaytmalarini o‘rnatish
RUN apk add --no-cache \
    zip unzip curl git \
    && docker-php-ext-install pdo pdo_mysql

# 4. Composerni yuklash va o‘rnatish
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Laravel loyihasi fayllarini nusxalash
COPY . .

# 6. Composer install (vendor papkasini yaratish)
RUN composer install --no-dev --optimize-autoloader

# 7. Laravel uchun huquqlarni sozlash
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 8. Konteyner ishga tushganda bajariladigan buyruq
CMD ["php-fpm"]
