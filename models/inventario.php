<?php
    //use files
    require_once('mysqlConnection.php');
    require_once('producto.php');
    require_once('almacen.php');

    class Inventario{
        //attributes
        private $inventario_id;
        private $producto;
        private $almacen;
        private $cantidad;

        //setters & getters
        public function setInventario_id($value){$this->inventario_id = $value;}
        public function getInventario_id(){return $this->inventario_id;}
        public function setProducto($value){$this->producto = $value;}
        public function getProducto(){return $this->producto;}
        public function setAlmacen($value){$this->almacen = $value;}
        public function getAlmacen(){return $this->almacen;}
        public function setCantidad($value){$this->cantidad = $value;}
        public function getCantidad(){return $this->cantidad;}

        //constructor
        public function __construct()
        {
            //empty constructor
            if(func_num_args() == 0){
                $this->inventario_id = 0;
                $this->producto = new Producto(); // new Producto();
                $this->almacen = new Almacen();
                $this->cantidad = 0;
            }
            //constructor with data from database
            if(func_num_args() == 1){
                // get id
                $id = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection();
                //query
                $query = "Select i.inventario_id, p.producto_id, c.categoria_id, c.nombre, c.estatus, s.subcategoria_id,s.nombre , s.estatus, 
                p.nombre, p.descripcion, p.foto, p.estatus, a.almacen_id, a.descripcion, a.direccion, a.nombre, i.cantidad
                From inventario i
                LEFT JOIN producto p ON i.producto_id = p.producto_id
                LEFT JOIN almacen a ON i.almacen_id = a.almacen_id 
                LEFT JOIN categoria c ON p.categoria_id = c.categoria_id 
                LEFT JOIN subcategoria s ON p.subcategoria_id = s.subcategoria_id
                Where i.inventario_id = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($inventario_id, $producto_id, $categoria_id, $c_nombre, $c_estatus, $subcategoria_id, $s_nombre, $s_estatus, 
                $p_nombre, $p_descripcion, $p_foto, $p_estatus, $almacen_id, $a_descripcion, $a_direccion, $a_nombre, $cantidad);
                //record was found
                if($command->fetch()){
                    $this->inventario_id = $inventario_id;
                    $c = new Categoria($categoria_id, $c_nombre, $c_estatus);
                    $s = new Subcategoria($subcategoria_id, $s_nombre, $s_estatus);
                    $p = new Producto($producto_id, $c, $s, $p_nombre, $p_descripcion, $p_foto, $p_estatus);
                    $a = new Almacen($almacen_id, $a_nombre, $a_direccion, $a_descripcion);
                    //pass values to the attributes
                    $this->producto = $p;
                    $this->almacen = $a;
                    $this->cantidad = $cantidad;
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
                $this->inventario_id = $arguments[0];
                $this->producto = $arguments[1];
                $this->almacen = $arguments[2];
                $this->cantidad = $arguments[3];
            }
        }

        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'inventario_id'=>$this->inventario_id,
                'producto'=>json_decode($this->producto->toJson()),
                'almacen'=>json_decode($this->almacen->toJson()),
                'cantidad'=>$this->cantidad
            ));
        }

        //get all
        public static function getAll(){
            //list
            $list = array();
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = "Select i.inventario_id, p.producto_id, c.categoria_id, c.nombre, c.estatus, s.subcategoria_id,s.nombre , s.estatus, 
            p.nombre, p.descripcion, p.foto, p.estatus, a.almacen_id, a.descripcion, a.direccion, a.nombre, i.cantidad 
            From inventario i
            LEFT JOIN producto p ON i.producto_id = p.producto_id
            LEFT JOIN almacen a ON i.almacen_id = a.almacen_id 
            LEFT JOIN categoria c ON p.categoria_id = c.categoria_id 
            LEFT JOIN subcategoria s ON p.subcategoria_id = s.subcategoria_id
            Where p.estatus=1";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($inventario_id, $producto_id, $categoria_id, $c_nombre, $c_estatus, $subcategoria_id, $s_nombre, $s_estatus, 
            $p_nombre, $p_descripcion, $p_foto, $p_estatus, $almacen_id, $a_descripcion, $a_direccion, $a_nombre, $cantidad);
            //fetch data
            while($command->fetch()){
                $c = new Categoria($categoria_id, $c_nombre, $c_estatus);
                $s = new Subcategoria($subcategoria_id, $s_nombre, $s_estatus);
                $p = new Producto($producto_id, $c, $s, $p_nombre, $p_descripcion, $p_foto, $p_estatus);
                $a = new Almacen($almacen_id, $a_nombre, $a_direccion, $a_descripcion);

                array_push($list, new Inventario($inventario_id, $p, $a, $cantidad));
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

    //get all by filter
    public static function getAllByFilter($Filter){
        //list
        $list = array();
        $filterValue = array();
        $types = '';
        //get connection
        $connection = MysqlConnection::getConnection();
        //query
        $query = 'Select i.inventario_id, p.producto_id, c.categoria_id, c.nombre, c.estatus, s.subcategoria_id,s.nombre , s.estatus, 
        p.nombre, p.descripcion, p.foto, p.estatus, a.almacen_id, a.descripcion, a.direccion, a.nombre, i.cantidad 
        From inventario i
        LEFT JOIN producto p ON i.producto_id = p.producto_id
        LEFT JOIN almacen a ON i.almacen_id = a.almacen_id 
        LEFT JOIN categoria c ON p.categoria_id = c.categoria_id 
        LEFT JOIN subcategoria s ON p.subcategoria_id = s.subcategoria_id
        Where ';

        foreach($Filter as $category => $element){
            $query .= "$category ";
            if( $category == "p.nombre"){
                $query .= "like ? and ";
                $types .= 's';
                $element.="%";
            }else{
                $query .= "= ? and ";
                $types .= 'i';
            }
            array_push($filterValue, $element);
        }
        $query .= "p.estatus = 1; ";
        //command
        $command = $connection->prepare($query);
        
        //bind param
        if(count($Filter) == 3){
            $command->bind_param($types, $filterValue[0], $filterValue[1], $filterValue[2]);
        }else if(count($Filter) == 2){
            $command->bind_param($types, $filterValue[0], $filterValue[1]);
        }else{
            $command->bind_param($types, $filterValue[0]);
        }

        //execute 
        $command->execute();
       //bind results
       $command->bind_result($inventario_id, $producto_id, $categoria_id, $c_nombre, $c_estatus, $subcategoria_id, $s_nombre, $s_estatus, 
            $p_nombre, $p_descripcion, $p_foto, $p_estatus, $almacen_id, $a_descripcion, $a_direccion, $a_nombre, $cantidad);
        //record was found
        //fetch data
        while($command->fetch()){
            $c = new Categoria($categoria_id, $c_nombre, $c_estatus);
            $s = new Subcategoria($subcategoria_id, $s_nombre, $s_estatus);
            $p = new Producto($producto_id, $c, $s, $p_nombre, $p_descripcion, $p_foto, $p_estatus);
            $a = new Almacen($almacen_id, $a_nombre, $a_direccion, $a_descripcion);

            array_push($list, new Inventario($inventario_id, $p, $a, $cantidad));
       }
       //close command
       mysqli_stmt_close($command);
       //close connection
       $connection->close();
       //Return records
       return $list;
    }

    //get all json by filter
    public static function getAllByJsonByFilter($Filter){
        //list
        $list = array();
        //get all
        foreach(self::getAllByFilter($Filter) as $item){
            array_push($list, json_decode($item->toJson()));
        }

        return json_encode($list);
    }

        //add
        public function add()
        {
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = "Insert Into inventario (producto_id, almacen_id, cantidad) Values(?, ?, ?)";
            //command
            $command = $connection->prepare($query);
            //bin parameter
            $command->bind_param('iii', $this->producto, $this->almacen, $this->cantidad);
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