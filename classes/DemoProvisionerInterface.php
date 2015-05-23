<?php namespace Krisawzm\DemoManager\Classes;

interface DemoProvisionerInterface
{
    /**
     * Run provision tasks.
     * MUST always return a boolean.
     *
     * @return bool Indicates success.
     */
    public function run();
}
