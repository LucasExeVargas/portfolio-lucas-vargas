<?php
require_once "conexion.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/styles/cards.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>

    <footer style="margin-top: 20rem;" id="contacto">
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center ">
                        <h2 class="mb-4">Contactame</h2>
                    </div>
                </div>
            </div>
        </section>
        <div class="container">
            <div class="">
                <div class="row">
                    <div class="col-md-3 text-center">

                    </div>
                    <div class="col-md-3">
                        <?php include 'usuario.php'; ?>
                    </div>

                    <!-- Sección derecha: Redes sociales -->
                    <div class="col-md-6 text-center d-flex justify-content-md-center align-items-md-center min-vh-md-100">
                        <a href="https://wa.me/+5493875220380" target="_blank" class="text-dark me-5">
                            <i class="bi bi-whatsapp fs-2"></i>
                        </a>
                        <a href="https://github.com/LucasExeVargas" target=" _blank" class="text-dark me-5">
                            <i class="bi bi-github fs-2"></i>
                        </a>
                        <a href="https://www.instagram.com/vargas.lucas___" target="_blank" class="text-dark me-5">
                            <i class="bi bi-instagram fs-2"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/lucas-vargas-a75268274" target="_blank" class="text-dark">
                            <i class="bi bi-linkedin fs-2"></i>
                        </a>
                    </div>
                </div>
                <!-- Sección izquierda: Incluye usuario.php -->

            </div>
        </div>
    </footer>
</body>

</html>