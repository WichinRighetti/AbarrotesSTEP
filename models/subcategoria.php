<?php
    //user files
    require_once('mysqlConnection.php');
    require_once('exceptions/recordNotFoundException.php');

    //Calse name
    Class Subcategoria{
        //attributes
        private $subcategoria_id;
        private $nombre;
        private $estatus;


        //Setters and getters
        public function setSubcategoria_id($value){$this->subcategoria_id = $value; }
        public function getSubcategoria_id(){ return $this->subcategoria_id; }
        public function setNombre($value){$this->nombre = $value; }
        public function getNombre(){ return $this->nombre; }
        public function setEstatus($value){$this->estatus = $value; }
        public function getEstatus(){ return $this->estatus; }

        
        //Constructors
        public function __construct(){
            //Empty construtor
            if(func_num_args() == 0){
                $this->subcategoria_id = 0;
                $this->nombre = '';
                $this->estatus = 0;
            }
            //Constructor with data from database
            if(func_num_args() == 1){
                //get id
                $id = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection(); // "::" Para llamar la funcion estatica
                //query
                $query = "Select subcategoria_id, nombre, estatus From subcategoria Where subcategoria_id = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($subcategoria_id, $nombre, $estatus);
                //Record was found
                if($command->fetch()){
                    //pass values to the attributes
                    $this->subcategoria_id = $subcategoria_id;
                    $this->nombre = $nombre;
                    $this->estatus = $estatus;
                }else{
                    //throw exception if record not found
                    throw new RecordNotFoundException($id);
                }
                //close command
                mysqli_stmt_close($command);
                //close connection
                $connection->close();
            }
            //Constructor with data from arguments
            if(func_num_args() == 3){
                //get arguments
                $arguments = func_get_args();
                //pass arguments to attributes
                $this->subcategoria_id = $arguments[0];
                $this->nombre = $arguments[1];
                $this->estatus = $arguments[2];
            }
        }
        
        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'subcategoria_id' => $this->subcategoria_id,
                'nombre' => $this->nombre,
                'estatus' => $this->estatus
            ));
        }

        //get all
        public static function getAll(){
            //list
            $list = array();
            //get connection
            $connection = MysqlConnection::getConnection(); // "::" Para llamar la funcion estatica
            //query
            $query = "Select subcategoria_id, nombre, estatus From subcategoria";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($subcategoria_id, $nombre, $estatus);
            //fetch data
            while($command->fetch()){
                array_push($list, new Subcategoria($subcategoria_id, $nombre, $estatus));
            }
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            //return list
            return $list;
        }

        //get all in JSON format
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

        //get all by categoria
        public static function getAllByFilter($Filter){
            //list
            $list = array();
            $filterValue = array();
            $types = '';
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = 'Select subcategoria_id, nombre, estatus From subcategoria where ';

            foreach($Filter as $subcategory => $element){
                $query .= "$subcategory ";
                if( $subcategory == "nombre"){
                    $query .= "like ? and ";
                    $types .= 's';
                    $element.="%";
                } else{
                    $query .= "= ? and ";
                    $types .= 'i';
                }
                array_push($filterValue, $element);
            }
            $query .= "estatus = 1; ";
            //command
            $command = $connection->prepare($query);
        
            //bind param
            if(count($Filter) == 1){
                $command->bind_param($types, $filterValue[0]);
            }

            //execute 
            $command->execute();
            //bind results
            $command->bind_result($subcategoria_id, $nombre, $estatus);
            //record was found
            //fetch data
            while($command->fetch()){
                array_push($list, new Subcategoria($subcategoria_id, $nombre ,$estatus));
            }
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            //Return records
            return $list;
        }

        //get all json by Categoria
        public static function getAllByJsonByFilter($Filter){
            //list
            $list = array();
            //get all
            foreach(self::getAllByFilter($Filter) as $item){
                array_push($list, json_decode($item->toJson()));
            }

            return json_encode($list);
        }

        public function add(){
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = "Insert Into subcategoria (nombre, estatus) Values(?, ?)";
            //command
            $command = $connection->prepare($query);
            //bin parameter
            $command->bind_param('si', $this->nombre, $this->estatus);
            //execute
            $result = $command->execute();
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            //retun result
            return $result;
        }

        public function update(){
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = "Update subcategoria set nombre = ? where subcategoria_id = ?";
            //command
            $command = $connection->prepare($query);
            //bin parameter
            $command->bind_param('si', $this->nombre, $this->subcategoria_id);
            //execute
            $result = $command->execute();
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            //retun result
            return $result;
        }

        public function delete(){
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = "Update subcategoria set estatus = 0 where subcategoria_id = ?";
            //command
            $command = $connection->prepare($query);
            //bin parameter
            $command->bind_param('s', $this->subcategoria_id);
            //execute
            $result = $command->execute();
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            //retun result
            return $result;
        }
    }
?>