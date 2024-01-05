<?php

namespace Helious\SeatBeacons\Notifications;

use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

/**
 * Class StuctureWarnings.
 *
 * @package Seat\Kassie\Calendar\Notifications
 */
class StuctureWarnings extends AbstractDiscordNotification
{   
    private $message;
    
    public function __construct($message)
    {
        $this->message = $message;
    }

 /**
     * @param  DiscordMessage  $message
     * @param  $notifiable
     */
    public function populateMessage(DiscordMessage $message, $notifiable)
    {        
        $message
            ->from('SeAT Beacons')
            ->content('**Structure Data**' . PHP_EOL . $this->message);
    }
}