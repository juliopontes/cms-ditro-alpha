<?php
namespace Cms\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Composer installer plugin
 *
 * @author  Julio Pontes <https://github.com/juliopontes>
 * @package Cms\Composer
 */
class Installer implements PluginInterface
{
    /**
     * Apply plugin modifications to composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new BaseInstaller($io, $composer);

        $composer->getInstallationManager()->addInstaller($installer);
    }
}