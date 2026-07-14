# =============================================================================
# Stage 1: Build (installs dependencies & compiles theme assets)
# =============================================================================
FROM php:8.2-apache AS build

# Install Node.js 20 and Yarn
RUN apt-get update && apt-get install -y ca-certificates curl gnupg \
    && mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g yarn \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install theme Composer dependencies (autoloader)
WORKDIR /var/www/html/wp-content/themes/dan-press-components
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Install theme Node dependencies and build assets
RUN yarn install --frozen-lockfile
RUN yarn build
RUN npx bud build

# Clean up build-only artifacts from the final image
RUN rm -rf node_modules .budfiles .cache

# =============================================================================
# Stage 2: Production (PHP + Apache with built assets)
# =============================================================================
FROM php:8.2-apache

# System dependencies for PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libmagickwand-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install mysqli intl opcache
RUN pecl install imagick && docker-php-ext-enable imagick

# Apache: enable mod_rewrite for WordPress permalinks
RUN a2enmod rewrite

# Allow WordPress .htaccess rewrite rules to take effect
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

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

# Set document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR ${APACHE_DOCUMENT_ROOT}

# Copy built application from the build stage
COPY --from=build ${APACHE_DOCUMENT_ROOT} ${APACHE_DOCUMENT_ROOT}

# Set proper permissions
RUN chown -R www-data:www-data ${APACHE_DOCUMENT_ROOT}

# Default port (Railway overrides this at runtime)
ENV PORT=80

# Startup fix: disable conflicting MPMs at container start (Railway re-enables them at runtime)
CMD ["bash", "-c", "\
  set -eux; \
  a2dismod mpm_event mpm_worker || true; \
  rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* || true; \
  a2enmod mpm_prefork; \
  sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf; \
  sed -i 's/:80/:${PORT}/' /etc/apache2/sites-available/000-default.conf; \
  apache2ctl -t; \
  exec apache2-foreground \
"]

EXPOSE 80
