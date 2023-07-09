<?php

namespace Helious\SeatBeacons\Console;

use Illuminate\Console\Command;
use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Seat\Eveapi\Models\Corporation\CorporationStructureService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Helious\SeatBeacons\Notifications\StuctureWarnings;


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
        $structures = CorporationStructure::where('type_id', '35840')->get();
        $structureList = "";
        $structures->each(function ($structure) use (&$structureList) {
            $services = $structure->services;
            $services->each(function ($service) use ($structure, &$structureList) {
                if($service->state === 'online') {
                    $fuel_expires = Carbon::parse($structure->fuel_expires);
                    $days_left = $fuel_expires->diffInDays();
                    if($days_left <= 7) {
                        $structureList .= $structure->info->name . " " . $days_left . " days left\n";
                    }
                } else {
                    $structureList .= $structure->info->name . " offline\n";
                }
            });
        });
        Notification::send($structureList, new StuctureWarnings());
    }
}