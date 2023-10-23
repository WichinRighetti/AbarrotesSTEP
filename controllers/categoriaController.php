<?php
    //Alow acces from outside the server
    header('Access-Control-Allow-origin: *');
    //Allow methods
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); //Read, inster, update, delete


    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/categoria.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/models/exceptions/recordNotFoundException.php');

    //GET reaad
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        //Parameter
        if(isset($_GET['categoria_id'])){
            try{
                $categoria = new Categoria($_GET['categoria_id']);
                //Display
                echo json_encode(array(
                    'status' => 0,
                    'categoria' => json_decode($categoria->toJson())
                ));
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 1,
                    'errorMessage' => $ex->get_message()
                ));
            }
        }else if(isset($_GET['nombre'])){
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
                    'categoria' => json_decode(Categoria::getAllByJsonByFilter($Filter))
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
                'categoria' => json_decode(Categoria::getAllByJson())
            ));
        }
    }

    //POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        //check parameters
        if (isset($_POST['categoria_id']) && isset($_POST['nombre'])){
            //error
            $categoria_id = $_POST['categoria_id'];
            $nombre = $_POST['nombre'];
            if ($categoria_id >= 1){
                $categoria = new Categoria($categoria_id);
                $categoria->setNombre($nombre);
                if ($categoria->update()){
                    echo json_encode(array(
                        'status' => 0,
                        'message' => 'categoria editada'
                    ));
                }
            } else {
                //create an empty obect
                $categoria = new Categoria();
                //set values
                $categoria->setNombre($_POST['nombre']);
                $categoria->setEstatus(1);
                //add
                if($categoria->add()){
                    echo json_encode(array(
                        'status' => 0,
                        'message' => 'producto agregado'
                    ));
                }
            }            
        } else if(isset($_POST['idDelete'])){
            try{
                $categoria = new Categoria($_POST['idDelete']);
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 2,
                    'errorMessage' => "Categoria ID no encontrado"
                ));
                $error = true;
            }if($categoria->delete()){
                echo json_encode(array(
                    'status' => 0,
                    'message' => 'Categoria eliminado'
                ));
            }else{echo json_encode(array(
                'status' => 3,
                'errorMessage' => 'No se puedo eliminar la categoria'
            ));
        }
        }else{
            echo json_encode(array(
                'status' => 3,
                'errorMessage' => 'No se puede agregar el producto.'
        ));
    }
}
?>