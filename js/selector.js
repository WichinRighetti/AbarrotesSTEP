$(document).ready(function () {
    // Manejador de clic en los checkboxes de productos para ENTRADA
    $('#entrada_id').on('click', function () {
        // Obtenemos el ID del producto a través del atributo "data-product-id" del checkbox
        var productoID = $('input[type="checkbox"]:checked').data('product-id');
        if (productoID) {
            // Buscamos la información del producto en la tabla
            var productoRow = $('#item-' + productoID);

            // Llenamos los campos del formulario de entrada
            $('#inventario-id').val(productoID); // Asegúrate de que el ID del campo coincida con el campo oculto en tu formulario
            $('#fecha-entrada').val(""); // Debes definir la fecha adecuada
            $('#cantidad-entrada').val($(productoRow).find('.cantidad-cell').text());
            // Puedes llenar otros campos relacionados con la entrada aquí
        }
    });

    // Manejador de clic en los checkboxes de productos para SALIDA
    $('#salida_id').on('click', function () {
        // Obtenemos el ID del producto a través del atributo "data-product-id" del checkbox
        var productoID = $('input[type="checkbox"]:checked').data('product-id');
        if (productoID) {
            // Buscamos la información del producto en la tabla
            var productoRow = $('#item-' + productoID);

            // Llenamos los campos del formulario de salida
            $('#inventario-id-salida').val(productoID); // Asegúrate de que el ID del campo coincida con el campo oculto en tu formulario de salida
            $('#fecha-salida').val(""); // Debes definir la fecha adecuada
            $('#cantidad-salida').val($(productoRow).find('.cantidad-cell').text());
            // Puedes llenar otros campos relacionados con la salida aquí
        }
    });
});
