<?php session_start();
    $_SESSION = array();
    session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>logout</title>
    <link rel="stylesheet" href="/public/css/logout.css">
</head>
<body>
    <?php include('logo.php'); ?>
    <h2>logout</h2>
    <p>logout done</p>
    <span id="again">\see you again/</span>
    <img src="/public/images/bye.png" alt="">
</body>
</html>