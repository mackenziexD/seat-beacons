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

        // handle max character count for discord
        if(strlen($this->message) > 2000) {
            return (new SlackMessage)
                ->error()
                ->from('SeAT Beacons')
                ->content('**Structure Data**' . PHP_EOL . 'Message too long to send to slack. Please check the SeAT Beacons page for more information.');
        }

        return (new SlackMessage)
            ->success()
            ->from('SeAT Beacons')
            ->content('**Structure Data**' . PHP_EOL . $this->message);
    }
}