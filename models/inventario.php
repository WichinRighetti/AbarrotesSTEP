<?php
    //use files
    require_once('mysqlConnection.php');
    require_once('producto.php');
    require_once('almacen.php');

    class Inventario{
        //attributes
        private $inventario_id;
        private $producto_id;
        private $almacen_id;
        private $cantidad;

        //setters & getters
        public function setInventario_id($value){$this->inventario_id = $value;}
        public function getInventario_id(){return $this->inventario_id;}
        public function setProducto_id($value){$this->producto_id = $value;}
        public function getProducto_id(){return $this->producto_id;}
        public function setAlmacen_id($value){$this->almacen_id = $value;}
        public function getAlmacen_id(){return $this->almacen_id;}
        public function setCantidad($value){$this->cantidad = $value;}
        public function getCantidad(){return $this->cantidad;}

        //constructor
        public function __construct()
        {
            //empty constructor
            if(func_num_args() == 0){
                $this->inventario_id = 0;
                $this->producto_id = 0; // new Producto();
                $this->almacen_id = 0;
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
                    $this->producto_id = $p;
                    $this->almacen_id = $a;
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
                $this->producto_id = $arguments[1];
                $this->almacen_id = $arguments[2];
                $this->cantidad = $arguments[3];
            }
        }

        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'inventario_id'=>$this->inventario_id,
                'producto_id'=>json_decode($this->producto_id->toJson()),
                'almacen_id'=>$this->almacen_id,
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
            $command->bind_param('iii', $this->producto_id, $this->almacen_id, $this->cantidad);
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
