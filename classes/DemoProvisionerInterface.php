<?php
namespace Krisawzm\DemoManager\Classes;

interface DemoProvisionerInterface
{
    /**
     * Run provision tasks.
     *
     * @return bool Indicates success.
     */
    public function run();
}
