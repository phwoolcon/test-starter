<?php

return [
    'queues' => [
        'default_queue' => [
            'options' => [
                'default' => 'phwoolcon-test',
            ],
        ],
    ],

    'connections' => [
        'beanstalkd' => [
            'read_timeout' => 1,
        ],
    ],
];
