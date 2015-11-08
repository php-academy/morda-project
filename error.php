<?php
/**
 * Created by PhpStorm.
 * User: LIS
 * Date: 16.10.2015
 * Time: 22:32
 */
$code = $_SERVER['argv'][0];
if($code==404){
    $message = 'Ничего страшного не случилось, вы видимо пришли не туда!
<br><br><br> Возвращайтесь на главную страницу!';
}
if($code==500){
$message = 'Что то не работает и мы не знаем что, но обязательно узнаем <br><br><br> Зайдите позже, мы скоро все исправим!';
}
if($code==403){
    $message = 'ДОСТУП ЗАПРЕЩЕН! <br><br><br> Возвращайтесь на главную страницу!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Currency Rates</title>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <ul class="nav nav-pills">
                <li role="presentation" class="active" >
                    <a href="">
                        <span class="glyphicon" aria-hidden="true"><?php echo "$message";?></span>
                    </a>
                </li>
        </ul>