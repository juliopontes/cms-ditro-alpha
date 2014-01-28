<?php
/**
 * @copyright Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Installer\Controller;

use Installer\Controller\DefaultController;

/**
 * Configuration Controller class for the Application
 *
 * @since 1.0
 */
class ConfigurationController extends DefaultController
{
    public function create()
    {
    	// Get the application
		$app = $this->getApplication();

		// Check for request forgeries.
		//$app->checkToken();

		// Get the setup model.
		$model = new \Installer\Model\SetupModel($this->getApplication(),$this->getInput());

		// Get the options from the session
		$options = $model->getOptions();

		// Get the database model.
		$configuration = new \Installer\Model\ConfigurationModel($this->getApplication(),$this->getInput());

    	// Attempt to setup the configuration.
		$return = $configuration->setup($options);

		$r = new \stdClass;
		$r->view = 'complete';

		// Check if the database was initialised
		if (!$return)
		{
			$r->view = 'database';
		}

		$app->sendJsonResponse($r);
    }
}