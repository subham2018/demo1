<?php
@session_start();
$_SESSION = array();

$params = session_get_cookie_params(); 

setcookie(session_name(),'', time() - 42000,$params["path"],$params["domain"],$params["secure"],$params["httponly"]);

session_unset();

session_destroy();

if (isset($_SERVER['HTTP_COOKIE'])) {

    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);

    foreach($cookies as $cookie) {

        $parts = explode('=', $cookie);

        $name = trim($parts[0]);

        setcookie($name, '', 1);

        setcookie($name, '', 1, '/');

    }

}

header('Location: login.php');