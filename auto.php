<?php
require(__DIR__ . '/data/project_functions.php');
$cities = require(__DIR__ . '/data/dbCity.php');

$currentCity = get_curr_city();
set_curr_city($currentCity);
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
        <?php
        array(
            'model' => array(
                'name'  		=> 'Toyota Noah',
                'year'  		=> 2010,
                'run'   		=> 67000,
                'power' 		=> 143,
                'isAutoTrans'	=> true,
                'is4wd'   		=> false,
            ),
            'price' => array(
                'value' 	=> 750000,
                'currency'	=> 'RUB',
            ),
            'cityCode' => 'nsk',
            )
        ?>
        <ol class="breadcrumb">
            <li><a href="#">Главная</a></li>
            <li class="active">Toyota Noah</li>
        </ol>

        <div class="col-xs-1"><strong>Модель:</strong></div>
        <div class="col-xs-11">
            Toyota Noah 2009г.<br>
            67 тыс. км.<br>
            143 л.c.<br>
            Автомат<br>
            4WD
        </div>

        <div class="col-xs-1"><strong>Цена:</strong></div>
        <div class="col-xs-11">
            750000 руб.
        </div>
    </div>
    <br>
    <div class="row bt">
        <br>
        <?
        date_default_timezone_set('America/Los_Angeles');
        ?>
        &copy; <?=date('Y'); ?> Morda inc. by Boris

        <br>
    </div>
</div>
</body>
</html>