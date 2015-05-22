<?php namespace Krisawzm\DemoManager\Classes;

use October\Rain\Support\Traits\Singleton;
use Artisan;
use System\Classes\UpdateManager;
use File;
use Krisawzm\DemoManager\Classes\DemoManagerException;

class DemoManager
{
    use Singleton;

    /**
     * Initiate the reset process.
     *
     * If an exception is thrown, the site will remain
     * locked until the lockfile is manually deleted.
     *
     * @return void
     * @throws \Krisawzm\DemoManager\Classes\DemoManagerException
     */
    public function resetEverything()
    {
        if ($this->locked()) {
            throw new DemoManagerException('Lock file exists. You may have to remove it.');
        }

        // Lock site
        $this->locked(true);

        // Clear cache
        Artisan::call('cache:clear');

        // Set up an UpdateManager
        $updateManager = UpdateManager::instance();

        // Delete database
        $updateManager->uninstall();

        // Update database
        $updateManager->update();

        // Run any custom provisioners
        $this->runProvisioners();

        // Unlock site
        $this->locked(false);
    }

    /**
     * Check if the lockfile exists or set the lock state.
     *
     * @param bool|null $set
     * @return bool
     */
    public function locked($set = null)
    {
        $file = base_path('.krisawzm-demomanager-lock');

        if ($set === true) {
            File::put($file, '');
        }
        elseif ($set === false) {
            File::delete($file);
        }
        else {
            return File::exists($file);
        }
    }

    /**
     * Create a copy of the original theme.
     *
     * @param string $destination
     * @return bool
     */
    public function copyTheme($destination)
    {
        return File::copyDirectory(
            themes_path(Config::get('krisawzm.demomanager::base_theme', 'demo')),
            themes_path($destination)
        );
    }

    /**
     * Render the lock page.
     *
     * @return strning
     * @todo Custom message
     */
    public function renderLockPage()
    {
        return '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>Resetting...</title>
            </head>
            <body>
                '.Config::get('krisawzm.demomanager::lock_page', 'Resetting...').'
            </body
            </html>';
    }

    /**
     * Delete old theme directories.
     *
     * @return bool
     */
    public function removeOldThemes()
    {
        $themesPath = themes_path();
        $baseThemePath = themes_path(Config::get('krisawzm.demomanager::base_theme', 'demo'));

        foreach (File::directories($themesPath) as $themePath) {
            // Do not remove the original theme.
            if ($themePath == $baseThemePath) {
                continue;
            }

            if (!File::deleteDirectory($themePath)) {
                return false;
            }
        }

        return true;
    }
}
