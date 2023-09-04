<?php namespace MoonWalkerz\Trustpilot;

use Backend;
use System\Classes\PluginBase;
use MoonWalkerz\Trustpilot\Models\Settings;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Trustpilot',
            'description' => 'Show Trustpilot reviews',
            'author' => 'moonwalkerz',
            'icon' => 'icon-star-o'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        $this->registerConsoleCommand('trustpilot.sync', \MoonWalkerz\Trustpilot\Console\Sync::class);
    }

    /**
     * registerSchedule used by the backend.
     */

    public function registerSchedule($schedule)
    {
        switch (Settings::get('','')) {

        case 0:
        break;
        case 1:
        $schedule->call(function () {
            Artisan::call('trustpilot:sync');
        })->monthly();
        break;

        case 2:
        $schedule->call(function () {
            Artisan::call('trustpilot:sync');
        })->weekly();
        break;

        case 3:   
        $schedule->call(function () {
            Artisan::call('trustpilot:sync');
        })->daily();
        break;
        }
    }
    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        //
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            'MoonWalkerz\Trustpilot\Components\Reviews' => 'reviews',
        ];
    }

     /**
     * Registers any back-end settings.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'moonwalkerz.trustpilot::lang.plugin.name',
                'description' => 'moonwalkerz.trustpilot::lang.plugin.manage_settings',
                'category'    => 'system::lang.system.categories.cms',
                'icon'        => 'icon-star-half-o',
                'class'       => 'MoonWalkerz\Trustpilot\Models\Settings',
                'order'       => 500,
                'keywords'    => 'search',
                'permissions' => ['moonwalkerz.trustpilot.manage_settings']
            ],
        ];
    }
}
