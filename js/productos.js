// Función para cargar los productos en la tabla

// Función para cambiar el estado de un producto
function cambiarEstadoProducto(productoId) {
    // Realiza una solicitud AJAX (puedes usar jQuery para facilitar esto)
    $.ajax({
        url: 'producto.php',
        data: { producto_id: productoId, nuevo_estado: false }, // Envía el ID del producto y el nuevo estado
        success: function (response) {

            console.log('Estado del producto cambiado a false');

        },
        error: function (xhr, status, error) {
            // Ocurrió un error durante la solicitud AJAX, maneja el error aquí
            console.error('Error al cambiar el estado del producto:', error);
        }
    });
}

// Agrega un evento click al botón
$(document).on('click', '.btn-outline-secondary[data-id]', function () {
    var productoId = $(this).data('id'); // Obtiene el ID del producto desde el atributo data-id
    cambiarEstadoProducto(productoId); // Llama a la función para cambiar el estado del producto
});