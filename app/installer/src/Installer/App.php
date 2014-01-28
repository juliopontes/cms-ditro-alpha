<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Installer;

use Installer\Service\ApplicationServiceProvider;
use Joomla\Application\AbstractWebApplication;
use Joomla\Controller\ControllerInterface;
use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;
use Joomla\Language\Language;
use Joomla\Language\Text;
use Joomla\Filesystem\Path;
use Joomla\Filesystem\Folder;

//@todo fix that
class_alias('Joomla\Language\Text','JText');

use Installer\Router\AppRouter;
use Installer\Response\ResponseJson;

use Joomla\Session\Session;
use Joomla\Registry\Registry;

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
     * A session object.
     *
     * @var    Session
     * @since  1.0
     * @note   This has been created to avoid a conflict with the $session member var from the parent class.
     */
    protected $session = null;

    /**
     * The User object.
     *
     * @var     User
     * @since   1.0
     */
    private $user = null;

    /**
     * The application message queue.
     *
     * @var    array
     * @since  1.0
     */
    protected $messageQueue = array();

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

        $this->theme = $this->config->get('theme.default');

        define('BASE_URL', $this->get('uri.base.full'));
        define('DEFAULT_THEME', BASE_URL . 'themes/' . $this->theme);

        $this->getSession();
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

            $routes_path = JPATH_ROOT . '/etc/routes.json';
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
            $router->setControllerPrefix('\\Installer');
            $router->setDefaultController('\\Controller\\DefaultController');

            $default_language = $this->getSession()->get('default.language', $this->getLanguage()->getDefault());
            $this->getLanguage()->load('', JPATH_ROOT, $default_language);
            $this->getLanguage()->setLanguage($default_language);

            // Fetch the controller
            /* @type ControllerInterface $controller */
            $controller = $router->getController($this->get('uri.route'));
            $content = $controller->execute();

            // render template
            $tmpl = $this->input->getCmd('tmpl', 'index');

            if ($template_path = Path::find(array(JPATH_ROOT.'/template'), $tmpl.'.php')) {
            } else {
                $template_path = JPATH_ROOT.'/template/index.php';
            }
            
            if (!is_file($template_path)) {
                throw new \Exception('Template not found');
            }

            ob_start();
            require_once $template_path;
            $template = ob_get_clean();

            $this->setBody(str_replace('{component}',$content,$template));
        }
        catch (\Exception $exception)
        {
            header('HTTP/1.1 500 Internal Server Error', true, 500);

            $content = $exception->getMessage();
            $this->setBody($content);
        }
    }

    /**
     * Method to send a JSON response. The data parameter
     * can be a Exception object for when an error has occurred or
     * a stdClass for a good response.
     *
     * @param   mixed  $response  stdClass on success, Exception on failure.
     *
     * @return  void
     *
     * @since   3.1
     */
    public function sendJsonResponse($response)
    {
        // Check if we need to send an error code.
        if ($response instanceof \Exception)
        {
            // Send the appropriate error code response.
            $this->setHeader('status', $response->getCode());
            $this->setHeader('Content-Type', 'application/json; charset=utf-8');
            $this->sendHeaders();
        }

        // Send the JSON response.
        echo json_encode(new ResponseJson($this, $response));

        // Close the application.
        $this->close();
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
     * Get a Language object
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
     * Method to get the template name. This is needed for compatability with JApplication.
     *
     * @return string The theme name.
     *
     * @since 1.0
     */
    public function getTemplate()
    {
        return $this->get('theme.default');
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

            $this->sendJsonResponse(new \Exception(Text::_('JINVALID_TOKEN'), 403));
        }
    }

    /**
     * Returns the installed language files in the administrative and
     * front-end area.
     *
     * @param   mixed  $db  JDatabaseDriver instance
     *
     * @return  array  Array with installed language packs in admin and site area
     *
     * @since   3.1
     */
    public function getLocaliseAdmin($db = false)
    {
        // Read the files in the admin area
        if (!defined('JPATH_APP_ADMINISTRATOR')) {
            $langfiles['admin'] = array();
        } else {
            $path = Language::getLanguagePath(JPATH_APP_ADMINISTRATOR);
            $langfiles['admin'] = Folder::folders($path);
            if (is_bool($langfiles['admin'])) {
                $langfiles['admin'] = array();
            }
        }
        
        // Read the files in the site area
        if (!defined('JPATH_APP_FRONTEND')) {
            $langfiles['site'] = array();
        } else {
            $path = Language::getLanguagePath(JPATH_APP_FRONTEND);
            $langfiles['site'] = Folder::folders($path);
            if (is_bool($langfiles['site'])) {
                $langfiles['site'] = array();
            }
        }

        if ($db)
        {
            $langfiles_disk = $langfiles;
            $langfiles = array();
            $langfiles['admin'] = array();
            $langfiles['site'] = array();
            $query = $db->getQuery(true)
                ->select($db->quoteName(array('element','client_id')))
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('type') . ' = ' . $db->quote('language'));
            $db->setQuery($query);
            $langs = $db->loadObjectList();

            foreach ($langs as $lang)
            {
                switch ($lang->client_id)
                {
                    // Site
                    case 0:
                        if (in_array($lang->element, $langfiles_disk['site']))
                        {
                            $langfiles['site'][] = $lang->element;
                        }

                        break;

                    // Administrator
                    case 1:
                        if (in_array($lang->element, $langfiles_disk['admin']))
                        {
                            $langfiles['admin'][] = $lang->element;
                        }

                        break;
                }
            }
        }

        return $langfiles;
    }
}
