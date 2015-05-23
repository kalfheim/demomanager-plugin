<?php namespace Krisawzm\DemoManager\Classes;

interface DemoProvisionerInterface
{
    /**
     * Run provision tasks.
     * MUST always return a boolean.
     *
     * @return bool Indicates whether there was an error.
     */
    public function run();
}
