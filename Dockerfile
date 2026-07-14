FROM php:8.2-apache

# Install system dependencies for PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libmagickwand-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by WordPress
RUN docker-php-ext-install mysqli intl opcache

# Install Imagick via PECL
RUN pecl install imagick && docker-php-ext-enable imagick

# Enable Apache mod_rewrite for WordPress permalinks
RUN a2enmod rewrite

# Recommended PHP settings for WordPress
RUN { \
    echo 'upload_max_filesize = 64M'; \
    echo 'post_max_size = 64M'; \
    echo 'memory_limit = 256M'; \
    echo 'max_execution_time = 300'; \
    echo 'max_input_time = 300'; \
    echo 'allow_url_fopen = On'; \
    echo 'allow_url_include = Off'; \
} > /usr/local/etc/php/conf.d/wordpress-recommended.ini

# Set document root and copy WordPress files
ENV APACHE_DOCUMENT_ROOT=/var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR ${APACHE_DOCUMENT_ROOT}
COPY . ${APACHE_DOCUMENT_ROOT}

# Set proper permissions
RUN chown -R www-data:www-data ${APACHE_DOCUMENT_ROOT}

# Startup fix: disable conflicting MPMs at container start (Railway re-enables them at runtime)
CMD ["bash", "-c", "\
  set -eux; \
  a2dismod mpm_event mpm_worker || true; \
  rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* || true; \
  a2enmod mpm_prefork; \
  apache2ctl -t; \
  exec apache2-foreground \
"]

EXPOSE 80
