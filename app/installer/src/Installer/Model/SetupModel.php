<?php
/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Installer\Model;

use Installer\Model\DefaultModel;

use Joomla\Form\Form as JForm;
use Joomla\Form\FormHelper as JFormHelper;
use Joomla\Database\DatabaseDriver;
use Joomla\Language\Text as JText;

/**
 * Default model for the tracker application.
 *
 * @since  1.0
 */
class SetupModel extends DefaultModel
{
	/**
	 * Gets PHP options.
	 *
	 * @return	array  Array of PHP config options
	 *
	 * @since   3.1
	 */
	public function getPhpOptions()
	{
		$options = array();

		// Check the PHP Version.
		$option = new \stdClass;
		$option->label  = \JText::_('INSTL_PHP_VERSION') . ' >= 5.3.1';
		$option->state  = version_compare(PHP_VERSION, '5.3.1', '>=');
		$option->notice = null;
		$options[] = $option;

		// Check for magic quotes gpc.
		$option = new \stdClass;
		$option->label  = \JText::_('INSTL_MAGIC_QUOTES_GPC');
		$option->state  = (ini_get('magic_quotes_gpc') == false);
		$option->notice = null;
		$options[] = $option;

		// Check for register globals.
		$option = new \stdClass;
		$option->label  = \JText::_('INSTL_REGISTER_GLOBALS');
		$option->state  = (ini_get('register_globals') == false);
		$option->notice = null;
		$options[] = $option;

		// Check for zlib support.
		$option = new \stdClass;
		$option->label  = \JText::_('INSTL_ZLIB_COMPRESSION_SUPPORT');
		$option->state  = extension_loaded('zlib');
		$option->notice = null;
		$options[] = $option;

		// Check for XML support.
		$option = new \stdClass;
		$option->label  = \JText::_('INSTL_XML_SUPPORT');
		$option->state  = extension_loaded('xml');
		$option->notice = null;
		$options[] = $option;

		// Check for database support.
		// We are satisfied if there is at least one database driver available.
		$available = DatabaseDriver::getConnectors();
		$option = new \stdClass;
		$option->label  = \JText::_('INSTL_DATABASE_SUPPORT');
		$option->label .= '<br />(' . implode(', ', $available) . ')';
		$option->state  = count($available);
		$option->notice = null;
		$options[] = $option;

		// Check for mbstring options.
		if (extension_loaded('mbstring'))
		{
			// Check for default MB language.
			$option = new \stdClass;
			$option->label  = \JText::_('INSTL_MB_LANGUAGE_IS_DEFAULT');
			$option->state  = (strtolower(ini_get('mbstring.language')) == 'neutral');
			$option->notice = ($option->state) ? null : JText::_('INSTL_NOTICEMBLANGNOTDEFAULT');
			$options[] = $option;

			// Check for MB function overload.
			$option = new \stdClass;
			$option->label  = \JText::_('INSTL_MB_STRING_OVERLOAD_OFF');
			$option->state  = (ini_get('mbstring.func_overload') == 0);
			$option->notice = ($option->state) ? null : JText::_('INSTL_NOTICEMBSTRINGOVERLOAD');
			$options[] = $option;
		}

		// Check for a missing native parse_ini_file implementation
		$option = new \stdClass;
		$option->label  = \JText::_('INSTL_PARSE_INI_FILE_AVAILABLE');
		$option->state  = $this->getIniParserAvailability();
		$option->notice = null;
		$options[] = $option;

		// Check for missing native json_encode / json_decode support
		$option = new \stdClass;
		$option->label  = \JText::_('INSTL_JSON_SUPPORT_AVAILABLE');
		$option->state  = function_exists('json_encode') && function_exists('json_decode');
		$option->notice = null;
		$options[] = $option;

		// Check for configuration file writable.
		$writable = (is_writable(JPATH_CONFIGURATION . '/configuration.php')
			|| (!file_exists(JPATH_CONFIGURATION . '/configuration.php') && is_writable(JPATH_CONFIGURATION)));

		$option = new \stdClass;
		$option->label  = \JText::sprintf('INSTL_WRITABLE', 'configuration.php');
		$option->state  = $writable;
		$option->notice = ($option->state) ? null : JText::_('INSTL_NOTICEYOUCANSTILLINSTALL');
		$options[] = $option;

		return $options;
	}

	/**
	 * Checks the availability of the parse_ini_file and parse_ini_string functions.
	 *
	 * @return	boolean  True if the method exists
	 *
	 * @since	3.1
	 */
	public function getIniParserAvailability()
	{
		$disabled_functions = ini_get('disable_functions');

		if (!empty($disabled_functions))
		{
			// Attempt to detect them in the disable_functions black list
			$disabled_functions = explode(',', trim($disabled_functions));
			$number_of_disabled_functions = count($disabled_functions);

			for ($i = 0; $i < $number_of_disabled_functions; $i++)
			{
				$disabled_functions[$i] = trim($disabled_functions[$i]);
			}

			$result = !in_array('parse_ini_string', $disabled_functions);
		}
		else
		{
			// Attempt to detect their existence; even pure PHP implementation of them will trigger a positive response, though.
			$result = function_exists('parse_ini_string');
		}

		return $result;
	}

	/**
	 * Checks if all of the mandatory PHP options are met
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.1
	 */
	public function getPhpOptionsSufficient()
	{
		$result  = true;
		$options = $this->getPhpOptions();

		foreach ($options as $option)
		{
			if (is_null($option->notice))
			{
				$result = ($result && $option->state);
			}
		}

		return $result;
	}

	/**
	 * Gets PHP Settings.
	 *
	 * @return  array
	 *
	 * @since   3.1
	 */
	public function getPhpSettings()
	{
		$settings = array();

		// Check for safe mode.
		$setting = new \stdClass;
		$setting->label = \JText::_('INSTL_SAFE_MODE');
		$setting->state = (bool) ini_get('safe_mode');
		$setting->recommended = false;
		$settings[] = $setting;

		// Check for display errors.
		$setting = new \stdClass;
		$setting->label = \JText::_('INSTL_DISPLAY_ERRORS');
		$setting->state = (bool) ini_get('display_errors');
		$setting->recommended = false;
		$settings[] = $setting;

		// Check for file uploads.
		$setting = new \stdClass;
		$setting->label = \JText::_('INSTL_FILE_UPLOADS');
		$setting->state = (bool) ini_get('file_uploads');
		$setting->recommended = true;
		$settings[] = $setting;

		// Check for magic quotes runtimes.
		$setting = new \stdClass;
		$setting->label = \JText::_('INSTL_MAGIC_QUOTES_RUNTIME');
		$setting->state = (bool) ini_get('magic_quotes_runtime');
		$setting->recommended = false;
		$settings[] = $setting;

		// Check for output buffering.
		$setting = new \stdClass;
		$setting->label = \JText::_('INSTL_OUTPUT_BUFFERING');
		$setting->state = (bool) ini_get('output_buffering');
		$setting->recommended = false;
		$settings[] = $setting;

		// Check for session auto-start.
		$setting = new \stdClass;
		$setting->label = \JText::_('INSTL_SESSION_AUTO_START');
		$setting->state = (bool) ini_get('session.auto_start');
		$setting->recommended = false;
		$settings[] = $setting;

		// Check for native ZIP support
		$setting = new \stdClass;
		$setting->label = \JText::_('INSTL_ZIP_SUPPORT_AVAILABLE');
		$setting->state = function_exists('zip_open') && function_exists('zip_read');
		$setting->recommended = true;
		$settings[] = $setting;

		return $settings;
	}
}
