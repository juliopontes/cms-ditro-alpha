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
        // add adapter installer
        foreach (glob(__DIR__.'/Adapter/*.php') as $installerAdapter) {
            $class_name = __NAMESPACE__ . '\\Adapter\\' . basename($installerAdapter,'.php');
            $installer = new $class_name($io, $composer);
            $composer->getInstallationManager()->addInstaller($installer);
        }
    }
}