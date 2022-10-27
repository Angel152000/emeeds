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
            switch (auth()->user()->tipo_user) 
            {
                case 1:
                    
                    $event->menu->addAfter('menu', [
                        'text'        => 'Calendario',
                        'url'         => '/home/calendario',
                        'icon'        => 'fa fa-calendar',
                        'label_color' => 'success',
                        'key'         => 'calendario',
                    ]);

                    $event->menu->addAfter('calendario', [
                        'text'        => 'Atenciones',
                        'url'         => '#',
                        'icon'        => 'fa-solid fa-laptop-medical',
                        'label_color' => 'success',
                        'key'         => 'atenciones',
                    ]);

                    $event->menu->addAfter('atenciones', [
                        'text'        => 'Pacientes',
                        'url'         => '#',
                        'icon'        => 'fa-solid fa-bed-pulse',
                        'label_color' => 'success',
                        'key'         => 'pacientes',
                    ]);

                    $event->menu->addAfter('pacientes', [
                        'text'    => 'Cuenta',
                        'icon'    => 'fas fa-fw fa-user',
                        'key'     => 'cuenta',
                        'submenu' => [
                            [
                                'text' => 'Perfil',
                                'url'  => '#',
                                'icon' => 'fa-regular fa-address-card',
                            ],
                            [
                                'text' => 'Configuración',
                                'url'  => '#',
                                'icon' => 'fa-solid fa-gear',
                            ],
                            [
                                'text'    => 'Zoom',
                                'url'     => '/home/account/zoom',
                                'icon' => 'fa-solid fa-video',
                            ]
                        ]
                    ]);
                break;
                case 2:

                    $event->menu->addAfter('menu', [
                        'text'        => 'Especialidades',
                        'url'         => '/home/especialidades',
                        'icon'        => 'fa-solid fa-stethoscope',
                        'label_color' => 'success',
                        'key'         => 'especialidades',
                    ]);

                    $event->menu->addAfter('especialidades', [
                        'text'        => 'Especialistas',
                        'url'         => '#',
                        'icon'        => 'fa fa-duotone fa-user-nurse',
                        'label_color' => 'success',
                        'key'         => 'especialistas',
                    ]);

                    $event->menu->addAfter('especialistas', [
                        'text'        => 'Contabilidad',
                        'url'         => '#',
                        'icon'        => 'fa-solid fa-money-bill-transfer',
                        'label_color' => 'success',
                        'key'         => 'contabilidad',
                    ]);

                    $event->menu->addAfter('contabilidad', [
                        'text'    => 'Cuenta',
                        'icon'    => 'fas fa-fw fa-user',
                        'key'     => 'cuenta',
                        'submenu' => [
                            [
                                'text' => 'Ficha',
                                'url'  => '#',
                                'icon' => 'fa-regular fa-address-card',
                            ],
                            [
                                'text' => 'Configuración',
                                'url'  => '#',
                                'icon' => 'fa-solid fa-gear',
                            ]
                        ]
                    ]);

                break;
                case 3:
                    $event->menu->addAfter('menu', [
                        'text'        => 'Calendario',
                        'url'         => '#',
                        'icon'        => 'fa fa-calendar',
                        'label_color' => 'success',
                        'key'         => 'calendario',
                    ]);

                    $event->menu->addAfter('calendario', [
                        'text'        => 'Atenciones',
                        'url'         => '#',
                        'icon'        => 'fa-solid fa-laptop-medical',
                        'label_color' => 'success',
                        'key'         => 'atenciones',
                    ]);

                    $event->menu->addAfter('atenciones', [
                        'text'    => 'Cuenta',
                        'icon'    => 'fas fa-fw fa-user',
                        'key'     => 'cuenta',
                        'submenu' => [
                            [
                                'text'        => 'Ficha médica',
                                'url'         => '#',
                                'icon'        => 'fa-solid fa-hospital-user',
                                'label_color' => 'success',
                                'key'         => 'atenciones',
                            ],
                            [
                                'text' => 'Configuración',
                                'url'  => '#',
                                'icon' => 'fa-solid fa-gear',
                            ]
                        ]
                    ]);
                break;
                default:
                break;
            }
        });
    }
}
