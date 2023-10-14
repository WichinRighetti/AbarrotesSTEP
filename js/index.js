//get products
function getProducts() {
    console.log('getting data...');
    //console request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', 'http://localhost/abarrotesStep/controllers/productoController.php', true);
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

//get products by filter
function getProductsByFilter() {
    var first = true;
    var url = "http://localhost/abarrotesStep/controllers/productoController.php?"

    //selects
    var selectCategoria = document.getElementById('slcCategoria');
    var selectSubcategoria = document.getElementById('slcSubcategoria');
    var txtNombre =document.getElementById("txtNombre");

    if(selectCategoria.value != 0){
        url+="categoria_id="+selectCategoria.value;
        first = false;
    }
    if(selectSubcategoria.value != 0){
        if(!first){
            url+="&";
        }
        url+="subcategoria_id="+selectSubcategoria.value;
        first = false;
    }
    if(txtNombre.value != ""){
        if(!first){
            url+="&";
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
            showProducts(x.responseText);
        }
    }
}

//show Products
function showProducts(data) {
    //get main div
    var mainTable = document.getElementById('productos-table-body');
    mainTable.innerHTML='';
    //parse to JSON
    var JSONdata = JSON.parse(data);
    //get cars array
    var products = JSONdata.producto;
    //read data
    for (var i = 0; i < products.length; i++) {
        //console.log(products[i]);
        //create components
        var tr = document.createElement("tr");

        var tdCheckBox = document.createElement("td");
        tdCheckBox.className = "align-middle";

        var divCheckBox = document.createElement('div');
        divCheckBox.className = "custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top";
        
        var checkBox = document.createElement('input');
        checkBox.type = "checkbox";
        //checkBox.className = "custom-control-input";
        checkBox.id = "item_"+products[i]["producto_id"];

        //var label = document.createElement('label');
        //label.className = "custom-control-label";
        
        var tdID = document.createElement("td");
        tdID.innerHTML = products[i]['producto_id'];

        var td1 = document.createElement("td");
        td1.innerHTML = products[i]['categoria_id']['nombre'];

        var td2 = document.createElement("td");
        td2.innerHTML = products[i]['subcategoria_id']['nombre'];

        var td3 = document.createElement("td");
        td3.innerHTML = products[i]['nombre'];

        var td4 = document.createElement("td");
        td4.innerHTML = products[i]['descripcion'];
        
        var td5 = document.createElement("td");
        td5.innerHTML = products[i]['foto'];

        var td6 = document.createElement("td");
        td6.innerHTML = products[i]['estatus'];

        var td7 = document.createElement("td");
        td7.innerHTML = "Soy ajeno";

        var td8 = document.createElement("td");

        var divButtons = document.createElement("div");
        divButtons.className = "btn-group align-top";

        var button1 = document.createElement('input');
        button1.type = "button";
        button1.className = "btn btn-sm btn-outline-secondary";
        button1.value = "Edit";

        var button2 = document.createElement('input');
        button2.className = "btn btn-sm btn-outline-secondary deactivate-product";
        button2.type = "button";

        var icon = document.createElement("span");
        icon.className = "fa fa-trash";//buscar icono y agregar manual

        //add components
        button2.appendChild(icon);

        divButtons.appendChild(button1);
        divButtons.appendChild(button2);

        divCheckBox.appendChild(checkBox);
        //divCheckBox.appendChild(label);

        tdCheckBox.appendChild(divCheckBox);

        td8.appendChild(divButtons);

        tr.appendChild(tdCheckBox);
        tr.appendChild(tdID);
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
    x.open('GET', 'http://localhost/abarrotesStep/controllers/categoriaController.php', true);
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
    x.open('GET', 'http://localhost/abarrotesStep/controllers/subcategoriaController.php', true);
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