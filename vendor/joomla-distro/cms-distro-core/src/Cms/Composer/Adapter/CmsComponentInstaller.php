<?php
namespace Cms\Composer\Adapter;

use Cms\Composer\BaseInstaller;
use Composer\Package\PackageInterface;

/**
 * Cms Component Installer class
 *
 * @author  Julio Pontes <https://github.com/juliopontes>
 * @package Cms\Composer\Adapter
 */
class CmsComponentInstaller extends BaseInstaller
{
    protected $location = 'components/{vendor}/{package}';
}