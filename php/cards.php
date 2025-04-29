<?php
// Database connection parameters
require_once 'conexion.php';
// Set character set to UTF-8
$conn->set_charset("utf8");

// Query to get all projects with their associated technologies
$sql = "SELECT p.id, p.nombre, p.foto, p.url, 
               GROUP_CONCAT(t.id) as tech_ids, 
               GROUP_CONCAT(t.nombre) as tech_names, 
               GROUP_CONCAT(t.foto) as tech_fotos
        FROM proyectos p
        LEFT JOIN proyecto_tecnologia pt ON p.id = pt.proyecto_id
        LEFT JOIN tecnologias t ON pt.tecnologia_id = t.id
        GROUP BY p.id
        ORDER BY p.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/styles/cards.css" rel="stylesheet">
</head>

<body class="bg-light py-5 light-theme">
    <div class="container mt-5">
        <h1 class="text-center mb-5">Proyectos</h1>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Process technology data
                    $tech_ids = $row["tech_ids"] ? explode(',', $row["tech_ids"]) : [];
                    $tech_names = $row["tech_names"] ? explode(',', $row["tech_names"]) : [];
                    $tech_fotos = $row["tech_fotos"] ? explode(',', $row["tech_fotos"]) : [];

                    // Combine tech data into an array of objects
                    $technologies = [];
                    for ($i = 0; $i < count($tech_ids); $i++) {
                        if (isset($tech_ids[$i]) && isset($tech_names[$i]) && isset($tech_fotos[$i])) {
                            $technologies[] = [
                                'id' => $tech_ids[$i],
                                'nombre' => $tech_names[$i],
                                'foto' => $tech_fotos[$i]
                            ];
                        }
                    }
            ?>
                    <div class="col d-flex flex-column align-items-center text-center">
                        <div class="card-hover card border-0 shadow-sm">
                            <!-- Project Image Section -->
                            <div class="position-relative overflow-hidden">
                                <img class="project-image"
                                    src="<?php echo htmlspecialchars($row["foto"]); ?>"
                                    alt="<?php echo htmlspecialchars($row["nombre"]); ?>">
                            </div>

                            <!-- Project Info Section -->
                            <div class="card-body p-4">
                                <h2 class="card-title h5 fw-bold"><?php echo htmlspecialchars($row["nombre"]); ?></h2>

                                <!-- Technology Logos Section -->
                                <div class="mt-4">
                                    <h3 class="text-card small text-uppercase fw-semibold mb-3">Tecnologias Usadas</h3>
                                    <div class="d-flex flex-wrap gap-2 justify-content-center align-items-center border-top pt-3">
                                        <?php foreach ($technologies as $tech): ?>
                                            <div class="logo-hover" title="<?php echo htmlspecialchars($tech['nombre']); ?>">
                                                <img class="tech-logo"
                                                    src="<?php echo htmlspecialchars($tech['foto']); ?>"
                                                    alt="<?php echo htmlspecialchars($tech['nombre']); ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- View Project Button -->
                                <div class="mt-4">
                                    <a href="<?php echo htmlspecialchars($row["url"]); ?>"
                                        class="btn btn-primary w-100 fw-bold"
                                        target="_blank">
                                        GitHub
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<div class='col-12'><p class='text-center'>No projects found</p></div>";
            }
            ?>
        </div>
    </div>

    <!-- Theme Toggle Script -->
    <script>
        const toggleTheme = () => {
            document.body.classList.toggle('dark-theme');
            document.body.classList.toggle('light-theme');
        };
    </script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


<?php
// Close connection
$conn->close();
?>