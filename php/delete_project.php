<?php
require_once 'conexion.php';

// Variable to store messages
$mensaje = '';

// If we have an ID in the URL, load that project
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, nombre, foto, url, activo FROM proyectos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $proyecto = $result->fetch_assoc();

        // Get technologies for this project
        $techQuery = "SELECT t.nombre FROM tecnologias t 
                      JOIN proyecto_tecnologia pt ON t.id = pt.tecnologia_id 
                      WHERE pt.proyecto_id = ?";
        $stmt = $conn->prepare($techQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $techResult = $stmt->get_result();

        $technologies = [];
        while ($techRow = $techResult->fetch_assoc()) {
            $technologies[] = $techRow['nombre'];
        }
    } else {
        $mensaje = "Proyecto no encontrado.";
    }
    $stmt->close();
} elseif (!isset($_POST['id'])) {
    // If no ID is provided and not a POST request, show all projects
    $result = $conn->query("SELECT id, nombre, foto, activo FROM proyectos ORDER BY nombre");
    $proyectos = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle form submission for soft delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Update the activo status (0 = inactive, 1 = active)
    $new_status = ($_POST['action'] == 'activate') ? 1 : 0;

    $stmt = $conn->prepare("UPDATE proyectos SET activo = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $id);

    if ($stmt->execute()) {
        $action_text = ($new_status == 1) ? "activado" : "desactivado";
        $mensaje = "Proyecto {$action_text} exitosamente.";

        // Reload the projects list
        $result = $conn->query("SELECT id, nombre, foto, activo FROM proyectos ORDER BY nombre");
        $proyectos = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $mensaje = "Error al actualizar en base de datos: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow p-4">
                    <h2 class="mb-4 text-center">Gestionar Proyectos</h2>

                    <?php if (isset($mensaje) && !empty($mensaje)): ?>
                        <div class="alert alert-info"><?php echo $mensaje; ?></div>
                    <?php endif; ?>

                    <?php if (isset($proyectos)): ?>
                        <!-- List of projects -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Imagen</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($proyectos as $project): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($project['nombre']); ?></td>
                                            <td>
                                                <?php if (!empty($project['foto']) && file_exists($project['foto'])): ?>
                                                    <img src="<?php echo $project['foto']; ?>" alt="<?php echo htmlspecialchars($project['nombre']); ?>" style="max-height: 50px;">
                                                <?php else: ?>
                                                    <span class="text-muted">Sin imagen</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($project['activo'] == 1): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form method="post" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                                    <?php if ($project['activo'] == 1): ?>
                                                        <input type="hidden" name="action" value="deactivate">
                                                        <button type="submit" class="btn btn-danger btn-sm">Desactivar</button>
                                                    <?php else: ?>
                                                        <input type="hidden" name="action" value="activate">
                                                        <button type="submit" class="btn btn-success btn-sm">Activar</button>
                                                    <?php endif; ?>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="http://localhost:3000/php/panel_admin.php" class="btn btn-outline-primary">Volver al Panel</a>
                    <?php elseif (isset($proyecto)): ?>
                        <!-- Manage form for the selected project -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($proyecto['nombre']); ?></h3>
                                <div class="mb-3">
                                    <?php if (!empty($proyecto['foto']) && file_exists($proyecto['foto'])): ?>
                                        <img src="<?php echo $proyecto['foto']; ?>" alt="<?php echo htmlspecialchars($proyecto['nombre']); ?>" style="max-height: 150px;" class="img-thumbnail">
                                    <?php else: ?>
                                        <p class="text-muted">No hay imagen disponible</p>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($technologies)): ?>
                                    <div class="mb-3">
                                        <strong>Tecnologías:</strong>
                                        <ul class="list-inline">
                                            <?php foreach ($technologies as $tech): ?>
                                                <li class="list-inline-item badge bg-secondary"><?php echo htmlspecialchars($tech); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($proyecto['url'])): ?>
                                    <div class="mb-3">
                                        <strong>URL:</strong>
                                        <a href="<?php echo htmlspecialchars($proyecto['url']); ?>" target="_blank"><?php echo htmlspecialchars($proyecto['url']); ?></a>
                                    </div>
                                <?php endif; ?>

                                <p>
                                    <strong>Estado actual:</strong>
                                    <?php if ($proyecto['activo'] == 1): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </p>

                                <form method="post" class="mt-3">
                                    <input type="hidden" name="id" value="<?php echo $proyecto['id']; ?>">

                                    <?php if ($proyecto['activo'] == 1): ?>
                                        <input type="hidden" name="action" value="deactivate">
                                        <button type="submit" class="btn btn-danger">Desactivar Proyecto</button>
                                    <?php else: ?>
                                        <input type="hidden" name="action" value="activate">
                                        <button type="submit" class="btn btn-success">Activar Proyecto</button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="delete_project.php" class="btn btn-secondary">Volver a la Lista</a>
                            <a href="http://localhost:3000/php/panel_admin.php" class="btn btn-outline-primary">Volver al Panel</a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">Proyecto no encontrado.</div>
                        <div class="d-grid gap-2">
                            <a href="delete_project.php" class="btn btn-primary">Ver Lista de Proyectos</a>
                            <a href="http://localhost:3000/php/panel_admin.php" class="btn btn-outline-primary">Volver al Panel</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>