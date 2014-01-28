<?php
/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Installer\Response;

use Joomla\Language\Text as JText;

/**
 * Database model for the tracker application.
 *
 * @since  1.0
 */
class ResponseJson
{
	/**
	 * Constructor for the JSON response
	 *
	 * @param   mixed  $data  Exception if there is an error, otherwise, the session data
	 *
	 * @since   3.1
	 */
	public function __construct($app, $data)
	{
		// The old token is invalid so send a new one.
		$this->token = $app->getFormToken(true);

		// Get the language and send it's tag along
		$this->lang = $app->getLanguage()->getTag();

		// Get the message queue
		$messages = $app->getMessageQueue();

		// Build the sorted message list
		if (is_array($messages) && count($messages))
		{
			foreach ($messages as $msg)
			{
				if (($msg['message'] instanceof \RuntimeException)) {
					$message = $msg['message']->getMessage();
				} else if (is_array($msg['message'])) {
					if (isset($msg['type']) && isset($msg['message']))
					{
						$message = $msg['message'];
					}
				}
				if (!empty($message)) {
					$lists[$msg['type']][] = $message;
				}
			}
		}

		// If messages exist add them to the output
		if (isset($lists) && is_array($lists))
		{
			$this->messages = $lists;
		}

		// Check if we are dealing with an error.
		if ($data instanceof \Exception)
		{
			// Prepare the error response.
			$this->error   = true;
			$this->header  = JText::_('INSTL_HEADER_ERROR');
			$this->message = $data->getMessage();
		}
		else
		{
			// Prepare the response data.
			$this->error = false;
			$this->data  = $data;
		}
	}
}
