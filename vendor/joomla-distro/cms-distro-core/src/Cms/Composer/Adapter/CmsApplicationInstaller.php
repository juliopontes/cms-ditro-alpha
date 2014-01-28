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

    /**
     * Return string path
     * @return String
     */
    protected function getLocation(PackageInterface $package)
    {
        // Example vendor/cms-installation-application, cms-administrator-application, cms-frontend-application
        $parts = explode('/', $package->getName());
        $application = str_replace('-application','', $parts[1]);
        $application = str_replace('cms-','', $application);
        $this->vars['application'] = $application;

    	return parent::getLocation($package);
    }
}