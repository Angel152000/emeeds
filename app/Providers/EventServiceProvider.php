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
            if(!empty(auth()->user()->tipo_user))
            {
                switch (auth()->user()->tipo_user) 
                {
                    case 1:
                        
                        /*
                        $event->menu->addAfter('menu', [
                            'text'        => 'Calendario',
                            'url'         => '/home/calendario',
                            'icon'        => 'fa fa-calendar',
                            'label_color' => 'success',
                            'key'         => 'calendario',
                        ]);
                        */

                        $event->menu->addAfter('menu', [
                            'text'        => 'Atenciones',
                            'url'         => '/home/atenciones/especialista',
                            'icon'        => 'fa-solid fa-laptop-medical',
                            'label_color' => 'success',
                            'key'         => 'atenciones',
                        ]);

                        $event->menu->addAfter('atenciones', [
                            'text'        => 'Pacientes',
                            'url'         => '/home/pacientes',
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
                                    'text' => 'Configuración',
                                    'url'  => '/home/configuracion/especialista',
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
                            'text'        => 'Horarios',
                            'url'         => '/home/horarios',
                            'icon'        => 'fa fa-calendar',
                            'label_color' => 'success',
                            'key'         => 'horarios',
                        ]);

                        $event->menu->addAfter('horarios', [
                            'text'        => 'Especialistas',
                            'url'         => '/home/especialistas',
                            'icon'        => 'fa fa-duotone fa-user-nurse',
                            'label_color' => 'success',
                            'key'         => 'especialistas',
                        ]);

                        $event->menu->addAfter('especialistas', [
                            'text'        => 'Atenciones',
                            'url'         => '/home/atenciones/establecimiento',
                            'icon'        => 'fa-solid fa-laptop-medical',
                            'label_color' => 'success',
                            'key'         => 'atenciones',
                        ]);

                        $event->menu->addAfter('atenciones', [
                            'header' => 'Cuenta', 
                            'key'    => 'cuenta'
                        ]);

                        $event->menu->addAfter('cuenta', [
                            'text'        => 'Configuración',
                            'url'         => '/home/configuracion/establecimiento',
                            'icon'        => 'fa-solid fa-gear',
                            'label_color' => 'success',
                            'key'         => 'contabilidad',
                        ]);

                    break;
                    case 3:
                        $event->menu->addAfter('menu', [
                            'text'        => 'Atenciones',
                            'url'         => '/home/atenciones/paciente',
                            'icon'        => 'fa-solid fa-laptop-medical',
                            'label_color' => 'success',
                            'key'         => 'atenciones',
                        ]);

                        $event->menu->addAfter('atenciones', [
                            'header' => 'Cuenta', 
                            'key' => 'cuenta'
                        ]);

                        $event->menu->addAfter('cuenta', [
                            'text'        => 'Ficha médica',
                            'url'         => '/home/ficha/medica',
                            'icon'        => 'fa-solid fa-hospital-user',
                            'label_color' => 'success',
                            'key'         => 'fichamedica',
                        ]);

                        $event->menu->addAfter('fichamedica', [
                            'text'        => 'Configuración',
                            'url'         => '/home/configuracion/paciente',
                            'icon'        => 'fa-solid fa-gear',
                            'label_color' => 'success',
                            'key'         => 'config',
                        ]);

                    break;
                    default:
                    break;
                }
            }
            else
            {
                return view('errors.404');
            }
        });
    }
}
