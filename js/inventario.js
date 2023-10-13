$(document).ready(function () {
    // Handler para el botón "Guardar Entrada" en entrada.php
    $("#entrada_id").click(function () {
        $.ajax({
            url: "../controllers/entradaController.php", // Ruta al script que manejará el guardado de entrada
            method: "POST",
            data: $("#entrada-form").serialize(), // Serializamos el formulario
            success: function (response) {
                // Maneja la respuesta del servidor
                if (response.success) {
                    // Obtenemos la cantidad actual del inventario desde la base de datos
                    $.ajax({
                        url: "../controllers/entradaController.php", // Ruta al script que obtiene la cantidad actual del inventario
                        method: "GET",
                        data: { inventario_id: response.inventario_id },
                        success: function (data) {
                            var cantidadActual = parseInt(data.cantidad);
                            // Sumamos la cantidad de entrada a la cantidad actual
                            var nuevaCantidad = cantidadActual + response.cantidad;

                            // Actualizamos la cantidad en la tabla de inventario
                            $.ajax({
                                url: "../controllers/entradaController.php", // Ruta al script que actualiza la cantidad en el inventario
                                method: "POST",
                                data: { inventario_id: response.inventario_id, cantidad: nuevaCantidad },
                                success: function (result) {
                                    if (result.success) {
                                        // Actualización exitosa, puedes mostrar un mensaje de éxito
                                    } else {
                                        // Maneja errores si la actualización no se pudo completar
                                        console.error('Error al actualizar la cantidad en el inventario', result.error);
                                    }
                                },
                                error: function (error) {
                                    // Maneja errores en la solicitud de actualización del inventario
                                    console.error('Error en la solicitud de actualización del inventario', error);
                                }
                            });
                        },
                        error: function (error) {
                            // Maneja errores en la solicitud de obtener cantidad actual del inventario
                            console.error('Error en la solicitud de obtener cantidad actual del inventario', error);
                        }
                    });
                } else {
                    // Maneja errores si la entrada no se pudo guardar
                    console.error('Error al guardar la entrada', response.error);
                }
            },
            error: function (error) {
                // Maneja errores en la solicitud de guardar entrada
                console.error('Error en la solicitud de guardar entrada', error);
            }
        });
    });

    // Handler para el botón "Guardar Salida" en salida.php
    $("#salida_id").click(function () {
        $.ajax({
            url: "../controllers/salidaController.php", // Ruta al script que manejará el guardado de salida
            method: "POST",
            data: $("#salida-form").serialize(), // Serializamos el formulario
            success: function (response) {
                // Maneja la respuesta del servidor
                if (response.success) {
                    // Obtenemos la cantidad actual del inventario desde la base de datos
                    $.ajax({
                        url: "../controllers/salidaController.php", // Ruta al script que obtiene la cantidad actual del inventario
                        method: "GET",
                        data: { inventario_id: response.inventario_id },
                        success: function (data) {
                            var cantidadActual = parseInt(data.cantidad);
                            // Restamos la cantidad de salida a la cantidad actual
                            var nuevaCantidad = cantidadActual - response.cantidad;

                            // Actualizamos la cantidad en la tabla de inventario
                            $.ajax({
                                url: "../controllers/salidaController.php", // Ruta al script que actualiza la cantidad en el inventario
                                method: "POST",
                                data: { inventario_id: response.inventario_id, cantidad: nuevaCantidad },
                                success: function (result) {
                                    if (result.success) {
                                        // Actualización exitosa, puedes mostrar un mensaje de éxito
                                    } else {
                                        // Maneja errores si la actualización no se pudo completar
                                        console.error('Error al actualizar la cantidad en el inventario', result.error);
                                    }
                                },
                                error: function (error) {
                                    // Maneja errores en la solicitud de actualización del inventario
                                    console.error('Error en la solicitud de actualización del inventario', error);
                                }
                            });
                        },
                        error: function (error) {
                            // Maneja errores en la solicitud de obtener cantidad actual del inventario
                            console.error('Error en la solicitud de obtener cantidad actual del inventario', error);
                        }
                    });
                } else {
                    // Maneja errores si la salida no se pudo guardar
                    console.error('Error al guardar la salida', response.error);
                }
            },
            error: function (error) {
                // Maneja errores en la solicitud de guardar salida
                console.error('Error en la solicitud de guardar salida', error);
            }
        });
    });
});


