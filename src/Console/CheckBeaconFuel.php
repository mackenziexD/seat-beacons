<?php

namespace Helious\SeatBeacons\Console;

use Illuminate\Console\Command;
use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Seat\Eveapi\Models\Corporation\CorporationStructureService;
use Carbon\Carbon;
use Seat\Notifications\Models\NotificationGroup;
use Illuminate\Support\Facades\Notification;
use Helious\SeatBeacons\Notifications\StuctureWarnings;

use Seat\Web\Models\User;


/**
 * Class RemindOperation.
 *
 * @package Seat\Kassie\Calendar\Commands
 */
class CheckBeaconFuel extends Command
{
    /**
     * @var string
     */
    protected $signature = 'beacons:fuel';

    /**
     * @var string
     */
    protected $description = 'Checks for beacons offline or low on fuel.';

    /**
     * Process the command.
     */
    public function handle()
    {
        \Log::error('Checking for beacons offline or low on fuel.');
        $structures = CorporationStructure::where('type_id', '35840')->get();
        $structureMessage = '';

        foreach ($structures as $structure) {
            $services = $structure->services->first();

            if ($services->state === 'online') {
                $fuel_expires = Carbon::parse($structure->fuel_expires);
                $days_left = $fuel_expires->diffInDays();
                if ($days_left <= 7) {
                    $structureMessage .= '`'. $structure->info->name . ': ' . $days_left . ' Days Left `'. PHP_EOL;
                }
            } else {
                $structureMessage .= '`'. $structure->info->name . ': OFFLINE `'. PHP_EOL;
            }

        }

        // dont send empty message
        if($structureMessage === '') return;

        // detect handlers setup for the current notification
        $handlers = config('notifications.alerts.seat_beacons_warnings.handlers', []);

        // retrieve routing candidates for the current notification
        $routes = $this->getRoutingCandidates();

        // in case no routing candidates has been delivered, exit
        if ($routes->isEmpty())
            return;

        // attempt to enqueue a notification for each routing candidates
        $routes->each(function ($integration) use ($handlers, $structureMessage) {
            if (array_key_exists($integration->channel, $handlers)) {

                // extract handler from the list
                $handler = $handlers[$integration->channel];

                // enqueue the notification
                Notification::route($integration->channel, $integration->route)
                    ->notifyNow(new $handler($structureMessage));
            }
        });

    }

    /**
     * Provide a unique list of notification channels (including driver and route).
     *
     * @return \Illuminate\Support\Collection
     */
    private function getRoutingCandidates()
    {
        $settings = NotificationGroup::with('alerts')
            ->whereHas('alerts', function ($query) {
                $query->where('alert', 'seat_beacons_warnings');
            })->get();

        $routes = $settings->map(function ($group) {
            return $group->integrations->map(function ($channel) {

                // extract the route value from settings field
                $settings = (array) $channel->settings;
                $key = array_key_first($settings);
                $route = $settings[$key];

                // build a composite object built with channel and route
                return (object) [
                    'channel' => $channel->type,
                    'route' => $route,
                ];
            });
        });

        return $routes->flatten()->unique(function ($integration) {
            return $integration->channel . $integration->route;
        });
    }
}