<?php
require(__DIR__ . '/data/project_functions.php');
$cities = require(__DIR__ . '/data/dbCity.php');
$autos = require(__DIR__ . '/data/dbAuto.php');


$currentCity = get_curr_city();
var_dump($currentCity);
set_curr_city($currentCity);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Morda</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="https//code.jquery.com/jquery-1.11.3.min.js"></script>
        <meta charset="UTF-8">
        <style>
            .w100 { width: 100px;}
            .bt { border-top: 1px solid;}
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
                <form action="data/forma.php" name="myform" method="post" >
                    <fieldset>
                        <div class="form-group form-inline">
                            <label class="col-sm-2 control-label">Цена:</label>
                            <input name="s_ot" placeholder="цена от" class="form-control">
                            -
                            <input name="s_do" placeholder="цена до" class="form-control">
                        </div>
                        <div class="form-group form-inline">
                            <label class="col-sm-2 control-label">Год:</label>
                            <input name="g_ot" placeholder="год от" class="form-control">
                            -
                            <input name="g_do" placeholder="год до" class="form-control">
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
                    
                        foreach ($autos as $avto_info_all) {
                            foreach ($avto_info_all as $avto_info)
                                $s = $avto_info_all['model']; // подмассив "Модель"
                                $d = $avto_info_all['price']; // подмассив "Прайс"
                                foreach( $cities as $cityData )
                                    if ($currentCity == $cityData['code']) {
                                        $e = $cityData['name'];                                    
                                    }                                                                                                 
                                if ($avto_info === $currentCity) 

                                    echo "<tr>
                                            <td>{$s['name']}</td><td>{$s['year']}</td><td>{$s['power']}</td><td>{$s['run']}</td><td>{$d['value']} {$d['currency']}<br>{$e}</td>
                                         </tr>";
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
                &copy; <?=date('Y'); ?> Morda inc.
                <br>
            </div>
        </div>
    </body>
</html>