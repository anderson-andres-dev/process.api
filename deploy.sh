#!/bin/bash

# Navegar al directorio del repositorio
cd ~/public_html/process.api || exit

# Actualizar el código con git pull
git pull origin main

# Instalar dependencias de Composer si es necesario
if [ -f composer.json ]; then
  composer install --no-dev --optimize-autoloader
fi

# Limpiar caché si usa Slim Framework
rm -rf var/cache/*

# Reiniciar procesos si es necesario
# touch storage/restart.txt
