<?php
opcache_reset();
ini_set("display_errors",true);
error_reporting(E_ALL);

require_once 'includes/defines.php';
require_once 'includes/autoload.php';

if (!is_file(JPATH_CONFIGURATION.'/configuration.php')) {
	header('location: installation/');
}

define('JPATH_ROOT', JPATH_FRONTEND);

echo 'Site application soon';