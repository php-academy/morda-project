<?php
require(__DIR__ . '/data/project_functions.php');
$cities = require(__DIR__ . '/data/dbCity.php');
$autos = require(__DIR__ . '/data/dbAuto.php');
$users = require(__DIR__ . '/data/dbUsers.php');
$currentCity = get_curr_city();
set_curr_city($currentCity);

$autos = filter($autos,$cities,$currentCity,$dist,$is4wd,$isAutoTrans);
print_r($autos);
$isUserAuth = false;
if( isset($_COOKIE['user'])){
    $userCookie = $_COOKIE['user'];
    $arUserCookie = explode(':',$userCookie);
    $login = $arUserCookie[0];
    $saltPasswordCookie = $arUserCookie[1];

    if(isset($users[$login])){
        $saltpassword = $users[$login]['saltpassword'];
        if($saltPasswordCookie == $saltpassword){
            $isUserAuth = true;
        }
    }
}
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
                        $disabled = $currentCity == $cityData['code'] ? 'disabled' : '';
                        ?>
                        <a href="/?curr_city=<?=$cityData['code']?>" class="btn btn-primary <?=$disabled?>"><?=$cityData['name']?></a>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-9">
                    <form action="/filter.php" method="post">
                        <fieldset>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Цена:</label>
                                <input name="price_ot" placeholder="цена от тыс. руб." class="form-control">
                                -
                                <input name="price_do" placeholder="цена до тыс. руб." class="form-control">
                            </div>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Год:</label>
                                <input name="year_ot" placeholder="год от" class="form-control">
                                -
                                <input name="year_do" placeholder="год до" class="form-control">
                            </div>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Расстояние от меня:</label>
                                <input name="distance" placeholder="расстояние" class="form-control">
                            </div>
                            <div class="checkbox">
                                <div class="col-sm-2"></div>
                                <label><input name="isAutoTrans" type="checkbox">Автомат</label>
                            </div>
                            <div class="checkbox">
                                <div class="col-sm-2"></div>
                                <label><input name="is4wd" type="checkbox">4WD</label>
                            </div>
                            <div class="form-group form-inline">
                                <button type="submit" class="btn btn-default">Найти</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="col-xs-3 ar">
                    <?php if(!$isUserAuth){?>
                    <!-- Not authorized user -->
                    <form action="/auth.php" method="post">
                        <fieldset>
                            <div class="form-group form-inline">
                                <label class="col-sm-4 control-label"> Логин:</label>
                                <input name="login" placeholder="Логин" class="form-control">
                            </div>
                            <div class="form-group form-inline">
                                <label class="col-sm-4 control-label">Пароль:</label>
                                <input name="password" type="password" placeholder="Пароль" class="form-control">
                            </div>
                            <div class="form-group form-inline">
                                <div class="col-sm-4"></div>
                                <button type="submit" class="btn btn-default">Войти</button>
                            </div>
                        </fieldset>
                    </form>
                    <!-- Not authorized user -->
                    <?php } else {?>
                    <!-- Authorized user -->
                    <i class="glyphicon glyphicon-user"><?=$login?></i>
                    <br>
                    <a href="/logout.php">Выход</a>
                    <!-- Authorized user -->
                    <?php } ?>
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

                    foreach($autos as $autodata){
                        ?><td><?=$autodata['model']['name']?></td><td><?=$autodata['model']['year']?></td><td><?=$autodata['model']['power']?>л.с.</td><td><?=$autodata['model']['run']?>км</td><td><?=$autodata['price']['value']?>руб.<br><?=$cities[$autodata['cityCode']]['name']?></td><?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="row bt">
                <br>
                <?
                date_default_timezone_set('America/Los_Angeles');
                ?>
                &copy; <?=date('Y'); ?> Morda inc. by Ivan

                <br>
            </div>
        </div>
    </body>
</html>