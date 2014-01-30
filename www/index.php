<?php
opcache_reset();
ini_set("display_errors",true);
error_reporting(E_ALL);

require_once 'includes/defines.php';
require_once 'includes/autoload.php';

/*
if (!is_file(JPATH_CONFIGURATION.'/configuration.php')) {
	$uri = str_replace(basename($_SERVER['SCRIPT_FILENAME']),'',$_SERVER['PHP_SELF']);
	header('location: '.$uri.'installation');
}*/

define('JPATH_ROOT', JPATH_APP_ADMINISTRATOR);

$container = new \Joomla\DI\Container;
$container->registerServiceProvider(new \Administrator\Service\ConfigurationServiceProvider(JPATH_ROOT . '/etc/config.json'))
    ->registerServiceProvider(new \Administrator\Service\DatabaseServiceProvider);

// Instantiate the application.
$application = new \Administrator\App($container);

// Execute the application.
$application->execute();