#!/bin/bash
set -e

# Default to 80 if PORT is not set (Render injects PORT=10000 dynamically)
PORT="${PORT:-80}"
echo "==> Configuring Apache for Render Web Service on port: ${PORT}"

# Replace Apache Listen port and VirtualHost port dynamically
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/*.conf

# Set Apache DocumentRoot to /var/www/html/HMS-2 so endpoints work cleanly from root
sed -i "s|DocumentRoot /var/www/html|DocumentRoot /var/www/html/HMS-2|g" /etc/apache2/sites-available/000-default.conf

# Add Alias for /HMS-2 to support backwards-compatible links
if ! grep -q "Alias /HMS-2" /etc/apache2/sites-available/000-default.conf; then
    sed -i '/DocumentRoot/a \    Alias /HMS-2 /var/www/html/HMS-2' /etc/apache2/sites-available/000-default.conf
fi

# Ensure correct Directory permissions for Apache
if ! grep -q "<Directory /var/www/html/HMS-2>" /etc/apache2/sites-available/000-default.conf; then
    cat <<EOF >> /etc/apache2/sites-available/000-default.conf

<Directory /var/www/html/HMS-2>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
EOF
fi

# Execute main process (apache2-foreground)
exec "$@"
