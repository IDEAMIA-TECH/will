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

                    <!-- Paso 3: PITS Comercial -->
                    <div class="wizard-step" data-step="3">
                        <div class="diagnostic-section">
                            <h2 class="section-title">PITS Comercial</h2>

                            <!-- DIRECCIÓN COMERCIAL -->
                            <h3>Dirección Comercial</h3>
                            <?php
                            $direccion_comercial = [
                                'Líder enfocado en desarrollar una gestión comercial basada en resultados.',
                                'El organigrama comercial responde  a la plataforma requerida para lograr objetivos y metas.',
                                'El Sistema de información facilita la toma de decisión desde un ambito comercial y en forma oportuna.'
                            ];
                            foreach ($direccion_comercial as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_dircom_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '<textarea name="comentario_pits_dircom_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                echo '</div>';
                            }
                            ?>

                            <!-- PROCESO DE GESTIÓN COMERCIAL -->
                            <h3>Proceso de Gestión Comercial</h3>
                            <?php
                            $proceso_gestion = [
                                'Efectividad de la gestión para Pre - Venta',
                                'Efectividad de la gestión para Venta',
                                'Efectividad de la gestión para Post-Venta',
                                'Efectividad de la gestión para Desarrollo de Cuenta'
                            ];
                            foreach ($proceso_gestion as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_gestion_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '<textarea name="comentario_pits_gestion_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                echo '</div>';
                            }
                            ?>

                            <!-- SISTEMA DE INFORMACIÓN -->
                            <h3>Sistema de Información</h3>
                            <?php
                            $sistema_info = [
                                'Captura  y organiza la información del cliente, tanto su comportamiento de compra como los contactos que se establecen entre el cliente y la organización. Etapa Prospecto - Cliente Fidelizado'
                            ];
                            foreach ($sistema_info as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_info_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '<textarea name="comentario_pits_info_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                echo '</div>';
                            }
                            ?>

                            <!-- ESTRATEGIAS Y TÁCTICAS -->
                            <h3>Estrategias y Tácticas</h3>
                            <?php
                            $estrategias = [
                                'Los objetivos,metas, estratégias comerciales estan documentadas en un Plan Comercial, con un plazo establecido para su cumplimiento.'
                            ];
                            foreach ($estrategias as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_estrategia_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '<textarea name="comentario_pits_estrategia_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                echo '</div>';
                            }
                            ?>

                            <!-- METRICAS COMERCIALES -->
                            <h3>Métricas Comerciales</h3>
                            <?php
                            $metricas = [
                                'Conoce el comportamiento histórico de indicadores comerciales, a fin de medirlos y controlarlos.',
                                'Sistema de medición de satisfacción al cliente y administración de comentarios y reclamos.'
                            ];
                            foreach ($metricas as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_metricas_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '<textarea name="comentario_pits_metricas_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Paso 4: PITS CALIDAD & PRODUCTIVIDAD -->
                    <div class="wizard-step" data-step="4">
                        <div class="diagnostic-section">
                            <h2 class="section-title">PITS CALIDAD & PRODUCTIVIDAD</h2>

                            <!-- Tipo de Proceso -->
                            <div class="form-group">
                                <label><strong>SELECCIONE EL TIPO DE PROCESO QUE LÍDERA:</strong></label><br>
                                <label class="rating-label"><input type="radio" name="tipo_proceso" value="Estratégico" required> Estratégico</label>
                                <label class="rating-label"><input type="radio" name="tipo_proceso" value="Operativo"> Operativo</label>
                                <label class="rating-label"><input type="radio" name="tipo_proceso" value="Calidad y Productividad"> Calidad y Productividad</label>
                                <label class="rating-label"><input type="radio" name="tipo_proceso" value="Soporte"> Soporte</label>
                            </div>

                            <!-- ENFOQUE POR RESULTADOS -->
                            <h3>Enfoque por Resultados</h3>
                            <?php
                            $enfoque_resultados = [
                                'El dueño del proceso, maneja la operación, en tal forma que pueden delegar en forma efectiva a su equipo de trabajo',
                                'La gestión está enfocada a través de resultados medibles en tiempo y calidad.',
                                'Es comprendida la finalidad para la cual se implementa el proceso.',
                                'El mando medio, desempeña un proceso de gestión enfocado en control, seguimiento y resultados'
                            ];
                            foreach ($enfoque_resultados as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_resultados_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>

                            <!-- ENFOQUE POR PROCESO -->
                            <h3>Enfoque por Proceso</h3>
                            <?php
                            $enfoque_proceso = [
                                'El organigrama funcional, contempla un área de calidad y procesos.',
                                'Cuenta con un Mapa de Proceso.',
                                'Cuenta con Diagrama de Flujos de Trabajo (secuencia de actividades).',
                                'Identifica los clientes internos a los cuales responde el proceso.'
                            ];
                            foreach ($enfoque_proceso as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_proceso_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>

                            <!-- CALIDAD Y MEJORA CONTINUA -->
                            <h3>Calidad y Mejora Continua</h3>
                            <?php
                            $calidad_mejora = [
                                'Existe un sistema de gestión por indicadores',
                                'Las salida del proceso cumple con requisitos y especificaciones previamente definidas.',
                                'Su proceso es medible y auditable.',
                                'Cuenta con un programa estructurado de auditoría a sus procesos.'
                            ];
                            foreach ($calidad_mejora as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_calidad_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>

                            <!-- OPORTUNIDAD DE MEJORA -->
                            <h3>Oportunidad de Mejora</h3>
                            <div class="form-group">
                                <label>Factores Críticos: Identifique dos actividades dentro de su proceso que de no realizarse en forma correcta, el proceso falla.</label>
                                <textarea name="oportunidad_factores" class="form-control" rows="2" placeholder="Describa los factores críticos..."></textarea>
                            </div>
                            <div class="form-group">
                                <label>Puntos clave del proceso: Identifique dos momentos y lugares donde se toman decisiones que afectan a todo el proceso en conjunto.</label>
                                <textarea name="oportunidad_puntos" class="form-control" rows="2" placeholder="Describa los puntos clave..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 5: PITS MAXIMIZACIÓN DE CAPACIDADES -->
                    <div class="wizard-step" data-step="5">
                        <div class="diagnostic-section">
                            <h2 class="section-title">PITS MAXIMIZACIÓN DE CAPACIDADES</h2>

                            <!-- IMPACTO DEL CAPITAL HUMANO EN LA PRODUCTIVIDAD -->
                            <h3>Impacto del Capital Humano en la Productividad</h3>
                            <?php
                            $impacto_capital = [
                                '¿El líder del área de Capital humano conoce la importancia del capital humano para la productividad de la organización?',
                                '¿Existe un sistema de evaluación de desempeño, desde el enfoque de eficiencia y eficacia?',
                                '¿La optimización de cargos, es una constante dentro del proceso de administración del Capital humano?',
                                '¿Considera que dentro del proceso de reclutamiento, se ejecutan actividades orientadas a evaluar el perfil de acuerdo al perfil del cargo que desempeñará?'
                            ];
                            foreach ($impacto_capital as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_capital_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '<textarea name="comentario_pits_capital_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                echo '</div>';
                            }
                            ?>

                            <!-- INVENTARIO DE CAPACIDADES -->
                            <h3>Inventario de Capacidades</h3>
                            <?php
                            $inventario = [
                                '¿La organización cuenta con un Inventario de Capacidades?',
                                '¿Mantiene actualizado el inventario de capacidades?',
                                '¿Aprovecha al máximo las capacidades de su equipo de gestión?',
                                '¿Conoce las brechas entre el perfil actual de sus empleados y el perfil ideal?'
                            ];
                            foreach ($inventario as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_inventario_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '<textarea name="comentario_pits_inventario_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                echo '</div>';
                            }
                            ?>

                            <!-- MAXIMIZACIÓN DE CAPACIDADES -->
                            <h3>Maximización de Capacidades</h3>
                            <?php
                            $maximizacion = [
                                '¿Cuenta con un Plan de Capacitación?',
                                '¿Cuenta con un Plan de Desarrollo de Carrera?',
                                '¿Cuenta con un programa institucionalizado para motivar y retener a su personal?'
                            ];
                            foreach ($maximizacion as $i => $pregunta) {
                                $num = $i + 1;
                                echo '<div class="form-group">';
                                echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                echo '<div class="rating-group">';
                                for ($r = 1; $r <= 4; $r++) {
                                    echo '<label class="rating-label"><input type="radio" name="pits_max_' . $num . '" value="' . $r . '" required> ' . $r . '</label>';
                                }
                                echo '</div>';
                                echo '<textarea name="comentario_pits_max_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>

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