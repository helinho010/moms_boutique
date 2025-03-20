<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acceso Denegado</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Estilos personalizados -->
  <style>
    body {
      background-color: #f8f9fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .access-denied-container {
      text-align: center;
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .access-denied-icon {
      font-size: 5rem;
      color: #dc3545; /* Rojo de Bootstrap */
      margin-bottom: 1rem;
    }
    .access-denied-title {
      font-size: 2rem;
      font-weight: bold;
      color: #343a40;
      margin-bottom: 1rem;
    }
    .access-denied-message {
      font-size: 1.2rem;
      color: #6c757d;
      margin-bottom: 2rem;
    }
    .btn-home {
      background-color: #007bff; /* Azul de Bootstrap */
      color: #ffffff;
      padding: 0.5rem 1.5rem;
      border-radius: 5px;
      text-decoration: none;
      font-size: 1rem;
    }
    .btn-home:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="access-denied-container">
    <!-- Ícono de acceso denegado -->
    <div class="access-denied-icon">
      <i class="bi bi-x-circle-fill"></i> <!-- Ícono de Bootstrap Icons -->
    </div>
    <!-- Título -->
    <h1 class="access-denied-title">Acceso Denegado</h1>
    <!-- Mensaje -->
    <p class="access-denied-message">
      No tienes permiso para acceder a esta página. Por favor, contacta al administrador si crees que esto es un error.
    </p>
    <p class="h3 text-uppercase">
      Su IP sera registrada y analizada por el departamento de soporte.
    </p>
    <!-- Botón para regresar al inicio -->
    {{-- <a href="/" class="btn-home">Volver al Inicio</a> --}}
  </div>

  <!-- Bootstrap JS y dependencias -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>