<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Component\Administrator\Frontend\Router;

use Joomla\Application\AbstractApplication;
use Joomla\Controller\ControllerInterface;
use Joomla\DI\ContainerAwareInterface;
use Joomla\Input\Input;
use Joomla\Router\Router;

/**
 * Sample Router
 *
 * @since  1.0
 */
class ComponentRouter extends Router
{
    /**
     * Application object to inject into controllers
     *
     * @var    AbstractApplication
     * @since  1.0
     */
    protected $app;

    /**
     * Constructor.
     *
     * @param   Input                $input  An optional input object from which to derive the route.  If none
     *                                       is given than the input from the application object will be used.
     * @param   AbstractApplication  $app    An optional application object to inject to controllers
     *
     * @since   1.0
     */
    public function __construct(Input $input = null, AbstractApplication $app = null)
    {
        parent::__construct($app->input);

        $this->app = $app;
    }

    /**
     * Fetch a ControllerInterface object for a given name.
     *
     * @param   string  $name  The controller name (excluding prefix) for which to fetch and instance.
     *
     * @return  ControllerInterface
     *
     * @since   1.0
     * @throws  \RuntimeException
     */
    protected function fetchController($name)
    {
        // Derive the controller class name.
        $class = $this->controllerPrefix . '\\' . $this->input->getCmd('component', 'Users') . ucfirst($name);

        // Instantiate the controller.
        $controller = new $class($this->app->getContainer());

        return $controller;
    }
}
