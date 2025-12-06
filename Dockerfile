FROM php:8.2-cli

WORKDIR /var/www/html

# Copiar todos tus archivos
COPY . .

# Render te da un PORT (ej: 10000)
ENV PORT=10000

# Iniciar solo un servidor web básico que muestra tu index.html
CMD php -S 0.0.0.0:${PORT} -t /var/www/html
