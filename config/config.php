<?php

/*
|
| DO NOT MODIFY THIS FILE!
|
| Instead, create a new file in:
| /config/krisawzm/demomanager/config.php
|
| The new config will be merged with this config.
|
| Example:
|
| <code>
| <?php
|
| return [
|     'base_theme' => 'some-theme'
| ];
| </code>
|
| The example above will change the `base_theme` while
| using the default values for everything else.
|
*/

return [
    /*
    |--------------------------------------------------------------------------
    | Base Theme
    |--------------------------------------------------------------------------
    |
    | Here you have to specify the name of the base theme.
    | This is where new themes are compied from.
    | The base theme is protected from users.
    |
    */

    'base_theme' => null,

    /*
    |--------------------------------------------------------------------------
    | Admin User Credentials
    |--------------------------------------------------------------------------
    |
    | Here you can specify login credentials for the backend admin user.
    |
    | CHANGE THESE VALUES.
    |
    */

    'admin' => [
        'login'    => 'admin',
        'password' => 'admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Demo User Permissions
    |--------------------------------------------------------------------------
    |
    | Here you can specify which permissions will be granted to the demo users.
    | You should only give demo users access to the plugin you will be
    | demoing, never any admin kind of permissions.
    |
    */

    'permissions' => [
        // 'author.plugin.permission',
        // 'author.plugin.other_permission',
    ],

    /*
    |--------------------------------------------------------------------------
    | Demo Username Length
    |--------------------------------------------------------------------------
    |
    | Here you can specify the length of the demo usernames.
    |
    */

    'username_length' => 10,

    /*
    |--------------------------------------------------------------------------
    | Lock Page HTML
    |--------------------------------------------------------------------------
    |
    | Here you can specify the HTML to display on the page that shows up when
    | the site is being reset.
    |
    */

    'lock_page' => '<h1>Please wait.</h1><p>Give us a few moments to give this demo a fresh restart :-)</p>',

    /*
    |--------------------------------------------------------------------------
    | Custom Provisioners
    |--------------------------------------------------------------------------
    |
    | Here you can specify which provisioners to run after every reset.
    |
    | @see README.md
    |
    */

    'provisioners' => [
        // '\Author\Plugin\DemoProvisioners\SomeProvisioner',
        // '\Author\Plugin\DemoProvisioners\OtherProvisioner',
        // '\Other\Namespace\SomeProvisioner',
    ],

    /*
    |--------------------------------------------------------------------------
    | Reset Schedule Interval
    |--------------------------------------------------------------------------
    |
    | Here you can enable automatic resetting. Disabled by default.
    | You can also manually run the demomanager:reset artisan command to run
    | the reset process.
    |
    | This requires you to set up scheduling.
    | @see http://laravel.com/docs/5.0/artisan#scheduling-artisan-commands
    |
    | All the available options are listed below.
    |
    */

    'reset_interval' => false,
    // 'reset_interval' => 'hourly',
    // 'reset_interval' => 'daily',
    // 'reset_interval' => 'twiceDaily',
    // 'reset_interval' => 'weekly',
    // 'reset_interval' => 'monthly',
    // 'reset_interval' => 'yearly',
    // 'reset_interval' => 'everyFiveMinutes',
    // 'reset_interval' => 'everyTenMinutes',
    // 'reset_interval' => 'everyThirtyMinutes',

    // 'reset_interval' => '* * * * *',
    // You can also do custom CRON expressions.

    'provisioners' => [
        // '\Author\Plugin\DemoProvisioners\SomeProvisioner',
        // '\Author\Plugin\DemoProvisioners\OtherProvisioner',
        // '\Other\Namespace\SomeProvisioner',
    ],

    /*
    |--------------------------------------------------------------------------
    | User Limit
    |--------------------------------------------------------------------------
    |
    | Here you can specify the maximum number of users (and theme copies)
    | that can exist at the same time.
    |
    | When the limit is reached, you can specify one of the following actions:
    |
    |   - 'reset'        (Default) Runs the reset process automatically.
    |
    |   - 'nothing'      The base theme will be used, but the user will not
    |                    have access to the backend.
    |
    |   - 'maintenance'  Display a page with the file name 'maintenance'
    |                    in the base theme.
    |
    */

    'limit' => 500,

    'limit_action' => 'reset',
];
