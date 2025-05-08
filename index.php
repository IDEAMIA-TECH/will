<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Gestión Disruptiva</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-chart-line me-2"></i>
                Diagnóstico Disruptivo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="diagnosticos.php">Diagnósticos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reportes.php">Reportes</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">
                            <i class="fas fa-clipboard-check text-primary me-2"></i>
                            Nuevo Diagnóstico
                        </h2>
                        
                        <!-- Formulario de Diagnóstico -->
                        <form id="diagnosticoForm" action="procesar_diagnostico.php" method="POST">
                            <!-- Información General -->
                            <div class="mb-4">
                                <h4 class="border-bottom pb-2">Información General</h4>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Fecha de Diagnóstico</label>
                                        <input type="date" class="form-control" name="fecha_diagnostico" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre del Cliente</label>
                                        <input type="text" class="form-control" name="nombre_cliente" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Industria</label>
                                        <input type="text" class="form-control" name="industria">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tamaño de la Empresa</label>
                                        <select class="form-select" name="tamano_empresa">
                                            <option value="pequeña">Pequeña</option>
                                            <option value="mediana">Mediana</option>
                                            <option value="grande">Grande</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Secciones del Diagnóstico -->
                            <div id="seccionesDiagnostico">
                                <!-- Las secciones se cargarán dinámicamente con JavaScript -->
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="button" class="btn btn-outline-secondary me-md-2" onclick="guardarBorrador()">
                                    <i class="fas fa-save me-2"></i>Guardar Borrador
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check me-2"></i>Finalizar Diagnóstico
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Diagnóstico de Gestión Disruptiva</h5>
                    <p class="mb-0">Herramienta de evaluación para organizaciones</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2024 Todos los derechos reservados</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html> 