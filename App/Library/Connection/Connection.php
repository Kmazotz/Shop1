<?php

    require '../../../vendor/
    .php';

    use Medoo\Medoo;
    use App\Settings\Config;

    final class Connection
    {

        /**
         * @var Medoo 
         */
        private static $connection;

        public static function GetConnection() : Medoo
        {
            if( static::$connection === null )
                static::Init();
          
            echo $connection;
            
            return static::$connection;
        }

        private static function Init(): void
        {
            $config = Config::GetConfig();

            $options = [
                "database_type" => $config['Database']['type'],
                "database_name" => $config['Database']['db_name'],
                "server" => $config['Server']['host'],
                "port" => $config['Server']['port'],
                "username" => $config['Database']['db_user'],
                "password" => $config['Database']['db_password'],
                        // [optional]
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'port' => 3306,
    
                // [optional] Table prefix
                'prefix' => 'PREFIX_',
    
                // [optional] Enable logging (Logging is disabled by default for better performance)
                'logging' => true,
    
                // [optional] MySQL socket (shouldn't be used with server and port)
                'socket' => '/tmp/mysql.sock',
    
                // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
                'option' => [
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ],
    
                // [optional] Medoo will execute those commands after connected to the database for initialization
                'command' => [
                    'SET SQL_MODE=ANSI_QUOTES'
                    ]
                ];
    
            static::$connection = new static($options);
            
        }
    }
    
?> 

