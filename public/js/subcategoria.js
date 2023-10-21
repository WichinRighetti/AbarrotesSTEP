//get Categories
function getSubcategories() {
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
            showCategories(x.responseText);
        }
    }
}

function getSubcategory(id){
    //console request
    var x = new XMLHttpRequest();
    var fd = new FormData();
    //prepare request
    x.open('GET', '/AbarrotesStep/controllers/subcategoriaController.php?subcategoria_id='+id+'', true);
    //send request
    x.send(fd);
    //handle ready state change event
    x.onreadystatechange = function () {
        if (x.status == 200 && x.readyState == 4) {
            //parse yo json
            var JSONData = JSON.parse(x.responseText);
            //check status
            if(JSONData.status == 0){
                showSubcategory(JSONData.subcategoria);
            }else{
                alert(JSONData.errorMessage);
            }
        }
    }
}

function showSubcategory(subcategoria){
    document.getElementById("nombre").value = subcategoria.nombre;
    document.getElementById("subcategoria_id").value = subcategoria.subcategoria_id;
}

//show Categories
function showSubcategories(data) {
    //get main div
    var mainTable = document.getElementById('subcategorias-table-body');
    mainTable.innerHTML='';
    //parse to JSON
    var JSONdata = JSON.parse(data);
    //get cars array
    var subcategorias = JSONdata.subcategoria;
    //read data
    for (var i = 0; i < subcategorias.length; i++) {
        //create components
        var tr = document.createElement("tr");

        var tdCheckBox = document.createElement("td");
        tdCheckBox.className = "align-middle";

        var divCheckBox = document.createElement('div');
        divCheckBox.className = "custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top";
        
        var checkBox = document.createElement('input');
        checkBox.type = "checkbox";
        //checkBox.className = "custom-control-input";
        checkBox.id = "item_"+subcategorias[i]["subcategoria_id"];

        //var label = document.createElement('label');
        //label.className = "custom-control-label";
        
        var tdID = document.createElement("td");
        tdID.innerHTML = subcategorias[i]['subcategoria_id'];

        var tdNombre = document.createElement("td");
        tdNombre.innerHTML = subcategorias[i]['nombre'];

        var tdEstatus = document.createElement("td");
        var iconEstatus = document.createElement("i");
        if (subcategorias[i]['estatus']){
            iconEstatus.className = "fa fa-fw text-secondary cursor-pointer fa-toggle-on";
        } else {
            iconEstatus.className = "fa fa-fw text-secondary cursor-pointer fa-toggle-off";
        }        
        tdEstatus.appendChild(iconEstatus);

        var tdButtons = document.createElement("td");

        var divButtons = document.createElement("div");
        divButtons.className = "btn-group align-top";

        var edit = document.createElement('input');
        edit.type = "button";
        edit.className = "btn btn-sm btn-outline-secondary";
        edit.setAttribute('onclick', 'getSubcategory('+ subcategorias[i].subcategoria_id +')');
        edit.setAttribute('data-toggle', 'modal');
        edit.setAttribute('data-target', '#user-form-modal');
        edit.value = "Edit";

        var del = document.createElement('input');
        del.className = "btn btn-sm btn-outline-secondary deactivate-product";
        del.type = "button";
        del.setAttribute('onclick', 'deleteSubcategory('+ subcategorias[i].subcategoria_id +')');
        
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
    document.getElementById("subcategoria_id").value = "";
}

function deleteSubcategory(id){
    //console request
    var x = new XMLHttpRequest();
    var fd = new FormData();
    //prepare request
    x.open('POST', '/AbarrotesStep/controllers/subcategoriaController.php', true);
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
                getSubcategories();
            }else{
                alert(JSONData.errorMessage);
            }
        }
    }
}

//get categories by filter
function getSubcategoriesByFilter() {
    var first = true;
    var url = "/AbarrotesStep/controllers/subcategoriaController.php"

    //selects
    var txtNombre = document.getElementById("txtNombre");
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
            showSubcategories(x.responseText);
        }
    }
}