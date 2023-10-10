<?php
    //Alow acces from outside the server
    header('Access-Control-Allow-origin: *');
    //Allow methods
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); //Read, inster, update, delete

    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/producto.php');

    //GET read
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        //Parameter
        if(isset($_GET['producto_id'])){
            try{
                $p = new Producto($_GET['producto_id']);
                //Display
                echo json_encode(array(
                    'status' => 0,
                    'state' => json_decode($p->toJson())
                ));
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 1,
                    'errorMessage' => $ex->get_message()
                ));
            }
        }else{
            echo json_encode(array(
                'status' => 0,
                'state' => json_decode(Producto::getAllByJson())
            ));
        }
    }

//POST
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(
        isset($_POST['producto_id'])&&
        isset($_POST['categoria'])&&
        isset($_POST['subcategoria'])&&
        isset($_POST['nombre'])&&
        isset($_POST['descripcion'])&&
        isset($_POST['foto'])&&
        isset($_POST['estatus'])
    ){
        try{
            $record = new Producto(
                $POST['producto_id'],
                $POST['categoria'],
                $POST['nombre'],
                $POST['descripcion'],
                $POST['foto'],
                $POST['estatus']
            );

            if($record->add()){
                echo json_encode(array('message' => 'Registro agregado'));
            }
            else{
                echo json_encode(array('message' => 'Registro no agregado'));
            }
        } catch(Exception $ex){
            echo json_encode(array('message' => $ex->getMessage()));
        }
    }else{
        echo json_encode(array('message' => 'Registro no agregado'));
    }
}
