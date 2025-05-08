# Formulario de Diagnóstico - Gestión Disruptiva

## Información General
- **Fecha de Diagnóstico:** [Fecha]
- **Nombre del Cliente:** [Nombre]
- **Industria:** [Industria]
- **Tamaño de la Empresa:** [Tamaño]

## Diagnóstico de Gestión Disruptiva

### Escala de Calificación
- 1: No implementado / No existe
- 2: En fase inicial / Muy básico
- 3: En desarrollo / Parcialmente implementado
- 4: Bien implementado / Funcionando
- 5: Excelente / Mejores prácticas

### 1. Liderazgo y Cultura
| Pregunta | Calificación (1-5) | Observaciones |
|----------|-------------------|---------------|
| Liderazgo adaptativo | [ ] | |
| Cultura de innovación | [ ] | |
| Tolerancia al riesgo | [ ] | |
| Mentalidad de crecimiento | [ ] | |
**Puntuación Total Sección:** [ ]/20

### 2. Estrategia y Visión
| Pregunta | Calificación (1-5) | Observaciones |
|----------|-------------------|---------------|
| Visión clara de futuro | [ ] | |
| Estrategia digital | [ ] | |
| Adaptabilidad al cambio | [ ] | |
| Planificación disruptiva | [ ] | |
**Puntuación Total Sección:** [ ]/20

### 3. Tecnología e Innovación
| Pregunta | Calificación (1-5) | Observaciones |
|----------|-------------------|---------------|
| Infraestructura tecnológica | [ ] | |
| Procesos de innovación | [ ] | |
| Adopción de nuevas tecnologías | [ ] | |
| Transformación digital | [ ] | |
**Puntuación Total Sección:** [ ]/20

### 4. Talento y Organización
| Pregunta | Calificación (1-5) | Observaciones |
|----------|-------------------|---------------|
| Gestión del talento | [ ] | |
| Estructura organizacional | [ ] | |
| Desarrollo de capacidades | [ ] | |
| Cultura de aprendizaje | [ ] | |
**Puntuación Total Sección:** [ ]/20

### 5. Operaciones y Procesos
| Pregunta | Calificación (1-5) | Observaciones |
|----------|-------------------|---------------|
| Eficiencia operativa | [ ] | |
| Procesos ágiles | [ ] | |
| Automatización | [ ] | |
| Gestión de calidad | [ ] | |
**Puntuación Total Sección:** [ ]/20

## Resumen de Resultados
- **Puntuación Total:** [ ]/100
- **Porcentaje de Implementación:** [ ]%

### Interpretación de Resultados
- 0-20%: Fase inicial - Necesita desarrollo significativo
- 21-40%: En desarrollo - Requiere mejoras importantes
- 41-60%: Implementación media - Áreas de mejora identificadas
- 61-80%: Bien implementado - Algunas mejoras necesarias
- 81-100%: Excelente - Mantener y optimizar

## Observaciones Generales
[Escriba aquí las observaciones generales]

## Recomendaciones Prioritarias
1. [Recomendación 1]
2. [Recomendación 2]
3. [Recomendación 3]

## Plan de Acción
| Acción | Responsable | Fecha Límite | Estado |
|--------|-------------|--------------|---------|
| [Acción 1] | [ ] | [ ] | [ ] |
| [Acción 2] | [ ] | [ ] | [ ] |
| [Acción 3] | [ ] | [ ] | [ ] |

## Esquema de Base de Datos

