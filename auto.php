<?php
require(__DIR__ . '/app/core.php');
$autos = new AutoRepo();
$auto = $autos->Autos();
$auto = $auto[$_GET['id']];
$currentCity = City::getCurrentCity();
City::setCurrentCity($currentCity);
//$autos = $autos[$_GET['id']];
$cityRepo = new CityRepo();
var_dump($auto->price);
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
            foreach( $cityRepo->getCities() as $cityData ) {
                $disabled = $currentCity == $cityData->code ? 'disabled' : '';
                ?>
                <a href="/?curr_city=<?=$cityData->code?>" class="btn btn-primary <?=$disabled?>"><?=$cityData->name?></a>
                <?php
            }
            ?>
        </div>
    </div>
    <br>
    <div class="row">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Модель</th><th>Год</th><th>Двигатель</th><th>Пробег</th><th>Цена</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($auto as $autodata){
                ?><tr><td><a href=/auto.php?id=<?=$autodata->id?>><?=$autodata->model?></a></td>
                <td><?=$autodata->year?></td><td><?=$autodata->power?>л.с.</td><td><?=$autodata->run?>км</td>
                <td><?=$autodata->price->getPriceString()?>
                    <br><?=$cityRepo->getCityByCode($autodata->cityCode)->name?></td></tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <!--
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="/">Главная</a></li>
            <li class="active"><?=$autos['model']['name']?></li>
        </ol>

        <div class="col-xs-1"><strong>Модель:</strong></div>
        <div class="col-xs-11"><?php
            foreach($autos['model'] as $data){

            }
            ?>
        </div>

        <div class="col-xs-1"><strong>Цена:</strong></div>
        <div class="col-xs-11">
            750000 руб.
        </div>
    </div>-->
    <br>
    <div class="row bt">
        <br>

        date_default_timezone_set('America/Los_Angeles');
        &copy; <?=date('Y'); ?> Morda inc. by Ivan

        <br>
    </div>
</div>
</body>
</html>