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
  <link rel="stylesheet" href="/assets/styles/cartel.css">
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

  <!-- Cartel de agradecimiento -->
  <div id="agradecimiento-cartel" class="cartel-overlay">
    <div class="cartel-container">
      <div class="cartel-header">
        <h3>¡Gracias por contactarme!</h3>
        <button class="cartel-close" id="cartel-close">&times;</button>
      </div>
      <div class="cartel-body">
        <p>He recibido tu mensaje correctamente.</p>
        <p>Me pondré en contacto contigo lo antes posible.</p>
      </div>
      <div class="cartel-footer">
        <button class="cartel-button" id="cartel-ok">Aceptar</button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
  <script>
    emailjs.init('4pVh9yp4Fi30cNVsA');

    const form = document.getElementById('contact-form');
    const cartel = document.getElementById('agradecimiento-cartel');
    const closeBtn = document.getElementById('cartel-close');
    const okBtn = document.getElementById('cartel-ok');

    // Función para cerrar el cartel
    function closeCartel() {
      cartel.classList.remove("show");
    }

    // Eventos para cerrar el cartel
    closeBtn.addEventListener("click", closeCartel);
    okBtn.addEventListener("click", closeCartel);
    
    // También cerrar el cartel si se hace clic fuera de él
    window.addEventListener("click", function(event) {
      if (event.target === cartel) {
        closeCartel();
      }
    });

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      const submitButton = form.querySelector('button[type="submit"]');
      const originalText = submitButton.querySelector('.text');
      const originalContent = originalText.textContent;

      // Bloquear y mostrar "Procesando..."
      submitButton.disabled = true;
      submitButton.classList.add('disabled');
      originalText.textContent = 'Procesando...';

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
                // Mostrar el cartel de agradecimiento
                cartel.classList.add("show");
                
                // Restaurar el botón y resetear el formulario
                submitButton.disabled = false;
                submitButton.classList.remove('disabled');
                originalText.textContent = originalContent;
                form.reset();
              }, (error) => {
                console.error('EmailJS error:', error);
                submitButton.disabled = false;
                submitButton.classList.remove('disabled');
                originalText.textContent = originalContent;
              });
          } else {
            console.log(res);
            submitButton.disabled = false;
            submitButton.classList.remove('disabled');
            originalText.textContent = originalContent;
          }
        })
        .catch(error => {
          console.error('Error al enviar a PHP:', error);
          submitButton.disabled = false;
          submitButton.classList.remove('disabled');
          originalText.textContent = originalContent;
        });
    });
  </script>

</body>

</html>