<?php
//use files
require_once('mysqlConnection.php');
require_once('exceptions/recordNotFoundException.php');

//classs name
class Producto
{
    //Atributes
    private $producto_id;
    private $categoria_id;
    private $subcategoria_id;
    private $nombre;
    private $descripcion;
    private $foto;
    private $estatus;

    //getters and setters
    public function getProductoId()
    {
        return $this->producto_id;
    }
    public function setProductoId($value)
    {
        $this->producto_id = $value;
    }
    
    public function getCategoriaId()
    {
        return $this->categoria_id;
    }
    public function setCategoriaId($value)
    {
        $this->categoria_id = $value;
    }

    public function getSubcategoriaId()
    {
        return $this->subcategoria_id;
    }
    public function setSubcategoriaId($value)
    {
        $this->subcategoria_id = $value;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($value)
    {
        $this->nombre = $value;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function setDescripcion($value)
    {
        $this->descripcion = $value;
    }

    public function getFoto()
    {
        return $this->foto;
    }
    public function foto($value)
    {
        $this->foto = $value;
    }

    public function getEstatus()
    {
        return $this->estatus;
    }
    public function setEstatus($value)
    {
        $this->estatus = $value;
    }

    //constructors
    public function __construct()
    {
        //empty constructors
        if (func_num_args()==0){
            $this-> producto_id = 0;
            $this-> categoria_id = 0;
            $this-> subcategoria_id = 0;
            $this-> nombre = "";
            $this-> descripcion = "";
            $this-> foto = "";
            $this-> estatus = 1;
        }
        //constructor with data from database
        if(func_num_args()==1){
            //get id
            $producto_id= func_get_arg(0);
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = "Select producto_id, categoria_id, subcategoria_id, nombre, descripcion, foto, estatus From producto
            Where producto_id = ?";
            //command
            $command = $connection->prepare($query);
            //bind parameter
            $command->bind_param('i', $producto_id);
            //execute
            $command->execute();
            //bind result
            $command->bind_result($producto_id, $categoria_id, $subcategoria_id, $nombre, $descripcion, 
            $foto, $estatus);
            //record was found
            if($command->fetch()){
                $this->producto_id = $producto_id;
                $this->categoria_id = $categoria_id;
                $this->subcategoria_id = $subcategoria_id;
                $this->nombre = $nombre;
                $this->descripcion = $descripcion;
                $this->foto = $foto;
                $this->estatus = $estatus;
            }else{
                //throw exception if record not found
                throw new RecordNotFoundException($producto_id);
            }
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
        }
        //constructor with data from arguments
        if(func_num_args() == 7){
            //get arguments
            $arguments = func_get_args();
            //pass arguments to attributes
            $this->producto_id = $arguments[0];
            $this->categoria_id = $arguments[1];
            $this->subcategoria_id = $arguments[2];
            $this->nombre = $arguments[3];
            $this->descripcion = $arguments[4];
            $this->foto = $arguments[5];
            $this->estatus = $arguments[6];
        }
    }

    //represent object in JSON format
    public function toJson()
    {
        return json_encode(
            array(
                'producto_id' => $this->producto_id,
                'categoria_id' => $this->categoria_id,
                'subcategoria_id' => $this->subcategoria_id,
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'foto' => $this->foto,
                'estatus' => $this->estatus)
        );
    }


    public static function getAll()
    {
        //list of records
        $records = array();
        //get connection
        $connection = MysqlConnection::getConnection();
        //query
        $query = "Select producto_id, categoria_id, subcategoria_id, nombre, descripcion, foto, estatus From producto";
        //command
        $command = $connection->prepare($query);
        //execute
        $command->execute();
        //bind results
        $command->bind_result($producto_id, $categoria_id, $subcategoria_id, $nombre, $descripcion, 
        $foto, $estatus);
        //fetch data
        while($command->fetch()){
            array_push($records, new Producto($producto_id, $categoria_id, $subcategoria_id, $nombre, $descripcion, 
            $foto, $estatus));
        }
        //close command
        mysqli_stmt_close($command);
        //close connection
        $connection->close();
        //Return records
        return $records;
    }

     //represent object in JSON format
     public static function getAllByJson()
     {
        //list
        $list = array();
        //get all
        foreach(self::getAll() as $record){
            array_push($list, json_decode($record->toJson()));
        }
        //return list
        return json_encode($list);
     }

    //add
    public function add()
    {
        //get connection
        $connection = MysqlConnection::getConnection();
        //query
        $query = "Insert Into producto (categoria_id, subcategoria_id, nombre, descripcion, foto, estatus) Values(?, ?, ?, ?, ?)";
        //command
        $command = $connection->prepare($query);
        //bin parameter
        $command->bind_param('iisssi', $this->categoria_id, $this->subcategoria_id, $this->nombre, 
        $this->descripcion, $this->foto, $this->estatus);
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