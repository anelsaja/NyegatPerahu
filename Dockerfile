FROM php:7.3-apache

# 2. Instal ekstensi sistem yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libxml2-dev

# Bersihkan cache instalasi
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Instal ekstensi PHP 
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

# 4. Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# 5. Pindahkan direktori kerja ke dalam folder server
WORKDIR /var/www/html

# 6. Salin semua file dari laptopmu ke dalam server Docker
COPY . /var/www/html

# 7. Instal Composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# 8. Jalankan instalasi dependency Laravel
RUN composer install --no-interaction --no-dev --optimize-autoloader

# 9. Atur perizinan folder
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Ubah rute default Apache agar mengarah ke folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 11. Buka port 80 untuk web
EXPOSE 80