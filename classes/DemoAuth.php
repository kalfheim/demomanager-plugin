<?php namespace Krisawzm\DemoManager\Classes;

use October\Rain\Support\Traits\Singleton;
use BackendAuth;
use Backend\Models\User;
use Illuminate\Support\Str;
use Cms\Classes\Page;
use Cms\Classes\Theme;
use Config;
use Cache;
use Event;

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
     * @throws \Krisawzm\DemoManager\Classes\DemoManagerException
     */
    protected function init()
    {
        $backendUser = BackendAuth::getUser();
        $baseTheme = $this->theme = Config::get('krisawzm.demomanager::base_theme', null);

        if ($backendUser) {
            if ($backendUser->login == Config::get('krisawzm.demomanager::admin.login', 'admin')) {
                $this->theme = $baseTheme;
            }
            else {
                $this->theme = $backendUser->login;
            }
        }
        else {
            if (UserCounter::instance()->limit()) {
                $action = Config::get('krisawzm.demomanager::limit_action', 'reset');

                if ($action == 'reset') {
                    DemoManager::instance()->resetEverything();
                    // @todo queue/async?

                    $this->theme = $this->newDemoUser()->login;
                }
                elseif ($action == 'maintenance') {
                    $theme = Theme::load($baseTheme);

                    Event::listen('cms.page.beforeDisplay', function($controller, $url, $page) use ($theme) {
                        return Page::loadCached($theme, 'maintenance');
                    });
                }
                elseif ($action == 'nothing') {
                    $this->theme = $baseTheme;
                }
                else {
                    throw new DemoManagerException('User limit is reached, but an invalid action is defined.');
                }
            }
            else {
                $this->theme = $this->newDemoUser()->login;

                // @todo Remember the username after signing out.
                //       Could prove useful as some plugins may
                //       have some different offline views.
            }
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

        UserCounter::instance()->inc();

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
