<?php
require_once '../config/database.php';

try {
    // Obtener secciones
    $stmt = $conn->query("SELECT * FROM secciones ORDER BY id");
    $secciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $html = '';
    
    foreach ($secciones as $seccion) {
        $html .= '
        <div class="seccion-diagnostico" data-seccion="' . $seccion['id'] . '">
            <h4>' . htmlspecialchars($seccion['nombre']) . '</h4>
            <p class="text-muted">' . htmlspecialchars($seccion['descripcion']) . '</p>';
        
        // Obtener preguntas de la sección
        $stmt = $conn->prepare("SELECT * FROM preguntas WHERE seccion_id = ? ORDER BY orden");
        $stmt->execute([$seccion['id']]);
        $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($preguntas as $pregunta) {
            $html .= '
            <div class="pregunta-item">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label mb-2">' . htmlspecialchars($pregunta['texto_pregunta']) . '</label>
                    <div class="calificacion">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input calificacion-input" type="radio" 
                                   name="pregunta_' . $pregunta['id'] . '" 
                                   value="1" data-seccion="' . $seccion['id'] . '">
                            <label class="form-check-label">1</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input calificacion-input" type="radio" 
                                   name="pregunta_' . $pregunta['id'] . '" 
                                   value="2" data-seccion="' . $seccion['id'] . '">
                            <label class="form-check-label">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input calificacion-input" type="radio" 
                                   name="pregunta_' . $pregunta['id'] . '" 
                                   value="3" data-seccion="' . $seccion['id'] . '">
                            <label class="form-check-label">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input calificacion-input" type="radio" 
                                   name="pregunta_' . $pregunta['id'] . '" 
                                   value="4" data-seccion="' . $seccion['id'] . '">
                            <label class="form-check-label">4</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input calificacion-input" type="radio" 
                                   name="pregunta_' . $pregunta['id'] . '" 
                                   value="5" data-seccion="' . $seccion['id'] . '">
                            <label class="form-check-label">5</label>
                        </div>
                    </div>
                </div>
                <div class="observaciones">
                    <textarea class="form-control" name="observacion_' . $pregunta['id'] . '" 
                              placeholder="Observaciones (opcional)"></textarea>
                </div>
            </div>';
        }
        
        $html .= '
            <div class="mt-3">
                <strong>Puntuación de la sección: </strong>
                <span id="puntuacion-seccion-' . $seccion['id'] . '" class="puntuacion-seccion">0</span>/20
            </div>
        </div>';
    }
    
    // Agregar resumen de puntuación total
    $html .= '
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Resumen de Puntuación</h5>
            <div class="progress mb-3">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <strong>Puntuación Total: </strong>
                    <span id="puntuacion-total">0</span>/100
                </div>
                <div>
                    <strong>Porcentaje: </strong>
                    <span id="porcentaje-total">0%</span>
                </div>
            </div>
        </div>
    </div>';
    
    echo $html;
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 