<?php include('dateBase.php');
    if(isset($_SESSION['id'])){
    session_regenerate_id(TRUE);
    header('Location:index.php');
    exit();
    }

    if(count($_POST) === 0){
        $message = '';
    }else{
        if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])){
            $message = 'please type email or username or password';
        }else{
            if($_POST['password'] !== $_POST['repassword']){
                $message = 'password is not much';
            }else{
                $hashPass = password_hash($_POST['password'],PASSWORD_BCRYPT);
                $check = $pdo->prepare('SELECT email FROM users WHERE email = ?');
                $check->bindParam(1,$_POST['email'],PDO::PARAM_STR,150);
                $check -> execute();
                $checkResult = $check->fetch(PDO::FETCH_ASSOC);
                if(!isset($checkResult)){
                    $message = 'email is Duplicate';
                }else{
                    $signup = $pdo->prepare('INSERT INTO users (username,email,password) VALUE (?,?,?)');
                    $signup -> bindParam(1,$_POST['username'],PDO::PARAM_STR,10);
                    $signup -> bindParam(2,$_POST['email'],PDO::PARAM_STR,150);
                    $signup -> bindParam(3,$hashPass,PDO::PARAM_STR,150);
                    $signup -> execute();
                    $signupResult = $signup->fetch(PDO::FETCH_ASSOC);
                    session_regenerate_id(TRUE);
                    $_SESSION['username'] = $_POST['username'];
                    $_SESSION['id'] = $pdo->lastInsertId();
                    header('Location:index.php');
                    exit();
                }
            }
        }
    }

?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>signup</title>
        <link rel="stylesheet" href="/public/css/signup.css">
        <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru:wght@300&display=swap" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    </head>
    <body>
        <?php include('logo.php'); ?>
        
        <h2><span>signup</span></h2>
        
            <ul class="error">
                <?php if($message !== ''): ?>
                <li><?php echo $message ?></li>
                <?php endif ?>
            </ul>
            
        <div class="wrapper">
            <form action="signup.php" method="post">
                <p>username([a-zA-Z0-9]{1,10})</p>
                <input name="username" pattern="^([a-zA-Z0-9]{1,15})$">
                <p>email</p>
                <input type="email" name="email" >
                <p>password([a-zA-Z0-9]{1,50})</p>
                <input type="password" name="password" pattern="^([a-zA-Z0-9]{1,50})$">
                <p>retype password</p>
                <input type="password" name="repassword" pattern="^([a-zA-Z0-9]{1,50})$">
                <br>
                <input type="submit" value="submit" id="submit">
            </form>
        </div>
    </body>
</html>