# -------- Build PHP dependencies --------
FROM composer:2.7 AS composer_deps
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-interaction

# -------- Build front-end assets --------
FROM node:18-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm install --no-audit --no-fund --loglevel=error
# Copy only assets needed for the CSS build to keep the layer small
COPY resources/less ./resources/less
COPY resources/js ./resources/js
COPY build.js ./build.js
RUN npm run build:css

# -------- Runtime image --------
FROM php:8.1-cli-alpine

# Install minimal extensions required by Slim + PDO SQLite (adjust as needed)
RUN apk add --no-cache \ 
    sqlite-libs

WORKDIR /var/www

# Copy PHP application source
COPY . .
# Copy vendor dependencies from composer stage
COPY --from=composer_deps /app/vendor ./vendor
# Copy pre-built CSS assets
COPY --from=assets /app/public/css ./public/css

# Environment and server settings
ENV PORT=8080
EXPOSE 8080

# Start Slim via PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
