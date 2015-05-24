Plugin for [OctoberCMS](https://octobercms.com/) which helps you set up an interactive demo of your new plugin in 1-2-3.

**Why a demo?** A live demo makes it really easy for users to evaluate the quality of a plugin without having to install and configure it first. This is great for free plugins, as it may increase the number of downloads and maybe even donations. **But** a live demo shines even brighter when it comes to paid plugins, where installing and testing it beforehand isn't even an option.

If you're selling a paid plugin without providing a live demo, you're surely doing something wrong. Time to change that!

> **Note:** This plugin is only made to be consumed by developers. If you do not know what you are doing, stay clear!

## Installation

    $ php artisan plugin:install Krisawzm.DemoManager

For security reasons, I **only** recommend setting up a demo site with this plugin in closed enviroments where this site is the only site on the server. You can easily and cheaply achieve this by using a service like [DigitalOcean](https://www.digitalocean.com/) or [Linode](https://www.linode.com/) to create a VPS.

## Features

### Closed demo enviroments

When a new person hits your demo site, a new backend user will be created and signed in - automatically. No more demo usernames and passwords!

After that, a copy of the base theme will be made and named to correspond with the new user. Now, when the user views the demo site, he will be viewing his own version. If he makes any changes in the backend, only he will see those changes.

Keep in mind that if the plugin you're demoing relies on models or something similar, some of those things will be shared across demo users. Only files in the `themes` directory will be seperate for each demo user. Which is one of the reasons why occasionally doing a [reset](#reset) is a good idea.

### Reset

This plugin provides a really simple way to reset everything (cache, migrations and old themes).

Simply run

    $ php artisan demomanager:reset

to reset everything on the site.

Or set up **scheduling** by setting the `reset_interval` config to for example `daily` - this will automatically run the reset process every day. This requires you to set up [Scheduling](http://laravel.com/docs/5.0/artisan#scheduling-artisan-commands).

## Configuration

> **Note:** This documentation does **not** cover everything in the configuration. I highly recommend reading the `config/config.php` file for details.

### `base_theme`

The base theme is the "original" theme. Demo user themes will be copied from this. Demo users will never be able to modify this theme.

### `admin`

You can easily change the admin login and password by changing `admin.login` and `admin.password`.

> **Note:** The admin login and password should **ALWAYS** be changed!

### `permissions`

By default, demo users are granted no permissions. It's important you specify which permissions they should be granted if the plugin you're demoing require permissions.

    'permissions' => [
        'rainlab.pages.manage_pages',
        'rainlab.pages.access_snippets'
    ],

The above example will grant demo users access to manage pages and access snippets in the [Static Pages](http://octobercms.com/plugin/rainlab-pages) plugin.

### `lock_page`

Before the reset process begins, a file named `.krisawzm-demomanage-lock` is put in the root directory of your project. Whenever this file is present, users will be shown a `"Please wait"` page.

> **Note:** The lockfile is only removed if the reset process runs without **any** errors. If an error occurs, you will have to fix the error, remove the lockfile and re-run the reset command.

### `provisioners`

Provisioners are used to feed the system additional data every time it has been reset. Provisioners can be thought of as seeders.

Let's say the plugin you're demoing requires an API key to interact with a third-party service, which is stored in a [Settings Model](https://octobercms.com/docs/plugin/settings#database-settings), you can create a provisioner to provide the API key:

``` php
<?php namespace Author\Plugin\DemoProvisioners;

use Krisawzm\DemoManager\Classes\DemoProvisionerInterface;
use Author\Plugin\Models\Settings;

class SettingsProvisioner implements DemoProvisionerInterface
{
    public function run()
    {
        Settings::set('api_key', 'some-random-api-key');
    }
}
```

Register the `SettingsProvisioner` in the [Configuration](#configuration) by referencing the fully qualifying class name:

    'provisioners' => [
        '\Author\Plugin\DemoProvisioners\SettingsProvisioner'
    ]

I recommend creating a directory named `demoprovisioners` (note: should be lower case) in your plugin's root directory where you can keep your provision scripts.

> **Note:** You should add a `.gitignore` file in `demoprovisioners` so it doesn't get pushed into your git repository.
>
> Optionally, you could store the provision scripts elsewhere (just remember to change the namespace.)

### `limit`

This feature enabled you to limit the number of users (or copies of the base theme) that are allowed at one time. The default is 500, but you should probably adjust this depending on resources such as disk space.

### `limit_action`

When the limit is reached, you can select how it should be handled:

- `reset`: (Default) Runs the reset process automatically.
- `nothing`: The base theme will be used, but the user will not have access to the backend.
- `maintenance`: Display a page with the file name **maintenance** in the base theme.

## License

[MIT](http://opensource.org/licenses/MIT) © 2015 [Kristoffer Alfheim](https://github.com/krisawzm)
