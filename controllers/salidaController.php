<?php
    //ALLOW ACCESS FROM OUTSIDE THE SERVER
    header('Access-Control-Allow-Origin: *');
    //Allow methods
    header('Access-Control-Methods: GET, POST, PUT, DELETE');

    require_once($_SERVER['DOCUMENT_ROOT'].'/abarrotesStep/models/salida.php');

    //get (read)
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        //parameters
        if(isset($_GET['salida_id'])){
            try{
                $s = new Salida($_GET['salida_id']);
                //display
                echo json_encode(array(
                    'status'=> 0,
                    'salida' => json_decode(($s->toJson()))
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
                'salida' => json_decode(salida::getAllByJson())
            ));
        }
    }
?>