### Tabla: diagnosticos
```sql
CREATE TABLE diagnosticos (
    id SERIAL PRIMARY KEY,
    fecha_diagnostico DATE NOT NULL,
    nombre_cliente VARCHAR(255) NOT NULL,
    industria VARCHAR(100),
    tamano_empresa VARCHAR(50),
    puntuacion_total INTEGER,
    porcentaje_implementacion DECIMAL(5,2),
    observaciones_generales TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabla: secciones
```sql
CREATE TABLE secciones (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    puntuacion_maxima INTEGER DEFAULT 20
);
```

### Tabla: preguntas
```sql
CREATE TABLE preguntas (
    id SERIAL PRIMARY KEY,
    seccion_id INTEGER REFERENCES secciones(id),
    texto_pregunta TEXT NOT NULL,
    orden INTEGER NOT NULL
);
```

### Tabla: respuestas
```sql
CREATE TABLE respuestas (
    id SERIAL PRIMARY KEY,
    diagnostico_id INTEGER REFERENCES diagnosticos(id),
    pregunta_id INTEGER REFERENCES preguntas(id),
    calificacion INTEGER CHECK (calificacion BETWEEN 1 AND 5),
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabla: recomendaciones
```sql
CREATE TABLE recomendaciones (
    id SERIAL PRIMARY KEY,
    diagnostico_id INTEGER REFERENCES diagnosticos(id),
    texto_recomendacion TEXT NOT NULL,
    prioridad INTEGER CHECK (prioridad BETWEEN 1 AND 3),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabla: plan_accion
```sql
CREATE TABLE plan_accion (
    id SERIAL PRIMARY KEY,
    diagnostico_id INTEGER REFERENCES diagnosticos(id),
    accion TEXT NOT NULL,
    responsable VARCHAR(255),
    fecha_limite DATE,
    estado VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Datos Iniciales para Secciones
```sql
INSERT INTO secciones (nombre, descripcion) VALUES
    ('Liderazgo y Cultura', 'Evaluación del liderazgo y la cultura organizacional'),
    ('Estrategia y Visión', 'Evaluación de la estrategia y visión de la organización'),
    ('Tecnología e Innovación', 'Evaluación de capacidades tecnológicas e innovación'),
    ('Talento y Organización', 'Evaluación del talento y estructura organizacional'),
    ('Operaciones y Procesos', 'Evaluación de operaciones y procesos');
```

### Datos Iniciales para Preguntas
```sql
-- Liderazgo y Cultura
INSERT INTO preguntas (seccion_id, texto_pregunta, orden) VALUES
    (1, 'Liderazgo adaptativo', 1),
    (1, 'Cultura de innovación', 2),
    (1, 'Tolerancia al riesgo', 3),
    (1, 'Mentalidad de crecimiento', 4);

-- Estrategia y Visión
INSERT INTO preguntas (seccion_id, texto_pregunta, orden) VALUES
    (2, 'Visión clara de futuro', 1),
    (2, 'Estrategia digital', 2),
    (2, 'Adaptabilidad al cambio', 3),
    (2, 'Planificación disruptiva', 4);

-- Tecnología e Innovación
INSERT INTO preguntas (seccion_id, texto_pregunta, orden) VALUES
    (3, 'Infraestructura tecnológica', 1),
    (3, 'Procesos de innovación', 2),
    (3, 'Adopción de nuevas tecnologías', 3),
    (3, 'Transformación digital', 4);

-- Talento y Organización
INSERT INTO preguntas (seccion_id, texto_pregunta, orden) VALUES
    (4, 'Gestión del talento', 1),
    (4, 'Estructura organizacional', 2),
    (4, 'Desarrollo de capacidades', 3),
    (4, 'Cultura de aprendizaje', 4);

-- Operaciones y Procesos
INSERT INTO preguntas (seccion_id, texto_pregunta, orden) VALUES
    (5, 'Eficiencia operativa', 1),
    (5, 'Procesos ágiles', 2),
    (5, 'Automatización', 3),
    (5, 'Gestión de calidad', 4);
```

### Índices Recomendados
```sql
CREATE INDEX idx_diagnosticos_fecha ON diagnosticos(fecha_diagnostico);
CREATE INDEX idx_respuestas_diagnostico ON respuestas(diagnostico_id);
CREATE INDEX idx_respuestas_pregunta ON respuestas(pregunta_id);
CREATE INDEX idx_plan_accion_diagnostico ON plan_accion(diagnostico_id);
CREATE INDEX idx_recomendaciones_diagnostico ON recomendaciones(diagnostico_id);
```

---
*Este esquema de base de datos está diseñado para almacenar y gestionar los diagnósticos de gestión disruptiva, permitiendo un seguimiento detallado de las evaluaciones, respuestas y planes de acción.*
