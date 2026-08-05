# PSS_Online - PHP 8 / Apache container image
FROM php:8.4-apache

# --- System packages needed by GD (barcodes/captchas), zip (PHPExcel/PCLZip), curl, etc ---
RUN apt-get update && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        libonig-dev \
        libcurl4-openssl-dev \
        libxml2-dev \
        unzip \
        default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# --- PHP extensions actually used by this codebase ---
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        gd \
        mysqli \
        curl \
        zip \
        mbstring \
        xml \
        opcache

# --- Apache config ---
RUN a2enmod rewrite headers
# App uses per-folder .htaccess (e.g. tcpdf/tools/.htaccess) so AllowOverride must be enabled.
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

# --- PHP runtime config tuned for this app (file uploads, Excel/PDF generation) ---
COPY docker/php.ini-overrides.ini /usr/local/etc/php/conf.d/zz-app-overrides.ini

WORKDIR /var/www/html

# App code is mounted as a volume in docker-compose for local dev; for a production image
# build, uncomment the line below to bake the code into the image instead.
# COPY . /var/www/html

EXPOSE 80
