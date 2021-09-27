<?php session_start();
    include('env.php');
        $host = getenv("DB_HOSTNAME");
        $dbname = getenv('DB_NAME');
            $dsn = "mysql:dbname=${dbname};host=${host};charset=utf8" ?>
        <?php $driver_options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => false,];
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD')?>
        <?php $pdo=new mysqli($host,$username,$password,$dbname);
        if($pdo -> connect_error){
            echo $pdo->connect_error;
            exit();
        }else{
            $pdo -> set_charset('utf8');
        }?>