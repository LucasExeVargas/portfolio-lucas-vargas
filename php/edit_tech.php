<?php
require_once 'conexion.php';

$mensaje = '';

// Si tenemos un ID en la URL, cargamos esa tecnología
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    // Actualizar la consulta SELECT para incluir el campo activo
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
    // Si no se proporciona ningún ID ni una solicitud POST, mostrar todas las tecnologías.
    // Actualizar también la consulta para listar todas las tecnologías.
    $result = $conn->query("SELECT id, nombre, foto, activo FROM tecnologias WHERE activo = 1 ORDER BY nombre");
    $tecnologias = $result->fetch_all(MYSQLI_ASSOC);
}

// Gestionar el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['nombre'])) {
    $id = $_POST['id'];
    $nombre = trim($_POST['nombre']);
    $nombre_original = $_POST['nombre_original'];

    // Verificamos si necesitamos validar el nombre (solo si cambió)
    $nombre_changed = ($nombre != $nombre_original);

    if (!empty($nombre)) {
        // Si el nombre cambió, verificar si ya existe
        if ($nombre_changed) {
            $check_stmt = $conn->prepare("SELECT id FROM tecnologias WHERE nombre = ? AND id != ?");
            $check_stmt->bind_param("si", $nombre, $id);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                $mensaje = "Error: Ya existe otra tecnología con ese nombre.";
                $check_stmt->close();
                // Recargar la tecnología actual
                $stmt = $conn->prepare("SELECT id, nombre, foto, activo FROM tecnologias WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $tecnologia = $result->fetch_assoc();
                $stmt->close();
            } else {
                $check_stmt->close();
                $update_image = false;
                $relative_path = '';

                //Comprueba si se ha cargado una nueva imagen
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                    if (!is_dir('img_tecnologias')) {
                        mkdir('img_tecnologias', 0777, true);
                    }

                    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                    $filename = $nombre . '.' . strtolower($ext);
                    $target_path = 'img_tecnologias/' . $filename;

                    // Obtener la ruta de la imagen antigua para eliminarla más tarde
                    $stmt = $conn->prepare("SELECT foto FROM tecnologias WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $old_image = $result->fetch_assoc()['foto'];
                    $stmt->close();

                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_path)) {
                        $relative_path = 'img_tecnologias/' . $filename;
                        $update_image = true;

                        //Eliminar la imagen antigua si existe y es diferente
                        if (!empty($old_image) && $old_image != $relative_path && file_exists($old_image)) {
                            unlink($old_image);
                        }
                    } else {
                        $mensaje = "Error al subir la nueva imagen.";
                    }
                }

                // Actualizar la base de datos
                if (empty($mensaje)) {
                    if ($update_image) {
                        $stmt = $conn->prepare("UPDATE tecnologias SET nombre = ?, foto = ? WHERE id = ?");
                        $stmt->bind_param("ssi", $nombre, $relative_path, $id);
                    } else {
                        $stmt = $conn->prepare("UPDATE tecnologias SET nombre = ? WHERE id = ?");
                        $stmt->bind_param("si", $nombre, $id);
                    }

                    if ($stmt->execute()) {
                        $mensaje = "Tecnología actualizada exitosamente.";

                        // Si el nombre cambió pero la imagen no se cargó, cambie el nombre del archivo de imagen
                        if ($nombre_changed && !$update_image) {
                            $stmt = $conn->prepare("SELECT foto FROM tecnologias WHERE id = ?");
                            $stmt->bind_param("i", $id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $current_image = $result->fetch_assoc()['foto'];

                            if (!empty($current_image) && file_exists($current_image)) {
                                $path_info = pathinfo($current_image);
                                $new_filename = 'img_tecnologias/' . $nombre . '.' . $path_info['extension'];

                                if (rename($current_image, $new_filename)) {
                                    // Actualizar la base de datos con el nuevo nombre de archivo
                                    $stmt = $conn->prepare("UPDATE tecnologias SET foto = ? WHERE id = ?");
                                    $stmt->bind_param("si", $new_filename, $id);
                                    $stmt->execute();
                                }
                            }
                        }

                        // Recargue la tecnología actual con información actualizada
                        $stmt = $conn->prepare("SELECT id, nombre, foto, activo FROM tecnologias WHERE id = ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $tecnologia = $result->fetch_assoc();
                    } else {
                        $mensaje = "Error al actualizar en base de datos: " . $stmt->error;
                    }

                    $stmt->close();
                }
            }
        } else {
            // El nombre no cambió, solo verificamos si necesitamos actualizar la imagen
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                if (!is_dir('img_tecnologias')) {
                    mkdir('img_tecnologias', 0777, true);
                }

                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $filename = $nombre . '.' . strtolower($ext);
                $target_path = 'img_tecnologias/' . $filename;

                // Obtener la ruta de la imagen antigua
                $stmt = $conn->prepare("SELECT foto FROM tecnologias WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $old_image = $result->fetch_assoc()['foto'];
                $stmt->close();

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_path)) {
                    $relative_path = 'img_tecnologias/' . $filename;

                    //Eliminar la imagen antigua si existe y es diferente
                    if (!empty($old_image) && $old_image != $relative_path && file_exists($old_image)) {
                        unlink($old_image);
                    }

                    //Actualizar solo la imagen
                    $stmt = $conn->prepare("UPDATE tecnologias SET foto = ? WHERE id = ?");
                    $stmt->bind_param("si", $relative_path, $id);

                    if ($stmt->execute()) {
                        $mensaje = "Imagen actualizada exitosamente.";

                        // Recargar la tecnología actual
                        $stmt = $conn->prepare("SELECT id, nombre, foto, activo FROM tecnologias WHERE id = ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $tecnologia = $result->fetch_assoc();
                    } else {
                        $mensaje = "Error al actualizar la imagen en base de datos: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    $mensaje = "Error al subir la nueva imagen.";
                }
            } else {
                $mensaje = "No se realizaron cambios.";
            }
        }
    } else {
        $mensaje = "El nombre no puede estar vacío.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Tecnología</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow p-4">
                    <h2 class="mb-4 text-center">Editar Tecnología</h2>

                    <?php if (isset($mensaje) && !empty($mensaje)): ?>
                        <div class="alert alert-info"><?php echo $mensaje; ?></div>
                    <?php endif; ?>

                    <?php if (isset($tecnologias)): ?>
                        <!-- Lista de tecnologías a seleccionar para editar -->
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
                                                <a href="?id=<?php echo $tech['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="http://localhost:3000/php/panel_admin.php" class="btn btn-outline-primary">Volver al Panel</a>
                    <?php elseif (isset($tecnologia)): ?>
                        <!-- Editar formulario para la tecnología seleccionada -->
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $tecnologia['id']; ?>">
                            <input type="hidden" name="nombre_original" value="<?php echo htmlspecialchars($tecnologia['nombre']); ?>">

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre de la Tecnología</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($tecnologia['nombre']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Actual</label>
                                <div class="mb-2">
                                    <?php if (!empty($tecnologia['foto']) && file_exists($tecnologia['foto'])): ?>
                                        <img src="<?php echo $tecnologia['foto']; ?>" alt="<?php echo htmlspecialchars($tecnologia['nombre']); ?>" style="max-height: 150px;" class="img-thumbnail">
                                    <?php else: ?>
                                        <p class="text-muted">No hay imagen disponible</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Nueva Foto (opcional)</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <div class="form-text">Deja este campo vacío si no deseas cambiar la imagen.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <div>
                                    <?php if ($tecnologia['activo'] == 1): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                    <div class="form-text">Para cambiar el estado, use la opción "Gestionar Estado de Tecnologías" en el panel de administración.</div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                <a href="edit_tech.php" class="btn btn-secondary">Volver a la Lista</a>
                                class="btn btn-secondary">Volver a la Lista</a>
                                <a href="http://localhost:3000/php/panel_admin.php" class="btn btn-outline-primary">Volver al Panel</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">Tecnología no encontrada.</div>
                        <div class="d-grid gap-2">
                            <a href="edit_tech.php" class="btn btn-primary">Ver Lista de Tecnologías</a>
                            <a href="http://localhost:3000/php/panel_admin.php" class="btn btn-outline-primary">Volver al Panel</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>