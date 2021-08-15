<?php include('dateBase.php');
    if(isset($_SESSION['id'])){
    session_regenerate_id(TRUE);
    header('Location:index.php');
    exit();
    }

    if(count($_POST) === 0){
        $message = 'please type';
    }else{
        
    }

    $message = htmlspecialchars($message);
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>signup</title>
        <link rel="stylesheet" href="/css/signup.css">
        <link rel="stylesheet" href="/css/logo.css">
        <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru:wght@300&display=swap" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    </head>
    <body>
        <?php include('logo.php'); ?>
        <div class="error">
            <?php echo $message ?>
        </div>
        
        <h2><span>signup</span></h2>
        
        <%if(errors.length>0){%>
            <ul class="error">
                <%errors.forEach(error=>{%>
                <li><%=error%></li>
                <%});%>
            </ul>
            <%}%>
            
        <div class="wrapper">
            <form action="/signup" method="post">
                <input type="hidden" name="_csrf" value="<%=csrfToken%>">
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