<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend;

use Joomla\Application\AbstractWebApplication;
use Joomla\Controller\ControllerInterface;
use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;
use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Session\Session;
use Joomla\Registry\Registry;
use Joomla\Filesystem\Path;

//@todo fix that
class_alias('Joomla\Language\Text','JText');

use Frontend\Router\AppRouter;

/**
 * Application class
 *
 * @since  1.0
 */
final class App extends AbstractWebApplication implements ContainerAwareInterface
{
    /**
     * DI Container
     *
     * @var    Container
     * @since  1.0
     */
    protected $container;

    /**
     * The default theme.
     *
     * @var    string
     * @since  1.0
     */
    protected $theme = null;

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

        // Merge the config into the application
        $config = $container->get('config');
        $this->config->merge($config);

        $this->theme = $this->config->get('default.theme');

        define('BASE_URL', $this->get('uri.base.full'));
        define('DEFAULT_THEME', BASE_URL . 'themes/' . $this->theme);
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
            $router = new AppRouter($this->input, $this);

            $route_path = JPATH_ROOT . '/etc/routes.json';
            if (file_exists($route_path)) {
                $maps = json_decode(file_get_contents($route_path));
            } else {
                $maps = new \stdclass;
            }

            if (!$maps)
            {
                throw new \RuntimeException('Invalid router file.', 500);
            }

            $router->addMaps($maps, true);
            $router->setControllerPrefix('\\'.__NAMESPACE__);
            $router->setDefaultController('Component');

            // Fetch the controller
            /* @type ControllerInterface $controller */
            $component = $router->getController($this->get('uri.route'));
            ob_start();
            $component->execute();
            $content = ob_get_contents();
            ob_end_clean();

            // render template
            $path = JPATH_WEB.'/templates/'.$this->getTemplate();

            $template_path = Path::find(array($path), $this->input->getCmd('tmpl','index') . '.php');
            if (!$template_path) {
                $template_path = $path.'/index.php';
            }
            if (!is_file($template_path)) {
                throw new \Exception('Template not found');
            }

            ob_start();
            require_once $template_path;
            $template_content = ob_get_contents();
            ob_end_clean();

            $this->setBody(str_replace('{component}',$content,$template_content));
        }
        catch (\Exception $exception)
        {
            header('HTTP/1.1 500 Internal Server Error', true, 500);

            $content = $exception->getMessage();
            $this->setBody($content);
        }
    }

    /**
     * Get a session object.
     *
     * @return  Session
     *
     * @since   1.0
     */
    public function getSession()
    {
        if (is_null($this->session))
        {
            $this->loadSession();
        }

        return $this->session;
    }

    /**
     * Get the system message queue.
     *
     * @return  array  The system message queue.
     *
     * @since   1.0
     */
    public function getMessageQueue()
    {
        // For empty queue, if messages exists in the session, enqueue them.
        if (!count($this->messageQueue))
        {
            $session = $this->getSession();
            $sessionQueue = $session->get('application.queue');

            if (count($sessionQueue))
            {
                $this->messageQueue = $sessionQueue;
                $session->set('application.queue', null);
            }
        }

        return $this->messageQueue;
    }

    /**
     * Enqueue a system message.
     *
     * @param   string  $msg   The message to enqueue.
     * @param   string  $type  The message type. Default is message.
     *
     * @return  void
     *
     * @since   1.0
     */
    public function enqueueMessage($msg, $type = 'message')
    {
        // For empty queue, if messages exists in the session, enqueue them first.
        if (!count($this->messageQueue))
        {
            $session = $this->getSession();
            $sessionQueue = $session->get('application.queue');

            if (count($sessionQueue))
            {
                $this->messageQueue = $sessionQueue;
                $session->set('application.queue', null);
            }
        }

        // Enqueue the message.
        $this->messageQueue[] = array('message' => $msg, 'type' => strtolower($type));
    }

    /**
     * Allows the application to load a custom or default session.
     *
     * The logic and options for creating this object are adequately generic for default cases
     * but for many applications it will make sense to override this method and create a session,
     * if required, based on more specific needs.
     *
     * @param   JSession  $session  An optional session object. If omitted, the session is created.
     *
     * @return  App  This method is chainable.
     *
     * @since   1.0
     */
    public function loadSession(JSession $session = null)
    {
        // Generate a session name.
        $name = md5($this->get('secret') . $this->get('session_name', get_class($this)));

        // Calculate the session lifetime.
        $lifetime = (($this->get('lifetime')) ? $this->get('lifetime') * 60 : 900);

        // Get the session handler from the configuration.
        $handler = $this->get('session_handler', 'none');

        // Initialize the options for JSession.
        $options = array(
            'name' => $name,
            'expire' => $lifetime,
            'force_ssl' => $this->get('force_ssl')
        );

        // Instantiate the session object.
        $session = Session::getInstance($handler, $options);
        $session->initialise($this->input);

        if ($session->getState() == 'expired')
        {
            $session->restart();
        }
        else
        {
            $session->start();
        }

        if (!$session->get('registry') instanceof Registry)
        {
            // Registry has been corrupted somehow
            $session->set('registry', new Registry('session'));
        }

        // Set the session object.
        $this->session = $session;

        return $this;
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
     * language.
     *
     * @return  Language
     *
     * @since   1.0
     */
    public function getLanguage()
    {
        return Language::getInstance();
    }

    /**
     * Method to determine a hash for anti-spoofing variable names
     *
     * @param   boolean  $forceNew  If true, force a new token to be created
     *
     * @return  string  Hashed var name
     *
     * @since   1.0
     */
    public function getFormToken($forceNew = false)
    {
        // @todo we need the user id somehow here
        $userId  = 0;

        return md5($this->get('secret') . $userId . $this->getSession()->getToken($forceNew));
    }

    /**
     * Validated Token
     *
     * @since   1.0
     */
    public function checkToken($method = 'post')
    {
        $token = $this->getFormToken();
        $token_value = $this->input->$method->get($token, '', 'alnum');
        if (empty($token_value)) {
            throw new \Exception(Text::_('JINVALID_TOKEN'), 403);
        }
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

    /**
     * Method to get the template name. This is needed for compatability with JApplication.
     *
     * @return string The theme name.
     *
     * @since 12.1
     */
    public function getTemplate()
    {
        return $this->get('default.theme');
    }
}
