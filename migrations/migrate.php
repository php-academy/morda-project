<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 13.10.15
 * Time: 10:40
 */

require(__DIR__ . '/../application/core.php');
$options = getopt("d::", array('name:',));
if(
    isset($options['name'])
) {
    $name = $options['name'];
    $action = isset($options['d']) ? 'down' : 'up';

    $m = new Migrator();
    $m->run($name, $action);
} else {
    echo "use command: migrate.php --name=NAME [-d]\n";
}