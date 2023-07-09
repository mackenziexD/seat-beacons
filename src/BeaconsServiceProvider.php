<?php

namespace Helious\SeatBeacons;

use Seat\Services\AbstractSeatPlugin;
use Illuminate\Console\Scheduling\Schedule;
use Helious\SeatBeacons\Console\CheckBeaconFuel;

class BeaconsServiceProvider extends AbstractSeatPlugin
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/seat-beacons.php', 'seat-beacons');
        $this->mergeConfigFrom(__DIR__ . '/Config/seat-beacons.sidebar.php', 'package.sidebar.tools.entries');
        $this->registerPermissions(__DIR__ . '/Config/seat-beacons.permissions.php', 'seat-beacons');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->addCommands();

        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'seat-beacons');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->mergeConfigFrom(
            __DIR__ . '/Config/notifications.alerts.php', 'notifications.alerts'
        );
        
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('beacons:fuel')->dailyAt('11:30');
        });
    }

    
    private function addCommands() 
    {
        $this->commands([
            CheckBeaconFuel::class,
        ]);
    }

    /**
     * Get the package's routes.
     *
     * @return string
     */
    protected function getRouteFile()
    {
        return __DIR__.'/routes.php';
    }

    

    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @return string
     * @example SeAT Web
     *
     */
    public function getName(): string
    {
        return 'SeAT Beacons';
    }

    /**
     * Return the plugin repository address.
     *
     * @example https://github.com/eveseat/web
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/mackenziexD/seat-beacons';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @return string
     * @example web
     *
     */
    public function getPackagistPackageName(): string
    {
        return 'seat-beacons';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @return string
     * @example eveseat
     *
     */
    public function getPackagistVendorName(): string
    {
        return 'helious';
    }
}