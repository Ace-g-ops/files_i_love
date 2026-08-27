cat > Dockerfile << 'EOF'
FROM php:8.2-fpm

# --- System dependencies ---
# libreoffice: doc/pdf conversion | ffmpeg: media conversion
# python3 + venv: pdf2docx script | nginx: serves the app
RUN apt-get update && apt-get install -y \
    nginx \
    libreoffice \
    ffmpeg \
    python3 \
    python3-venv \
    python3-pip \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# --- PHP extensions Laravel needs ---
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# --- Composer ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# --- App code ---
COPY . .

# --- PHP dependencies (no dev deps, optimized autoloader) ---
RUN composer install --no-dev --optimize-autoloader --no-interaction

# --- Python venv for the pdf2docx script ---
RUN python3 -m venv venv && \
    venv/bin/pip install --no-cache-dir pdf2docx

# --- Laravel storage/cache permissions ---
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- nginx config ---
COPY docker/nginx.conf /etc/nginx/sites-available/default

# --- Entrypoint (runs migrations, caches config, starts php-fpm + nginx) ---
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
EOF