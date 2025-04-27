<?php
require_once 'conexion.php';

// Definir variables
$projectId = $projectName = $githubLink = $currentImage = "";
$technologies = [];
$errors = [];
$success = false;
$projects = [];

// Obtener todos los proyectos para el dropdown
$projectsQuery = "SELECT id, nombre FROM proyectos ORDER BY nombre";
$projectsResult = $conn->query($projectsQuery);
if ($projectsResult->num_rows > 0) {
    while ($row = $projectsResult->fetch_assoc()) {
        $projects[] = $row;
    }
}

// Si se selecciona un proyecto, cargar sus datos
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['project_id']) && !empty($_GET['project_id'])) {
    $projectId = $_GET['project_id'];
    
    // Obtener datos del proyecto
    $projectQuery = "SELECT id, nombre, foto, url FROM proyectos WHERE id = ?";
    $stmt = $conn->prepare($projectQuery);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $projectData = $result->fetch_assoc();
        $projectName = $projectData['nombre'];
        $currentImage = $projectData['foto'];
        $githubLink = $projectData['url'];
        
        // Obtener tecnologías del proyecto
        $techQuery = "SELECT t.nombre FROM tecnologias t 
                      JOIN proyecto_tecnologia pt ON t.id = pt.tecnologia_id 
                      WHERE pt.proyecto_id = ?";
        $stmt = $conn->prepare($techQuery);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $techResult = $stmt->get_result();
        
        while ($techRow = $techResult->fetch_assoc()) {
            $technologies[] = $techRow['nombre'];
        }
    }
    $stmt->close();
}

