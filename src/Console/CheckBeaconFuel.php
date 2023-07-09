<?php

namespace Helious\SeatBeacons\Console;

use Illuminate\Console\Command;
use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Seat\Eveapi\Models\Corporation\CorporationStructureService;
use Carbon\Carbon;
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
        $structures = CorporationStructure::where('type_id', '35840')->get();
        $structureMessage = '';

        foreach ($structures as $structure) {
            $services = $structure->services->first();
            echo $structure->info->name . " " . $services->state . "\n";
            if ($services->state === 'online') {
                $fuel_expires = Carbon::parse($structure->fuel_expires);
                $days_left = $fuel_expires->diffInDays();
                if ($days_left <= 7) {
                    $structureMessage .= $structure->info->name . ": " . $days_left . " Days Left\n";
                }
            } else {
                $structureMessage .= $structure->info->name . ": OFFLINE\n";
            }

        }

        // dont send empty message
        if($structureMessage === '') return;
        
        Notification::send(new StuctureWarnings($structureMessage));
    }
}