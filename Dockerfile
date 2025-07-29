# Multi-stage build for optimized production image
# Stage 1: Build stage with dev dependencies
FROM node:18-alpine AS node-builder

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install Node.js dependencies
RUN npm ci --only=production --no-audit --no-fund

# Copy frontend assets
COPY resources/ ./resources/
COPY vite.config.js ./
COPY tailwind.config.js ./

# Build frontend assets
RUN npm run build:production

# Stage 2: PHP dependencies stage
FROM composer:latest AS php-dependencies

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (production only)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-suggest \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Stage 3: Production image
FROM php:8.2-fpm-alpine AS production

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite \
    sqlite-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_sqlite \
        pdo_mysql \
        zip \
        gd \
        mbstring \
        exif \
        pcntl \
        bcmath \
        opcache

# Install Redis extension
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis igbinary \
    && docker-php-ext-enable redis igbinary \
    && apk del $PHPIZE_DEPS

# Configure PHP for production
COPY docker/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Configure Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/site.conf /etc/nginx/http.d/default.conf

# Configure Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create application user
RUN addgroup -g 1000 -S app \
    && adduser -u 1000 -S app -G app

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --chown=app:app . .

# Copy built frontend assets from node-builder stage
COPY --from=node-builder --chown=app:app /app/public/build ./public/build

# Copy vendor dependencies from php-dependencies stage
COPY --from=php-dependencies --chown=app:app /app/vendor ./vendor

# Create necessary directories and set permissions
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R app:app storage bootstrap/cache \
    && chmod -R 755 storage bootstrap/cache

# Create SQLite database if it doesn't exist
RUN touch database/database.sqlite \
    && chown app:app database/database.sqlite \
    && chmod 664 database/database.sqlite

# Switch to app user
USER app

# Optimize Laravel for production
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan optimize

# Switch back to root for supervisor
USER root

# Expose port
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8080/health || exit 1

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]