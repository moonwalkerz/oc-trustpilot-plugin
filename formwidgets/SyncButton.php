<?php namespace MoonWalkerz\Trustpilot\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Artisan;
/**
 * SyncButton Form Widget
 *
 * @link https://docs.octobercms.com/3.x/extend/forms/form-widgets.html
 */
class SyncButton extends FormWidgetBase
{
    protected $defaultAlias = 'trustpilot_sync_button';

    public function init()
    {
    }

    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('syncbutton');
    }

    public function prepareVars()
    {
                              
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    public function loadAssets()
    {

    }

    public function getSaveValue($value)
    {
        return $value;
    }

    public function onSync()
    {
        Artisan::call('trustpilot:sync');
    }
}
