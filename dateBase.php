<?php session_start();
<<<<<<< HEAD
    include('env.php');
        $host = getenv("DB_HOSTNAME");
        $dbname = getenv('DB_NAME');
            $dsn = "mysql:dbname=${dbname};host=${host};charset=utf8" ?>
        <?php $driver_options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => false,];
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD')?>
=======
            $dsn = 'mysql:dbname=heroku_3bf83702ed90efb;host=us-cdbr-east-04.cleardb.com;charset=utf8' ?>
        <?php $driver_options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => false,];
            $username = 'b5ca0c8294a634';
            $password = 'e09e4331'?>
>>>>>>> heroku/master
        <?php $pdo=new PDO($dsn,$username,$password,$driver_options);?>