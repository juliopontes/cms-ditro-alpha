<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Installer\Router;

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
class AppRouter extends Router
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
     * get a Controller by route/task
     *
     * @since   1.0
     */
    public function getController($route)
    {
        // Get the controller name based on the route patterns and requested route.
        $name = $this->parseRoute($route);

        // controller.task
        $task = $this->app->input->getCmd('task');
        if (strpos($task,'.') && $name == '\Controller\DefaultController') {
            $parts = explode('.',$task);
            $controller = $parts[0];
            $controllerTask = sprintf('\\Controller\\%sController', $controller);
            $class_name = $this->controllerPrefix.$controllerTask;
            if (class_exists($this->controllerPrefix.$controllerTask)) {
                $name = $controllerTask;
                // set task value
                $this->app->input->set('task', $parts[1]);
            }
        }

        // Get the controller object by name.
        return $this->fetchController($name);
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
        $class = $this->controllerPrefix . ucfirst($name);

        // If the controller class does not exist panic.
        if (!class_exists($class) || !is_subclass_of($class, 'Joomla\\Controller\\ControllerInterface'))
        {
            throw new \RuntimeException(sprintf('Unable to locate controller `%s`.', $class), 404);
        }

        // Instantiate the controller.
        $controller = new $class($this->input, $this->app);

        return $controller;
    }
}
