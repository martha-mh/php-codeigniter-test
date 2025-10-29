<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Task_model');
		$this->load->helper(['url', 'form']);
		$this->load->library(['form_validation','session']);
	}

	public function index()
	{
		$status = $this->input->get('status');
		$data['tasks'] = $this->Task_model->get_all($status);
		$this->load->view('tasks/index', $data);
	}

	public function create()
	{
		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('title', 'Título', 'required|trim');

			if ($this->form_validation->run() === TRUE) {
				$payload = [
					'title' => $this->input->post('title', TRUE),
					'description' => $this->input->post('description', TRUE),
					'due_date' => $this->input->post('due_date', TRUE),
				];

				$this->Task_model->create($payload);
				$this->session->set_flashdata('message', 'Tarea creada correctamente.');
				redirect('tasks');
			} else {
				// If validation fails, redirect back to index with errors
				$status = $this->input->get('status');
				$data['tasks'] = $this->Task_model->get_all($status);
				$this->load->view('tasks/index', $data);
				return;
			}
		}

		$this->load->view('tasks/form');
	}

	public function edit($id = null)
	{
		if (empty($id)) show_404();

		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('title', 'Título', 'required|trim');

			if ($this->form_validation->run() === TRUE) {
				$payload = [
					'title' => $this->input->post('title', TRUE),
					'description' => $this->input->post('description', TRUE),
					'due_date' => $this->input->post('due_date', TRUE),
					'status' => $this->input->post('status', TRUE),
				];

				$this->Task_model->update($id, $payload);
				$this->session->set_flashdata('message', 'Tarea actualizada.');
				redirect('tasks');
			} else {
				// If validation fails, redirect back to index with errors and task data
				$status = $this->input->get('status');
				$data['tasks'] = $this->Task_model->get_all($status);
				$data['edit_task'] = $this->Task_model->get($id);
				$this->load->view('tasks/index', $data);
				return;
			}
		}

		$data['task'] = $this->Task_model->get($id);
		if (empty($data['task'])) show_404();
		$this->load->view('tasks/form', $data);
	}

	public function delete($id = null)
	{
		if (empty($id)) show_404();
		$this->Task_model->delete($id);
		$this->session->set_flashdata('message', 'Tarea eliminada.');
		redirect('tasks');
	}

	public function toggle($id = null)
	{
		if (empty($id)) show_404();
		$task = $this->Task_model->get($id);
		if (empty($task)) show_404();

		$new_status = ($task['status'] === 'completed') ? 'pending' : 'completed';
		$this->Task_model->update($id, ['status' => $new_status]);
		$this->session->set_flashdata('message', 'Estado actualizado.');
		redirect('tasks');
	}
}

?>
