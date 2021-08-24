<?php   include('dateBase.php');
    //セキュリティの為、セッションIDを変更
    session_regenerate_id(TRUE);

    $postView = $pdo->prepare('SELECT * FROM post JOIN users ON user_id=id WHERE id = ? ORDER BY post_id DESC');
    $postView -> bindParam(1,$_GET['userId'],PDO::PARAM_INT);
    $postView->execute();

    $name = $pdo->prepare('SELECT username FROM users WHERE id=?');
    $name -> bindParam(1,$_GET['userId'],PDO::PARAM_INT);
    $name -> execute();
    $userName = $name -> fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>log</title>
    <link rel="stylesheet" href="/public/css/top.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <?php include('logo.php') ?>

    <div class="info">
            <i class="fas fa-bars"></i>
            <ul>
                <li><a id="adminBtn" class="infoLi">who is admin?</a></li>
                <?php if(isset($_SESSION['id'])):?>
                <li><a href="/mypage.php" class="infoLi">mypage</a></li>
                <li><a href="/logout.php" class="infoLi">logout</a></li>
                <?php else: ?>
                <li><a href="/login.php" class="infoLi">login</a></li>
                <li><a href="/signup.php" class="infoLi">signup</a></li>
                <?php endif?>
            </ul>
        </div>

    <h2 id="log">[<?php echo $userName[0] ?>]   log</h2>
    
    <div class="post-wrapper" id="check">
        <?php $postView->execute()?>
            <?php foreach($postView->fetchAll() as $post):?>

            <div class="post">
                <img class="icon" src="<?php echo $post['icon']?>">
                <form method="get" action="user.php" name="<?php echo 'form'.$post['post_id'] ?>">
                    <input type="hidden" name="userId" value="<?php echo $post['user_id'] ?>">
                    <a href="#" onclick="document.<?php echo 'form'.$post['post_id'] ?>.submit();"class="userName"><?php echo $post['username']?></a>
                </form>
                <p class="post-info"><span class="like"><form action="index.php" method="post" id="like-form"><?php echo $post['favo']?></span> <button type="submit" name="favoid" value="<?php echo $post['post_id'] ?>" id="favo-submit"><i class="fas fa-sign-language"></i></button></form>
                <?php if(isset($_SESSION['id'])): ?>
                    <?php if($post['user_id'] === $_SESSION['id']): ?>
                        <form action="index.php" method="post" id="del">
                            <input type="hidden" name="del" value="<?php echo $post['post_id'] ?>"></button>
                        </form>
                    <button id="deltn" onclick="document.<?php echo $post['post_id'] ?>.submit()">delete</button>
                    <?php endif ?>
                    <?php endif ?>
                    </p></span>
                
                    <div class="content">
                        <p class="contentP"><?php echo $post['text']?></p>         <!--投稿内容-->
                    </div>
                    <?php if($post['img'] !== ''):?>
                        <div class="imgDiv">
                            <img class="content-img" src="<?php echo $post['img']?>" alt="">
                        </div>
                    <?php endif?>
                    <span id="data"><?php echo $post['date']?></span>
                </div>
                <?php endforeach?>
            </div>
    </div>
    
    <script async src="/public/js/app.js"></script>
</body>
</html>