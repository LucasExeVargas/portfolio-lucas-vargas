<?php
require_once 'conexion.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);

    if (!empty($nombre) && isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        // Validar si el nombre ya existe en la base de datos
        $check_stmt = $conn->prepare("SELECT id FROM tecnologias WHERE nombre = ?");
        $check_stmt->bind_param("s", $nombre);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $mensaje = "Error: Ya existe una tecnología con ese nombre.";
        } else {
            if (!is_dir('img_tecnologias')) {
                mkdir('img_tecnologias', 0777, true);
            }

            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $filename = $nombre . '.' . strtolower($ext);
            $target_path = 'img_tecnologias/' . $filename;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_path)) {
                $relative_path = 'img_tecnologias/' . $filename;
                //Agrega el campo activo con valor por defecto 1 (activo)
                $stmt = $conn->prepare("INSERT INTO tecnologias (nombre, foto, activo) VALUES (?, ?, 1)");
                $stmt->bind_param("ss", $nombre, $relative_path);

                if ($stmt->execute()) {
                    $mensaje = "Tecnología agregada exitosamente.";
                } else {
                    $mensaje = "Error al insertar en base de datos: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $mensaje = "Error al mover el archivo.";
            }
        }
        $check_stmt->close();
    } else {
        $mensaje = "Debes completar todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Tecnología</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h2 class="mb-4 text-center">Agregar Tecnología</h2>

                    <?php if (isset($mensaje)): ?>
                        <div class="alert alert-info"><?php echo $mensaje; ?></div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Tecnología</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                        </div>
                        <div class="d-grid mb-3">

                            <button type="submit" class="btn btn-primary w-100">Guardar</button>
                        </div>

                        <div class="d-grid mb-3">
                            <a href="http://localhost:3000/php/panel_admin.php" class="btn btn-primary btn-lg" role="button">Volver</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>