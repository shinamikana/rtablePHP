<?php session_start();
    include('env.php');
        $host = getenv("DB_HOSTNAME");
        $dbname = getenv('DB_NAME');
            $dsn = "mysql:dbname=${dbname};host=${host};charset=utf8" ?>
        <?php $driver_options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => false,];
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD')?>
        <?php $pdo=new PDO($dsn,$username,$password,$driver_options);?>