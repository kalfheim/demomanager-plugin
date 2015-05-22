<?php namespace Krisawzm\DemoManager\Classes;

use October\Rain\Support\Traits\Singleton;
use BackendAuth;
use Backend\Models\User;
use Illuminate\Support\Str;
use Config;

class DemoAuth
{
    use Singleton;

    /**
     * Active theme.
     *
     * @var string
     */
    public $theme;

    /**
     * Initialize.
     *
     * @return void
     */
    protected function init()
    {
        $backendUser = BackendAuth::getUser();

        if ($backendUser) {
            if ($backendUser->login == 'admin') {
                $this->theme = Config::get('krisawzm.demomanager::base_theme', 'demo');
            }
            else {
                $this->theme = $backendUser->login;
            }
        }
        else {
            $this->theme = $this->newDemoUser()->login;
        }
    }

    /**
     * Set up a new demo user and log in.
     *
     * @return BackendAuth
     */
    protected function newDemoUser()
    {
        $demoManager = DemoManager::instance();
        $username = Str::quickRandom(10);

        if (!$demoManager->copyTheme($username)) {
            return false;
        }

        $user = User::create([
            'email'                 => $username.'@'.$username.'.tld',
            'login'                 => $username,
            'password'              => $username,
            'password_confirmation' => $username,
            'first_name'            => ucfirst($username),
            'last_name'             => 'Demo',
            'permissions'           => $this->getPermissions(),
            'is_activated'          => true
        ]);

        BackendAuth::login($user);

        return $user;
    }

    /**
     * Load permissions for the demo users.
     *
     * @return array
     */
    protected function getPermissions()
    {
        static $loadedPermissions = null;

        if (is_null($loadedPermissions)) {
            $loadedPermissions = [];
            $permissions = Config::get('krisawzm.demomanager::permissions', []);

            foreach ($permissions as $permission) {
                $loadedPermissions[$permission] = '1';
            }
        }

        return $loadedPermissions;
    }
}
