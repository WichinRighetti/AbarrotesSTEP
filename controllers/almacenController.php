<?php
    //ALLOW ACCESS FROM OUTSIDE THE SERVER
    header('Access-Control-Allow-Origin: *');
    //Allow methods
    header('Access-Control-Methods: GET, POST, PUT, DELETE');

    require_once($_SERVER['DOCUMENT_ROOT'].'/abarrotesStep/models/almacen.php');

    //GET reaad
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        //Parameter
        if(isset($_GET['almacen_id'])){
            try{
                $almacen = new Almacen($_GET['almacen_id']);
                //Display
                echo json_encode(array(
                    'status' => 0,
                    'almacen' => json_decode($almacen->toJson())
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
                    'almacen' => json_decode(Almacen::getAllByJsonByFilter($Filter))
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
                'almacen' => json_decode(Almacen::getAllByJson())
            ));
        }
    }

    //POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        //check parameters
        if (isset($_POST['almacen_id']) && isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['direccion'])){
            //error
            $almacen_id = $_POST['almacen_id'];
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $direccion = $_POST['direccion'];
            if ($almacen_id >= 1){
                $almacen = new Almacen($almacen_id);
                $almacen->setNombre($nombre);
                $almacen->setDescripcion($descripcion);
                $almacen->setDireccion($direccion);
                if ($almacen->update()){
                    echo json_encode(array(
                        'status' => 0,
                        'message' => $almacen->toJson()
                    ));
                }
            } else {
                //create an empty obect
                $almacen = new Almacen();
                //set values
                $almacen->setNombre($nombre);
                $almacen->setDescripcion($descripcion);
                $almacen->setDireccion($direccion);
                //add
                if($almacen->add()){
                    echo json_encode(array(
                        'status' => 0,
                        'message' => 'almacen agregado'
                    ));
                }
            }            
        } else if(isset($_POST['idDelete'])){
            try{
                $almacen = new Almacen($_POST['idDelete']);
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 2,
                    'errorMessage' => "Almacen ID no encontrado"
                ));
                $error = true;
            }if($almacen->delete()){
                echo json_encode(array(
                    'status' => 0,
                    'message' => 'Almacen eliminado'
                ));
            }else{echo json_encode(array(
                'status' => 3,
                'errorMessage' => 'No se puedo eliminar la almacen'
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