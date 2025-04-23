

<?php
require "conexion.php";
if(isset($_POST['addnew'])){
    if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email'])){
        echo "Debe completar los campos";
    }else{
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $activ = true;
        $sql = "INSERT INTO usuarios(nombre,apellido,email,activo)VALUES('$firstname','$lastname','$email','$activ')";
        if($con->query($sql)=== TRUE){
            echo "<div class = 'alert-success'>Usuario agregado correctamente </div>";
        }else{
            echo "<div class = 'alert-danger'>Ocurrio un error mientras se agregaba el usuario</div>";
        }
    }
}else{
  echo "HOLA";
}
?>

<section>
    <div class="containter">
      <div class="col-md-6">
        <div class="formbox">
          <form action="" method="post">
            <p class="text-nav text-nav-dark title-form">Fromulario</p>
            <p class="text-nav text-nav-dark lable-text">Nombre</p>
            <input name="firstname" placeholder="Nombre Completo" type="text" class="input">
            <p class="text-nav text-nav-dark lable-text">Apellido</p>
            <input name="lastname" placeholder="Nombre Completo" type="text" class="input">
            <p class="text-nav text-nav-dark lable-text">Email</p>
            <input name="email" placeholder="Email" type="email" class="input">
            <p class="text-nav text-nav-dark lable-text">Feedback</p>
            <textarea style="width: 100%;"></textarea>
            <div class="btn-feedback">
              <input name="addnew" type="submit">Enviar Feedback
            </div>
          </form>
        </div>
      </div>
    </div>
</section>