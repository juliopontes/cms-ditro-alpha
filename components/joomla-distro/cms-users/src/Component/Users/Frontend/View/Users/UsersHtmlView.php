<?php

namespace Component\Users\Frontend\View\Users;

use Joomla\Application\AbstractWebApplication;
use Joomla\Model\AbstractModel;
use Component\Users\Frontend\View\DefaultHtmlView;

class UsersHtmlView extends DefaultHtmlView
{
    public function __construct(AbstractWebApplication $app, AbstractModel $model, array $paths)
    {
        parent::__construct($app, $model, $paths);

        $this->users = $this->model->getAll();
    }
}
