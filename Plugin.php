<?php namespace Krisawzm\DemoManager;

use System\Classes\PluginBase;
use Krisawzm\DemoManager\Classes\DemoManager;
use Krisawzm\DemoManager\Classes\DemoAuth;
use Event;
use Route;
use Config;
use October\Rain\Exception\ApplicationException;

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
            'description' => 'Helps you set up an interactive demo of your new plugin in 1-2-3.',
            'author'      => 'Kristoffer Alfheim',
            'icon'        => 'icon-refresh',
            'homepage'    => 'https://github.com/krisawzm/demomanager-plugin',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerConsoleCommand(
            'demomanager:reset',
            '\Krisawzm\DemoManager\Console\DemoManagerResetCommand'
        );
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
    }

    /**
     * {@inheritdoc}
     *
     * @throws \October\Rain\Exception\ApplicationException
     */
    public function registerSchedule($schedule)
    {
        $interval = Config::get('krisawzm.demomanager::reset_interval', false);

        if (!$interval) {
            return;
        }

        if (is_string($interval)) {
            if (method_exists($schedule, $interval)) {
                $schedule->$interval();
            }
            else {
                $parts = preg_split('/\s/', $interval, null, PREG_SPLIT_NO_EMPTY);

                if (count($parts) == 5) {
                    $schedule->cron($interval);
                }
            }
        }

        throw new ApplicationException(
            $interval.' is an invalid schedule interval or CRON expression.'
        );
    }
}
