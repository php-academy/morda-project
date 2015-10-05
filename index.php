<?php
require(__DIR__ . '/data/project_functions.php');
$cities = require(__DIR__ . '/data/dbCity.php');
$autos = require(__DIR__ . '/data/dbAuto.php');
$users = require(__DIR__ . '/data/dbUsers.php');
$currentCity = get_curr_city();
set_curr_city($currentCity);

$dist = (isset($_POST['distance']) && !empty($_POST['distance'])) ? $_POST['distance'] : NULL;
$price_ot = (isset($_POST['price_ot']) && !empty($_POST['price_ot'])) ? $_POST['price_ot'] : NULL;
$price_do = (isset($_POST['price_do']) && !empty($_POST['price_do']))? $_POST['price_do'] : NULL;
$year_ot = (isset($_POST['year_ot']) && !empty($_POST['year_ot'])) ? $_POST['year_ot'] : NULL;
$year_do = (isset($_POST['year_do']) && !empty($_POST['year_do'])) ? $_POST['year_do'] : NULL;
$isAutoTrans = (isset($_POST['isAutoTrans'])) ? $_POST['isAutoTrans'] : NULL;
$is4wd = (isset($_POST['is4wd'])) ? $_POST['is4wd'] : NULL;

$autos = filter($autos,$cities,$currentCity,$dist,$is4wd,$isAutoTrans,$price_ot,$price_do,$year_ot,$year_do);
//print_r($autos);
$isUserAuth = false;
if( isset($_COOKIE['user'])){
    $userCookie = $_COOKIE['user'];
    $arUserCookie = explode(':',$userCookie);
    $login = $arUserCookie[0];
    $saltPasswordCookie = $arUserCookie[1];

    if(isset($users[$login])){
        $saltpassword = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'].date("d.m.Y").$users[$login]['salt'].$users[$login]['saltpassword']);
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
                    <form action="/" method="post">
                        <fieldset>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Цена:</label>
                                <input name="price_ot" placeholder="цена от тыс. руб." class="form-control" value=<?=$price_ot?>>
                                -
                                <input name="price_do" placeholder="цена до тыс. руб." class="form-control" value=<?=$price_do?>>
                            </div>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Год:</label>
                                <input name="year_ot" placeholder="год от" class="form-control" value=<?=$year_ot?> >
                                -
                                <input name="year_do" placeholder="год до" class="form-control" value=<?=$year_do?>>
                            </div>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Расстояние от меня:</label>
                                <input name="distance" placeholder="расстояние км." class="form-control" value=<?=$dist?>>
                            </div>
                            <?php
                            $checked_isAutoTrans = isset($_POST['isAutoTrans']) ? 'checked' : '';
                            ?>
                            <div class="checkbox">
                                <div class="col-sm-2"></div>
                                <label><input name="isAutoTrans" type="checkbox" <?=$checked_isAutoTrans?>>Автомат</label>
                            </div>
                            <?php
                            $checked4wd = isset($_POST['is4wd']) ? 'checked' : '';
                            ?>
                            <div class="checkbox">
                                <div class="col-sm-2"></div>
                                <label><input name="is4wd" type="checkbox" <?=$checked4wd?>>4WD</label>
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

                    foreach($autos as $autodata1){
                        ?><tr><td><?=$autodata1['model']['name']?></td><td><?=$autodata1['model']['year']?></td><td><?=$autodata1['model']['power']?>л.с.</td><td><?=$autodata1['model']['run']?>км</td><td><?=$autodata1['price']['value']?>руб.<br><?=$cities[$autodata1['cityCode']]['name']?></td></tr><?php
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