// Procesar el envío del formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update']) && isset($_POST['projectId'])) {
    $projectId = $_POST['projectId'];
    
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

    // Validar enlace de GitHub (opcional)
    if (!empty($_POST["githubLink"])) {
        $githubLink = test_input($_POST["githubLink"]);
        if (!filter_var($githubLink, FILTER_VALIDATE_URL)) {
            $errors[] = "El enlace de GitHub no es válido.";
        }
    }

    // Manejar la imagen
    $uploadedFile = $_POST['currentImage']; // Mantener la imagen actual por defecto
    
    if (!empty($_FILES["projectImage"]["name"])) {
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
                
                // Eliminar la imagen anterior si es diferente
                if ($_POST['currentImage'] != $newFileName && !empty($_POST['currentImage'])) {
                    $oldImagePath = $targetDir . $_POST['currentImage'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            } else {
                $errors[] = "Lo sentimos, hubo un error al subir tu archivo.";
            }
        } else {
            $errors[] = "Solo se permiten archivos JPG, JPEG y PNG.";
        }
    }

    // Si no hay errores, actualizar
    if (empty($errors)) {
        // Actualizar el proyecto
        $updateProjectSql = "UPDATE proyectos SET nombre = ?, foto = ?, url = ? WHERE id = ?";
        $stmt = $conn->prepare($updateProjectSql);
        $stmt->bind_param("sssi", $projectName, $uploadedFile, $githubLink, $projectId);
        $stmt->execute();
        $stmt->close();

        // Eliminar las tecnologías actuales del proyecto
        $deleteTechSql = "DELETE FROM proyecto_tecnologia WHERE proyecto_id = ?";
        $stmt = $conn->prepare($deleteTechSql);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $stmt->close();

        // Insertar las nuevas tecnologías seleccionadas
        foreach ($technologies as $tech) {
            // Obtener el ID de la tecnología seleccionada
            $techIdQuery = "SELECT id FROM tecnologias WHERE nombre = ?";
            $stmt = $conn->prepare($techIdQuery);
            $stmt->bind_param("s", $tech);
            $stmt->execute();
            $techResult = $stmt->get_result();
            $techId = $techResult->fetch_assoc()['id'];
            $stmt->close();

            // Insertar la relación en la tabla 'proyecto_tecnologia'
            $insertTechSql = "INSERT INTO proyecto_tecnologia (proyecto_id, tecnologia_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insertTechSql);
            $stmt->bind_param("ii", $projectId, $techId);
            $stmt->execute();
            $stmt->close();
        }

        $success = true;
        
        // Recargar los datos del proyecto actualizado
        $projectQuery = "SELECT id, nombre, foto, url FROM proyectos WHERE id = ?";
        $stmt = $conn->prepare($projectQuery);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $projectData = $result->fetch_assoc();
            $projectName = $projectData['nombre'];
            $currentImage = $projectData['foto'];
            $githubLink = $projectData['url'];
        }
        $stmt->close();
        
        // Recargar tecnologías
        $technologies = [];
        $techQuery = "SELECT t.nombre FROM tecnologias t 
                      JOIN proyecto_tecnologia pt ON t.id = pt.tecnologia_id 
                      WHERE pt.proyecto_id = ?";
        $stmt = $conn->prepare($techQuery);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $techResult = $stmt->get_result();
        
        while ($techRow = $techResult->fetch_assoc()) {
            $technologies[] = $techRow['nombre'];
        }
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
    <title>Editar Proyecto</title>
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
        
        .current-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container">
        <div class="form-container bg-white">
            <h2 class="mb-4 text-center">Editar Proyecto</h2>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    ¡Proyecto actualizado exitosamente!
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
            
            <!-- Formulario para seleccionar proyecto -->
            <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-9">
                        <label for="project_id" class="form-label">Seleccionar Proyecto a Editar</label>
                        <select class="form-select" id="project_id" name="project_id" required>
                            <option value="">Seleccione un proyecto</option>
                            <?php foreach ($projects as $project): ?>
                                <option value="<?php echo $project['id']; ?>" <?php echo ($projectId == $project['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($project['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary w-100">Cargar Proyecto</button>
                    </div>
                </div>
            </form>

            <!-- Formulario para editar proyecto -->
            <?php if (!empty($projectId)): ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="projectId" value="<?php echo $projectId; ?>">
                    <input type="hidden" name="currentImage" value="<?php echo $currentImage; ?>">
                    
                    <div class="mb-3">
                        <label for="projectName" class="form-label required-field">Nombre del Proyecto</label>
                        <input type="text" class="form-control" id="projectName" name="projectName" value="<?php echo $projectName; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="technologies" class="form-label required-field">Tecnologías Utilizadas</label>
                        <select class="form-select" id="technologies" name="technologies[]" multiple="multiple" required>
                            <?php
                            $sql = "SELECT id, nombre FROM tecnologias";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                                    $selected = in_array($row['nombre'], $technologies) ? 'selected' : '';
                            ?>
                                    <option value="<?php echo htmlspecialchars($row['nombre']); ?>" <?php echo $selected; ?>>
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
                        <label for="projectImage" class="form-label">Imagen del Proyecto</label>
                        <?php if (!empty($currentImage)): ?>
                            <div>
                                <p>Imagen actual:</p>
                                <img src="img_proyectos/<?php echo $currentImage; ?>" alt="Imagen actual" class="current-image">
                            </div>
                            <p class="mt-2">Subir nueva imagen (opcional):</p>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="projectImage" name="projectImage" accept="image/png, image/jpeg">
                    </div>

                    <div class="mb-3">
                        <label for="githubLink" class="form-label">Enlace de GitHub (Opcional)</label>
                        <input type="url" class="form-control" id="githubLink" name="githubLink" value="<?php echo $githubLink; ?>" placeholder="https://github.com/usuario/repositorio">
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" name="update" class="btn btn-primary">Actualizar Proyecto</button>
                    </div>
                </form>
            <?php elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['project_id'])): ?>
                <div class="alert alert-warning">
                    No se encontró el proyecto seleccionado.
                </div>
            <?php endif; ?>
            
            <div class="d-grid mb-3">
                <a href="http://localhost:3000/php/panel_admin.php" class="btn btn-primary btn-lg" role="button">Volver</a>
            </div>
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
            
            $('#project_id').select2({
                placeholder: "Selecciona un proyecto para editar"
            });
        });
    </script>
</body>

</html>

<?php $conn->close(); ?>