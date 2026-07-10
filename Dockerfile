FROM php:8.4-fpm-alpine

# Instalar dependencias
RUN apk add --no-cache nginx curl sqlite-dev sqlite-libs \
    && docker-php-ext-install pdo_sqlite

# Configurar PHP-FPM para escuchar en TCP (no socket)
RUN echo "[www]" > /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "listen = 127.0.0.1:9000" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm = dynamic" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.max_children = 5" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.start_servers = 2" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.min_spare_servers = 1" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.max_spare_servers = 3" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Crear directorio para base de datos
RUN mkdir -p /var/www/data && chmod -R 777 /var/www/data

# Copiar archivos
COPY . /usr/share/nginx/html
COPY nginx.conf /etc/nginx/http.d/default.conf

# Permisos
RUN chmod -R 777 /usr/share/nginx/html

EXPOSE 80

# Iniciar ambos servicios
CMD php-fpm -D && nginx -g 'daemon off;'