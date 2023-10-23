$(document).ready(function () {
    // Handler para el botón "Guardar Entrada" en entrada.php
    $("#entrada_id").click(function () {
        $.ajax({
            url: "/AbarrotesSTEP/controllers/entradaController.php", // Ruta al script que manejará el guardado de entrada
            method: "POST",
            data: $("#entrada-form").serialize(), // Serializamos el formulario
            success: function (response) {
                // Maneja la respuesta del servidor
                if (response.success) {
                    // Obtenemos la cantidad actual del inventario desde la base de datos
                    $.ajax({
                        url: "/AbarrotesSTEP/controllers/entradaController.php", // Ruta al script que obtiene la cantidad actual del inventario
                        method: "GET",
                        data: { inventario_id: response.inventario_id },
                        success: function (data) {
                            var cantidadActual = parseInt(data.cantidad);
                            // Sumamos la cantidad de entrada a la cantidad actual
                            var nuevaCantidad = cantidadActual + response.cantidad;

                            // Actualizamos la cantidad en la tabla de inventario
                            $.ajax({
                                url: "/AbarrotesSTEP/controllers/entradaController.php", // Ruta al script que actualiza la cantidad en el inventario
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

function getDataForm(){
    getWarehouses();
    getProducts();
}

function getWarehouses(){
    console.log('getting data...');
    //console request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', '/AbarrotesStep/controllers/almacenController.php', true);
    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function () {
        //check status
        if (x.status == 200 && x.readyState == 4) {
            showWarehouses(x.responseText);
        }
    }
}

function getProducts(){
    console.log('getting data...');
    //console request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', '/AbarrotesStep/controllers/productoController.php', true);
    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function () {
        //check status
        if (x.status == 200 && x.readyState == 4) {
            showProducts(x.responseText);
        }
    }
}

function showWarehouses(data){
    //select
    var selectEntrada = document.getElementById('almacen-entrada');
    var selectSalida = document.getElementById('almacen-salida');
    //parse to JSON
    var JSONData = JSON.parse(data);
    //get brands array
    var almacenes = JSONData.almacen;
 
    /*var optionDefault = document.createElement('option');
    optionDefault.value = 0;
    optionDefault.innerHTML= "Almacenes";
    select.appendChild(optionDefault);
    */
 
    //read data
    for(var i = 0;i<almacenes.length;i++){
        //console.log(categorias[i].categoria_id);
        //option
        var option = document.createElement('option');
        option.value = almacenes[i].almacen_id;
        option.innerHTML=almacenes[i].nombre;
        selectEntrada.appendChild(option);
    }

    
    for(var i = 0;i<almacenes.length;i++){
        //console.log(categorias[i].categoria_id);
        //option
        var option = document.createElement('option');
        option.value = almacenes[i].almacen_id;
        option.innerHTML=almacenes[i].nombre;
        selectSalida.appendChild(option);
    }
}

function showProducts(data){
    //select
    var selectEntrada = document.getElementById('producto-entrada');
    var selectSalida = document.getElementById('producto-salida');
    //parse to JSON
    var JSONData = JSON.parse(data);
    //get brands array
    var productos = JSONData.producto;
    
    /*var optionDefault = document.createElement('option');
    optionDefault.value = 0;
    optionDefault.innerHTML= "Productos";
    select.appendChild(optionDefault);*/
 
    //read data
    for(var i = 0;i< productos.length;i++){
        if (productos[i].estatus){
            var option = document.createElement('option');
            option.value = productos[i].producto_id;
            option.innerHTML=productos[i].nombre;
            selectEntrada.appendChild(option);
        }
    }

    for(var i = 0;i< productos.length;i++){
        if (productos[i].estatus){
            var option = document.createElement('option');
            option.value = productos[i].producto_id;
            option.innerHTML=productos[i].nombre;
            selectSalida.appendChild(option);
        }
    }
}

function getStockByFilter(){
    var first = true;
    var url = "/AbarrotesStep/controllers/inventarioController.php"

    //selects
    let selectAlmacen = document.getElementById('slcAlmacen');
    let selectCategoria = document.getElementById('slcCategoria');
    let txtNombre = document.getElementById("txtNombre");

    if(selectCategoria.value != 0){
        if(first){
            url+='?';
        }
        url+="categoria_id="+selectCategoria.value;
        first = false;
    } else {
        if(first){
            url+='?';
        }
        url+="categoria_id > 0";
        first = false;
    }
    if(selectAlmacen.value != 0){
        if(!first){
            url+='&';
        }else{
            url+='?';
        }
        url+="almacen_id = "+selectAlmacen.value;
        first = false;
    } else {
        if(!first){
            url+='&';
        }else{
            url+='?';
        }
        url+="almacen_id > 0 ";
        first = false;
    }
    if(txtNombre.value != ""){
        if(!first){
            url+='&';
        }else{
            url+='?';
        }
        url+="nombre="+txtNombre.value;
        first = false;
    }
    console.log(url);
    //console request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', url, true);
    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function () {
        //check status
        if (x.status == 200 && x.readyState == 4) {
            showStocks(x.responseText);
        }
    }

}

function showStocks(data){
    //get main div
    var mainTable = document.getElementById('inventario-table-body');
    mainTable.innerHTML = '';
    //parse to JSON
    var JSONdata = JSON.parse(data);
    //get cars array
    var inventario = JSONdata.inventario;
    console.log(inventario);
    //read data
    for (var i = 0; i < inventario.length; i++) {
        //console.log(products[i]);
        //create components
        var tr = document.createElement("tr");
        
        var tdCheckBox = document.createElement("td");
        tdCheckBox.className = "align-middle";

        var divCheckBox = document.createElement('div');
        divCheckBox.className = "custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top";
        
        var checkBox = document.createElement('input');
        checkBox.className = "custom-control-input";
        checkBox.type = "checkbox";
        //checkBox.className = "custom-control-input";
        checkBox.id = "item_"+inventario[i]["inventario_id"];

        //var label = document.createElement('label');
        //label.className = "custom-control-label";
        
        var tdAlmacen = document.createElement("td");
        tdAlmacen.innerHTML = inventario[i]['almacen']['nombre'];

        var tdProductoID = document.createElement("td");
        tdProductoID.innerHTML = inventario[i]['producto']['producto_id'];

        var tdCategoria = document.createElement("td");
        tdCategoria.innerHTML = inventario[i]['producto']['categoria_id']['nombre'];

        var tdSubcategoria = document.createElement("td");
        tdSubcategoria.innerHTML = inventario[i]['producto']['subcategoria_id']['nombre'];

        var tdNombre = document.createElement("td");
        tdNombre.innerHTML = inventario[i]['producto']['nombre'];

        var tdDescripcion = document.createElement("td");
        tdDescripcion.innerHTML = inventario[i]['producto']['descripcion'];
        
        var tdFoto = document.createElement("td");
        tdFoto.innerHTML = inventario[i]['producto']['foto'];

        var tdCantidad = document.createElement("td");
        tdCantidad.innerHTML = inventario[i]['cantidad'];

        var tdNivel = document.createElement("td");
        tdNivel.innerHTML = 'Nivel';

        divCheckBox.appendChild(checkBox);
        //divCheckBox.appendChild(label);

        tdCheckBox.appendChild(divCheckBox);

        tr.appendChild(tdCheckBox);
        tr.appendChild(tdAlmacen);
        tr.appendChild(tdProductoID);
        tr.appendChild(tdCategoria);
        tr.appendChild(tdSubcategoria);
        tr.appendChild(tdNombre);
        tr.appendChild(tdDescripcion);
        tr.appendChild(tdFoto);
        tr.appendChild(tdCantidad);
        tr.appendChild(tdNivel);

        mainTable.appendChild(tr);
    }
}

function loadAlmacenes(){
    console.log('getting data...');
    //console request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', '/AbarrotesStep/controllers/almacenController.php', true);
    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function () {
        //check status
        if (x.status == 200 && x.readyState == 4) {
            showAlmacenes(x.responseText);
        }
    }
}

function showAlmacenes(data){
    //select
    var selectAlmacen = document.getElementById('slcAlmacen');
    //parse to JSON
    var JSONData = JSON.parse(data);
    //get brands array
    var almacenes = JSONData.almacen;

    var optionDefault = document.createElement('option');
    optionDefault.value = 0;
    optionDefault.innerHTML= "Todos";

    selectAlmacen.appendChild(optionDefault);

    //read data
    for(var i = 0;i<almacenes.length;i++){
    //option
        var option = document.createElement('option');
        option.value = almacenes[i].almacen_id;
        option.innerHTML=almacenes[i].nombre;

        selectAlmacen.appendChild(option);
    
    } 
}

function loadCategorias(){
    console.log('getting data...');
    //console request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', '/AbarrotesStep/controllers/categoriaController.php', true);
    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function () {
        //check status
        if (x.status == 200 && x.readyState == 4) {
            showCategorias(x.responseText);
        }
    }
}

function showCategorias(data){
    //select
    var selectCategoria = document.getElementById('slcCategoria');
    //parse to JSON
    var JSONData = JSON.parse(data);
    //get brands array
    var categorias = JSONData.categoria;

    var optionDefault = document.createElement('option');
    optionDefault.value = 0;
    optionDefault.innerHTML= "Todos";

    selectCategoria.appendChild(optionDefault);

    //read data
    for(var i = 0;i<categorias.length;i++){
    //option
        var option = document.createElement('option');
        option.value = categorias[i].categoria_id;
        option.innerHTML=categorias[i].nombre;

        selectCategoria.appendChild(option);
    
    } 
}