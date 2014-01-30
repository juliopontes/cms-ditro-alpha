<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Component\Users\Administrator\Controller;

use Joomla\Controller\AbstractController;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\Container;
use Joomla\Language\Language;
use Component\Users\Administrator\View\DefaultHtmlView;

/**
 * Default Controller class for the application
 *
 * @since  1.0
 */
class DefaultController extends AbstractController implements ContainerAwareInterface
{
    /**
     * DI Container
     *
     * @var    Container
     * @since  1.0
     */
    private $container;

    /**
     * The default view for the app
     *
     * @var    string
     * @since  1.0
     */
    protected $defaultView = 'users';

    /**
     * Execute the controller.
     *
     * This is a generic method to execute and render a view and is not suitable for tasks.
     *
     * @return  void
     *
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function execute()
    {
        // Get the input
        $input = $this->getInput();

        $task = $input->get('task','view');

        // Get some data from the request
        $vName   = $input->getWord('view', $this->defaultView);
        $vFormat = $input->getWord('format', 'html');

        if (is_null($input->get('layout')))
        {
            if ($task == 'view' && $input->get('id') == null)
            {
                $input->set('layout', 'index');
            }
            elseif ($task == 'view')
            {
                $input->set('layout', 'view');
            }
            elseif (method_exists($this, $task))
            {
                $this->$task();
            }
        }

        $lName = $input->get('layout', 'index');

        $input->set('view', $vName);

        $base = '\\Component\\Users\\Administrator';

        $vClass = $base . '\\View\\' . ucfirst($vName) . '\\' . ucfirst($vName) . ucfirst($vFormat) . 'View';
        $mClass = $base . '\\Model\\' . ucfirst($vName) . 'Model';

        // If a model doesn't exist for our view, revert to the default model
        if (!class_exists($mClass))
        {
            $mClass = $base . '\\Model\\DefaultModel';

            // If there still isn't a class, panic.
            if (!class_exists($mClass))
            {
                throw new \RuntimeException(sprintf('No model found for view %s', $vName));
            }
        }

        // Make sure the view class exists, otherwise revert to the default
        if (!class_exists($vClass))
        {
            $vClass = $base . '\\View\\Default' . ucfirst($vFormat) . 'View';
            if (!class_exists($vClass)) {
                $vClass = $base . '\\View\\DefaultHtmlView';

                // If there still isn't a class, panic.
                if (!class_exists($vClass))
                {
                    throw new \RuntimeException(sprintf('Class %s not found', $vClass));
                }
            }
        }

        // Register the templates paths for the view
        $paths = array();

        $path = JPATH_WEB.'/template/' . $vName;

        if (is_dir($path))
        {
            $paths[] = $path;
        }

        /* @type DefaultHtmlView $view */
        $view = new $vClass($this->getApplication(),new $mClass($this->getApplication(),$this->getInput()), $paths);
        $view->setLayout($vName . '/' . $lName);

        try
        {
            // Render our view.
            echo $view->render();
        }
        catch (\Exception $e)
        {
            throw new \RuntimeException(sprintf('Error: ' . $e->getMessage()));
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
