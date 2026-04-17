<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actions</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


</head>

<body>


  <!-- CONTENIDO -->
  <div class="container py-5">

    <!-- HEADER CURSO -->
    <div class="mb-5">
      <h2 class="section-title">Campos de Acción</h2>
      <p class="text-muted">Acciones para registro de datos</p>

    <div class="row">

      <!-- LISTA DE ACCIONES -->
      <div class="col-lg-12">

        <h5 class="section-title mb-3">Contenido</h5>

        <div class="list-group">

          <a href=" {{ asset('newpeople') }} " class="list-group-item lesson-item d-flex justify-content-between align-items-center">
            <div>
              <strong class="p-4">1. Personas</strong>
            </div>
            <i class="fa fa-play-circle text-primary"></i>
          </a>

          <a href=" {{ asset('newuser') }} " class="list-group-item lesson-item d-flex justify-content-between align-items-center">
            <div>
              <strong class="p-4">2. Usuarios</strong>
            </div>
            <i class="fa fa-play-circle text-primary"></i>
          </a>

          <a href=" {{ asset('newroll') }} " class="list-group-item lesson-item d-flex justify-content-between align-items-center">
            <div>
              <strong class="p-4">3. Roles</strong>
            </div>
            <i class="fa fa-play-circle text-primary"></i>
          </a>

          <a href=" {{ asset('newspecialty') }} " class="list-group-item lesson-item d-flex justify-content-between align-items-center">
            <div>
              <strong class="p-4">4. Especialidades</strong>
            </div>
            <i class="fa fa-play-circle text-primary"></i>
          </a>

          <a href=" {{ asset('newuser_roll') }} " class="list-group-item lesson-item d-flex justify-content-between align-items-center">
            <div>
              <strong class="p-4">5. Roles de Usuario</strong>
            </div>
            <i class="fa fa-play-circle text-primary"></i>
          </a>

          <a href=" {{ asset('newcourse') }} " class="list-group-item lesson-item d-flex justify-content-between align-items-center">
            <div>
              <strong class="p-4">6. Cursos</strong>
            </div>
            <i class="fa fa-play-circle text-primary"></i>
          </a>

          <a href=" {{ asset('newtraining') }} " class="list-group-item lesson-item d-flex justify-content-between align-items-center">
            <div>
              <strong class="p-4">7. Capacitaciones</strong>
            </div>
            <i class="fa fa-play-circle text-primary"></i>
          </a>

          <a href=" {{ asset('newpay_method') }} " class="list-group-item lesson-item d-flex justify-content-between align-items-center">
            <div>
              <strong class="p-4">8. Métodos de Pago</strong>
            </div>
            <i class="fa fa-play-circle text-primary"></i>
          </a>

          <a href=" {{ asset('newregistration') }} " class="list-group-item lesson-item d-flex justify-content-between align-items-center">
            <div>
              <strong class="p-4">9. Matrículas</strong>
            </div>
            <i class="fa fa-play-circle text-primary"></i>
          </a>

        </div>
      </div>

      <!-- SIDEBAR -->
      <!-- <div class="col-lg-4 mt-4 mt-lg-0">

        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body">
            <h6 class="fw-bold">Detalles del curso</h6>
            <p class="text-muted small mb-2">Instructor: John Doe</p>
            <p class="text-muted small mb-2">Duración: 40 horas</p>
            <p class="text-muted small">Nivel: Principiante a avanzado</p>

            <button class="btn btn-success w-100 mt-3">
              <i class="fa fa-play me-2"></i> Continuar
            </button>
          </div>
        </div>

      </div> -->

    </div>


</body>

</html>