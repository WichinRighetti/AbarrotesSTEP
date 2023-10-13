<?php
//Alow acces from outside the server
header('Access-Control-Allow-origin: *');
//Allow methods
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); //Read, inster, update, delete

    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/producto.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/categoria.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/subcategoria.php');


//GET read
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    //Parameter
    if (isset($_GET['producto_id'])) {
        try {
            $p = new Producto($_GET['producto_id']);
            //Display
            echo json_encode(array(
                'status' => 0,
                'state' => json_decode($p->toJson())
            ));
        } catch (RecordNotFoundException $ex) {
            echo json_encode(array(
                'status' => 1,
                'errorMessage' => $ex->get_message()
            ));
        }
    } else if (isset($_GET['categoria_id']) || isset($_GET['subcategoria_id']) || isset($_GET['nombre'])) {
        $Filter = array();
        $errorMsg = "";
        $error = false;
        if (isset($_GET['categoria_id'])) {
            try {
                $c = new Categoria($_GET['categoria_id']);
            } catch (RecordNotFOundException $ex) {
                $errorMsg .= "\n Categoria not found";
                $error = true;
            }
            $Filter["c.categoria_id"] = $_GET['categoria_id'];
        }
        if (isset($_GET['subcategoria_id'])) {
            try {
                $s = new Subcategoria($_GET['subcategoria_id']);
            } catch (RecordNotFOundException $ex) {
                $errorMsg .= "\n Subcategoria not found";
                $error = true;
            }
        }else if(isset($_GET['categoria_id']) || isset($_GET['subcategoria_id']) || isset($_GET['nombre'])){
            $Filter = array();
            $errorMsg = "";
            $error = false;
            if(isset($_GET['categoria_id'])){
                try{
                    $c = new Categoria($_GET['categoria_id']);
                }catch(RecordNotFOundException $ex){
                    $errorMsg .= "\n Categoria not found";
                    $error = true;
                }
                $Filter["c.categoria_id"] = $_GET['categoria_id'];
            }
            if(isset($_GET['subcategoria_id'])){
                try{
                    $s = new Subcategoria($_GET['subcategoria_id']);
                }catch(RecordNotFOundException $ex){
                    $errorMsg .= "\n Subcategoria not found";
                    $error = true;
                }
                $Filter["s.subcategoria_id"] = $_GET['subcategoria_id'];
            }
            if(isset($_GET['nombre'])){
                $Filter["p.nombre"] = $_GET['nombre'];
            }
            if(!$error){
                //Display
                echo json_encode(array(
                    'status' => 0,
                    'state' => json_decode(Producto::getAllByJsonByFilter($Filter))
                ));
            }else{
                echo json_encode(array(
                    'status'=>2,
                    'errorMessage'=> $errorMsg
                ));
            }
            
        }else{
            echo json_encode(array(
                'status' => 0,
                'state' => json_decode(Producto::getAllByJsonByFilter($Filter))
            ));
        } else {
            echo json_encode(array(
                'status' => 2,
                'errorMessage' => $errorMsg
            ));
        }
    } else {
        echo json_encode(array(
            'status' => 0,
            'state' => json_decode(Producto::getAllByJson())
        ));
    }
}

//POST

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //check parameters
    if(!isset($_POST['idDelete']) && isset($_POST['categoria_id']) && isset($_POST['subcategoria_id']) && isset($_POST['nombre']) && isset($_POST['descripcion'])
    && isset($_POST['foto'])){
        //error
        $error = false;
        if(!$error){
            //create an empty obect
            $p = new Producto();
            //set values
            $p->setCategoriaId($_POST['categoria_id']);
            $p->setSubcategoriaId($_POST['subcategoria_id']);
            $p->setNombre($_POST['nombre']);
            $p->setDescripcion($_POST['descripcion']);
            $p->foto($_POST['foto']);
            //add
            if($p->add()){
                echo json_encode(array(
                    'status' => 0,
                    'message' => 'producto agregado'
                ));
            }
        }
    }else if(isset($_POST['idDelete'])){

            try{
                $p = new Producto($_POST['idDelete']);
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 2,
                    'errorMessage' => 'Producto ID  no encontrado'
                ));
                $error = true;
            }if($p->delete()){
                    echo json_encode(array(
                       'status' => 0,
                       'message' => 'Producto eliminado'
                    ));
                }else{echo json_encode(array(
                    'status' => 3,
                    'errorMessage' => 'No se puedo eliminar el producto'
                ));
            }
        }else{
        echo json_encode(array(
            'status' => 3,
            'errorMessage' => 'No se puede agregar el producto.'
        ));
    }
}


