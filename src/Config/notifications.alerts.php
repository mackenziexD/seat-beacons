<?php

return [
    'seat_beacons_warnings' => [
        'label' => 'Seat Beacons Warnings',
        'handlers' => [
            'discord' => \Helious\SeatBeacons\Notifications\StuctureWarnings::class,
        ],
    ]
];