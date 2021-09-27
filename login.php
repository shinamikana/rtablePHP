<?php include('dateBase.php');
    if(isset($_SESSION['id'])){
        session_regenerate_id(TRUE);
        header('Location:index.php');
        exit();
    }
    
       if(count($_POST) === 0){
           $message = '';
       }else{
           if(empty($_POST['email']) && empty($_POST['password'])){
               $message = 'please type password or email';
           }else{
            $login = $pdo->prepare('SELECT * FROM users WHERE email = ?');
            $login->bind_param('s',$_POST['email']); 
            $login->execute();
            $result = $login -> get_result() -> fetch_assoc();
            if(!isset($result['email'])){
                $message = 'wrong email';
            }else{
                if(!password_verify($_POST['password'],$result['password'])){
                  $message = 'do not match password';
                }else{
                 session_regenerate_id(TRUE);
                 $_SESSION['username'] = $result['username'];
                 $_SESSION['id'] = $result['id'];
                 $_SESSION['icon'] = $result['icon'];
                 header('Location:index.php');
                }
            }
            }
        }

        ?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./public/css/login.css">
        <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru:wght@300&display=swap" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
        <title>login</title>
        <script src="https://www.google.com/recaptcha/api.js"></script>
    </head>
    <body>
        <?php include('logo.php');?>
        <h2>login</h2>
        <?php if($message === ''): ?>
        <?php else : ?>
        <p class="error"><?php echo $message?></p>
        <?php endif ?>
        <div class="wrapper">
            <form action="login.php" method="post" id="loginForm">
            <p>email</p>
             <input name="email" value="<?php if(!empty($_POST['email'])){echo $_POST['email'];} ?>">
             <p>password</p>
             <input type="password" name="password">
             <br>
             <button class="g-recaptcha" data-sitekey="6LeiLZIcAAAAAD7bdpvaSyisU50Opi1shovLyrsP" data-callback="onSubmit" data-action="submit">login</button>
            </form>
        </div>
        
        <script>
            function onSubmit(token){
                document.getElementById('loginForm').submit();
            }
        </script>
    </body>
</html>