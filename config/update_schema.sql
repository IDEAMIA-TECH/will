-- Primero eliminamos la clave foránea
ALTER TABLE respuestas DROP FOREIGN KEY respuestas_ibfk_2;

-- Modificamos el tipo de columna pregunta_id
ALTER TABLE respuestas MODIFY COLUMN pregunta_id VARCHAR(10);

-- Eliminamos el índice existente
DROP INDEX idx_respuestas_pregunta ON respuestas;

-- Creamos un nuevo índice para la columna VARCHAR
CREATE INDEX idx_respuestas_pregunta ON respuestas(pregunta_id); 