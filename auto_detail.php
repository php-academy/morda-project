<?php

require(__DIR__ . '/data/project_functions.php');

if(isset($_GET['id'])){
    $id=$_GET['id'];
}

$auto=getAutoById($id);

print_r($auto);

