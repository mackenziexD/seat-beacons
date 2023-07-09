<?php

namespace Helious\SeatBeacons\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

/**
 * Class StuctureWarnings.
 *
 * @package Seat\Kassie\Calendar\Notifications
 */
class StuctureWarnings extends Notification
{
    use Queueable;

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function toSlack($notifiable)
    {

        return (new SlackMessage)
            ->success()
            ->from('SeAT Beacons', ':warning:')
            ->content('Beacon Fuel Warning');
    }
}