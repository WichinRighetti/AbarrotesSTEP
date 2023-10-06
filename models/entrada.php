<?php
    //use files
    require_once('mysqlConnection.php');

    class Entrada{
        //attributes
        private $entrada_id;
        private $inventario;
        private $cantidad;
        private $fecha;

        //setters & getters
        public function setEntrada_id($value){$this->entrada_id = $value;}
        public function getEntrada_id(){return $this->entrada_id;}
        public function setInventario($value){$this->inventario = $value;}
        public function getInventario(){return $this->inventario;}
        public function setCantidad($value){$this->cantidad = $value;}
        public function getCantidad(){return $this->cantidad;}
        public function setFecha($value){$this->fecha = $value;}
        public function getFecha(){return $this->fecha;}

        //constructor
        public function __construct()
        {
            //empty constructor
            if(func_num_args() == 0){
                $this->entrada_id = 0;
                $this->inventario = new Inventario();
                $this->cantidad = 0;
                $this->fecha = '';
            }
            //constructor with data from database
            if(func_num_args() == 1){
                // get id
                $id = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection();
                //query
                $query = "Select e.entrada_id, p.producto_id, c.categoria_id, c.nombre, c.status 'Categoria status', c.categoria_id, 
                c.nombre, c.status 'Categoria status', s.subategoria_id, s.nombre, s.status 'Subcategoria status'p.nombre Producto, 
                p.descripcion, p.foto, i.inventario_id, a.almacen_id, a.nombre Almacen, a.direccion, a.descripcion 'Descripcion Almacen', i.cantidad,
                e.cantidad Entrada, e.fecha 
                from entrada e left JOIN inventario i ON e.inventario_id = i.inventario_id
                left JOIN almacen a ON i.almacen_id = a.almacen_id
                left JOIN producto p ON i.producto_id = p.producto_id
                left JOIN categoria c ON p.categoria_id = c.categoria_id
                left JOIN subcategoria s ON p.subcategoria_id = s.subcategoria_id
                where e.entrada_id = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id, $producto_id, $categoria_id, $categoria, $categoriaStatus, $subcategoria_id, $subcategoria, $subcategoriaStatus, $producto, $descripcionProducto, $foto, $inventario_id, $almacen_id, $almacen, $direccion, $almacenDescripcion, $cantidad, $cantidadEntrada, $fecha);
                //reconrd was found
                if($command->fetch()){
                    $c = new Categoria($categoria_id, $categoria, $categoriaStatus);
                    $s = new Subcategoria($subcategoria_id, $subcategoria, $subcategoriaStatus);
                    $p = new Producto($producto_id, $c, $s, $producto, $descripcionProducto, $foto);
                    $a = new Almacen($almacen_id, $almacen, $direccion, $almacenDescripcion);
                    $i = new Inventario($inventario_id, $p, $a, $cantidad);
                    //pass values to the attributes
                    $this->entrada_id = $id;
                    $this->inventario = $i;
                    $this->cantidad = $cantidad;
                    $this->fecha = $fecha;
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
                $this->entrada_id = $arguments[0];
                $this->inventario = $arguments[1];
                $this->cantidad = $arguments[2];
                $this->fecha = $arguments[3];
            }
        }

        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'entrada_id'=>$this->entrada_id,
                'producto'=>json_decode($this->inventario->toJson()),
                'cantidad'=>$this->cantidad,
                'fecha'=>$this->fecha,
            ));
        }

        //get all
        public static function getAll(){
            //list
            $list = array();
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = "Select e.entrada_id, p.producto_id, c.categoria_id, c.nombre, c.status 'Categoria status', c.categoria_id, 
            c.nombre, c.status 'Categoria status', s.subategoria_id, s.nombre, s.status 'Subcategoria status'p.nombre Producto, 
            p.descripcion, p.foto, i.inventario_id, a.almacen_id, a.nombre Almacen, a.direccion, a.descripcion 'Descripcion Almacen', i.cantidad,
            e.cantidad Entrada, e.fecha 
            from entrada e left JOIN inventario i ON e.inventario_id = i.inventario_id
            left JOIN almacen a ON i.almacen_id = a.almacen_id
            left JOIN producto p ON i.producto_id = p.producto_id
            left JOIN categoria c ON p.categoria_id = c.categoria_id
            left JOIN subcategoria s ON p.subcategoria_id = s.subcategoria_id";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id, $producto_id, $categoria_id, $categoria, $categoriaStatus, $subcategoria_id, $subcategoria, $subcategoriaStatus, $producto, $descripcionProducto, $foto, $inventario_id, $almacen_id, $almacen, $direccion, $almacenDescripcion, $cantidad, $cantidadEntrada, $fecha);
            //fetch data
            while($command->fetch()){
                $c = new Categoria($categoria_id, $categoria, $categoriaStatus);
                $s = new Subcategoria($subcategoria_id, $subcategoria, $subcategoriaStatus);
                $p = new Producto($producto_id, $c, $s, $producto, $descripcionProducto, $foto);
                $a = new Almacen($almacen_id, $almacen, $direccion, $almacenDescripcion);
                $i = new Inventario($inventario_id, $p, $a, $cantidad);

                array_push($list, new Entrada($id, $i, $cantidadEntrada, $fecha));
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