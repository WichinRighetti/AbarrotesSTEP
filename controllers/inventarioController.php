<?php
    //ALLOW ACCESS FROM OUTSIDE THE SERVER
    header('Access-Control-Allow-Origin: *');
    //Allow methods
    header('Access-Control-Methods: GET, POST, PUT, DELETE');

    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/inventario.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/entrada.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/salida.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/exceptions/recordNotFoundException.php');

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
        }else if(isset($_GET['categoria_id']) || isset($_GET['almacen_id']) || isset($_GET['nombre'])){
            $Filter = array();
            $errorMsg = "";
            $error = false;
            if(isset($_GET['categoria_id'])){
                try{
                    $categoria = new Categoria($_GET['categoria_id']);
                }catch(RecordNotFOundException $ex){
                    $errorMsg .= "\n Categoria not found";
                    $error = true;
                }
                $Filter["c.categoria_id"] = $_GET['categoria_id'];
            }
            if(isset($_GET['almacen_id'])){
                try{
                    $almacen = new Almacen($_GET['almacen_id']);
                }catch(RecordNotFOundException $ex){
                    $errorMsg .= "\n almacen not found";
                    $error = true;
                }
                $Filter["a.almacen_id"] = $_GET['almacen_id'];
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
                $producto = new Producto($_POST['producto_id']);
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 2,
                    'errorMessage' => 'Producto ID no encontrado'
                ));
                $error = true;
            }
            try{
                $almacen = new Almacen($_POST['almacen_id']);
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 2,
                    'errorMessage' => 'almacen ID no encontrado'
                ));
                $error = true;
            }
            
            //create an empty obect
            $inventario = new Inventario();
            //set values
            $inventario->setProducto($producto);
            $inventario->setAlmacen($almacen);
            $inventario->setCantidad($_POST['cantidad']);
            $inventario->getIdInventario();
            if(!$error){
                //add
                if ($inventario->getInventario_id() == 0){
                    if($inventario->add()){
                    }else{
                        echo "No se ejecuto add()";
                    }
                }
                //agregar entrada || agregar salida
                $inventario = new Inventario($inventario->getInventario_id());
                $tipo = $_POST['tipo'];
                if ($tipo == 'E'){
                    //AGREGAR ENTRADA
                    $entrada = new Entrada();
                    //set values
                    $entrada->setInventario($inventario);
                    $entrada->setCantidad($_POST['cantidad']);
                    $entrada->setFecha($_POST['fecha']);
                    if($entrada->add()){
                        $inventario->setCantidad($inventario->getCantidad() + $entrada->getCantidad());
                        if($inventario->update()){
                            echo json_encode(array(
                                'status' => 0,
                                'message'=>'Update stock'
                            ));
                        } else {
                            echo json_encode(array(
                                'status' => 5,
                                'errorMessage'=>'Could not update the entry'
                            ));
                        }
                    }else{
                        echo json_encode(array(
                            'status' => 3,
                            'errorMessage'=>'Could not add the entry'
                        ));
                    }
                } else {
                    //AGREGAR SALIDA
                    //create an empty object
                    $salida = new Salida();
                    //set values
                    $salida->setInventario($inventario);
                    $salida->setCantidad($_POST['cantidad']);
                    $salida->setFecha($_POST['fecha']);
                    if($salida->add()){
                        $inventario->setCantidad($inventario->getCantidad() - $salida->getCantidad());
                        if($inventario->update()){
                            echo json_encode(array(
                                'status' => 0,
                                'message'=>'Update stock'
                            ));
                        } else {
                            echo json_encode(array(
                                'status' => 5,
                                'errorMessage'=>'Could not update the entry'
                            ));
                        }
                    }else{
                        echo json_encode(array(
                            'status' => 3,
                            'errorMessage'=>'Could not add the entry'
                        ));
                    }
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