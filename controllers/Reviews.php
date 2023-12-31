<?php namespace MoonWalkerz\Trustpilot\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Artisan;
class Reviews extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('MoonWalkerz.Trustpilot', 'main-menu-item');
    }

    public function onSync()
    {
        Artisan::call('trustpilot:sync');
        return $this->listRefresh();
    }


}
