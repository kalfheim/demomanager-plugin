<?php
namespace Krisawzm\DemoManager\Tests;

use Config;
use PluginTestCase;
use Backend\Models\User;
// use Krisawzm\DemoManager\Classes\DemoManager;
use Krisawzm\DemoManager\Classes\DemoAuth;
use Krisawzm\DemoManager\Classes\UserCounter;

class DemoManagerTest extends PluginTestCase
{
    public function setUp()
    {
        parent::setUp();

        Config::set('krisawzm.demomanager::base_theme', 'test');
        Config::set('krisawzm.demomanager::username_length', 10);
    }

    public function testDemoAuth()
    {
        $this->assertNotEmpty(DemoAuth::instance()->theme);

        $this->assertEquals(2, User::count());
    }
}
