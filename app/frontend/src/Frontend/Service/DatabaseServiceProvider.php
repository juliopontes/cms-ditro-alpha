<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Service;

use Joomla\Database\DatabaseDriver;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * Database service provider
 *
 * @since  1.0
 */
class DatabaseServiceProvider implements ServiceProviderInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function register(Container $container)
	{
		$container->set('Joomla\\Database\\DatabaseDriver',
			function () use ($container)
			{
				$config = $container->get('config');

				$options = array(
					'driver' => $config->get('dbtype'),
					'host' => $config->get('host'),
					'user' => $config->get('user'),
					'password' => $config->get('password'),
					'database' => $config->get('db'),
					'prefix' => $config->get('dbprefix')
				);

				$db = DatabaseDriver::getInstance($options);
				$db->setDebug($config->get('debug.database', false));

				if (!$db->connected()) {
					$db->connect();
				}

				return $db;
			}, true, true
		);

		// Alias the database
		$container->alias('db', 'Joomla\\Database\\DatabaseDriver');
	}
}
