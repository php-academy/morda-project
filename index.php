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
                    <a href="#" class="btn btn-primary">Барнаул</a>
                    <a href="#" class="btn btn-primary">Красноярск</a>
                    <a href="#" class="btn btn-primary disabled">Новосибирск</a>
                    <a href="#" class="btn btn-primary">Томск</a>
                </div>
            </div>
            <br>
            <div class="row">
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
            <br>
            <div class="row">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Модель</th><th>Год</th><th>Двигатель</th><th>Пробег</th><th>Цена</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Toyota Noah</td><td>2009</td><td>143 л.c.</td><td>45</td><td>750 000 руб.<br>Новосибирск</td>
                    </tr>
                    <tr>
                        <td>Toyota Noah</td><td>2009</td><td>143 л.c.</td><td>45</td><td>750 000 руб.<br>Новосибирск</td>
                    </tr>
                    <tr>
                        <td>Toyota Noah</td><td>2009</td><td>143 л.c.</td><td>45</td><td>750 000 руб.<br>Новосибирск</td>
                    </tr>
                    <tr>
                        <td>Toyota Noah</td><td>2009</td><td>143 л.c.</td><td>45</td><td>750 000 руб.<br>Новосибирск</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="row bt">
                <br>
                &copy; 2015 Morda inc.
            </div>
        </div>
    </body>
</html>