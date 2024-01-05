<?php

namespace Helious\SeatBeacons\Notifications;

use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Notifications\AbstractNotification;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

class StuctureWarnings extends AbstractDiscordNotification implements ShouldQueue
{
    use SerializesModels;

    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @param DiscordMessage $message
     * @param $notifiable
     * @return void
     */
    protected function populateMessage(DiscordMessage $message, $notifiable)
    {
        $message->from('SeAT Beacons');

        // handle max character count for discord
        if (strlen($this->message) > 2000) {
            $content = 'Message too long to send to Discord. Please check the SeAT Beacons page for more information.';
        } else {
            $content = PHP_EOL . $this->message;
        }

        $message->content($content);

        return $message;

    }
}
