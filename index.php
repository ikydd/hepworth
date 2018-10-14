<?php

error_reporting(E_ALL);

ini_set('display_errors', 1);

require 'engine/Autoloader.php';

session_start();

// $_GET['q'] will be the url without the base folder and domain name, just
// the extra bits. this is done by the .htaccess file
$engine = new Engine();
// the router will figure out from the url which controller to load, and which method to
// to execute on it, we then actually execute it right here and get the HTML contents from it
$url = isset($_GET['q']) ? $_GET['q'] : false;
$content = $engine->manufacture($url);

echo $content;