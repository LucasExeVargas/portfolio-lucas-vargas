<?php
require_once 'conexion.php';

// Definir variables
$projectName = $githubLink = "";
$technologies = [];
$errors = [];
$success = false;
$uploadedFile = "";

// Procesar el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['projectName']) && isset($_POST['technologies']) && isset($_FILES["projectImage"]["name"])) {
    // Validar nombre del proyecto
    if (empty($_POST["projectName"])) {
        $errors[] = "El nombre del proyecto es obligatorio.";
    } else {
        $projectName = test_input($_POST["projectName"]);
        $safeProjectName = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '_', $projectName));
    }

    // Validar tecnologías
    if (empty($_POST["technologies"])) {
        $errors[] = "Debe seleccionar al menos una tecnología.";
    } else {
        $technologies = $_POST["technologies"];
    }

    // Validar subida de imagen
    if (empty($_FILES["projectImage"]["name"])) {
        $errors[] = "La imagen del proyecto es obligatoria.";
    } else {
        $targetDir = "img_proyectos/";

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileType = strtolower(pathinfo($_FILES["projectImage"]["name"], PATHINFO_EXTENSION));
        $newFileName = $safeProjectName . "." . $fileType;
        $targetFilePath = $targetDir . $newFileName;

        $allowTypes = array('jpg', 'png', 'jpeg');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["projectImage"]["tmp_name"], $targetFilePath)) {
                $uploadedFile = $newFileName;
            } else {
                $errors[] = "Lo sentimos, hubo un error al subir tu archivo.";
            }
        } else {
            $errors[] = "Solo se permiten archivos JPG, JPEG y PNG.";
        }
    }

    // Validar enlace de GitHub (opcional)
    if (!empty($_POST["githubLink"])) {
        $githubLink = test_input($_POST["githubLink"]);
        if (!filter_var($githubLink, FILTER_VALIDATE_URL)) {
            $errors[] = "El enlace de GitHub no es válido.";
        }
    }

    // Si no hay errores, procesar
    if (empty($errors)) {
        // Insertar el nuevo proyecto
        $insertProjectSql = "INSERT INTO proyectos (nombre, foto, url) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertProjectSql);
        $stmt->bind_param("sss", $projectName, $targetFilePath, $githubLink);
        $stmt->execute();
        $projectId = $stmt->insert_id; // Obtener el ID del nuevo proyecto insertado
        $stmt->close();

        // Insertar las tecnologías asociadas al nuevo proyecto en la tabla 'proyecto_tecnologia'
        foreach ($technologies as $tech) {
            // Obtener el ID de la tecnología seleccionada
            $techId = $conn->query("SELECT id FROM tecnologias WHERE nombre = '$tech'")->fetch_assoc()['id'];

            // Insertar la relación en la tabla 'proyecto_tecnologia'
            $insertTechSql = "INSERT INTO proyecto_tecnologia (proyecto_id, tecnologia_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insertTechSql);
            $stmt->bind_param("ii", $projectId, $techId);
            $stmt->execute();
            $stmt->close();
        }

        $success = true;
        // Resetear los campos del formulario
        $projectName = "";
        $technologies = [];
        $githubLink = "";
    }
}

// Función para sanitizar datos
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
        }

        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container">
        <div class="form-container bg-white">
            <h2 class="mb-4 text-center">Nuevo Proyecto</h2>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    ¡Proyecto enviado exitosamente! Imagen guardada como: <?php echo $uploadedFile; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="projectName" class="form-label required-field">Nombre del Proyecto</label>
                    <input type="text" class="form-control" id="projectName" name="projectName" value="<?php echo $projectName; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="technologies" class="form-label required-field">Tecnologías Utilizadas</label>
                    <select class="form-select" id="technologies" name="technologies[]" multiple="multiple" required>
                        <?php
                        // Modificar la consulta SQL para seleccionar solo tecnologías activas
                        $sql = "SELECT id, nombre FROM tecnologias WHERE activo = 1";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                        ?>
                                <option value="<?php echo htmlspecialchars($row['nombre']); ?>">
                                    <?php echo htmlspecialchars($row['nombre']); ?>
                                </option>
                            <?php
                            endwhile;
                        else:
                            ?>
                            <option value="">No hay tecnologías disponibles</option>
                        <?php
                        endif;
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="projectImage" class="form-label required-field">Imagen del Proyecto</label>
                    <input type="file" class="form-control" id="projectImage" name="projectImage" required accept="image/png, image/jpeg">
                </div>

                <div class="mb-3">
                    <label for="githubLink" class="form-label">Enlace de GitHub (Opcional)</label>
                    <input type="url" class="form-control" id="githubLink" name="githubLink" value="<?php echo $githubLink; ?>" placeholder="https://github.com/usuario/repositorio">
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Enviar Proyecto</button>
                </div>
                <div class="d-grid mb-3">
                    <a href="http://localhost:3000/php/panel_admin.php" class="btn btn-primary btn-lg" role="button">Volver</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#technologies').select2({
                placeholder: "Selecciona las tecnologías utilizadas",
                allowClear: true
            });
        });
    </script>
</body>

</html>

<?php $conn->close(); ?>