$(document).ready(function () {
    // Manejar el clic en el botón "Desactivar productos seleccionados"
    $("#deactivate-product").click(function () {
        // Crear una lista para almacenar los IDs de productos seleccionados
        var selectedProductIds = [];

        // Iterar sobre los checkboxes para encontrar los productos seleccionados
        $("input[type='checkbox']:checked").each(function () {
            // Obtener el ID del producto del atributo "data-id"
            var productId = $(this).data("id");
            selectedProductIds.push(productId);
        });

        // Verificar si se seleccionaron productos
        if (selectedProductIds.length > 0) {
            // Confirmar si el usuario desea desactivar los productos seleccionados
            if (window.confirm("¿Estás seguro de que deseas desactivar los productos seleccionados?")) {
                // Enviar una solicitud AJAX al servidor para desactivar los productos
                $.ajax({
                    url: '../controllers/productoController.php', // Ruta al controlador de productos
                    type: 'POST', // Método HTTP POST
                    data: { idDelete: selectedProductIds }, // Enviar los IDs de productos a desactivar
                    success: function (response) {
                        // Manejar la respuesta del servidor, actualizar la tabla, o mostrar un mensaje al usuario
                        if (response.status === 0) {
                            alert("Productos desactivados exitosamente");
                            location.reload(); // Actualizar la página
                        } else {
                            alert("Error al desactivar productos: " + response.errorMessage);
                        }
                    },
                    error: function () {
                        alert("Error en la solicitud de desactivar productos");
                    }
                });
            }
        } else {
            alert("Por favor, selecciona al menos un producto para desactivar.");
        }
    });
});


