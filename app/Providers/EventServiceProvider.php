<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) 
        {
            /*
            switch (auth()->user()->id) 
            {
                case 1:
                    $event->menu->addAfter('menu', [
                        'key' => 'account_settings_profile',
                        'text' => 'Profile',
                        'url' => 'account/edit/profile',
                    ]);
                break;
                case 2:

                break;

                case 3:

                break;
                default:

                break;
            }
            */
        });
    }
}
