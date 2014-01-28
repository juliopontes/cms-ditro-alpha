<?php

namespace Installer\View\Preinstall;

use Installer\App;
use Installer\View\DefaultHtmlView;

class PreinstallHtmlView extends DefaultHtmlView
{
    public function __construct($app, $model, array $paths)
    {
        parent::__construct($app, $model, $paths);

        $this->form = $this->model->getForm('preinstall');
		$this->options = $this->model->getPhpOptions();
		$this->settings = $this->model->getPhpSettings();
    }
}
