# Используем PHP 8.3 с FPM
FROM php:8.3-fpm

# Устанавливаем расширения PHP
RUN apt-get update && apt-get install -y \
    libpq-dev libpng-dev libjpeg-dev libfreetype6-dev zip unzip git \
    && docker-php-ext-install pdo pdo_mysql gd

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Указываем рабочую директорию
WORKDIR /var/www/html

# Копируем код Laravel внутрь контейнера
COPY . /var/www/html

# Даем права на запись
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Устанавливаем зависимости и чистим кеш
RUN composer install --no-dev --optimize-autoloader

# Запускаем PHP-FPM
CMD ["php-fpm"]
