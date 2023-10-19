<?php
    //Alow acces from outside the server
    header('Access-Control-Allow-origin: *');
    //Allow methods
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); //Read, inster, update, delete


    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/subcategoria.php');    
    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/exceptions/recordNotFoundException.php');


    //GET reaad
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        //Parameter
        if(isset($_GET['subcategoria_id'])){
            try{
                $s = new Subcategoria($_GET['subcategoria_id']);
                //Display
                echo json_encode(array(
                    'status' => 0,
                    'subcategoria' => json_decode($s->toJson())
                ));
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 1,
                    'errorMessage' => $ex->get_message()
                ));
            }
        } else if(isset($_GET['nombre'])){
            $Filter = array();
            $errorMsg = "";
            $error = false;
            if(isset($_GET['nombre'])){
                $Filter["nombre"] = $_GET['nombre'];
            }
            if(!$error){
                //Display
                echo json_encode(array(
                    'status' => 0,
                    'subcategoria' => json_decode(Subcategoria::getAllByJsonByFilter($Filter))
                ));
            }else{
                echo json_encode(array(
                    'status'=>2,
                    'errorMessage'=> $errorMsg
                ));
            }            
        } else{
            //Display
            echo json_encode(array(
                'status' => 0,
                'subcategoria' => json_decode(Subcategoria::getAllByJson())
            ));
        }
    }

    //POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        //check parameters
        if (isset($_POST['subcategoria_id']) && isset($_POST['nombre'])){
            //error
            $subcategoria_id = $_POST['subcategoria_id'];
            $nombre = $_POST['nombre'];
            if ($subcategoria_id >= 1){
                $subcategoria = new Subcategoria($subcategoria_id);
                $subcategoria->setNombre($nombre);
                if ($subcategoria->update()){
                    echo json_encode(array(
                        'status' => 0,
                        'message' => 'subcategoria editada'
                    ));
                }
            } else {
                //create an empty obect
                $subcategoria = new Subcategoria();
                //set values
                $subcategoria->setNombre($_POST['nombre']);
                $subcategoria->setEstatus(1);
                //add
                if($subcategoria->add()){
                    echo json_encode(array(
                        'status' => 0,
                        'message' => 'subcategoria agregada'
                    ));
                }
            }            
        } else if(isset($_POST['idDelete'])){
            try{
                $subcategoria = new Subcategoria($_POST['idDelete']);
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 2,
                    'errorMessage' => "Subategoria ID no encontrado"
                ));
                $error = true;
            }if($subcategoria->delete()){
                echo json_encode(array(
                    'status' => 0,
                    'message' => 'Subcategoria eliminado'
                ));
            }else{echo json_encode(array(
                'status' => 3,
                'errorMessage' => 'No se puedo eliminar la subcategoria'
            ));
        }
        }else{
            echo json_encode(array(
                'status' => 3,
                'errorMessage' => 'No se puede agregar las subcategoria.'
        ));
    }
} 
?>