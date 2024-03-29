<?php
    //use files
    require_once('mysqlConnection.php');

    class Almacen{
        //attributes
        private $almacen_id;
        private $nombre;
        private $descripcion;
        private $direccion;

        //setters & getters
        public function setAlmacen_id($value){$this->almacen_id = $value;}
        public function getAlmacen_id(){return $this->almacen_id;}
        public function setNombre($value){$this->nombre = $value;}
        public function getNombre(){return $this->nombre;}
        public function setDescripcion($value){$this->descripcion = $value;}
        public function getDescripcion(){return $this->descripcion;}
        public function setDireccion($value){$this->direccion = $value;}
        public function getDireccion(){return $this->direccion;}

        //constructor
        public function __construct()
        {
            //empty constructor
            if(func_num_args() == 0){
                $this->almacen_id = 0;
                $this->nombre = '';
                $this->descripcion = '';
                $this->direccion = '';
            }
            //constructor with data from database
            if(func_num_args() == 1){
                // get id
                $id = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection();
                //query
                $query = "select almacen_id, nombre, descripcion, direccion from almacen where almacen_id = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id, $nombre, $descripcion, $direccion);
                //reconrd was found
                if($command->fetch()){
                    //pass values to the attributes
                    $this->almacen_id = $id;
                    $this->nombre = $nombre;
                    $this->descripcion = $descripcion;
                    $this->direccion = $direccion;
                }else{
                    // throw exception if record not found
                    throw new RecordNotFoundException($id);
                }
                //close command
                mysqli_stmt_close($command);
                //close connection
                $connection->close();
            }
            //constructor with data from database
            if(func_num_args()==4){
                //get arguments
                $arguments = func_get_args();
                //pass arguments to attibutes
                $this->almacen_id = $arguments[0];
                $this->nombre = $arguments[1];
                $this->descripcion = $arguments[2];
                $this->direccion = $arguments[3];
            }
        }

        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'amacen_id'=>$this->almacen_id,
                'nombre'=>$this->nombre,
                'descripcion'=>$this->descripcion,
                'direccion'=>$this->direccion,
            ));
        }

        //get all
        public static function getAll(){
            //list
            $list = array();
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = "select almacen_id, nombre, descripcion, direccion from almacen";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id, $nombre, $descripcion, $direccion);
            //fetch data
            while($command->fetch()){
                array_push($list, new Almacen($id, $nombre, $descripcion, $direccion));
            }
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            //return $list
            return $list;
        }

        public static function getAllByJson(){
            //list
            $list = array();
            //get all
            foreach(self::getAll() as $item){
                array_push($list, json_decode($item->toJson()));
            }
            //return list
            return json_encode($list);
        }
    }
?>