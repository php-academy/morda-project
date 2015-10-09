<?php
header('Content-Type: text/html; charset=utf-8');
require(__DIR__ . '/data/project_functions.php');
$cities = require(__DIR__ . '/data/dbCity.php');
$currentCity = get_curr_city();
set_curr_city($currentCity);

if(isset($_GET['id'])){
    $id=$_GET['id'];
}

$auto=getAutoById($id);

auto_handler($auto);

//print_r($auto);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Morda</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <meta charset="UTF-8">
    <style>
        .bt { border-top: 1px solid;}
        .ar { text-align: right;}
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="btn-group btn-group-justified">
            <?php
            foreach( $cities as $cityData ) {
                if( $currentCity == $cityData['code'] ) {
                    $disabled = 'disabled';
                } else {
                    $disabled = '';
                }
                ?>
                <a href="/?curr_city=<?=$cityData['code']?>" class="btn btn-primary <?=$disabled?>"><?=$cityData['name']?></a>
                <?php
            }
            ?>
        </div>
    </div>
    <br>
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#">Главная</a></li>
            <li class="active"><?=$auto['model']['name']?></li>
        </ol>

        <div class="col-xs-1"><strong>Модель:</strong></div>
        <div class="col-xs-11">
            <?=$auto['model']['name'].' '.$auto['model']['year']?>г.<br>
            <?=$auto['model']['run']?><br>
            <?=$auto['model']['power']?><br>
            <?=$auto['model']['isAutoTrans']?><br>
            <?=$auto['model']['is4wd']?>
        </div>

        <div class="col-xs-1"><strong>Цена:</strong></div>
        <div class="col-xs-11">
            <?=$auto['price']['value']?> руб.
        </div>
    </div>
    <br>
    <div class="row bt">
        <br>
        <?
        date_default_timezone_set('America/Los_Angeles');
        ?>
        &copy; <?=date('Y'); ?> Morda inc. by Pavel

        <br>
    </div>
</div>
</body>
</html>


