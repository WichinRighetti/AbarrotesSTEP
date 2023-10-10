// Función para manejar el clic en el botón "Entradas"
function handleEntradasButtonClick() {
    // Aquí puedes realizar acciones relacionadas con las Entradas
    // Por ejemplo, puedes hacer una solicitud AJAX para cargar los datos de Entradas
    $.ajax({
        url: 'entrada.php', // Ruta a tu archivo PHP que maneja las Entradas
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            // Manipula los datos de Entradas aquí y actualiza tu tabla en el HTML
            console.log('Datos de Entradas:', data);
            // Actualiza la tabla con los datos de Entradas (puedes usar jQuery o JavaScript puro)
        },
        error: function (error) {
            console.error('Error al cargar los datos de Entradas:', error);
        }
    });
}

// Función para manejar el clic en el botón "Salidas"
function handleSalidasButtonClick() {
    // Aquí puedes realizar acciones relacionadas con las Salidas
    // Por ejemplo, puedes hacer una solicitud AJAX para cargar los datos de Salidas
    $.ajax({
        url: 'salida.php', // Ruta a tu archivo PHP que maneja las Salidas
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            // Manipula los datos de Salidas aquí y actualiza tu tabla en el HTML
            console.log('Datos de Salidas:', data);
            // Actualiza la tabla con los datos de Salidas (puedes usar jQuery o JavaScript puro)
        },
        error: function (error) {
            console.error('Error al cargar los datos de Salidas:', error);
        }
    });
}