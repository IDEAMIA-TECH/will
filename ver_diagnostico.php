<?php
require_once 'config/database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$diagnostico_id = $_GET['id'];

try {
    // Obtener información del diagnóstico
    $stmt = $conn->prepare("SELECT * FROM diagnosticos WHERE id = ?");
    $stmt->execute([$diagnostico_id]);
    $diagnostico = $stmt->fetch();

    if (!$diagnostico) {
        throw new Exception("Diagnóstico no encontrado");
    }

    // Obtener respuestas
    $stmt = $conn->prepare("
        SELECT r.*, p.texto_pregunta, s.nombre as seccion_nombre
        FROM respuestas r
        LEFT JOIN preguntas p ON r.pregunta_id = p.id
        LEFT JOIN secciones s ON p.seccion_id = s.id
        WHERE r.diagnostico_id = ?
        ORDER BY s.id, p.orden, r.pregunta_id
    ");
    $stmt->execute([$diagnostico_id]);
    $respuestas = $stmt->fetchAll();

    // Obtener recomendaciones
    $stmt = $conn->prepare("SELECT * FROM recomendaciones WHERE diagnostico_id = ? ORDER BY prioridad");
    $stmt->execute([$diagnostico_id]);
    $recomendaciones = $stmt->fetchAll();

    // Obtener plan de acción
    $stmt = $conn->prepare("SELECT * FROM plan_accion WHERE diagnostico_id = ? ORDER BY fecha_limite");
    $stmt->execute([$diagnostico_id]);
    $plan_accion = $stmt->fetchAll();

    // Calcular totales por sección
    $totales_seccion = [];
    $puntuacion_total = 0;
    $total_preguntas = 0;
    $max_pregunta = 4; // máximo valor por pregunta
    // IDs de preguntas a considerar (excluyendo switches y textos abiertos)
    $bloques = [
        'PITS Comercial' => [
            'Dirección Comercial' => ['PITS_DIRCOM_1','PITS_DIRCOM_2','PITS_DIRCOM_3'],
            'Proceso de Gestión Comercial' => ['PITS_GESTION_1','PITS_GESTION_2','PITS_GESTION_3','PITS_GESTION_4'],
            'Sistema de Información' => ['PITS_INFO_1'],
            'Estrategias y Tácticas' => ['PITS_ESTRATEGIA_1'],
            'Métricas Comerciales' => ['PITS_METRICAS_1','PITS_METRICAS_2'],
        ],
        'PITS CALIDAD & PRODUCTIVIDAD' => [
            'Enfoque por Resultados' => ['PITS_RES_1','PITS_RES_2','PITS_RES_3','PITS_RES_4'],
            'Enfoque por Proceso' => ['PITS_PROC_1','PITS_PROC_2','PITS_PROC_3','PITS_PROC_4'],
            'Calidad y Mejora Continua' => ['PITS_CAL_1','PITS_CAL_2','PITS_CAL_3','PITS_CAL_4'],
        ],
        'PITS MAXIMIZACIÓN DE CAPACIDADES' => [
            'Impacto del Capital Humano en la Productividad' => ['PITS_CAPITAL_1','PITS_CAPITAL_2','PITS_CAPITAL_3','PITS_CAPITAL_4'],
            'Inventario de Capacidades' => ['PITS_INVENTARIO_1','PITS_INVENTARIO_2','PITS_INVENTARIO_3','PITS_INVENTARIO_4'],
            'Maximización de Capacidades' => ['PITS_MAX_1','PITS_MAX_2','PITS_MAX_3'],
        ],
    ];
    // Indexar respuestas por pregunta_id
    $respuestas_idx = [];
    foreach ($respuestas as $r) {
        $respuestas_idx[$r['pregunta_id']] = $r;
    }
    foreach ($bloques as $seccion => $subbloques) {
        foreach ($subbloques as $ids) {
            foreach ($ids as $pid) {
                if (isset($respuestas_idx[$pid]) && $respuestas_idx[$pid]['calificacion'] !== null && $respuestas_idx[$pid]['calificacion'] !== '') {
                    $puntuacion_total += (int)$respuestas_idx[$pid]['calificacion'];
                    $total_preguntas++;
                }
            }
        }
    }
    $max_total = $total_preguntas * $max_pregunta;
    $porcentaje = $max_total > 0 ? ($puntuacion_total / $max_total) * 100 : 0;

} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados del Diagnóstico - Gestión Disruptiva</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="message message-error fade-in">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php else: ?>
            <div class="card fade-in">
                <div class="card-header">
                    <h1 class="card-title">Resultados del Diagnóstico</h1>
                </div>
                <div class="card-body">
                    <!-- Información General -->
                    <div class="diagnostic-section">
                        <h2 class="section-title">Información General</h2>
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Fecha de Diagnóstico</th>
                                    <td><?php echo htmlspecialchars($diagnostico['fecha_diagnostico']); ?></td>
                                </tr>
                                <tr>
                                    <th>Nombre del Cliente</th>
                                    <td><?php echo htmlspecialchars($diagnostico['nombre_cliente']); ?></td>
                                </tr>
                                <tr>
                                    <th>Industria</th>
                                    <td><?php echo htmlspecialchars($diagnostico['industria']); ?></td>
                                </tr>
                                <tr>
                                    <th>Tamaño de la Empresa</th>
                                    <td><?php echo htmlspecialchars($diagnostico['tamano_empresa']); ?></td>
                                </tr>
                                <tr>
                                    <th>Puntuación Total</th>
                                    <td><?php echo $puntuacion_total . ' / ' . $max_total; ?></td>
                                </tr>
                                <tr>
                                    <th>Porcentaje de Implementación</th>
                                    <td><?php echo number_format($porcentaje, 1); ?>%</td>
                                </tr>
                            </table>
                        </div>
                        <!-- Tabla resumen de totales por sección -->
                        <div class="table-responsive" style="margin-top:20px;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sección</th>
                                        <th>Total de Calificación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($totales_seccion as $seccion => $total): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($seccion); ?></td>
                                        <td><?php echo htmlspecialchars($total); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Resultados por Sección -->
                    <div class="diagnostic-section">
                        <h2 class="section-title">Resultados por Sección</h2>
                        <?php
                        // Agrupar respuestas por sección y bloque personalizado
                        $bloques = [
                            'PITS Comercial' => [
                                'Dirección Comercial' => ['PITS_DIRCOM_1','PITS_DIRCOM_2','PITS_DIRCOM_3'],
                                'Proceso de Gestión Comercial' => ['PITS_GESTION_1','PITS_GESTION_2','PITS_GESTION_3','PITS_GESTION_4'],
                                'Sistema de Información' => ['PITS_INFO_1'],
                                'Estrategias y Tácticas' => ['PITS_ESTRATEGIA_1'],
                                'Métricas Comerciales' => ['PITS_METRICAS_1','PITS_METRICAS_2'],
                            ],
                            'PITS CALIDAD & PRODUCTIVIDAD' => [
                                'Enfoque por Resultados' => ['PITS_RES_1','PITS_RES_2','PITS_RES_3','PITS_RES_4'],
                                'Enfoque por Proceso' => ['PITS_PROC_1','PITS_PROC_2','PITS_PROC_3','PITS_PROC_4'],
                                'Calidad y Mejora Continua' => ['PITS_CAL_1','PITS_CAL_2','PITS_CAL_3','PITS_CAL_4'],
                                'Oportunidad de Mejora' => ['PITS_MEJ_1','PITS_MEJ_2'],
                            ],
                            'PITS MAXIMIZACIÓN DE CAPACIDADES' => [
                                'Impacto del Capital Humano en la Productividad' => ['PITS_CAPITAL_1','PITS_CAPITAL_2','PITS_CAPITAL_3','PITS_CAPITAL_4'],
                                'Inventario de Capacidades' => ['PITS_INVENTARIO_1','PITS_INVENTARIO_2','PITS_INVENTARIO_3','PITS_INVENTARIO_4'],
                                'Maximización de Capacidades' => ['PITS_MAX_1','PITS_MAX_2','PITS_MAX_3'],
                            ],
                        ];
                        // Indexar respuestas por pregunta_id
                        $respuestas_idx = [];
                        foreach ($respuestas as $r) {
                            $respuestas_idx[$r['pregunta_id']] = $r;
                        }
                        foreach ($bloques as $seccion => $subbloques): ?>
                            <div class="section-results">
                                <h2><?php echo htmlspecialchars($seccion); ?></h2>
                                <?php foreach ($subbloques as $titulo => $ids):
                                    $suma = 0;
                                    $count = 0;
                                    $max_bloque = count($ids) * $max_pregunta;
                                ?>
                                <h3><?php echo htmlspecialchars($titulo); ?></h3>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Pregunta</th>
                                            <th>Calificación</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($ids as $pid):
                                        if (!isset($respuestas_idx[$pid])) continue;
                                        $r = $respuestas_idx[$pid];
                                        $texto = $r['texto_pregunta'] ?? $pid;
                                        $calif = $r['calificacion'];
                                        $obs = $r['observaciones'];
                                        // Oportunidad de Mejora es texto abierto
                                        if (strpos($pid, 'PITS_MEJ_') === 0) {
                                            echo '<tr><td colspan="3"><strong>' . htmlspecialchars($texto) . ':</strong><br>' . nl2br(htmlspecialchars($obs)) . '</td></tr>';
                                            continue;
                                        }
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($texto) . '</td>';
                                        echo '<td>' . ($calif !== null ? htmlspecialchars($calif) : '-') . '</td>';
                                        echo '<td>' . htmlspecialchars($obs) . '</td>';
                                        echo '</tr>';
                                        if ($calif !== null && $calif !== '') { $suma += (int)$calif; $count++; }
                                    endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight:bold;"><td>Total bloque</td><td><?php echo $suma . ' / ' . $max_bloque; ?></td><td></td></tr>
                                    </tfoot>
                                </table>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Respuestas crudas para depuración -->
                    <div class="diagnostic-section">
                        <h2 class="section-title">Respuestas crudas (debug)</h2>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Diagnóstico</th>
                                        <th>ID Pregunta</th>
                                        <th>Calificación</th>
                                        <th>Observaciones</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($respuestas as $r): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($r['id']); ?></td>
                                        <td><?php echo htmlspecialchars($r['diagnostico_id']); ?></td>
                                        <td><?php echo htmlspecialchars($r['pregunta_id']); ?></td>
                                        <td><?php echo htmlspecialchars($r['calificacion']); ?></td>
                                        <td><?php echo htmlspecialchars($r['observaciones']); ?></td>
                                        <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recomendaciones -->
                    <div class="diagnostic-section">
                        <h2 class="section-title">Recomendaciones Prioritarias</h2>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Prioridad</th>
                                        <th>Recomendación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recomendaciones as $recomendacion): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($recomendacion['prioridad']); ?></td>
                                        <td><?php echo htmlspecialchars($recomendacion['texto_recomendacion']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Plan de Acción -->
                    <div class="diagnostic-section">
                        <h2 class="section-title">Plan de Acción</h2>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Acción</th>
                                        <th>Responsable</th>
                                        <th>Fecha Límite</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($plan_accion as $accion): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($accion['accion']); ?></td>
                                        <td><?php echo htmlspecialchars($accion['responsable']); ?></td>
                                        <td><?php echo htmlspecialchars($accion['fecha_limite']); ?></td>
                                        <td><?php echo htmlspecialchars($accion['estado']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Observaciones Generales -->
                    <?php if (!empty($diagnostico['observaciones_generales'])): ?>
                    <div class="diagnostic-section">
                        <h2 class="section-title">Observaciones Generales</h2>
                        <div class="card">
                            <div class="card-body">
                                <?php echo nl2br(htmlspecialchars($diagnostico['observaciones_generales'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="text-center" style="margin-top: 20px;">
                        <a href="index.php" class="btn btn-primary">Nuevo Diagnóstico</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 