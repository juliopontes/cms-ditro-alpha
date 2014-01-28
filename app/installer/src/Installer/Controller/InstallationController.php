<?php
/**
 * @copyright Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Installer\Controller;

use Installer\Controller\DefaultController;
use Joomla\Filesystem\Folder;
use Joomla\Language\Text as JText;

/**
 * Installation Controller class for the Application
 *
 * @since 1.0
 */
class InstallationController extends DefaultController
{
    public function removeFolder()
    {
        $app = $this->getApplication();

        $app->checkToken();

        // Check whether the folder still exists
        if (!file_exists(JPATH_WEB.'/installation'))
        {
            $app->sendJsonResponse(new \Exception(JText::sprintf('INSTL_COMPLETE_ERROR_FOLDER_ALREADY_REMOVED'), 500));
        }
        $return = Folder::delete(JPATH_WEB.'/installation');
        // If an error was encountered return an error.
        if (!$return)
        {
            $app->sendJsonResponse(new \Exception(JText::_('INSTL_COMPLETE_ERROR_FOLDER_DELETE'), 500));
        }

        // Create a response body.
        $r = new \stdClass;
        $r->text = JText::_('INSTL_COMPLETE_FOLDER_REMOVED');

        $app->sendJsonResponse($r);
    }
}