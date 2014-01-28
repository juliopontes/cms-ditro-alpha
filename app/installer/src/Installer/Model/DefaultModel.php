<?php
/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Installer\Model;

use Joomla\Factory;
use Joomla\Input\Input;
use Joomla\Model\AbstractModel;
use Joomla\Form\Form as JForm;
use Joomla\Form\FormHelper as JFormHelper;

/**
 * Default model for the tracker application.
 *
 * @since  1.0
 */
class DefaultModel extends AbstractModel
{
    /**
     * Input object
     *
     * @var    Input
     * @since  1.0
     */
    protected $input;

    /**
     * Application Object
     *
     * @var     application
     * @since   1.0
     */
    protected $application;

    /**
     * Instantiate the model.
     *
     * @param   Input           $input  Input object.
     * @param   Registry        $state  The model state.
     *
     * @since   1.0
     */
    public function __construct($app, Input $input, Registry $state = null)
    {
        $this->input = $input;
        $this->application = $app;

        parent::__construct($state);
    }

    /**
     * Get Form
     */
    public function getForm($view = null)
    {
        if (is_null($view)) {
            $view = 'configuration';
        }

        JFormHelper::addFormPath(JPATH_ROOT.'/etc/forms/');
        JFormHelper::addFieldPath(JPATH_ROOT.'/etc/fields/');
        JFormHelper::addRulePath(JPATH_ROOT.'/etc/rules/');

        try
        {
            $form = JForm::getInstance('jform', $view, array('control' => 'jform'));
        }
        catch (Exception $e)
        {
            $this->application->enqueueMessage($e->getMessage(), 'error');
            return false;
        }

        // Check the session for previously entered form data.
        $data = (array) $this->getOptions();

        // Bind the form data if present.
        if (!empty($data))
        {
            $form->bind($data);
        }

        return $form;
    }

    /**
     * Method to check the form data
     *
     * @param   string  $view  The view being checked
     *
     * @return  array  Validated form data
     *
     * @since   3.1
     */
    public function checkForm($view = 'configuration')
    {

        // Get the posted values from the request and validate them.
        $data   = $this->application->input->post->get('jform', array(), 'array');
        $return = $this->validate($data, $view);

        // Attempt to save the data before validation
        $form = $this->getForm($view);
        $data = $form->filter($data);
        unset($data['admin_password2']);
        $this->storeOptions($data);

        // Check for validation errors.
        if ($return === false)
        {
            // Redirect back to the previous page.
            $r = new \stdClass;
            $r->view = $view;
            $this->application->sendJsonResponse($r);
        }

        unset($return['admin_password2']);

        // Store the options in the session.
        $vars = $this->storeOptions($return);

        return $vars;
    }

    /**
     * Method to validate the form data.
     *
     * @param   array   $data  The form data.
     * @param   string  $view  The view.
     *
     * @return  mixed   Array of filtered data if valid, false otherwise.
     *
     * @since   3.1
     */
    public function validate($data, $view = null)
    {
        // Get the form.
        $form = $this->getForm($view);

        // Check for an error.
        if ($form === false)
        {
            return false;
        }

        // Filter and validate the form data.
        $data   = $form->filter($data);
        $return = $form->validate($data);

        // Check for an error.
        if ($return instanceof Exception)
        {
            $this->application->enqueueMessage($return->getMessage(), 'warning');
            return false;
        }

        // Check the validation results.
        if ($return === false)
        {
            // Get the validation messages from the form.
            foreach ($form->getErrors() as $message)
            {
                if ($message instanceof Exception)
                {
                    $this->application->enqueueMessage($message->getMessage(), 'warning');
                }
                else
                {
                    $this->application->enqueueMessage($message, 'warning');
                }
            }

            return false;
        }

        return $data;
    }

    /**
     * Get the current setup options from the session.
     *
     * @return  array  An array of options from the session
     *
     * @since   3.1
     */
    public function getOptions()
    {
        $session = $this->application->getSession();
        $options = $session->get('setup.options', array());

        return $options;
    }

    /**
     * Store the current setup options in the session.
     *
     * @param   array  $options  The installation options
     *
     * @return  array  An array of options from the session
     *
     * @since   3.1
     */
    public function storeOptions($options)
    {
        // Get the current setup options from the session.
        $session = $this->application->getSession();
        $old = $session->get('setup.options', array());

        // Ensure that we have language
        if (!isset($options['language']) || empty($options['language']))
        {
            $options['language'] = $this->application->getLanguage()->getTag();
        }

        $options['helpurl'] = $session->get('setup.helpurl', null);

        // Merge the new setup options into the current ones and store in the session.
        $options = array_merge($old, (array) $options);
        $session->set('setup.options', $options);

        return $options;
    }
}
