<?php
/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Installer\Helper;

use Joomla\Database\DatabaseDriver as JDatabaseDriver;

/**
 * Joomla Installation Database Helper Class.
 *
 * @package  Joomla.Installation
 * @since    1.6
 */
abstract class DatabaseHelper
{
	/**
	 * Method to get a JDatabaseDriver object.
	 *
	 * @param   string   $driver    The database driver to use.
	 * @param   string   $host      The hostname to connect on.
	 * @param   string   $user      The user name to connect with.
	 * @param   string   $password  The password to use for connection authentication.
	 * @param   string   $database  The database to use.
	 * @param   string   $prefix    The table prefix to use.
	 * @param   boolean  $select    True if the database should be selected.
	 *
	 * @return  JDatabaseDriver
	 *
	 * @since   1.6
	 */
	public static function getDBO($driver, $host, $user, $password, $database, $prefix, $select = true)
	{
		static $db;

		if (!$db)
		{
			// Build the connection options array.
			$options = array(
				'driver' => $driver,
				'host' => $host,
				'user' => $user,
				'password' => $password,
				'database' => $database,
				'prefix' => $prefix,
				'select' => $select
			);
			
			// Get a database object.
			$db = JDatabaseDriver::getInstance($options);
		}

		return $db;
	}
}