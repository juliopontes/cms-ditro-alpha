<?php
/**
* @copyright Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/

namespace Installer\Controller;

use Installer\Controller\DefaultController;

/**
* Database Controller class for the Application
*
* @since 1.0
*/
class DatabaseController extends DefaultController
{
	public function installSampleData()
	{
		// Get the application
		$app = $this->getApplication();

		// Check for request forgeries.
		$app->checkToken();

		// Get the setup model.
		$model = new \Installer\Model\SetupModel($this->getApplication(),$this->getInput());

		// Get the options from the session
		$options = $model->getOptions();

		// Get the database model.
		$db = new \Installer\Model\DatabaseModel($this->getApplication(),$this->getInput());

		// Attempt to create the database tables.
		$return = $db->installSampleData($options);

		$r = new stdClass;
		$r->view = 'install';

		// Check if the database was initialised
		if (!$return)
		{
			$r->view = 'database';
		}

		$app->sendJsonResponse($r);
	}

	public function backup()
	{
		$this->handleDatabase();
	}

	public function remove()
	{
		$this->handleDatabase();
	}

	public function handleDatabase()
	{
		// Get the application
		$app = $this->getApplication();
		// Check for request forgeries.
		$app->checkToken();

		// Get the setup model.
		$model = new \Installer\Model\SetupModel($this->getApplication(),$this->getInput());

		// Get the options from the session
		$options = $model->getOptions();

		// Get the database model.
		$db = new \Installer\Model\DatabaseModel($this->getApplication(),$this->getInput());

		// Attempt to create the database tables.
		$return = $db->handleOldDatabase($options);

		$r = new \stdClass;
		$r->view = 'install';

		// Check if the database was initialised
		if (!$return)
		{
			$r->view = 'database';
		}

		$app->sendJsonResponse($r);
	}

	public function install()
	{
		// Get the application
		$app = $this->getApplication();

		// Check for request forgeries.
		$app->checkToken();

		// Get the setup model.
		$model = new \Installer\Model\SetupModel($this->getApplication(),$this->getInput());

		// Get the options from the session
		$options = $model->getOptions();

		// Get the database model.
		$db = new \Installer\Model\DatabaseModel($this->getApplication(),$this->getInput());

		// Attempt to create the database tables.
		$return = $db->createTables($options);

		$r = new \stdClass;
		$r->view = 'install';

		// Check if the database was initialised
		if (!$return)
		{
			$r->view = 'database';
		}

		$app->sendJsonResponse($r);
	}
}