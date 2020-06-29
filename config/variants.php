<?php

return [
    'Free' => [
        'max_templates' => 1,
        'monthly_requests' => 100,
        'history_access' => false,
    ],
    'Personal' => [
        'max_templates' => 5,
        'monthly_requests' => 500,
        'history_access' => true,
    ],
    'Professional' => [
        'max_templates' => 25,
        'monthly_requests' => 2500,
        'history_access' => true,
    ],
];
