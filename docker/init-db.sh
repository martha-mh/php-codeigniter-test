#!/bin/bash

# Script de inicialización de base de datos
# Se ejecuta automáticamente al iniciar el contenedor

echo "================================================"
echo "  Inicializando Base de Datos"
echo "================================================"

# Esperar a que MySQL esté listo
echo "Esperando a que MySQL esté disponible..."
sleep 10

# Intentar conectar hasta 30 veces (30 segundos)
max_attempts=30
attempt=0

while [ $attempt -lt $max_attempts ]; do
    if php /var/www/html/index.php migrate status > /dev/null 2>&1; then
        echo "MySQL está listo"
        break
    fi
    attempt=$((attempt + 1))
    echo "   Intento $attempt/$max_attempts..."
    sleep 1
done

if [ $attempt -eq $max_attempts ]; then
    echo "[ERROR] No se pudo conectar a MySQL después de $max_attempts intentos"
    exit 1
fi

# Verificar si las migraciones ya se ejecutaron
echo ""
echo "[INFO] Verificando estado de migraciones..."

if php /var/www/html/index.php migrate status | grep -q "Versión actual: 0"; then
    echo "[RUN] Ejecutando migraciones..."
    php /var/www/html/index.php migrate
    
    if [ $? -eq 0 ]; then
        echo ""
        echo "Base de datos inicializada correctamente"
        echo ""
    else
        echo ""
        echo "ERROR: Falló la inicialización de la base de datos"
        exit 1
    fi
else
    echo "[INFO] Las migraciones ya están aplicadas"
    php /var/www/html/index.php migrate status
fi

echo ""
echo "================================================"
echo "    Inicialización Completa"
echo "================================================"
echo ""
