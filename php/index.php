<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portfolio Lucas Vargas</title>
  <link rel="stylesheet" href="/assets/styles/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body>
  <div>

    <!-- Mobile Header -->
    <div class="d-md-none">
      <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerMobile"
            aria-controls="navbarTogglerMobile" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarTogglerMobile">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a href="#sobremi" class="select-nav text-decoration-none">
                  <p class="text-nav text-nav-dark fs-3 fw-medium m-0">Sobre mí</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#proyectos" class="nav-link select-nav text-decoration-none">
                  <p class="text-nav text-nav-dark fs-3 fw-medium m-0">Proyectos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#contacto" class="nav-link select-nav text-decoration-none">
                  <p class="text-nav text-nav-dark fs-3 fw-medium m-0">Contacto</p>
                </a>
              </li>
            </ul>
            <div style="padding-left: 1rem;">
              <label class="switch">
                <input checked="true" id="checkbox" type="checkbox" />
                <span class="slider">
                  <div class="star star_1"></div>
                  <div class="star star_2"></div>
                  <div class="star star_3"></div>
                  <svg viewBox="0 0 16 16" class="cloud_1 cloud">
                    <path transform="matrix(.77976 0 0 .78395-299.99-418.63)" fill="#fff"
                      d="m391.84 540.91c-.421-.329-.949-.524-1.523-.524-1.351 0-2.451 1.084-2.485 2.435-1.395.526-2.388 1.88-2.388 3.466 0 1.874 1.385 3.423 3.182 3.667v.034h12.73v-.006c1.775-.104 3.182-1.584 3.182-3.395 0-1.747-1.309-3.186-2.994-3.379.007-.106.011-.214.011-.322 0-2.707-2.271-4.901-5.072-4.901-2.073 0-3.856 1.202-4.643 2.925" />
                  </svg>
                </span>
              </label>
            </div>
          </div>
        </div>
      </nav>

    </div>

    <!-- Desktop Header -->
    <nav class="navbar d-none d-md-flex">
      <div class="header-container  align-items-center justify-content-between">

        <!-- Logo -->
        <div class="column-1">
          <figure class="figure-nav m-0">
            <img src="/assets/img/logo-ar.svg" class="img-ar" alt="Logo AR">
          </figure>
        </div>

        <!-- Navigation Links -->
        <ul class="nav-list d-flex list-unstyled mb-0">
          <li><a href="#sobremi" class="select-nav text-decoration-none">
              <p class="text-nav text-nav-dark fs-3 fw-medium m-0">Sobre mí</p>
            </a>
          </li>
          <li><a href="#proyectos" class="select-nav text-decoration-none">
              <p class="text-nav text-nav-dark fs-3 fw-medium m-0">Proyectos</p>
            </a>
          </li>
          <li><a href="#contacto" class="select-nav text-decoration-none">
              <p class="text-nav text-nav-dark fs-3 fw-medium m-0">Contacto</p>
            </a>
          </li>
        </ul>

        <!-- Dark Mode Switch -->
        <div class="column-3">
          <label class="switch">
            <input checked="true" id="checkbox" type="checkbox" />
            <span class="slider">
              <div class="star star_1"></div>
              <div class="star star_2"></div>
              <div class="star star_3"></div>
              <svg viewBox="0 0 16 16" class="cloud_1 cloud">
                <path transform="matrix(.77976 0 0 .78395-299.99-418.63)" fill="#fff"
                  d="m391.84 540.91c-.421-.329-.949-.524-1.523-.524-1.351 0-2.451 1.084-2.485 2.435-1.395.526-2.388 1.88-2.388 3.466 0 1.874 1.385 3.423 3.182 3.667v.034h12.73v-.006c1.775-.104 3.182-1.584 3.182-3.395 0-1.747-1.309-3.186-2.994-3.379.007-.106.011-.214.011-.322 0-2.707-2.271-4.901-5.072-4.901-2.073 0-3.856 1.202-4.643 2.925" />
              </svg>
            </span>
          </label>
        </div>
      </div>
    </nav>
  </div>
  <section>
    <div class="container">
      <div class="row">
        <div class="col-md-3 text-center">
        </div>
        <div class="col-md-3 text-center">
          <figure>
            <img src="/assets/img/yo-dia.svg" class="img-yo" alt="Descripción">
          </figure>
        </div>
        <div class="col-md-6 text-center d-flex flex-column justify-content-md-center align-items-md-center min-vh-md-100">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-md-8">
                <div class="row">
                  <div>
                    <p class="text-center fs-4 fw-bold text-nav text-nav-dark">HOLA, ME LLAMO</p>
                    <p class="text-center fs-3 fw-bold text-nav text-nav-dark">LUCAS EXEQUIEL VARGAS</p>
                  </div>
                </div>
                <div class="row mt-5">
                  <div class="col-6">
                    <button class="button type1 custom-animated-btn btn-cv" onclick="window.open('/assets/download/CV-LucasVargas.pdf', '_blank')"></button>
                  </div>
                  <div class="col-6">
                    <button class="button type1 custom-animated-btn btn-info"></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="py-5" id="sobremi">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 text-center ">
          <h2 class="mb-4">Sobre Mí</h2>
          <p class="lead text-nav text-nav-dark">
            Soy una persona apasionada por el aprendizaje constante y los nuevos desafíos. Me encanta explorar diferentes áreas del conocimiento y siempre busco maneras de mejorar, innovar y aportar valor en cada proyecto que emprendo. Mi enfoque combina creatividad, disciplina y entusiasmo por crecer tanto a nivel personal como profesional.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container mt-5">
      <div class="row align-items-center vh-md-100">
        <div class="col-md-6 d-flex justify-content-center mb-5  mb-md-0">
          <div class="card card-light">
            <div class="head head-light d-flex justify-content-center align-items-center">
              <p class=" text-nav-light m-0">Educación</p>
            </div>
            <div class="content content-light">
              <p class="text-nav text-nav-dark">
                Actualmente me encuentro cursando el tercer año de la Tecnicatura Universitaria en Programación en la UNSa.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 d-flex justify-content-center">
          <div class="card card-light">
            <div class="head head-light d-flex justify-content-center align-items-center">
              <p class=" text-nav-light m-0">Experiencia</p>
            </div>
            <div class="content content-light">
              <p class="text-nav text-nav-dark">
                Desarrollador Back-End especializado en aplicaciones de escritorio y Desarrollador Front-End enfocado en páginas estáticas.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="container mt-5">
    <h1 class="text-center mb-5">Estudios</h1>
    <div id="carouselExampleCaptions" class="carousel slide">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2" onclick="descargarPDF()"></button>
        <!-- <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3" onclick="descargarPDF()"></button> -->
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="/assets/img/unsa.png" class="d-block  carousel-image" alt="...">
          <div class="carousel-caption d-md-block">
            <h5>Universidad Nacional de Salta</h5>
            <p>Actualmente en curso.</p>
          </div>
        </div>
        <div class="carousel-item ">
          <img src="/assets/img/cac.png" class="d-block carousel-image" alt="...">
          <div class="carousel-caption d-md-block">
            <h5>Codo a Codo
              Full-Stack en Java
            </h5>
            <p>Finalizado</p>
          </div>
        </div>
        <!-- <div class="carousel-item">
          <img src="/assets/img/yo-noche.svg" class="d-block w-100" alt="...">
          <div class="carousel-caption d-md-block">
            <h5>Third slide label</h5>
            <p>Some representative placeholder content for the third slide.</p>
          </div>
        </div> -->
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <i class="bi bi-chevron-left text-light  fs-2"></i>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <i class="bi bi-chevron-right text-light fs-2"></i>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </section>

  <!-- <section class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div>
      <?php require_once "usuario.php"; ?>
    </div>
  </section> -->
  <section class="container justify-content-center align-items-center" id="proyectos">
    <div>
      <?php require_once "cards.php"; ?>
    </div>
  </section>
  <section>
    <div class="container">
      <?php require_once "footer.php"; ?>
    </div>
  </section>

  <script src="/assets/js/script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>

</html>