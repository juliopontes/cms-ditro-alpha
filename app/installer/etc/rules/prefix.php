<?php
namespace Joomla\Form\Rule;
use Joomla\Form\Rule;

/**
 * Form Rule class for the prefix DB.
 *
 * @package  Joomla.Installation
 * @since    1.7
 */
class Prefix extends Rule
{
	/**
	 * The regular expression to use in testing a form field value.
	 *
	 * @var    string
	 * @since  1.7
	 */
	protected $regex = '^[a-z][a-z0-9]*_$';

	/**
	 * The regular expression modifiers to use when testing a form field value.
	 *
	 * @var    string
	 * @since  1.7
	 */
	protected $modifiers = 'i';
}
