<?php
/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Component\Users\Frontend\Model;

use Component\Users\Frontend\Model\DefaultModel;

/**
 * Overview model for the tracker application.
 *
 * @since  1.0
 */
class UsersModel extends DefaultModel
{
	public function getAll()
	{
		$users = array();

		$db = $this->application->getContainer()->get('db');

		if ($db->connected()) {
			$query = $db->getQuery(true);
			$query->select('username')->from('#__users');

			$db->setQuery($query);
			$users = $db->loadObjectList();
		}
		
		return $users;
	}
}
