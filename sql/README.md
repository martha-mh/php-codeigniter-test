# Scripts SQL

## ℹ️ Sistema de Migraciones Automáticas

Este proyecto utiliza el **sistema de migraciones de CodeIgniter** para gestionar la base de datos de forma automática.

### No necesitas ejecutar scripts SQL manualmente

La tabla `tasks` se crea automáticamente cuando inicias el proyecto con Docker Compose.

### Archivos

- **`tasks.sql.backup`**: Script SQL manual (solo referencia, no se usa)
- **Migraciones activas**: Ver `application/migrations/`

### Gestión de Migraciones

**Ver estado:**
```bash
docker exec -it <container_id> php index.php migrate status
```

**Ejecutar migraciones:**
```bash
docker exec -it <container_id> php index.php migrate
```

**Resetear (eliminar todas las tablas):**
```bash
docker exec -it <container_id> php index.php migrate reset
```

### Crear Nuevas Migraciones

1. Crear archivo en `application/migrations/` con formato: `00X_nombre.php`
2. Implementar métodos `up()` y `down()`
3. Actualizar `migration_version` en `application/config/migration.php`
4. Ejecutar: `php index.php migrate`

Ver más detalles en el `README.md` principal.
