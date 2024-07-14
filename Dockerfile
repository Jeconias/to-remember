FROM php:8.3-alpine3.20 AS builder

WORKDIR /app

RUN apk update && \ 
    apk upgrade && \
    apk add composer && \
    apk add postgresql-dev

COPY ./config/php.ini /etc/php83/php.ini
COPY ./composer.json ./composer.lock .
COPY ./src ./src

RUN composer install --no-dev --no-scripts && \
    composer dump-autoload --optimize --no-scripts && \
    touch .db.cache

EXPOSE 1717

# Only for development 
CMD ["php", "-S", "0.0.0.0:1717", "src/index.php"]
