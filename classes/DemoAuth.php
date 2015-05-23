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
            if ($backendUser->login == Config::get('krisawzm.demomanager::admin.login', 'admin')) {
                $this->theme = Config::get('krisawzm.demomanager::base_theme', null);
            }
            else {
                $this->theme = $backendUser->login;
            }
        }
        else {
            $this->theme = $this->newDemoUser()->login;

            // @todo Remember the username after signing out.
            //       Could prove useful as some plugins may
            //       have some different offline views.
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
        $login = Str::quickRandom(Config::get('krisawzm.demomanager::username_length', 10));

        if (!$demoManager->copyTheme($login)) {
            return false;
        }

        $user = User::create([
            'email'                 => $login.'@'.$login.'.tld',
            'login'                 => $login,
            'password'              => $login,
            'password_confirmation' => $login,
            'first_name'            => ucfirst($login),
            'last_name'             => 'Demo',
            'permissions'           => $this->getPermissions(),
            'is_activated'          => true,
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
