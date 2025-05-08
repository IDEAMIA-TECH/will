<?php
require_once 'database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación - Diagnóstico de Gestión Disruptiva</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="card fade-in">
            <div class="card-header">
                <h1 class="card-title">Instalación del Sistema</h1>
            </div>
            <div class="card-body">
                <?php
                // Función para mostrar mensajes de error o éxito
                function mostrarMensaje($mensaje, $tipo = 'success') {
                    echo "<div class='message message-" . $tipo . " fade-in'>$mensaje</div>";
                }

                try {
                    // Verificar conexión
                    if ($conn) {
                        mostrarMensaje("Conexión a la base de datos establecida correctamente.");
                    }

                    // Crear tablas una por una
                    $queries = [
                        // Tabla secciones
                        "CREATE TABLE IF NOT EXISTS secciones (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            nombre VARCHAR(100) NOT NULL,
                            descripcion TEXT,
                            puntuacion_maxima INT DEFAULT 20,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        )",
                        
                        // Tabla preguntas
                        "CREATE TABLE IF NOT EXISTS preguntas (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            seccion_id INT,
                            texto_pregunta TEXT NOT NULL,
                            orden INT NOT NULL,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            FOREIGN KEY (seccion_id) REFERENCES secciones(id)
                        )",
                        
                        // Tabla diagnosticos
                        "CREATE TABLE IF NOT EXISTS diagnosticos (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            fecha_diagnostico DATE NOT NULL,
                            nombre_cliente VARCHAR(255) NOT NULL,
                            industria VARCHAR(100),
                            tamano_empresa VARCHAR(50),
                            puntuacion_total INT,
                            porcentaje_implementacion DECIMAL(5,2),
                            observaciones_generales TEXT,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                        )",
                        
                        // Tabla respuestas
                        "CREATE TABLE IF NOT EXISTS respuestas (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            diagnostico_id INT,
                            pregunta_id INT,
                            calificacion INT CHECK (calificacion BETWEEN 1 AND 5),
                            observaciones TEXT,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(id),
                            FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
                        )",
                        
                        // Tabla recomendaciones
                        "CREATE TABLE IF NOT EXISTS recomendaciones (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            diagnostico_id INT,
                            texto_recomendacion TEXT NOT NULL,
                            prioridad INT CHECK (prioridad BETWEEN 1 AND 3),
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(id)
                        )",
                        
                        // Tabla plan_accion
                        "CREATE TABLE IF NOT EXISTS plan_accion (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            diagnostico_id INT,
                            accion TEXT NOT NULL,
                            responsable VARCHAR(255),
                            fecha_limite DATE,
                            estado VARCHAR(50),
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                            FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(id)
                        )"
                    ];

                    // Ejecutar cada consulta de creación de tabla
                    foreach ($queries as $query) {
                        try {
                            $conn->exec($query);
                            mostrarMensaje("Tabla creada exitosamente: " . substr($query, 17, 20) . "...");
                        } catch (PDOException $e) {
                            mostrarMensaje("Error al crear tabla: " . $e->getMessage(), 'error');
                        }
                    }

                    // Insertar datos iniciales
                    try {
                        // Insertar secciones
                        $secciones = [
                            ['Liderazgo y Cultura', 'Evaluación del liderazgo y la cultura organizacional'],
                            ['Estrategia y Visión', 'Evaluación de la estrategia y visión de la organización'],
                            ['Tecnología e Innovación', 'Evaluación de capacidades tecnológicas e innovación'],
                            ['Talento y Organización', 'Evaluación del talento y estructura organizacional'],
                            ['Operaciones y Procesos', 'Evaluación de operaciones y procesos']
                        ];

                        $stmt = $conn->prepare("INSERT INTO secciones (nombre, descripcion) VALUES (?, ?)");
                        foreach ($secciones as $seccion) {
                            $stmt->execute($seccion);
                        }
                        mostrarMensaje("Secciones insertadas correctamente");

                        // Insertar preguntas
                        $preguntas = [
                            // Liderazgo y Cultura
                            [1, 'Liderazgo adaptativo', 1],
                            [1, 'Cultura de innovación', 2],
                            [1, 'Tolerancia al riesgo', 3],
                            [1, 'Mentalidad de crecimiento', 4],
                            
                            // Estrategia y Visión
                            [2, 'Visión clara de futuro', 1],
                            [2, 'Estrategia digital', 2],
                            [2, 'Adaptabilidad al cambio', 3],
                            [2, 'Planificación disruptiva', 4],
                            
                            // Tecnología e Innovación
                            [3, 'Infraestructura tecnológica', 1],
                            [3, 'Procesos de innovación', 2],
                            [3, 'Adopción de nuevas tecnologías', 3],
                            [3, 'Transformación digital', 4],
                            
                            // Talento y Organización
                            [4, 'Gestión del talento', 1],
                            [4, 'Estructura organizacional', 2],
                            [4, 'Desarrollo de capacidades', 3],
                            [4, 'Cultura de aprendizaje', 4],
                            
                            // Operaciones y Procesos
                            [5, 'Eficiencia operativa', 1],
                            [5, 'Procesos ágiles', 2],
                            [5, 'Automatización', 3],
                            [5, 'Gestión de calidad', 4]
                        ];

                        $stmt = $conn->prepare("INSERT INTO preguntas (seccion_id, texto_pregunta, orden) VALUES (?, ?, ?)");
                        foreach ($preguntas as $pregunta) {
                            $stmt->execute($pregunta);
                        }
                        mostrarMensaje("Preguntas insertadas correctamente");

                        // Crear índices
                        $indices = [
                            "CREATE INDEX idx_diagnosticos_fecha ON diagnosticos(fecha_diagnostico)",
                            "CREATE INDEX idx_respuestas_diagnostico ON respuestas(diagnostico_id)",
                            "CREATE INDEX idx_respuestas_pregunta ON respuestas(pregunta_id)",
                            "CREATE INDEX idx_plan_accion_diagnostico ON plan_accion(diagnostico_id)",
                            "CREATE INDEX idx_recomendaciones_diagnostico ON recomendaciones(diagnostico_id)"
                        ];

                        foreach ($indices as $indice) {
                            try {
                                $conn->exec($indice);
                                mostrarMensaje("Índice creado exitosamente");
                            } catch (PDOException $e) {
                                // Ignorar error si el índice ya existe
                                if ($e->getCode() != '42000') {
                                    mostrarMensaje("Error al crear índice: " . $e->getMessage(), 'error');
                                }
                            }
                        }

                    } catch (PDOException $e) {
                        mostrarMensaje("Error al insertar datos iniciales: " . $e->getMessage(), 'error');
                    }

                    // Verificar la instalación
                    $tables = ['secciones', 'preguntas', 'diagnosticos', 'respuestas', 'recomendaciones', 'plan_accion'];
                    $tablesCreated = [];
                    
                    foreach ($tables as $table) {
                        try {
                            $result = $conn->query("SHOW TABLES LIKE '$table'");
                            if ($result->rowCount() > 0) {
                                $tablesCreated[] = $table;
                            }
                        } catch (PDOException $e) {
                            mostrarMensaje("Error al verificar tabla $table: " . $e->getMessage(), 'error');
                        }
                    }

                    if (count($tablesCreated) == count($tables)) {
                        mostrarMensaje("¡Instalación completada exitosamente! Todas las tablas fueron creadas.");
                        echo '<div class="text-center" style="margin-top: 20px;">
                                <a href="../index.php" class="btn btn-primary">Ir al Inicio</a>
                              </div>';
                    } else {
                        mostrarMensaje("Algunas tablas no se crearon correctamente. Tablas creadas: " . implode(', ', $tablesCreated), 'error');
                    }

                } catch(Exception $e) {
                    mostrarMensaje("Error durante la instalación: " . $e->getMessage(), 'error');
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html> 