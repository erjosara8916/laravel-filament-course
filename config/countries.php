<?php

return [
    'insert_activations_only' => true,
    'countries' => [
        'activation' => [
            'default' => true,
            'only' => [
                'iso2' => ['SV'],
                'iso3' => ['SLV'],
            ],
            'except' => [
                'iso2' => [],
                'iso3' => [],
            ],
        ],
    ],
];
