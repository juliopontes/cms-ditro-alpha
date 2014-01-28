<?php
if (!defined('JPATH_VENDOR')) {
	throw new Exception('define JPATH_VENDOR');
}

// Load the Composer autoloader
$vendor_autoload = JPATH_VENDOR . '/autoload.php';
if (!file_exists($vendor_autoload) || (is_dir(basename($vendor_autoload)) && !file_exists(basename($vendor_autoload)) )) {
    throw new Exception('run composer install');
}

require $vendor_autoload;