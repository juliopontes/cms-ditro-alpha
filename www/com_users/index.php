<?php
opcache_reset();
ini_set("display_errors",true);
error_reporting(E_ALL);

require_once '../includes/defines.php';
require_once '../includes/autoload.php';
define('JPATH_ROOT', JPATH_PROJECT);

$container = new \Joomla\DI\Container;

$component = new \Component\Users\Administrator\Dispatcher($container);
$component->execute();