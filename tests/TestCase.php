<?php

namespace Zerp\Hrm\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Zerp\Hrm\Providers\HrmServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [HrmServiceProvider::class];
    }
}
