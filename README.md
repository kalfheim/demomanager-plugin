Plugin for [OctoberCMS](https://octobercms.com/) which helps you set up an interactive demo of your new plugin in 1-2-3.

**Why a demo?** A live demo makes it really easy for users to evaluate the quality of a plugin without having to install and configure it first. This is great for free plugins, as it may increase the number of downloads and maybe even donations. **But** a live demo shines even brighter when it comes to paid plugins, where installing and testing it beforehand isn't even an option.

If you're selling a paid plugin without providing a live demo, you're surely doing something wrong. Time to change that!

> **Note:** This plugin is only made to be consumed by developers. If you do not know what you are doing, stay clear!

## Installation

    $ php artisan plugin:install Krisawzm.DemoManager

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

I recommend creating a directory named `DemoProvisioners` in your plugin's root directory where you can keep your provision scripts.

> **Note:** You should add a `.gitignore` file in `DemoProvisioners` so it doesn't get pushed into your git repository.
>
> Optionally, you could store the provision scripts elsewhere (just remember to change the namespace.)
