<?php
require(__DIR__ . '/application/core.php');

$currentCity = get_curr_city();
set_curr_city($currentCity);

if(isset($_GET['id'])){
    $id=$_GET['id'];
}

$cities=new CityRepository();
$cities=$cities->getCities();

$auto=new AutoRepo();
$auto=$auto->getAutoById($id);

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
            foreach( $cities as $codeCity=>$cityData ) {
                $disabled = $currentCity == $codeCity ? 'disabled' : '';
                ?>
                <a href="/?curr_city=<?=$cityData->code?>" class="btn btn-primary <?=$disabled?>"><?=$cityData->name?></a>
                <?php
            }
            ?>
        </div>
    </div>
    <br>
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#">�������</a></li>
            <li class="active"><?=$auto->auto->name ?></li>
        </ol>

        <div class="col-xs-1"><strong>������:</strong></div>
        <div class="col-xs-11">
            <?=$auto->auto->name.' '.$auto->auto->year?>�.<br>
            <?=$auto->auto->run?><br>
            <?=$auto->auto->power?><br>
            <?=$auto->auto->isAutoTrans?><br>
            <?=$auto->auto->is4wd?>
        </div>

        <div class="col-xs-1"><strong>����:</strong></div>
        <div class="col-xs-11">
            <?=$auto->price->getPriceString()?>
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


