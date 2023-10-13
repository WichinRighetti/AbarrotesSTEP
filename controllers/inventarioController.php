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
        if(!$error){
            //create an empty obect
            $i = new Inventario();
            //set values
            $i->setProducto_id($_POST['producto_id']);
            $i->setAlmacen_id($_POST['almacen_id']);
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
