<?php

return [
    'seat_beacons_warnings' => [
        'label' => 'Seat Beacons Warnings',
        'handlers' => [
            'slack' => \Helious\SeatBeacons\Notifications\StuctureWarnings::class,
        ],
    ]
];