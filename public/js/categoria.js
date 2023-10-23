//get Categories
function getCategories() {
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

function getCategory(id){
    //console request
    var x = new XMLHttpRequest();
    var fd = new FormData();
    //prepare request
    x.open('GET', '/AbarrotesStep/controllers/categoriaController.php?categoria_id='+id+'', true);
    //send request
    x.send(fd);
    //handle ready state change event
    x.onreadystatechange = function () {
        if (x.status == 200 && x.readyState == 4) {
            //parse yo json
            var JSONData = JSON.parse(x.responseText);
            //check status
            if(JSONData.status == 0){
                showCategory(JSONData.categoria);
            }else{
                alert(JSONData.errorMessage);
            }
        }
    }
}

function showCategory(categoria){
    document.getElementById("nombre").value = categoria.nombre;
    document.getElementById("categoria_id").value = categoria.categoria_id;
}

//show Categories
function showCategories(data) {
    //get main div
    var mainTable = document.getElementById('categorias-table-body');
    mainTable.innerHTML='';
    //parse to JSON
    var JSONdata = JSON.parse(data);
    //get cars array
    var categorias = JSONdata.categoria;
    //read data
    for (var i = 0; i < categorias.length; i++) {
        //create components
        var tr = document.createElement("tr");

        var tdCheckBox = document.createElement("td");
        tdCheckBox.className = "align-middle";

        var divCheckBox = document.createElement('div');
        divCheckBox.className = "custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top";
        
        var checkBox = document.createElement('input');
        checkBox.type = "checkbox";
        //checkBox.className = "custom-control-input";
        checkBox.id = "item_"+categorias[i]["categoria_id"];

        //var label = document.createElement('label');
        //label.className = "custom-control-label";
        
        var tdID = document.createElement("td");
        tdID.innerHTML = categorias[i]['categoria_id'];

        var tdNombre = document.createElement("td");
        tdNombre.innerHTML = categorias[i]['nombre'];

        var tdEstatus = document.createElement("td");
        tdEstatus.innerHTML = categorias[i]['estatus'];

        var tdButtons = document.createElement("td");

        var divButtons = document.createElement("div");
        divButtons.className = "btn-group align-top";

        var edit = document.createElement('input');
        edit.type = "button";
        edit.className = "btn btn-sm btn-outline-secondary";
        edit.setAttribute('onclick', 'getCategory('+ categorias[i].categoria_id +')');
        edit.setAttribute('data-toggle', 'modal');
        edit.setAttribute('data-target', '#user-form-modal');
        edit.value = "Edit";

        var del = document.createElement('input');
        del.className = "btn btn-sm btn-outline-secondary deactivate-product";
        del.type = "button";
        del.setAttribute('onclick', 'deleteCategory('+ categorias[i].categoria_id +')');
        
        var icon = document.createElement("span");
        icon.className = "fa fa-trash";//buscar icono y agregar manual

        //add components
        del.appendChild(icon);

        divButtons.appendChild(edit);
        divButtons.appendChild(del);

        divCheckBox.appendChild(checkBox);
        //divCheckBox.appendChild(label);

        tdCheckBox.appendChild(divCheckBox);

        tdButtons.appendChild(divButtons);

        tr.appendChild(tdCheckBox);
        tr.appendChild(tdID);
        tr.appendChild(tdNombre);
        tr.appendChild(tdEstatus);
        tr.appendChild(tdButtons);

        mainTable.appendChild(tr);
    }
}

function cleanForm(){
    document.getElementById("nombre").value = "";
    document.getElementById("categoria_id").value = "";
}

function deleteCategory(id){
    alert(id);
    //console request
    var x = new XMLHttpRequest();
    var fd = new FormData();
    //prepare request
    x.open('POST', '/AbarrotesStep/controllers/categoriaController.php', true);
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
                getCategories();
            }else{
                alert(JSONData.errorMessage);
            }
        }
    }
}

//get categories by filter
function getCategoriesByFilter() {
    var first = true;
    var url = "/AbarrotesStep/controllers/categoriaController.php"

    //selects
    var txtNombre =document.getElementById("txtNombre");
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
            showCategories(x.responseText);
        }
    }
}