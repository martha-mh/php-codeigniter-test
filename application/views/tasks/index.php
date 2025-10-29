<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lista de Tareas</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">
	<style>
		body { background-color: #f8f9fa; }
		.navbar { box-shadow: 0 2px 4px rgba(0,0,0,.1); }
		.table-container { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
		.status-badge { font-size: 0.85rem; }
		.btn-action { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
	</style>
</head>
<body>
	<!-- Navbar -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
		<div class="container">
			<a class="navbar-brand" href="<?php echo base_url('tasks'); ?>">
				<i class="bi bi-check2-square"></i> Task Manager
			</a>
		</div>
	</nav>

	<div class="container">
		<!-- Alert Messages -->
		<?php if ($this->session->flashdata('message')): ?>
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<i class="bi bi-check-circle-fill"></i> <?php echo $this->session->flashdata('message'); ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		<?php endif; ?>

		<!-- Header -->
		<div class="d-flex justify-content-between align-items-center mb-4">
			<h1 class="h3 mb-0"><i class="bi bi-list-task"></i> Mis Tareas</h1>
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
				<i class="bi bi-plus-circle"></i> Nueva Tarea
			</button>
		</div>

		<!-- Filters and Search -->
		<div class="table-container mb-4">
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label fw-bold">Filtrar por estado:</label>
					<div class="btn-group w-100" role="group">
						<a href="<?php echo base_url('tasks'); ?>" class="btn btn-outline-secondary <?php echo empty($_GET['status']) ? 'active' : ''; ?>">
							<i class="bi bi-list"></i> Todas
						</a>
						<a href="<?php echo base_url('tasks?status=pending'); ?>" class="btn btn-outline-warning <?php echo (isset($_GET['status']) && $_GET['status']=='pending') ? 'active' : ''; ?>">
							<i class="bi bi-clock"></i> Pendientes
						</a>
						<a href="<?php echo base_url('tasks?status=completed'); ?>" class="btn btn-outline-success <?php echo (isset($_GET['status']) && $_GET['status']=='completed') ? 'active' : ''; ?>">
							<i class="bi bi-check-circle"></i> Completadas
						</a>
					</div>
				</div>
				<div class="col-md-6">
					<label for="task-search" class="form-label fw-bold">Buscar:</label>
					<div class="input-group">
						<span class="input-group-text"><i class="bi bi-search"></i></span>
						<input id="task-search" type="text" class="form-control" placeholder="Buscar por título..." />
					</div>
				</div>
			</div>
		</div>

		<!-- Tasks Table -->
		<div class="table-container">
			<?php if (empty($tasks)): ?>
				<div class="text-center py-5">
					<i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
					<p class="text-muted mt-3">No hay tareas disponibles.</p>
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
						<i class="bi bi-plus-circle"></i> Crear la primera tarea
					</button>
				</div>
			<?php else: ?>
				<div class="table-responsive">
					<table class="table table-hover align-middle">
						<thead class="table-light">
							<tr>
								<th style="width: 60px;">#</th>
								<th>Título</th>
								<th>Descripción</th>
								<th style="width: 130px;">Vencimiento</th>
								<th style="width: 120px;">Estado</th>
								<th style="width: 200px;" class="text-center">Acciones</th>
							</tr>
						</thead>
						<tbody id="tasks-tbody">
							<?php foreach ($tasks as $t): ?>
								<tr>
									<td class="fw-bold text-muted"><?php echo $t['id']; ?></td>
									<td>
										<strong><?php echo htmlspecialchars($t['title']); ?></strong>
									</td>
									<td class="text-muted">
										<?php 
											$desc = htmlspecialchars($t['description']);
											echo strlen($desc) > 60 ? substr($desc, 0, 60) . '...' : $desc;
										?>
									</td>
									<td>
										<small class="text-muted">
											<i class="bi bi-calendar-event"></i> <?php echo date('d/m/Y', strtotime($t['due_date'])); ?>
										</small>
									</td>
									<td>
										<?php if ($t['status'] == 'completed'): ?>
											<span class="badge bg-success status-badge">
												<i class="bi bi-check-circle"></i> Completada
											</span>
										<?php else: ?>
											<span class="badge bg-warning text-dark status-badge">
												<i class="bi bi-clock"></i> Pendiente
											</span>
										<?php endif; ?>
									</td>
									<td class="text-center">
										<button type="button" class="btn btn-sm btn-outline-primary btn-action" title="Editar" onclick="editTask(<?php echo $t['id']; ?>, '<?php echo htmlspecialchars(addslashes($t['title'])); ?>', '<?php echo htmlspecialchars(addslashes($t['description'])); ?>', '<?php echo $t['due_date']; ?>', '<?php echo $t['status']; ?>')">
											<i class="bi bi-pencil-square"></i>
										</button>
										<button type="button" class="btn btn-sm btn-outline-info btn-action" title="Cambiar estado" onclick="confirmToggleStatus(<?php echo $t['id']; ?>, '<?php echo $t['status']; ?>')">
											<i class="bi bi-arrow-repeat"></i>
										</button>
										<button type="button" class="btn btn-sm btn-outline-danger btn-action" title="Eliminar" onclick="confirmDelete(<?php echo $t['id']; ?>)">
											<i class="bi bi-trash"></i>
										</button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</div>

		<!-- Create Task Modal -->
		<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header bg-primary text-white">
						<h5 class="modal-title" id="createTaskModalLabel">
							<i class="bi bi-plus-circle"></i> Nueva Tarea
						</h5>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form method="post" action="<?php echo base_url('tasks/create'); ?>" id="createTaskForm">
						<div class="modal-body">
							<?php if (validation_errors()): ?>
								<div class="alert alert-danger alert-dismissible fade show" role="alert">
									<i class="bi bi-exclamation-triangle-fill"></i>
									<strong>Errores de validación:</strong>
									<?php echo validation_errors(); ?>
									<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
								</div>
							<?php endif; ?>

							<div class="mb-3">
								<label for="modal_title" class="form-label fw-bold">
									<i class="bi bi-card-text"></i> Título <span class="text-danger">*</span>
								</label>
								<input 
									type="text" 
									class="form-control" 
									id="modal_title" 
									name="title" 
									value="<?php echo set_value('title'); ?>"
									required 
									placeholder="Ej: Completar informe mensual"
								/>
							</div>

							<div class="mb-3">
								<label for="modal_description" class="form-label fw-bold">
									<i class="bi bi-align-left"></i> Descripción
								</label>
								<textarea 
									class="form-control" 
									id="modal_description" 
									name="description" 
									rows="3"
									placeholder="Describe los detalles de la tarea..."
								><?php echo set_value('description'); ?></textarea>
							</div>

							<div class="mb-3">
								<label for="modal_due_date" class="form-label fw-bold">
									<i class="bi bi-calendar-event"></i> Fecha límite
								</label>
								<input 
									type="date" 
									class="form-control" 
									id="modal_due_date" 
									name="due_date"
									value="<?php echo set_value('due_date'); ?>"
								/>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
								<i class="bi bi-x-circle"></i> Cancelar
							</button>
							<button type="submit" class="btn btn-primary">
								<i class="bi bi-save"></i> Crear tarea
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Edit Task Modal -->
		<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header bg-primary text-white">
						<h5 class="modal-title" id="editTaskModalLabel">
							<i class="bi bi-pencil-square"></i> Editar Tarea
						</h5>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form method="post" id="editTaskForm">
						<div class="modal-body">
							<div class="mb-3">
								<label for="edit_title" class="form-label fw-bold">
									<i class="bi bi-card-text"></i> Título <span class="text-danger">*</span>
								</label>
								<input 
									type="text" 
									class="form-control" 
									id="edit_title" 
									name="title" 
									required 
									placeholder="Ej: Completar informe mensual"
								/>
							</div>

							<div class="mb-3">
								<label for="edit_description" class="form-label fw-bold">
									<i class="bi bi-align-left"></i> Descripción
								</label>
								<textarea 
									class="form-control" 
									id="edit_description" 
									name="description" 
									rows="3"
									placeholder="Describe los detalles de la tarea..."
								></textarea>
							</div>

							<div class="mb-3">
								<label for="edit_due_date" class="form-label fw-bold">
									<i class="bi bi-calendar-event"></i> Fecha límite
								</label>
								<input 
									type="date" 
									class="form-control" 
									id="edit_due_date" 
									name="due_date"
								/>
							</div>

							<div class="mb-3">
								<label for="edit_status" class="form-label fw-bold">
									<i class="bi bi-toggle-on"></i> Estado
								</label>
								<select class="form-select" id="edit_status" name="status">
									<option value="pending">
										<i class="bi bi-clock"></i> Pendiente
									</option>
									<option value="completed">
										<i class="bi bi-check-circle"></i> Completada
									</option>
								</select>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
								<i class="bi bi-x-circle"></i> Cancelar
							</button>
							<button type="submit" class="btn btn-primary">
								<i class="bi bi-save"></i> Guardar cambios
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
	<script>
		// Open modal if there are validation errors on page load
		<?php if (validation_errors()): ?>
			document.addEventListener('DOMContentLoaded', function() {
				<?php if (isset($edit_task)): ?>
					// Open edit modal with task data
					editTask(
						<?php echo $edit_task['id']; ?>,
						'<?php echo htmlspecialchars(addslashes($edit_task['title'])); ?>',
						'<?php echo htmlspecialchars(addslashes($edit_task['description'])); ?>',
						'<?php echo $edit_task['due_date']; ?>',
						'<?php echo $edit_task['status']; ?>'
					);
				<?php else: ?>
					// Open create modal
					const modal = new bootstrap.Modal(document.getElementById('createTaskModal'));
					modal.show();
				<?php endif; ?>
			});
		<?php endif; ?>

		// Function to edit task - opens modal with task data
		function editTask(id, title, description, dueDate, status) {
			document.getElementById('edit_title').value = title;
			document.getElementById('edit_description').value = description;
			document.getElementById('edit_due_date').value = dueDate;
			document.getElementById('edit_status').value = status;
			
			// Update form action with task ID
			document.getElementById('editTaskForm').action = '<?php echo base_url('tasks/edit/'); ?>' + id;
			
			// Open modal
			const modal = new bootstrap.Modal(document.getElementById('editTaskModal'));
			modal.show();
		}

		// Function to confirm status toggle with SweetAlert2
		function confirmToggleStatus(id, currentStatus) {
			const newStatus = currentStatus === 'completed' ? 'Pendiente' : 'Completada';
			const icon = currentStatus === 'completed' ? 'warning' : 'success';
			const color = currentStatus === 'completed' ? '#ffc107' : '#198754';
			
			Swal.fire({
				title: '¿Cambiar estado?',
				html: `¿Deseas cambiar el estado de esta tarea a <strong>${newStatus}</strong>?`,
				icon: icon,
				showCancelButton: true,
				confirmButtonColor: color,
				cancelButtonColor: '#6c757d',
				confirmButtonText: `Sí, cambiar a ${newStatus}`,
				cancelButtonText: 'Cancelar',
				reverseButtons: true
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = '<?php echo base_url('tasks/toggle/'); ?>' + id;
				}
			});
		}

		// Function to confirm delete with SweetAlert2
		function confirmDelete(id) {
			Swal.fire({
				title: '¿Eliminar tarea?',
				text: "Esta acción no se puede deshacer",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#dc3545',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Sí, eliminar',
				cancelButtonText: 'Cancelar',
				reverseButtons: true
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = '<?php echo base_url('tasks/delete/'); ?>' + id;
				}
			});
		}

		// Debounce helper
		function debounce(fn, delay){
			let t;
			return function(...args){
				clearTimeout(t);
				t = setTimeout(()=>fn.apply(this,args), delay);
			}
		}

		function renderTasks(rows){
			const tbody = document.getElementById('tasks-tbody');
			if(!tbody) return;
			if(!rows || rows.length===0){
				tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox"></i> No hay tareas que coincidan con la búsqueda.</td></tr>';
				return;
			}
			tbody.innerHTML = rows.map(t=>{
				const statusBadge = t.status === 'completed' 
					? '<span class="badge bg-success status-badge"><i class="bi bi-check-circle"></i> Completada</span>'
					: '<span class="badge bg-warning text-dark status-badge"><i class="bi bi-clock"></i> Pendiente</span>';
				
				const desc = (t.description || '').length > 60 ? (t.description || '').substring(0, 60) + '...' : (t.description || '');
				const dueDate = new Date(t.due_date).toLocaleDateString('es-ES');
				
				// Escape quotes for onclick handler
				const titleEscaped = (t.title || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
				const descEscaped = (t.description || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
				
				return `<tr>`+
					`<td class="fw-bold text-muted">${t.id}</td>`+
					`<td><strong>${t.title || ''}</strong></td>`+
					`<td class="text-muted">${desc}</td>`+
					`<td><small class="text-muted"><i class="bi bi-calendar-event"></i> ${dueDate}</small></td>`+
					`<td>${statusBadge}</td>`+
					`<td class="text-center">`+
					  `<button type="button" class="btn btn-sm btn-outline-primary btn-action" title="Editar" onclick="editTask(${t.id}, '${titleEscaped}', '${descEscaped}', '${t.due_date}', '${t.status}')"><i class="bi bi-pencil-square"></i></button> `+
					  `<button type="button" class="btn btn-sm btn-outline-info btn-action" title="Cambiar estado" onclick="confirmToggleStatus(${t.id}, '${t.status}')"><i class="bi bi-arrow-repeat"></i></button> `+
					  `<button type="button" class="btn btn-sm btn-outline-danger btn-action" title="Eliminar" onclick="confirmDelete(${t.id})"><i class="bi bi-trash"></i></button>`+
					`</td>`+
				`</tr>`;
			}).join('');
		}

		async function searchTasks(q){
			try{
				const url = '<?php echo base_url('api/tasks'); ?>' + (q?('?search='+encodeURIComponent(q)):'');
				const res = await fetch(url, { credentials: 'same-origin' });
				if(!res.ok) return;
				const data = await res.json();
				renderTasks(data);
			}catch(e){
				console.error(e);
			}
		}

		const input = document.getElementById('task-search');
		if(input){
			input.addEventListener('input', debounce(function(e){
				const q = e.target.value.trim();
				searchTasks(q);
			}, 300));
		}
	</script>
</body>
</html>
