<?php
session_start();
date_default_timezone_set('America/Los_Angeles');

function __autoload($class_name) {
    $class_file = "./classes/{$class_name}.php";
    if( file_exists($class_file) ) {
        require($class_file);
    }
}

require(__DIR__ . '/data/project_functions.php');
//require(__DIR__ . '/data/project_classes.php');



$cityRepo = new CityRepo();

$currentCity = get_curr_city();
set_curr_city($currentCity);

$autos = get_autos($currentCity);

$user = User::auth();

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
                    foreach( $cityRepo->getCities() as $city ) {
                        $disabled = $currentCity == $city->code ? 'disabled' : '';
                        ?>
                        <a href="/?curr_city=<?=$city->code?>" class="btn btn-primary <?=$disabled?>"><?=$city->name?></a>
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
                    <?php if(!$user) { ?>
                    <!-- Not authorized user -->
                    <form action="/auth.php?action=login" method="post">
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
                                <?php if( isset($_SESSION['login']['error']) ) {
                                    ?><div class="alert alert-danger" role="alert"><?=$_SESSION['login']['error'];?></div><?
                                    unset($_SESSION['login']['error']);
                                }  ?>
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
                    <i class="glyphicon glyphicon-user"> <?=$user->login?></i>
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
                    foreach( $autos as $autoData ) {
                        ?><td><?=$autoData['model']['name']?></td><td><?=$autoData['model']['year']?></td><td><?=$autoData['model']['power']?> л.c.</td><td><?=$autoData['model']['run']?></td><td><?=$autoData['price']['value']?> руб.<br><?=$cityRepo->getCityByCode($autoData['cityCode'])->name?></td><?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="row bt">
                <br>
                &copy; <?=date('Y'); ?> Morda inc. by nasedkin
            </div>
            <br>
        </div>
    </body>
</html>