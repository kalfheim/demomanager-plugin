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
    | @todo
    |
    */

    'base_theme' => 'demo',

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
    | @todo
    |
    */

    'permissions' => [
        // 'author.plugin.permission',
        // 'author.plugin.other_permission',
    ],

    /*
    |--------------------------------------------------------------------------
    | Lock Page HTML
    |--------------------------------------------------------------------------
    |
    | @todo
    |
    */

    'lock_page' => '<h1>Please wait.</h1><p>Give us a few moments to reset this demo.</p>',

    /*
    |--------------------------------------------------------------------------
    | Custom Provisioners
    |--------------------------------------------------------------------------
    |
    | @todo
    |
    */

    'provisioners' => [
        // '\Author\Plugin\Classes\DemoProvisioner',
    ],
];
