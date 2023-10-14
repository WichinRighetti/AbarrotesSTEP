<?php
    //Alow acces from outside the server
    header('Access-Control-Allow-origin: *');
    //Allow methods
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); //Read, inster, update, delete


    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/categoria.php');


    //GET reaad
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        //Parameter
        if(isset($_GET['categoria_id'])){
            try{
                $c = new Categoria($_GET['categoria_id']);
                //Display
                echo json_encode(array(
                    'status' => 0,
                    'categoria' => json_decode($c->toJson())
                ));
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 1,
                    'errorMessage' => $ex->get_message()
                ));
            }
        }else{
            //Display
            echo json_encode(array(
                'status' => 0,
                'categoria' => json_decode(Categoria::getAllByJson())
            ));
        }
    }
?>