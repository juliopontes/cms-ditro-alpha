<?php
namespace Joomla\Form;

use Joomla\Form\Field_Radio;
use Joomla\Language\Language;
use Joomla\Filesystem\Folder as JFolder;
use Joomla\Language\Text as JText;
use Joomla\Filesystem\File as JFile;

/**
 * Sample data Form Field class.
 *
 * @package  Joomla.Installation
 * @since    1.6
 */
class Field_sample extends Field_Radio
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Sample';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
		$lang = Language::getInstance();
		$options = array();
		$type = $this->form->getValue('db_type');

		// Some database drivers share DDLs; point these drivers to the correct parent
		if ($type == 'Mysqli')
		{
			$type = 'mysql';
		}
		elseif ($type == 'Sqlsrv')
		{
			$type = 'sqlazure';
		}

		// Get a list of files in the search path with the given filter.
		$files = JFolder::files(JPATH_ROOT . '/sql/' . $type, '^sample.*\.sql$');

		// Add option to not install sample data.
		$option = new \stdClass;
		$option->value = '';
		$option->text = JText::_('INSTL_SITE_INSTALL_SAMPLE_NONE');

		$options[] = $option;

		// Build the options list from the list of files.
		if (is_array($files))
		{
			foreach ($files as $file)
			{
				$option = new \stdClass;
				$option->value = $file;
				$option->text = JText::_('INSTL_' . ($file = JFile::stripExt($file)) . '_SET');

				$options[] = $option;
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
		if (!$this->value)
		{
			$this->value = '';
		}

		return parent::getInput();
	}
}
