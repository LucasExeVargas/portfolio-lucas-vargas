<?php
require_once 'conexion.php';
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="mb-4 text-center">Panel de Administración</h2>
            <form method="post" class="text-center">
                <div class="d-grid gap-3">
                    <h3 class="mt-3 mb-2">Proyectos</h3>
                    <a href="http://localhost:3000/php/insert_data.php" class="btn btn-primary btn-lg" role="button">Nuevo Proyecto</a>
                    <a href="http://localhost:3000/php/edit_data.php" class="btn btn-warning btn-lg" role="button">Modificar Proyecto</a>
                    <a href="http://localhost:3000/php/delete_project.php" class="btn btn-danger btn-lg" role="button">Gestionar Estado de Proyectos</a>

                    <h3 class="mt-4 mb-2">Tecnologías</h3>
                    <a href="http://localhost:3000/php/insert_tech.php" class="btn btn-success btn-lg" role="button">Crear Nueva Tecnología</a>
                    <a href="http://localhost:3000/php/edit_tech.php" class="btn btn-info btn-lg" role="button">Modificar Tecnología</a>
                    <a href="http://localhost:3000/php/delete_tech.php" class="btn btn-danger btn-lg" role="button">Gestionar Estado de Tecnologías</a>
                </div>
                <div class="mt-4 border-top pt-4">
                    <a href="http://localhost:3000/php/index.php" class="btn btn-secondary btn-lg w-100" role="button">
                        <i class="bi bi-house-door"></i> Volver a la Página Principal
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>