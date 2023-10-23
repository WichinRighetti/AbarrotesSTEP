//get inventarios
function getInventary() {
    console.log('getting data...');
    //console request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', '/AbarrotesStep/controllers/inventarioController.php', true);
    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function () {
        //check status
        if (x.status == 200 && x.readyState == 4) {
            showInventary(x.responseText);
        }
    }
}

//get products by filter
function getInventaryByFilter() {
    var first = true;
    var url = "/AbarrotesStep/controllers/inventarioController.php"

    //selects
    var selectCategoria = document.getElementById('slcCategoria');
    var selectSubcategoria = document.getElementById('slcSubcategoria');
    var txtNombre =document.getElementById("txtNombre");

    if(selectCategoria.value != 0){
        if(first){
            url+='?';
        }
        url+="categoria_id="+selectCategoria.value;
        first = false;
    }
    if(selectSubcategoria.value != 0){
        if(!first){
            url+='&';
        }else{
            url+='?';
        }
        url+="subcategoria_id="+selectSubcategoria.value;
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
            showInventary(x.responseText);
        }
    }
}

//show Products
function showInventary(data) {
    //get main div
    var mainTable = document.getElementById('inventary-table-body');
    mainTable.innerHTML='';
    //parse to JSON
    var JSONdata = JSON.parse(data);
    //get cars array
    var inventarios = JSONdata.inventario;
    //read data
    for (var i = 0; i < inventarios.length; i++) {
        var products = JSONdata.inventario[i].producto;
        //create components
        var tr = document.createElement("tr");

        var tdCheckBox = document.createElement("td");
        tdCheckBox.className = "align-middle";

        var divCheckBox = document.createElement('div');
        divCheckBox.className = "custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top";
        
        var checkBox = document.createElement('input');
        checkBox.type = "checkbox";
        //checkBox.className = "custom-control-input";
        checkBox.id = "item_"+products["producto_id"];

        //var label = document.createElement('label');
        //label.className = "custom-control-label";
        var tdAlmacen = document.createElement("td");
        tdAlmacen.innerHTML = inventarios[i]["almacen"]["nombre"];

        var tdID = document.createElement("td");
        tdID.innerHTML = products['producto_id'];

        var td1 = document.createElement("td");
        td1.innerHTML = products['categoria_id']['nombre'];

        var td2 = document.createElement("td");
        td2.innerHTML = products['subcategoria_id']['nombre'];

        var td3 = document.createElement("td");
        td3.innerHTML = products['nombre'];

        var td4 = document.createElement("td");
        td4.innerHTML = products['descripcion'];
        
        var td5 = document.createElement("td");
        td5.innerHTML = products['foto'];

        var td6 = document.createElement("td");
        td6.innerHTML = products['estatus'];

        var td7 = document.createElement("td");
        td7.innerHTML = inventarios[i]["cantidad"];

        var td8 = document.createElement("td");

        var divButtons = document.createElement("div");
        divButtons.className = "btn-group align-top";

        //add components

        divCheckBox.appendChild(checkBox);
        //divCheckBox.appendChild(label);

        tdCheckBox.appendChild(divCheckBox);

        tr.appendChild(tdCheckBox);
        tr.appendChild(tdAlmacen);
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tr.appendChild(td4);
        tr.appendChild(td5);
        tr.appendChild(td6);
        tr.appendChild(td7);
        tr.appendChild(td8);

        mainTable.appendChild(tr);
    }
}

function getCategories(){
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
            showCategories(x.responseText);
        }
    }
}

function showCategories(data){
    //select
    var select = document.getElementById('slcCategoria');
    //parse to JSON
    var JSONData = JSON.parse(data);
    //get brands array
    var categorias = JSONData.categoria;

    var optionDefault = document.createElement('option');
    optionDefault.value = 0;
    optionDefault.innerHTML= "Categoria";

    select.appendChild(optionDefault);

    //read data
    for(var i = 0;i<categorias.length;i++){
        //console.log(categorias[i].categoria_id);
        //option
        var option = document.createElement('option');
        option.value = categorias[i].categoria_id;
        option.innerHTML=categorias[i].nombre;

        select.appendChild(option);
    }
}

function getSubcategories(){
    console.log('getting data...');
    //console request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', '/AbarrotesStep/controllers/subcategoriaController.php', true);
    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function () {
        //check status
        if (x.status == 200 && x.readyState == 4) {
            showSubcategories(x.responseText);
        }
    }
}

function showSubcategories(data){
    //select
    var select = document.getElementById('slcSubcategoria');
    //parse to JSON
    var JSONData = JSON.parse(data);
    //get brands array
    var subcategorias = JSONData.subcategoria;

    var optionDefault = document.createElement('option');
    optionDefault.value = 0;
    optionDefault.innerHTML= "Subcategoria";

    select.appendChild(optionDefault);

    //read data
    for(var i = 0;i<subcategorias.length;i++){
        //console.log(categorias[i].categoria_id);
        //option
        var option = document.createElement('option');
        option.value = subcategorias[i].subcategoria_id;
        option.innerHTML=subcategorias[i].nombre;

        select.appendChild(option);
    }
}