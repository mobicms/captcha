FROM alpine:3.22

RUN apk add --no-cache \
    nginx \
    php84 \
    php84-fpm \
    php84-gd \
    php84-session

RUN mkdir -p /var/www/html /run/nginx /run/php

COPY nginx.conf /etc/nginx/nginx.conf
COPY php-fpm.conf /etc/php84/php-fpm.d/www.conf

EXPOSE 80

CMD ["sh", "-c", "nginx && php-fpm84 -F"]
