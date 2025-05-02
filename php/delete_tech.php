<?php
require_once 'conexion.php';

// Variable to store messages
$mensaje = '';

// If we have an ID in the URL, load that technology
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, nombre, foto, activo FROM tecnologias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tecnologia = $result->fetch_assoc();
    } else {
        $mensaje = "Tecnología no encontrada.";
    }
    $stmt->close();
} elseif (!isset($_POST['id'])) {
    // If no ID is provided and not a POST request, show all technologies
    $result = $conn->query("SELECT id, nombre, foto, activo FROM tecnologias ORDER BY nombre");
    $tecnologias = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle form submission for soft delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Update the activo status (0 = inactive, 1 = active)
    $new_status = ($_POST['action'] == 'activate') ? 1 : 0;
    
    $stmt = $conn->prepare("UPDATE tecnologias SET activo = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $id);
    
    if ($stmt->execute()) {
        $action_text = ($new_status == 1) ? "activada" : "desactivada";
        $mensaje = "Tecnología {$action_text} exitosamente.";
        
        // Reload the technologies list
        $result = $conn->query("SELECT id, nombre, foto, activo FROM tecnologias ORDER BY nombre");
        $tecnologias = $result->fetch_all(MYSQLI_ASSOC);
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
    <title>Gestionar Tecnologías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow p-4">
                    <h2 class="mb-4 text-center">Gestionar Tecnologías</h2>

                    <?php if (isset($mensaje) && !empty($mensaje)): ?>
                        <div class="alert alert-info"><?php echo $mensaje; ?></div>
                    <?php endif; ?>

                    <?php if (isset($tecnologias)): ?>
                        <!-- List of technologies -->
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
                                    <?php foreach ($tecnologias as $tech): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($tech['nombre']); ?></td>
                                            <td>
                                                <?php if (!empty($tech['foto']) && file_exists($tech['foto'])): ?>
                                                    <img src="<?php echo $tech['foto']; ?>" alt="<?php echo htmlspecialchars($tech['nombre']); ?>" style="max-height: 50px;">
                                                <?php else: ?>
                                                    <span class="text-muted">Sin imagen</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($tech['activo'] == 1): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form method="post" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo $tech['id']; ?>">
                                                    <?php if ($tech['activo'] == 1): ?>
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
                    <?php elseif (isset($tecnologia)): ?>
                        <!-- Manage form for the selected technology -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($tecnologia['nombre']); ?></h3>
                                <div class="mb-3">
                                    <?php if (!empty($tecnologia['foto']) && file_exists($tecnologia['foto'])): ?>
                                        <img src="<?php echo $tecnologia['foto']; ?>" alt="<?php echo htmlspecialchars($tecnologia['nombre']); ?>" style="max-height: 150px;" class="img-thumbnail">
                                    <?php else: ?>
                                        <p class="text-muted">No hay imagen disponible</p>
                                    <?php endif; ?>
                                </div>
                                <p>
                                    <strong>Estado actual:</strong>
                                    <?php if ($tecnologia['activo'] == 1): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </p>
                                
                                <form method="post" class="mt-3">
                                    <input type="hidden" name="id" value="<?php echo $tecnologia['id']; ?>">
                                    
                                    <?php if ($tecnologia['activo'] == 1): ?>
                                        <input type="hidden" name="action" value="deactivate">
                                        <button type="submit" class="btn btn-danger">Desactivar Tecnología</button>
                                    <?php else: ?>
                                        <input type="hidden" name="action" value="activate">
                                        <button type="submit" class="btn btn-success">Activar Tecnología</button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="delete_tech.php" class="btn btn-secondary">Volver a la Lista</a>
                            <a href="http://localhost:3000/php/panel_admin.php" class="btn btn-outline-primary">Volver al Panel</a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">Tecnología no encontrada.</div>
                        <div class="d-grid gap-2">
                            <a href="delete_tech.php" class="btn btn-primary">Ver Lista de Tecnologías</a>
                            <a href="http://localhost:3000/php/panel_admin.php" class="btn btn-outline-primary">Volver al Panel</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
