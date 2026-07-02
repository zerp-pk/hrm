<?php

namespace Zerp\Hrm\Providers;

use App\Events\DefaultData;
use Zerp\Hrm\Listeners\DataDefault;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\GivePermissionToRole;
use Zerp\Hrm\Listeners\GiveRoleToPermission;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DefaultData::class => [
            DataDefault::class
        ],
         GivePermissionToRole::class => [
            GiveRoleToPermission::class,
        ],
    ];
}
