-- Primero eliminamos la clave foránea
ALTER TABLE respuestas DROP FOREIGN KEY respuestas_ibfk_2;

-- Modificamos el tipo de columna pregunta_id
ALTER TABLE respuestas MODIFY COLUMN pregunta_id VARCHAR(10);

-- Eliminamos el índice existente
DROP INDEX idx_respuestas_pregunta ON respuestas;

-- Creamos un nuevo índice para la columna VARCHAR
CREATE INDEX idx_respuestas_pregunta ON respuestas(pregunta_id);

-- Eliminamos la restricción existente de calificacion
ALTER TABLE respuestas DROP CONSTRAINT IF EXISTS respuestas_chk_1;

-- Agregamos la nueva restricción que permite valores 0 y 1
ALTER TABLE respuestas ADD CONSTRAINT respuestas_chk_1 CHECK (calificacion BETWEEN 0 AND 5); 