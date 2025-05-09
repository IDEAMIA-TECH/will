<?php
require_once 'database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación y Prueba de Conexión - Diagnóstico de Gestión Disruptiva</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .message { margin-bottom: 1rem; padding: 1rem; border-radius: 6px; }
        .message-success { background: #e8f5e9; color: #256029; }
        .message-error { background: #ffebee; color: #b71c1c; }
        .section-block { margin-bottom: 2rem; padding: 1.5rem; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        h2, h3 { margin-top: 0; }
        ul { margin: 0 0 1rem 1.5rem; }
        pre { background: #f8fafc; padding: 1rem; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card fade-in">
            <div class="card-header">
                <h1 class="card-title">Instalación y Prueba de Conexión</h1>
            </div>
            <div class="card-body">
                <?php
                // Función para mostrar mensajes de error o éxito
                function mostrarMensaje($mensaje, $tipo = 'success') {
                    echo "<div class='message message-" . $tipo . " fade-in'>$mensaje</div>";
                }

                $instalacionExitosa = false;
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
                            id VARCHAR(30) PRIMARY KEY,
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
                            pregunta_id VARCHAR(30),
                            calificacion INT CHECK (calificacion BETWEEN 0 AND 5),
                            observaciones TEXT,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos(id)
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
                            [100, 'PLANIFICACIÓN', 'Preguntas de Momento Empresarial', 20],
                            [101, 'Comercial', 'Preguntas de Áreas de Acción', 20],
                            [102, 'Productividad', 'Preguntas de PITS Calidad & Productividad', 20],
                            [103, 'Capacidades', 'Preguntas de PITS Maximización de Capacidades', 20],
                            [200, 'PRODUCTIVIDAD', 'Bloques de evaluación de procesos, calidad y mejora continua', 20],
                            [201, 'Comercial', 'Evaluación de la gestión comercial y procesos relacionados', 20],
                            [202, 'CAPACIDADES', 'Evaluación de capital humano y capacidades', 20]
                        ];
                        $stmt = $conn->prepare("INSERT IGNORE INTO secciones (id, nombre, descripcion, puntuacion_maxima) VALUES (?, ?, ?, ?)");
                        foreach ($secciones as $seccion) {
                            $stmt->execute($seccion);
                        }
                        mostrarMensaje("Secciones insertadas correctamente");

                        // Insertar preguntas
                        $preguntas = [
                            // Momento Empresarial (sección 100)
                            ['M1_1', 100, '¿La declaración de la Visión es coherente con la misión de su organización?', 1],
                            ['M1_2', 100, '¿Dedica tiempo para analizar información de alto impacto al negocio?', 2],
                            ['M1_3', 100, '¿Desarrolla reuniones estratégicas con sus líderes de área?', 3],
                            ['M1_4', 100, '¿La toma de decisión es oportuna?', 4],
                            ['M1_5', 100, '¿Sus líderes de área conocen hacia dónde se dirige la organización?', 5],
                            ['M1_6', 100, '¿Desarrolla objetivos desde las siguientes perspectivas? (Administrativa, Comercial, Procesos, Capital Humano)', 6],
                            ['M1_7', 100, '¿Cuenta con plan de...? (Administrativo, Comercial, Operativo, Capital Humano)', 7],
                            ['M1_8', 100, '¿Actualmente tiene documentado algún plan organizacional?', 8],
                            ['M1_9', 100, '¿Conoce el % de cumplimiento respecto al plan que actualmente implementa la organización?', 9],
                            // Switches de Momento Empresarial
                            ['M1S_1', 100, '¿La empresa cuenta con un sistema de gestión documental digitalizado?', 10],
                            ['M1S_2', 100, '¿Se utilizan tableros de control para el seguimiento de indicadores?', 11],
                            ['M1S_3', 100, '¿Se realizan reuniones periódicas de seguimiento de resultados?', 12],
                            ['M1S_4', 100, '¿La empresa cuenta con un plan de continuidad de negocio?', 13],
                            ['M1S_5', 100, '¿Se promueve el uso de herramientas tecnológicas para la colaboración?', 14],
                            ['M1S_6', 100, '¿Existe un programa de bienestar para los empleados?', 15],
                            ['M1S_7', 100, '¿La empresa cuenta con certificaciones de calidad vigentes?', 16],
                            ['M1S_8', 100, '¿Se realiza evaluación de desempeño al personal?', 17],
                            ['M1S_9', 100, '¿Se cuenta con un programa de capacitación continua?', 18],
                            ['M1S_10', 100, '¿La empresa promueve la innovación en sus procesos?', 19],
                            // PITS Comercial (sección 201)
                            ['PITS_DIRCOM_1', 201, 'Líder enfocado en desarrollar una gestión comercial basada en resultados.', 1],
                            ['PITS_DIRCOM_2', 201, 'El organigrama comercial responde a la plataforma requerida para lograr objetivos y metas.', 2],
                            ['PITS_DIRCOM_3', 201, 'El Sistema de información facilita la toma de decisión desde un ambito comercial y en forma oportuna.', 3],
                            ['PITS_GESTION_1', 201, 'Efectividad de la gestión para Pre - Venta', 4],
                            ['PITS_GESTION_2', 201, 'Efectividad de la gestión para Venta', 5],
                            ['PITS_GESTION_3', 201, 'Efectividad de la gestión para Post-Venta', 6],
                            ['PITS_GESTION_4', 201, 'Efectividad de la gestión para Desarrollo de Cuenta', 7],
                            ['PITS_INFO_1', 201, 'Captura y organiza la información del cliente, tanto su comportamiento de compra como los contactos que se establecen entre el cliente y la organización. Etapa Prospecto - Cliente Fidelizado', 8],
                            ['PITS_ESTRATEGIA_1', 201, 'Los objetivos,metas, estratégias comerciales estan documentadas en un Plan Comercial, con un plazo establecido para su cumplimiento.', 9],
                            ['PITS_METRICAS_1', 201, 'Conoce el comportamiento histórico de indicadores comerciales, a fin de medirlos y controlarlos.', 10],
                            ['PITS_METRICAS_2', 201, 'Sistema de medición de satisfacción al cliente y administración de comentarios y reclamos.', 11],
                            // PITS Calidad & Productividad (sección 200)
                            ['PITS_TIPO', 200, 'SELECCIONE EL TIPO DE PROCESO QUE LÍDERA:', 0],
                            ['PITS_RES_1', 200, 'El dueño del proceso, maneja la operación, en tal forma que pueden delegar en forma efectiva a su equipo de trabajo', 1],
                            ['PITS_RES_2', 200, 'La gestión está enfocada a través de resultados medibles en tiempo y calidad.', 2],
                            ['PITS_RES_3', 200, 'Es comprendida la finalidad para la cual se implementa el proceso.', 3],
                            ['PITS_RES_4', 200, 'El mando medio, desempeña un proceso de gestión enfocado en control, seguimiento y resultados', 4],
                            ['PITS_PROC_1', 200, 'El organigrama funcional, contempla un área de calidad y procesos.', 5],
                            ['PITS_PROC_2', 200, 'Cuenta con un Mapa de Proceso.', 6],
                            ['PITS_PROC_3', 200, 'Cuenta con Diagrama de Flujos de Trabajo (secuencia de actividades).', 7],
                            ['PITS_PROC_4', 200, 'Identifica los clientes internos a los cuales responde el proceso.', 8],
                            ['PITS_CAL_1', 200, 'Existe un sistema de gestión por indicadores', 9],
                            ['PITS_CAL_2', 200, 'Las salida del proceso cumple con requisitos y especificaciones previamente definidas.', 10],
                            ['PITS_CAL_3', 200, 'Su proceso es medible y auditable.', 11],
                            ['PITS_CAL_4', 200, 'Cuenta con un programa estructurado de auditoría a sus procesos.', 12],
                            ['PITS_MEJ_1', 200, 'Factores Críticos: Identifique dos actividades dentro de su proceso que de no realizarse en forma correcta, el proceso falla.', 13],
                            ['PITS_MEJ_2', 200, 'Puntos clave del proceso: Identifique dos momentos y lugares donde se toman decisiones que afectan a todo el proceso en conjunto.', 14],
                            // PITS Maximización de Capacidades (sección 202)
                            ['PITS_CAPITAL_1', 202, '¿El líder del área de Capital humano conoce la importancia del capital humano para la productividad de la organización?', 1],
                            ['PITS_CAPITAL_2', 202, '¿Existe un sistema de evaluación de desempeño, desde el enfoque de eficiencia y eficacia?', 2],
                            ['PITS_CAPITAL_3', 202, '¿La optimización de cargos, es una constante dentro del proceso de administración del Capital humano?', 3],
                            ['PITS_CAPITAL_4', 202, '¿Considera que dentro del proceso de reclutamiento, se ejecutan actividades orientadas a evaluar el perfil de acuerdo al perfil del cargo que desempeñará?', 4],
                            ['PITS_INVENTARIO_1', 202, '¿La organización cuenta con un Inventario de Capacidades?', 5],
                            ['PITS_INVENTARIO_2', 202, '¿Mantiene actualizado el inventario de capacidades?', 6],
                            ['PITS_INVENTARIO_3', 202, '¿Aprovecha al máximo las capacidades de su equipo de gestión?', 7],
                            ['PITS_INVENTARIO_4', 202, '¿Conoce las brechas entre el perfil actual de sus empleados y el perfil ideal?', 8],
                            ['PITS_MAX_1', 202, '¿Cuenta con un Plan de Capacitación?', 9],
                            ['PITS_MAX_2', 202, '¿Cuenta con un Plan de Desarrollo de Carrera?', 10],
                            ['PITS_MAX_3', 202, '¿Cuenta con un programa institucionalizado para motivar y retener a su personal?', 11]
                        ];
                        $stmt = $conn->prepare("INSERT IGNORE INTO preguntas (id, seccion_id, texto_pregunta, orden) VALUES (?, ?, ?, ?)");
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
                                if ($e->getCode() != '42000') {
                                    mostrarMensaje("Error al crear índice: " . $e->getMessage(), 'error');
                                }
                            }
                        }
                        $instalacionExitosa = true;
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

                // --- PRUEBA DE CONEXIÓN Y ESTADO ---
                echo '<div class="section-block">';
                echo '<h2>Prueba de Conexión y Estado de la Base de Datos</h2>';
                try {
                    // Información del servidor
                    $serverInfo = $conn->getAttribute(PDO::ATTR_SERVER_INFO);
                    $serverVersion = $conn->getAttribute(PDO::ATTR_SERVER_VERSION);
                    echo "<div class='message message-success'><strong>Conexión exitosa!</strong><br>Información del servidor: $serverInfo<br>Versión del servidor: $serverVersion</div>";

                    // Listar tablas existentes
                    echo "<h3>Tablas existentes en la base de datos:</h3>";
                    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                    if (count($tables) > 0) {
                        echo "<ul>";
                        foreach ($tables as $table) {
                            echo "<li>" . htmlspecialchars($table) . "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>No hay tablas en la base de datos.</p>";
                    }

                    // Permisos del usuario
                    echo "<h3>Permisos del usuario:</h3>";
                    $permissions = $conn->query("SHOW GRANTS FOR CURRENT_USER")->fetchAll(PDO::FETCH_COLUMN);
                    echo "<pre>";
                    print_r($permissions);
                    echo "</pre>";
                } catch(PDOException $e) {
                    echo "<div class='message message-error'><strong>Error de conexión:</strong><br>Mensaje: " . $e->getMessage() . "<br>Código: " . $e->getCode() . "</div>";
                    // Información adicional para diagnóstico
                    echo "<h3>Información de conexión:</h3>";
                    echo "<ul>";
                    echo "<li>Host: " . DB_HOST . "</li>";
                    echo "<li>Usuario: " . DB_USER . "</li>";
                    echo "<li>Base de datos: " . DB_NAME . "</li>";
                    echo "</ul>";
                }
                echo '</div>';
                ?>
            </div>
        </div>
    </div>
</body>
</html> 