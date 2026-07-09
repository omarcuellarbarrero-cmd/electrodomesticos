FROM php:8.4-fpm-alpine

# Instalar dependencias (incluyendo sqlite-libs que a veces falta)
RUN apk add --no-cache nginx curl sqlite-dev sqlite-libs \
    && docker-php-ext-install pdo_sqlite

# Crear directorio seguro para la base de datos
RUN mkdir -p /var/www/data && chmod -R 777 /var/www/data

# Copiar archivos
COPY . /usr/share/nginx/html
COPY nginx.conf /etc/nginx/http.d/default.conf

# Permisos de escritura para la app
RUN chmod -R 777 /usr/share/nginx/html

# Crear un script de inicio para asegurar que ambos servicios corran
RUN echo '#!/bin/sh' > /start.sh && \
    echo 'php-fpm -D' >> /start.sh && \
    echo 'sleep 2' >> /start.sh && \
    echo 'nginx -g "daemon off;"' >> /start.sh && \
    chmod +x /start.sh

EXPOSE 80

# Usar el script de inicio
CMD ["/start.sh"]