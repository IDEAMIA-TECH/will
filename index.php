<?php
require_once 'config/database.php';
$fecha_hoy = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Gestión Disruptiva</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .wizard-step { display: none; }
        .wizard-step.active { display: block; }
        .wizard-nav { display: flex; justify-content: space-between; margin-top: 32px; }
        .wizard-progress { display: flex; justify-content: center; margin-bottom: 24px; gap: 8px; }
        .wizard-dot { width: 14px; height: 14px; border-radius: 50%; background: #e0e0e0; transition: background 0.2s; }
        .wizard-dot.active { background: var(--primary-color); }
        .yesno-group { display: flex; gap: 24px; align-items: center; }
        .yesno-label { margin-right: 8px; font-weight: 500; }
        .rating-group { display: flex; gap: 10px; align-items: center; }
        .rating-label { margin-right: 8px; font-weight: 500; }
        .switch-group { display: flex; align-items: center; gap: 18px; margin-bottom: 18px; }
        .switch-label { font-weight: 500; margin-right: 12px; }
        .switch { position: relative; display: inline-block; width: 48px; height: 24px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: #ccc; transition: .4s; border-radius: 24px; }
        .slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 2px; bottom: 2px; background: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background: var(--primary-color); }
        input:checked + .slider:before { transform: translateX(24px); }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-content">
            <img src="assets/img/image.png" alt="Ralktech Logo" class="logo">
            
        </div>
    </header>
    <div class="container">
        <div class="card fade-in">
            <div class="card-header">
                <h1 class="card-title">Diagnóstico de Gestión Disruptiva</h1>
            </div>
            <div class="card-body">
                <div class="wizard-progress" id="wizardProgress"></div>
                <form id="diagnosticoForm" method="POST" action="procesar_diagnostico.php">
                    <!-- Paso 1: Información General -->
                    <div class="wizard-step active" data-step="1">
                        <div class="diagnostic-section">
                            <h2 class="section-title">Información General</h2>
                            <div class="form-group">
                                <label for="fecha_diagnostico">Fecha de Diagnóstico</label>
                                <input type="date" id="fecha_diagnostico" name="fecha_diagnostico" class="form-control" required value="<?php echo $fecha_hoy; ?>">
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
                    </div>

                    <!-- Paso 2: MOMENTO EMPRESARIAL -->
                    <div class="wizard-step" data-step="2">
                        <div class="diagnostic-section">
                            <h2 class="section-title">Momento Empresarial</h2>
                            <div class="form-group">
                                <label>¿La declaración de la Visión es coherente con la misión de su organización?</label>
                                <div class="yesno-group">
                                    <label class="yesno-label"><input type="radio" name="momento_1" value="1" required> Sí</label>
                                    <label class="yesno-label"><input type="radio" name="momento_1" value="0"> No</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>¿Dedica tiempo para analizar información de alto impacto al negocio?</label>
                                <div class="yesno-group">
                                    <label class="yesno-label"><input type="radio" name="momento_2" value="1" required> Sí</label>
                                    <label class="yesno-label"><input type="radio" name="momento_2" value="0"> No</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>¿Desarrolla reuniones estratégicas con sus líderes de área?</label>
                                <div class="yesno-group">
                                    <label class="yesno-label"><input type="radio" name="momento_3" value="1" required> Sí</label>
                                    <label class="yesno-label"><input type="radio" name="momento_3" value="0"> No</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>¿La toma de decisión es oportuna?</label>
                                <div class="yesno-group">
                                    <label class="yesno-label"><input type="radio" name="momento_4" value="1" required> Sí</label>
                                    <label class="yesno-label"><input type="radio" name="momento_4" value="0"> No</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>¿Sus líderes de área conocen hacia dónde se dirige la organización?</label>
                                <div class="yesno-group">
                                    <label class="yesno-label"><input type="radio" name="momento_5" value="1" required> Sí</label>
                                    <label class="yesno-label"><input type="radio" name="momento_5" value="0"> No</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>¿Desarrolla objetivos desde las siguientes perspectivas? (Administrativa, Comercial, Procesos, Capital Humano)</label>
                                <div class="yesno-group">
                                    <label class="yesno-label"><input type="radio" name="momento_6" value="1" required> Sí</label>
                                    <label class="yesno-label"><input type="radio" name="momento_6" value="0"> No</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>¿Cuenta con plan de...? (Administrativo, Comercial, Operativo, Capital Humano)</label>
                                <div class="yesno-group">
                                    <label class="yesno-label"><input type="radio" name="momento_7" value="1" required> Sí</label>
                                    <label class="yesno-label"><input type="radio" name="momento_7" value="0"> No</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>¿Actualmente tiene documentado algún plan organizacional?</label>
                                <div class="yesno-group">
                                    <label class="yesno-label"><input type="radio" name="momento_8" value="1" required> Sí</label>
                                    <label class="yesno-label"><input type="radio" name="momento_8" value="0"> No</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>¿Conoce el % de cumplimiento respecto al plan que actualmente implementa la organización?</label>
                                <div class="yesno-group">
                                    <label class="yesno-label"><input type="radio" name="momento_9" value="1" required> Sí</label>
                                    <label class="yesno-label"><input type="radio" name="momento_9" value="0"> No</label>
                                </div>
                            </div>
                            <!-- Switches adicionales -->
                            <?php
                            $switch_preguntas = [
                                '¿La empresa cuenta con un sistema de gestión documental digitalizado?',
                                '¿Se utilizan tableros de control para el seguimiento de indicadores?',
                                '¿Se realizan reuniones periódicas de seguimiento de resultados?',
                                '¿La empresa cuenta con un plan de continuidad de negocio?',
                                '¿Se promueve el uso de herramientas tecnológicas para la colaboración?',
                                '¿Existe un programa de bienestar para los empleados?',
                                '¿La empresa cuenta con certificaciones de calidad vigentes?',
                                '¿Se realiza evaluación de desempeño al personal?',
                                '¿Se cuenta con un programa de capacitación continua?',
                                '¿La empresa promueve la innovación en sus procesos?'
                            ];
                            foreach ($switch_preguntas as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="switch-group">';
                                echo '<span class="switch-label">' . htmlspecialchars($pregunta) . '</span>';
                                echo '<label class="switch">';
                                echo '<input type="checkbox" name="momento_switch_' . $num . '" value="1">';
                                echo '<span class="slider"></span>';
                                echo '</label>';
                                echo '<input type="hidden" name="momento_switch_' . $num . '_hidden" value="0">';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Paso 3: ÁREAS DE ACCIÓN -->
                    <div class="wizard-step" data-step="3">
                        <div class="diagnostic-section">
                            <h2 class="section-title">ÁREAS DE ACCIÓN</h2>
                            <?php
                            $areas_preguntas = [
                                '¿La Alta Dirección promueve la visión y misión de la organización?',
                                '¿La Alta Dirección promueve la cultura organizacional?',
                                '¿La Alta Dirección promueve la innovación?',
                                '¿La Alta Dirección promueve la mejora continua?',
                                '¿La Alta Dirección promueve la toma de decisiones basada en datos?',
                                '¿La Alta Dirección promueve la comunicación efectiva?',
                                '¿La Alta Dirección promueve el trabajo en equipo?',
                                '¿La Alta Dirección promueve el desarrollo del talento?',
                                '¿La Alta Dirección promueve la responsabilidad social?',
                                '¿La Alta Dirección promueve la ética y valores?'
                            ];
                            foreach ($areas_preguntas as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 5; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="area_accion_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '<textarea name="comentario_area_accion_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Paso 4: PITS CALIDAD & PRODUCTIVIDAD -->
                    <div class="wizard-step" data-step="4">
                        <div class="diagnostic-section">
                            <h2 class="section-title">PITS CALIDAD & PRODUCTIVIDAD</h2>
                            <?php
                            $pits_preguntas = [
                                '¿El área cuenta con indicadores de calidad y productividad definidos?',
                                '¿Se da seguimiento periódico a los indicadores de calidad y productividad?',
                                '¿Se implementan acciones de mejora cuando los indicadores no se cumplen?',
                                '¿El personal está capacitado para cumplir con los estándares de calidad y productividad?'
                            ];
                            foreach ($pits_preguntas as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '<textarea name="comentario_pits_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Paso 5: PITS MAXIMIZACIÓN DE CAPACIDADES -->
                    <div class="wizard-step" data-step="5">
                        <div class="diagnostic-section">
                            <h2 class="section-title">PITS MAXIMIZACIÓN DE CAPACIDADES</h2>
                            <?php
                            $pits_max_preguntas = [
                                '¿El personal cuenta con las competencias necesarias para su puesto?',
                                '¿Se realizan capacitaciones periódicas para el desarrollo de habilidades?',
                                '¿Se promueve la polivalencia y multifuncionalidad del personal?',
                                '¿Existen programas de reconocimiento al desempeño y desarrollo?' 
                            ];
                            foreach ($pits_max_preguntas as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="yesno-group">';
                                echo '<label class="yesno-label"><input type="radio" name="pits_max_' . $num . '" value="1" required> Sí</label>';
                                echo '<label class="yesno-label"><input type="radio" name="pits_max_' . $num . '" value="0"> No</label>';
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                    // Obtener secciones y preguntas
                    $step = 6;
                    try {
                        $stmt = $conn->query("SELECT * FROM secciones ORDER BY id");
                        while ($seccion = $stmt->fetch()) {
                            echo '<div class="wizard-step" data-step="' . $step . '">';
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
                            echo '</div>';
                            $step++;
                        }
                    } catch (PDOException $e) {
                        echo '<div class="message message-error">Error al cargar el formulario: ' . $e->getMessage() . '</div>';
                    }
                    ?>

                    <div class="wizard-nav">
                        <button type="button" class="btn btn-primary" id="prevBtn" onclick="nextPrev(-1)">Anterior</button>
                        <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)">Siguiente</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn" style="display:none;">Guardar Diagnóstico</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Wizard logic
    let currentStep = 0;
    const steps = document.querySelectorAll('.wizard-step');
    const progress = document.getElementById('wizardProgress');
    let totalSteps = steps.length;

    function showStep(n) {
        steps.forEach((step, i) => {
            step.classList.toggle('active', i === n);
        });
        // Progress dots
        progress.innerHTML = '';
        for (let i = 0; i < totalSteps; i++) {
            progress.innerHTML += `<span class="wizard-dot${i === n ? ' active' : ''}"></span>`;
        }
        // Buttons
        document.getElementById('prevBtn').style.display = n === 0 ? 'none' : '';
        document.getElementById('nextBtn').style.display = n === totalSteps - 1 ? 'none' : '';
        document.getElementById('submitBtn').style.display = n === totalSteps - 1 ? '' : 'none';
    }

    function nextPrev(n) {
        // Simple validation: check required fields in current step
        const currentFields = steps[currentStep].querySelectorAll('input, select, textarea');
        for (let field of currentFields) {
            if (field.hasAttribute('required') && !field.value) {
                field.focus();
                field.classList.add('error');
                setTimeout(() => field.classList.remove('error'), 1200);
                return;
            }
        }
        currentStep += n;
        if (currentStep < 0) currentStep = 0;
        if (currentStep >= totalSteps) currentStep = totalSteps - 1;
        showStep(currentStep);
    }

    // Inicializar wizard
    document.addEventListener('DOMContentLoaded', function() {
        window.steps = document.querySelectorAll('.wizard-step');
        window.totalSteps = steps.length;
        showStep(0);
    });
    </script>
</body>
</html> 