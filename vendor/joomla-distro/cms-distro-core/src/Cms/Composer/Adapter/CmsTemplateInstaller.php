<?php
namespace Cms\Composer\Adapter;

use Cms\Composer\BaseInstaller;
use Composer\Package\PackageInterface;

/**
 * Cms Template Installer class
 *
 * @author  Julio Pontes <https://github.com/juliopontes>
 * @package Cms\Composer\Adapter
 */
class CmsTemplateInstaller extends BaseInstaller
{
    protected $location = 'www/templates/{template}';
    protected $support = 'cms-template';

    /**
     * Return string path
     * @return String
     */
    protected function getLocation(PackageInterface $package)
    {
        // Example vendor/cms-isis-template
        $parts = explode('/', $package->getName());
        $vars = explode('-', $parts[1]);
        $this->vars['template'] = trim($vars[1]);

    	return parent::getLocation($package);
    }
}