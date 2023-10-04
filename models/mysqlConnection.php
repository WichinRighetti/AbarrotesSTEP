<?php   
    //Class
    class MysqlConnection{
        //return MYSQL connection object
        public static function getConnection(){
            //open configuration file
            $configPath = $_SERVER['DOCUMENT_ROOT'].'/AbarrotesSTEP/config/mysqlConnection.json';
            $configData = json_decode(file_get_contents($configPath), true);
            //check parameters
            if(isset($configData['server'])){
                $server = $configData['server'];
            }else{
                echo 'Configuration error, server name not found'; die;
            }
            if(isset($configData['database'])){
                $database = $configData['database'];
            }else{
                echo 'Configuration error, database name not found'; die;
            }
            if(isset($configData['user'])){
                $user = $configData['user'];
            }else{
                echo 'Configuration error, user not found'; die;
            }
            if(isset($configData['password'])){
                $password = $configData['password'];
            }else{
                echo 'Configuration error, password not found'; die;
            }
            //create connection
            $connection = mysqli_connect($server, $user, $password, $database);
            //Character set
            $connection -> set_charset('utf8');
            //ckeck connection
            if(!$connection){
                echo 'Could not connect to MySQL'; die;
            }
            //return connection
            return($connection);
        }
        
    }
?>