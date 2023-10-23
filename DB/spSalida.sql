DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spSalida`(registroCantidad INT, registroId INT)
BEGIN -- Insertar la cantidad en la tabla entrada
INSERT INTO salida (salida.inventario_id , salida.cantidad) VALUES (registroId, registroCantidad);
-- Actualizar la cantidad en la tabla inventario 
UPDATE inventario SET inventario.cantidad = (inventario.cantidad - registroCantidad) 
WHERE inventario.inventario_id = registroId; 
END$$
DELIMITER ;
