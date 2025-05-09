-- Eliminar la restricción existente si existe
ALTER TABLE respuestas DROP CONSTRAINT IF EXISTS respuestas_chk_1;

-- Agregar la nueva restricción que permite valores entre 0 y 5
ALTER TABLE respuestas ADD CONSTRAINT respuestas_chk_1 CHECK (calificacion BETWEEN 0 AND 5);

-- Verificar que la restricción se aplicó correctamente
SELECT CONSTRAINT_NAME, CHECK_CLAUSE 
FROM information_schema.TABLE_CONSTRAINTS 
WHERE TABLE_NAME = 'respuestas' 
AND CONSTRAINT_TYPE = 'CHECK'; 