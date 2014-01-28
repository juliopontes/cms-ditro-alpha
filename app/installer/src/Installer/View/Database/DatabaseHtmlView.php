<?php

namespace Installer\View\Database;

use Installer\App;
use Installer\View\DefaultHtmlView;

class DatabaseHtmlView extends DefaultHtmlView
{
    public function __construct($app, $model, array $paths)
    {
        parent::__construct($app, $model, $paths);

        $this->form = $this->model->getForm('database');
    }
}
