<?php   include('dateBase.php');
    session_regenerate_id(TRUE);
    $postView = $pdo->prepare('SELECT * FROM post JOIN users ON user_id=id ORDER BY post_id DESC');
    $postView->execute();
    if(isset($_SESSION['id'])){
        $icon = $pdo->prepare('SELECT icon FROM users WHERE id=?');
        $icon->bindParam(1,$_SESSION['id'],PDO::PARAM_INT);
        $icon -> execute();
        $iconResult = $icon->fetch();
    }
?>
<?php require_once('dateBase.php'); ?>
<!DOCTYPE html>
<html lag="ja">
    <head>
        <meta charset="utf-8">
        <title>ROUND TABLE</title>
        <link rel="stylesheet" href="/public/css/top.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru:wght@300&display=swap" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/earlyaccess/hannari.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bona+Nova&display=swap" rel="stylesheet">
    </head>
    <body>

        <div class="w-wrapper">
        </div>

        <?php include('logo.php'); ?>

        <div class="info">
            <i class="fas fa-bars"></i>
            <ul>
                <li><a id="adminBtn">who is admin?</a></li>
                <?php if(isset($_SESSION['id'])):?>
                <li><a href="/mypage.php">mypage</a></li>
                <li><a href="/logout.php">logout</a></li>
                <?php else: ?>
                <li><a href="/login.php">login</a></li>
                <li><a href="/signup.php">signup</a></li>
                <?php endif?>
            </ul>
        </div>

                
        <div class="myInfo">
        <?php if(isset($_SESSION['id'])):?>
            <a href="/mypage.php"><span><?php echo $_SESSION['username']?></span><img src="<?php echo $iconResult['icon'] ?>"></a>
        <?php else:?>
            <a><span>guest</span><img src="/images/icon.jpg" alt=""></a>
        <?php endif ?>
        </div>

        <div class="img-view">
            <img src="">
        </div>

        <div class="img-wrapper">
        <?php foreach($postView->fetchAll() as $imgs):?>
        <?php if($imgs['img'] !== ''): ?>
        <div class="img-sum">
            <img src="<?php echo $imgs['img']?>" class="imgSum-img" alt="">
        </div>
        <?php endif ?>
        <?php endforeach?>
        </div>
       
        <?php include('admin.php'); ?>

        <div class="post-wrapper">
        <?php $postView->execute()?>
            <?php foreach($postView->fetchAll() as $post):?>

            <div class="post">
                <img class="icon" src="<?php echo $post['icon']?>">
                <a href="/user/<%=posted.id%>"><?php echo $post['username']?></a>    <!--投稿者-->
                <p class="post-info"><span class="like"><form action="/" method="post" id="like-form"><?php echo $post['favo']?></span> <button type="submit" name="favoid" value="<%=posted.post_id%>" id="favo-submit"><i class="fas fa-sign-language"></i></button></form>
                <?php if(count($_SESSION) !== 0): ?>
                    <?php if($post['user_id'] === $_SESSION['id']): ?>
                        <form action="/" method="post" id="del">
                            <input type="hidden" name="_csrf" value="<%=csrfToken%>">
                            <button name="del" value="<%=posted.post_id%>" id="deltn" onClick="return clickEvent()">delete</button>
                        </form>
                    <?php endif ?>         <!--投稿日表示-->
                    <?php endif ?>
                    </p></span>
                
                    <div class="content">
                        <p><?php echo $post['text']?></p>         <!--投稿内容-->
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


        <div class="posting">
            <i class="fas fa-angle-up"></i>
            <form action="index.php" method="post" id="postForm">
                <input type="hidden" name="_csrf" value="<%=csrfToken%>">
                <textarea name="img" id="img-url" type="text" cols="30" rows="10" placeholder="http//img url~.◯◯"></textarea>
                <textarea name="text" id="textarea" cols="30" rows="10" placeholder="text"></textarea>
                <input type="submit" value="submit" id="submit">
            </form>

        </div>

        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        <script async>
        function clickEvent(){
                let form = document.getElementById('del');
                if(confirm('seriously?')){
                    form.submit();
                }else{
                    return false;
                }
            }
        
        $(()=>{
            $('.posting').find('.fa-angle-up').hover(function(){             //投稿フレーム表示/非表示処理
                $('.w-wrapper').show(0);
                $('.posting').find('form').slideDown(300);
                $(this).hide(0);
            });

            $('.w-wrapper').click(function(){
                if($('.posting').find('form').css('display','block')){
                $(this).hide();
                }
                $('.posting').find('form').slideUp(400);
                $('.fa-angle-up').show();
            });

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
        <script async src="/public/js/app.js"></script>
    </body>
</html>