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
        <?php $dsn = 'mysql:dbname=heroku_3bf83702ed90efb;host=us-cdbr-east-04.cleardb.com;charset=utf8' ?>
        <?php $driver_options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => false,];
            $username = 'b5ca0c8294a634';
            $password = 'e09e4331'?>
        <?php $pdo=new PDO($dsn,$username,$password,$driver_options);?>
            <?php $postView = $pdo->prepare('SELECT * FROM post JOIN users ON user_id=id ORDER BY post_id DESC'); ?>

        <div class="w-wrapper">
        </div>

        <%-include('logo.ejs');%>

        <div class="info">
            <i class="fas fa-bars"></i>
            <ul>
                <li><a id="adminBtn">who is admin?</a></li>
                <li><a href="/login">login</a></li>
                <li><a href="/signup">signup</a></li>
                <li><a href="/mypage">mypage</a></li>
                <li><a href="/logout">logout</a></li>
            </ul>
        </div>

                
        <div class="myInfo">
        <%if(locals.userId !== undefined){%>
            <a href="/mypage"><span><%=myInfo.username%></span><img src="<%=myInfo.icon%>"></a>
        <%}else{%>
            <a><span>guest</span><img src="/images/icon.jpg" alt=""></a>
        <%}%>
        </div>

        <div class="img-view">
            <img src="">
        </div>

        <div class="img-wrapper">
        <?php foreach($postView->fetchAll() as $imgs):?>
        <div class="img-sum">
            <img src="<?php echo $imgs['img']?>" class="imgSum-img" alt="">
        </div>
        <?php endforeach?>
        </div>
       
        <div class="admin">
            <i class="fas fa-times"></i>
            <div class="topText">
             <h3>Kenji Iwasaki</h3>
             <br>
             <img src="/images/icon.jpg" id="adminImg">
             <br>
             <a href="https://twitter.com/tunanikan?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-lang="en" data-show-count="false">Follow @tunanikan</a>
            </div>
            <div class="textContent">
            <div class="leftText">
              <p>Hi :)</p>
              <p>I'm from Osaka in Japan</p>
              <p>What do you think about the website?</p>
              <p>I think it is not so bad, isn't it :)</p>
              <p>I am still studying programing :)</p>
              <p>I will create more websites, stay tuned!</p>
              <p>thanks :)</p>
            </div>
            <div class="rightText">
              <p>こんにちは！</p>
              <p>私は大阪に住んでいます</p>
              <p>趣味でWEBサイトの制作をしています</p>
              <p>まだ勉強の途中なのでシンプルな作りとなりましたが</p>
              <p>これからもWEBサイトやWEBアプリを作り続けるので</p>
              <p>気に入って頂けたら是非仲良くしてください！</p>
              <p>このサイトに来てくれてありがとう！</p>
            </div>
        </div>
        </div>

        <div class="post-wrapper">
        <?php $postView->execute()?>
            <?php foreach($postView->fetchAll() as $post):?>

            <div class="post">
                <img class="icon" src="<?php echo $post['icon']?>">
                <a href="/user/<%=posted.id%>"><?php echo $post['username']?></a>    <!--投稿者-->
                <p class="post-info">|　　<?php echo $post['date']?> <span class="like"><form action="/" method="post" id="like-form"><?php echo $post['favo']?></span> <button type="submit" name="favoid" value="<%=posted.post_id%>" id="favo-submit"><i class="fas fa-sign-language"></i></button></form>
                    <%if(posted.user_id ===locals.userId){%>
                        <form action="/" method="post" id="del">
                            <input type="hidden" name="_csrf" value="<%=csrfToken%>">
                            <button name="del" value="<%=posted.post_id%>" id="deltn" onClick="return clickEvent()">delete</button>
                        </form>
                    <%}%></p>         <!--投稿日表示-->
                    </span>
                
                    <div class="content">
                        <p><?php echo $post['text']?></p>         <!--投稿内容-->
                    </div>
                    <?php if($post['img'] !== ''):?>
                        <img class="content-img" src="<?php echo $post['img']?>" alt="">
                    <?php endif?>
                </div>
                <?php endforeach?>
            </div>


        <div class="posting">
            <i class="fas fa-angle-up"></i>
            <form action="/" method="post" id="postForm">
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

            $('#adminBtn').click(()=>{
                $('.admin').show();
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
        <script async src="/js/app.js"></script>
    </body>
</html>