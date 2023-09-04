<?php namespace MoonWalkerz\Trustpilot\Models;

use Model;
use Cms\Classes\Page;
use Cms\Classes\Theme;

class Settings extends Model
{
    public $implement = [
        'System.Behaviors.SettingsModel',
        '@RainLab.Translate.Behaviors.TranslatableModel'
                        ];

    // A unique code
    public $settingsCode = 'moonwalkerz_press_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
    
    public function getPagesByComponent($component)
    {
        $theme = Theme::getActiveTheme();
        $pages = Page::listInTheme($theme, true);
        
        $cmsPages = [];
        
        foreach ($pages as $page) {
            if (!$page->hasComponent($component)) {
                continue;
            }
            $cmsPages[$page->baseFileName] = $page->title;
        }

        return count($cmsPages) < 1
            ? $this->allPages()
            : $cmsPages;
    }
    protected function allPages()
    {
        return Page
            ::listInTheme( Theme::getActiveTheme(), true)
            ->mapWithKeys(function($page) {
                return [$page->baseFileName => $page->title];
            })
            ->toArray();
    }
    public function getReleasePageOptions()
    {
        return $this->getPagesByComponent('release');
    }

}