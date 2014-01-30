<?php
namespace Cms\Composer\Adapter;

use Cms\Composer\BaseInstaller;
use Composer\Package\PackageInterface;

/**
 * Cms Media Installer class
 *
 * @author  Julio Pontes <https://github.com/juliopontes>
 * @package Cms\Composer\Adapter
 */
class CmsMediaInstaller extends BaseInstaller
{
    protected $location = 'www/media/{vendor}/{package}';
    protected $support = 'cms-media';
}