<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
require(__DIR__ . '/data/project_functions.php');
require(__DIR__ . '/data/project_classes.php');
$cities = require(__DIR__ . '/data/dbCity.php');
$autos  = require(__DIR__ . '/data/dbAuto.php');
$users = require( __DIR__ . '/data/dbusers.php');

$currentCity = get_curr_city();
set_curr_city($currentCity);

//POST данные для поиска

$search=getPostParam();
$checked=isChecked($search);

//var_dump($search);

$autos = filter($autos,$cities,$currentCity,$search);

//$isUserAuth=isAuth();


$isUserAuth = false;
$isUserAuth=User::isAuth();


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
            .error{ color:red;}
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
                    <form action="" method="post">
                        <fieldset>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Цена:</label>
                                <input placeholder="цена от" class="form-control" name="price1" value="<?=$search['price1']?>">
                                -
                                <input placeholder="цена до" class="form-control" name="price2" value="<?=$search['price2']?>">
                            </div>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Год:</label>
                                <input placeholder="год от" class="form-control" name="year1" value="<?=$search['year1']?>">
                                -
                                <input placeholder="год до" class="form-control" name="year2" value="<?=$search['year2']?>">
                            </div>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Расстояние от меня:</label>
                                <input placeholder="расстояние" name="distance" class="form-control" value="<?=$search['distance']?>">
                            </div>
                            <div class="checkbox">
                                <div class="col-sm-2"></div>
                                <label><input type="checkbox" name="autotrans" value="true" <?=$checked['autotrans']?>>Автомат</label>
                            </div>
                            <div class="checkbox">
                                <div class="col-sm-2"></div>
                                <label><input type="checkbox" name="wd" value="true" <?=$checked['wd']?>>4WD</label>
                            </div>
                            <div class="form-group form-inline">
                                <button type="submit" class="btn btn-default">Найти</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="col-xs-3 ar">
                    <?php if(!$isUserAuth['user']){?>
                    <!-- Not authorized user -->
                    <form action="/auth.php" method="post">
                        <fieldset>
                            <?php if($isUserAuth['error']){?>
                                <div>
                                    <label class="error">
                                        <?=$isUserAuth['error']?>
                                    </label>
                                </div>
                            <?php } ?>
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
                    <i class="glyphicon glyphicon-user"><?=$isUserAuth['user']->login?></i>
                    <br>
                    <a href="/?logout=1">Выход</a>
                    <!-- Authorized user -->
                    <?php } ?>
                </div>
            </div>
            <br>
            <?php if($autos){?>
            <div class="row">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Модель</th><th>Год</th><th>Двигатель</th><th>Пробег</th><th>Цена</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach( $autos as $id=>$autoData ) {
                        ?>
                        <tr>
                            <td><a href="/auto_detail.php?id=<?=$id?>"><?=$autoData['model']['name']?></a></td>
                            <td><?=$autoData['model']['year']?></td>
                            <td><?=$autoData['model']['power']?> л.c.</td>
                            <td><?=$autoData['model']['run']?></td>
                            <td><?=$autoData['price']['value']?> руб.<br><?=get_city_name_by_code($cities, $autoData['cityCode'])?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php }else{
                echo 'Машины не найдены!';
            } ?>
            <br>
            <div class="row bt">
                <br>
                <?
                date_default_timezone_set('America/Los_Angeles');
                ?>
                &copy; <?=date('Y'); ?> Morda inc. by nasedkin
            </div>
            <br>
        </div>
    </body>
</html>