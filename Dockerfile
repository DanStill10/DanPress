# =============================================================================
# Stage 1: Build (installs dependencies & compiles theme assets)
# =============================================================================
FROM php:8.2-apache AS build

# Install Node.js 20 and Yarn
RUN apt-get update && apt-get install -y ca-certificates curl gnupg unzip \
    && mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g yarn \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Download WordPress core (gitignored files won't be in the build context)
RUN curl -fsSL https://wordpress.org/latest.tar.gz -o /tmp/wordpress.tar.gz \
    && tar -xzf /tmp/wordpress.tar.gz -C /tmp \
    && cp -a /tmp/wordpress/* /var/www/html/ \
    && rm -rf /tmp/wordpress /tmp/wordpress.tar.gz

COPY . .

# Install WordPress plugins (downloaded from WordPress.org at build time)
RUN mkdir -p wp-content/plugins \
    && curl -fsSL -o /tmp/wpforms.zip https://downloads.wordpress.org/plugin/wpforms-lite.zip \
    && unzip -qo /tmp/wpforms.zip -d wp-content/plugins && rm /tmp/wpforms.zip \
    && curl -fsSL -o /tmp/updraftplus.zip https://downloads.wordpress.org/plugin/updraftplus.zip \
    && unzip -qo /tmp/updraftplus.zip -d wp-content/plugins && rm /tmp/updraftplus.zip \
    && curl -fsSL -o /tmp/acf.zip https://downloads.wordpress.org/plugin/advanced-custom-fields.zip \
    && unzip -qo /tmp/acf.zip -d wp-content/plugins && rm /tmp/acf.zip \
    && curl -fsSL -o /tmp/ai1wm.zip https://downloads.wordpress.org/plugin/all-in-one-wp-migration.zip \
    && unzip -qo /tmp/ai1wm.zip -d wp-content/plugins && rm /tmp/ai1wm.zip \
    && curl -fsSL -o /tmp/akismet.zip https://downloads.wordpress.org/plugin/akismet.zip \
    && unzip -qo /tmp/akismet.zip -d wp-content/plugins && rm /tmp/akismet.zip

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
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Grant explicit directory permissions for /var/www/html
RUN printf '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' >> /etc/apache2/apache2.conf

WORKDIR ${APACHE_DOCUMENT_ROOT}

# Copy built application from the build stage
COPY --from=build ${APACHE_DOCUMENT_ROOT} ${APACHE_DOCUMENT_ROOT}

# Set proper permissions
RUN chown -R www-data:www-data ${APACHE_DOCUMENT_ROOT}

# Default port (Railway overrides this at runtime)
ENV PORT=8080

# Startup fix: disable conflicting MPMs at container start (Railway re-enables them at runtime)
CMD ["bash", "-c", "\
  set -eux; \
  a2dismod mpm_event mpm_worker || true; \
  rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* || true; \
  a2enmod mpm_prefork; \
  sed -i \"s/Listen .*/Listen ${PORT}/\" /etc/apache2/ports.conf; \
  sed -i \"s/<VirtualHost _default_:80>/<VirtualHost *:${PORT}>/\" /etc/apache2/sites-available/000-default.conf; \
  apache2ctl -t; \
  exec apache2-foreground \
"]

EXPOSE 8080
