//get products
function getProducts() {
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

function getProduct(id){
    //console request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', '/AbarrotesStep/controllers/productoController.php?producto_id='+id+'', true);
    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function () {
        //check status
        if (x.status == 200 && x.readyState == 4) {
            var JSONData = JSON.parse(x.responseText);
            //check status
            if(JSONData.status == 0){
                showProduct(JSONData.producto);
            }else{
                alert(JSONData.errorMessage);
            }
        }
    }
}

function showProduct(product){
    console.log(product);
    document.getElementById("producto_id").value = product.producto_id;
    document.getElementById("categoria").value = product.categoria_id.categoria_id;
    document.getElementById("subcategoria").value = product.subcategoria_id.subcategoria_id;
    document.getElementById("nombre").value = product.nombre;
    document.getElementById("descripcion").value = product.descripcion;
    document.getElementById("foto").value = product.foto;
}

//get products by filter
function getProductsByFilter() {
    var first = true;
    var url = "/AbarrotesStep/controllers/productoController.php"

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
    } else {
        if(first){
            url+='?';
        }
        url+="categoria_id>0";
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
    } else {
        if(!first){
            url+='&';
        }else{
            url+='?';
        }
        url+="subcategoria_id>0";
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
    var mainTable = document.getElementById('producto-table-body');
    mainTable.innerHTML = '';
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

        var tdEstatus = document.createElement("td");
        var iconEstatus = document.createElement("i");
        if (products[i]['estatus']){
            iconEstatus.className = "fa fa-fw text-secondary cursor-pointer fa-toggle-on";
        } else {
            iconEstatus.className = "fa fa-fw text-secondary cursor-pointer fa-toggle-off";
        }        
        tdEstatus.appendChild(iconEstatus);

        var td7 = document.createElement("td");

        var divButtons = document.createElement("div");
        divButtons.className = "btn-group align-top";

        var edit = document.createElement('input');
        edit.type = "button";
        edit.className = "btn btn-sm btn-outline-secondary";
        edit.setAttribute('onclick', 'getSubcategory('+ products[i].producto_id +')');
        edit.setAttribute('data-toggle', 'modal');
        edit.setAttribute('data-target', '#user-form-modal');
        edit.value = "Edit";
                                                            
        var del = document.createElement('button');
        del.className = "btn btn-sm btn-outline-secondary deactivate-product";
        del.type = "button";
        del.setAttribute('onclick', 'deleteProduct('+ products[i].producto_id +')');

        var icon = document.createElement("i");
        icon.className = "fa fa-trash";//buscar icono y agregar manual

        //add components
        del.appendChild(icon);

        divButtons.appendChild(edit);
        divButtons.appendChild(del);

        divCheckBox.appendChild(checkBox);
        //divCheckBox.appendChild(label);

        tdCheckBox.appendChild(divCheckBox);

        td7.appendChild(divButtons);

        tr.appendChild(tdCheckBox);
        tr.appendChild(tdID);
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tr.appendChild(td4);
        tr.appendChild(td5);
        tr.appendChild(tdEstatus);
        tr.appendChild(td7);

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
    var selectCategorias = document.getElementById('categoria');
    var select = document.getElementById('slcCategoria');
    //parse to JSON
    var JSONData = JSON.parse(data);
    //get brands array
    var categorias = JSONData.categoria;

    var optionDefault = document.createElement('option');
    optionDefault.value = 0;
    optionDefault.innerHTML= "Todos";

    select.appendChild(optionDefault);

    //read data
    for(var i = 0;i<categorias.length;i++){
        //option
            var option = document.createElement('option');
            option.value = categorias[i].categoria_id;
            option.innerHTML=categorias[i].nombre;

            select.appendChild(option);
        
    }
    
    for(var i = 0;i<categorias.length;i++){
        if(categorias[i].estatus){
        //console.log(categorias[i].categoria_id);
        //option
        var option = document.createElement('option');
        option.value = categorias[i].categoria_id;
        option.innerHTML=categorias[i].nombre;

        selectCategorias.appendChild(option);
        }
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
    var selectSubcategorias = document.getElementById('subcategoria');
    var select = document.getElementById('slcSubcategoria');
    //parse to JSON
    var JSONData = JSON.parse(data);
    //get brands array
    var subcategorias = JSONData.subcategoria;

    var optionDefault = document.createElement('option');
    optionDefault.value = 0;
    optionDefault.innerHTML= "Todos";

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

    //read data
    for(var i = 0;i<subcategorias.length;i++){
        if (subcategorias[i].estatus){
        //console.log(categorias[i].categoria_id);
        //option
        var option = document.createElement('option');
        option.value = subcategorias[i].subcategoria_id;
        option.innerHTML=subcategorias[i].nombre;

        selectSubcategorias.appendChild(option);
        }
    }
}

function deleteProduct(){
    let id = document.getElementById("delProduct").value;
    if (id <= 0){
        return;
    }
    //console request
    var x = new XMLHttpRequest();
    var fd = new FormData();
    //prepare request
    x.open('POST', '/AbarrotesStep/controllers/productoController.php', true);
    fd.append('idDelete', id)
    //send request
    x.send(fd);
    //handle ready state change event
    x.onreadystatechange = function () {
        if (x.status == 200 && x.readyState == 4) {
            //parse yo json
            var JSONData = JSON.parse(x.responseText);
            //check status
            if(JSONData.status == 0){
                alert(JSONData.message);
                getProductsByFilter();
            }else{
                alert(JSONData.errorMessage);
            }
        }
    }
}

function cleanForm(){
    document.getElementById("producto_id").value = "";
    document.getElementById("categoria").value = "";
    document.getElementById("subcategoria").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("descripcion").value = "";
    document.getElementById("foto").value = "";
}

function loadDelProduct(id){
    document.getElementById("delProduct").value = id;
}