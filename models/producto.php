<?php
//use files
require_once('mysqlConnection.php');
require_once('exceptions/recordNotFoundException.php');
require_once('categoria.php');
require_once('subcategoria.php');

//classs name
class Producto
{
    //Atributes
    private $producto_id;
    private $categoria;
    private $subcategoria;
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
        return $this->categoria;
    }
    public function setCategoriaId($value)
    {
        $this->categoria = $value;
    }

    public function getSubcategoriaId()
    {
        return $this->subcategoria;
    }
    public function setSubcategoriaId($value)
    {
        $this->subcategoria = $value;
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
        if (func_num_args() == 0) {
            $this->producto_id = 0;
            $this->categoria = new Categoria();
            $this->subcategoria = new Subcategoria();
            $this->nombre = "";
            $this->descripcion = "";
            $this->foto = "";
            $this->estatus = 1;
        }
        //constructor with data from database
        if (func_num_args() == 1) {
            //get id
            $producto_id = func_get_arg(0);
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = "  Select p.producto_id, c.categoria_id, c.nombre, c.estatus, s.subcategoria_id,s.nombre , s.estatus, 
            p.nombre, p.descripcion, p.foto, p.estatus From producto p LEFT JOIN categoria c ON p.categoria_id = c.categoria_id LEFT JOIN subcategoria s ON p.subcategoria_id = s.subcategoria_id
            Where producto_id = ?";
            //command
            $command = $connection->prepare($query);
            //bind parameter
            $command->bind_param('i', $producto_id);
            //execute
            $command->execute();
            //bind result
            $command->bind_result(
                $producto_id,
                $categoria_id,
                $categoriaNombre,
                $categoriaEstatus,
                $subcategoria_id,
                $subcategoriaNombre,
                $subcategoriaEstatus,
                $nombre,
                $descripcion,
                $foto,
                $estatus
            );
            //record was found
            if ($command->fetch()) {
                $this->producto_id = $producto_id;
                $this->categoria = new Categoria($categoria_id, $categoriaNombre, $categoriaEstatus);
                $this->subcategoria = new subcategoria($subcategoria_id, $subcategoriaNombre, $subcategoriaEstatus);
                $this->nombre = $nombre;
                $this->descripcion = $descripcion;
                $this->foto = $foto;
                $this->estatus = $estatus;
            } else {
                //throw exception if record not found
                throw new RecordNotFoundException($producto_id);
            }
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
        }
        //constructor with data from arguments
        if (func_num_args() == 7) {
            //get arguments
            $arguments = func_get_args();
            //pass arguments to attributes
            $this->producto_id = $arguments[0];
            $this->categoria = $arguments[1];
            $this->subcategoria = $arguments[2];
            $this->nombre = $arguments[3];
            $this->descripcion = $arguments[4];
            $this->foto = $arguments[5];
            $this->estatus = $arguments[6];
        }
    }

    //represent object in JSON format
    public function toJson()
    {
        if ($this->estatus == '1') {
            return json_encode(
                array(
                    'producto_id' => $this->producto_id,
                    'categoria_id' => json_decode($this->categoria->toJson()),
                    'subcategoria_id' => json_decode($this->subcategoria->toJson()),
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'foto' => $this->foto,
                    'estatus' => $this->estatus
                )
            );
        } else {
            return json_encode(
                array(
                    'message' => 'Producto inexistente'
                )
            );
        }
    }


    public static function getAll()
    {
        //list of records
        $records = array();
        //get connection
        $connection = MysqlConnection::getConnection();
        //query
        $query = "  Select p.producto_id, c.categoria_id, c.nombre, c.estatus, s.subcategoria_id,s.nombre , s.estatus, 
        p.nombre, p.descripcion, p.foto, p.estatus From producto p LEFT JOIN categoria c ON p.categoria_id = c.categoria_id LEFT JOIN subcategoria s ON p.subcategoria_id = s.subcategoria_id WHERE p.estatus = 1;";
        //command
        $command = $connection->prepare($query);
        //execute
        $command->execute();
        //bind results
        $command->bind_result(
            $producto_id,
            $categoria_id,
            $categoriaNombre,
            $categoriaEstatus,
            $subcategoria_id,
            $subcategoriaNombre,
            $subcategoriaEstatus,
            $nombre,
            $descripcion,
            $foto,
            $estatus
        );
        //record was found
        //fetch data
        while ($command->fetch()) {
            $categoria = new Categoria($categoria_id, $categoriaNombre, $categoriaEstatus);
            $subcategoria = new Subcategoria($subcategoria_id, $subcategoriaNombre, $subcategoriaEstatus);
            array_push($records, new Producto(
                $producto_id,
                $categoria,
                $subcategoria,
                $nombre,
                $descripcion,
                $foto,
                $estatus
            ));
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
        foreach (self::getAll() as $record) {
            array_push($list, json_decode($record->toJson(), true)); // Agrega true para decodificar como array asociativo (seccion Modificada)
        }
        //return list
        return $list; // Devuelve el array en lugar de codificarlo nuevamente como JSON (seccion modificada)
    }

    //get all by categoria
    public static function getAllByFilter($Filter)
    {
        //list
        $list = array();
        $filterValue = array();
        $types = '';
        //get connection
        $connection = MysqlConnection::getConnection();
        //query
        $query = 'Select p.producto_id, c.categoria_id, c.nombre, c.estatus, 
        s.subcategoria_id,s.nombre , s.estatus, p.nombre, p.descripcion, p.foto, p.estatus 
        From producto p LEFT JOIN categoria c ON p.categoria_id = c.categoria_id 
        LEFT JOIN subcategoria s ON p.subcategoria_id = s.subcategoria_id
        where ';

        foreach ($Filter as $category => $element) {
            $query .= "$category ";
            if ($category == "p.nombre") {
                $query .= "like ? and ";
                $types .= 's';
            } else {
                $query .= "= ? and ";
                $types .= 'i';
            }
            array_push($filterValue, $element);
        }
        $query .= "p.estatus = 1; ";
        //command
        $command = $connection->prepare($query);

        //bind param
        echo $Filter[0];
        if (count($Filter) == 3) {
            $command->bind_param($types, $filterValue[0], $filterValue[1], $filterValue[2]);
        } else if (count($Filter) == 2) {
            $command->bind_param($types, $filterValue[0], $filterValue[1]);
        } else {
            $command->bind_param($types, $filterValue[0]);
        }

        //execute 
        $command->execute();
        //bind results
        $command->bind_result(
            $producto_id,
            $categoria_id,
            $categoriaNombre,
            $categoriaEstatus,
            $subcategoria_id,
            $subcategoriaNombre,
            $subcategoriaEstatus,
            $nombre,
            $descripcion,
            $foto,
            $estatus
        );
        //record was found
        //fetch data
        while ($command->fetch()) {
            $categoria = new Categoria($categoria_id, $categoriaNombre, $categoriaEstatus);
            $subcategoria = new Subcategoria($subcategoria_id, $subcategoriaNombre, $subcategoriaEstatus);
            array_push($list, new Producto(
                $producto_id,
                $categoria,
                $subcategoria,
                $nombre,
                $descripcion,
                $foto,
                $estatus
            ));
        }
        //close command
        mysqli_stmt_close($command);
        //close connection
        $connection->close();
        //Return records
        return $list;
    }

    //get all json by Categoria
    public static function getAllByJsonByFilter($Filter)
    {
        //list
        $list = array();
        //get all
        foreach (self::getAllByFilter($Filter) as $item) {
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
        $query = "Insert Into producto (categoria_id, subcategoria_id, nombre, descripcion, foto) Values(?, ?, ?, ?, ?)";
        //command
        $command = $connection->prepare($query);
        //bin parameter
        $command->bind_param(
            'iisss',
            $this->categoria,
            $this->subcategoria,
            $this->nombre,
            $this->descripcion,
            $this->foto
        );
        //execute
        $result = $command->execute();
        //close command
        mysqli_stmt_close($command);
        //close connection
        $connection->close();
        //retun result
        return $result;
    }

    function delete()
    {
        $connection = MysqlConnection::getConnection();
        $query = "Update producto set estatus = 0 where producto_id = ?";
        //command 
        $command = $connection->prepare($query);
        $command->bind_param('i', $this->producto_id);
        $result = $command->execute();
        //close command
        mysqli_stmt_close($command);
        $connection->close();
        return $result;
    }
}
