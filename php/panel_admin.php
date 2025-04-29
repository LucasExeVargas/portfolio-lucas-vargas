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
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="mb-4 text-center">Panel de Administración</h2>
            <form method="post" class="text-center">
                <div class="d-grid gap-3">
                    <a href="http://localhost:3000/php/insert_data.php" class="btn btn-primary btn-lg" role="button">Nuevo Proyecto</a>
                    <a href="http://localhost:3000/php/edit_data.php" class="btn btn-warning btn-lg" role="button">Modificar Proyecto</a>
                    <a href="http://localhost:3000/php/insert_tech.php" class="btn btn-success btn-lg" role="button">Crear Nueva Tecnología</a>
                    <a href="http://localhost:3000/php/edit_tech.php" class="btn btn-info btn-lg" role="button">Modificar Tecnología</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>