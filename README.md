# Sistema de Gestión de Tareas - CodeIgniter 3

Implementación completa de un CRUD de tareas con interfaz web y API REST para el ejercicio técnico PHP Developer 2025.

## Tabla de Contenidos

- [Requisitos](#requisitos)
- [Instalación y Configuración](#instalación-y-configuración)
  - [Opción 1: Con Docker (Recomendado)](#opción-1-con-docker-recomendado)
  - [Opción 2: Sin Docker (Manual)](#opción-2-sin-docker-instalación-manual)
- [URLs y Endpoints](#urls-y-endpoints)
- [Configuración](#configuración)
- [Características Implementadas](#características-implementadas)
- [Testing Manual](#testing-manual)
- [Sistema de Migraciones](#sistema-de-migraciones)
- [Solución de Problemas](#solución-de-problemas)
- [Notas de Desarrollo](#notas-de-desarrollo)
- [Análisis de Código](#análisis-de-código)
- [Cumplimiento del Ejercicio](#cumplimiento-del-ejercicio)

## Requisitos

- Docker & Docker Compose (recomendado)
- O manualmente: PHP 7.4+, MySQL 8.0, Apache

## Instalación y Configuración

### Opción 1: Con Docker (Recomendado)

#### Paso 1: Clonar el repositorio

```bash
# Clonar el proyecto
git clone https://github.com/martha-mh/php-codeigniter-test.git
cd php-codeigniter-test
```

#### Paso 2: Configurar base de datos

```bash
# Copiar el archivo de configuración de ejemplo
cp application/config/database.php.example application/config/database.php

# El archivo ya tiene la configuración correcta para Docker
# No necesitas modificarlo si usas Docker
```

#### Paso 3: Verificar requisitos

Asegúrate de tener instalado:
- Docker Desktop (Mac/Windows) o Docker Engine (Linux)
- Docker Compose

```bash
# Verificar instalación
docker --version
docker-compose --version
```

#### Paso 4: Levantar los servicios

```bash
# Construir y levantar los contenedores en segundo plano
docker-compose up -d --build
```

Este comando:
- Construye la imagen PHP con Apache
- Descarga MySQL 8.0
- Descarga phpMyAdmin
- Inicia los 3 servicios
- **Ejecuta automáticamente las migraciones de base de datos**

**Salida esperada:**
```
[+] Building 2.5s (15/15) FINISHED
[+] Running 5/5
 ✔ Network php-codeigniter-test_default  Created
 ✔ Volume php-codeigniter-test_db_data   Created
 ✔ Container ci_db                       Healthy
 ✔ Container php-codeigniter-test-web-1  Started
 ✔ Container ci_phpmyadmin               Started
```

#### Paso 5: Verificar que todo funciona

```bash
# Ver logs del contenedor web
docker-compose logs web

```bash
# Deberías ver mensajes como:
# [OK] Tabla 'tasks' creada exitosamente.
# [OK] Migraciones ejecutadas correctamente.
# [OK] Base de datos lista para usar.
```

```bash
# Verificar estado de migraciones
docker exec php-codeigniter-test-web-1 php index.php migrate status

# Salida esperada:
# Versión actual: 1
# [OK] [001] create_tasks_table
```

#### Paso 6: Acceder a la aplicación

Abre tu navegador en:
- **Aplicación web**: http://127.0.0.1:8080/tasks
- **phpMyAdmin**: http://127.0.0.1:8081
  - Usuario: `root`
  - Contraseña: `rootpass`

#### Paso 7: Probar la API

```bash
# Listar tareas
curl http://127.0.0.1:8080/api/tasks

# Crear una tarea de prueba
curl -X POST http://127.0.0.1:8080/api/tasks \
  -H "Content-Type: application/json" \
  -d '{"title":"Mi primera tarea","description":"Probando la API","due_date":"2025-12-31","status":"pending"}'
```

#### Comandos útiles Docker

```bash
# Ver contenedores en ejecución
docker ps

# Ver logs en tiempo real
docker-compose logs -f web
docker-compose logs -f db

# Detener servicios
docker-compose stop

# Reiniciar servicios
docker-compose restart

# Detener y eliminar contenedores (mantiene datos)
docker-compose down

# Detener y eliminar TODO (incluye base de datos)
docker-compose down -v

# Reconstruir y reiniciar
docker-compose down && docker-compose up -d --build
```

---

### Opción 2: Sin Docker (Instalación Manual)

#### Paso 1: Requisitos previos

Instala los siguientes componentes:

**En Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install -y apache2 php7.4 php7.4-mysql php7.4-mbstring php7.4-xml php7.4-curl mysql-server
```

**En macOS (con Homebrew):**
```bash
brew install php@7.4 mysql apache2
brew services start mysql
brew services start httpd
```

**En Windows:**
- Instalar [XAMPP](https://www.apachefriends.org/) o [WAMP](https://www.wampserver.com/)
- PHP 7.4+
- MySQL 8.0+
- Apache 2.4+

#### Paso 2: Clonar el repositorio

```bash
git clone https://github.com/martha-mh/php-codeigniter-test.git
cd php-codeigniter-test
```

#### Paso 3: Configurar la base de datos

**3.1. Copiar archivo de configuración:**

```bash
# Copiar el archivo de ejemplo
cp application/config/database.php.example application/config/database.php

# Editar con tu editor favorito
nano application/config/database.php
# o
code application/config/database.php
```

**3.2. Ajustar credenciales en `database.php`:**

```php
'hostname' => 'localhost',  // Cambiar de 'db' a 'localhost'
'username' => 'ci_user',
'password' => 'cipass',
'database' => 'ci_db',
```

**3.3. Crear base de datos y usuario:**

```bash
# Acceder a MySQL
mysql -u root -p

# Ejecutar en MySQL:
```

```sql
CREATE DATABASE ci_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ci_user'@'localhost' IDENTIFIED BY 'cipass';
GRANT ALL PRIVILEGES ON ci_db.* TO 'ci_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**3.2. Configurar credenciales:**

Edita `application/config/database.php`:

```php
$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',  // Cambiar de 'db' a 'localhost'
    'username' => 'ci_user',
    'password' => 'cipass',
    'database' => 'ci_db',
    'dbdriver' => 'mysqli',
    // ... resto de configuración
);
```

#### Paso 4: Ejecutar migraciones

```bash
# Desde la raíz del proyecto
php index.php migrate

# Salida esperada:
# ========================================
#    EJECUTANDO MIGRACIONES DE BD
# ========================================
# 
# [OK] Tabla 'tasks' creada exitosamente.
# [OK] Migraciones ejecutadas correctamente.
```

**Verificar:**
```bash
php index.php migrate status

# Salida esperada:
# Versión actual: 1
# [OK] [001] create_tasks_table
```

#### Paso 5: Configurar Apache

**5.1. Copiar archivos al directorio web:**

```bash
# Linux/macOS
sudo cp -r . /var/www/html/php-codeigniter-test/

# Windows (XAMPP)
# Copiar carpeta a C:\xampp\htdocs\php-codeigniter-test\
```

**5.2. Configurar VirtualHost (Opcional pero recomendado):**

Crear archivo `/etc/apache2/sites-available/codeigniter-tasks.conf`:

```apache
<VirtualHost *:80>
    ServerName tasks.local
    DocumentRoot /var/www/html/php-codeigniter-test

    <Directory /var/www/html/php-codeigniter-test>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/tasks_error.log
    CustomLog ${APACHE_LOG_DIR}/tasks_access.log combined
</VirtualHost>
```

```bash
# Habilitar sitio y mod_rewrite
sudo a2ensite codeigniter-tasks.conf
sudo a2enmod rewrite
sudo systemctl restart apache2

# Agregar a /etc/hosts
echo "127.0.0.1 tasks.local" | sudo tee -a /etc/hosts
```

**5.3. Configurar permisos:**

```bash
# Linux/macOS
sudo chown -R www-data:www-data /var/www/html/php-codeigniter-test
sudo chmod -R 755 /var/www/html/php-codeigniter-test
sudo chmod -R 777 /var/www/html/php-codeigniter-test/application/cache
sudo chmod -R 777 /var/www/html/php-codeigniter-test/application/logs
```

#### Paso 6: Verificar instalación

```bash
# Probar desde línea de comandos
php -S localhost:8000

# Abrir en navegador:
# http://localhost:8000/tasks
```

O si configuraste VirtualHost:
```
http://tasks.local/tasks
```

#### Paso 7: Probar la API

```bash
# Listar tareas
curl http://localhost:8000/api/tasks

# O con el VirtualHost:
curl http://tasks.local/api/tasks
```

---

### Migraciones de Base de Datos

Ambos métodos usan el sistema de migraciones de CodeIgniter:

**Ver estado:**
```bash
# Con Docker
docker exec php-codeigniter-test-web-1 php index.php migrate status

# Sin Docker
php index.php migrate status
```

**Ejecutar migraciones:**
```bash
# Con Docker
docker exec php-codeigniter-test-web-1 php index.php migrate

# Sin Docker
php index.php migrate
```

**Resetear base de datos:**
```bash
# Con Docker
docker exec php-codeigniter-test-web-1 php index.php migrate reset

# Sin Docker
php index.php migrate reset
```

## URLs y Endpoints

### Interfaz Web
- **Lista de tareas**: http://127.0.0.1:8080/tasks
- **Crear tarea**: http://127.0.0.1:8080/tasks/create
- **Editar tarea**: http://127.0.0.1:8080/tasks/edit/{id}
- **Eliminar tarea**: http://127.0.0.1:8080/tasks/delete/{id}
- **Cambiar estado**: http://127.0.0.1:8080/tasks/toggle/{id}

### API REST (JSON)

**Listar todas las tareas**
```bash
curl http://127.0.0.1:8080/api/tasks
```

**Buscar por título**
```bash
curl "http://127.0.0.1:8080/api/tasks?search=ejemplo"
```

**Crear tarea**
```bash
curl -X POST http://127.0.0.1:8080/api/tasks \
  -H "Content-Type: application/json" \
  -d '{"title":"Nueva tarea","description":"Descripción","due_date":"2025-12-31","status":"pending"}'
```

**Obtener tarea específica**
```bash
curl http://127.0.0.1:8080/api/tasks/1
```

**Actualizar tarea**
```bash
curl -X PUT http://127.0.0.1:8080/api/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{"title":"Tarea actualizada","status":"completed"}'
```

**Eliminar tarea**
```bash
curl -X DELETE http://127.0.0.1:8080/api/tasks/1
```

## Configuración

### Configuración de Base de Datos

#### IMPORTANTE: Seguridad

El archivo `application/config/database.php` contiene credenciales sensibles y **está en `.gitignore`** para no subirlo al repositorio.

**Primera vez configurando el proyecto:**

1. **Copia el archivo de ejemplo:**
   ```bash
   cp application/config/database.php.example application/config/database.php
   ```

2. **Edita las credenciales según tu entorno:**
   
   **Para Docker (ya está configurado):**
   ```php
   'hostname' => 'db',
   'username' => 'ci_user',
   'password' => 'cipass',
   'database' => 'ci_db',
   ```
   
   **Para instalación local:**
   ```php
   'hostname' => 'localhost',
   'username' => 'root',
   'password' => 'tu_contraseña',
   'database' => 'ci_db',
   ```

#### Variables de Entorno (.env)

También puedes copiar `.env.example` a `.env` y ajustar las variables:

```bash
cp .env.example .env
```

### Credenciales por Defecto (Docker)

- **Host**: db
- **Usuario**: ci_user
- **Password**: cipass
- **Base de datos**: ci_db
- **Root password**: rootpass

**IMPORTANTE:** Cambiar estas credenciales en producción.

### Estructura del Proyecto

```
├── application/
│   ├── config/
│   │   ├── config.php       # Configuración principal
│   │   ├── database.php     # Credenciales BD
│   │   └── routes.php       # Rutas web y API
│   ├── controllers/
│   │   ├── Tasks.php        # Controlador web
│   │   └── api/
│   │       └── Task_api.php # Controlador API REST
│   ├── models/
│   │   └── Task_model.php   # Modelo de datos
│   └── views/
│       └── tasks/
│           ├── index.php    # Vista lista
│           └── form.php     # Vista formulario
├── sql/
│   └── tasks.sql           # Script de BD
├── docker-compose.yml      # Configuración Docker
└── ANALYSIS.md            # Análisis de código (Ejercicio 2)
```

## Características Implementadas

### CRUD Completo
- [x] Crear, leer, actualizar y eliminar tareas
- [x] Filtrado por estado (pendiente/completado)
- [x] Búsqueda en tiempo real con AJAX
- [x] Validación de formularios
- [x] Mensajes flash de confirmación

### API REST
- [x] Endpoints JSON para todas las operaciones
- [x] Códigos HTTP apropiados (200, 201, 404, etc.)
- [x] Búsqueda por parámetro query
- [x] Manejo de errores

### Seguridad Básica
- [x] Validación de entrada
- [x] Prepared statements (Active Record)
- [x] CSRF protection (sesiones)
- [x] XSS filtering básico

## Testing Manual

### Probar el CRUD Web
1. Abre http://127.0.0.1:8080/tasks
2. Crea una nueva tarea
3. Edita y cambia su estado
4. Usa el buscador AJAX
5. Elimina una tarea

### Probar la API
Ejecuta el script de prueba incluido:

```bash
# Ver todas las tareas
curl http://127.0.0.1:8080/index.php/api/tasks | python3 -m json.tool

# Crear tarea
curl -X POST http://127.0.0.1:8080/index.php/api/tasks \
  -H "Content-Type: application/json" \
  -d '{"title":"Test API","description":"Prueba","due_date":"2025-12-01","status":"pending"}'

# Actualizar
curl -X PUT http://127.0.0.1:8080/index.php/api/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{"status":"completed"}'

# Eliminar
curl -X DELETE http://127.0.0.1:8080/index.php/api/tasks/1
```

## Sistema de Migraciones

El proyecto utiliza el sistema de migraciones de CodeIgniter para gestionar la base de datos de forma automática y versionada.

### Estructura de Migraciones

```
application/
  ├── migrations/
  │   └── 001_create_tasks_table.php    # Crea la tabla tasks
  ├── config/
  │   └── migration.php                  # Configuración de migraciones
  └── controllers/
      └── Migrate.php                    # Controlador CLI para migraciones
```

### Comandos Disponibles

**Ver estado de las migraciones:**
```bash
docker exec -it <container_id> php index.php migrate status
```

**Ejecutar migraciones:**
```bash
docker exec -it <container_id> php index.php migrate
```

**Migrar a una versión específica:**
```bash
docker exec -it <container_id> php index.php migrate version 1
```

**Resetear todas las migraciones:**
```bash
docker exec -it <container_id> php index.php migrate reset
```

## phpMyAdmin

Accede a phpMyAdmin en http://127.0.0.1:8081
- Usuario: root
- Password: rootpass

## Solución de Problemas

### Con Docker

**1. Error: "port is already allocated"**
```bash
# El puerto 8080, 3306 u 8081 está en uso
# Ver qué proceso usa el puerto
lsof -i :8080  # macOS/Linux
netstat -ano | findstr :8080  # Windows

# Cambiar el puerto en docker-compose.yml
# En la sección 'web' -> 'ports', cambiar "8080:80" a "9090:80"
```

**2. Error: "Cannot connect to MySQL"**
```bash
# Ver logs de MySQL
docker-compose logs db

# Reiniciar servicios
docker-compose restart db
docker-compose restart web

# Si persiste, recrear base de datos
docker-compose down -v
docker-compose up -d
```

**3. Las migraciones no se ejecutan**
```bash
# Ver logs del contenedor web
docker-compose logs web

# Ejecutar manualmente
docker exec php-codeigniter-test-web-1 php index.php migrate

# Si falla, verificar conexión a BD
docker exec php-codeigniter-test-web-1 php -r "echo 'PHP funciona';"
docker exec ci_db mysql -u ci_user -pcipass ci_db -e "SELECT 1;"
```

**4. Error: "Permission denied" en archivos**
```bash
# Dar permisos a directorios de logs y cache
docker-compose exec web chmod -R 777 /var/www/html/application/cache
docker-compose exec web chmod -R 777 /var/www/html/application/logs
```

**5. Página en blanco o error 500**
```bash
# Ver logs de Apache
docker-compose logs -f web

# Ver logs de PHP
docker exec php-codeigniter-test-web-1 tail -f /var/www/html/application/logs/log-*.php

# Verificar configuración
docker exec php-codeigniter-test-web-1 php -m  # Ver extensiones PHP
```

**6. Resetear completamente el proyecto**
```bash
# Detener y eliminar TODO (contenedores, volúmenes, redes)
docker-compose down -v

# Eliminar imágenes (opcional)
docker rmi php-codeigniter-test-web

# Limpiar cache de Docker
docker system prune -a

# Iniciar desde cero
docker-compose up -d --build
```

---

### Sin Docker

**1. Error: "mysqli extension is missing"**
```bash
# Ubuntu/Debian
sudo apt install php7.4-mysqli
sudo systemctl restart apache2

# macOS (Homebrew)
brew install php@7.4
brew services restart php@7.4

# Verificar extensión
php -m | grep mysqli
```

**2. Error: "Access denied for user"**
```sql
-- Verificar usuario en MySQL
mysql -u root -p

-- Recrear usuario
DROP USER IF EXISTS 'ci_user'@'localhost';
CREATE USER 'ci_user'@'localhost' IDENTIFIED BY 'cipass';
GRANT ALL PRIVILEGES ON ci_db.* TO 'ci_user'@'localhost';
FLUSH PRIVILEGES;

-- Probar conexión
mysql -u ci_user -pcipass ci_db
```

**3. Error 404 en todas las URLs**
```bash
# Verificar mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Verificar .htaccess en la raíz del proyecto
cat .htaccess

# Verificar AllowOverride en Apache config
sudo nano /etc/apache2/apache2.conf
# Buscar <Directory /var/www/> y cambiar AllowOverride a All
```

**4. Las migraciones fallan**
```bash
# Verificar conexión a base de datos
php -r "
\$conn = new mysqli('localhost', 'ci_user', 'cipass', 'ci_db');
if (\$conn->connect_error) {
    die('Error: ' . \$conn->connect_error);
}
echo 'Conexión exitosa';
"

# Ejecutar con errores visibles
php -d display_errors=1 index.php migrate

# Verificar configuración en application/config/database.php
```

**5. Error: "Failed to start session"**
```bash
# Crear directorio de sesiones
mkdir -p /tmp/ci_sessions
chmod 777 /tmp/ci_sessions

# O cambiar en application/config/config.php:
# $config['sess_save_path'] = sys_get_temp_dir();
```

**6. Página en blanco**
```bash
# Activar display_errors en index.php
nano index.php
# Cambiar: ini_set('display_errors', 1);

# Ver logs de Apache
tail -f /var/log/apache2/error.log  # Linux
tail -f /usr/local/var/log/httpd/error_log  # macOS
# C:\xampp\apache\logs\error.log  # Windows XAMPP

# Ver logs de PHP de CodeIgniter
tail -f application/logs/log-*.php
```

**7. Permisos en Linux/macOS**
```bash
# Dar permisos correctos
sudo chown -R www-data:www-data /var/www/html/php-codeigniter-test
sudo chmod -R 755 /var/www/html/php-codeigniter-test
sudo chmod -R 777 /var/www/html/php-codeigniter-test/application/cache
sudo chmod -R 777 /var/www/html/php-codeigniter-test/application/logs

# En macOS el usuario puede ser _www o tu usuario
sudo chown -R _www:_www /Library/WebServer/Documents/php-codeigniter-test
```

---

### Problemas Comunes (Ambos)

**1. "Headers already sent"**
```
Solución: Asegurar que no haya salida antes de headers
- Verificar que no haya espacios antes de <?php
- Verificar que archivos PHP estén en UTF-8 sin BOM
- En index.php cambiar ini_set('display_errors', 0);
```

**2. AJAX search no funciona**
```bash
# Verificar que la URL sea correcta
# En navegador, abrir Developer Tools -> Network
# Verificar que la petición a /api/tasks?search=xxx devuelva JSON

# Probar manualmente
curl http://127.0.0.1:8080/api/tasks?search=tarea
```

**3. No aparecen las tareas**
```bash
# Verificar que la tabla tenga datos
# Con Docker:
docker exec ci_db mysql -u ci_user -pcipass ci_db -e "SELECT * FROM tasks;"

# Sin Docker:
mysql -u ci_user -pcipass ci_db -e "SELECT * FROM tasks;"

# Si está vacía, crear una tarea de prueba desde la interfaz web
```

## Notas de Desarrollo

- **Environment**: Configurado como 'development' en index.php
- **Display errors**: Desactivado para evitar conflictos con headers HTTP
- **Log threshold**: Configurado en 0 (sin logs) para desarrollo
- **Session storage**: Sistema de archivos temporal

### Para Producción
1. Cambiar `ENVIRONMENT` a 'production' en `index.php`
2. Actualizar `encryption_key` en `config.php`
3. Configurar `log_path` y aumentar `log_threshold`
4. Habilitar mod_rewrite para URLs limpias
5. Añadir autenticación y autorización
6. Implementar rate limiting en la API

---

## Inicio Rápido (Resumen)

### Con Docker (4 comandos):
```bash
git clone https://github.com/martha-mh/php-codeigniter-test.git
cd php-codeigniter-test
cp application/config/database.php.example application/config/database.php
docker-compose up -d
```
**Listo!** -> http://127.0.0.1:8080/tasks

### Sin Docker (6 pasos):
```bash
# 1. Instalar requisitos (PHP 7.4+, MySQL 8.0+, Apache)

# 2. Clonar repositorio
git clone https://github.com/martha-mh/php-codeigniter-test.git
cd php-codeigniter-test

# 3. Configurar database.php
cp application/config/database.php.example application/config/database.php
# Editar y cambiar 'hostname' de 'db' a 'localhost'

# 4. Crear base de datos
mysql -u root -p -e "CREATE DATABASE ci_db; CREATE USER 'ci_user'@'localhost' IDENTIFIED BY 'cipass'; GRANT ALL ON ci_db.* TO 'ci_user'@'localhost';"

# 5. Ejecutar migraciones
php index.php migrate

# 6. Iniciar servidor
php -S localhost:8000
```
**Listo!** -> http://localhost:8000/tasks

---

## Autor

Proyecto desarrollado como parte del ejercicio técnico PHP Developer 2025 por Martha Morales.
