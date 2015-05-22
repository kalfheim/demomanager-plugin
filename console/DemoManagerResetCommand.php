<?php namespace Krisawzm\DemoManager\Console;

use Illuminate\Console\Command;
use Krisawzm\DemoManager\Classes\DemoManager;

class DemoManagerResetCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'demomanager:reset';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Reset the site.';

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function fire()
    {
        DemoManager::instance()->resetEverything();

        $this->info('Done.');
    }
}
