<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo isset($task) ? 'Editar' : 'Crear'; ?> Tarea</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<style>
		body { background-color: #f8f9fa; }
		.navbar { box-shadow: 0 2px 4px rgba(0,0,0,.1); }
		.form-container { 
			background: white; 
			border-radius: 8px; 
			padding: 30px; 
			box-shadow: 0 2px 8px rgba(0,0,0,.08);
			max-width: 700px;
			margin: 0 auto;
		}
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
		<div class="form-container">
			<!-- Header -->
			<div class="mb-4">
				<h1 class="h3 mb-1">
					<i class="bi bi-<?php echo isset($task) ? 'pencil-square' : 'plus-circle'; ?>"></i>
					<?php echo isset($task) ? 'Editar' : 'Crear'; ?> Tarea
				</h1>
				<p class="text-muted">
					<?php echo isset($task) ? 'Modifica los datos de la tarea' : 'Completa el formulario para crear una nueva tarea'; ?>
				</p>
			</div>

			<!-- Validation Errors -->
			<?php if (validation_errors()): ?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<i class="bi bi-exclamation-triangle-fill"></i>
					<strong>Errores de validación:</strong>
					<?php echo validation_errors(); ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			<?php endif; ?>

			<!-- Form -->
			<?php if (isset($task)): ?>
				<form method="post" action="<?php echo base_url('tasks/edit/'.$task['id']); ?>">
			<?php else: ?>
				<form method="post" action="<?php echo base_url('tasks/create'); ?>">
			<?php endif; ?>

				<div class="mb-3">
					<label for="title" class="form-label fw-bold">
						<i class="bi bi-card-text"></i> Título <span class="text-danger">*</span>
					</label>
					<input 
						type="text" 
						class="form-control" 
						id="title" 
						name="title" 
						value="<?php echo isset($task) ? htmlspecialchars($task['title']) : set_value('title'); ?>" 
						required 
						placeholder="Ej: Completar informe mensual"
					/>
					<div class="form-text">El título debe ser descriptivo y conciso.</div>
				</div>

				<div class="mb-3">
					<label for="description" class="form-label fw-bold">
						<i class="bi bi-align-left"></i> Descripción
					</label>
					<textarea 
						class="form-control" 
						id="description" 
						name="description" 
						rows="4"
						placeholder="Describe los detalles de la tarea..."
					><?php echo isset($task) ? htmlspecialchars($task['description']) : set_value('description'); ?></textarea>
					<div class="form-text">Añade información adicional sobre la tarea (opcional).</div>
				</div>

				<div class="mb-3">
					<label for="due_date" class="form-label fw-bold">
						<i class="bi bi-calendar-event"></i> Fecha límite
					</label>
					<input 
						type="date" 
						class="form-control" 
						id="due_date" 
						name="due_date" 
						value="<?php echo isset($task) ? $task['due_date'] : set_value('due_date'); ?>"
					/>
					<div class="form-text">Fecha en que debe completarse la tarea.</div>
				</div>

				<?php if (isset($task)): ?>
					<div class="mb-4">
						<label for="status" class="form-label fw-bold">
							<i class="bi bi-toggle-on"></i> Estado
						</label>
						<select class="form-select" id="status" name="status">
							<option value="pending" <?php echo ($task['status']=='pending')?'selected':''; ?>>
								<i class="bi bi-clock"></i> Pendiente
							</option>
							<option value="completed" <?php echo ($task['status']=='completed')?'selected':''; ?>>
								<i class="bi bi-check-circle"></i> Completada
							</option>
						</select>
						<div class="form-text">Cambia el estado actual de la tarea.</div>
					</div>
				<?php endif; ?>

				<hr class="my-4">

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary">
						<i class="bi bi-save"></i> <?php echo isset($task) ? 'Guardar cambios' : 'Crear tarea'; ?>
					</button>
					<a href="<?php echo base_url('tasks'); ?>" class="btn btn-outline-secondary">
						<i class="bi bi-arrow-left"></i> Volver
					</a>
				</div>
			</form>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
