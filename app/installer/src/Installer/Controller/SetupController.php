<?php
/**
* @copyright Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/

namespace Installer\Controller;

use Installer\Controller\DefaultController;
use Joomla\Session\Session;

/**
* Setup Controller class for the Application
*
* @since 1.0
*/
class SetupController extends DefaultController
{
	private function validate()
	{
		$this->getApplication()->checkToken();

		// Get the input
        $input = $this->getInput();

        // view name
        $vName   = $input->getWord('view', $this->defaultView);

		// Get the setup model.
		$mClass = '\\Installer\\Model\\SetupModel';
		$model = new $mClass($this->getApplication(),$this->getInput());

		// Check the form
		$model->checkForm($vName);
	}
	
	public function configuration()
	{
		$this->validate();

		// Redirect to the page.
		$r = new \stdClass;
		$r->view = 'database';
		$this->getApplication()->sendJsonResponse($r);
	}

	public function database()
	{
		$this->validate();

		// Redirect to the page.
		$r = new \stdClass;
		$r->view = 'overview';
		$this->getApplication()->sendJsonResponse($r);
	}

	public function overview()
	{
		$this->validate();

		// Redirect to the page.
		$r = new \stdClass;
		$r->view = 'install';
		$this->getApplication()->sendJsonResponse($r);
	}
}