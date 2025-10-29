<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Obtener todas las tareas, opcionalmente filtrando por estado
	 * @param string|null $status 'pending'|'completed' or null
	 * @return array
	 */
	public function get_all($status = null, $search = null)
	{
		if ($status && in_array($status, ['pending','completed'])) {
			$this->db->where('status', $status);
		}
		if (!empty($search)) {
			// Buscar por título (case-insensitive)
			$this->db->like('title', $search);
		}
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get('tasks')->result_array();
	}

	public function get($id)
	{
		return $this->db->get_where('tasks', ['id' => (int)$id])->row_array();
	}

	public function create($data)
	{
		$now = date('Y-m-d H:i:s');
		$insert = [
			'title' => $data['title'],
			'description' => isset($data['description']) ? $data['description'] : null,
			'due_date' => !empty($data['due_date']) ? $data['due_date'] : null,
			'status' => isset($data['status']) ? $data['status'] : 'pending',
			'created_at' => $now,
			'updated_at' => $now,
		];

		$this->db->insert('tasks', $insert);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		$update = [];
		if (isset($data['title'])) $update['title'] = $data['title'];
		if (array_key_exists('description', $data)) $update['description'] = $data['description'];
		if (array_key_exists('due_date', $data)) $update['due_date'] = $data['due_date'];
		if (isset($data['status']) && in_array($data['status'], ['pending','completed'])) $update['status'] = $data['status'];
		$update['updated_at'] = date('Y-m-d H:i:s');

		$this->db->where('id', (int)$id);
		$this->db->update('tasks', $update);
		return $this->db->affected_rows() >= 0;
	}

	public function delete($id)
	{
		$this->db->where('id', (int)$id);
		$this->db->delete('tasks');
		return $this->db->affected_rows() > 0;
	}
}

?>
