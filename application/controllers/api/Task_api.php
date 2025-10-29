<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Task_model');
		// Respuestas JSON
		header('Content-Type: application/json; charset=utf-8');
	}

	// GET /api/tasks  (list)  POST /api/tasks (create)
	public function index()
	{
		$method = $this->input->method();

		if ($method === 'get') {
			$status = $this->input->get('status');
			$search = $this->input->get('search');
			$tasks = $this->Task_model->get_all($status, $search);
			echo json_encode($tasks);
			return;
		}

		if ($method === 'post') {
			$payload = json_decode($this->input->raw_input_stream, true);
			if (empty($payload)) $payload = $this->input->post();

			if (empty($payload['title'])) {
				http_response_code(422);
				echo json_encode(['error' => 'title is required']);
				return;
			}

			$id = $this->Task_model->create($payload);
			http_response_code(201);
			echo json_encode(['id' => $id]);
			return;
		}

		http_response_code(405);
		echo json_encode(['error' => 'Method not allowed']);
	}

	// GET /api/tasks/{id}  PUT /api/tasks/{id}  DELETE /api/tasks/{id}
	public function item($id = null)
	{
		if (empty($id)) {
			http_response_code(400);
			echo json_encode(['error' => 'id required']);
			return;
		}

		$method = $this->input->method();

		if ($method === 'get') {
			$task = $this->Task_model->get($id);
			if (empty($task)) {
				http_response_code(404);
				echo json_encode(['error' => 'not found']);
				return;
			}
			echo json_encode($task);
			return;
		}

		if ($method === 'put' || $method === 'patch') {
			$payload = json_decode($this->input->raw_input_stream, true);
			if (empty($payload)) {
				http_response_code(422);
				echo json_encode(['error' => 'invalid payload']);
				return;
			}

			$this->Task_model->update($id, $payload);
			echo json_encode(['updated' => true]);
			return;
		}

		if ($method === 'delete') {
			$this->Task_model->delete($id);
			echo json_encode(['deleted' => true]);
			return;
		}

		http_response_code(405);
		echo json_encode(['error' => 'Method not allowed']);
	}
}

?>
