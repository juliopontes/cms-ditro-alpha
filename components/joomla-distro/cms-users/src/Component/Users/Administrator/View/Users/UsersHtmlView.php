<?php

namespace Component\Users\Administrator\View\Users;

use Joomla\Application\AbstractWebApplication;
use Joomla\Model\AbstractModel;
use Component\Users\Administrator\View\DefaultHtmlView;

class UsersHtmlView extends DefaultHtmlView
{
    public function __construct(AbstractWebApplication $app, AbstractModel $model, array $paths)
    {
        parent::__construct($app, $model, $paths);

        $this->users = $this->model->getAll();
    }
}
