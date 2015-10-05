<?php
require(__DIR__ . '/data/project_functions.php');
$cities = require(__DIR__ . '/data/dbCity.php');
$ads = require(__DIR__ . '/data/dbAuto.php'); # Массив с объявлениями.
$users = require(__DIR__ . '/data/dbUsers.php'); # Массив с пользователями.
$currentCity = get_curr_city();
$isUserAuth = getAuthFromCookie();

//$tmp = getUserByLogin('vasya');
//var_dump($tmp);
//$saltstr = 'helloword!123456';
//echo md5($saltstr);


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
                <div class="col-xs-9">
                    <form >
                        <fieldset>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Цена:</label>
                                <input placeholder="цена от" class="form-control">
                                -
                                <input placeholder="цена до" class="form-control">
                            </div>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Год:</label>
                                <input placeholder="год от" class="form-control">
                                -
                                <input placeholder="год до" class="form-control">
                            </div>
                            <div class="form-group form-inline">
                                <label class="col-sm-2 control-label">Расстояние от меня:</label>
                                <input placeholder="расстояние" class="form-control">
                            </div>
                            <div class="checkbox">
                                <div class="col-sm-2"></div>
                                <label><input type="checkbox">Автомат</label>
                            </div>
                            <div class="checkbox">
                                <div class="col-sm-2"></div>
                                <label><input type="checkbox">4WD</label>
                            </div>
                            <div class="form-group form-inline">
                                <button type="submit" class="btn btn-default">Найти</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="col-xs-3 ar">
                    <!-- Not authorized user -->
                    <?php if(!$isUserAuth)
                    { ?>
                    <form action="auth.php" method="post">
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

                    <?php } else { ?>
                    <!-- Authorized user -->
                    <i href="#" class="glyphicon glyphicon-user"> <?=$login;?></i>
                    <br>
                    <a href="/auth.php?action=logout">Выход</a>
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
                    foreach ($ads as $key => $carData) {
                        if ($carData['cityCode'] == $currentCity) { # Если город в объявлении совпадает с текущим городом...
                            // ...выбираем из этого объявления всю нужную информацию:
                            $city = $cities["$currentCity"]['name'];
                            $modelName = $carData['model']['name'];
                            $modelYear = $carData['model']['year'];
                            $modelRun = $carData['model']['run'];
                            $modelPower = $carData['model']['power'];
                            $modelIsAutoTrans = $carData['model']['isAutoTrans'];
                            $priceValue = $carData['price']['value'];
                            $priceCurrency = $carData['price']['currency'];
                            // Выводим в табличку:
                            ?>
                            <tr>
                                <td><?=$modelName;?></td>
                                <td><?=$modelYear;?></td>
                                <td><?=$modelPower;?></td>
                                <td><?=$modelRun;?></td>
                                <td><?=$priceValue;?> <?=$priceCurrency;?><br><?=$city;?></td>
                            </tr>
                            <?php
                        }
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
                &copy; <?=date('Y'); ?> Morda inc. by Boris

                <br>
            </div>
        </div>
    </body>
</html>