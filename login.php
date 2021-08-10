<?php session_start();
    $dsn = 'mysql:dbname=heroku_3bf83702ed90efb;host=us-cdbr-east-04.cleardb.com;charset=utf8';
    $driver_options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => false,];
    $username = 'b5ca0c8294a634';
    $password = 'e09e4331';
    $pdo=new PDO($dsn,$username,$password,$driver_options);
    if(isset($_SESSION['username'])){
        session_regenerate_id(TRUE);
        header('Location : index.php');
        exit();
    }
    
       if(count($_POST) === 0){
           $message = '';
       }else{
           if(empty($_POST['email']) || empty($_POST['password'])){
               $message = 'please type password or email';
           }else{
            $login = $pdo->prepare('SELECT * FROM users WHERE email=?');
            $login->bindParam(1,$_POST['email'],PDO::PARAM_STR,150); 
            $login->execute();
            $result = $login->fetch(PDO::FETCH_ASSOC);
           
                if(!password_verify($_POST['password'],$rusult['password'])){
                  $message = 'do not match';
                }else{
                 session_regenerate_id(TRUE);
                 $_SESSION['username'] = $_POST['username'];
                 $_SESSION['id'] = $result['id'];
                 header('Location:index.php');
                 exit();
                }
            }
        }
        $message = htmlspecialchars($message);
        ?>



<html lang="ja">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./public/css/login.css">
        <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru:wght@300&display=swap" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
        <title>login</title>
    </head>
    <body>
        
        <p class="error"><?php echo $message?></p>
        <h2>login</h2>
        <div class="wrapper">
            <form action="login.php" method="post">
            <p>email</p>
             <input name="email">
             <p>password</p>
             <input type="password" name="password">
             <br>
             <input type="submit" id="submit" value="login">
            </form>
        </div>
        
    </body>
</html>