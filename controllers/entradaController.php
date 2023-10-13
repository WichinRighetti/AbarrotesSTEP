<?php
    //ALLOW ACCESS FROM OUTSIDE THE SERVER
    header('Access-Control-Allow-Origin: *');
    //Allow methods
    header('Access-Control-Methods: GET, POST, PUT, DELETE');

    require_once($_SERVER['DOCUMENT_ROOT'].'/abarrotesStep/models/entrada.php');

    //get (read)
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        //parameters
        if(isset($_GET['entrada_id'])){
            try{
                $e = new Entrada($_GET['entrada_id']);
                //display
                echo json_encode(array(
                    'status'=> 0,
                    'entrada' => json_decode(($e->toJson()))
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
                'entrada' => json_decode(entrada::getAllByJson())
            ));
        }
    }

    //POST
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['inventario_id'], $_POST['cantidad'], $_POST['fecha'])){
            //Error
            $error = false;
            //inventario_id
            try{
                $i = new Inventario($_POST['inventario_id']);
            }catch(RecordNotFOundException $ex){
                echo json_encode(array(
                    'status'=>2,
                    'errorMessage'=> 'Inventario id not found'
                ));
                $error = true;
            }
            if(!$error){
                //create an empty object
                $e = new Entrada();
                //set values
                $e->setInventario($i);
                $e->setCantidad($_POST['cantidad']);
                $e->setFecha($_POST['fecha']);
                if($c->add()){
                    echo json_encode(array(
                        'status' => 0,
                        'message'=>'Entry added successfully'
                    ));
                }else{
                    echo json_encode(array(
                        'status' => 3,
                        'errorMessage'=>'Could not add the entry'
                    ));
                }
            }
        }else{
            echo json_encode(array(
                'status' => 4,
                'errorMessage' => 'Missing Parameter'
            ));
        }
    }
