<?php session_start();
    session_destroy();
    $_SESSION = array();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>logout</title>
    <link rel="stylesheet" href="/public/css/logout.css">
</head>
<body>
    <?php include('logo.php'); ?>
    <h1>logout</h1>
    <p>logout done</p>
</body>
</html>