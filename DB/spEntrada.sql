DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `spEntrada`(registroCantidad INT, registroId INT)
BEGIN -- Insertar la cantidad en la tabla entrada
INSERT INTO entrada (entrada.inventario_id , entrada.cantidad) VALUES (registroId, registroCantidad);
-- Actualizar la cantidad en la tabla inventario 
UPDATE inventario SET inventario.cantidad = (inventario.cantidad + registroCantidad) 
WHERE inventario.inventario_id = registroId; 
END$$
DELIMITER ;
