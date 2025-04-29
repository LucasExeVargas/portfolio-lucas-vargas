<?php
require_once "conexion.php";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
  if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email'])) {
    echo "Debe completar los campos";
    exit;
  } else {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO usuarios(nombre, apellido, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $firstname, $lastname, $email);

    if ($stmt->execute()) {
      echo "Usuario agregado correctamente";
    } else {
      echo "Ocurrió un error al agregar el usuario";
    }

    $stmt->close();
    $con->close();
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/assets/styles/form.css" />
  <title>Formulario con PHP + EmailJS</title>
</head>

<body>

  <form id="contact-form" class="form" method="post">
    <div class="flex">
      <label>
        <input name="firstname" required type="text" class="input" />
        <span>Nombre</span>
      </label>

      <label>
        <input name="lastname" required type="text" class="input" />
        <span>Apellido</span>
      </label>
    </div>

    <label>
      <input name="email" required type="email" class="input" />
      <span>Email</span>
    </label>

    <label>
      <textarea name="message" required rows="3" class="input01"></textarea>
      <span>Mensaje</span>
    </label>

    <button type="submit" class="fancy">
      <span class="top-key"></span>
      <span class="text">Enviar</span>
      <span class="bottom-key-1"></span>
      <span class="bottom-key-2"></span>
    </button>
  </form>

  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
  <script>
    emailjs.init('4pVh9yp4Fi30cNVsA');

    const form = document.getElementById('contact-form');

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      const submitButton = form.querySelector('button[type="submit"]');
      const originalText = submitButton.querySelector('.text');
      const originalContent = originalText.textContent;

      // Bloquear y mostrar "Procesando..."
      submitButton.disabled = true;
      submitButton.classList.add('disabled');
      originalText.textContent = 'Procesando...';

      setTimeout(() => {
        // Restaurar botón
        submitButton.disabled = false;
        submitButton.classList.remove('disabled');
        originalText.textContent = originalContent;
      }, 6000);

      const formData = new FormData(form);
      formData.append('submit', '1');

      fetch(window.location.href, {
          method: 'POST',
          body: formData
        })
        .then(res => res.text())
        .then(res => {
          console.log(res);
          if (res.includes("Usuario agregado correctamente")) {
            emailjs.sendForm('service_k1ewwe6', 'template_yf8abgr', form)
              .then(() => {
                form.reset();
              }, (error) => {
                console.error('EmailJS error:', error);
              });
          } else {
            console.log(res);
          }
        })
        .catch(error => {
          console.error('Error al enviar a PHP:', error);
        });
    });
  </script>

</body>

</html>