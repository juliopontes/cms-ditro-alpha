<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Component\Administrator\Frontend;

use Joomla\Application\AbstractWebApplication;
use Joomla\Controller\ControllerInterface;
use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;

use Component\Administrator\Frontend\Router\ComponentRouter;

/**
 * Application class
 *
 * @since  1.0
 */
final class Dispatcher extends AbstractWebApplication implements ContainerAwareInterface
{
    /**
     * DI Container
     *
     * @var    Container
     * @since  1.0
     */
    protected $container;

    /**
     * Class constructor.
     *
     * @since   1.0
     */
    public function __construct(Container $container)
    {
        // Run the parent constructor
        parent::__construct();

        $this->setContainer($container);
    }

    /**
     * Method to run the Web application routines.
     *
     * @return  void
     *
     * @since   1.0
     */
    protected function doExecute()
    {
        try
        {
            // Instantiate the router
            $router = new ComponentRouter($this->input, $this);

            $root_path = dirname(dirname(dirname(dirname(__DIR__))));

            $routes_path = $root_path . '/routes/frontend.json';
            if (!file_exists($routes_path)) {
                $routes = '{}';
            } else {
                $routes = file_get_contents($routes_path);
            }
            $maps = json_decode($routes);

            if (!$maps)
            {
                throw new \RuntimeException('Invalid router file.', 500);
            }

            $router->addMaps($maps, true);
            $router->setControllerPrefix('\\Component');
            $router->setDefaultController('\\Administrator\\Dispatcher');
            // Fetch the controller
            $component = $router->getController($this->get('uri.route'));
            $component->execute();
        }
        catch (\Exception $exception)
        {
            header('HTTP/1.1 500 Internal Server Error', true, 500);

            $content = $exception->getMessage();
            echo $content;
        }
    }

    /**
     * Get the DI container.
     *
     * @return  Container
     *
     * @since   1.0
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set the DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  $this  Method allows chaining
     *
     * @since   1.0
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }
}
