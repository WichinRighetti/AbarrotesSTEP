<?php
    //ALLOW ACCESS FROM OUTSIDE THE SERVER
    header('Access-Control-Allow-Origin: *');
    //Allow methods
    header('Access-Control-Methods: GET, POST, PUT, DELETE');

    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/inventario.php');

    //get (read)
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        //parameters
        if(isset($_GET['inventario_id']) && !isset($_POST['producto_id']) && !isset($_POST['almacen_id']) && !isset($_POST['nombre_almacen']) 
        && !isset($_POST['cantidad'])){
            try{
                $e = new Inventario($_GET['inventario_id']);
                //display
                echo json_encode(array(
                    'status'=> 0,
                    'inventario' => json_decode(($e->toJson()))
                ));
            }catch(RecordNotFOundException $ex){
                echo json_encode(array(
                    'status' => 1,
                    'errorMessage' => $ex->get_message()
                ));
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
                    'inventario' => json_decode(Inventario::getAllByJsonByFilter($Filter))
                ));
            }
        }else{
            echo json_encode(array(
                'status' => 0,
                'inventario' => json_decode(inventario::getAllByJson())
            ));
        }
    }

    //POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //check parameters
        if(isset($_POST['producto_id']) && isset($_POST['almacen_id']) && isset($_POST['cantidad'])){
            //error
            $error = false;
            try{
                $p = new Producto($_POST['producto_id']);
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 2,
                    'errorMessage' => 'Producto ID no encontrado'
                ));
                $error = true;}
                try{
                    $a = new Almacen($_POST['almacen_id']);
                }catch(RecordNotFoundException $ex){
                    echo json_encode(array(
                        'status' => 2,
                        'errorMessage' => 'almacen ID no encontrado'
                    ));
                    $error = true;
                }
            if(!$error){
                //create an empty obect
                $i = new Inventario();
                //set values
                $i->setProducto($p);
                $i->setAlmacen($a);
                $i->setCantidad($_POST['cantidad']);
                //add
                echo "Resultado de add: ";
                if($i->add()){
                    echo "add";
                    echo json_encode(array(
                        'status' => 0,
                        'message' => 'Inventario agregado'
                    ));
                }else{
                    echo "No se ejecuto add()";
                }
            }
        }else{
            echo json_encode(array(
                'status' => 3,
                'errorMessage' => 'Inventario no puede ser agregado'
            ));
        }
    }
?>