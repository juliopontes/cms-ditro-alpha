<?php
opcache_reset();
ini_set("display_errors",true);
error_reporting(E_ALL);

require_once '../includes/defines.php';
require_once '../includes/autoload.php';

define('JPATH_ROOT', JPATH_APP_INSTALLER);

$container = new \Joomla\DI\Container;
$container->registerServiceProvider(new \Installer\Service\ConfigurationServiceProvider(JPATH_ROOT . '/etc/config.json'))
    ->registerServiceProvider(new \Installer\Service\DatabaseServiceProvider);

// Instantiate the application.
$application = new \Installer\App($container);

// Execute the application.
$application->execute();