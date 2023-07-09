<?php

namespace Helious\SeatBeacons\Notifications;

use Illuminate\Notifications\Messages\SlackAttachmentField;
use Illuminate\Notifications\Messages\SlackMessage;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Seat\Notifications\Notifications\AbstractNotification;
use Seat\Notifications\Traits\NotificationTools;

/**
 * Class StuctureWarnings.
 *
 * @package Seat\Kassie\Calendar\Notifications
 */
class StuctureWarnings extends AbstractNotification
{
    use NotificationTools;
    
    private $message;
    
    public function __construct($message)
    {
        $this->message = $message;
    }

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
            ->from('SeAT Beacons')
            ->content('**Structure Data**' . "\n" . $this->message);
    }
}