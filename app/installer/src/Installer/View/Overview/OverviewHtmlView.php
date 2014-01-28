<?php

namespace Installer\View\Overview;

use Installer\App;
use Installer\View\DefaultHtmlView;

class OverviewHtmlView extends DefaultHtmlView
{
    public function __construct($app, $model, array $paths)
    {
        parent::__construct($app, $model, $paths);

        $this->form 		= $this->model->getForm('overview');
		$this->phpoptions  	= $this->model->getPhpOptions();
		$this->phpsettings 	= $this->model->getPhpSettings();
		$this->options     	= $this->model->getOptions();
    }
}
