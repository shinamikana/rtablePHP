<?php include_once('dateBase.php');
    $user = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $user -> bindParam(1,$_GET['userId'],PDO::PARAM_INT);
    $user -> execute();
    $userInfo = $user -> fetch();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/user.css">
    <title><%=give.username%> page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Architects+Daughter&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    
</head>
<body>
    <?php include('logo.php'); ?>

    <div class="info">
        <ul>
            <?php if(!isset($_SESSION['username'])): ?>   <!--セッションを確認-->
            <li><a href="/login">login</a></li>
            <li><a href="/signup">signup</a></li>
            <?php else: ?>
            <li><a href="/mypage">mypage</a></li>
            <li><a href="/logout">logout</a></li>
            <?php endif ?>
        </ul>
    </div>

    <?php echo $_GET['userId'] ?>

    <h2 id="mypage-logo"><?php echo $userInfo['username'] ?> page</h2>
    <div class="wrapper">
        <div class="sub-wrapper">
            <i class="fas fa-thumbtack pin1"></i>
            <i class="fas fa-thumbtack pin2"></i>
            <h2 id="karte">karte</h2><img class="icon" src="<?php echo $userInfo['icon'] ?>">
            <div class="content">
                <p>user name:<span><?php echo $userInfo['username'] ?></span></p>
                <form action="postlog.php" method="get" name="userId">
                    <input type="hidden" name="userId" value="$_GET['userId']">
                </form>
                <p>posted:<span>　　<a href="#" onclick="document.userId.submit();"><%=posts.upost%></a></span></p>
                <p>has likes:<span></span></p>
                <p>gives likes:<span><?php echo $userInfo['give_like'] ?></span></p>
                <span id="sign">　　awesome!!　　by　administor</span>
            </div>
        </div>
    </div>
</body>
</html>