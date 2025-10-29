<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migración para crear la tabla tasks
 */
class Migration_Create_tasks_table extends CI_Migration {
    
    public function up() {
        // Cargar dbforge
        $this->load->dbforge();
        
        // Crear tabla tasks
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'due_date' => [
                'type' => 'DATE',
                'null' => TRUE
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'completed'],
                'default' => 'pending',
                'null' => FALSE
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => FALSE
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => FALSE
            ]
        ]);
        
        $this->dbforge->add_key('id', TRUE);
        
        // Crear tabla base
        $this->dbforge->create_table('tasks', TRUE);
        
        // Modificar timestamps con query crudo (dbforge no soporta bien DEFAULT CURRENT_TIMESTAMP)
        $this->db->query('ALTER TABLE `tasks` 
            MODIFY `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            MODIFY `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ');
        
        echo "[OK] Tabla 'tasks' creada exitosamente.\n";
    }
    
    public function down() {
        // Cargar dbforge
        $this->load->dbforge();
        
        // Eliminar tabla tasks
        $this->dbforge->drop_table('tasks', TRUE);
        echo "[INFO] Tabla 'tasks' eliminada.\n";
    }
}
