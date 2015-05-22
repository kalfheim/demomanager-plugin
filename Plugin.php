<?php namespace Krisawzm\DemoManager;

use System\Classes\PluginBase;
use Krisawzm\DemoManager\Classes\DemoManager;
use Krisawzm\DemoManager\Classes\DemoAuth;
use Event;
use Route;
use Config;

/**
 * DemoManager Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * {@inheritdoc}
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'DemoManager',
            'description' => 'Demo Manager',
            'author'      => 'Kristoffer Alfheim',
            'icon'        => 'icon-refresh',
            'homepage'    => 'https://github.com/krisawzm/demomanager-plugin',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $demoManager = DemoManager::instance();

        // Prevent users from accessing the site while resetting.
        // Override all other routes with a custom lock page.
        if ($demoManager->locked()) {
            Route::any('', function() use ($demoManager) {
                return $demoManager->renderLockPage();
            });
        }

        // Set active theme to correspond with username.
        // Changes will only be visible to this user.
        Event::listen('cms.activeTheme', function() {
            return DemoAuth::instance()->theme;
        });

        Route::any('/cfg', function() {
            dd(Config::get('krisawzm.demomanager::admin.password', 'admin'));
        });
    }
}
