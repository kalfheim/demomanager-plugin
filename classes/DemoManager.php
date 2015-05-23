<?php namespace Krisawzm\DemoManager\Classes;

use October\Rain\Support\Traits\Singleton;
use Artisan;
use System\Classes\UpdateManager;
use File;
use Config;
use BackendAuth;

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

        // Lock site.
        if (!$this->locked(true)) {
            throw new DemoManagerException('Error making lockfile.');
        }

        // Clear cache.
        Artisan::call('cache:clear');

        // Set up an UpdateManager.
        $updateManager = UpdateManager::instance();

        // Uninstall database.
        $updateManager->uninstall();

        // Remove old themes.
        if (!$this->removeOldThemes()) {
            throw new DemoManagerException('Error removing old themes.');
        }

        // Update database.
        $updateManager->update();

        // Update the admin user.
        if (!$this->updateAdminUser()) {
            throw new DemoManagerException('Error updating admin user.');
        }

        // Run any custom provisioners.
        if (!$this->runProvisioners()) {
            throw new DemoManagerException('Error running provisioners.');
        }

        // Unlock site.
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
            return File::put($file, '') === 0;
        }
        elseif ($set === false) {
            return File::delete($file);
        }
        else {
            return File::exists($file);
        }
    }

    /**
     * Render the lock page.
     *
     * @return strning
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
     * Create a copy of the original theme.
     *
     * @param string $destination
     * @return bool
     */
    public function copyTheme($destination)
    {
        return File::copyDirectory(
            themes_path(Config::get('krisawzm.demomanager::base_theme', null)),
            themes_path($destination)
        );
    }

    /**
     * Update the admin user.
     *
     * @return bool
     */
    protected function updateAdminUser()
    {
        $user = BackendAuth::findUserById(1);

        if (!$user) {
            return false;
        }

        $user->login = Config::get('krisawzm.demomanager::admin.login', 'admin');

        $password = Config::get('krisawzm.demomanager::admin.password', 'admin');
        $user->password = $password;
        $user->password_confirmation = $password;

        return $user->save();
    }

    /**
     * Remove old theme directories.
     *
     * @return bool
     * @throws \Krisawzm\DemoManager\Classes\DemoManagerException
     */
    protected function removeOldThemes()
    {
        $baseTheme = Config::get('krisawzm.demomanager::base_theme', null);

        if (!$baseTheme) {
            // Prevents the base theme from accidentally being deleted.
            throw new DemoManagerException('A base theme is not specified.');
        }

        $themesPath = themes_path();
        $baseThemePath = themes_path($baseTheme);

        foreach (File::directories($themesPath) as $themePath) {
            // Do not remove the base theme.
            if ($themePath == $baseThemePath) {
                continue;
            }

            if (!File::deleteDirectory($themePath)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Run provisioners specified in the config.
     *
     * @return bool
     * @throws \Krisawzm\DemoManager\Classes\DemoManagerException
     */
    public function runProvisioners()
    {
        $provisioners = Config::get('krisawzm.demomanager::provisioners', []);

        foreach ($provisioners as $className) {
            $provisioner = new $className;

            if (!$provisioner instanceof DemoProvisionerInterface) {
                throw new DemoManagerException(
                    sprintf('%s must implement \Krisawzm\DemoManager\Classes\DemoProvisionerInterface.', $className)
                );
            }

            if (!$provisioner->run()) {
                return false;
            }
        }

        return true;
    }
}
