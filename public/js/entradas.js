$(document).ready(function () {
    $("#guardar-entrada").click(function () {
        // Captura los datos del formulario
        var fechaEntrada = $("#fecha-entrada").val();
        var cantidadEntrada = $("#cantidad-entrada").val();
        var observacionesEntrada = $("#observaciones-entrada").val();

        // Crea un objeto con los datos para enviar al servidor
        var datosEntrada = {
            fecha: fechaEntrada,
            cantidad: cantidadEntrada,
            observaciones: observacionesEntrada
        };

        // Envía los datos al servidor a través de una solicitud AJAX
        $.ajax({
            type: "POST",
            url: "guardar-entrada.php", // Reemplaza con la URL correcta de tu script PHP
            data: datosEntrada,
            success: function (response) {
                // Maneja la respuesta del servidor (puede ser una confirmación de éxito o error)
                if (response === "success") {
                    alert("Entrada guardada exitosamente");
                    // Puedes hacer otras acciones, como limpiar el formulario, actualizar la lista de entradas, etc.
                } else {
                    alert("Error al guardar la entrada");
                }
            }
        });
    });
});