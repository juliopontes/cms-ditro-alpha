<?php

namespace Component\Users\Frontend\View;

use Joomla\Application\AbstractWebApplication;
use Joomla\Model\AbstractModel;
use Joomla\View\AbstractHtmlView;

class DefaultHtmlView extends AbstractHtmlView
{
    public function __construct(AbstractWebApplication $app, AbstractModel $model, array $paths)
    {
        $this->application = $app;

        $q = new \SplPriorityQueue();
        if (!empty($paths)) {
            foreach ($paths as $path) {
                $q->insert($path);
            }
        }

        $root_dir = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

        $q->insert(JPATH_WEB.'/template/'. $this->application->getContainer()->get('config')->get('default.theme') .'/html/frontend/com_users', 1);
        $q->insert(JPATH_WEB.'/template/'. $this->application->getContainer()->get('config')->get('default.theme') .'/html/com_users', 2);
        $q->insert($root_dir .'/layouts/frontend/', 3);

        parent::__construct($model, $q);
    }
}
