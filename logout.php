<?php
header('Location: /');
setcookie("user", $login . ":" . $_SERVER['HTTP_USER_AGENT'] . date('Y-m-d'), time() + 0, '/');