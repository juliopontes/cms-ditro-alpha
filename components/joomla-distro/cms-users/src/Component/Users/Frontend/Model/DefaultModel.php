<?php
/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Component\Users\Frontend\Model;

use Joomla\Application\AbstractWebApplication;
use Joomla\Input\Input;
use Joomla\Model\AbstractModel;

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
    public function __construct(AbstractWebApplication $app, Input $input, Registry $state = null)
    {
        $this->input = $input;
        $this->application = $app;

        parent::__construct($state);
    }
}
