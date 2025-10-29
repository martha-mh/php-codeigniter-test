<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador de Migraciones
 * Se ejecuta desde CLI para inicializar la base de datos
 */
class Migrate extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Solo permitir ejecución desde CLI
        if (!$this->input->is_cli_request()) {
            show_error('Este script solo puede ejecutarse desde la línea de comandos.', 403);
        }
        
        $this->load->library('migration');
    }
    
    /**
     * Ejecutar migraciones hasta la versión actual
     */
    public function index() {
        echo "========================================\n";
        echo "   EJECUTANDO MIGRACIONES DE BD\n";
        echo "========================================\n\n";
        
        if ($this->migration->current() === FALSE) {
            echo "[ERROR] " . $this->migration->error_string() . "\n";
            exit(1);
        } else {
            echo "\n[OK] Migraciones ejecutadas correctamente.\n";
            echo "   Base de datos lista para usar.\n\n";
            exit(0);
        }
    }
    
    /**
     * Ejecutar migración a una versión específica
     */
    public function version($version = null) {
        if ($version === null) {
            echo "[ERROR] Debes especificar una versión.\n";
            echo "Uso: php index.php migrate version <número>\n";
            exit(1);
        }
        
        echo "Migrando a versión {$version}...\n";
        
        if ($this->migration->version($version) === FALSE) {
            echo "[ERROR] " . $this->migration->error_string() . "\n";
            exit(1);
        } else {
            echo "[OK] Migración completada a versión {$version}.\n";
            exit(0);
        }
    }
    
    /**
     * Revertir todas las migraciones
     */
    public function reset() {
        echo "[ADVERTENCIA] Esto eliminará todas las tablas.\n";
        echo "Revirtiendo migraciones...\n";
        
        if ($this->migration->version(0) === FALSE) {
            echo "[ERROR] " . $this->migration->error_string() . "\n";
            exit(1);
        } else {
            echo "[OK] Base de datos reseteada.\n";
            exit(0);
        }
    }
    
    /**
     * Información sobre el estado de las migraciones
     */
    public function status() {
        echo "========================================\n";
        echo "   ESTADO DE MIGRACIONES\n";
        echo "========================================\n\n";
        
        // Verificar si la tabla de migraciones existe
        if (!$this->db->table_exists('migrations')) {
            echo "[INFO] Tabla 'migrations' no existe aún.\n";
            echo "   Ejecuta: php index.php migrate\n\n";
            exit(0);
        }
        
        $query = $this->db->get('migrations');
        $current_version = $query->row()->version ?? 0;
        
        echo "Versión actual: {$current_version}\n";
        echo "Versión objetivo: " . $this->config->item('migration_version') . "\n\n";
        
        // Listar archivos de migración
        $migration_path = APPPATH . 'migrations/';
        $migrations = glob($migration_path . '*.php');
        
        echo "Migraciones disponibles:\n";
        foreach ($migrations as $migration) {
            $filename = basename($migration);
            preg_match('/^(\d+)_(.+)\.php$/', $filename, $matches);
            $version = $matches[1] ?? '?';
            $name = $matches[2] ?? $filename;
            
            $status = ($version <= $current_version) ? '[OK]' : '[PENDIENTE]';
            echo "  {$status} [{$version}] {$name}\n";
        }
        
        echo "\n";
    }
}
