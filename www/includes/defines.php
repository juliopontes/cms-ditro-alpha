<?php
// Defines Paths
define('JPATH_PROJECT', dirname(dirname(__DIR__)));
define('JPATH_APP', JPATH_PROJECT . '/app');
define('JPATH_ETC', JPATH_PROJECT . '/etc');
define('JPATH_VENDOR', JPATH_PROJECT . '/vendor');
define('JPATH_WEB', JPATH_PROJECT . '/www');
define('JPATH_CONFIGURATION', JPATH_PROJECT);

// autocreate define app path: JPATH_APP_FOLDERNAME
$directories = glob(JPATH_APP.'/*', GLOB_ONLYDIR);
foreach ($directories as $directory) {
	$define_var = trim('JPATH_APP_'.strtoupper(basename($directory)));
	if (!defined($define_var)) {
		define($define_var, $directory);
	}
}