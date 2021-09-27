<?php   include('dateBase.php');
    //セキュリティの為、セッションIDを変更
    session_regenerate_id(TRUE);

    //エスケープ処理 XSS対策
    function h($str){
        return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
    }

    //クリックジャッキング対策
    //他のページを重ねて表示させない
    header('X-FRAME-OPTIONS:DENY');

    //トークン処理

    $TOKEN_LENGTH = 16;
    $tokenByte = openssl_random_pseudo_bytes($TOKEN_LENGTH);
    $csrfToken = bin2hex($tokenByte);
    //セッションに設定
    $_SESSION['csrfToken'] = $csrfToken;

    //投稿をDBから持ってくる
    $postView = $pdo->query('SELECT * FROM post JOIN users ON user_id=id ORDER BY post_id DESC');
    if(isset($_SESSION['id'])){
        $icon = $pdo->prepare('SELECT icon FROM users WHERE id=?');
        $icon->bind_param('i',$_SESSION['id']);
        $icon -> execute();
        $resultIcon = $icon -> get_result() -> fetch_assoc();
    }
    
    if(isset($_SESSION['csrfToken']) || $_SESSION['csrfToken'] === $_POST['csrf']){
    //投稿処理
    if(count($_POST) === 0){    //POSTの有無確認
        $message = '';
    }else{
        if(!empty($_POST['text']) || !empty($_POST['img'])){    //投稿フォームの各POSTのどちらかが空でなければ投稿
            //日時取得
            date_default_timezone_set('Asia/Tokyo');
            $date = date('Y/m/d/ H:i:s');
            //セッションがなければゲスト投稿
            if(empty($_SESSION['username'])){
                $gpost = $pdo->prepare('INSERT INTO post(text,date,username,img,ipAddress) VALUES(?,?,"guest",?,?) ');
                $gpost -> bind_param('ssss',$_POST['text'],$date,$_POST['img'],$_SERVER['REMOTE_ADDR']);
                $gpost -> execute();
                $gpost -> close();
                header('Location:index.php');
            }else{
                //セッションがあれば通常投稿
                $post = $pdo->prepare('INSERT INTO post(text,user_id,date,username,img,ipAddress) VALUES(?,?,?,?,?,?) ');
                $post -> bind_param('sissss',$_POST['text'],$_SESSION['id'],$date,$_SESSION['username'],$_POST['img'],$_SERVER['REMOTE_ADDR']);
                $post -> execute();
                $post -> close();
                header('Location:index.php');
            }
        }else{
            $message = '';
        }
        
        //いいね処理
        //いいねフォームが空でなければ ＝　送信されれば
        if(!empty($_POST['favoid'])){
            if(isset($_SESSION['id'])){
                $hfavo = $pdo->prepare('UPDATE users SET give_like = give_like+1 WHERE id = ?');
                $hfavo -> bind_param('i',$_SESSION['id']);
                $hfavo -> execute();
                $hfavo -> close();
            }
            $gfavo = $pdo->prepare('UPDATE post SET favo = favo+1 WHERE post_id = ?');
            $gfavo -> bind_param('i',$_POST['favoid']);
            $gfavo -> execute();
            $gfavo -> close();
            header('Location:index.php');
        }

        if(!empty($_POST['del'])){
            $del = $pdo->prepare('DELETE FROM post WHERE post_id = ?');
            $del -> bind_param('i',$_POST['del']);
            $del -> execute();
            $del -> close();
            header('Location:index.php');
        }
    }
    }else{
        header('Location:https://www.google.com/');
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
        <div class="faWrapper" id="faW">
            <i class="fas fa-adjust" id="dark" onclick="darking()"></i>
        </div>

        <div class="w-wrapper">
        </div>

        <?php include('logo.php'); ?>

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

                
        <div class="myInfo" id="myInfo">
        <?php if(isset($_SESSION['id'])):?>
            <a href="/mypage.php"><span><?php echo h($_SESSION['username'])?></span><img src="<?php echo h($resultIcon['icon']) ?>"></a>
        <?php else:?>
            <a><span>guest</span><img src="/images/icon.jpg" alt=""></a>
        <?php endif ?>
        </div>

        <!--確認ダイアログ -->
        <div class="confirm">
            <p>seriously?</p>
            <button onclick="false;" id="cancel">nope</button><button onclick="clickEvent();" id="ok">yup</button>
        </div>

        <!--エラー表示-->
        <?php if($message !== ''): ?>
        <p class="error"><?php echo h($message) ?></p>
        <?php endif ?>

        <div class="img-view">
            <img src="">
        </div>

        <!--画像一覧-->
        <div class="img-wrapper" id="imgW">
        <?php foreach($postView as $imgs):?>
        <?php if($imgs['img'] !== ''): ?>
        <div class="img-sum">
            <img src="<?php echo h($imgs['img'])?>" class="imgSum-img" alt="">
        </div>
        <?php endif ?>
        <?php endforeach?>
        </div>
       
        <?php include('admin.php'); ?>


        <div class="post-wrapper" id="check">
            <?php foreach($postView as $post):?>

            <div class="post">
            <a href="#" onclick="document.<?php echo h('form'.$post['post_id']) ?>.submit();"class="userName"><img class="icon" src="<?php echo h($post['icon'])?>"></a>
                <form method="get" action="user.php" name="<?php echo h('form'.$post['post_id']) ?>">
                    <input type="hidden" name="userId" value="<?php echo h($post['user_id']) ?>">
                    <input type="hidden" name="csrf" value="<?php echo $csrfToken ?>">
                    <a href="#" onclick="document.<?php echo h('form'.$post['post_id']) ?>.submit();"class="userName"><?php echo h($post['username'])?></a>
                </form>
                <div id="userInfo">
                <p class="post-info"><span class="like"><form action="index.php" method="post" id="like-form"><input type="hidden" name="csrf" value="<?php echo $csrfToken ?>"><?php echo h($post['favo'])?></span> <button type="submit" name="favoid" value="<?php echo h($post['post_id']) ?>" id="favo-submit"><i class="fas fa-sign-language"></i></button></form>
                <?php if(isset($_SESSION['id'])): ?>
                    <?php if($post['user_id'] == $_SESSION['id']): ?>
                        <form action="index.php" method="post" id="del">
                            <input type="hidden" name="csrf" value="<?php echo $csrfToken ?>">
                            <button id="deltn" name="del" value="<?php echo h($post['post_id']) ?>">delete</button>
                        </form>
                    <?php endif ?>
                    <?php endif ?>
                    </p></span>
                </div>

                    <div class="content">
                        <p class="contentP"><?php echo h($post['text'])?></p>         <!--投稿内容-->
                    </div>
                    <?php if($post['img'] !== ''):?>
                        <div class="imgDiv">
                            <img class="content-img" src="<?php echo h($post['img'])?>" alt="">
                        </div>
                    <?php endif?>
                    <span id="data"><?php echo h($post['date'])?></span>
                </div>
                <?php endforeach?>
            </div>


        <div class="posting">
            <i class="fas fa-angle-up"></i>
            <form action="index.php" method="post" id="postForm">
                <textarea name="img" id="img-url" type="text" cols="30" rows="10" placeholder="http//img url~.◯◯"></textarea>
                <input type="hidden" name="csrf" value="<?php echo $csrfToken ?>">
                <textarea name="text" id="textarea" cols="30" rows="10" placeholder="text"></textarea>
                <input type="submit" value="submit" id="submit">
            </form>

        </div>

        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        <script async>


        function clickEvent(){
                let form = document.getElementById('del');
                    form.submit();
            }

            //PHPでonclick時に切り替えるように
            const darking = ()=>{
                if(!check.classList.contains('postD')){
                    chageDark();
                }else{
                    reChangeDark();
                }
            }

            const chageDark = ()=>{
                let dark = document.getElementById('dark');
                let check = document.getElementById('check');
                let html = document.documentElement;
                let body = document.body;
                let imgW = document.getElementById('imgW');
                let logoA = document.getElementById('logoA');
                let logoS = document.getElementById('logoS');
                let textarea = document.getElementById('textarea');
                let submit = document.getElementById('submit');
                let imgUrl = document.getElementById('img-url');
                let admin = document.getElementById('admin');
                let txtC = document.getElementById('txtcontent');
                let faBg = document.getElementById('faW');
                let myInfo = document.getElementById('myInfo');

                    dark.classList.add('rotate');
                    check.classList.add('postD');
                    html.classList.add('postD');
                    body.classList.add('postD');
                    imgW.classList.add('postD');
                    textarea.classList.add('postD');
                    textarea.classList.add('darkContentP');
                    submit.classList.add('darkContentP');
                    submit.classList.add('postD');
                    imgUrl.classList.add('postD');
                    imgUrl.classList.add('darkContentP');
                    admin.classList.add('postD');
                    txtC.classList.add('darkContentP');
                    logoA.classList.add('bgD');
                    logoS.classList.add('colorD');
                    faBg.classList.add('lightBg');
                    myInfo.classList.add('bgD');
                    posting();
                    infoD();
            }

            const reChangeDark = ()=>{
                let dark = document.getElementById('dark');
                let check = document.getElementById('check');
                let html = document.documentElement;
                let body = document.body;
                let imgW = document.getElementById('imgW');
                let logoA = document.getElementById('logoA');
                let logoS = document.getElementById('logoS');
                let textarea = document.getElementById('textarea');
                let submit = document.getElementById('submit');
                let imgUrl = document.getElementById('img-url');
                let admin = document.getElementById('admin');
                let txtC = document.getElementById('txtcontent');
                let faBg = document.getElementById('faW');
                let myInfo = document.getElementById('myInfo');

                    removeDark();
                    rInfoD();
                    check.classList.remove('postD');
                    html.classList.remove('postD');
                    body.classList.remove('postD');
                    dark.classList.add('rRotate');
                    imgW.classList.remove('postD');
                    logoA.classList.remove('bgD');
                    logoS.classList.remove('colorD');
                    textarea.classList.remove('postD');
                    submit.classList.remove('postD');
                    submit.classList.remove('darkContentP');
                    textarea.classList.remove('darkContentP');
                    imgUrl.classList.remove('postD');
                    imgUrl.classList.remove('darkContentP');
                    admin.classList.remove('postD');
                    txtC.classList.remove('darkContentP');
                    dark.classList.remove('rotate');
                    faBg.classList.remove('lightBg');
                    myInfo.classList.remove('bgD');
            }

            let count = 0;
            let posts = document.getElementsByClassName('post');
            let content = document.getElementsByClassName('content');
            let darkP = document.getElementsByClassName('contentP');
            let userName = document.getElementsByClassName('userName');
            const posting = ()=>{
                posts[count].classList.add('postD');
                content[count].classList.add('postD');
                darkP[count].classList.add('darkContentP');
                userName[count].classList.add('darkContentP');
                ++count ;
                const timer = setTimeout(posting);
                if(count === posts.length){
                    clearTimeout(timer);
                }
            }

            let haveDark = document.getElementsByClassName('postD');
            let haveDarkP = document.getElementsByClassName('darkContentP');
            let count2 = 0;
            const removeDark = ()=>{
                count--;
                posts[count].classList.remove('postD');
                content[count].classList.remove('postD');
                darkP[count].classList.remove('darkContentP');
                userName[count].classList.remove('darkContentP');
                const removeTimer = setTimeout(removeDark);
                if(count === 0 ){
                    clearTimeout(removeTimer);
                    count = 0;
                }
            }

            let infoLi = document.getElementsByClassName('infoLi');
            let count3 = 0;

            const infoD = ()=>{
                console.log(infoLi.length);
                console.log(count3);
                infoLi[count3].classList.add('bgD');
                const infoTimer = setTimeout(infoD);
                count3++;
                if(count3 === infoLi.length){
                    clearTimeout(infoTimer);
                }
            }

            const rInfoD = ()=>{
                --count3;
                infoLi[count3].classList.remove('bgD');
                const removeInfoTimer = setTimeout(rInfoD);
                if(count3 === 0){
                    clearTimeout(removeInfoTimer);
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

            $('#deltn').click(()=>{
                $('.confirm').show();
            });

            $('#cancel').click(()=>{
                $('.confirm').hide();
            });


        });

        </script>
        <script async src="/public/js/app.js"></script>
    </body>
</html>