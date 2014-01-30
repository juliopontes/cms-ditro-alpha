<?php
namespace Cms\Composer\Adapter;

use Cms\Composer\BaseInstaller;
use Composer\Package\PackageInterface;

/**
 * Cms Application Installer class
 *
 * @author  Julio Pontes <https://github.com/juliopontes>
 * @package Cms\Composer\Adapter
 */
class CmsApplicationInstaller extends BaseInstaller
{
    protected $location = 'app/{application}';
    protected $support = 'cms-application';

    /**
     * Return string path
     * @return String
     */
    protected function getLocation(PackageInterface $package)
    {
        // Example vendor/cms-installation-application, cms-administrator-application, cms-frontend-application
        $parts = explode('/', $package->getName());
        $vars = explode('-', $parts[1]);
        $this->vars['application'] = trim($vars[1]);

    	return parent::getLocation($package);
    }
}