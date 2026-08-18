#!/bin/bash
set -e

# Configurar el puerto de Apache según la variable de entorno $PORT (Render suele usar 10000 o 80)
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf 2>/dev/null || true
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf 2>/dev/null || true

# Asegurar permisos en carpetas de almacenamiento y caché
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Crear enlace simbólico de storage si no existe
php artisan storage:link --force || true

# Optimizaciones de caché de Laravel en producción
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizando configuraciones y rutas de Laravel para producción..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Ejecutar el comando principal (iniciar Apache en primer plano)
exec "$@"
