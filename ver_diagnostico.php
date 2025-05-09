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
    foreach ($respuestas as $respuesta) {
        $nombre_seccion = $respuesta['seccion_nombre'] ?? 'Sin sección';
        $es_switch = (strpos($respuesta['pregunta_id'], 'M1S_') === 0);
        if (!isset($totales_seccion[$nombre_seccion])) {
            $totales_seccion[$nombre_seccion] = 0;
        }
        if (!$es_switch) {
            $totales_seccion[$nombre_seccion] += (int)$respuesta['calificacion'];
        }
    }

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
                                    <td><?php echo htmlspecialchars($diagnostico['puntuacion_total']); ?>/100</td>
                                </tr>
                                <tr>
                                    <th>Porcentaje de Implementación</th>
                                    <td><?php echo number_format($diagnostico['porcentaje_implementacion'], 1); ?>%</td>
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
                        $seccion_actual = '';
                        $suma_seccion = 0;
                        $total_respuestas_seccion = 0;
                        foreach ($respuestas as $i => $respuesta):
                            $nombre_seccion = $respuesta['seccion_nombre'] ?? 'Sin sección';
                            $es_switch = (strpos($respuesta['pregunta_id'], 'M1S_') === 0);
                            if ($seccion_actual != $nombre_seccion):
                                if ($seccion_actual != '') {
                                    // Mostrar total de la sección anterior
                                    echo '<tr style="font-weight:bold;"><td colspan="2">Total sección</td><td>' . $suma_seccion . '</td><td></td></tr>';
                                    echo '</table></div>';
                                }
                                $seccion_actual = $nombre_seccion;
                                $suma_seccion = 0;
                                $total_respuestas_seccion = 0;
                        ?>
                            <div class="section-results">
                                <h3><?php echo htmlspecialchars($seccion_actual); ?></h3>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID Pregunta</th>
                                            <th>Pregunta</th>
                                            <th>Calificación</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        <?php endif; ?>
                                        <tr<?php if($es_switch) echo ' style="background:#f5f5f5;"'; ?>>
                                            <td><?php echo htmlspecialchars($respuesta['pregunta_id']); ?></td>
                                            <td><?php echo htmlspecialchars($respuesta['texto_pregunta'] ?? '(Sin texto)'); ?></td>
                                            <td>
                                                <?php
                                                if ($es_switch) {
                                                    echo ($respuesta['calificacion'] == 1) ? 'Sí' : 'No';
                                                } else {
                                                    echo htmlspecialchars($respuesta['calificacion']);
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($respuesta['observaciones']); ?></td>
                                        </tr>
                        <?php 
                            // Solo sumar si NO es switch
                            if (!$es_switch) {
                                $suma_seccion += (int)$respuesta['calificacion']; 
                                $total_respuestas_seccion++;
                            }
                            // Si es la última respuesta o la siguiente es de otra sección, muestra el total
                            $siguiente = $respuestas[$i+1]['seccion_nombre'] ?? null;
                            if ($siguiente !== $seccion_actual) {
                                echo '<tr style="font-weight:bold;"><td colspan="2">Total sección</td><td>' . $suma_seccion . '</td><td></td></tr>';
                                echo '</tbody></table></div>';
                            }
                        endforeach; ?>
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