<?php

namespace Installer\View\Install;

use Installer\App;
use Installer\View\DefaultHtmlView;

class InstallHtmlView extends DefaultHtmlView
{
    public function __construct($app, $model, array $paths)
    {
        parent::__construct($app, $model, $paths);

        $this->options = $this->model->getOptions();

		/*
		 * Prepare the tasks array
		 * Note: The first character of the task *MUST* be capitalised or the application will not find the task
		 */
		$this->tasks[] = ($this->options['db_old'] == 'remove') ? 'database.remove' : 'database.backup';
		$this->tasks[] = 'database.install';

		if (isset($this->options['sample_file']))
		{
			$this->tasks[] = 'database.installsampledata';
		}

		$this->tasks[] = 'configuration.create';

		if ($this->options['summary_email'])
		{
			$this->tasks[] = 'Email';
		}
    }
}
