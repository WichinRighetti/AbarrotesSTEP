document.addEventListener("DOMContentLoaded", function () {
    // Función para mostrar los productos en la tabla
    function mostrarProductos() {
        var tbody = document.getElementById("productos-table-body");

        productos.forEach(function (producto) {
            var row = document.createElement("tr");
            row.innerHTML = `
                <td>${producto.producto_id}</td>
                <td>${producto.categoria_id.nombre}</td>
                <td>${producto.subcategoria_id.nombre}</td>
                <td>${producto.nombre}</td>
                <td>${producto.descripcion}</td>
                <td>${producto.foto}</td>
                <td>${producto.estatus}</td>
            `;
            tbody.appendChild(row);
        });
    }

    // Llama a la función para mostrar productos al cargar la página
    mostrarProductos();
});