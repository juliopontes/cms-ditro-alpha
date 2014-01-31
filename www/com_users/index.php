<?php
opcache_reset();
ini_set("display_errors",true);
error_reporting(E_ALL);

require_once '../includes/defines.php';
require_once '../includes/autoload.php';

if (!is_file(JPATH_CONFIGURATION.'/configuration.php')) {
	header('location: ../installation');
}

define('JPATH_ROOT', JPATH_APP_ADMINISTRATOR);

$container = new \Joomla\DI\Container;

// read config and merge with configuration.php
$config = new \Administrator\Service\ConfigurationServiceProvider(JPATH_ROOT . '/etc/config.json');

$jconfig = \Joomla\Registry\Registry::getInstance('configuration');
require_once JPATH_CONFIGURATION.'/configuration.php';
$jconfig->loadObject(new \JConfig);
$config->getConfig()->merge($jconfig);

$container->registerServiceProvider($config)
    ->registerServiceProvider(new \Administrator\Service\DatabaseServiceProvider);


$component = new \Component\Users\Administrator\Dispatcher($container);
$component->execute();