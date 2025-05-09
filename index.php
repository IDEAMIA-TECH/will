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
        /* Estilos generales */
        body {
            background-color: #f5f7fa;
            color: #2d3748;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-title {
            margin: 0;
            color: #1a202c;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Estilos del wizard */
        .wizard-step { 
            display: none; 
            padding: 1rem;
        }
        
        .wizard-step.active { 
            display: block; 
        }

        .wizard-progress {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
            gap: 1rem;
        }

        .wizard-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e2e8f0;
            transition: all 0.3s ease;
        }

        .wizard-dot.active {
            background: var(--primary-color);
            transform: scale(1.2);
        }

        /* Estilos de las secciones */
        .diagnostic-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            color: #2d3748;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
        }

        /* Estilos para subsecciones */
        .subsection {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .subsection-title {
            color: #2d3748;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Estilos para grupos de formulario mejorados */
        .form-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            align-items: start;
            margin-bottom: 1.5rem;
            padding: 1.25rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .form-group:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            padding-right: 1rem;
        }

        .form-label label {
            font-weight: 500;
            color: #4a5568;
            line-height: 1.5;
        }

        .form-input {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        /* Estilos para grupos de respuestas mejorados */
        .response-group {
            display: flex;
            gap: 1rem;
            align-items: center;
            padding: 0.5rem;
            background: #f8fafc;
            border-radius: 6px;
        }

        .yesno-group {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            padding: 0.5rem;
            background: #f8fafc;
            border-radius: 6px;
        }

        .yesno-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .yesno-label:hover {
            background: #edf2f7;
        }

        .rating-group {
            display: flex;
            gap: 1rem;
            align-items: center;
            padding: 0.5rem;
            background: #f8fafc;
            border-radius: 6px;
        }

        .rating-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .rating-label:hover {
            background: #edf2f7;
        }

        /* Estilos para switches mejorados */
        .switch-section {
            margin-top: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .switch-group {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .switch-group:hover {
            background: #edf2f7;
        }

        .switch-label {
            flex: 1;
            font-weight: 500;
            color: #4a5568;
            line-height: 1.5;
        }

        /* Estilos para comentarios */
        textarea.form-control {
            min-height: 80px;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            resize: vertical;
            transition: all 0.2s ease;
        }

        textarea.form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
            outline: none;
        }

        /* Estilos para botones mejorados */
        .wizard-nav {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            min-width: 120px;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background: var(--primary-color-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Estilos para el progreso del wizard */
        .wizard-progress {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
            gap: 1rem;
        }

        .wizard-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
        }

        .wizard-dot.active {
            background: var(--primary-color);
            transform: scale(1.2);
        }

        .wizard-dot.completed {
            background: var(--primary-color);
        }

        .wizard-dot.completed::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 8px;
        }

        /* Responsive mejorado */
        @media (max-width: 768px) {
            .form-group {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .container {
                padding: 1rem;
            }

            .wizard-nav {
                flex-direction: column;
                gap: 1rem;
            }

            .btn {
                width: 100%;
            }

            .yesno-group,
            .rating-group {
                flex-wrap: wrap;
            }
        }
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
                                <div class="form-label">
                                    <label for="fecha_diagnostico">Fecha de Diagnóstico</label>
                                </div>
                                <div class="form-input">
                                    <input type="date" id="fecha_diagnostico" name="fecha_diagnostico" class="form-control" required value="<?php echo $fecha_hoy; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label for="nombre_cliente">Nombre del Cliente</label>
                                </div>
                                <div class="form-input">
                                    <input type="text" id="nombre_cliente" name="nombre_cliente" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label for="industria">Industria</label>
                                </div>
                                <div class="form-input">
                                    <input type="text" id="industria" name="industria" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label for="tamano_empresa">Tamaño de la Empresa</label>
                                </div>
                                <div class="form-input">
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
                    </div>

                    <!-- Paso 2: MOMENTO EMPRESARIAL -->
                    <div class="wizard-step" data-step="2">
                        <div class="diagnostic-section">
                            <h2 class="section-title">Momento Empresarial</h2>
                            <div class="form-group">
                                <div class="form-label">
                                    <label>¿La declaración de la Visión es coherente con la misión de su organización?</label>
                                </div>
                                <div class="form-input">
                                    <div class="yesno-group">
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_1" value="1" required>
                                            <span>Sí</span>
                                        </label>
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_1" value="0">
                                            <span>No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label>¿Dedica tiempo para analizar información de alto impacto al negocio?</label>
                                </div>
                                <div class="form-input">
                                    <div class="yesno-group">
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_2" value="1" required>
                                            <span>Sí</span>
                                        </label>
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_2" value="0">
                                            <span>No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label>¿Desarrolla reuniones estratégicas con sus líderes de área?</label>
                                </div>
                                <div class="form-input">
                                    <div class="yesno-group">
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_3" value="1" required>
                                            <span>Sí</span>
                                        </label>
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_3" value="0">
                                            <span>No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label>¿La toma de decisión es oportuna?</label>
                                </div>
                                <div class="form-input">
                                    <div class="yesno-group">
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_4" value="1" required>
                                            <span>Sí</span>
                                        </label>
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_4" value="0">
                                            <span>No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label>¿Sus líderes de área conocen hacia dónde se dirige la organización?</label>
                                </div>
                                <div class="form-input">
                                    <div class="yesno-group">
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_5" value="1" required>
                                            <span>Sí</span>
                                        </label>
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_5" value="0">
                                            <span>No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label>¿Desarrolla objetivos desde las siguientes perspectivas? (Administrativa, Comercial, Procesos, Capital Humano)</label>
                                </div>
                                <div class="form-input">
                                    <div class="yesno-group">
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_6" value="1" required>
                                            <span>Sí</span>
                                        </label>
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_6" value="0">
                                            <span>No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label>¿Cuenta con plan de...? (Administrativo, Comercial, Operativo, Capital Humano)</label>
                                </div>
                                <div class="form-input">
                                    <div class="yesno-group">
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_7" value="1" required>
                                            <span>Sí</span>
                                        </label>
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_7" value="0">
                                            <span>No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label>¿Actualmente tiene documentado algún plan organizacional?</label>
                                </div>
                                <div class="form-input">
                                    <div class="yesno-group">
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_8" value="1" required>
                                            <span>Sí</span>
                                        </label>
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_8" value="0">
                                            <span>No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label>¿Conoce el % de cumplimiento respecto al plan que actualmente implementa la organización?</label>
                                </div>
                                <div class="form-input">
                                    <div class="yesno-group">
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_9" value="1" required>
                                            <span>Sí</span>
                                        </label>
                                        <label class="yesno-label">
                                            <input type="radio" name="momento_9" value="0">
                                            <span>No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Switches adicionales -->
                            <div class="switch-section">
                                <h3 class="subsection-title">Aspectos Adicionales</h3>
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
                    </div>

                    <!-- Paso 3: PITS Comercial -->
                    <div class="wizard-step" data-step="3">
                        <div class="diagnostic-section">
                            <h2 class="section-title">PITS Comercial</h2>

                            <!-- DIRECCIÓN COMERCIAL -->
                            <div class="subsection">
                                <h3 class="subsection-title">Dirección Comercial</h3>
                                <?php
                                $direccion_comercial = [
                                    'Líder enfocado en desarrollar una gestión comercial basada en resultados.',
                                    'El organigrama comercial responde  a la plataforma requerida para lograr objetivos y metas.',
                                    'El Sistema de información facilita la toma de decisión desde un ambito comercial y en forma oportuna.'
                                ];
                                foreach ($direccion_comercial as $i => $pregunta) {
                                    $num = $i + 1;
                                    echo '<div class="form-group">';
                                    echo '<div class="form-label">';
                                    echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                    echo '</div>';
                                    echo '<div class="form-input">';
                                    echo '<div class="rating-group">';
                                    for ($r = 1; $r <= 4; $r++) {
                                        echo '<label class="rating-label">';
                                        echo '<input type="radio" name="pits_dircom_' . $num . '" value="' . $r . '" required>';
                                        echo '<span>' . $r . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                    echo '<textarea name="comentario_pits_dircom_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <!-- PROCESO DE GESTIÓN COMERCIAL -->
                            <div class="subsection">
                                <h3 class="subsection-title">Proceso de Gestión Comercial</h3>
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
                                    echo '<div class="form-label">';
                                    echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                    echo '</div>';
                                    echo '<div class="form-input">';
                                    echo '<div class="rating-group">';
                                    for ($r = 1; $r <= 4; $r++) {
                                        echo '<label class="rating-label">';
                                        echo '<input type="radio" name="pits_gestion_' . $num . '" value="' . $r . '" required>';
                                        echo '<span>' . $r . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                    echo '<textarea name="comentario_pits_gestion_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <!-- SISTEMA DE INFORMACIÓN -->
                            <div class="subsection">
                                <h3 class="subsection-title">Sistema de Información</h3>
                                <?php
                                $sistema_info = [
                                    'Captura  y organiza la información del cliente, tanto su comportamiento de compra como los contactos que se establecen entre el cliente y la organización. Etapa Prospecto - Cliente Fidelizado'
                                ];
                                foreach ($sistema_info as $i => $pregunta) {
                                    $num = $i + 1;
                                    echo '<div class="form-group">';
                                    echo '<div class="form-label">';
                                    echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                    echo '</div>';
                                    echo '<div class="form-input">';
                                    echo '<div class="rating-group">';
                                    for ($r = 1; $r <= 4; $r++) {
                                        echo '<label class="rating-label">';
                                        echo '<input type="radio" name="pits_info_' . $num . '" value="' . $r . '" required>';
                                        echo '<span>' . $r . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                    echo '<textarea name="comentario_pits_info_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <!-- ESTRATEGIAS Y TÁCTICAS -->
                            <div class="subsection">
                                <h3 class="subsection-title">Estrategias y Tácticas</h3>
                                <?php
                                $estrategias = [
                                    'Los objetivos,metas, estratégias comerciales estan documentadas en un Plan Comercial, con un plazo establecido para su cumplimiento.'
                                ];
                                foreach ($estrategias as $i => $pregunta) {
                                    $num = $i + 1;
                                    echo '<div class="form-group">';
                                    echo '<div class="form-label">';
                                    echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                    echo '</div>';
                                    echo '<div class="form-input">';
                                    echo '<div class="rating-group">';
                                    for ($r = 1; $r <= 4; $r++) {
                                        echo '<label class="rating-label">';
                                        echo '<input type="radio" name="pits_estrategia_' . $num . '" value="' . $r . '" required>';
                                        echo '<span>' . $r . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                    echo '<textarea name="comentario_pits_estrategia_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <!-- METRICAS COMERCIALES -->
                            <div class="subsection">
                                <h3 class="subsection-title">Métricas Comerciales</h3>
                                <?php
                                $metricas = [
                                    'Conoce el comportamiento histórico de indicadores comerciales, a fin de medirlos y controlarlos.',
                                    'Sistema de medición de satisfacción al cliente y administración de comentarios y reclamos.'
                                ];
                                foreach ($metricas as $i => $pregunta) {
                                    $num = $i + 1;
                                    echo '<div class="form-group">';
                                    echo '<div class="form-label">';
                                    echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                    echo '</div>';
                                    echo '<div class="form-input">';
                                    echo '<div class="rating-group">';
                                    for ($r = 1; $r <= 4; $r++) {
                                        echo '<label class="rating-label">';
                                        echo '<input type="radio" name="pits_metricas_' . $num . '" value="' . $r . '" required>';
                                        echo '<span>' . $r . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                    echo '<textarea name="comentario_pits_metricas_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 4: PITS CALIDAD & PRODUCTIVIDAD -->
                    <div class="wizard-step" data-step="4">
                        <div class="diagnostic-section">
                            <h2 class="section-title">PITS CALIDAD & PRODUCTIVIDAD</h2>

                            <!-- Tipo de Proceso -->
                            <div class="form-group">
                                <div class="form-label">
                                    <label><strong>SELECCIONE EL TIPO DE PROCESO QUE LÍDERA:</strong></label><br>
                                </div>
                                <div class="form-input">
                                    <label class="rating-label"><input type="radio" name="tipo_proceso" value="Estratégico" required> Estratégico</label>
                                    <label class="rating-label"><input type="radio" name="tipo_proceso" value="Operativo"> Operativo</label>
                                    <label class="rating-label"><input type="radio" name="tipo_proceso" value="Calidad y Productividad"> Calidad y Productividad</label>
                                    <label class="rating-label"><input type="radio" name="tipo_proceso" value="Soporte"> Soporte</label>
                                </div>
                            </div>

                            <!-- ENFOQUE POR RESULTADOS -->
                            <div class="subsection">
                                <h3 class="subsection-title">Enfoque por Resultados</h3>
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
                                    echo '<div class="form-label">';
                                    echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                    echo '</div>';
                                    echo '<div class="form-input">';
                                    echo '<div class="rating-group">';
                                    for ($r = 1; $r <= 4; $r++) {
                                        echo '<label class="rating-label">';
                                        echo '<input type="radio" name="pits_resultados_' . $num . '" value="' . $r . '" required>';
                                        echo '<span>' . $r . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <!-- ENFOQUE POR PROCESO -->
                            <div class="subsection">
                                <h3 class="subsection-title">Enfoque por Proceso</h3>
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
                                    echo '<div class="form-label">';
                                    echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                    echo '</div>';
                                    echo '<div class="form-input">';
                                    echo '<div class="rating-group">';
                                    for ($r = 1; $r <= 4; $r++) {
                                        echo '<label class="rating-label">';
                                        echo '<input type="radio" name="pits_proceso_' . $num . '" value="' . $r . '" required>';
                                        echo '<span>' . $r . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <!-- CALIDAD Y MEJORA CONTINUA -->
                            <div class="subsection">
                                <h3 class="subsection-title">Calidad y Mejora Continua</h3>
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
                                    echo '<div class="form-label">';
                                    echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                    echo '</div>';
                                    echo '<div class="form-input">';
                                    echo '<div class="rating-group">';
                                    for ($r = 1; $r <= 4; $r++) {
                                        echo '<label class="rating-label">';
                                        echo '<input type="radio" name="pits_calidad_' . $num . '" value="' . $r . '" required>';
                                        echo '<span>' . $r . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <!-- OPORTUNIDAD DE MEJORA -->
                            <div class="subsection">
                                <h3 class="subsection-title">Oportunidad de Mejora</h3>
                                <div class="form-group">
                                    <div class="form-label">
                                        <label>Factores Críticos: Identifique dos actividades dentro de su proceso que de no realizarse en forma correcta, el proceso falla.</label>
                                    </div>
                                    <div class="form-input">
                                        <textarea name="oportunidad_factores" class="form-control" rows="2" placeholder="Describa los factores críticos..."></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-label">
                                        <label>Puntos clave del proceso: Identifique dos momentos y lugares donde se toman decisiones que afectan a todo el proceso en conjunto.</label>
                                    </div>
                                    <div class="form-input">
                                        <textarea name="oportunidad_puntos" class="form-control" rows="2" placeholder="Describa los puntos clave..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 5: PITS MAXIMIZACIÓN DE CAPACIDADES -->
                    <div class="wizard-step" data-step="5">
                        <div class="diagnostic-section">
                            <h2 class="section-title">PITS MAXIMIZACIÓN DE CAPACIDADES</h2>

                            <!-- IMPACTO DEL CAPITAL HUMANO EN LA PRODUCTIVIDAD -->
                            <div class="subsection">
                                <h3 class="subsection-title">Impacto del Capital Humano en la Productividad</h3>
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
                                    echo '<div class="form-label">';
                                    echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                    echo '</div>';
                                    echo '<div class="form-input">';
                                    echo '<div class="rating-group">';
                                    for ($r = 1; $r <= 4; $r++) {
                                        echo '<label class="rating-label">';
                                        echo '<input type="radio" name="pits_capital_' . $num . '" value="' . $r . '" required>';
                                        echo '<span>' . $r . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                    echo '<textarea name="comentario_pits_capital_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <!-- INVENTARIO DE CAPACIDADES -->
                            <div class="subsection">
                                <h3 class="subsection-title">Inventario de Capacidades</h3>
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
                                    echo '<div class="form-label">';
                                    echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                    echo '</div>';
                                    echo '<div class="form-input">';
                                    echo '<div class="rating-group">';
                                    for ($r = 1; $r <= 4; $r++) {
                                        echo '<label class="rating-label">';
                                        echo '<input type="radio" name="pits_inventario_' . $num . '" value="' . $r . '" required>';
                                        echo '<span>' . $r . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                    echo '<textarea name="comentario_pits_inventario_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <!-- MAXIMIZACIÓN DE CAPACIDADES -->
                            <div class="subsection">
                                <h3 class="subsection-title">Maximización de Capacidades</h3>
                                <?php
                                $maximizacion = [
                                    '¿Cuenta con un Plan de Capacitación?',
                                    '¿Cuenta con un Plan de Desarrollo de Carrera?',
                                    '¿Cuenta con un programa institucionalizado para motivar y retener a su personal?'
                                ];
                                foreach ($maximizacion as $i => $pregunta) {
                                    $num = $i + 1;
                                    echo '<div class="form-group">';
                                    echo '<div class="form-label">';
                                    echo '<label>' . htmlspecialchars($pregunta) . '</label>';
                                    echo '</div>';
                                    echo '<div class="form-input">';
                                    echo '<div class="rating-group">';
                                    for ($r = 1; $r <= 4; $r++) {
                                        echo '<label class="rating-label">';
                                        echo '<input type="radio" name="pits_max_' . $num . '" value="' . $r . '" required>';
                                        echo '<span>' . $r . '</span>';
                                        echo '</label>';
                                    }
                                    echo '</div>';
                                    echo '<textarea name="comentario_pits_max_' . $num . '" class="form-control" rows="2" placeholder="Comentarios (opcional)"></textarea>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
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
    <div id="mensajeCompletado" style="display:none; text-align:center; padding:2rem;">
        <h2>Diagnóstico completado</h2>
        <p>¡Gracias! Un especialista se pondrá en contacto con usted pronto.</p>
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

    // Envío por AJAX
    const form = document.getElementById('diagnosticoForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        fetch('procesar_diagnostico.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'OK') {
                form.style.display = 'none';
                document.getElementById('mensajeCompletado').style.display = 'block';
            } else {
                alert('Ocurrió un error al guardar el diagnóstico. Intente de nuevo.');
            }
        })
        .catch(() => {
            alert('Ocurrió un error de red. Intente de nuevo.');
        });
    });
    </script>
</body>
</html> 