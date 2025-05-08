<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Gestión Disruptiva</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header class="site-header">
        <div class="header-content">
            <img src="assets/img/image.png" alt="Ralktech Logo" class="logo">
            <span class="brand-title">RALKTECH</span>
        </div>
    </header>
    <div class="container">
        <div class="card fade-in">
            <div class="card-header">
                <h1 class="card-title">Diagnóstico de Gestión Disruptiva</h1>
            </div>
            <div class="card-body">
                <form id="diagnosticoForm" method="POST" action="procesar_diagnostico.php">
                    <!-- Información General -->
                    <div class="diagnostic-section">
                        <h2 class="section-title">Información General</h2>
                        <div class="form-group">
                            <label for="fecha_diagnostico">Fecha de Diagnóstico</label>
                            <input type="date" id="fecha_diagnostico" name="fecha_diagnostico" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre_cliente">Nombre del Cliente</label>
                            <input type="text" id="nombre_cliente" name="nombre_cliente" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="industria">Industria</label>
                            <input type="text" id="industria" name="industria" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="tamano_empresa">Tamaño de la Empresa</label>
                            <select id="tamano_empresa" name="tamano_empresa" class="form-control" required>
                                <option value="">Seleccione...</option>
                                <option value="Micro">Micro (1-10 empleados)</option>
                                <option value="Pequeña">Pequeña (11-50 empleados)</option>
                                <option value="Mediana">Mediana (51-250 empleados)</option>
                                <option value="Grande">Grande (251+ empleados)</option>
                            </select>
                        </div>
                    </div>

                    <?php
                    // Obtener secciones y preguntas
                    try {
                        $stmt = $conn->query("SELECT * FROM secciones ORDER BY id");
                        while ($seccion = $stmt->fetch()) {
                            echo '<div class="diagnostic-section">';
                            echo '<h2 class="section-title">' . htmlspecialchars($seccion['nombre']) . '</h2>';
                            echo '<p>' . htmlspecialchars($seccion['descripcion']) . '</p>';
                            
                            // Obtener preguntas de esta sección
                            $stmtPreguntas = $conn->prepare("SELECT * FROM preguntas WHERE seccion_id = ? ORDER BY orden");
                            $stmtPreguntas->execute([$seccion['id']]);
                            
                            echo '<table class="table">';
                            echo '<thead><tr><th>Pregunta</th><th>Calificación</th><th>Observaciones</th></tr></thead>';
                            echo '<tbody>';
                            
                            while ($pregunta = $stmtPreguntas->fetch()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($pregunta['texto_pregunta']) . '</td>';
                                echo '<td>';
                                echo '<div class="rating">';
                                for ($i = 1; $i <= 5; $i++) {
                                    echo '<label class="rating-item">';
                                    echo '<input type="radio" name="calificacion_' . $pregunta['id'] . '" value="' . $i . '" required>';
                                    echo $i;
                                    echo '</label>';
                                }
                                echo '</div>';
                                echo '</td>';
                                echo '<td><textarea name="observacion_' . $pregunta['id'] . '" class="form-control" rows="2"></textarea></td>';
                                echo '</tr>';
                            }
                            
                            echo '</tbody></table>';
                            echo '</div>';
                        }
                    } catch (PDOException $e) {
                        echo '<div class="message message-error">Error al cargar el formulario: ' . $e->getMessage() . '</div>';
                    }
                    ?>

                    <!-- Recomendaciones y Plan de Acción -->
                    <div class="diagnostic-section">
                        <h2 class="section-title">Recomendaciones y Plan de Acción</h2>
                        <div class="form-group">
                            <label for="observaciones_generales">Observaciones Generales</label>
                            <textarea id="observaciones_generales" name="observaciones_generales" class="form-control" rows="4"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Recomendaciones Prioritarias</label>
                            <div id="recomendaciones">
                                <div class="recomendacion-item">
                                    <input type="text" name="recomendaciones[]" class="form-control" placeholder="Recomendación 1" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="agregarRecomendacion()">Agregar Recomendación</button>
                        </div>

                        <div class="form-group">
                            <label>Plan de Acción</label>
                            <div id="plan_accion">
                                <div class="plan-item">
                                    <input type="text" name="acciones[]" class="form-control" placeholder="Acción" required>
                                    <input type="text" name="responsables[]" class="form-control" placeholder="Responsable" required>
                                    <input type="date" name="fechas[]" class="form-control" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="agregarPlanAccion()">Agregar Acción</button>
                        </div>
                    </div>

                    <div class="text-center" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary">Guardar Diagnóstico</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function agregarRecomendacion() {
        const container = document.getElementById('recomendaciones');
        const count = container.children.length + 1;
        const div = document.createElement('div');
        div.className = 'recomendacion-item';
        div.innerHTML = `
            <input type="text" name="recomendaciones[]" class="form-control" placeholder="Recomendación ${count}" required>
        `;
        container.appendChild(div);
    }

    function agregarPlanAccion() {
        const container = document.getElementById('plan_accion');
        const div = document.createElement('div');
        div.className = 'plan-item';
        div.innerHTML = `
            <input type="text" name="acciones[]" class="form-control" placeholder="Acción" required>
            <input type="text" name="responsables[]" class="form-control" placeholder="Responsable" required>
            <input type="date" name="fechas[]" class="form-control" required>
        `;
        container.appendChild(div);
    }
    </script>
</body>
</html> 