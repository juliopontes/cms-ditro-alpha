<?php
namespace Cms\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Installer\LibraryInstaller;

/**
 * Codebase installer class
 *
 * @author  Julio Pontes <https://github.com/juliopontes>
 * @package Cms\Composer
 */
class BaseInstaller extends LibraryInstaller
{
    /**
     * Array vars to template location
     */
    protected $vars         = array();

    /**
     * String with location path
     */
    protected $location     = null;

    /**
     * Composer Config
     */
    protected $_config      = null;

    /**
     * Custom Framework Installer
     */
    protected $framework    = null;

    /**
     * Cache Loaded Framework Installers
     */
    protected $_cache       = array();

    /**
     * {@inheritDoc}
     */
    public function __construct(IOInterface $io, Composer $composer, $type = 'library')
    {
        parent::__construct($io, $composer, $type);

        $this->_io = $io;
        $this->_composer = $composer;
        $this->_type = $type;
        $this->_config = $composer->getConfig();
    }

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        return $this->framework->getLocation($package);
    }

    /**
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);

        if (method_exists($this->framework, 'customInstall')) {
            $this->framework->customInstall($repo, $package);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        parent::update($repo, $initial, $target);

        if (method_exists($this->framework, 'customUpdate')) {
            $this->framework->customUpdate($package->getPrettyVersion($repo, $initial, $target));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        $frameworkType = $this->findFrameworkType($packageType);

        if ($frameworkType === false) {
            return false;
        }

        return true;
    }

    /**
     * Finds a supported framework type if it exists and returns it
     *
     * @param string $type
     * @return string
     */
    protected function findFrameworkType($type)
    {
        $frameworkType = false;

        if (strpos($type,'-') === false) {
            return $frameworkType;
        }

        if (!empty($this->_cache) && array_key_exists($type, $this->_cache)) {
            return $this->_cache[$type];
        }
        
        $parts = explode('-',$type);
        $baseName = implode('',array_map('ucfirst', $parts));
        $file = $baseName . 'Installer';
        // convert type-name into TypeNameInstaller.php for a specific composer installer
        $file_path = __DIR__.'/adapter/'.$file.'.php';

        if (!file_exists($file_path)) {
            // try to find a type installer
            $file = ucfirst($parts[0]) . 'Installer';
            // convert type into TypeInstaller.php for a specific composer installer
            $file_path = __DIR__.'/adapter/'.$file.'.php';

            if (!file_exists($file_path)) {
                return $frameworkType;
            }
        }

        $frameworkType = $file;
        $this->_cache[$type] = $frameworkType;

        //initialise framework installer
        $frameworkClass = 'Cms\\Composer\\Adapter\\' . $frameworkType;
        $this->framework = new $frameworkClass($this->_io, $this->_composer, $this->_type);

        return $frameworkType;
    }

    /**
     * Return string path
     * @return String
     */
    protected function getLocation(PackageInterface $package)
    {
        $parts = explode('/', $package->getName());
        $this->vars['vendor'] = $parts[0];
        $this->vars['package'] = $parts[1];
        $location = $this->location;
        foreach ($this->vars as $find => $replace) {
            $location = str_replace('{'.$find.'}',$replace,$location);
        }
        
        return $location;
    }

    public function __destruct()
    {
        if(!empty($this->framework)) {
            unset($this->framework);
        }
    }
}