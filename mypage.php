<?php  require_once('dateBase.php');
        $info = $pdo->prepare('SELECT * FROM users WHERE id=?');
        $info2 = $pdo->prepare('SELECT COUNT(*) FROM users WHERE id=?');
        $info3 = $pdo->prepare('SELECT SUM(favo) FROM post WHERE user_id=?');
        
        $info->bindParam(1,$_SESSION['id'],PDO::PARAM_INT);
        $info2->bindParam(1,$_SESSION['id'],PDO::PARAM_INT);
        $info3->bindParam(1,$_SESSION['id'],PDO::PARAM_INT);
        $info->execute();
        $info2->execute();
        $info3->execute();
        $myInfo = $info->fetch();
        $posts = $info2->fetch();
        $favos = $info3->fetch();
    ?>
<?php if(count($_POST)===0){
    
}else{
    if(empty($_POST['icon'])){
        
    }else{
        $imgPost = $pdo->prepare('UPDATE users SET icon=? WHERE id=?');
        $imgPost->bindParam(1,$_POST['icon'],PDO::PARAM_STR);
        $imgPost->bindParam(2,$_SESSION['id'],PDO::PARAM_STR);
        $imgPost->execute();
        header('Location:mypage.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/mypage.css">
    <title>mypage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Architects+Daughter&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body>
    <?php include('admin.php'); ?>

    <?php require_once('dateBase.php'); ?>
    <?php include('logo.php'); ?>
    <div class="info">
        <i class="fas fa-bars"></i>
        <ul>
            <li><a id="adminBtn">who is admin?</a></li>
            <?php if(count($_SESSION) === 0): ?>
            <li><a href="/login.php">login</a></li>
            <li><a href="/signup.php">signup</a></li>
            <?php else: ?>
            <li><a href="/mypage.php">mypage</a></li>
            <li><a href="/logout.php">logout</a></li>
            <?php endif ?>
        </ul>
    </div>

    <h2 id="mypage-logo">mypage</h2>
    <div class="wrapper">
        <div class="sub-wrapper">
            <h2 id="karte">karte</h2><img class="icon" src="<?php echo $myInfo['icon']?>">
            <form action="/mypage.php" method="post" id="change_icon"><input name="icon" placeholder="//img url~.◯◯" id="imgurl"><br><input type="submit" name="submit" value="change icon" id="submit"></form>
            <div class="content">
                <p>user name:<span><?php echo $myInfo['username'] ?></span></p>
                <p>posted:　 <span><a href="/postlog"><?php echo $posts[0] ?></a></span></p>
                <p>have likes: <span><?php echo $favos[0] ?></span></p>
                <p>give likes:<span><?php echo $myInfo['give_like'] ?></span></p>
                <span id="sign">　　thanks!!　　by　administor</span>
            </div>
        </div>
    </div>
    <script>
        $(()=>{
            $('.info').find('.fa-bars').click(function(){
                if($(this).hasClass('hoge')){
                    $('.info').find('ul').slideUp();
                    $(this).removeClass('hoge');
                }else{
                    $('.info').find('ul').slideDown();
                    $(this).addClass('hoge');
                }
            });
        });
    </script>
    <script src="/public/js/app.js"></script>
</body>
</html>